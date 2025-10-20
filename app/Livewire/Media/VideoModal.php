<?php

namespace App\Livewire\Media;

use Livewire\Component;
use App\Models\Video;
use App\Models\VideoSnap;
use Illuminate\Support\Facades\Auth;

class VideoModal extends Component
{
    public $videoId;
    public $newComment = '';
    public $showSnapForm = false;
    public $snapContent = '';

    public function mount($videoId)
    {
        $this->videoId = $videoId;
    }

    public function getVideoProperty()
    {
        return Video::with(['user', 'likes', 'comments', 'snaps'])
            ->find($this->videoId);
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
            $this->video->comments()->create([
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

    public function createSnap()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Devi essere autenticato per creare uno snap.');
            return;
        }

        if (empty(trim($this->snapContent))) {
            session()->flash('error', 'Lo snap non può essere vuoto.');
            return;
        }

        try {
            VideoSnap::create([
                'video_id' => $this->video->id,
                'user_id' => Auth::id(),
                'content' => $this->snapContent,
                'timestamp' => 0 // Per ora sempre 0, potremmo implementare la selezione del timestamp
            ]);

            $this->snapContent = '';
            $this->showSnapForm = false;
            session()->flash('success', 'Snap creato con successo!');
        } catch (\Exception $e) {
            session()->flash('error', 'Errore nella creazione dello snap.');
        }
    }

    public function toggleSnapForm()
    {
        $this->showSnapForm = !$this->showSnapForm;
        if (!$this->showSnapForm) {
            $this->snapContent = '';
        }
    }

    public function render()
    {
        return view('livewire.media.video-modal');
    }
}
