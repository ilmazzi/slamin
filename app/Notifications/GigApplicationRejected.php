<?php

namespace App\Notifications;

use App\Models\GigApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class GigApplicationRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public $application;

    public function __construct(GigApplication $application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Candidatura Rifiutata',
            'message' => "La tua candidatura per '{$this->application->gig->title}' non è stata accettata.",
            'type' => 'gig_application_rejected',
            'gig_id' => $this->application->gig_id,
            'application_id' => $this->application->id,
            'event_id' => $this->application->gig->event_id,
        ];
    }
}
