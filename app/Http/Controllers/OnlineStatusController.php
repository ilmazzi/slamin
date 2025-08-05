<?php

namespace App\Http\Controllers;

use App\Events\UserStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OnlineStatusController extends Controller
{
    /**
     * Aggiorna lo stato online dell'utente
     */
    public function updateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:online,away,busy,invisible,offline',
        ]);

        $user = Auth::user();
        $status = $request->input('status');

        switch ($status) {
            case 'online':
                $user->setOnline();
                break;
            case 'away':
                $user->setAway();
                break;
            case 'busy':
                $user->setBusy();
                break;
            case 'invisible':
                $user->setInvisible();
                break;
            case 'offline':
                $user->setOffline();
                break;
        }

        // Broadcast del cambio di stato via Reverb
        broadcast(new UserStatusChanged($user, $status))->toOthers();

        return response()->json([
            'success' => true,
            'status' => $user->online_status,
            'is_online' => $user->is_online,
        ]);
    }

    /**
     * Aggiorna l'ultima attività dell'utente
     */
    public function updateLastSeen(): JsonResponse
    {
        $user = Auth::user();
        $user->updateLastSeen();

        return response()->json([
            'success' => true,
            'last_seen_at' => $user->last_seen_at,
        ]);
    }

    /**
     * Ottieni lo stato online di un utente specifico
     */
    public function getUserStatus(User $user): JsonResponse
    {
        $currentUser = Auth::user();

        if (!$currentUser->canSeeOnlineStatus($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Non puoi vedere lo stato di questo utente',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'user_id' => $user->id,
            'is_online' => $user->isCurrentlyOnline(),
            'status' => $user->getOnlineStatusDisplay(),
            'last_seen' => $user->getLastSeenDisplay(),
            'status_color' => $user->getOnlineStatusColor(),
            'status_icon' => $user->getOnlineStatusIcon(),
        ]);
    }

    /**
     * Ottieni lo stato online di più utenti (per la chat)
     */
    public function getMultipleUsersStatus(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        $currentUser = Auth::user();
        $userIds = $request->input('user_ids');
        $users = User::whereIn('id', $userIds)->get();

        $statuses = [];
        foreach ($users as $user) {
            if ($currentUser->canSeeOnlineStatus($user)) {
                $statuses[$user->id] = [
                    'is_online' => $user->isCurrentlyOnline(),
                    'status' => $user->getOnlineStatusDisplay(),
                    'status_display' => $user->getOnlineStatusDisplay(),
                    'last_seen' => $user->getLastSeenDisplay(),
                    'status_color' => $user->getOnlineStatusColor(),
                    'status_icon' => $user->getOnlineStatusIcon(),
                ];
            } else {
                $statuses[$user->id] = [
                    'is_online' => false,
                    'status' => 'offline',
                    'status_display' => 'offline',
                    'last_seen' => 'Non disponibile',
                    'status_color' => 'secondary',
                    'status_icon' => 'ph-circle',
                ];
            }
        }

        return response()->json([
            'success' => true,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Aggiorna le preferenze di privacy per lo stato online
     */
    public function updatePrivacyPreferences(Request $request): JsonResponse
    {
        $request->validate([
            'visibility' => 'required|in:all,friends,none',
        ]);

        $user = Auth::user();
        $preferences = $user->online_preferences ?? [];
        $preferences['visibility'] = $request->input('visibility');

        $user->update(['online_preferences' => $preferences]);

        return response()->json([
            'success' => true,
            'preferences' => $user->online_preferences,
        ]);
    }

    /**
     * Ottieni le preferenze di privacy dell'utente
     */
    public function getPrivacyPreferences(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'preferences' => $user->online_preferences ?? ['visibility' => 'all'],
        ]);
    }

    /**
     * Ottieni il conteggio dei messaggi non letti per la sidebar
     */
    public function getUnreadMessagesCount(): JsonResponse
    {
        $user = Auth::user();
        $unreadCount = $user->unread_chat_messages_count;
        
        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
        ]);
    }
}
