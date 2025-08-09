<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $roomId;
    public int $message_id;
    public int $sender_id;
    public string $sender_name;
    public string $avatar_url;
    public string $content;
    public string $time;

    public function __construct(array $payload)
    {
        $this->roomId      = (int) $payload['room_id'];
        $this->message_id  = (int) $payload['message_id'];
        $this->sender_id   = (int) $payload['sender_id'];
        $this->sender_name = (string) $payload['sender_name'];
        $this->avatar_url  = (string) $payload['avatar_url'];
        $this->content     = (string) $payload['content'];
        $this->time        = (string) $payload['time'];
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel("chat.room.{$this->roomId}")];
    }

    public function broadcastAs(): string
    {
        return 'chat.message';
    }

    public function broadcastWith(): array
    {
        return [
            'message_id'  => $this->message_id,
            'sender_id'   => $this->sender_id,
            'sender_name' => $this->sender_name,
            'avatar_url'  => $this->avatar_url,
            'content'     => $this->content,
            'time'        => $this->time,
        ];
    }
}
