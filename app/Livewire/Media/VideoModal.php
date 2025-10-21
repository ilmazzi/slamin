<?php

namespace App\Livewire\Media;

use Livewire\Component;
use App\Models\Video;

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
        if (!auth()->check()) {
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
            $user = auth()->user();
            $video->addComment($user, trim($this->newComment));
            
            $this->newComment = '';
            
            // Dispatch event per aggiornare i contatori
            $this->dispatch('commentAdded', [
                'contentId' => $video->id,
                'contentType' => 'video'
            ]);
            
        } catch (\Exception $e) {
            // Handle error silently
        }
    }

    public function refreshComments($data = null)
    {
        // Force re-render
        $this->dispatch('$refresh');
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
