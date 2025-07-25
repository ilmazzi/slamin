<?php

namespace App\Http\Controllers;

use App\Models\Poem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PoemActionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Metti/rimuovi mi piace a una poesia
     */
    public function toggleLike(Poem $poem)
    {
        $user = Auth::user();
        $isLiked = $poem->likes()->where('user_id', $user->id)->exists();

        if ($isLiked) {
            $poem->likes()->detach($user->id);
            $poem->decrementLikeCount();
            $message = __('poems.actions.unlike');
        } else {
            $poem->likes()->attach($user->id);
            $poem->incrementLikeCount();
            $message = __('poems.actions.like');
        }

        return response()->json([
            'success' => true,
            'liked' => !$isLiked,
            'like_count' => $poem->fresh()->like_count,
            'message' => $message
        ]);
    }

    /**
     * Aggiungi/rimuovi dai segnalibri
     */
    public function toggleBookmark(Poem $poem)
    {
        $user = Auth::user();
        $isBookmarked = $poem->bookmarks()->where('user_id', $user->id)->exists();

        if ($isBookmarked) {
            $poem->bookmarks()->detach($user->id);
            $poem->decrementBookmarkCount();
            $message = __('poems.actions.unbookmark');
        } else {
            $poem->bookmarks()->attach($user->id);
            $poem->incrementBookmarkCount();
            $message = __('poems.actions.bookmark');
        }

        return response()->json([
            'success' => true,
            'bookmarked' => !$isBookmarked,
            'bookmark_count' => $poem->fresh()->bookmark_count,
            'message' => $message
        ]);
    }

    /**
     * Incrementa il contatore delle condivisioni
     */
    public function share(Poem $poem)
    {
        $poem->incrementShareCount();

        return response()->json([
            'success' => true,
            'share_count' => $poem->fresh()->share_count,
            'message' => __('poems.messages.shared')
        ]);
    }

    /**
     * Richiedi traduzione di una poesia
     */
    public function requestTranslation(Request $request, Poem $poem)
    {
        if (!$poem->translation_available) {
            return response()->json([
                'success' => false,
                'message' => __('poems.errors.translation_not_available')
            ], 400);
        }

        $user = Auth::user();
        $translationRequests = $poem->translation_requests ?? [];

        // Verifica se l'utente ha già fatto una richiesta
        $existingRequest = collect($translationRequests)->firstWhere('user_id', $user->id);
        
        if ($existingRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Hai già richiesto una traduzione per questa poesia'
            ], 400);
        }

        // Aggiungi la richiesta
        $translationRequests[] = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'requested_at' => now()->toISOString(),
            'target_language' => $request->get('target_language', 'en'),
            'notes' => $request->get('notes', '')
        ];

        $poem->update(['translation_requests' => $translationRequests]);

        return response()->json([
            'success' => true,
            'message' => __('poems.messages.translation_requested'),
            'translation_request_count' => count($translationRequests)
        ]);
    }

    /**
     * Mostra i segnalibri dell'utente
     */
    public function bookmarks()
    {
        $bookmarks = Auth::user()->bookmarkedPoems()
            ->with(['user', 'likes', 'comments'])
            ->orderBy('pivot_created_at', 'desc')
            ->paginate(12);

        return view('poems.bookmarks', compact('bookmarks'));
    }

    /**
     * Mostra le poesie che piacciono all'utente
     */
    public function liked()
    {
        $likedPoems = Auth::user()->likedPoems()
            ->with(['user', 'likes', 'comments'])
            ->orderBy('pivot_created_at', 'desc')
            ->paginate(12);

        return view('poems.liked', compact('likedPoems'));
    }
}
