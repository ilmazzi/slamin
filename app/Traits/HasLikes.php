<?php

namespace App\Traits;

use App\Models\User;
use App\Models\UnifiedLike;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasLikes
{
    /**
     * Get all likes for this model
     */
    public function likes(): HasMany
    {
        return $this->hasMany(UnifiedLike::class, 'likeable_id')->where('likeable_type', get_class($this));
    }

    /**
     * Get users who liked this model
     */
    public function likedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'unified_likes', 'likeable_id', 'user_id')
                    ->where('likeable_type', get_class($this))
                    ->withTimestamps();
    }

    /**
     * Check if a user has liked this model
     */
    public function isLikedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if the current authenticated user has liked this model
     */
    public function isLikedByCurrentUser(): bool
    {
        $user = auth()->user();
        return $this->isLikedBy($user);
    }

    /**
     * Like this model by a user
     */
    public function like(User $user): bool
    {
        if ($this->isLikedBy($user)) {
            return false;
        }

        $this->likes()->create([
            'user_id' => $user->id,
            'likeable_type' => get_class($this),
            'likeable_id' => $this->id
        ]);
        
        // Check for badge achievement
        $badgeService = app(\App\Services\BadgeService::class);
        $badgeService->checkAndAwardBadge($user, 'likes', $this);
        
        return true;
    }

    /**
     * Unlike this model by a user
     */
    public function unlike(User $user): bool
    {
        $like = $this->likes()->where('user_id', $user->id)->first();
        
        if (!$like) {
            return false;
        }

        $like->delete();
        
        return true;
    }

    /**
     * Toggle like status for a user
     */
    public function toggleLike(User $user): bool
    {
        if ($this->isLikedBy($user)) {
            return $this->unlike($user);
        }

        return $this->like($user);
    }

    /**
     * Get the number of likes
     */
    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

    /**
     * Get the number of likes (alias for compatibility)
     */
    public function getLikeCountAttribute(): int
    {
        return $this->getLikesCountAttribute();
    }
}
