<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EventParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'guest_bio',
        'registration_type',
        'status',
        'performance_order',
        'performance_time',
        'notes',
        'added_by',
    ];

    protected $casts = [
        'performance_order' => 'integer',
        'performance_time' => 'datetime',
    ];

    /**
     * Event this participant is in
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * User (if registered)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * User who added this participant
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Scores for this participant
     */
    public function scores(): HasMany
    {
        return $this->hasMany(EventScore::class, 'participant_id');
    }

    /**
     * Ranking for this participant
     */
    public function ranking(): HasOne
    {
        return $this->hasOne(EventRanking::class, 'participant_id');
    }

    /**
     * Get display name (user name or guest name)
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->user ? $this->user->getDisplayName() : $this->guest_name;
    }

    /**
     * Check if participant is a guest (not registered)
     */
    public function isGuest(): bool
    {
        return is_null($this->user_id);
    }

    /**
     * Check if participant is a registered user
     */
    public function isRegisteredUser(): bool
    {
        return !is_null($this->user_id);
    }

    /**
     * Get average score across all rounds
     */
    public function getAverageScoreAttribute(): float
    {
        return $this->scores()->avg('score') ?? 0;
    }

    /**
     * Get total score (sum of all rounds)
     */
    public function getTotalScoreAttribute(): float
    {
        return $this->scores()->sum('score') ?? 0;
    }

    /**
     * Scope: confirmed participants
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope: performed participants
     */
    public function scopePerformed($query)
    {
        return $query->where('status', 'performed');
    }

    /**
     * Scope: registered users only
     */
    public function scopeRegisteredUsers($query)
    {
        return $query->whereNotNull('user_id');
    }

    /**
     * Scope: guests only
     */
    public function scopeGuests($query)
    {
        return $query->whereNull('user_id');
    }

    /**
     * Scope: ordered by performance
     */
    public function scopeOrderedByPerformance($query)
    {
        return $query->orderBy('performance_order');
    }
}

