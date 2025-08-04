<?php

namespace App\Notifications;

use App\Models\Gig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class GigClosed extends Notification implements ShouldQueue
{
    use Queueable;

    public $gig;

    public function __construct(Gig $gig)
    {
        $this->gig = $gig;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Ingaggio Chiuso',
            'message' => "L'ingaggio '{$this->gig->title}' è stato chiuso. Tutte le posizioni sono state coperte.",
            'type' => 'gig_closed',
            'gig_id' => $this->gig->id,
            'event_id' => $this->gig->event_id,
        ];
    }
}
