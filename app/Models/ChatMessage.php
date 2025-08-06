<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'metadata',
        'is_edited',
        'edited_at',
        'is_deleted',
        'deleted_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_edited' => 'boolean',
        'edited_at' => 'datetime',
        'is_deleted' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the chat this message belongs to
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Get the user who sent this message
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for non-deleted messages
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', false);
    }

    /**
     * Scope for text messages only
     */
    public function scopeTextOnly($query)
    {
        return $query->where('type', 'text');
    }

    /**
     * Scope for recent messages
     */
    public function scopeRecent($query, $limit = 50)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Check if message is from a specific user
     */
    public function isFromUser(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute(): ?string
    {
        if (!$this->file_size) {
            return null;
        }

        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if message is editable (within time limit)
     */
    public function isEditable(): bool
    {
        return !$this->is_deleted &&
               $this->created_at->diffInMinutes(now()) <= 15; // 15 minuti per modificare
    }

    /**
     * Check if message is deletable
     */
    public function isDeletable(): bool
    {
        return !$this->is_deleted &&
               $this->created_at->diffInMinutes(now()) <= 60; // 1 ora per eliminare
    }
}
