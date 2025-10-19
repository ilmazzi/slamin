<?php

namespace App\Livewire\Social;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

class SocialViewCounter extends Component
{
    public $model;
    public $modelType;
    public $modelId;
    public $viewCount = 0;
    public $size = 'md';
    public $showCount = true;
    public $autoIncrement = true;

    protected $listeners = ['viewIncremented' => 'refreshViewCount'];

    public function mount($model, $size = 'md', $showCount = true, $autoIncrement = true)
    {
        $this->model = $model;
        $this->modelType = get_class($model);
        $this->modelId = $model->id;
        $this->size = $size;
        $this->showCount = $showCount;
        $this->autoIncrement = $autoIncrement;
        
        $this->refreshViewCount();
        
        // Auto-incrementa le visualizzazioni se abilitato
        if ($this->autoIncrement) {
            $this->incrementView();
        }
    }

    public function incrementView()
    {
        try {
            $this->model->incrementViewIfNotOwner();
            $this->refreshViewCount();
            
            // Emetti evento per aggiornare altri componenti
            $this->dispatch('viewIncremented', [
                'modelType' => $this->modelType,
                'modelId' => $this->modelId,
                'viewCount' => $this->viewCount
            ]);

        } catch (\Exception $e) {
            // Silently fail per le visualizzazioni
        }
    }

    public function refreshViewCount()
    {
        // Ricarica il modello dal database per avere i dati aggiornati
        $this->model = $this->model->fresh();
        $this->viewCount = $this->model->views()->count();
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
        return view('livewire.social.social-view-counter');
    }
}
