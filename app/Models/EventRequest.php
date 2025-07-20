<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class EventRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'event_id',
        'user_id',
        'message',
        'requested_role',
        'portfolio_links',
        'experience',
        'status',
        'organizer_response',
        'reviewed_by',
        'reviewed_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'portfolio_links' => 'array',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the event this request belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who made the request
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who reviewed the request
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope: Pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: Accepted requests
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    /**
     * Scope: For specific event
     */
    public function scopeForEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    /**
     * Accept the request
     */
    public function accept(User $reviewer, string $response = null): bool
    {
        if (!$this->canBeAccepted()) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_ACCEPTED,
            'organizer_response' => $response,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => Carbon::now(),
        ]);

        // Create notification for the applicant
        Notification::create([
            'user_id' => $this->user_id,
            'type' => 'request_accepted',
            'title' => 'Richiesta Accettata!',
            'message' => 'La tua richiesta per partecipare a "' . $this->event->title . '" è stata accettata!',
            'data' => json_encode([
                'event_id' => $this->event_id,
                'request_id' => $this->id,
                'reviewer_id' => $reviewer->id,
            ]),
            'action_url' => '/events/' . $this->event_id,
            'action_text' => 'Vedi Evento',
            'priority' => 'high',
        ]);

        return true;
    }

    /**
     * Decline the request
     */
    public function decline(User $reviewer, string $response = null): bool
    {
        if (!$this->canBeDeclined()) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_DECLINED,
            'organizer_response' => $response,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => Carbon::now(),
        ]);

        // Create notification for the applicant
        Notification::create([
            'user_id' => $this->user_id,
            'type' => 'request_declined',
            'title' => 'Richiesta Rifiutata',
            'message' => 'La tua richiesta per partecipare a "' . $this->event->title . '" non è stata accettata.',
            'data' => json_encode([
                'event_id' => $this->event_id,
                'request_id' => $this->id,
                'reviewer_id' => $reviewer->id,
            ]),
            'action_url' => '/events/' . $this->event_id,
        ]);

        return true;
    }

    /**
     * Cancel the request (by the user)
     */
    public function cancel(): bool
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_CANCELLED,
        ]);

        // Create notification for organizer
        Notification::create([
            'user_id' => $this->event->organizer_id,
            'type' => 'request_cancelled',
            'title' => 'Richiesta Cancellata',
            'message' => $this->user->name . ' ha cancellato la richiesta per "' . $this->event->title . '"',
            'data' => json_encode([
                'event_id' => $this->event_id,
                'request_id' => $this->id,
                'user_id' => $this->user_id,
            ]),
            'action_url' => '/events/' . $this->event_id . '/manage',
        ]);

        return true;
    }

    /**
     * Check if request can be accepted
     */
    public function canBeAccepted(): bool
    {
        return $this->status === self::STATUS_PENDING &&
               !$this->event->isFull() &&
               $this->event->start_datetime > Carbon::now();
    }

    /**
     * Check if request can be declined
     */
    public function canBeDeclined(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if request can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if user can apply to event
     */
    public static function canUserApplyToEvent(User $user, Event $event): bool
    {
        // Check if event accepts requests
        if (!$event->acceptsRequests()) {
            return false;
        }

        // Check if user already has a request for this event
        if (self::where('event_id', $event->id)->where('user_id', $user->id)->exists()) {
            return false;
        }

        // Check if user already has an invitation for this event
        if (EventInvitation::where('event_id', $event->id)->where('invited_user_id', $user->id)->exists()) {
            return false;
        }

        // Check if event is full
        if ($event->isFull()) {
            return false;
        }

        return true;
    }

    /**
     * Create a new request with notification
     */
    public static function createWithNotification(array $data): self
    {
        $request = self::create($data);

        // Create notification for organizer
        Notification::create([
            'user_id' => $request->event->organizer_id,
            'type' => 'new_request',
            'title' => 'Nuova Richiesta Partecipazione',
            'message' => $request->user->name . ' vuole partecipare a "' . $request->event->title . '"',
            'data' => json_encode([
                'event_id' => $request->event_id,
                'request_id' => $request->id,
                'user_id' => $request->user_id,
            ]),
            'action_url' => '/events/' . $request->event_id . '/manage',
            'action_text' => 'Gestisci Richieste',
            'priority' => 'normal',
        ]);

        return $request;
    }
}
