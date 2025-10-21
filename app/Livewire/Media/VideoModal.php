<?php

namespace App\Livewire\Media;

use Livewire\Component;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;

class VideoModal extends Component
{
    public $videoId;
    public $showModal = false;
    public $newComment = '';

    protected $listeners = [
        'openVideoModal' => 'openModal',
        'closeVideoModal' => 'closeModal',
        'commentAdded' => 'refreshComments'
    ];

    public function openModal($videoId = null)
    {
        // Se videoId è un array (evento Livewire con parametri)
        if (is_array($videoId)) {
            $this->videoId = $videoId['videoId'] ?? null;
        } else {
            $this->videoId = $videoId;
        }
        
        $this->showModal = true;
        $this->newComment = '';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->videoId = null;
        $this->newComment = '';
    }

    public function addComment()
    {
        if (!Auth::check()) {
            $this->dispatch('show-auth-modal');
            return;
        }

        if (empty(trim($this->newComment))) {
            return;
        }

        $video = $this->video;
        if (!$video) {
            return;
        }

        try {
            $user = Auth::user();
            $video->addComment($user, trim($this->newComment));
            
            $this->newComment = '';
            
            // Non ricaricare il componente, solo resettare la proprietà computed
            unset($this->comments);
            
        } catch (\Exception $e) {
            // Handle error silently
        }
    }

    public function refreshComments($data = null)
    {
        // Reset the computed property instead of forcing a refresh
        unset($this->comments);
    }

    public function getVideoProperty()
    {
        if (!$this->videoId) {
            return null;
        }

        try {
            return Video::with(['user'])->find($this->videoId);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getCommentsProperty()
    {
        if (!$this->video) {
            return collect();
        }

        return $this->video->comments()
            ->with(['user'])
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.media.video-modal', [
            'video' => $this->video,
            'comments' => $this->comments,
        ]);
    }
}
