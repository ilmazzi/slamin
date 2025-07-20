<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'action_url',
        'action_text',
        'is_read',
        'read_at',
        'is_email_sent',
        'priority',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'is_email_sent' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Priority constants
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Type constants for events
     */
    const TYPE_EVENT_INVITATION = 'event_invitation';
    const TYPE_INVITATION_ACCEPTED = 'invitation_accepted';
    const TYPE_INVITATION_DECLINED = 'invitation_declined';
    const TYPE_NEW_REQUEST = 'new_request';
    const TYPE_REQUEST_ACCEPTED = 'request_accepted';
    const TYPE_REQUEST_DECLINED = 'request_declined';
    const TYPE_REQUEST_CANCELLED = 'request_cancelled';
    const TYPE_EVENT_UPDATE = 'event_update';
    const TYPE_EVENT_CANCELLED = 'event_cancelled';
    const TYPE_EVENT_REMINDER = 'event_reminder';

    /**
     * Get the user this notification belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope: Read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope: By priority
     */
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope: Recent notifications (last 30 days)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays(30));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Get priority badge class for UI
     */
    public function getPriorityBadgeAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_LOW => 'badge bg-secondary',
            self::PRIORITY_NORMAL => 'badge bg-primary',
            self::PRIORITY_HIGH => 'badge bg-warning',
            self::PRIORITY_URGENT => 'badge bg-danger',
            default => 'badge bg-primary',
        };
    }

    /**
     * Get icon class based on notification type
     */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_EVENT_INVITATION => 'ph ph-envelope',
            self::TYPE_INVITATION_ACCEPTED => 'ph ph-check-circle',
            self::TYPE_INVITATION_DECLINED => 'ph ph-x-circle',
            self::TYPE_NEW_REQUEST => 'ph ph-hand-waving',
            self::TYPE_REQUEST_ACCEPTED => 'ph ph-party-popper',
            self::TYPE_REQUEST_DECLINED => 'ph ph-thumbs-down',
            self::TYPE_REQUEST_CANCELLED => 'ph ph-x',
            self::TYPE_EVENT_UPDATE => 'ph ph-calendar-check',
            self::TYPE_EVENT_CANCELLED => 'ph ph-calendar-x',
            self::TYPE_EVENT_REMINDER => 'ph ph-clock',
            default => 'ph ph-bell',
        };
    }

    /**
     * Get color class based on notification type
     */
    public function getColorAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_EVENT_INVITATION => 'text-primary',
            self::TYPE_INVITATION_ACCEPTED, self::TYPE_REQUEST_ACCEPTED => 'text-success',
            self::TYPE_INVITATION_DECLINED, self::TYPE_REQUEST_DECLINED => 'text-danger',
            self::TYPE_NEW_REQUEST => 'text-info',
            self::TYPE_REQUEST_CANCELLED => 'text-warning',
            self::TYPE_EVENT_UPDATE => 'text-primary',
            self::TYPE_EVENT_CANCELLED => 'text-danger',
            self::TYPE_EVENT_REMINDER => 'text-warning',
            default => 'text-primary',
        };
    }

    /**
     * Create and send event invitation notification with email
     */
    public static function createEventInvitation(EventInvitation $invitation): void
    {
        $notification = self::create([
            'user_id' => $invitation->invited_user_id,
            'type' => self::TYPE_EVENT_INVITATION,
            'title' => 'Nuovo Invito Evento',
            'message' => "Sei stato invitato a partecipare come {$invitation->role} all'evento \"{$invitation->event->title}\"",
            'data' => [
                'event_id' => $invitation->event_id,
                'invitation_id' => $invitation->id,
                'role' => $invitation->role,
                'compensation' => $invitation->compensation,
            ],
            'action_url' => route('invitations.index'),
            'action_text' => 'Gestisci Invito',
            'priority' => self::PRIORITY_HIGH,
        ]);

        // Send email notification
        self::sendEmailNotification($notification, $invitation);

        // Broadcast real-time notification
        self::broadcastNotification($notification);
    }

    /**
     * Create event reminder notification
     */
    public static function createEventReminder(Event $event, User $user, int $hoursUntil = 24): void
    {
        $timeText = $hoursUntil === 24 ? 'domani' : "tra {$hoursUntil} ore";

        $notification = self::create([
            'user_id' => $user->id,
            'type' => self::TYPE_EVENT_REMINDER,
            'title' => 'Promemoria Evento',
            'message' => "L'evento \"{$event->title}\" inizia {$timeText}",
            'data' => [
                'event_id' => $event->id,
                'hours_until' => $hoursUntil,
            ],
            'action_url' => route('events.show', $event),
            'action_text' => 'Vedi Evento',
            'priority' => $hoursUntil <= 2 ? self::PRIORITY_HIGH : self::PRIORITY_NORMAL,
        ]);

        // Send email for 24h and 2h reminders
        if (in_array($hoursUntil, [24, 2])) {
            \Mail::to($user)->send(new \App\Mail\EventUpdateMail(
                $event,
                $user,
                'reminder'
            ));
        }

        // Always broadcast
        self::broadcastNotification($notification);
    }

    /**
     * Create event update notification
     */
    public static function createEventUpdate(Event $event, User $user, array $changes = [], string $customMessage = null): void
    {
        $changesSummary = self::formatChangesSummary($changes);

        $notification = self::create([
            'user_id' => $user->id,
            'type' => self::TYPE_EVENT_UPDATE,
            'title' => 'Evento Aggiornato',
            'message' => "L'evento \"{$event->title}\" è stato aggiornato" . ($changesSummary ? ": {$changesSummary}" : ''),
            'data' => [
                'event_id' => $event->id,
                'changes' => $changes,
                'custom_message' => $customMessage,
            ],
            'action_url' => route('events.show', $event),
            'action_text' => 'Vedi Modifiche',
            'priority' => self::isImportantChange($changes) ? self::PRIORITY_HIGH : self::PRIORITY_NORMAL,
        ]);

        // Send email for important changes
        if (self::isImportantChange($changes) || $customMessage) {
            $updateType = self::getUpdateType($changes);
            \Mail::to($user)->send(new \App\Mail\EventUpdateMail(
                $event,
                $user,
                $updateType,
                $changes,
                $customMessage
            ));
        }

        // Always broadcast
        self::broadcastNotification($notification);
    }

    /**
     * Create event cancelled notification
     */
    public static function createEventCancelled(Event $event, User $user, string $reason = null): void
    {
        $notification = self::create([
            'user_id' => $user->id,
            'type' => self::TYPE_EVENT_CANCELLED,
            'title' => 'Evento Cancellato',
            'message' => "L'evento \"{$event->title}\" è stato cancellato" . ($reason ? ": {$reason}" : ''),
            'data' => [
                'event_id' => $event->id,
                'reason' => $reason,
            ],
            'priority' => self::PRIORITY_HIGH,
        ]);

        // Always send email for cancellations
        \Mail::to($user)->send(new \App\Mail\EventUpdateMail(
            $event,
            $user,
            'cancelled',
            [],
            $reason
        ));

        // Always broadcast
        self::broadcastNotification($notification);
    }

    /**
     * Send email notification based on type
     */
    protected static function sendEmailNotification(self $notification, $relatedModel = null): void
    {
        try {
            switch ($notification->type) {
                case self::TYPE_EVENT_INVITATION:
                    if ($relatedModel instanceof EventInvitation) {
                        \Mail::to($notification->user)->send(
                            new \App\Mail\EventInvitationMail($relatedModel)
                        );
                    }
                    break;

                // Add other email types as needed
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send email notification', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Broadcast real-time notification
     */
    protected static function broadcastNotification(self $notification): void
    {
        try {
            broadcast(new \App\Events\NotificationSent($notification));
        } catch (\Exception $e) {
            \Log::error('Failed to broadcast notification', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Format changes summary for display
     */
    protected static function formatChangesSummary(array $changes): string
    {
        if (empty($changes)) return '';

        $summaries = [];
        foreach ($changes as $field => $change) {
            switch ($field) {
                case 'start_datetime':
                    $summaries[] = 'orario modificato';
                    break;
                case 'venue_name':
                case 'venue_address':
                    $summaries[] = 'luogo cambiato';
                    break;
                case 'description':
                    $summaries[] = 'descrizione aggiornata';
                    break;
                default:
                    $summaries[] = $field;
            }
        }

        return implode(', ', array_slice($summaries, 0, 2));
    }

    /**
     * Check if changes are important enough for email
     */
    protected static function isImportantChange(array $changes): bool
    {
        $importantFields = ['start_datetime', 'end_datetime', 'venue_name', 'venue_address', 'status'];
        return !empty(array_intersect(array_keys($changes), $importantFields));
    }

    /**
     * Get update type based on changes
     */
    protected static function getUpdateType(array $changes): string
    {
        if (isset($changes['start_datetime']) || isset($changes['end_datetime'])) {
            return 'datetime';
        }
        if (isset($changes['venue_name']) || isset($changes['venue_address'])) {
            return 'location';
        }
        if (isset($changes['status']) && $changes['status']['new'] === 'cancelled') {
            return 'cancelled';
        }
        return 'general';
    }

    /**
     * Bulk mark as read for user
     */
    public static function markAllAsReadForUser(User $user): int
    {
        return self::where('user_id', $user->id)
                   ->where('is_read', false)
                   ->update([
                       'is_read' => true,
                       'read_at' => Carbon::now(),
                   ]);
    }

    /**
     * Get user's unread count
     */
    public static function getUnreadCountForUser(User $user): int
    {
        return self::where('user_id', $user->id)
                   ->where('is_read', false)
                   ->count();
    }

    /**
     * Clean old notifications (older than 90 days)
     */
    public static function cleanOldNotifications(): int
    {
        return self::where('created_at', '<', Carbon::now()->subDays(90))
                   ->where('is_read', true)
                   ->delete();
    }

    /**
     * Create invitation response notification
     */
    public static function createInvitationResponse(EventInvitation $invitation, string $response): void
    {
        $responseText = $response === 'accepted' ? 'accettato' : 'rifiutato';
        $type = $response === 'accepted' ? self::TYPE_INVITATION_ACCEPTED : self::TYPE_INVITATION_DECLINED;
        $title = $response === 'accepted' ? 'Invito Accettato' : 'Invito Rifiutato';

        $notification = self::create([
            'user_id' => $invitation->event->user_id, // Notify the event organizer
            'type' => $type,
            'title' => $title,
            'message' => "{$invitation->invitedUser->name} ha {$responseText} l'invito per partecipare come {$invitation->role} all'evento \"{$invitation->event->title}\"",
            'data' => [
                'event_id' => $invitation->event_id,
                'invitation_id' => $invitation->id,
                'invited_user_id' => $invitation->invited_user_id,
                'response' => $response,
                'role' => $invitation->role,
            ],
            'action_url' => route('events.show', $invitation->event),
            'action_text' => 'Vedi Evento',
            'priority' => self::PRIORITY_NORMAL,
        ]);

        // Broadcast real-time notification
        self::broadcastNotification($notification);
    }

    /**
     * Get available notification types
     */
    public static function getAvailableTypes(): array
    {
        return [
            self::TYPE_EVENT_INVITATION => 'Invito Evento',
            self::TYPE_INVITATION_ACCEPTED => 'Invito Accettato',
            self::TYPE_INVITATION_DECLINED => 'Invito Rifiutato',
            self::TYPE_NEW_REQUEST => 'Nuova Richiesta',
            self::TYPE_REQUEST_ACCEPTED => 'Richiesta Accettata',
            self::TYPE_REQUEST_DECLINED => 'Richiesta Rifiutata',
            self::TYPE_REQUEST_CANCELLED => 'Richiesta Cancellata',
            self::TYPE_EVENT_UPDATE => 'Aggiornamento Evento',
            self::TYPE_EVENT_CANCELLED => 'Evento Cancellato',
            self::TYPE_EVENT_REMINDER => 'Promemoria Evento',
        ];
    }
}
