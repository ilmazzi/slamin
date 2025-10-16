<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'body',
        'type',
        'metadata',
        'reply_to',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_to');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Message::class, 'reply_to');
    }

    public function isText(): bool
    {
        return $this->type === 'text';
    }

    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function isFile(): bool
    {
        return $this->type === 'file';
    }

    public function isEmoji(): bool
    {
        return $this->type === 'emoji';
    }

    public function getAttachmentUrl(): ?string
    {
        return $this->metadata['url'] ?? null;
    }

    public function getReactions(): array
    {
        return $this->metadata['reactions'] ?? [];
    }

    public function addReaction(string $emoji, int $userId): void
    {
        $reactions = $this->getReactions();
        if (!isset($reactions[$emoji])) {
            $reactions[$emoji] = [];
        }
        if (!in_array($userId, $reactions[$emoji])) {
            $reactions[$emoji][] = $userId;
        }
        $this->update(['metadata' => array_merge($this->metadata ?? [], ['reactions' => $reactions])]);
    }

    public function removeReaction(string $emoji, int $userId): void
    {
        $reactions = $this->getReactions();
        if (isset($reactions[$emoji])) {
            $reactions[$emoji] = array_filter($reactions[$emoji], fn($id) => $id !== $userId);
            if (empty($reactions[$emoji])) {
                unset($reactions[$emoji]);
            }
        }
        $this->update(['metadata' => array_merge($this->metadata ?? [], ['reactions' => $reactions])]);
    }
}
