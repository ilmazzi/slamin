<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class UserLoggedIn implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $name;
    public $email;

    /**
     * Create a new event instance.
     */
    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('admin.logged-users'); // PUBBLICO temporaneamente
    }

    public function broadcastAs()
    {
        return 'UserLoggedIn';
    }
}
