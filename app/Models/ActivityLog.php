<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'category',
        'description',
        'details',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'status_code',
        'response_time',
        'level',
        'related_model',
        'related_id',
    ];

    protected $casts = [
        'details' => 'array',
        'response_time' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Log levels
    const LEVEL_INFO = 'info';
    const LEVEL_WARNING = 'warning';
    const LEVEL_ERROR = 'error';
    const LEVEL_CRITICAL = 'critical';

    // Categories
    const CATEGORY_AUTH = 'authentication';
    const CATEGORY_EVENTS = 'events';
    const CATEGORY_VIDEOS = 'videos';
    const CATEGORY_USERS = 'users';
    const CATEGORY_ADMIN = 'admin';
    const CATEGORY_SYSTEM = 'system';
    const CATEGORY_PREMIUM = 'premium';
    const CATEGORY_MEDIA = 'media';
    const CATEGORY_PERMISSIONS = 'permissions';
    const CATEGORY_CAROUSEL = 'carousel';
    const CATEGORY_TRANSLATIONS = 'translations';
    const CATEGORY_SETTINGS = 'settings';

    /**
     * Get the user that performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to filter by level
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope to filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Get formatted description with user info
     */
    public function getFormattedDescriptionAttribute(): string
    {
        $userName = $this->user ? $this->user->name : 'Guest';
        return "[{$userName}] {$this->description}";
    }

    /**
     * Get short description for display
     */
    public function getShortDescriptionAttribute(): string
    {
        return Str::limit($this->description, 100);
    }

    /**
     * Get related model instance if exists
     */
    public function getRelatedModelInstanceAttribute()
    {
        if (!$this->related_model || !$this->related_id) {
            return null;
        }

        try {
            return $this->related_model::find($this->related_id);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get all available categories
     */
    public static function getCategories(): array
    {
        return [
            self::CATEGORY_AUTH => 'Authentication',
            self::CATEGORY_EVENTS => 'Events',
            self::CATEGORY_VIDEOS => 'Videos',
            self::CATEGORY_USERS => 'Users',
            self::CATEGORY_ADMIN => 'Admin',
            self::CATEGORY_SYSTEM => 'System',
            self::CATEGORY_PREMIUM => 'Premium',
            self::CATEGORY_MEDIA => 'Media',
            self::CATEGORY_PERMISSIONS => 'Permissions',
            self::CATEGORY_CAROUSEL => 'Carousel',
            self::CATEGORY_TRANSLATIONS => 'Translations',
            self::CATEGORY_SETTINGS => 'Settings',
        ];
    }

    /**
     * Get all available levels
     */
    public static function getLevels(): array
    {
        return [
            self::LEVEL_INFO => 'Info',
            self::LEVEL_WARNING => 'Warning',
            self::LEVEL_ERROR => 'Error',
            self::LEVEL_CRITICAL => 'Critical',
        ];
    }

    /**
     * Get level badge class for UI
     */
    public function getLevelBadgeClassAttribute(): string
    {
        return match($this->level) {
            self::LEVEL_INFO => 'badge bg-info',
            self::LEVEL_WARNING => 'badge bg-warning',
            self::LEVEL_ERROR => 'badge bg-danger',
            self::LEVEL_CRITICAL => 'badge bg-dark',
            default => 'badge bg-secondary',
        };
    }

    /**
     * Get category badge class for UI
     */
    public function getCategoryBadgeClassAttribute(): string
    {
        return match($this->category) {
            self::CATEGORY_AUTH => 'badge bg-primary',
            self::CATEGORY_EVENTS => 'badge bg-success',
            self::CATEGORY_VIDEOS => 'badge bg-info',
            self::CATEGORY_USERS => 'badge bg-warning',
            self::CATEGORY_ADMIN => 'badge bg-danger',
            self::CATEGORY_SYSTEM => 'badge bg-dark',
            self::CATEGORY_PREMIUM => 'badge bg-purple',
            self::CATEGORY_MEDIA => 'badge bg-teal',
            self::CATEGORY_PERMISSIONS => 'badge bg-indigo',
            self::CATEGORY_CAROUSEL => 'badge bg-pink',
            self::CATEGORY_TRANSLATIONS => 'badge bg-orange',
            self::CATEGORY_SETTINGS => 'badge bg-cyan',
            default => 'badge bg-secondary',
        };
    }
} 