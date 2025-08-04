<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Gig;

class GigShared extends Notification
{
    use Queueable;

    protected $gig;

    /**
     * Create a new notification instance.
     */
    public function __construct(Gig $gig)
    {
        $this->gig = $gig;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'gig_shared',
            'title' => __('gigs.notifications.shared_title'),
            'message' => __('gigs.notifications.shared_message', [
                'title' => $this->gig->title,
                'event' => $this->gig->event->title ?? 'N/A',
                'type' => __('gigs.types.' . $this->gig->type),
                'location' => $this->gig->location,
                'deadline' => $this->gig->deadline->format('d/m/Y H:i')
            ]),
            'gig_id' => $this->gig->id,
            'event_id' => $this->gig->event_id,
            'user_id' => $this->gig->user_id,
            'url' => route('gigs.show', $this->gig->id)
        ];
    }
}
