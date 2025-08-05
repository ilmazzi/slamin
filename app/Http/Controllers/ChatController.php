<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostra la pagina principale delle chat
     */
    public function index()
    {
        $user = Auth::user();

        // Ottieni tutte le chat dell'utente
        $chats = $user->activeChats()
                     ->with(['lastMessage.user', 'participants.user'])
                     ->orderBy('last_message_at', 'desc')
                     ->get();

        // Ottieni il numero di messaggi non letti per ogni chat
        foreach ($chats as $chat) {
            $chat->unread_count = $chat->getUnreadCount($user);
        }

        return view('chat', compact('chats'));
    }

    /**
     * Mostra una chat specifica
     */
    public function show(Chat $chat)
    {
        $user = Auth::user();

        // Verifica che l'utente sia partecipante della chat
        if (!$chat->hasUser($user)) {
            abort(403, 'Non hai accesso a questa chat.');
        }

        // Marca i messaggi come letti
        $chat->markAsRead($user);

        // Ottieni i messaggi con paginazione
        $messages = $chat->messages()
                        ->with(['user'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(50);

        // Ottieni i partecipanti
        $participants = $chat->participants()
                            ->with('user')
                            ->where('is_active', true)
                            ->get();

        return view('chat.show', compact('chat', 'messages', 'participants'));
    }

    /**
     * Crea una nuova chat privata
     */
    public function createPrivate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $otherUser = User::findOrFail($request->user_id);

        // Non può creare chat con se stesso
        if ($user->id === $otherUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Non puoi creare una chat con te stesso.'
            ], 400);
        }

        try {
            // Crea o trova la chat esistente
            $chat = Chat::createPrivate($user, $otherUser);

            return response()->json([
                'success' => true,
                'message' => 'Chat privata creata con successo.',
                'chat_id' => $chat->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nella creazione della chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crea una nuova chat di gruppo
     */
    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        $user = Auth::user();

        try {
            // Crea la chat di gruppo
            $chat = Chat::createGroupChat($user, $request->name, $request->user_ids);

            return response()->json([
                'success' => true,
                'message' => 'Chat di gruppo creata con successo.',
                'chat_id' => $chat->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nella creazione della chat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ottieni i messaggi di una chat (per AJAX)
     */
    public function getMessages(Chat $chat, Request $request)
    {
        $user = Auth::user();

        // Verifica che l'utente sia partecipante della chat
        if (!$chat->hasUser($user)) {
            return response()->json(['error' => 'Accesso negato'], 403);
        }

        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 50);

        $messages = $chat->messages()
                        ->with(['user'])
                        ->orderBy('created_at', 'desc')
                        ->paginate($perPage, ['*'], 'page', $page);

        // Marca come letti se è la prima pagina
        if ($page == 1) {
            $chat->markAsRead($user);
        }

        return response()->json([
            'success' => true,
            'messages' => $messages->items(),
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
            ],
        ]);
    }

    /**
     * Ottieni le chat dell'utente (per AJAX)
     */
    public function getChats()
    {
        $user = Auth::user();

        $chats = $user->activeChats()
                     ->with(['lastMessage.user', 'participants.user'])
                     ->orderBy('last_message_at', 'desc')
                     ->get();

        // Aggiungi il numero di messaggi non letti
        foreach ($chats as $chat) {
            $chat->unread_count = $chat->getUnreadCount($user);
        }

        // Separa le chat private da quelle di gruppo
        $privateChats = $chats->where('type', 'private')->map(function($chat) use ($user) {
            $otherUser = $chat->participants->where('user_id', '!=', $user->id)->first()->user ?? null;
            
            return [
                'id' => $chat->id,
                'type' => $chat->type,
                'unread_count' => $chat->unread_count,
                'last_message' => $chat->lastMessage ? [
                    'message' => $chat->lastMessage->message,
                    'created_at' => $chat->lastMessage->created_at->diffForHumans()
                ] : null,
                'other_user' => $otherUser ? [
                    'id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'avatar_url' => \App\Helpers\AvatarHelper::getUserAvatarUrl($otherUser),
                    'online_status_color' => $otherUser->getOnlineStatusColor(),
                    'online_status_icon' => $otherUser->getOnlineStatusIcon(),
                    'online_status_display' => $otherUser->getOnlineStatusDisplay(),
                    'last_seen_display' => $otherUser->getLastSeenDisplay()
                ] : null
            ];
        });

        $groupChats = $chats->where('type', 'group')->map(function($chat) {
            return [
                'id' => $chat->id,
                'type' => $chat->type,
                'name' => $chat->name,
                'unread_count' => $chat->unread_count,
                'participants_count' => $chat->participants->count(),
                'last_message' => $chat->lastMessage ? [
                    'message' => $chat->lastMessage->message,
                    'created_at' => $chat->lastMessage->created_at->diffForHumans()
                ] : null
            ];
        });

        return response()->json([
            'success' => true,
            'private_chats' => $privateChats,
            'group_chats' => $groupChats
        ]);
    }

    /**
     * Cerca utenti per creare chat private
     */
    public function searchUsers(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $user = Auth::user();
        $query = $request->get('q');

        $users = User::where('id', '!=', $user->id)
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                          ->orWhere('email', 'like', "%{$query}%")
                          ->orWhere('nickname', 'like', "%{$query}%");
                    })
                    ->limit(10)
                    ->get(['id', 'name', 'email', 'nickname', 'profile_photo']);

        // Aggiungi l'URL dell'avatar per ogni utente
        $usersWithAvatars = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nickname' => $user->nickname,
                'avatar_url' => \App\Helpers\AvatarHelper::getUserAvatarUrl($user)
            ];
        });

        return response()->json(['success' => true, 'users' => $usersWithAvatars]);
    }

    /**
     * Silenzia o riattiva una chat
     */
    public function toggleMute(Chat $chat)
    {
        $user = Auth::user();
        $participation = $chat->participants()->where('user_id', $user->id)->first();

        if (!$participation) {
            return response()->json(['error' => 'Non sei partecipante di questa chat'], 403);
        }

        $participation->is_muted = !$participation->is_muted;
        $participation->save();

        return response()->json([
            'muted' => $participation->is_muted,
            'message' => $participation->is_muted ? 'Chat silenziata' : 'Chat riattivata'
        ]);
    }

    /**
     * Esci da una chat
     */
    public function leave(Chat $chat)
    {
        $user = Auth::user();
        $participation = $chat->participants()->where('user_id', $user->id)->first();

        if (!$participation) {
            return back()->with('error', 'Non sei partecipante di questa chat.');
        }

        // Non permettere di uscire se è l'ultimo admin
        if ($participation->isAdmin() && $chat->participants()->admins()->count() <= 1) {
            return back()->with('error', 'Non puoi uscire dalla chat se sei l\'ultimo amministratore.');
        }

        $participation->deactivate();

        // Crea messaggio di sistema
        ChatMessage::createSystemMessage($chat, "{$user->name} ha lasciato la chat.");

        return redirect()->route('chat.index')
                        ->with('success', 'Hai lasciato la chat.');
    }

    /**
     * Elimina una chat (solo per admin)
     */
    public function destroy(Chat $chat)
    {
        $user = Auth::user();
        $participation = $chat->participants()->where('user_id', $user->id)->first();

        if (!$participation || !$participation->canManageParticipants()) {
            abort(403, 'Non hai i permessi per eliminare questa chat.');
        }

        // Elimina tutti i file associati
        $messagesWithFiles = $chat->messages()->whereNotNull('file_path')->get();
        foreach ($messagesWithFiles as $message) {
            if (Storage::disk('public')->exists($message->file_path)) {
                Storage::disk('public')->delete($message->file_path);
            }
        }

        // Elimina la chat (cascade eliminerà partecipanti e messaggi)
        $chat->delete();

        return redirect()->route('chat.index')
                        ->with('success', 'Chat eliminata con successo.');
    }

    /**
     * Gestisci i partecipanti di una chat
     */
    public function participants(Chat $chat)
    {
        $user = Auth::user();
        $participation = $chat->participants()->where('user_id', $user->id)->first();

        if (!$participation || !$participation->canManageParticipants()) {
            abort(403, 'Non hai i permessi per gestire i partecipanti.');
        }

        $participants = $chat->participants()
                            ->with('user')
                            ->where('is_active', true)
                            ->orderBy('role', 'desc')
                            ->orderBy('joined_at', 'asc')
                            ->get();

        return view('chat.participants', compact('chat', 'participants'));
    }

    /**
     * Aggiungi partecipante a una chat
     */
    public function addParticipant(Chat $chat, Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'nullable|in:member,moderator',
        ]);

        $user = Auth::user();
        $participation = $chat->participants()->where('user_id', $user->id)->first();

        if (!$participation || !$participation->canManageParticipants()) {
            return response()->json(['error' => 'Non hai i permessi per aggiungere partecipanti'], 403);
        }

        $newUser = User::findOrFail($request->user_id);

        // Verifica che l'utente non sia già nella chat
        if ($chat->hasUser($newUser)) {
            return response()->json(['error' => 'L\'utente è già nella chat'], 400);
        }

        $role = $request->get('role', 'member');
        $chat->addParticipant($newUser, $role);

        // Crea messaggio di sistema
        ChatMessage::createSystemMessage($chat, "{$newUser->name} è stato aggiunto alla chat.");

        return response()->json(['success' => 'Partecipante aggiunto con successo']);
    }

    /**
     * Rimuovi partecipante da una chat
     */
    public function removeParticipant(Chat $chat, Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $participation = $chat->participants()->where('user_id', $user->id)->first();

        if (!$participation || !$participation->canManageParticipants()) {
            return response()->json(['error' => 'Non hai i permessi per rimuovere partecipanti'], 403);
        }

        $userToRemove = User::findOrFail($request->user_id);
        $userParticipation = $chat->participants()->where('user_id', $userToRemove->id)->first();

        if (!$userParticipation) {
            return response()->json(['error' => 'L\'utente non è nella chat'], 400);
        }

        // Non permettere di rimuovere se stessi
        if ($userToRemove->id === $user->id) {
            return response()->json(['error' => 'Non puoi rimuovere te stesso'], 400);
        }

        // Non permettere di rimuovere l'ultimo admin
        if ($userParticipation->isAdmin() && $chat->participants()->admins()->count() <= 1) {
            return response()->json(['error' => 'Non puoi rimuovere l\'ultimo amministratore'], 400);
        }

        $userParticipation->deactivate();

        // Crea messaggio di sistema
        ChatMessage::createSystemMessage($chat, "{$userToRemove->name} è stato rimosso dalla chat.");

        return response()->json(['success' => 'Partecipante rimosso con successo']);
    }

    /**
     * Cambia ruolo di un partecipante
     */
    public function changeRole(Chat $chat, Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:member,moderator,admin',
        ]);

        $user = Auth::user();
        $participation = $chat->participants()->where('user_id', $user->id)->first();

        if (!$participation || !$participation->canManageParticipants()) {
            return response()->json(['error' => 'Non hai i permessi per cambiare i ruoli'], 403);
        }

        $targetUser = User::findOrFail($request->user_id);
        $targetParticipation = $chat->participants()->where('user_id', $targetUser->id)->first();

        if (!$targetParticipation) {
            return response()->json(['error' => 'L\'utente non è nella chat'], 400);
        }

        $oldRole = $targetParticipation->role;
        $newRole = $request->role;

        // Aggiorna il ruolo
        $targetParticipation->update(['role' => $newRole]);

        // Crea messaggio di sistema
        $roleNames = [
            'member' => 'membro',
            'moderator' => 'moderatore',
            'admin' => 'amministratore'
        ];

        ChatMessage::createSystemMessage(
            $chat,
            "{$targetUser->name} è stato promosso a {$roleNames[$newRole]}."
        );

        return response()->json(['success' => 'Ruolo aggiornato con successo']);
    }
}
