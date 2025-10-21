<?php

namespace App\Livewire\Social;

use Livewire\Component;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SocialCommentButton extends Component
{
    public Model $content;
    public string $type;
    public string $size = 'md';
    
    public int $commentCount = 0;
    public bool $showModal = false;

    protected $listeners = ['refreshCommentButton' => 'refreshCommentCount'];

    public function mount(Model $content, string $type, string $size = 'md')
    {
        $this->content = $content;
        $this->type = $type;
        $this->size = $size;
        
        $this->refreshCommentCount();
    }

    public function openCommentModal()
    {
        if (!auth()->check()) {
            $this->dispatch('show-auth-modal');
            return;
        }

        $this->dispatch('openCommentModal', [
            'contentId' => $this->content->id,
            'contentType' => $this->type
        ]);
    }

    public function closeCommentModal()
    {
        $this->showModal = false;
    }

    public function refreshCommentCount()
    {
        $this->commentCount = $this->content->comment_count;
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
        return view('livewire.social.social-comment-button');
    }
}
