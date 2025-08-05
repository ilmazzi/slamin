<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebRTCSignal implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fromUserId;
    public $targetUserId;
    public $signal;
    public $signalType;

    public function __construct($fromUserId, $targetUserId, $signal, $signalType)
    {
        $this->fromUserId = $fromUserId;
        $this->targetUserId = $targetUserId;
        $this->signal = $signal;
        $this->signalType = $signalType;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('webrtc.' . $this->targetUserId);
    }

    public function broadcastAs()
    {
        return 'webrtc-signal';
    }

    public function broadcastWith()
    {
        return [
            'from_user_id' => $this->fromUserId,
            'signal' => $this->signal,
            'signal_type' => $this->signalType
        ];
    }
} 