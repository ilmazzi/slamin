<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'role',
        'joined_at',
        'last_read_at',
        'is_active',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'last_read_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the chat this participant belongs to
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Get the user who is a participant
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if participant is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if participant is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(): void
    {
        $this->update(['last_read_at' => now()]);
    }

    /**
     * Get unread messages count for this participant
     */
    public function getUnreadCount(): int
    {
        if (!$this->last_read_at) {
            return $this->chat->messages()->count();
        }

        return $this->chat->messages()
            ->where('created_at', '>', $this->last_read_at)
            ->count();
    }

    /**
     * Scope for active participants
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for admin participants
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }
}
