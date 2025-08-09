<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'sender_id',
        'content',
        'message_type',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'metadata',
        'reply_to',
        'is_edited',
        'edited_at',
        'is_deleted',
        'deleted_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_edited' => 'boolean',
        'is_deleted' => 'boolean',
        'edited_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relazione con la chat room
     */
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    /**
     * Relazione con il mittente
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relazione con il messaggio a cui si risponde
     */
    public function replyTo()
    {
        return $this->belongsTo(ChatMessage::class, 'reply_to');
    }

    /**
     * Relazione con le letture del messaggio
     */
    public function reads()
    {
        return $this->hasMany(ChatMessageRead::class);
    }

    /**
     * Relazione con le reazioni al messaggio
     */
    public function reactions()
    {
        return $this->hasMany(ChatMessageReaction::class);
    }

    /**
     * Verifica se il messaggio è stato letto da un utente
     */
    public function isReadBy(int $userId): bool
    {
        return $this->reads()->where('user_id', $userId)->exists();
    }

    /**
     * Verifica se il messaggio è di tipo testo
     */
    public function isText(): bool
    {
        return $this->message_type === 'text';
    }

    /**
     * Verifica se il messaggio è di tipo file
     */
    public function isFile(): bool
    {
        return in_array($this->message_type, ['image', 'file', 'audio', 'video']);
    }

    /**
     * Ottieni il nome del file per i messaggi di tipo file
     */
    public function getFileName(): string
    {
        return $this->file_name ?: basename($this->file_path);
    }
}
