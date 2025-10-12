<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Subreddit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'rules',
        'icon',
        'banner',
        'color',
        'created_by',
        'is_active',
        'is_private',
        'subscribers_count',
        'posts_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_private' => 'boolean',
        'subscribers_count' => 'integer',
        'posts_count' => 'integer',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subreddit) {
            if (!$subreddit->slug) {
                $subreddit->slug = Str::slug($subreddit->name);
            }
        });
    }

    /**
     * Creator relationship
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Posts relationship
     */
    public function posts(): HasMany
    {
        return $this->hasMany(ForumPost::class);
    }

    /**
     * Moderators relationship
     */
    public function moderators(): HasMany
    {
        return $this->hasMany(ForumModerator::class);
    }

    /**
     * Subscribers relationship (many-to-many)
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subreddit_subscribers')
            ->withTimestamps();
    }

    /**
     * Check if user is subscribed
     */
    public function isSubscribedBy(User $user): bool
    {
        return $this->subscribers()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if user is moderator
     */
    public function isModerator(User $user): bool
    {
        return $this->moderators()->where('user_id', $user->id)->exists();
    }

    /**
     * Scope for active subreddits
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for public subreddits
     */
    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }

    /**
     * Scope for popular subreddits
     */
    public function scopePopular($query)
    {
        return $query->orderBy('subscribers_count', 'desc');
    }

    /**
     * Get route key name (use slug for URLs)
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
