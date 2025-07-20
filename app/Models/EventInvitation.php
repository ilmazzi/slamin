<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class EventInvitation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'event_id',
        'invited_user_id',
        'inviter_id',
        'message',
        'role',
        'compensation',
        'status',
        'response_message',
        'responded_at',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'compensation' => 'decimal:2',
        'responded_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';
    const STATUS_EXPIRED = 'expired';

    /**
     * Role constants
     */
    const ROLE_PERFORMER = 'performer';
    const ROLE_JUDGE = 'judge';
    const ROLE_TECHNICIAN = 'technician';
    const ROLE_HOST = 'host';

    /**
     * Get the event this invitation belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the invited user
     */
    public function invitedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_user_id');
    }

    /**
     * Get the user who sent the invitation
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    /**
     * Scope: Pending invitations
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: Accepted invitations
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    /**
     * Scope: Non-expired invitations
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($query) {
            $query->whereNull('expires_at')
                  ->orWhere('expires_at', '>', Carbon::now());
        });
    }

    /**
     * Accept the invitation
     */
    public function accept(string $responseMessage = null): bool
    {
        if (!$this->canBeAccepted()) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_ACCEPTED,
            'response_message' => $responseMessage,
            'responded_at' => Carbon::now(),
        ]);

        // Create notification for organizer
        Notification::create([
            'user_id' => $this->event->organizer_id,
            'type' => 'invitation_accepted',
            'title' => 'Invito Accettato',
            'message' => $this->invitedUser->name . ' ha accettato l\'invito per ' . $this->event->title,
            'data' => json_encode([
                'event_id' => $this->event_id,
                'invitation_id' => $this->id,
                'user_id' => $this->invited_user_id,
            ]),
            'action_url' => '/events/' . $this->event_id,
        ]);

        return true;
    }

    /**
     * Decline the invitation
     */
    public function decline(string $responseMessage = null): bool
    {
        if (!$this->canBeDeclined()) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_DECLINED,
            'response_message' => $responseMessage,
            'responded_at' => Carbon::now(),
        ]);

        // Create notification for organizer
        Notification::create([
            'user_id' => $this->event->organizer_id,
            'type' => 'invitation_declined',
            'title' => 'Invito Rifiutato',
            'message' => $this->invitedUser->name . ' ha rifiutato l\'invito per ' . $this->event->title,
            'data' => json_encode([
                'event_id' => $this->event_id,
                'invitation_id' => $this->id,
                'user_id' => $this->invited_user_id,
            ]),
            'action_url' => '/events/' . $this->event_id,
        ]);

        return true;
    }

    /**
     * Check if invitation can be accepted
     */
    public function canBeAccepted(): bool
    {
        return $this->status === self::STATUS_PENDING &&
               !$this->isExpired() &&
               !$this->event->isFull();
    }

    /**
     * Check if invitation can be declined
     */
    public function canBeDeclined(): bool
    {
        return $this->status === self::STATUS_PENDING && !$this->isExpired();
    }

    /**
     * Check if invitation is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && Carbon::now() > $this->expires_at;
    }

    /**
     * Mark as expired
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => self::STATUS_EXPIRED]);
    }

    /**
     * Get available roles
     */
    public static function getAvailableRoles(): array
    {
        return [
            self::ROLE_PERFORMER => 'Performer',
            self::ROLE_JUDGE => 'Judge',
            self::ROLE_TECHNICIAN => 'Technician',
            self::ROLE_HOST => 'Host',
        ];
    }
}
