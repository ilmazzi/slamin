<?php

namespace App\Http\Controllers;

use App\Models\Poem;
use App\Models\PoemComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PoemCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Mostra i commenti di una poesia
     */
    public function index(Poem $poem)
    {
        $comments = $poem->comments()
            ->with(['user', 'replies.user'])
            ->topLevel()
            ->approved()
            ->recent()
            ->paginate(10);

        return response()->json($comments);
    }

    /**
     * Salva un nuovo commento
     */
    public function store(Request $request, Poem $poem)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:3|max:1000',
            'parent_id' => 'nullable|exists:poem_comments,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['user_id'] = Auth::id();
        $data['poem_id'] = $poem->id;

        // Imposta lo stato di moderazione (auto-approvazione per utenti verificati)
        $data['moderation_status'] = Auth::user()->verified ? 'approved' : 'pending';

        $comment = PoemComment::create($data);

        // Incrementa il contatore dei commenti della poesia
        $poem->increment('comment_count');

        // Carica le relazioni per la risposta
        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => __('poems.messages.commented'),
            'comment' => $comment
        ]);
    }

    /**
     * Aggiorna un commento
     */
    public function update(Request $request, PoemComment $comment)
    {
        if (!$comment->canBeEditedByCurrentUser) {
            return response()->json([
                'success' => false,
                'message' => __('poems.errors.not_authorized')
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
            'content' => $request->content,
            'is_edited' => true,
            'edited_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => __('poems.messages.updated'),
            'comment' => $comment->fresh()
        ]);
    }

    /**
     * Elimina un commento
     */
    public function destroy(PoemComment $comment)
    {
        if (!$comment->canBeDeletedByCurrentUser) {
            return response()->json([
                'success' => false,
                'message' => __('poems.errors.not_authorized')
            ], 403);
        }

        $poem = $comment->poem;
        $comment->delete();

        // Decrementa il contatore dei commenti della poesia
        $poem->decrement('comment_count');

        return response()->json([
            'success' => true,
            'message' => __('poems.messages.deleted')
        ]);
    }

    /**
     * Metti/rimuovi mi piace a un commento
     */
    public function toggleLike(PoemComment $comment)
    {
        $user = Auth::user();
        $isLiked = $comment->likes()->where('user_id', $user->id)->exists();

        if ($isLiked) {
            $comment->likes()->detach($user->id);
            $comment->decrementLikeCount();
            $message = __('poems.actions.unlike');
        } else {
            $comment->likes()->attach($user->id);
            $comment->incrementLikeCount();
            $message = __('poems.actions.like');
        }

        return response()->json([
            'success' => true,
            'liked' => !$isLiked,
            'like_count' => $comment->fresh()->like_count,
            'message' => $message
        ]);
    }

    /**
     * Moderazione commenti (solo admin)
     */
    public function moderate(Request $request, PoemComment $comment)
    {
        if (!$comment->canBeModeratedByCurrentUser) {
            return response()->json([
                'success' => false,
                'message' => __('poems.errors.not_authorized')
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'moderation_status' => 'required|in:approved,rejected',
            'moderation_notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $comment->update($data);

        $message = $data['moderation_status'] === 'approved' 
            ? __('poems.messages.approved') 
            : __('poems.messages.rejected');

        return response()->json([
            'success' => true,
            'message' => $message,
            'comment' => $comment->fresh()
        ]);
    }
}
