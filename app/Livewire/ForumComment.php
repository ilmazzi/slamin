<?php

namespace App\Livewire;

use App\Models\ForumComment as CommentModel;
use App\Notifications\Forum\CommentReply;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ForumComment extends Component
{
    public CommentModel $comment;
    public $showReplyForm = false;
    public $replyContent = '';

    public function toggleReplyForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->showReplyForm = !$this->showReplyForm;
        
        if (!$this->showReplyForm) {
            $this->replyContent = '';
        }
    }

    public function addReply()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'replyContent' => 'required|string|min:1|max:10000',
        ]);

        // Check depth limit
        if (!$this->comment->canReply()) {
            $this->dispatch('notify', [
                'message' => __('forum.max_comment_depth_reached'),
                'type' => 'error'
            ]);
            return;
        }

        $reply = CommentModel::create([
            'post_id' => $this->comment->post_id,
            'parent_id' => $this->comment->id,
            'user_id' => Auth::id(),
            'content' => $this->replyContent,
            'depth' => $this->comment->depth + 1,
            'approved_at' => now(), // Auto-approve for now
        ]);

        // Increment post comment count
        $this->comment->post->increment('comments_count');

        // Notify parent comment author
        if ($this->comment->user_id !== Auth::id()) {
            $this->comment->user->notify(new CommentReply($reply));
        }

        $this->replyContent = '';
        $this->showReplyForm = false;

        $this->dispatch('notify', [
            'message' => __('forum.reply_added'),
            'type' => 'success'
        ]);

        $this->dispatch('comment-added');
    }

    public function deleteComment()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user can delete (author or moderator)
        if ($this->comment->user_id !== $user->id && !$user->hasRole('admin') && !$user->hasRole('moderator')) {
            $this->dispatch('notify', [
                'message' => __('forum.unauthorized_action'),
                'type' => 'error'
            ]);
            return;
        }

        $this->comment->softDelete($user);

        $this->dispatch('notify', [
            'message' => __('forum.comment_deleted'),
            'type' => 'success'
        ]);

        $this->dispatch('comment-deleted');
    }

    public function render()
    {
        return view('livewire.forum-comment');
    }
}
