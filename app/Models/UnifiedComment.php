<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnifiedComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'commentable_type',
        'commentable_id',
        'content',
        'parent_id',
        'status',
        'moderated_by',
        'moderated_at',
        'moderation_notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'moderated_at' => 'datetime',
    ];

    /**
     * Relazione con l'utente che ha scritto il commento
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione polimorfica con il contenuto commentato
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relazione con il commento padre (per risposte)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(UnifiedComment::class, 'parent_id');
    }

    /**
     * Relazione con i commenti figli (risposte)
     */
    public function replies(): HasMany
    {
        return $this->hasMany(UnifiedComment::class, 'parent_id');
    }

    /**
     * Relazione con il moderatore
     */
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    /**
     * Scope per commenti approvati
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope per commenti in attesa
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope per commenti rifiutati
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope per commenti di primo livello (non risposte)
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Verifica se il commento è una risposta
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * Verifica se il commento può essere modificato dall'utente
     */
    public function canBeEditedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $user->id === $this->user_id || $user->hasRole('admin');
    }
}
