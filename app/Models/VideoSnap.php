<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoSnap extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'user_id',
        'timestamp',
        'title',
        'description',
        'thumbnail_url',
        'status',
        'like_count',
        'view_count',
        'moderation_notes',
    ];

    protected $casts = [
        'timestamp' => 'integer',
        'like_count' => 'integer',
        'view_count' => 'integer',
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
     * Scope per snap approvati
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope per snap in moderazione
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope per snap più popolari
     */
    public function scopePopular($query)
    {
        return $query->orderBy('like_count', 'desc')->orderBy('view_count', 'desc');
    }

    /**
     * Verifica se lo snap è approvato
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Verifica se lo snap è in moderazione
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Formatta il timestamp per la visualizzazione
     */
    public function getFormattedTimestampAttribute(): string
    {
        $minutes = floor($this->timestamp / 60);
        $seconds = $this->timestamp % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Ottiene il link diretto al momento del video
     */
    public function getVideoLinkAttribute(): string
    {
        return $this->video->peertube_url . '?start=' . $this->timestamp;
    }

    /**
     * Incrementa il contatore visualizzazioni
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Incrementa il contatore like
     */
    public function incrementLikeCount(): void
    {
        $this->increment('like_count');
    }

    /**
     * Decrementa il contatore like
     */
    public function decrementLikeCount(): void
    {
        $this->decrement('like_count');
    }

    /**
     * Ottiene il titolo o un titolo di default
     */
    public function getDisplayTitleAttribute(): string
    {
        return $this->title ?: 'Snap a ' . $this->formatted_timestamp;
    }
}
