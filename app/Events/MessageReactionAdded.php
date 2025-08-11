<?php

namespace App\Events;

use App\Models\MessageReaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReactionAdded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reaction;
    public $messageId;
    public $roomId;

    /**
     * Create a new event instance.
     */
    public function __construct(MessageReaction $reaction, int $roomId)
    {
        $this->reaction = $reaction;
        $this->messageId = $reaction->message_id;
        $this->roomId = $roomId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.room.' . $this->roomId),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.reaction.added';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'message_id' => $this->messageId,
            'emoji' => $this->reaction->emoji,
            'user' => [
                'id' => $this->reaction->user->id,
                'name' => $this->reaction->user->name,
                'avatar' => \App\Helpers\AvatarHelper::getUserAvatarUrl($this->reaction->user)
            ],
            'reactions' => \App\Models\MessageReaction::getCachedReactions($this->messageId)
        ];
    }
}
