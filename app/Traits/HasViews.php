<?php

namespace App\Traits;

use App\Models\UnifiedView;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasViews
{
    /**
     * Relazione con le visualizzazioni
     */
    public function views(): MorphMany
    {
        return $this->morphMany(UnifiedView::class, 'viewable');
    }

    /**
     * Relazione con gli utenti che hanno visualizzato
     */
    public function viewedBy(): MorphToMany
    {
        return $this->belongsToMany(User::class, 'unified_views', 'viewable_id', 'user_id')
            ->where('viewable_type', static::class)
            ->withTimestamps();
    }

    /**
     * Verifica se il contenuto è stato visualizzato dall'utente
     */
    public function isViewedBy($user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user || !$user->id) {
            return false;
        }

        return $this->views()->where('user_id', $user->id)->exists();
    }

    /**
     * Incrementa le visualizzazioni (solo se non è l'autore)
     */
    public function incrementViewIfNotOwner($user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }

        // Se c'è un utente autenticato, verifica se è l'autore
        if ($user && $user->id) {
            // Verifica se l'utente è l'autore del contenuto
            if (property_exists($this, 'user_id') && $this->user_id === $user->id) {
                return false; // L'autore non può incrementare le proprie views
            }

            // Se l'utente ha già visualizzato, non incrementare
            if ($this->isViewedBy($user)) {
                return false;
            }

            // Crea record con user_id
            return $this->views()->create([
                'user_id' => $user->id,
                'viewable_type' => get_class($this),
                'viewable_id' => $this->id,
            ]) !== null;
        } else {
            // Per utenti non autenticati, incrementa solo il contatore
            // senza creare record specifici
            $this->increment('view_count');
            return true;
        }
    }

    /**
     * Ottiene il numero di visualizzazioni uniche
     */
    public function getViewCountAttribute(): int
    {
        return $this->views()->count();
    }

    /**
     * Ottiene il numero di visualizzazioni uniche (alias for compatibility)
     */
    public function getViewsCountAttribute(): int
    {
        return $this->getViewCountAttribute();
    }

    /**
     * Scope per contenuti visualizzati da un utente
     */
    public function scopeViewedBy($query, $user)
    {
        if (!$user || !$user->id) {
            return $query;
        }

        return $query->whereHas('views', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }

    /**
     * Scope per contenuti più visti (ordinati per visualizzazioni)
     */
    public function scopeMostViewed($query)
    {
        return $query->withCount('views')->orderBy('views_count', 'desc');
    }
}
