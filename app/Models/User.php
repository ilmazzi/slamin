<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\UserSubscription;
use App\Models\VideoComment;
use App\Models\VideoSnap;
use App\Models\VideoLike;
use App\Models\Video;
use App\Models\SystemSetting;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'email',
        'password',
        'bio',
        'location',
        'status',
        'phone',
        'website',
        'profile_photo',
        'social_facebook',
        'social_instagram',
        'social_youtube',
        'social_twitter',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Poetry Slam specific helper methods
     */

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is a moderator (admin or moderator)
     */
    public function isModerator(): bool
    {
        return $this->hasAnyRole(['admin', 'moderator']);
    }

    /**
     * Check if user can organize events
     */
    public function canOrganizeEvents(): bool
    {
        return $this->hasAnyRole(['admin', 'moderator', 'organizer']);
    }

    /**
     * Check if user is a poet (can perform)
     */
    public function isPoet(): bool
    {
        return $this->hasRole('poet');
    }

    /**
     * Get display name (nickname if available, otherwise name)
     */
    public function getDisplayName(): string
    {
        return $this->nickname ?: $this->name;
    }

    /**
     * Check if user can judge competitions
     */
    public function canJudge(): bool
    {
        return $this->hasRole('judge');
    }

    /**
     * Check if user owns venues
     */
    public function ownsVenues(): bool
    {
        return $this->hasRole('venue_owner');
    }

    /**
     * Get user's preferred language
     */
    public function getPreferredLanguage(): string
    {
        // TODO: Add preferred_language field to users table
        return session('locale', 'it');
    }

    /**
     * Event relationships
     */

    /**
     * Events organized by this user
     */
    public function organizedEvents()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    /**
     * Alias for organizedEvents (for compatibility)
     */
    public function events()
    {
        return $this->organizedEvents();
    }

    /**
     * Events where this user owns the venue
     */
    public function venueEvents()
    {
        return $this->hasMany(Event::class, 'venue_owner_id');
    }

    /**
     * Invitations received by this user
     */
    public function receivedInvitations()
    {
        return $this->hasMany(EventInvitation::class, 'invited_user_id');
    }

    /**
     * Invitations sent by this user
     */
    public function sentInvitations()
    {
        return $this->hasMany(EventInvitation::class, 'inviter_id');
    }

    /**
     * Event requests made by this user
     */
    public function eventRequests()
    {
        return $this->hasMany(EventRequest::class, 'user_id');
    }

    /**
     * Event requests reviewed by this user
     */
    public function reviewedRequests()
    {
        return $this->hasMany(EventRequest::class, 'reviewed_by');
    }

    /**
     * Notifications for this user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    /**
     * Get events where user is participating (accepted invitations or requests)
     */
    public function participatingEvents()
    {
        $invitedEvents = Event::whereHas('invitations', function ($query) {
            $query->where('invited_user_id', $this->id)
                  ->where('status', EventInvitation::STATUS_ACCEPTED);
        });

        $requestedEvents = Event::whereHas('requests', function ($query) {
            $query->where('user_id', $this->id)
                  ->where('status', EventRequest::STATUS_ACCEPTED);
        });

        return $invitedEvents->union($requestedEvents);
    }

    /**
     * Get upcoming events for user
     */
    public function upcomingEvents()
    {
        $organizedEvents = $this->organizedEvents()
                               ->upcoming()
                               ->published();

        $participatingEvents = $this->participatingEvents()
                                   ->upcoming()
                                   ->published();

        return $organizedEvents->union($participatingEvents)
                               ->orderBy('start_datetime');
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    /**
     * Get recent notifications
     */
    public function getRecentNotificationsAttribute()
    {
        return $this->notifications()
                   ->orderBy('created_at', 'desc')
                   ->limit(5)
                   ->get();
    }

    /**
     * Check if user can create events
     */
    public function canCreateEvents(): bool
    {
        return $this->hasPermissionTo('create events');
    }

    /**
     * Check if user can invite others
     */
    public function canInviteUsers(): bool
    {
        return $this->hasPermissionTo('send invitations');
    }

    /**
     * Check if user can participate in events
     */
    public function canParticipateInEvents(): bool
    {
        return $this->hasPermissionTo('view events');
    }

    /**
     * Get user's role display name
     */
    public function getRoleDisplayNameAttribute(): string
    {
        $role = $this->roles->first();
        return $role ? $role->name : 'audience';
    }

    /**
     * Get user's primary role for events
     */
    public function getPrimaryEventRoleAttribute(): string
    {
        if ($this->hasRole('organizer')) return 'organizer';
        if ($this->hasRole('poet')) return 'poet';
        if ($this->hasRole('judge')) return 'judge';
        if ($this->hasRole('venue_owner')) return 'venue_owner';
        if ($this->hasRole('technician')) return 'technician';
        return 'audience';
    }

    /**
     * Get user's display roles for UI
     */
    public function getDisplayRoles(): array
    {
        return $this->roles->pluck('name')->toArray();
    }

    /**
     * Check if user is active (for future status management)
     */
    public function isActive(): bool
    {
        return $this->status !== 'suspended' && $this->status !== 'banned';
    }

    /**
     * Get user's videos
     */
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Get user's active subscription
     */
    public function activeSubscription()
    {
        return $this->hasOne(UserSubscription::class)->active();
    }

    /**
     * Get all user's subscriptions
     */
    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    /**
     * Get user's video comments
     */
    public function videoComments()
    {
        return $this->hasMany(VideoComment::class);
    }

    /**
     * Get user's video snaps
     */
    public function videoSnaps()
    {
        return $this->hasMany(VideoSnap::class);
    }

    /**
     * Get user's video likes
     */
    public function videoLikes()
    {
        return $this->hasMany(VideoLike::class);
    }

    /**
     * Get current video limit for user
     */
    public function getCurrentVideoLimitAttribute(): int
    {
        $subscription = $this->activeSubscription;

        if ($subscription) {
            return $subscription->effective_video_limit;
        }

        // Limite gratuito dalle impostazioni di sistema
        return SystemSetting::get('default_video_limit', 3);
    }

    /**
     * Get current video count for user
     */
    public function getCurrentVideoCountAttribute(): int
    {
        return $this->videos()->count();
    }

    /**
     * Check if user can upload more videos
     */
    public function canUploadMoreVideos(): bool
    {
        return $this->current_video_count < $this->current_video_limit;
    }

    /**
     * Get remaining video uploads
     */
    public function getRemainingVideoUploadsAttribute(): int
    {
        return max(0, $this->current_video_limit - $this->current_video_count);
    }

    /**
     * Check if user has premium subscription
     */
    public function hasPremiumSubscription(): bool
    {
        return $this->activeSubscription !== null;
    }

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        return asset('assets/images/avatar/' . ($this->id % 16 + 1) . '.png');
    }
}
