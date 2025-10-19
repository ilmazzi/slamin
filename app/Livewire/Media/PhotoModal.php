<?php

namespace App\Livewire\Media;

use Livewire\Component;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;

class PhotoModal extends Component
{
    public Photo $photo;
    public $newComment = '';

    public function mount(Photo $photo)
    {
        $this->photo = $photo;
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
            
            // Ricarica la foto con i commenti aggiornati
            $this->photo = $this->photo->fresh(['comments.user']);
        } catch (\Exception $e) {
            session()->flash('error', 'Errore nell\'aggiunta del commento.');
        }
    }

    public function render()
    {
        return view('livewire.media.photo-modal');
    }
}
