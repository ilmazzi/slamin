<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Get comments for an article
     */
    public function index(Request $request, Article $article)
    {
        $comments = $article->comments()
            ->approved()
            ->topLevel()
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comments' => $comments,
                'html' => view('articles.partials.comments-list', compact('comments'))->render()
            ]);
        }

        return view('articles.comments', compact('article', 'comments'));
    }

    /**
     * Store a new comment
     */
    public function store(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:3|max:1000',
            'parent_id' => 'nullable|exists:article_comments,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        // Check if user can comment
        if (!$this->canComment($user, $article)) {
            return response()->json([
                'success' => false,
                'message' => 'Non puoi commentare questo articolo'
            ], 403);
        }

        // Check if comment approval is required
        $status = config('articles.auto_approve_comments', true) ? 'approved' : 'pending';

        $comment = $article->addComment(
            $user,
            $request->content,
            $request->parent_id
        );

        // Update status if needed
        if ($status !== 'approved') {
            $comment->update(['status' => $status]);
        }

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => $status === 'approved' ? __('articles.comment_posted') : __('articles.comment_pending'),
            'comment' => $comment,
            'status' => $status
        ]);
    }

    /**
     * Update a comment
     */
    public function update(Request $request, ArticleComment $comment)
    {
        $user = Auth::user();

        // Check if user can edit this comment
        if (!$this->canEditComment($user, $comment)) {
            return response()->json([
                'success' => false,
                'message' => 'Non puoi modificare questo commento'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:3|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $comment->update([
            'content' => $request->content
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Commento aggiornato con successo',
            'comment' => $comment->load('user')
        ]);
    }

    /**
     * Delete a comment
     */
    public function destroy(ArticleComment $comment)
    {
        $user = Auth::user();

        // Check if user can delete this comment
        if (!$this->canDeleteComment($user, $comment)) {
            return response()->json([
                'success' => false,
                'message' => 'Non puoi eliminare questo commento'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => __('articles.comment_deleted')
        ]);
    }

    /**
     * Approve a comment (admin/editor only)
     */
    public function approve(ArticleComment $comment)
    {
        if (!Auth::user()->hasPermissionTo('articles.moderate_comments')) {
            return response()->json([
                'success' => false,
                'message' => 'Accesso negato'
            ], 403);
        }

        $comment->approve();

        return response()->json([
            'success' => true,
            'message' => 'Commento approvato'
        ]);
    }

    /**
     * Reject a comment (admin/editor only)
     */
    public function reject(ArticleComment $comment)
    {
        if (!Auth::user()->hasPermissionTo('articles.moderate_comments')) {
            return response()->json([
                'success' => false,
                'message' => 'Accesso negato'
            ], 403);
        }

        $comment->reject();

        return response()->json([
            'success' => true,
            'message' => 'Commento rifiutato'
        ]);
    }

    /**
     * Get replies for a comment
     */
    public function getReplies(Request $request, ArticleComment $comment)
    {
        $replies = $comment->replies()
            ->approved()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'replies' => $replies
            ]);
        }

        return view('articles.partials.comment-replies', compact('replies'));
    }

    /**
     * Like a comment
     */
    public function like(Request $request, ArticleComment $comment)
    {
        $user = Auth::user();

        // Check if already liked
        $existingLike = $comment->likes()->where('user_id', $user->id)->first();
        if ($existingLike) {
            return response()->json([
                'success' => false,
                'message' => 'Hai già messo mi piace a questo commento'
            ], 400);
        }

        $comment->likes()->create(['user_id' => $user->id]);
        $comment->incrementLikes();

        return response()->json([
            'success' => true,
            'message' => 'Mi piace aggiunto',
            'likes_count' => $comment->likes()->count()
        ]);
    }

    /**
     * Unlike a comment
     */
    public function unlike(Request $request, ArticleComment $comment)
    {
        $user = Auth::user();

        $like = $comment->likes()->where('user_id', $user->id)->first();
        if (!$like) {
            return response()->json([
                'success' => false,
                'message' => 'Non hai ancora messo mi piace a questo commento'
            ], 400);
        }

        $like->delete();
        $comment->decrementLikes();

        return response()->json([
            'success' => true,
            'message' => 'Mi piace rimosso',
            'likes_count' => $comment->likes()->count()
        ]);
    }

    /**
     * Check if user can comment
     */
    private function canComment($user, Article $article)
    {
        // Check if article is published
        if (!$article->isPublished) {
            return false;
        }

        // Check if comments are enabled
        if (!config('articles.enable_comments', true)) {
            return false;
        }

        // Check if user is not banned
        if ($user->isBanned()) {
            return false;
        }

        return true;
    }

    /**
     * Check if user can edit comment
     */
    private function canEditComment($user, ArticleComment $comment)
    {
        // User is the author
        if ($user->id === $comment->user_id) {
            return true;
        }

        // User is admin or editor
        if ($user->hasRole(['admin', 'editor'])) {
            return true;
        }

        // User has permission
        if ($user->hasPermissionTo('articles.moderate_comments')) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can delete comment
     */
    private function canDeleteComment($user, ArticleComment $comment)
    {
        // User is the author
        if ($user->id === $comment->user_id) {
            return true;
        }

        // User is admin or editor
        if ($user->hasRole(['admin', 'editor'])) {
            return true;
        }

        // User has permission
        if ($user->hasPermissionTo('articles.moderate_comments')) {
            return true;
        }

        return false;
    }
}
