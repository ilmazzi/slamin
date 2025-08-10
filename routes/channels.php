<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\ChatRoom;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user-logins', fn($user) => true);

Broadcast::channel('user-presence', fn () => true);

// Chat room private channel: consenti solo ai partecipanti della stanza
Broadcast::channel('chat.room.{room}', function ($user, $room) {
    // Se $room è un ID numerico, trova la ChatRoom
    if (is_numeric($room)) {
        $chatRoom = ChatRoom::find($room);
        if (!$chatRoom) {
            return false;
        }
        return $chatRoom->participants()->where('user_id', $user->id)->exists();
    }

    // Se $room è già un modello ChatRoom
    if ($room instanceof ChatRoom) {
        return $room->participants()->where('user_id', $user->id)->exists();
    }

    return false;
});

