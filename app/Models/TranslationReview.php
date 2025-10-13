<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TranslationReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'language',
        'file',
        'key',
        'is_reviewed',
        'reviewed_by',
        'reviewed_at',
        'notes',
    ];

    protected $casts = [
        'is_reviewed' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the user who reviewed this translation
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope per filtrare solo le chiavi revisionate
     */
    public function scopeReviewed($query)
    {
        return $query->where('is_reviewed', true);
    }

    /**
     * Scope per filtrare solo le chiavi non revisionate
     */
    public function scopeNotReviewed($query)
    {
        return $query->where('is_reviewed', false);
    }

    /**
     * Scope per una specifica lingua e file
     */
    public function scopeForLanguageAndFile($query, string $language, string $file)
    {
        return $query->where('language', $language)->where('file', $file);
    }

    /**
     * Segna come revisionata
     */
    public function markAsReviewed(int $userId, ?string $notes = null): void
    {
        $this->update([
            'is_reviewed' => true,
            'reviewed_by' => $userId,
            'reviewed_at' => now(),
            'notes' => $notes,
        ]);
    }

    /**
     * Rimuovi revisione
     */
    public function unmarkAsReviewed(): void
    {
        $this->update([
            'is_reviewed' => false,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'notes' => null,
        ]);
    }
}

