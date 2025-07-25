<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Search users for event invitations
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            if (strlen($query) < 2) {
                return response()->json(['users' => []]);
            }

            $users = User::where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->where('id', '!=', Auth::id()) // Escludi l'utente corrente
            ->limit(10)
            ->get(['id', 'name', 'email', 'profile_photo']);

            // Aggiungi l'URL dell'avatar per ogni utente
            $usersWithAvatars = $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar_url' => $user->profile_photo_url
                ];
            });

            return response()->json(['users' => $usersWithAvatars]);
        } catch (\Exception $e) {
            Log::error('User search API error: ' . $e->getMessage(), [
                'query' => $query ?? 'null',
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['users' => [], 'error' => 'Search failed'], 500);
        }
    }

    /**
     * Get suggested users for event invitations
     */
    public function suggested()
    {
        $currentUser = Auth::user();
        
        // Per ora restituiamo utenti attivi recenti
        // In futuro potremmo implementare un sistema di followers/following
        $suggestedUsers = User::where('id', '!=', $currentUser->id)
            ->where('created_at', '>=', now()->subMonths(3)) // Utenti attivi negli ultimi 3 mesi
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get(['id', 'name', 'email', 'profile_photo']);

        // Aggiungi l'URL dell'avatar per ogni utente suggerito
        $suggestedUsersWithAvatars = $suggestedUsers->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar_url' => $user->profile_photo_url
            ];
        });

        return response()->json(['users' => $suggestedUsersWithAvatars]);
    }
} 