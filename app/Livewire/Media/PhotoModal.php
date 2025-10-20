<?php

namespace App\Livewire\Media;

use Livewire\Component;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;

class PhotoModal extends Component
{
    public $photoId;
    public $newComment = '';

    public function mount($photoId)
    {
        $this->photoId = $photoId;
    }

    public function getPhotoProperty()
    {
        return Photo::with(['user', 'likes', 'comments'])
            ->find($this->photoId);
    }

    public function addComment()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Devi essere autenticato per commentare.');
            return;
        }

        if (empty(trim($this->newComment))) {
            session()->flash('error', 'Il commento non può essere vuoto.');
            return;
        }

        try {
            $this->photo->comments()->create([
                'user_id' => Auth::id(),
                'content' => $this->newComment,
                'status' => 'approved'
            ]);

            $this->newComment = '';
            session()->flash('success', 'Commento aggiunto con successo!');
        } catch (\Exception $e) {
            session()->flash('error', 'Errore nell\'aggiunta del commento.');
        }
    }

    public function render()
    {
        return view('livewire.media.photo-modal');
    }
}
