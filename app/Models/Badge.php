<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'description',
        'icon_path',
        'category',
        'criteria_type',
        'criteria_value',
        'points',
        'order',
        'is_active',
    ];

    protected $casts = [
        'criteria_value' => 'integer',
        'points' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Users who have earned this badge
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withPivot(['earned_at', 'metadata', 'progress', 'awarded_by', 'admin_notes'])
            ->withTimestamps();
    }

    /**
     * User badge pivot records
     */
    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    /**
     * Event rankings that awarded this badge
     */
    public function eventRankings(): HasMany
    {
        return $this->hasMany(EventRanking::class);
    }

    /**
     * Get icon URL
     */
    public function getIconUrlAttribute(): string
    {
        if ($this->icon_path && file_exists(public_path($this->icon_path))) {
            return asset($this->icon_path);
        }
        
        // Fallback to draghetto.png
        return asset('assets/images/draghetto.png');
    }

    /**
     * Check if this is a portal badge
     */
    public function isPortalBadge(): bool
    {
        return $this->type === 'portal';
    }

    /**
     * Check if this is an event badge
     */
    public function isEventBadge(): bool
    {
        return $this->type === 'event';
    }

    /**
     * Scope: only active badges
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: portal badges
     */
    public function scopePortal($query)
    {
        return $query->where('type', 'portal');
    }

    /**
     * Scope: event badges
     */
    public function scopeEvent($query)
    {
        return $query->where('type', 'event');
    }

    /**
     * Scope: by category
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: ordered
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('criteria_value');
    }
}

