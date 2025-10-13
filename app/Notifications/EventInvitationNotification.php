<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\EventInvitation;

class EventInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invitation;

    /**
     * Create a new notification instance.
     */
    public function __construct(EventInvitation $invitation)
    {
        $this->invitation = $invitation;
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
        $event = $this->invitation->event;
        $inviter = $this->invitation->inviter;

        return [
            'type' => 'event_invitation',
            'invitation_id' => $this->invitation->id,
            'event_id' => $event->id,
            'event_title' => $event->title,
            'event_category' => $event->category,
            'event_start' => $event->start_datetime?->toDateTimeString(),
            'inviter_id' => $inviter->id,
            'inviter_name' => $inviter->getDisplayName(),
            'message' => $inviter->getDisplayName() . ' ti ha invitato all\'evento: ' . $event->title,
            'url' => route('events.show', $event),
            'action' => 'view_invitation',
            'icon' => 'ph-envelope',
            'color' => 'primary',
        ];
    }
}
