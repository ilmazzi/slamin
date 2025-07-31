<?php

namespace App\Traits;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasLikes
{
    /**
     * Relazione con i like
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Relazione con gli utenti che hanno messo like
     */
    public function likedBy(): MorphToMany
    {
        return $this->belongsToMany(User::class, 'unified_likes', 'likeable_id', 'user_id')
            ->where('likeable_type', static::class)
            ->withTimestamps();
    }

    /**
     * Verifica se il contenuto è stato likato dall'utente
     */
    public function isLikedBy($user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user || !$user->id) {
            return false;
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Aggiunge un like dell'utente
     */
    public function addLike($user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user || !$user->id) {
            return false;
        }

        if ($this->isLikedBy($user)) {
            return false; // Già likato
        }

        return $this->likes()->create([
            'user_id' => $user->id,
        ]) !== null;
    }

    /**
     * Rimuove un like dell'utente
     */
    public function removeLike($user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user || !$user->id) {
            return false;
        }

        return $this->likes()->where('user_id', $user->id)->delete() > 0;
    }

    /**
     * Toggle del like (aggiunge se non presente, rimuove se presente)
     */
    public function toggleLike($user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user || !$user->id) {
            return false;
        }

        if ($this->isLikedBy($user)) {
            return $this->removeLike($user);
        } else {
            return $this->addLike($user);
        }
    }

    /**
     * Ottiene il numero di like
     */
    public function getLikeCountAttribute(): int
    {
        return $this->likes()->count();
    }

    /**
     * Scope per contenuti likati da un utente
     */
    public function scopeLikedBy($query, $user)
    {
        if (!$user || !$user->id) {
            return $query;
        }

        return $query->whereHas('likes', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }

    /**
     * Scope per contenuti più popolari (ordinati per like)
     */
    public function scopePopular($query)
    {
        return $query->withCount('likes')->orderBy('likes_count', 'desc');
    }
}
