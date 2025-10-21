<?php

namespace App\Livewire\Social;

use Livewire\Component;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SocialLikeButton extends Component
{
    public Model $content;
    public string $type;
    public string $size = 'md';
    
    public bool $isLiked = false;
    public int $likeCount = 0;
    public bool $isLoading = false;

    protected $listeners = ['refreshLikeButton' => 'refreshLikeStatus'];

    public function mount(Model $content, string $type, string $size = 'md')
    {
        $this->content = $content;
        $this->type = $type;
        $this->size = $size;
        
        $this->refreshLikeStatus();
    }

    public function toggleLike()
    {
        if (!auth()->check()) {
            $this->dispatch('show-auth-modal');
            return;
        }

        $this->isLoading = true;

        try {
            $user = auth()->user();
            
            if ($this->isLiked) {
                // Rimuovi like
                $this->content->unlike($user);
                $this->isLiked = false;
                $this->likeCount = max(0, $this->likeCount - 1);
            } else {
                // Aggiungi like
                $this->content->like($user);
                $this->isLiked = true;
                $this->likeCount = $this->likeCount + 1;
            }
            
            // Dispatch event per aggiornare altri componenti
            $this->dispatch('like-toggled', [
                'contentId' => $this->content->id,
                'contentType' => $this->type,
                'isLiked' => $this->isLiked,
                'likeCount' => $this->likeCount
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Errore durante l\'operazione');
        } finally {
            $this->isLoading = false;
        }
    }

    public function refreshLikeStatus()
    {
        $this->isLiked = auth()->check() ? $this->content->isLikedBy(auth()->user()) : false;
        $this->likeCount = $this->content->likes_count;
    }

    public function getSizeClassesProperty()
    {
        return [
            'sm' => [
                'button' => 'btn btn-sm',
                'width' => '24px',
                'height' => '24px',
                'text' => 'f-s-10'
            ],
            'md' => [
                'button' => 'btn',
                'width' => '26px',
                'height' => '26px',
                'text' => 'f-s-12'
            ],
            'lg' => [
                'button' => 'btn btn-lg',
                'width' => '28px',
                'height' => '28px',
                'text' => 'f-s-14'
            ],
            'xs' => [
                'button' => 'btn btn-sm',
                'width' => '22px',
                'height' => '22px',
                'text' => 'f-s-9'
            ]
        ][$this->size] ?? [
            'button' => 'btn',
            'width' => '26px',
            'height' => '26px',
            'text' => 'f-s-12'
        ];
    }

    public function render()
    {
        return view('livewire.social.social-like-button');
    }
}
