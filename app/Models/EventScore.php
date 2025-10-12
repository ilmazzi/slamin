<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'participant_id',
        'judge_id',
        'round',
        'score',
        'notes',
        'scored_at',
    ];

    protected $casts = [
        'round' => 'integer',
        'score' => 'decimal:1',
        'scored_at' => 'datetime',
    ];

    /**
     * Event this score belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Participant who received this score
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(EventParticipant::class, 'participant_id');
    }

    /**
     * Judge who gave this score
     */
    public function judge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    /**
     * Scope: by round
     */
    public function scopeRound($query, int $round)
    {
        return $query->where('round', $round);
    }

    /**
     * Scope: by participant
     */
    public function scopeForParticipant($query, int $participantId)
    {
        return $query->where('participant_id', $participantId);
    }

    /**
     * Scope: by judge
     */
    public function scopeByJudge($query, int $judgeId)
    {
        return $query->where('judge_id', $judgeId);
    }
}

