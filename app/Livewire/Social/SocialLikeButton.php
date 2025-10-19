<?php

namespace App\Livewire\Social;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SocialLikeButton extends Component
{
    public $model;
    public $modelType;
    public $modelId;
    public $isLiked = false;
    public $likeCount = 0;
    public $size = 'md';
    public $showCount = true;

    protected $listeners = ['likeToggled' => 'refreshLikeStatus'];

    public function mount($model, $size = 'md', $showCount = true)
    {
        $this->model = $model;
        $this->modelType = get_class($model);
        $this->modelId = $model->id;
        $this->size = $size;
        $this->showCount = $showCount;
        
        $this->refreshLikeStatus();
    }

    public function toggleLike()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Devi essere autenticato per mettere like.');
            return redirect()->route('login');
        }

        try {
            $this->model->toggleLike(Auth::user());
            $this->refreshLikeStatus();
            
            // Emetti evento per aggiornare altri componenti
            $this->dispatch('likeToggled', [
                'modelType' => $this->modelType,
                'modelId' => $this->modelId,
                'isLiked' => $this->isLiked,
                'likeCount' => $this->likeCount
            ]);

        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante l\'operazione. Riprova.');
        }
    }

    public function refreshLikeStatus()
    {
        // Ricarica il modello dal database per avere i dati aggiornati
        $this->model = $this->model->fresh();
        $this->isLiked = $this->model->isLikedByCurrentUser();
        $this->likeCount = $this->model->likes()->count();
    }

    public function getSizeStyles()
    {
        $sizes = [
            'sm' => 'min-width: 50px; padding: 6px; gap: 2px;',
            'md' => 'min-width: 60px; padding: 8px; gap: 2px;',
            'lg' => 'min-width: 70px; padding: 10px; gap: 2px;'
        ];
        return $sizes[$this->size] ?? $sizes['md'];
    }

    public function getIconStyles()
    {
        $sizes = [
            'sm' => 'width: 20px; height: 20px;',
            'md' => 'width: 24px; height: 24px;',
            'lg' => 'width: 28px; height: 28px;'
        ];
        return $sizes[$this->size] ?? $sizes['md'];
    }

    public function getTextClass()
    {
        $sizes = [
            'sm' => 'f-s-10',
            'md' => 'f-s-12',
            'lg' => 'f-s-14'
        ];
        return $sizes[$this->size] ?? $sizes['md'];
    }

    public function render()
    {
        return view('livewire.social.social-like-button');
    }
}
