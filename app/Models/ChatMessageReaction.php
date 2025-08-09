<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessageReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'user_id',
        'reaction_type',
        'emoji'
    ];

    /**
     * Relazione con il messaggio
     */
    public function message()
    {
        return $this->belongsTo(ChatMessage::class);
    }

    /**
     * Relazione con l'utente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
