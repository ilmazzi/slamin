<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRanking extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'participant_id',
        'position',
        'total_score',
        'round_scores',
        'badge_id',
        'badge_awarded',
    ];

    protected $casts = [
        'position' => 'integer',
        'total_score' => 'decimal:2',
        'round_scores' => 'array',
        'badge_awarded' => 'boolean',
    ];

    /**
     * Event this ranking belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Participant who achieved this ranking
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(EventParticipant::class, 'participant_id');
    }

    /**
     * Badge awarded for this position (if any)
     */
    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class);
    }

    /**
     * Get medal emoji based on position
     */
    public function getMedalAttribute(): ?string
    {
        return match($this->position) {
            1 => '🥇',
            2 => '🥈',
            3 => '🥉',
            default => null
        };
    }

    /**
     * Check if this is a winning position (top 3)
     */
    public function isWinningPosition(): bool
    {
        return $this->position <= 3;
    }

    /**
     * Scope: winners only (top 3)
     */
    public function scopeWinners($query)
    {
        return $query->where('position', '<=', 3);
    }

    /**
     * Scope: ordered by position
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    /**
     * Scope: badge not yet awarded
     */
    public function scopeBadgeNotAwarded($query)
    {
        return $query->where('badge_awarded', false)->whereNotNull('badge_id');
    }
}

