<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLanguage extends Model
{
    protected $fillable = [
        'user_id',
        'language_name',
        'language_code',
        'type',
        'level',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con l'utente
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope per lingua madre
     */
    public function scopeNative($query)
    {
        return $query->where('type', 'native');
    }

    /**
     * Scope per lingue parlate
     */
    public function scopeSpoken($query)
    {
        return $query->where('type', 'spoken');
    }

    /**
     * Scope per lingue scritte
     */
    public function scopeWritten($query)
    {
        return $query->where('type', 'written');
    }

    /**
     * Ottieni il display name del tipo
     */
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'native' => __('languages.native'),
            'spoken' => __('languages.spoken'),
            'written' => __('languages.written'),
            default => $this->type,
        };
    }

    /**
     * Ottieni il display name del livello
     */
    public function getLevelDisplayAttribute(): string
    {
        if (!$this->level) {
            return '';
        }

        return match($this->level) {
            'excellent' => __('languages.excellent'),
            'good' => __('languages.good'),
            'poor' => __('languages.poor'),
            default => $this->level,
        };
    }

    /**
     * Ottieni la descrizione completa della competenza
     */
    public function getCompetenceDescriptionAttribute(): string
    {
        if ($this->type === 'native') {
            return $this->type_display;
        }

        return $this->type_display . ' ' . $this->level_display;
    }
}
