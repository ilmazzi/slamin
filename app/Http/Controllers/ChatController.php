<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatRoom;
use App\Models\User;
use App\Models\ChatParticipant;
use App\Services\OnlineStatusService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Events\ChatMessageSent;
use App\Events\ChatMessageNotification;
use App\Models\ChatMessage;
use App\Services\TypingService;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;


class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $service = app(OnlineStatusService::class);
        $selectedRoom = null;
        $messages = [];
        $selectedContact = null;

        // Se non c'è una chat specifica selezionata, marca tutte le notifiche della chat come lette
        if (!$request->get('room')) {
            Notification::markAllChatNotificationsAsRead($currentUser);
        }

        // Ottieni tutte le chat private dell'utente
        $chatRooms = ChatRoom::where('type', 'private')
            ->whereHas('participants', function($q) use ($currentUser) {
                $q->where('user_id', $currentUser->id);
            })
            ->with(['participants', 'lastMessage'])
            ->get();

        $contacts = [];

        foreach ($chatRooms as $room) {
            // Trova l'altro partecipante (non l'utente corrente)
            $otherParticipant = $room->participants
                ->where('user_id', '!=', $currentUser->id)
                ->first();

            if ($otherParticipant) {
                // Carica l'utente manualmente
                $user = User::find($otherParticipant->user_id);
                if ($user) {
                    $lastMessage = $room->lastMessage;

                    $isOnline = $service->isOnline($user->id);
                    $status = $isOnline ? 'online' : 'offline';

                    \Illuminate\Support\Facades\Log::info('Stato utente chat', [
                        'user_id' => $user->id,
                        'is_online' => $isOnline,
                        'status' => $status
                    ]);

                    $contact = [
                        'id' => $user->id,
                        'chat_room_id' => $room->id,
                        'name' => $user->getDisplayName(),
                        'avatar' => getUserAvatarHtml($user, 'h-45 w-45', 'b-r-50'),
                        'status' => $status,
                        'last_message' => $lastMessage ? $lastMessage->content : '',
                        'last_message_time' => $lastMessage ? $lastMessage->created_at->format('g:iA') : '',
                        'unread_count' => 0 // Per ora semplifico
                    ];

                    $contacts[] = $contact;

                    // Se questa è la chat selezionata, carica i messaggi
                    if ($request->get('room') == $room->id) {
                        $selectedRoom = $room;
                        $selectedContact = $contact;
                        
                        // Marca come lette le notifiche per questa chat
                        Notification::markChatNotificationsAsRead($currentUser, $room->id);
                        
                        $messages = $room->messages()
                            ->with(['sender', 'reactions.user:id,name'])
                            ->orderBy('created_at', 'asc')
                            ->get()
                            ->map(function($message) {
                                // Raggruppa le reazioni per emoji
                                $reactions = [];
                                if ($message->reactions) {
                                    $reactionGroups = $message->reactions->groupBy('reaction');
                                    foreach ($reactionGroups as $emoji => $emojiReactions) {
                                                                                     $reactions[] = [
                                                 'emoji' => $emoji,
                                                 'count' => $emojiReactions->count(),
                                                 'users' => $emojiReactions->map(function($reaction) {
                                                     return [
                                                         'id' => $reaction->user->id,
                                                         'name' => $reaction->user->name,
                                                         'avatar' => \App\Helpers\AvatarHelper::getUserAvatarUrl($reaction->user)
                                                     ];
                                                 })->toArray()
                                             ];
                                    }
                                }

                                return [
                                    'id' => $message->id,
                                    'sender_id' => $message->sender_id,
                                    'content' => $message->content,
                                    'sender_name' => $message->sender->getDisplayName(),
                                    'sender_avatar' => getUserAvatarHtml($message->sender, 'h-45 w-45', 'b-r-50'),
                                    'is_own' => $message->sender_id === auth()->id(),
                                    'time' => $message->created_at->format('g:iA'),
                                    'date' => $message->created_at->format('M j'),
                                    'reactions' => $reactions
                                ];
                            });
                    }
                }
            }
        }

        // Ordina per: online > recent > idle > offline > ultimo messaggio
        usort($contacts, function($a, $b) {
            $priority = ['online' => 4, 'recent' => 3, 'idle' => 2, 'offline' => 1];
            $aPriority = $priority[$a['status']] ?? 0;
            $bPriority = $priority[$b['status']] ?? 0;

            if ($aPriority !== $bPriority) {
                return $bPriority - $aPriority;
            }

            return strtotime($b['last_message_time']) - strtotime($a['last_message_time']);
        });

        return view('chat.index', compact('contacts', 'selectedRoom', 'messages', 'selectedContact'));
    }

    /**
     * Mark chat notifications as read via API
     */
    public function markNotificationsAsRead(Request $request)
    {
        $currentUser = auth()->user();
        $chatRoomId = $request->get('chat_room_id');

        if ($chatRoomId) {
            // Marca come lette le notifiche per una chat specifica
            $count = Notification::markChatNotificationsAsRead($currentUser, $chatRoomId);
        } else {
            // Marca come lette tutte le notifiche della chat
            $count = Notification::markAllChatNotificationsAsRead($currentUser);
        }

        return response()->json([
            'success' => true,
            'message' => "Notifiche marcate come lette",
            'count' => $count
        ]);
    }

    /**
     * Cerca utenti per nuova chat
     */
    public function searchUsers(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $currentUser = auth()->user();

            if (strlen($query) < 2) {
                return response()->json(['users' => []]);
            }

            // Query con esclusione utenti già in chat
            $users = User::where('id', '!=', $currentUser->id)
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('nickname', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })
                ->whereDoesntHave('chatParticipants', function($q) use ($currentUser) {
                    $q->whereHas('chatRoom', function($room) use ($currentUser) {
                        $room->where('type', 'private')
                             ->whereHas('participants', function($p) use ($currentUser) {
                                 $p->where('user_id', $currentUser->id);
                             });
                    });
                })
                ->limit(10)
                ->get(['id', 'name', 'nickname', 'email']);

            $formattedUsers = $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->getDisplayName(),
                    'avatar_html' => getUserAvatarHtml($user, 'h-40 w-40', 'b-r-50'),
                    'status' => 'offline' // Per ora semplifico
                ];
            });

            return response()->json(['users' => $formattedUsers]);
        } catch (\Exception $e) {
            Log::error('Errore in searchUsers: ' . $e->getMessage());
            return response()->json(['error' => 'Errore interno del server'], 500);
        }
    }



    /**
     * Crea una nuova chat privata
     */
    public function createPrivateChat(Request $request, $userId)
    {
        $currentUser = auth()->user();
        $targetUser = User::findOrFail($userId);

        // Verifica se esiste già una chat tra questi utenti
        $existingChat = ChatRoom::where('type', 'private')
            ->whereHas('participants', function($q) use ($currentUser) {
                $q->where('user_id', $currentUser->id);
            })
            ->whereHas('participants', function($q) use ($targetUser) {
                $q->where('user_id', $targetUser->id);
            })
            ->first();

        if ($existingChat) {
            // Se esiste già, reindirizza alla chat esistente
            return response()->json([
                'success' => true,
                'message' => 'Chat già esistente',
                'chat_id' => $existingChat->id,
                'redirect' => route('chat.index')
            ]);
        }

        // Crea nuova chat room
        $chatRoom = ChatRoom::create([
            'name' => "Chat con {$targetUser->getDisplayName()}",
            'type' => 'private',
            'created_by' => $currentUser->id,
            'is_active' => true
        ]);

        // Aggiungi partecipanti
        ChatParticipant::create([
            'chat_room_id' => $chatRoom->id,
            'user_id' => $currentUser->id,
            'role' => 'member',
            'joined_at' => now()
        ]);

        ChatParticipant::create([
            'chat_room_id' => $chatRoom->id,
            'user_id' => $targetUser->id,
            'role' => 'member',
            'joined_at' => now()
        ]);

        // Clear cache
        Cache::forget("chat_search_{$currentUser->id}_*");

        return response()->json([
            'success' => true,
            'message' => 'Chat creata con successo',
            'chat_id' => $chatRoom->id,
            'redirect' => route('chat.index')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created chat message and broadcast it.
     */

     public function store(Request $request, int $room)
     {
         $request->validate([
             'content' => ['required', 'string', 'max:2000'],
         ]);

         $user = Auth::user();

         // ⬇️ QUI la fix: usiamo chat_room_id (non room_id)
         $message = ChatMessage::create([
             'chat_room_id' => $room,
             'sender_id'    => $user->id,
             'content'      => $request->input('content'),
         ]);

         $payload = [
             'room_id'     => $room,                          // per il nome canale
             'message_id'  => $message->id,
             'sender_id'   => $user->id,
             'sender_name' => $user->name,
             'avatar_url'  => \App\Helpers\AvatarHelper::getUserAvatarUrl($user),
             'content'     => $message->content,
             'time'        => now()->format('g:iA'),
         ];

         event(new ChatMessageSent($payload));

         // Notifica per-utente: invia al destinatario quando non è nella pagina chat
         // Trova l'altro partecipante della chat privata
         $roomModel = ChatRoom::with('participants')->find($room);
         if ($roomModel) {
             $recipient = $roomModel->participants
                 ->firstWhere('user_id', '!=', $user->id);
             if ($recipient) {
                 $recipientId = (int) $recipient->user_id;
                 $preview = mb_strimwidth($message->content ?? '', 0, 80, '…');
                 event(new ChatMessageNotification(
                     recipientId: $recipientId,
                     roomId: (int) $room,
                     senderId: (int) $user->id,
                     senderName: (string) ($user->getDisplayName() ?? $user->name ?? 'User'),
                     avatarUrl: (string) \App\Helpers\AvatarHelper::getUserAvatarUrl($user),
                     preview: $preview,
                 ));
             }
         }

         return response()->json(['ok' => true]);
     }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Start typing indicator for a user in a chat room
     */
    public function startTyping(Request $request, int $room)
    {
        $user = Auth::user();
        $typingService = app(TypingService::class);

        try {
            $typingUsers = $typingService->startTyping(
                $room,
                $user->id,
                $user->getDisplayName()
            );

            return response()->json([
                'success' => true,
                'typing_users' => $typingUsers
            ]);
        } catch (\Exception $e) {
            Log::error('Error starting typing', [
                'room' => $room,
                'user' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error starting typing indicator'
            ], 500);
        }
    }

    /**
     * Stop typing indicator for a user in a chat room
     */
    public function stopTyping(Request $request, int $room)
    {
        $user = Auth::user();
        $typingService = app(TypingService::class);

        try {
            $typingUsers = $typingService->stopTyping(
                $room,
                $user->id,
                $user->getDisplayName()
            );

            return response()->json([
                'success' => true,
                'typing_users' => $typingUsers
            ]);
        } catch (\Exception $e) {
            Log::error('Error stopping typing', [
                'room' => $room,
                'user' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error stopping typing indicator'
            ], 500);
        }
    }

    /**
     * Get current typing users in a chat room
     */
    public function getTypingUsers(Request $request, int $room)
    {
        try {
            $typingService = app(TypingService::class);
            $typingUsers = $typingService->getTypingUsers($room);

            return response()->json([
                'success' => true,
                'typing_users' => $typingUsers
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting typing users', [
                'room' => $room,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error getting typing users'
            ], 500);
        }
    }
}
