<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Events\UserTyping;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the chat interface
     */
    public function show()
    {
        return view('chat.index');
    }

    /**
     * Get all chats for the authenticated user (API)
     */
    public function getChats(): JsonResponse
    {
        try {
            $user = Auth::user();

            $chats = $user->chats()
                ->with(['participants', 'lastMessage.user'])
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(function ($chat) use ($user) {
                    $otherParticipant = $chat->getOtherParticipant($user);
                    $unreadCount = $chat->getUnreadCountForUser($user);

                    return [
                        'id' => $chat->id,
                        'name' => $chat->type === 'private' ? $otherParticipant->name : $chat->name,
                        'type' => $chat->type,
                        'avatar' => $chat->type === 'private' ? $otherParticipant->profile_photo_url : $chat->avatar,
                        'last_message' => $chat->lastMessage->first() ? [
                            'message' => $chat->lastMessage->first()->message,
                            'user_name' => $chat->lastMessage->first()->user->name,
                            'created_at' => $chat->lastMessage->first()->created_at->toISOString(),
                        ] : null,
                        'unread_count' => $unreadCount,
                        'participants_count' => $chat->participants->count(),
                        'updated_at' => $chat->updated_at->toISOString(),
                    ];
                });

            return response()->json([
                'success' => true,
                'chats' => $chats,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching chats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Errore nel caricamento delle chat',
            ], 500);
        }
    }

    /**
     * Get messages for a specific chat
     */
    public function getMessages(Request $request, int $chatId): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check if user is participant in this chat
            $chat = Chat::with('participants')->findOrFail($chatId);
            if (!$chat->hasParticipant($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorizzato',
                ], 403);
            }

            // Mark messages as read
            $chat->participants()
                ->where('user_id', $user->id)
                ->update(['last_read_at' => now()]);

            // Get messages with pagination
            $perPage = $request->get('per_page', 50);
            $messages = $chat->messages()
                ->with('user')
                ->notDeleted()
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $formattedMessages = $messages->getCollection()->map(function ($message) use ($user) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'type' => $message->type,
                    'file_path' => $message->file_path,
                    'file_name' => $message->file_name,
                    'file_size' => $message->file_size,
                    'mime_type' => $message->mime_type,
                    'is_edited' => $message->is_edited,
                    'is_from_me' => $message->isFromUser($user),
                    'user' => [
                        'id' => $message->user->id,
                        'name' => $message->user->name,
                        'avatar' => $message->user->profile_photo_url,
                    ],
                    'created_at' => $message->created_at->toISOString(),
                    'updated_at' => $message->updated_at->toISOString(),
                ];
            })->reverse()->values();

            return response()->json([
                'success' => true,
                'messages' => $formattedMessages,
                'pagination' => [
                    'current_page' => $messages->currentPage(),
                    'last_page' => $messages->lastPage(),
                    'per_page' => $messages->perPage(),
                    'total' => $messages->total(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching messages: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Errore nel caricamento dei messaggi',
            ], 500);
        }
    }

    /**
     * Send a message to a chat
     */
    public function sendMessage(Request $request, int $chatId): JsonResponse
    {
        try {
            $user = Auth::user();

            // Validate request
            $validator = Validator::make($request->all(), [
                'message' => 'required|string|max:1000',
                'type' => 'sometimes|string|in:text,image,file',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dati non validi',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Check if user is participant in this chat
            $chat = Chat::with('participants')->findOrFail($chatId);
            if (!$chat->hasParticipant($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorizzato',
                ], 403);
            }

            // Create message
            $message = ChatMessage::create([
                'chat_id' => $chatId,
                'user_id' => $user->id,
                'message' => $request->message,
                'type' => $request->type ?? 'text',
            ]);

            // Load user relationship
            $message->load('user');

            // Broadcast event
            Log::info('Broadcasting new message', [
                'chat_id' => $chatId,
                'message_id' => $message->id,
                'user_id' => $user->id
            ]);

            try {
                broadcast(new NewChatMessage($message))->toOthers();
                Log::info('Event broadcasted successfully');
            } catch (\Exception $e) {
                Log::error('Error broadcasting message: ' . $e->getMessage());
            }

            // Update chat timestamp
            $chat->touch();

            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'type' => $message->type,
                    'is_from_me' => true,
                    'user' => [
                        'id' => $message->user->id,
                        'name' => $message->user->name,
                        'avatar' => $message->user->profile_photo_url,
                    ],
                    'created_at' => $message->created_at->toISOString(),
                    'updated_at' => $message->updated_at->toISOString(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending message: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Errore nell\'invio del messaggio',
            ], 500);
        }
    }

    /**
     * Create a new private chat
     */
    public function createPrivateChat(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            // Validate request
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dati non validi',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $otherUserId = $request->user_id;

            // Check if user is trying to chat with themselves
            if ($user->id === $otherUserId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non puoi creare una chat con te stesso',
                ], 422);
            }

            // Check if chat already exists
            $existingChat = Chat::where('type', 'private')
                ->whereHas('participants', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->whereHas('participants', function ($query) use ($otherUserId) {
                    $query->where('user_id', $otherUserId);
                })
                ->first();

            if ($existingChat) {
                return response()->json([
                    'success' => true,
                    'chat_id' => $existingChat->id,
                    'message' => 'Chat già esistente',
                ]);
            }

            // Create new chat
            DB::beginTransaction();

            $chat = Chat::create([
                'type' => 'private',
                'created_by' => $user->id,
            ]);

            // Add participants
            ChatParticipant::create([
                'chat_id' => $chat->id,
                'user_id' => $user->id,
                'role' => 'member',
            ]);

            ChatParticipant::create([
                'chat_id' => $chat->id,
                'user_id' => $otherUserId,
                'role' => 'member',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'chat_id' => $chat->id,
                'message' => 'Chat creata con successo',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating private chat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Errore nella creazione della chat',
            ], 500);
        }
    }

    /**
     * Search users for new chat
     */
    public function searchUsers(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $query = $request->get('q', '');

            if (strlen($query) < 2) {
                return response()->json([
                    'success' => true,
                    'users' => [],
                ]);
            }

            $users = User::where('id', '!=', $user->id)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('nickname', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })
                ->select(['id', 'name', 'nickname', 'profile_photo'])
                ->limit(10)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'nickname' => $user->nickname,
                        'avatar' => $user->profile_photo_url,
                    ];
                });

            return response()->json([
                'success' => true,
                'users' => $users,
            ]);
        } catch (\Exception $e) {
            Log::error('Error searching users: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Errore nella ricerca utenti',
            ], 500);
        }
    }

    /**
     * Mark user as typing
     */
    public function markTyping(Request $request, int $chatId): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check if user is participant in this chat
            $chat = Chat::findOrFail($chatId);
            if (!$chat->hasParticipant($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorizzato',
                ], 403);
            }

            $isTyping = $request->get('is_typing', true);

            // Broadcast typing event
            broadcast(new UserTyping($chatId, $user->id, $user->name, $isTyping))->toOthers();

            return response()->json([
                'success' => true,
                'message' => $isTyping ? 'Digitazione iniziata' : 'Digitazione terminata',
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking typing: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Errore nell\'aggiornamento dello stato',
            ], 500);
        }
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(Request $request, int $chatId): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check if user is participant in this chat
            $chat = Chat::findOrFail($chatId);
            if (!$chat->hasParticipant($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorizzato',
                ], 403);
            }

            // Mark as read
            $chat->participants()
                ->where('user_id', $user->id)
                ->update(['last_read_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Messaggi segnati come letti',
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Errore nell\'aggiornamento',
            ], 500);
        }
    }
}
