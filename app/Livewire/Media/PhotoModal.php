<?php

namespace App\Livewire\Media;

use Livewire\Component;
use App\Models\Photo;

class PhotoModal extends Component
{
    public $photoId;
    public $showModal = false;

    protected $listeners = [
        'openPhotoModal' => 'openModal',
        'closePhotoModal' => 'closeModal'
    ];

    public function openModal($photoId = null)
    {
        // Se photoId è un array (evento Livewire con parametri)
        if (is_array($photoId)) {
            $this->photoId = $photoId['photoId'] ?? null;
        } else {
            $this->photoId = $photoId;
        }
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->photoId = null;
    }

    public function getPhotoProperty()
    {
        if (!$this->photoId) {
            return null;
        }

        try {
            return Photo::with(['user'])->find($this->photoId);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function render()
    {
        return view('livewire.media.photo-modal', [
            'photo' => $this->photo,
        ]);
    }
}
