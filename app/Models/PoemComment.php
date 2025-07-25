<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class PoemComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'poem_id',
        'user_id',
        'content',
        'parent_id',
        'moderation_status',
        'moderation_notes',
        'like_count',
        'is_edited',
        'edited_at'
    ];

    protected $casts = [
        'is_edited' => 'boolean',
        'edited_at' => 'datetime'
    ];

    protected $dates = [
        'edited_at'
    ];

    // Relazioni
    public function poem(): BelongsTo
    {
        return $this->belongsTo(Poem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PoemComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(PoemComment::class, 'parent_id');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'poem_comment_likes')->withTimestamps();
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('moderation_status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('moderation_status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('moderation_status', 'rejected');
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopePopular($query)
    {
        return $query->orderBy('like_count', 'desc');
    }

    // Accessors
    public function getIsLikedByCurrentUserAttribute()
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->likes()->where('user_id', Auth::id())->exists();
    }

    public function getCanBeEditedByCurrentUserAttribute()
    {
        if (!Auth::check()) {
            return false;
        }
        return Auth::id() === $this->user_id;
    }

    public function getCanBeDeletedByCurrentUserAttribute()
    {
        if (!Auth::check()) {
            return false;
        }
        return Auth::id() === $this->user_id || Auth::user()->hasRole('admin');
    }

    public function getCanBeModeratedByCurrentUserAttribute()
    {
        if (!Auth::check()) {
            return false;
        }
        return Auth::user()->hasRole('admin');
    }

    public function getIsReplyAttribute()
    {
        return $this->parent_id !== null;
    }

    public function getHasRepliesAttribute()
    {
        return $this->replies()->count() > 0;
    }

    public function getReplyCountAttribute()
    {
        return $this->replies()->count();
    }

    // Metodi
    public function incrementLikeCount()
    {
        $this->increment('like_count');
    }

    public function decrementLikeCount()
    {
        $this->decrement('like_count');
    }

    public function markAsEdited()
    {
        $this->update([
            'is_edited' => true,
            'edited_at' => now()
        ]);
    }

    public function isApproved()
    {
        return $this->moderation_status === 'approved';
    }

    public function isPending()
    {
        return $this->moderation_status === 'pending';
    }

    public function isRejected()
    {
        return $this->moderation_status === 'rejected';
    }

    public function canBeEditedBy($user)
    {
        return $user && ($user->id === $this->user_id || $user->hasRole('admin'));
    }

    public function canBeDeletedBy($user)
    {
        return $user && ($user->id === $this->user_id || $user->hasRole('admin'));
    }

    public function canBeModeratedBy($user)
    {
        return $user && $user->hasRole('admin');
    }
}
