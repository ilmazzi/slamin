<?php

namespace App\Livewire\Social;

use Livewire\Component;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SocialViewCounter extends Component
{
    public Model $content;
    public string $type;
    public string $size = 'md';
    
    public int $viewCount = 0;
    public bool $isLoading = false;

    protected $listeners = ['refreshViewCounter' => 'refreshViewCount'];

    public function mount(Model $content, string $type, string $size = 'md')
    {
        $this->content = $content;
        $this->type = $type;
        $this->size = $size;
        
        $this->refreshViewCount();
    }

    public function incrementView()
    {
        if ($this->isLoading) {
            return;
        }

        $this->isLoading = true;

        try {
            $user = auth()->user();
            $incremented = $this->content->incrementViewIfNotOwner($user);
            
            if ($incremented) {
                $this->viewCount = $this->content->view_count;
                
                // Dispatch event per aggiornare altri componenti
                $this->dispatch('view-incremented', [
                    'contentId' => $this->content->id,
                    'contentType' => $this->type,
                    'viewCount' => $this->viewCount
                ]);
            }
            
        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Errore durante l\'incremento delle visualizzazioni');
        } finally {
            $this->isLoading = false;
        }
    }

    public function refreshViewCount()
    {
        $this->viewCount = $this->content->view_count;
    }

    public function getSizeClassesProperty()
    {
        return [
            'sm' => [
                'button' => 'btn btn-sm',
                'icon' => 'f-s-16',
                'text' => 'f-s-10'
            ],
            'md' => [
                'button' => 'btn',
                'icon' => 'f-s-18',
                'text' => 'f-s-12'
            ],
            'lg' => [
                'button' => 'btn btn-lg',
                'icon' => 'f-s-20',
                'text' => 'f-s-14'
            ],
            'xs' => [
                'button' => 'btn btn-sm',
                'icon' => 'f-s-14',
                'text' => 'f-s-9'
            ]
        ][$this->size] ?? [
            'button' => 'btn',
            'icon' => 'f-s-18',
            'text' => 'f-s-12'
        ];
    }

    public function render()
    {
        return view('livewire.social.social-view-counter');
    }
}
