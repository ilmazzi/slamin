<?php

namespace App\Events\Chat;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Chat\Conversation;
use App\Models\Chat\Message;

class BroadcastMessageEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message, public Conversation $conversation)
    {

        // Log::info($participant);
    }
}
