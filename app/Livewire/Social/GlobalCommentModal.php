<?php

namespace App\Livewire\Social;

use Livewire\Component;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class GlobalCommentModal extends Component
{
    public ?Model $content = null;
    public string $type = '';
    public bool $showModal = false;
    
    public string $newComment = '';
    public array $comments = [];
    
    protected $listeners = [
        'openCommentModal' => 'openModal',
        'closeCommentModal' => 'closeModal',
        'commentAdded' => 'refreshComments'
    ];

    public function openModal($contentId = null, $contentType = null)
    {
        if (!auth()->check()) {
            $this->dispatch('show-auth-modal');
            return;
        }

        // Se i parametri sono in un array (evento Livewire)
        if (is_array($contentId)) {
            $contentType = $contentId['contentType'] ?? null;
            $contentId = $contentId['contentId'] ?? null;
        }

        if (!$contentId || !$contentType) {
            $this->dispatch('show-error', 'Parametri mancanti: contentId=' . $contentId . ', contentType=' . $contentType);
            return;
        }

        // Trova il contenuto basato sul tipo
        $this->type = $contentType;
        $this->content = $this->findContent($contentId, $contentType);
        
        if (!$this->content) {
            $this->dispatch('show-error', 'Contenuto non trovato: ' . $contentType . ' ID ' . $contentId);
            return;
        }

        $this->showModal = true;
        $this->loadComments();
        
        $this->dispatch('show-success', 'Modal commenti aperto per ' . $contentType . ' ID ' . $contentId);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->newComment = '';
        $this->content = null;
        $this->type = '';
    }

    public function addComment()
    {
        // Debug: verifica che il metodo sia chiamato
        $this->dispatch('show-success', 'Metodo addComment chiamato!');
        
        if (!auth()->check()) {
            $this->dispatch('show-error', 'Utente non autenticato');
            return;
        }

        if (!$this->content) {
            $this->dispatch('show-error', 'Contenuto non trovato');
            return;
        }

        if (empty(trim($this->newComment))) {
            $this->dispatch('show-error', 'Il commento non può essere vuoto');
            return;
        }

        try {
            $user = auth()->user();
            $comment = $this->content->addComment($user, trim($this->newComment));
            
            $this->newComment = '';
            $this->loadComments();
            
            // Refresh del contenuto per ottenere il count aggiornato
            $this->content->refresh();
            
            // Dispatch event per aggiornare il count nei pulsanti
            $this->dispatch('commentAdded', [
                'contentId' => $this->content->id,
                'contentType' => $this->type,
                'commentCount' => $this->content->comment_count
            ]);
            
            $this->dispatch('show-success', 'Commento aggiunto con successo');
            
        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Errore durante l\'aggiunta del commento: ' . $e->getMessage());
        }
    }

    public function loadComments()
    {
        if (!$this->content) {
            $this->comments = [];
            return;
        }

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

    private function findContent($contentId, $contentType)
    {
        switch ($contentType) {
            case 'event':
                return \App\Models\Event::find($contentId);
            case 'photo':
                return \App\Models\Photo::find($contentId);
            case 'video':
                return \App\Models\Video::find($contentId);
            case 'poem':
                return \App\Models\Poem::find($contentId);
            case 'article':
                return \App\Models\Article::find($contentId);
            default:
                return null;
        }
    }

    public function render()
    {
        return view('livewire.social.global-comment-modal');
    }
}
