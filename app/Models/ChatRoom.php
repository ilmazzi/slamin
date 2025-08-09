<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'avatar',
        'created_by',
        'is_active',
        'last_message_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_message_at' => 'datetime',
    ];

    /**
     * Relazione con i partecipanti
     */
    public function participants()
    {
        return $this->hasMany(ChatParticipant::class);
    }

    /**
     * Relazione con i messaggi
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * Ultimo messaggio della chat
     */
    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class)->latest();
    }

    /**
     * Creatore della chat
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Verifica se è una chat privata
     */
    public function isPrivate(): bool
    {
        return $this->type === 'private';
    }

    /**
     * Verifica se è una chat di gruppo
     */
    public function isGroup(): bool
    {
        return $this->type === 'group';
    }

    /**
     * Ottieni l'altro partecipante in una chat privata
     */
    public function getOtherParticipant(int $currentUserId): ?ChatParticipant
    {
        return $this->participants()
            ->where('user_id', '!=', $currentUserId)
            ->first();
    }
}
