<?php

namespace App\Mail;

use App\Models\EventInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invitation;
    public $event;
    public $inviter;

    /**
     * Create a new message instance.
     */
    public function __construct(EventInvitation $invitation)
    {
        $this->invitation = $invitation;
        $this->event = $invitation->event;
        $this->inviter = $invitation->inviter;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🎭 Invito Poetry Slam: {$this->event->title}",
            from: config('mail.from.address', 'noreply@poetryslam.it'),
            replyTo: $this->inviter->email ?? config('mail.from.address'),
            tags: ['event-invitation', 'poetry-slam'],
            metadata: [
                'event_id' => $this->event->id,
                'invitation_id' => $this->invitation->id,
                'role' => $this->invitation->role,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.event-invitation',
            with: [
                'invitation' => $this->invitation,
                'event' => $this->event,
                'inviter' => $this->inviter,
                'acceptUrl' => route('invitations.accept', $this->invitation),
                'declineUrl' => route('invitations.decline', $this->invitation),
                'eventUrl' => route('events.show', $this->event),
                'role' => ucfirst($this->invitation->role),
                'compensation' => $this->invitation->compensation,
                'expiresAt' => $this->invitation->expires_at,
                'eventDate' => $this->event->start_datetime->format('d F Y'),
                'eventTime' => $this->event->start_datetime->format('H:i') . ' - ' . $this->event->end_datetime->format('H:i'),
                'venueInfo' => $this->event->venue_name . ', ' . $this->event->city,
                'mapUrl' => $this->event->latitude && $this->event->longitude
                    ? "https://maps.google.com/?q={$this->event->latitude},{$this->event->longitude}"
                    : null,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
