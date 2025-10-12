<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBadge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'badge_id',
        'earned_at',
        'metadata',
        'progress',
        'awarded_by',
        'admin_notes',
        'show_in_sidebar',
        'show_in_profile',
        'sidebar_order',
        'profile_order',
    ];

    protected $casts = [
        'earned_at' => 'datetime',
        'metadata' => 'array',
        'progress' => 'integer',
        'show_in_sidebar' => 'boolean',
        'show_in_profile' => 'boolean',
        'sidebar_order' => 'integer',
        'profile_order' => 'integer',
    ];

    /**
     * User who earned this badge
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Badge that was earned
     */
    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class);
    }

    /**
     * Admin who manually awarded this badge (if applicable)
     */
    public function awardedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'awarded_by');
    }

    /**
     * Check if badge was manually awarded
     */
    public function wasManuallyAwarded(): bool
    {
        return !is_null($this->awarded_by);
    }

    /**
     * Scope: recently earned
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('earned_at', '>=', now()->subDays($days));
    }
}

