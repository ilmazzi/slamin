<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumModerator extends Model
{
    protected $fillable = [
        'subreddit_id',
        'user_id',
        'role',
        'permissions',
        'added_by',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function subreddit(): BelongsTo
    {
        return $this->belongsTo(Subreddit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
