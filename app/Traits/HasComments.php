<?php

namespace App\Traits;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComments
{
    /**
     * Relazione con i commenti
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Relazione con i commenti approvati
     */
    public function approvedComments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->approved();
    }

    /**
     * Relazione con i commenti in attesa
     */
    public function pendingComments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->pending();
    }

    /**
     * Aggiunge un commento
     */
    public function addComment($content, $user = null, $parentId = null): ?Comment
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user || !$user->id) {
            return null;
        }

        // Verifica se i commenti sono abilitati per questo tipo di contenuto
        if (!$this->isCommentable()) {
            return null;
        }

        return $this->comments()->create([
            'user_id' => $user->id,
            'content' => $content,
            'parent_id' => $parentId,
            'status' => $this->getCommentStatus(),
        ]);
    }

    /**
     * Ottiene il numero di commenti approvati
     */
    public function getCommentCountAttribute(): int
    {
        return $this->approvedComments()->count();
    }

    /**
     * Ottiene il numero di commenti in attesa
     */
    public function getPendingCommentCountAttribute(): int
    {
        return $this->pendingComments()->count();
    }

    /**
     * Verifica se il contenuto può essere commentato
     */
    public function isCommentable(): bool
    {
        // Controlla le impostazioni di sistema
        $commentableContent = \App\Models\SystemSetting::get('social_commentable_content', ['video', 'photo', 'poem', 'article', 'event']);
        $contentType = $this->getSocialContentType();
        
        return in_array($contentType, $commentableContent);
    }

    /**
     * Ottiene il tipo di contenuto per le impostazioni
     */
    protected function getSocialContentType(): string
    {
        $className = class_basename($this);
        return strtolower($className);
    }

    /**
     * Ottiene lo status di default per i nuovi commenti
     */
    protected function getCommentStatus(): string
    {
        // Controlla se la moderazione automatica è abilitata
        $autoApprove = \App\Models\SystemSetting::get('social_auto_approve_comments', true);
        return $autoApprove ? 'approved' : 'pending';
    }

    /**
     * Scope per contenuti con commenti
     */
    public function scopeWithComments($query)
    {
        return $query->with(['comments.user', 'comments.replies.user']);
    }

    /**
     * Scope per contenuti commentati da un utente
     */
    public function scopeCommentedBy($query, $user)
    {
        if (!$user || !$user->id) {
            return $query;
        }

        return $query->whereHas('comments', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }
}
