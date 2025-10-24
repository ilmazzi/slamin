<?php

namespace App\Livewire\Forum;

use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Notifications\Forum\NewCommentOnPost;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class ForumPostShow extends Component
{
    public ForumPost $post;
    public $newComment = '';
    public $sortComments = 'best'; // best, new, old, top

    public function mount(ForumPost $post)
    {
        $this->post = $post;
        
        // Increment views
        $this->post->incrementViews();
    }

    public function setSortComments($sort)
    {
        $this->sortComments = $sort;
    }

    public function addComment()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'newComment' => 'required|string|min:1|max:10000',
        ]);

        $comment = $this->post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $this->newComment,
            'depth' => 0,
            'approved_at' => now(), // Auto-approve for now
        ]);

        $this->post->increment('comments_count');

        // Notify post author
        if ($this->post->user_id !== Auth::id()) {
            $this->post->user->notify(new NewCommentOnPost($comment));
        }

        $this->newComment = '';

        $this->dispatch('notify', [
            'message' => __('forum.comment_added'),
            'type' => 'success'
        ]);

        $this->dispatch('comment-added');
    }

    #[On('comment-added')]
    #[On('comment-deleted')]
    public function refreshPost()
    {
        $this->post->refresh();
    }

    public function render()
    {
        // Get root comments with nested replies
        $query = $this->post->rootComments()
            ->with(['user', 'replies.user', 'replies.replies.user'])
            ->notDeleted();

        // Apply sorting
        switch ($this->sortComments) {
            case 'new':
                $query->orderBy('created_at', 'desc');
                break;
            case 'old':
                $query->orderBy('created_at', 'asc');
                break;
            case 'top':
                $query->orderBy('score', 'desc');
                break;
            case 'best':
            default:
                // Reddit "best" algorithm: score / age
                $query->orderBy('score', 'desc')->orderBy('created_at', 'desc');
                break;
        }

        $comments = $query->get();

        return view('livewire.forum-post-show', [
            'comments' => $comments,
        ]);
    }
}
