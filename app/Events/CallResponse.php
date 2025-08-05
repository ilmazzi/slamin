<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallResponse implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fromUser;
    public $targetUserId;
    public $accepted;
    public $answer;

    public function __construct($fromUser, $targetUserId, $accepted, $answer = null)
    {
        $this->fromUser = $fromUser;
        $this->targetUserId = $targetUserId;
        $this->accepted = $accepted;
        $this->answer = $answer;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->targetUserId);
    }

    public function broadcastAs()
    {
        return 'call-response';
    }

    public function broadcastWith()
    {
        return [
            'from_user' => [
                'id' => $this->fromUser->id,
                'name' => $this->fromUser->name
            ],
            'accepted' => $this->accepted,
            'answer' => $this->answer
        ];
    }
} 