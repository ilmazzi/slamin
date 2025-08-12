<?php

namespace App\Traits;

use App\Models\User;
use App\Models\ArticleLike;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasLikes
{
    /**
     * Get all likes for this model
     */
    public function likes(): HasMany
    {
        return $this->hasMany(ArticleLike::class, 'article_id');
    }

    /**
     * Get users who liked this model
     */
    public function likedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'article_likes', 'article_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Check if a user has liked this model
     */
    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Like this model by a user
     */
    public function like(User $user): bool
    {
        if ($this->isLikedBy($user)) {
            return false;
        }

        $this->likes()->create(['user_id' => $user->id]);
        $this->increment('likes_count');
        
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
        $this->decrement('likes_count');
        
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
}
