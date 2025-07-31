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
     * Type constants for groups
     */
    const TYPE_GROUP_INVITATION = 'group_invitation';
    const TYPE_GROUP_INVITATION_ACCEPTED = 'group_invitation_accepted';
    const TYPE_GROUP_INVITATION_DECLINED = 'group_invitation_declined';
    const TYPE_GROUP_JOIN_REQUEST = 'group_join_request';
    const TYPE_GROUP_JOIN_REQUEST_ACCEPTED = 'group_join_request_accepted';
    const TYPE_GROUP_JOIN_REQUEST_DECLINED = 'group_join_request_declined';
    const TYPE_GROUP_MEMBER_JOINED = 'group_member_joined';
    const TYPE_GROUP_MEMBER_LEFT = 'group_member_left';
    const TYPE_GROUP_ROLE_CHANGED = 'group_role_changed';

    /**
     * Type constants for social interactions
     */
    const TYPE_CONTENT_LIKED = 'content_liked';
    const TYPE_CONTENT_COMMENTED = 'content_commented';
    const TYPE_COMMENT_LIKED = 'comment_liked';
    const TYPE_VIDEO_SNAPPED = 'video_snapped';

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
            self::TYPE_GROUP_INVITATION => 'ph ph-users',
            self::TYPE_GROUP_INVITATION_ACCEPTED => 'ph ph-user-plus',
            self::TYPE_GROUP_INVITATION_DECLINED => 'ph ph-user-minus',
            self::TYPE_GROUP_JOIN_REQUEST => 'ph ph-hand-waving',
            self::TYPE_GROUP_JOIN_REQUEST_ACCEPTED => 'ph ph-user-plus',
            self::TYPE_GROUP_JOIN_REQUEST_DECLINED => 'ph ph-user-minus',
            self::TYPE_GROUP_MEMBER_JOINED => 'ph ph-user-plus',
            self::TYPE_GROUP_MEMBER_LEFT => 'ph ph-user-minus',
            self::TYPE_GROUP_ROLE_CHANGED => 'ph ph-user-gear',
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
            self::TYPE_GROUP_INVITATION => 'text-primary',
            self::TYPE_GROUP_INVITATION_ACCEPTED, self::TYPE_GROUP_MEMBER_JOINED => 'text-success',
            self::TYPE_GROUP_INVITATION_DECLINED, self::TYPE_GROUP_MEMBER_LEFT => 'text-danger',
            self::TYPE_GROUP_JOIN_REQUEST => 'text-info',
            self::TYPE_GROUP_JOIN_REQUEST_ACCEPTED => 'text-success',
            self::TYPE_GROUP_JOIN_REQUEST_DECLINED => 'text-danger',
            self::TYPE_GROUP_ROLE_CHANGED => 'text-warning',
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
        // Check if event and organizer exist
        if (!$invitation->event || !$invitation->event->organizer_id) {
            \Log::warning('Cannot create invitation response notification: missing event or organizer', [
                'invitation_id' => $invitation->id,
                'event_id' => $invitation->event_id,
                'organizer_id' => $invitation->event?->organizer_id
            ]);
            return;
        }

        // Delete existing invitation notifications for the invited user
        self::where('type', self::TYPE_EVENT_INVITATION)
            ->where('user_id', $invitation->invited_user_id)
            ->whereJsonContains('data->invitation_id', $invitation->id)
            ->delete();

        $responseText = $response === 'accepted' ? 'accettato' : 'rifiutato';
        $type = $response === 'accepted' ? self::TYPE_INVITATION_ACCEPTED : self::TYPE_INVITATION_DECLINED;
        $title = $response === 'accepted' ? 'Invito Accettato' : 'Invito Rifiutato';

        $notification = self::create([
            'user_id' => $invitation->event->organizer_id, // Notify the event organizer
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
     * Create group invitation notification
     */
    public static function createGroupInvitation(GroupInvitation $invitation): void
    {
        $notification = self::create([
            'user_id' => $invitation->user_id,
            'type' => self::TYPE_GROUP_INVITATION,
            'title' => 'Nuovo Invito Gruppo',
            'message' => "Sei stato invitato a unirti al gruppo \"{$invitation->group->name}\"",
            'data' => [
                'group_id' => $invitation->group_id,
                'invitation_id' => $invitation->id,
                'invited_by' => $invitation->invited_by,
                'message' => $invitation->message,
            ],
            'action_url' => route('group-invitations.index'),
            'action_text' => 'Gestisci Invito',
            'priority' => self::PRIORITY_HIGH,
        ]);

        // Broadcast real-time notification
        self::broadcastNotification($notification);
    }

    /**
     * Create group invitation response notification
     */
    public static function createGroupInvitationResponse(GroupInvitation $invitation, string $response): void
    {
        // Delete existing invitation notifications for the invited user
        self::where('type', self::TYPE_GROUP_INVITATION)
            ->where('user_id', $invitation->user_id)
            ->whereJsonContains('data->invitation_id', $invitation->id)
            ->delete();

        $responseText = $response === 'accepted' ? 'accettato' : 'rifiutato';
        $type = $response === 'accepted' ? self::TYPE_GROUP_INVITATION_ACCEPTED : self::TYPE_GROUP_INVITATION_DECLINED;
        $title = $response === 'accepted' ? 'Invito Gruppo Accettato' : 'Invito Gruppo Rifiutato';

        $notification = self::create([
            'user_id' => $invitation->invited_by, // Notify the person who sent the invitation
            'type' => $type,
            'title' => $title,
            'message' => "{$invitation->user->name} ha {$responseText} l'invito per unirsi al gruppo \"{$invitation->group->name}\"",
            'data' => [
                'group_id' => $invitation->group_id,
                'invitation_id' => $invitation->id,
                'user_id' => $invitation->user_id,
                'response' => $response,
            ],
            'action_url' => route('groups.show', $invitation->group),
            'action_text' => 'Vedi Gruppo',
            'priority' => self::PRIORITY_NORMAL,
        ]);

        // Broadcast real-time notification
        self::broadcastNotification($notification);
    }

    /**
     * Create group member joined notification
     */
    public static function createGroupMemberJoined(Group $group, User $newMember, User $notifyUser): void
    {
        $notification = self::create([
            'user_id' => $notifyUser->id,
            'type' => self::TYPE_GROUP_MEMBER_JOINED,
            'title' => 'Nuovo Membro Gruppo',
            'message' => "{$newMember->name} si è unito al gruppo \"{$group->name}\"",
            'data' => [
                'group_id' => $group->id,
                'new_member_id' => $newMember->id,
            ],
            'action_url' => route('groups.show', $group),
            'action_text' => 'Vedi Gruppo',
            'priority' => self::PRIORITY_NORMAL,
        ]);

        // Broadcast real-time notification
        self::broadcastNotification($notification);
    }

    /**
     * Create group member left notification
     */
    public static function createGroupMemberLeft(Group $group, User $leftMember, User $notifyUser): void
    {
        $notification = self::create([
            'user_id' => $notifyUser->id,
            'type' => self::TYPE_GROUP_MEMBER_LEFT,
            'title' => 'Membro Lasciato Gruppo',
            'message' => "{$leftMember->name} ha lasciato il gruppo \"{$group->name}\"",
            'data' => [
                'group_id' => $group->id,
                'left_member_id' => $leftMember->id,
            ],
            'action_url' => route('groups.show', $group),
            'action_text' => 'Vedi Gruppo',
            'priority' => self::PRIORITY_NORMAL,
        ]);

        // Broadcast real-time notification
        self::broadcastNotification($notification);
    }

    /**
     * Create group role changed notification
     */
    public static function createGroupRoleChanged(Group $group, User $member, string $oldRole, string $newRole, User $changedBy): void
    {
        $roleNames = [
            'member' => 'membro',
            'moderator' => 'moderatore',
            'admin' => 'amministratore'
        ];

        $oldRoleName = $roleNames[$oldRole] ?? $oldRole;
        $newRoleName = $roleNames[$newRole] ?? $newRole;

        $title = 'Ruolo Cambiato nel Gruppo';
        $message = "Il tuo ruolo nel gruppo \"{$group->name}\" è stato cambiato da {$oldRoleName} a {$newRoleName} da {$changedBy->name}";

        $notification = self::create([
            'user_id' => $member->id, // Notify the member whose role was changed
            'type' => self::TYPE_GROUP_ROLE_CHANGED,
            'title' => $title,
            'message' => $message,
            'data' => [
                'group_id' => $group->id,
                'old_role' => $oldRole,
                'new_role' => $newRole,
                'changed_by_id' => $changedBy->id,
            ],
            'action_url' => route('groups.show', $group),
            'action_text' => 'Vedi Gruppo',
            'priority' => self::PRIORITY_HIGH,
        ]);

        // Broadcast real-time notification
        self::broadcastNotification($notification);
    }

    /**
     * Create group join request notification
     */
        public static function createGroupJoinRequest(GroupJoinRequest $request): void
    {
        // Notifica tutti gli admin e moderatori del gruppo
        $group = $request->group;
        $adminsAndModerators = $group->members()
                                   ->whereIn('role', ['admin', 'moderator'])
                                   ->get();

        foreach ($adminsAndModerators as $member) {
            $notification = self::create([
                'user_id' => $member->user_id,
                'type' => self::TYPE_GROUP_JOIN_REQUEST,
                'title' => 'Nuova Richiesta di Partecipazione',
                'message' => "{$request->user->name} ha richiesto di unirsi al gruppo \"{$group->name}\"",
                'data' => [
                    'group_id' => $group->id,
                    'request_id' => $request->id,
                    'user_id' => $request->user_id,
                    'message' => $request->message,
                ],
                'action_url' => route('groups.requests.pending', $group),
                'action_text' => 'Gestisci Richiesta',
                'priority' => self::PRIORITY_HIGH,
            ]);

            // Broadcast real-time notification
            self::broadcastNotification($notification);
        }
    }

    /**
     * Create group join request response notification
     */
    public static function createGroupJoinRequestResponse(GroupJoinRequest $request, string $response): void
    {
        $responseText = $response === 'accepted' ? 'accettata' : 'rifiutata';
        $type = $response === 'accepted' ? self::TYPE_GROUP_JOIN_REQUEST_ACCEPTED : self::TYPE_GROUP_JOIN_REQUEST_DECLINED;
        $title = $response === 'accepted' ? 'Richiesta Accettata' : 'Richiesta Rifiutata';

        $notification = self::create([
            'user_id' => $request->user_id, // Notify the user who made the request
            'type' => $type,
            'title' => $title,
            'message' => "La tua richiesta di partecipazione al gruppo \"{$request->group->name}\" è stata {$responseText}",
            'data' => [
                'group_id' => $request->group_id,
                'request_id' => $request->id,
                'response' => $response,
                'processed_by_id' => $request->processed_by,
            ],
            'action_url' => route('groups.show', $request->group),
            'action_text' => 'Vedi Gruppo',
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
            self::TYPE_GROUP_INVITATION => 'Invito Gruppo',
            self::TYPE_GROUP_INVITATION_ACCEPTED => 'Invito Gruppo Accettato',
            self::TYPE_GROUP_INVITATION_DECLINED => 'Invito Gruppo Rifiutato',
            self::TYPE_GROUP_JOIN_REQUEST => 'Richiesta Partecipazione Gruppo',
            self::TYPE_GROUP_JOIN_REQUEST_ACCEPTED => 'Richiesta Partecipazione Accettata',
            self::TYPE_GROUP_JOIN_REQUEST_DECLINED => 'Richiesta Partecipazione Rifiutata',
            self::TYPE_GROUP_MEMBER_JOINED => 'Nuovo Membro Gruppo',
            self::TYPE_GROUP_MEMBER_LEFT => 'Membro Lasciato Gruppo',
            self::TYPE_GROUP_ROLE_CHANGED => 'Ruolo Cambiato',
        ];
    }
}
