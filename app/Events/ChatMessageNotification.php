<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $recipientId;
    public int $roomId;
    public int $senderId;
    public string $senderName;
    public string $avatarUrl;
    public string $preview;

    public function __construct(int $recipientId, int $roomId, int $senderId, string $senderName, string $avatarUrl, string $preview)
    {
        $this->recipientId = $recipientId;
        $this->roomId = $roomId;
        $this->senderId = $senderId;
        $this->senderName = $senderName;
        $this->avatarUrl = $avatarUrl;
        $this->preview = $preview;
    }

    public function broadcastOn(): array
    {
        // Usa canale privato per-utente già definito in routes/channels.php
        return [new PrivateChannel('App.Models.User.' . $this->recipientId)];
    }

    public function broadcastAs(): string
    {
        return 'chat.message.notify';
    }

    public function broadcastWith(): array
    {
        return [
            'roomId' => $this->roomId,
            'senderId' => $this->senderId,
            'senderName' => $this->senderName,
            'avatarUrl' => $this->avatarUrl,
            'preview' => $this->preview,
        ];
    }
}


