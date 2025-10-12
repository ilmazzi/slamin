<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'subreddit_id',
        'user_id',
        'title',
        'content',
        'type',
        'url',
        'image_path',
        'original_image_name',
        'upvotes',
        'downvotes',
        'score',
        'comments_count',
        'views_count',
        'is_sticky',
        'is_locked',
        'is_archived',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'upvotes' => 'integer',
        'downvotes' => 'integer',
        'score' => 'integer',
        'comments_count' => 'integer',
        'views_count' => 'integer',
        'is_sticky' => 'boolean',
        'is_locked' => 'boolean',
        'is_archived' => 'boolean',
        'approved_at' => 'datetime',
    ];

    /**
     * Subreddit relationship
     */
    public function subreddit(): BelongsTo
    {
        return $this->belongsTo(Subreddit::class);
    }

    /**
     * User (author) relationship
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Comments relationship
     */
    public function comments(): HasMany
    {
        return $this->hasMany(ForumComment::class, 'post_id');
    }

    /**
     * Root comments (depth 0) relationship
     */
    public function rootComments(): HasMany
    {
        return $this->hasMany(ForumComment::class, 'post_id')
            ->whereNull('parent_id')
            ->orderBy('score', 'desc')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Votes relationship (polymorphic)
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(ForumVote::class, 'voteable');
    }

    /**
     * Get user's vote on this post
     */
    public function getUserVote(?User $user)
    {
        if (!$user) return null;
        
        return $this->votes()
            ->where('user_id', $user->id)
            ->first();
    }

    /**
     * Increment views count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Update score (upvotes - downvotes)
     */
    public function updateScore(): void
    {
        $this->update([
            'score' => $this->upvotes - $this->downvotes
        ]);
    }

    /**
     * Scope for approved posts
     */
    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }

    /**
     * Scope for pending approval
     */
    public function scopePending($query)
    {
        return $query->whereNull('approved_at');
    }

    /**
     * Scope for hot posts (Reddit algorithm)
     */
    public function scopeHot($query)
    {
        return $query->selectRaw('*, (upvotes - downvotes) / POWER(TIMESTAMPDIFF(HOUR, created_at, NOW()) + 2, 1.5) as hotness')
            ->orderBy('is_sticky', 'desc')
            ->orderByRaw('hotness DESC')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Scope for top posts
     */
    public function scopeTop($query, $timeframe = 'all')
    {
        $query->orderBy('is_sticky', 'desc')->orderBy('score', 'desc');

        switch ($timeframe) {
            case 'today':
                return $query->whereDate('created_at', today());
            case 'week':
                return $query->where('created_at', '>=', now()->subWeek());
            case 'month':
                return $query->where('created_at', '>=', now()->subMonth());
            case 'year':
                return $query->where('created_at', '>=', now()->subYear());
            default:
                return $query;
        }
    }

    /**
     * Scope for new posts
     */
    public function scopeNew($query)
    {
        return $query->orderBy('is_sticky', 'desc')->orderBy('created_at', 'desc');
    }

    /**
     * Check if post is approved
     */
    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) return null;
        
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        return asset('storage/' . $this->image_path);
    }
}
