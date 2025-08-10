<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Questo Event resta qui solo per eventuali fallback server-driven.
 * Non viene usato nel flusso "whisper", ma lo lasciamo pronto:
 * - canale: presence-chat.room.{roomId}
 * - evento: user.typing
 */
class UserTyping implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $roomId;
    public int $sender_id;
    public string $sender_name;
    public bool $is_typing;

    public function __construct(int $roomId, int $senderId, string $senderName, bool $isTyping)
    {
        $this->roomId = $roomId;
        $this->sender_id = $senderId;
        $this->sender_name = $senderName;
        $this->is_typing = $isTyping;
    }

    public function broadcastOn(): array
    {
        return [new PresenceChannel("chat.room.{$this->roomId}")];
    }

    public function broadcastAs(): string
    {
        return 'user.typing';
    }

    public function broadcastWith(): array
    {
        return [
            'sender_id'   => $this->sender_id,
            'sender_name' => $this->sender_name,
            'is_typing'   => $this->is_typing,
        ];
    }
}
