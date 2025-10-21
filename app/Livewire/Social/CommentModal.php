<?php

namespace App\Livewire\Social;

use Livewire\Component;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CommentModal extends Component
{
    public Model $content;
    public string $type;
    public bool $showModal = false;
    
    public string $newComment = '';
    public $comments = [];
    
    protected $listeners = [
        'openCommentModal' => 'openModal',
        'closeCommentModal' => 'closeModal',
        'commentAdded' => 'refreshComments'
    ];

    public function mount(Model $content, string $type)
    {
        $this->content = $content;
        $this->type = $type;
        $this->loadComments();
    }

    public function openModal()
    {
        if (!auth()->check()) {
            $this->dispatch('show-auth-modal');
            return;
        }

        $this->showModal = true;
        $this->loadComments();
    }

    public function closeModal()
    {
        $this->showModal = false;
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

        try {
            $user = auth()->user();
            $comment = $this->content->addComment($user, trim($this->newComment));
            
            $this->newComment = '';
            $this->loadComments();
            
            // Dispatch event per aggiornare il count nel pulsante
            $this->dispatch('commentAdded', [
                'contentId' => $this->content->id,
                'contentType' => $this->type,
                'commentCount' => $this->content->comment_count
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Errore durante l\'aggiunta del commento');
        }
    }

    public function loadComments()
    {
        $this->comments = $this->content->topLevelComments()
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function refreshComments()
    {
        $this->loadComments();
    }

    public function render()
    {
        return view('livewire.social.comment-modal');
    }
}
