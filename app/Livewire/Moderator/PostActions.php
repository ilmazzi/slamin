<?php

namespace App\Livewire\Moderator;

use App\Models\ForumPost;
use App\Models\ForumBan;
use App\Models\User;
use App\Notifications\Forum\PostModerated;
use App\Notifications\Forum\UserBanned;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PostActions extends Component
{
    public ForumPost $post;
    public $showBanModal = false;
    public $banUserId;
    public $banReason = '';
    public $banDuration = 'permanent'; // permanent, 1day, 7days, 30days
    
    public function mount(ForumPost $post)
    {
        $this->post = $post;
    }

    public function toggleSticky()
    {
        if (!$this->canModerate()) {
            $this->dispatch('notify', [
                'message' => 'Non autorizzato',
                'type' => 'error'
            ]);
            return;
        }

        $wasSticky = $this->post->is_sticky;
        
        $this->post->update([
            'is_sticky' => !$this->post->is_sticky
        ]);

        // Notify post author
        if ($this->post->user_id !== Auth::id()) {
            $action = $wasSticky ? 'unsticky' : 'sticky';
            $this->post->user->notify(new PostModerated($this->post, $action, Auth::user()->name));
        }

        $this->post->refresh();

        $this->dispatch('notify', [
            'message' => $this->post->is_sticky ? 'Post fissato in alto!' : 'Post rimosso da fissati',
            'type' => 'success'
        ]);
    }

    public function toggleLock()
    {
        if (!$this->canModerate()) {
            $this->dispatch('notify', [
                'message' => 'Non autorizzato',
                'type' => 'error'
            ]);
            return;
        }

        $wasLocked = $this->post->is_locked;
        
        $this->post->update([
            'is_locked' => !$this->post->is_locked
        ]);

        // Notify post author
        if ($this->post->user_id !== Auth::id()) {
            $action = $wasLocked ? 'unlocked' : 'locked';
            $this->post->user->notify(new PostModerated($this->post, $action, Auth::user()->name));
        }

        $this->post->refresh();

        $this->dispatch('notify', [
            'message' => $this->post->is_locked ? 'Post bloccato (no nuovi commenti)' : 'Post sbloccato',
            'type' => 'success'
        ]);
    }

    public function toggleArchive()
    {
        if (!$this->canModerate()) {
            $this->dispatch('notify', [
                'message' => 'Non autorizzato',
                'type' => 'error'
            ]);
            return;
        }

        $this->post->update([
            'is_archived' => !$this->post->is_archived
        ]);

        $this->post->refresh();

        $this->dispatch('notify', [
            'message' => $this->post->is_archived ? 'Post archiviato' : 'Post ripristinato',
            'type' => 'success'
        ]);
    }

    public function openBanModal($userId)
    {
        $this->banUserId = $userId;
        $this->showBanModal = true;
    }

    public function banUser()
    {
        if (!$this->canModerate()) {
            $this->dispatch('notify', [
                'message' => 'Non autorizzato',
                'type' => 'error'
            ]);
            return;
        }

        $this->validate([
            'banReason' => 'required|string|min:10|max:500',
        ]);

        $expiresAt = match($this->banDuration) {
            '1day' => now()->addDay(),
            '7days' => now()->addDays(7),
            '30days' => now()->addDays(30),
            default => null,
        };

        $ban = ForumBan::create([
            'subreddit_id' => $this->post->subreddit_id,
            'user_id' => $this->banUserId,
            'reason' => $this->banReason,
            'banned_by' => Auth::id(),
            'type' => $this->banDuration === 'permanent' ? 'permanent' : 'temporary',
            'expires_at' => $expiresAt,
            'is_active' => true,
        ]);

        // Notify banned user
        $bannedUser = User::find($this->banUserId);
        $bannedUser->notify(new UserBanned($ban));

        $this->showBanModal = false;
        $this->banReason = '';
        $this->banDuration = 'permanent';

        $this->dispatch('swal:success', [
            'title' => 'Utente Bannato!',
            'text' => 'L\'utente non potrà più interagire in questo subreddit.',
        ]);
    }

    public function deletePost()
    {
        if (!$this->canModerate()) {
            $this->dispatch('notify', [
                'message' => 'Non autorizzato',
                'type' => 'error'
            ]);
            return;
        }

        $subredditSlug = $this->post->subreddit->slug;
        $this->post->delete();

        session()->flash('success', 'Post eliminato');
        
        return redirect()->route('forum.subreddit.show', $subredditSlug);
    }

    private function canModerate()
    {
        $user = Auth::user();
        return $user->hasRole('admin') || $this->post->subreddit->isModerator($user);
    }

    public function render()
    {
        return view('livewire.moderator.post-actions');
    }
}
