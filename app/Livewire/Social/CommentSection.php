<?php

namespace App\Livewire\Social;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CommentSection extends Component
{
    public $contentId;
    public $contentType; // 'video', 'photo', 'poem', 'article', etc.
    public $newComment = '';

    protected $listeners = [
        'commentAdded' => 'refreshComments'
    ];

    public function mount($contentId, $contentType)
    {
        $this->contentId = $contentId;
        $this->contentType = $contentType;
    }

    public function addComment()
    {
        if (!Auth::check()) {
            $this->dispatch('show-auth-modal');
            return;
        }

        if (empty(trim($this->newComment))) {
            return;
        }

        $content = $this->getContent();
        if (!$content) {
            return;
        }

        try {
            $user = Auth::user();
            $content->addComment($user, trim($this->newComment));
            
            $this->newComment = '';
            
            // Dispatch event per aggiornare i contatori negli altri componenti
            $this->dispatch('commentAdded', [
                'contentId' => $this->contentId,
                'contentType' => $this->contentType
            ]);
            
        } catch (\Exception $e) {
            // Handle error silently
        }
    }

    public function refreshComments($data = null)
    {
        // Component will automatically re-render
    }

    private function getContent()
    {
        $modelClass = $this->getModelClass();
        if (!$modelClass) {
            return null;
        }

        try {
            return $modelClass::find($this->contentId);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getModelClass()
    {
        $models = [
            'video' => \App\Models\Video::class,
            'photo' => \App\Models\Photo::class,
            'poem' => \App\Models\Poem::class,
            'article' => \App\Models\Article::class,
        ];

        return $models[$this->contentType] ?? null;
    }

    public function getContentProperty()
    {
        return $this->getContent();
    }

    public function getCommentsProperty()
    {
        if (!$this->content) {
            return collect();
        }

        return $this->content->comments()
            ->with(['user'])
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.social.comment-section', [
            'content' => $this->content,
            'comments' => $this->comments,
        ]);
    }
}
