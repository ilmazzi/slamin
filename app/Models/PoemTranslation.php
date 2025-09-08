<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoemTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'gig_id',
        'poem_id',
        'translator_id',
        'language',
        'title',
        'content',
        'translator_notes',
        'status',
        'final_compensation',
        'completed_at',
    ];

    protected $casts = [
        'final_compensation' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    /**
     * Relazione con il gig di traduzione
     */
    public function gig(): BelongsTo
    {
        return $this->belongsTo(Gig::class);
    }

    /**
     * Relazione con la poesia originale
     */
    public function poem(): BelongsTo
    {
        return $this->belongsTo(Poem::class);
    }

    /**
     * Relazione con il traduttore
     */
    public function translator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'translator_id');
    }

    /**
     * Scope per stato specifico
     */
    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope per lingua specifica
     */
    public function scopeInLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    /**
     * Scope per traduttore specifico
     */
    public function scopeByTranslator($query, $translatorId)
    {
        return $query->where('translator_id', $translatorId);
    }

    /**
     * Scope per poesia specifica
     */
    public function scopeForPoem($query, $poemId)
    {
        return $query->where('poem_id', $poemId);
    }

    /**
     * Scope per traduzioni approvate
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope per traduzioni in bozza
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope per traduzioni completate
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }
}
