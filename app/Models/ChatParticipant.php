<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'user_id',
        'role',
        'is_muted',
        'muted_until',
        'is_banned',
        'banned_until',
        'joined_at',
        'left_at'
    ];

    protected $casts = [
        'is_muted' => 'boolean',
        'is_banned' => 'boolean',
        'muted_until' => 'datetime',
        'banned_until' => 'datetime',
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
    ];

    /**
     * Relazione con la chat room
     */
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    /**
     * Relazione con l'utente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica se il partecipante è admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Verifica se il partecipante è moderatore
     */
    public function isModerator(): bool
    {
        return $this->role === 'moderator';
    }

    /**
     * Verifica se il partecipante è mutato
     */
    public function isMuted(): bool
    {
        if (!$this->is_muted) {
            return false;
        }

        if ($this->muted_until && $this->muted_until->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Verifica se il partecipante è bannato
     */
    public function isBanned(): bool
    {
        if (!$this->is_banned) {
            return false;
        }

        if ($this->banned_until && $this->banned_until->isPast()) {
            return false;
        }

        return true;
    }
}
