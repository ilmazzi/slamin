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
        'is_muted',
        'is_active',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'last_read_at' => 'datetime',
        'is_muted' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relazioni
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMuted($query)
    {
        return $query->where('is_muted', true);
    }

    public function scopeNotMuted($query)
    {
        return $query->where('is_muted', false);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeModerators($query)
    {
        return $query->whereIn('role', ['admin', 'moderator']);
    }

    // Metodi
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isModerator(): bool
    {
        return $this->role === 'admin' || $this->role === 'moderator';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    public function isMuted(): bool
    {
        return $this->is_muted;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function mute(): void
    {
        $this->update(['is_muted' => true]);
    }

    public function unmute(): void
    {
        $this->update(['is_muted' => false]);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    public function markAsRead(): void
    {
        $this->update(['last_read_at' => now()]);
    }

    public function getUnreadCount(): int
    {
        if (!$this->last_read_at) {
            return $this->chat->messages()->count();
        }

        return $this->chat->messages()->where('created_at', '>', $this->last_read_at)->count();
    }

    public function canSendMessages(): bool
    {
        return $this->is_active && !$this->is_muted;
    }

    public function canModerate(): bool
    {
        return $this->isModerator() && $this->is_active;
    }

    public function canManageParticipants(): bool
    {
        return $this->isAdmin() && $this->is_active;
    }

    // Metodi per cambiare ruolo
    public function promoteToModerator(): bool
    {
        if ($this->isAdmin()) {
            return false; // Non può essere promosso se è già admin
        }

        $this->update(['role' => 'moderator']);
        return true;
    }

    public function promoteToAdmin(): bool
    {
        $this->update(['role' => 'admin']);
        return true;
    }

    public function demoteToMember(): bool
    {
        if ($this->isAdmin()) {
            return false; // Non può essere declassato se è admin
        }

        $this->update(['role' => 'member']);
        return true;
    }

    // Metodi statici
    public static function findParticipant(Chat $chat, User $user): ?ChatParticipant
    {
        return self::where('chat_id', $chat->id)
                  ->where('user_id', $user->id)
                  ->first();
    }

    public static function addParticipant(Chat $chat, User $user, string $role = 'member'): ChatParticipant
    {
        return self::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'role' => $role,
            'joined_at' => now(),
        ]);
    }

    public static function removeParticipant(Chat $chat, User $user): bool
    {
        return self::where('chat_id', $chat->id)
                  ->where('user_id', $user->id)
                  ->delete();
    }
}
