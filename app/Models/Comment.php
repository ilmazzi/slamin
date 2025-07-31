<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasLikes;
use App\Traits\HasViews;

class Comment extends Model
{
    use HasFactory, HasLikes, HasViews;

    protected $table = 'unified_comments';

    protected $fillable = [
        'user_id',
        'commentable_type',
        'commentable_id',
        'content',
        'status',
        'parent_id',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Relazione con l'utente
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione polimorfa con il contenuto commentato
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
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Relazione con i commenti figli (risposte)
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
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
     * Verifica se il commento è approvato
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Verifica se il commento è in attesa
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Verifica se il commento è una risposta
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * Verifica se il commento ha risposte
     */
    public function hasReplies(): bool
    {
        return $this->replies()->count() > 0;
    }
}
