<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'user_id',
        'type',
    ];

    /**
     * Relazione con il video
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Relazione con l'utente
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope per like
     */
    public function scopeLikes($query)
    {
        return $query->where('type', 'like');
    }

    /**
     * Scope per dislike
     */
    public function scopeDislikes($query)
    {
        return $query->where('type', 'dislike');
    }

    /**
     * Verifica se è un like
     */
    public function isLike(): bool
    {
        return $this->type === 'like';
    }

    /**
     * Verifica se è un dislike
     */
    public function isDislike(): bool
    {
        return $this->type === 'dislike';
    }

    /**
     * Ottiene il testo del tipo
     */
    public function getTypeTextAttribute(): string
    {
        return $this->type === 'like' ? 'Mi piace' : 'Non mi piace';
    }
}
