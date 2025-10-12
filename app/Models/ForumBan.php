<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumBan extends Model
{
    protected $fillable = [
        'subreddit_id',
        'user_id',
        'reason',
        'type',
        'expires_at',
        'banned_by',
        'is_active',
        'lifted_at',
        'lifted_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'lifted_at' => 'datetime',
    ];

    public function subreddit(): BelongsTo
    {
        return $this->belongsTo(Subreddit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'banned_by');
    }

    public function isActive(): bool
    {
        if (!$this->is_active) return false;
        
        if ($this->type === 'permanent') return true;
        
        return $this->expires_at === null || $this->expires_at->isFuture();
    }
}
