<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fromUser;
    public $targetUserId;
    public $callType;
    public $offer;

    public function __construct($fromUser, $targetUserId, $callType, $offer = null)
    {
        $this->fromUser = $fromUser;
        $this->targetUserId = $targetUserId;
        $this->callType = $callType;
        $this->offer = $offer;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->targetUserId);
    }

    public function broadcastAs()
    {
        return 'call-request';
    }

    public function broadcastWith()
    {
        return [
            'from_user' => [
                'id' => $this->fromUser->id,
                'name' => $this->fromUser->name,
                'avatar' => $this->fromUser->avatar
            ],
            'call_type' => $this->callType,
            'offer' => $this->offer
        ];
    }
} 