<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'user_id',
        'parent_id',
        'content',
        'timestamp',
        'status',
        'like_count',
        'report_count',
        'moderation_notes',
    ];

    protected $casts = [
        'timestamp' => 'integer',
        'like_count' => 'integer',
        'report_count' => 'integer',
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
     * Relazione con il commento padre (per risposte)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(VideoComment::class, 'parent_id');
    }

    /**
     * Relazione con i commenti figli (risposte)
     */
    public function replies(): HasMany
    {
        return $this->hasMany(VideoComment::class, 'parent_id');
    }

    /**
     * Scope per commenti approvati
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope per commenti in moderazione
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope per commenti spam
     */
    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    /**
     * Verifica se il commento è approvato
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Verifica se il commento è in moderazione
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Verifica se il commento è spam
     */
    public function isSpam(): bool
    {
        return $this->status === 'spam';
    }

    /**
     * Verifica se il commento è una risposta
     */
    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }

    /**
     * Formatta il timestamp per la visualizzazione
     */
    public function getFormattedTimestampAttribute(): string
    {
        if (!$this->timestamp) {
            return '';
        }

        $minutes = floor($this->timestamp / 60);
        $seconds = $this->timestamp % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
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
     * Incrementa il contatore segnalazioni
     */
    public function incrementReportCount(): void
    {
        $this->increment('report_count');
    }
}
