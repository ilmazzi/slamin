<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'group_id',
        'created_by',
        'is_active',
        'last_message_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_message_at' => 'datetime',
    ];

    // Relazioni
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(ChatParticipant::class);
    }

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, ChatParticipant::class, 'chat_id', 'id', 'id', 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(ChatMessage::class)->latest();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForGroup($query, $groupId)
    {
        return $query->where('group_id', $groupId);
    }

    // Metodi
    public function isPrivate(): bool
    {
        return $this->type === 'private';
    }

    public function isGroup(): bool
    {
        return $this->type === 'group';
    }

    public function isGeneral(): bool
    {
        return $this->type === 'general';
    }

    public function hasUser(User $user): bool
    {
        return $this->participants()->where('user_id', $user->id)->exists();
    }

    public function addParticipant(User $user, string $role = 'member'): ChatParticipant
    {
        return $this->participants()->create([
            'user_id' => $user->id,
            'role' => $role,
        ]);
    }

    public function removeParticipant(User $user): bool
    {
        return $this->participants()->where('user_id', $user->id)->delete();
    }

    public function getParticipantRole(User $user): ?string
    {
        $participant = $this->participants()->where('user_id', $user->id)->first();
        return $participant ? $participant->role : null;
    }

    public function isParticipantAdmin(User $user): bool
    {
        return $this->getParticipantRole($user) === 'admin';
    }

    public function isParticipantModerator(User $user): bool
    {
        $role = $this->getParticipantRole($user);
        return $role === 'admin' || $role === 'moderator';
    }

    public function updateLastMessage(): void
    {
        $lastMessage = $this->messages()->latest()->first();
        $this->update(['last_message_at' => $lastMessage ? $lastMessage->created_at : null]);
    }

    public function getUnreadCount(User $user): int
    {
        $participant = $this->participants()->where('user_id', $user->id)->first();
        if (!$participant || !$participant->last_read_at) {
            return $this->messages()->count();
        }

        return $this->messages()->where('created_at', '>', $participant->last_read_at)->count();
    }

    public function markAsRead(User $user): void
    {
        $this->participants()->where('user_id', $user->id)->update([
            'last_read_at' => now(),
        ]);
    }

    // Metodi statici per creare chat
    public static function createPrivate(User $user1, User $user2): Chat
    {
        // Verifica se esiste già una chat privata tra questi utenti
        $existingChat = self::where('type', 'private')
            ->whereHas('participants', function ($query) use ($user1) {
                $query->where('user_id', $user1->id);
            })
            ->whereHas('participants', function ($query) use ($user2) {
                $query->where('user_id', $user2->id);
            })
            ->first();

        if ($existingChat) {
            return $existingChat;
        }

        // Crea nuova chat privata
        $chat = self::create([
            'type' => 'private',
            'created_by' => $user1->id,
        ]);

        // Aggiungi partecipanti
        $chat->addParticipant($user1);
        $chat->addParticipant($user2);

        return $chat;
    }

    public static function createGroupChat(Group $group, User $creator, string $name = null): Chat
    {
        $chat = self::create([
            'name' => $name ?: "Chat di {$group->name}",
            'type' => 'group',
            'group_id' => $group->id,
            'created_by' => $creator->id,
        ]);

        // Aggiungi tutti i membri del gruppo
        foreach ($group->members as $member) {
            $role = $member->role === 'admin' ? 'admin' :
                   ($member->role === 'moderator' ? 'moderator' : 'member');
            $chat->addParticipant($member->user, $role);
        }

        return $chat;
    }

    public static function createGeneralChat(): Chat
    {
        $chat = self::create([
            'name' => 'Chat Generale',
            'type' => 'general',
            'created_by' => null,
        ]);

        // Aggiungi tutti gli utenti
        $users = User::all();
        foreach ($users as $user) {
            $role = $user->hasRole('admin') ? 'admin' : 'member';
            $chat->addParticipant($user, $role);
        }

        return $chat;
    }
}
