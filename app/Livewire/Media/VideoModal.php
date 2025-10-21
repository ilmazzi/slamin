<?php

namespace App\Livewire\Media;

use Livewire\Component;
use App\Models\Video;

class VideoModal extends Component
{
    public $videoId;
    public $showModal = false;

    protected $listeners = [
        'openVideoModal' => 'openModal',
        'closeVideoModal' => 'closeModal'
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
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->videoId = null;
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

    public function render()
    {
        return view('livewire.media.video-modal', [
            'video' => $this->video,
        ]);
    }
}
