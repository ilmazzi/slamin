<?php

namespace App\Livewire\Media;

use Livewire\Component;
use App\Models\Photo;

class PhotoModal extends Component
{
    public $photoId;
    public $showModal = false;
    public $newComment = '';

    protected $listeners = [
        'openPhotoModal' => 'openModal',
        'closePhotoModal' => 'closeModal',
        'commentAdded' => 'refreshComments'
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
        $this->newComment = '';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->photoId = null;
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

        $photo = $this->photo;
        if (!$photo) {
            return;
        }

        try {
            $user = auth()->user();
            $photo->addComment($user, trim($this->newComment));
            
            $this->newComment = '';
            
            // Dispatch event per aggiornare i contatori
            $this->dispatch('commentAdded', [
                'contentId' => $photo->id,
                'contentType' => 'photo'
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

    public function getCommentsProperty()
    {
        if (!$this->photo) {
            return collect();
        }

        return $this->photo->comments()
            ->with(['user'])
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.media.photo-modal', [
            'photo' => $this->photo,
            'comments' => $this->comments,
        ]);
    }
}
