<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ForumComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'parent_id',
        'user_id',
        'content',
        'original_language',
        'upvotes',
        'downvotes',
        'score',
        'depth',
        'is_deleted',
        'deleted_at',
        'deleted_by',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'upvotes' => 'integer',
        'downvotes' => 'integer',
        'score' => 'integer',
        'depth' => 'integer',
        'is_deleted' => 'boolean',
        'deleted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Post relationship
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(ForumPost::class, 'post_id');
    }

    /**
     * Parent comment relationship
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ForumComment::class, 'parent_id');
    }

    /**
     * Child comments relationship
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ForumComment::class, 'parent_id')
            ->orderBy('score', 'desc')
            ->orderBy('created_at', 'asc');
    }

    /**
     * User (author) relationship
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Votes relationship (polymorphic)
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(ForumVote::class, 'voteable');
    }

    /**
     * Get user's vote on this comment
     */
    public function getUserVote(?User $user)
    {
        if (!$user) return null;
        
        return $this->votes()
            ->where('user_id', $user->id)
            ->first();
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
     * Soft delete (mark as deleted but keep in DB)
     */
    public function softDelete(User $user): void
    {
        $this->update([
            'is_deleted' => true,
            'deleted_at' => now(),
            'deleted_by' => $user->id,
            'content' => '[deleted]'
        ]);
    }

    /**
     * Scope for not deleted
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', false);
    }

    /**
     * Scope for approved comments
     */
    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }

    /**
     * Check if comment is approved
     */
    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }

    /**
     * Check if user can reply (depth limit)
     */
    public function canReply(): bool
    {
        $maxDepth = ForumSetting::get('comment_depth_limit', 3);
        return $this->depth < $maxDepth;
    }
}
