<?php

namespace App\Livewire\Social;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SocialCommentButton extends Component
{
    public $modelType;
    public $modelId;
    public $commentCount = 0;
    public $size = 'md';
    public $showCount = true;
    public $showModal = false;

    protected $listeners = ['commentAdded' => 'refreshCommentCount', 'commentRemoved' => 'refreshCommentCount'];

    public function mount($model, $size = 'md', $showCount = true)
    {
        $this->modelType = get_class($model);
        $this->modelId = $model->id;
        $this->size = $size;
        $this->showCount = $showCount;
        
        $this->refreshCommentCount();
    }

    public function getModelProperty()
    {
        return app($this->modelType)->find($this->modelId);
    }

    public function toggleModal()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Devi essere autenticato per commentare.');
            return redirect()->route('login');
        }

        $this->showModal = !$this->showModal;
    }

    public function addComment($content)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Devi essere autenticato per commentare.');
            return redirect()->route('login');
        }

        try {
            $this->model->addComment(Auth::user(), $content);
            $this->refreshCommentCount();
            
            // Emetti evento per aggiornare altri componenti
            $this->dispatch('commentAdded', [
                'modelType' => $this->modelType,
                'modelId' => $this->modelId,
                'commentCount' => $this->commentCount
            ]);

            session()->flash('success', 'Commento aggiunto con successo!');
            $this->showModal = false;

        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante l\'aggiunta del commento. Riprova.');
        }
    }

    public function refreshCommentCount()
    {
        $this->commentCount = $this->model->comments()->where('status', 'approved')->count();
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

    public function getIconClass()
    {
        $sizes = [
            'sm' => 'f-s-16',
            'md' => 'f-s-20',
            'lg' => 'f-s-24'
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
        return view('livewire.social.social-comment-button');
    }
}
