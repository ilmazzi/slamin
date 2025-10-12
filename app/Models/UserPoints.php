<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPoints extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_points',
        'portal_points',
        'event_points',
        'level',
        'badges_count',
    ];

    protected $casts = [
        'total_points' => 'integer',
        'portal_points' => 'integer',
        'event_points' => 'integer',
        'level' => 'integer',
        'badges_count' => 'integer',
    ];

    /**
     * User who owns these points
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get current level details
     */
    public function getCurrentLevelAttribute()
    {
        return GamificationLevel::where('level', $this->level)->first();
    }

    /**
     * Get next level details
     */
    public function getNextLevelAttribute()
    {
        return GamificationLevel::where('level', $this->level + 1)->first();
    }

    /**
     * Get progress to next level (0-100)
     */
    public function getProgressToNextLevelAttribute(): int
    {
        $nextLevel = $this->next_level;
        
        if (!$nextLevel) {
            return 100; // Max level reached
        }

        $currentLevelPoints = $this->current_level ? $this->current_level->required_points : 0;
        $pointsNeeded = $nextLevel->required_points - $currentLevelPoints;
        $pointsEarned = max(0, $this->total_points - $currentLevelPoints);

        if ($pointsNeeded <= 0) {
            return 100;
        }

        return min(100, (int) (($pointsEarned / $pointsNeeded) * 100));
    }

    /**
     * Scope: top users by total points
     */
    public function scopeTopByPoints($query, int $limit = 10)
    {
        return $query->orderByDesc('total_points')->limit($limit);
    }

    /**
     * Scope: top users by level
     */
    public function scopeTopByLevel($query, int $limit = 10)
    {
        return $query->orderByDesc('level')
            ->orderByDesc('total_points')
            ->limit($limit);
    }
}

