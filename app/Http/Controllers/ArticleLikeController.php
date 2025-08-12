<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleLikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Toggle like for an article
     */
    public function toggle(Request $request, Article $article)
    {
        $user = Auth::user();

        if ($article->isLikedBy($user)) {
            $article->unlike($user);
            $liked = false;
            $message = __('articles.unliked');
        } else {
            $article->like($user);
            $liked = true;
            $message = __('articles.liked');
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'liked' => $liked,
            'likes_count' => $article->likes()->count()
        ]);
    }

    /**
     * Like an article
     */
    public function like(Request $request, Article $article)
    {
        $user = Auth::user();

        if ($article->isLikedBy($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Hai già messo mi piace a questo articolo'
            ], 400);
        }

        $article->like($user);

        return response()->json([
            'success' => true,
            'message' => __('articles.liked'),
            'likes_count' => $article->likes()->count()
        ]);
    }

    /**
     * Unlike an article
     */
    public function unlike(Request $request, Article $article)
    {
        $user = Auth::user();

        if (!$article->isLikedBy($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Non hai ancora messo mi piace a questo articolo'
            ], 400);
        }

        $article->unlike($user);

        return response()->json([
            'success' => true,
            'message' => __('articles.unliked'),
            'likes_count' => $article->likes()->count()
        ]);
    }

    /**
     * Get users who liked an article
     */
    public function getLikers(Request $request, Article $article)
    {
        $likers = $article->likedBy()
            ->with('profile')
            ->orderBy('article_likes.created_at', 'desc')
            ->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'likers' => $likers
            ]);
        }

        return view('articles.likers', compact('article', 'likers'));
    }

    /**
     * Get like status for current user
     */
    public function getStatus(Request $request, Article $article)
    {
        $user = Auth::user();
        $liked = $user ? $article->isLikedBy($user) : false;

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $article->likes()->count()
        ]);
    }

    /**
     * Get multiple articles like status
     */
    public function getMultipleStatus(Request $request)
    {
        $request->validate([
            'article_ids' => 'required|array',
            'article_ids.*' => 'exists:articles,id'
        ]);

        $user = Auth::user();
        $statuses = [];

        foreach ($request->article_ids as $articleId) {
            $article = Article::find($articleId);
            if ($article) {
                $statuses[$articleId] = [
                    'liked' => $user ? $article->isLikedBy($user) : false,
                    'likes_count' => $article->likes()->count()
                ];
            }
        }

        return response()->json([
            'success' => true,
            'statuses' => $statuses
        ]);
    }
}
