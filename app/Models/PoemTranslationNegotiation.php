<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoemTranslationNegotiation extends Model
{
    use HasFactory;

    protected $fillable = [
        'gig_application_id',
        'user_id',
        'message_type',
        'message',
        'proposed_compensation',
        'proposed_deadline',
        'is_read',
    ];

    protected $casts = [
        'proposed_compensation' => 'decimal:2',
        'proposed_deadline' => 'date',
        'is_read' => 'boolean',
    ];

    /**
     * Relazione con la candidatura
     */
    public function gigApplication(): BelongsTo
    {
        return $this->belongsTo(GigApplication::class);
    }

    /**
     * Relazione con l'utente che ha scritto il messaggio
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor per ottenere il gig dalla candidatura
     */
    public function getGigAttribute()
    {
        return $this->gigApplication?->gig;
    }

    /**
     * Accessor per ottenere la poesia dal gig
     */
    public function getPoemAttribute()
    {
        return $this->gig?->poem;
    }

    /**
     * Scope per messaggi non letti
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope per tipo di messaggio
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('message_type', $type);
    }

    /**
     * Scope per candidatura specifica
     */
    public function scopeForApplication($query, $applicationId)
    {
        return $query->where('gig_application_id', $applicationId);
    }
}
