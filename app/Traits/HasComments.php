<?php

namespace App\Traits;

use App\Models\User;
use App\Models\UnifiedComment;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasComments
{
    /**
     * Get all comments for this model
     */
    public function comments(): HasMany
    {
        return $this->hasMany(UnifiedComment::class, 'commentable_id')->where('commentable_type', get_class($this));
    }

    /**
     * Get approved comments for this model
     */
    public function approvedComments(): HasMany
    {
        return $this->comments()->where('status', 'approved');
    }

    /**
     * Get top-level comments (not replies)
     */
    public function topLevelComments(): HasMany
    {
        return $this->comments()->whereNull('parent_id')->where('status', 'approved');
    }

    /**
     * Add a comment to this model
     */
    public function addComment(User $user, string $content, $parentId = null): UnifiedComment
    {
        $comment = $this->comments()->create([
            'user_id' => $user->id,
            'content' => $content,
            'parent_id' => $parentId,
            'commentable_type' => get_class($this),
            'commentable_id' => $this->id,
            'status' => 'approved', // Auto-approve for now
        ]);
        
        return $comment;
    }

    /**
     * Get the number of comments
     */
    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->where('status', 'approved')->count();
    }

    /**
     * Get the number of comments (alias for compatibility)
     */
    public function getCommentCountAttribute(): int
    {
        return $this->getCommentsCountAttribute();
    }

    /**
     * Get pending comments count
     */
    public function getPendingCommentsCountAttribute(): int
    {
        return $this->comments()->where('status', 'pending')->count();
    }
}
