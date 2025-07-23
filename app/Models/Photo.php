<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_path',
        'thumbnail_path',
        'alt_text',
        'status',
        'like_count',
        'view_count',
        'moderation_notes',
        'metadata',
    ];

    protected $casts = [
        'like_count' => 'integer',
        'view_count' => 'integer',
        'metadata' => 'array',
    ];

    /**
     * Relazione con l'utente
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope per foto approvate
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope per foto in moderazione
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope per foto più popolari
     */
    public function scopePopular($query)
    {
        return $query->orderBy('like_count', 'desc')->orderBy('view_count', 'desc');
    }

    /**
     * Verifica se la foto è approvata
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Verifica se la foto è in moderazione
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Ottiene l'URL dell'immagine
     */
    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }
        
        return Storage::url($this->image_path);
    }

    /**
     * Ottiene l'URL del thumbnail
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail_path) {
            if (str_starts_with($this->thumbnail_path, 'http')) {
                return $this->thumbnail_path;
            }
            return Storage::url($this->thumbnail_path);
        }
        
        return $this->image_url;
    }

    /**
     * Incrementa il contatore delle visualizzazioni
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Incrementa il contatore dei like
     */
    public function incrementLikeCount(): void
    {
        $this->increment('like_count');
    }

    /**
     * Decrementa il contatore dei like
     */
    public function decrementLikeCount(): void
    {
        $this->decrement('like_count');
    }

    /**
     * Ottiene il titolo di visualizzazione
     */
    public function getDisplayTitleAttribute(): string
    {
        return $this->title ?: 'Foto di ' . $this->user->getDisplayName();
    }

    /**
     * Ottiene la descrizione di visualizzazione
     */
    public function getDisplayDescriptionAttribute(): string
    {
        return $this->description ?: 'Foto condivisa su Slamin';
    }
}
