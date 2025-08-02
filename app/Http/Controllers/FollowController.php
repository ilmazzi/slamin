<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Toggle follow/unfollow di un utente
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $user = Auth::user();
        $targetUser = User::findOrFail($request->user_id);

        // Non può seguire se stesso
        if ($user->id === $targetUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Non puoi seguire te stesso'
            ], 400);
        }

        $isFollowing = $user->isFollowing($targetUser);
        $result = $user->toggleFollow($targetUser);

        if ($result) {
            return response()->json([
                'success' => true,
                'following' => !$isFollowing,
                'message' => !$isFollowing ? 'Utente seguito con successo' : 'Follow rimosso con successo',
                'followers_count' => $targetUser->followers_count,
                'following_count' => $targetUser->following_count
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Errore durante l\'operazione'
        ], 500);
    }

    /**
     * Ottieni la lista dei followers di un utente
     */
    public function followers(Request $request, User $user): JsonResponse
    {
        $followers = $user->followers()
            ->with(['videos', 'photos', 'poems'])
            ->paginate(20);

        return response()->json([
            'success' => true,
            'followers' => $followers
        ]);
    }

    /**
     * Ottieni la lista degli utenti che un utente segue
     */
    public function following(Request $request, User $user): JsonResponse
    {
        $following = $user->following()
            ->with(['videos', 'photos', 'poems'])
            ->paginate(20);

        return response()->json([
            'success' => true,
            'following' => $following
        ]);
    }

    /**
     * Controlla se l'utente corrente segue un altro utente
     */
    public function check(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $user = Auth::user();
        $targetUser = User::findOrFail($request->user_id);

        return response()->json([
            'success' => true,
            'following' => $user->isFollowing($targetUser)
        ]);
    }
}
