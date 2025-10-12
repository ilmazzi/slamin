<?php

namespace App\Livewire\Moderator;

use App\Models\Subreddit;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumBan;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ModerationQueue extends Component
{
    use WithPagination;

    public $selectedSubreddit = 'all';
    public $contentType = 'all'; // all, posts, comments
    public $actionFilter = 'pending'; // pending, approved, rejected

    public function setFilter($type, $value)
    {
        $this->{$type} = $value;
        $this->resetPage();
    }

    public function approvePost($postId)
    {
        $post = ForumPost::findOrFail($postId);
        
        if (!$this->canModerate($post->subreddit)) {
            $this->dispatch('notify', [
                'message' => 'Non sei autorizzato a moderare questo subreddit',
                'type' => 'error'
            ]);
            return;
        }

        $post->update([
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        $this->dispatch('notify', [
            'message' => 'Post approvato!',
            'type' => 'success'
        ]);
    }

    public function rejectPost($postId)
    {
        $post = ForumPost::findOrFail($postId);
        
        if (!$this->canModerate($post->subreddit)) {
            $this->dispatch('notify', [
                'message' => 'Non sei autorizzato a moderare questo subreddit',
                'type' => 'error'
            ]);
            return;
        }

        $post->delete();

        $this->dispatch('notify', [
            'message' => 'Post rifiutato ed eliminato',
            'type' => 'success'
        ]);
    }

    public function approveComment($commentId)
    {
        $comment = ForumComment::findOrFail($commentId);
        
        if (!$this->canModerate($comment->post->subreddit)) {
            $this->dispatch('notify', [
                'message' => 'Non sei autorizzato a moderare questo subreddit',
                'type' => 'error'
            ]);
            return;
        }

        $comment->update([
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        $this->dispatch('notify', [
            'message' => 'Commento approvato!',
            'type' => 'success'
        ]);
    }

    public function deleteComment($commentId)
    {
        $comment = ForumComment::findOrFail($commentId);
        
        if (!$this->canModerate($comment->post->subreddit)) {
            $this->dispatch('notify', [
                'message' => 'Non sei autorizzato a moderare questo subreddit',
                'type' => 'error'
            ]);
            return;
        }

        $comment->softDelete(Auth::user());

        $this->dispatch('notify', [
            'message' => 'Commento eliminato',
            'type' => 'success'
        ]);
    }

    private function canModerate(Subreddit $subreddit)
    {
        $user = Auth::user();
        return $user->hasRole('admin') || $subreddit->isModerator($user);
    }

    public function render()
    {
        $user = Auth::user();

        // Get subreddits user can moderate
        $moderatedSubreddits = collect();
        if ($user->hasRole('admin')) {
            $moderatedSubreddits = Subreddit::all();
        } else {
            $moderatorRecords = ForumModerator::where('user_id', $user->id)
                ->with('subreddit')
                ->get();
            $moderatedSubreddits = $moderatorRecords->pluck('subreddit');
        }

        // Build query based on filters
        $postsQuery = ForumPost::with(['user', 'subreddit']);
        $commentsQuery = ForumComment::with(['user', 'post.subreddit']);

        // Filter by subreddit
        if ($this->selectedSubreddit !== 'all') {
            $postsQuery->where('subreddit_id', $this->selectedSubreddit);
            $commentsQuery->whereHas('post', function ($q) {
                $q->where('subreddit_id', $this->selectedSubreddit);
            });
        } else {
            if (!$user->hasRole('admin')) {
                $subredditIds = $moderatedSubreddits->pluck('id')->toArray();
                $postsQuery->whereIn('subreddit_id', $subredditIds);
                $commentsQuery->whereHas('post', function ($q) use ($subredditIds) {
                    $q->whereIn('subreddit_id', $subredditIds);
                });
            }
        }

        // Filter by approval status
        if ($this->actionFilter === 'pending') {
            $postsQuery->whereNull('approved_at');
            $commentsQuery->whereNull('approved_at');
        } elseif ($this->actionFilter === 'approved') {
            $postsQuery->whereNotNull('approved_at');
            $commentsQuery->whereNotNull('approved_at');
        }

        $posts = ($this->contentType === 'all' || $this->contentType === 'posts') 
            ? $postsQuery->latest()->get() 
            : collect();

        $comments = ($this->contentType === 'all' || $this->contentType === 'comments') 
            ? $commentsQuery->latest()->get() 
            : collect();

        return view('livewire.moderator.moderation-queue', [
            'moderatedSubreddits' => $moderatedSubreddits,
            'posts' => $posts,
            'comments' => $comments,
        ]);
    }
}
