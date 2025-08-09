<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PresenceUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;
    public string $state;
    public int $ttl;

    public function __construct(int $userId, string $state, int $ttl)
    {
        $this->userId = $userId;
        $this->state  = $state;
        $this->ttl    = $ttl;
    }

    public function broadcastOn(): Channel
    {
        // Canale pubblico
        return new Channel('user-presence');
    }

    public function broadcastAs(): string
    {
        return 'user-presence';
    }
}
