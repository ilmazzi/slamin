<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventUpdateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $event;
    public $user;
    public $updateType;
    public $changes;
    public $customMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, User $user, string $updateType = 'general', array $changes = [], string $customMessage = null)
    {
        $this->event = $event;
        $this->user = $user;
        $this->updateType = $updateType;
        $this->changes = $changes;
        $this->customMessage = $customMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjects = [
            'general' => "📢 Aggiornamento: {$this->event->title}",
            'datetime' => "⏰ Cambio orario: {$this->event->title}",
            'location' => "📍 Cambio venue: {$this->event->title}",
            'cancelled' => "🚫 Evento cancellato: {$this->event->title}",
            'reminder' => "⏰ Promemoria: {$this->event->title}",
        ];

        return new Envelope(
            subject: $subjects[$this->updateType] ?? $subjects['general'],
            from: config('mail.from.address', 'noreply@poetryslam.it'),
            tags: ['event-update', 'poetry-slam', $this->updateType],
            metadata: [
                'event_id' => $this->event->id,
                'user_id' => $this->user->id,
                'update_type' => $this->updateType,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.event-update',
            with: [
                'event' => $this->event,
                'user' => $this->user,
                'updateType' => $this->updateType,
                'changes' => $this->changes,
                'customMessage' => $this->customMessage,
                'eventUrl' => route('events.show', $this->event),
                'eventDate' => $this->event->start_datetime->format('d F Y'),
                'eventTime' => $this->event->start_datetime->format('H:i') . ' - ' . $this->event->end_datetime->format('H:i'),
                'venueInfo' => $this->event->venue_name . ', ' . $this->event->city,
                'organizerName' => $this->event->organizer->name,
                'daysUntilEvent' => now()->diffInDays($this->event->start_datetime),
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
