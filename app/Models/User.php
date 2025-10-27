<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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
use App\Models\UserLanguage;
use App\Services\OnlineStatusService;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail
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
        'precise_address',
        'public_location',
        'city',
        'region',
        'country',
        'location_privacy',
        'status',
        'phone',
        'website',
        'profile_photo',
        'banner_image',
        'social_facebook',
        'social_instagram',
        'social_youtube',
        'social_twitter',
        // Online status fields
        'is_online',
        'last_seen_at',
        'online_status',
        'online_preferences',
        // Payment accounts fields
        'stripe_connect_account_id',
        'stripe_connect_status',
        'stripe_connect_details',
        'stripe_connected_at',
        'paypal_email',
        'paypal_merchant_id',
        'paypal_verified',
        'paypal_connected_at',
        'preferred_payout_method',
        'payout_method_configured',
        'payout_settings',
        'bank_name',
        'bank_iban',
        'bank_swift',
        'bank_account_holder',
        // PeerTube fields
        'peertube_user_id',
        'peertube_username',
        'peertube_display_name',
        'peertube_token',
        'peertube_refresh_token',
        'peertube_token_expires_at',
        'peertube_account_id',
        'peertube_channel_id',
        'peertube_email',
        'peertube_role',
        'peertube_video_quota',
        'peertube_video_quota_daily',
        'peertube_created_at',
        'peertube_password',
        'peertube_roles',
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
            'peertube_token_expires_at' => 'datetime',
            'peertube_created_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'online_preferences' => 'array',
            'peertube_roles' => 'array',
            // Payment accounts casts
            'stripe_connect_details' => 'array',
            'payout_settings' => 'array',
            'stripe_connected_at' => 'datetime',
            'paypal_connected_at' => 'datetime',
            'paypal_verified' => 'boolean',
            'payout_method_configured' => 'boolean',
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

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get privacy-safe display identifier (no email)
     */
    public function getPrivacySafeIdentifier(): string
    {
        return $this->nickname ?: 'ID: ' . $this->id;
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\EmailVerificationNotification);
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
     * Event invitations received by this user (alias for receivedInvitations)
     */
    public function eventInvitations()
    {
        return $this->receivedInvitations();
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
        return $this->hasPermissionTo('events.create.public') || $this->hasPermissionTo('events.create.private');
    }

    /**
     * Check if user can invite others
     */
    public function canInviteUsers(): bool
    {
        return $this->hasPermissionTo('events.invite');
    }

    /**
     * Check if user can participate in events
     */
    public function canParticipateInEvents(): bool
    {
        return $this->hasPermissionTo('events.view.public') || $this->hasPermissionTo('events.view.private');
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

    public function getMorphClass(): string
    {
        return 'user';
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
     * Photos uploaded by this user
     */
    public function photos()
    {
        return $this->hasMany(Photo::class);
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

    // Relazioni per le poesie
    public function poems()
    {
        return $this->hasMany(Poem::class);
    }

    public function poemComments()
    {
        return $this->hasMany(PoemComment::class);
    }

    public function likedPoems()
    {
        return $this->belongsToMany(Poem::class, 'poem_likes')->withTimestamps();
    }

    public function bookmarkedPoems()
    {
        return $this->belongsToMany(Poem::class, 'poem_bookmarks')->withTimestamps();
    }

    public function likedPoemComments()
    {
        return $this->belongsToMany(PoemComment::class, 'poem_comment_likes')->withTimestamps();
    }

    public function gigs()
    {
        return $this->hasMany(Gig::class);
    }

    public function gigApplications()
    {
        return $this->hasMany(GigApplication::class);
    }

    /**
     * Relazione con la wishlist
     */
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Eventi nella wishlist
     */
    public function wishlistedEvents()
    {
        return $this->belongsToMany(Event::class, 'wishlists', 'user_id', 'event_id');
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
        return asset('assets/images/avatar/default-avatar.webp');
    }

    /**
     * Get banner image URL
     */
    public function getBannerImageUrlAttribute()
    {
        if ($this->banner_image) {
            return asset('storage/' . $this->banner_image);
        }
        return asset('assets/images/avatar/default-banner.webp');
    }

    /**
     * Verifica se l'utente ha un account PeerTube
     */
    public function hasPeerTubeAccount(): bool
    {
        return !empty($this->peertube_user_id);
    }

    /**
     * Verifica se l'utente può avere un account PeerTube (ruoli poet/organizer)
     */
    public function canHavePeerTubeAccount(): bool
    {
        return $this->hasAnyRole(['poet', 'organizer']);
    }

    // ========================================
    // RELAZIONI CON I GRUPPI
    // ========================================

    /**
     * Gruppi creati dall'utente
     */
    public function createdGroups()
    {
        return $this->hasMany(Group::class, 'created_by');
    }

    /**
     * Gruppi di cui l'utente è membro
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_members')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Membership nei gruppi
     */
    public function groupMemberships()
    {
        return $this->hasMany(GroupMember::class);
    }

    /**
     * Gruppi di cui l'utente è admin
     */
    public function adminGroups()
    {
        return $this->belongsToMany(Group::class, 'group_members')
                    ->wherePivot('role', 'admin')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }

    /**
     * Gruppi di cui l'utente è moderatore (inclusi admin)
     */
    public function moderatorGroups()
    {
        return $this->belongsToMany(Group::class, 'group_members')
                    ->whereIn('group_members.role', ['admin', 'moderator'])
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Inviti ricevuti per i gruppi
     */
    public function groupInvitations()
    {
        return $this->hasMany(GroupInvitation::class);
    }

    /**
     * Inviti inviati per i gruppi
     */
    public function sentGroupInvitations()
    {
        return $this->hasMany(GroupInvitation::class, 'invited_by');
    }

    /**
     * Richieste di partecipazione ai gruppi
     */
    public function groupJoinRequests()
    {
        return $this->hasMany(GroupJoinRequest::class);
    }

    /**
     * Richieste di partecipazione processate dall'utente
     */
    public function processedGroupJoinRequests()
    {
        return $this->hasMany(GroupJoinRequest::class, 'processed_by');
    }

    // ========================================
    // METODI HELPER PER I GRUPPI
    // ========================================

    /**
     * Verifica se l'utente può creare gruppi
     */
    public function canCreateGroups(): bool
    {
        return $this->hasAnyRole(['poet', 'organizer', 'admin']);
    }

    public function canViewGroups(): bool
    {
        return $this->hasAnyRole(['poet', 'organizer', 'admin', 'audience']);
    }

    /**
     * Verifica se l'utente è membro di un gruppo specifico
     */
    public function isMemberOf(Group $group): bool
    {
        return $this->groups()->where('groups.id', $group->id)->exists();
    }

    /**
     * Verifica se l'utente è admin di un gruppo specifico
     */
    public function isAdminOf(Group $group): bool
    {
        return $this->adminGroups()->where('groups.id', $group->id)->exists();
    }

    /**
     * Verifica se l'utente è moderatore di un gruppo specifico
     */
    public function isModeratorOf(Group $group): bool
    {
        return $this->moderatorGroups()->where('groups.id', $group->id)->exists();
    }

    /**
     * Ottieni il ruolo dell'utente in un gruppo specifico
     */
    public function getRoleInGroup(Group $group): ?string
    {
        $membership = $this->groupMemberships()->where('group_id', $group->id)->first();
        return $membership ? $membership->role : null;
    }

    /**
     * Conta i gruppi di cui l'utente è membro
     */
    public function getGroupsCountAttribute(): int
    {
        return $this->groups()->count();
    }

    /**
     * Conta i gruppi di cui l'utente è admin
     */
    public function getAdminGroupsCountAttribute(): int
    {
        return $this->adminGroups()->count();
    }




    /**
     * Relazioni per il sistema chat
     */
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'participants')
                    ->withPivot('role', 'last_read_at')
                    ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    /**
     * Relazione con i like unificati
     */
    public function likes()
    {
        return $this->hasMany(\App\Models\UnifiedLike::class);
    }

    /**
     * Relazione con le visualizzazioni unificate
     */
    public function views()
    {
        return $this->hasMany(\App\Models\UnifiedView::class);
    }

    /**
     * Relazione con i commenti unificati
     */
    public function comments()
    {
        return $this->hasMany(\App\Models\UnifiedComment::class);
    }

    /**
     * Get all activities performed by this user
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Contenuti likati dall'utente (relazione polimorfa)
     */
    public function likedContent()
    {
        return $this->belongsToMany(\App\Models\Video::class, 'unified_likes', 'user_id', 'likeable_id')
            ->where('likeable_type', \App\Models\Video::class)
            ->withTimestamps()
            ->union(
                $this->belongsToMany(\App\Models\Photo::class, 'unified_likes', 'user_id', 'likeable_id')
                    ->where('likeable_type', \App\Models\Photo::class)
                    ->withTimestamps()
            )
            ->union(
                $this->belongsToMany(\App\Models\Poem::class, 'unified_likes', 'user_id', 'likeable_id')
                    ->where('likeable_type', \App\Models\Poem::class)
                    ->withTimestamps()
            )
            ->union(
                $this->belongsToMany(\App\Models\Carousel::class, 'unified_likes', 'user_id', 'likeable_id')
                    ->where('likeable_type', \App\Models\Carousel::class)
                    ->withTimestamps()
            )
            ->union(
                $this->belongsToMany(\App\Models\Event::class, 'unified_likes', 'user_id', 'likeable_id')
                    ->where('likeable_type', \App\Models\Event::class)
                    ->withTimestamps()
            );
    }

    /**
     * Contenuti visualizzati dall'utente (relazione polimorfa)
     */
    public function viewedContent()
    {
        return $this->belongsToMany(\App\Models\Video::class, 'unified_views', 'user_id', 'viewable_id')
            ->where('viewable_type', \App\Models\Video::class)
            ->withTimestamps()
            ->union(
                $this->belongsToMany(\App\Models\Photo::class, 'unified_views', 'user_id', 'viewable_id')
                    ->where('viewable_type', \App\Models\Photo::class)
                    ->withTimestamps()
            )
            ->union(
                $this->belongsToMany(\App\Models\Poem::class, 'unified_views', 'user_id', 'viewable_id')
                    ->where('viewable_type', \App\Models\Poem::class)
                    ->withTimestamps()
            )
            ->union(
                $this->belongsToMany(\App\Models\Carousel::class, 'unified_views', 'user_id', 'viewable_id')
                    ->where('viewable_type', \App\Models\Carousel::class)
                    ->withTimestamps()
            )
            ->union(
                $this->belongsToMany(\App\Models\Event::class, 'unified_views', 'user_id', 'viewable_id')
                    ->where('viewable_type', \App\Models\Event::class)
                    ->withTimestamps()
            );
    }

    /**
     * Relazioni per il sistema follow
     */

    /**
     * Utenti che questo utente segue (following)
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    /**
     * Utenti che seguono questo utente (followers)
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    /**
     * Controlla se questo utente segue un altro utente
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Controlla se questo utente è seguito da un altro utente
     */
    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    /**
     * Segue un utente
     */
    public function follow(User $user): bool
    {
        if ($this->id === $user->id) {
            return false; // Non può seguire se stesso
        }

        if (!$this->isFollowing($user)) {
            $this->following()->attach($user->id);
            return true;
        }

        return false;
    }

    /**
     * Smette di seguire un utente
     */
    public function unfollow(User $user): bool
    {
        if ($this->id === $user->id) {
            return false; // Non può unfolloware se stesso
        }

        if ($this->isFollowing($user)) {
            $this->following()->detach($user->id);
            return true;
        }

        return false;
    }

    /**
     * Toggle follow/unfollow
     */
    public function toggleFollow(User $user): bool
    {
        if ($this->isFollowing($user)) {
            return $this->unfollow($user);
        } else {
            return $this->follow($user);
        }
    }

    /**
     * Accessor per il conteggio dei followers
     */
    public function getFollowersCountAttribute(): int
    {
        return $this->followers()->count();
    }

    /**
     * Accessor per il conteggio dei following
     */
    public function getFollowingCountAttribute(): int
    {
        return $this->following()->count();
    }

/**
     * Accessor: stato presenza ('online'|'recent'|'idle'|'offline')
     */
    public function getPresenceStateAttribute(): string
    {
        return app(OnlineStatusService::class)->getPresenceState($this);
    }

    /**
     * Accessor: boolean "online" (deriva da presence_state)
     */
    public function getIsOnlineAttribute(): bool
    {
        return $this->presence_state === 'online';
    }

    /**
     * Accessor: classe CSS per la presence (config/online.php -> ui.classes)
     */
    public function getPresenceClassAttribute(): string
    {
        return app(OnlineStatusService::class)->classFor($this->presence_state);
    }

    /**
     * Accessor: icona FontAwesome (o quello che usi)
     */
    public function getPresenceIconAttribute(): string
    {
        return app(OnlineStatusService::class)->iconFor($this->presence_state);
    }

    /**
     * Accessor: label localizzata
     */
    public function getPresenceLabelAttribute(): string
    {
        return app(OnlineStatusService::class)->labelFor($this->presence_state);
    }

    /**
     * Accessor: "Ultimo accesso" in forma umana (fallback quando non online).
     */
    public function getLastSeenHumanAttribute(): string
    {
        return $this->last_seen_at ? $this->last_seen_at->diffForHumans() : 'Mai';
    }

  

    /**
     * Get user's articles
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Get user's published articles
     */
    public function publishedArticles()
    {
        return $this->hasMany(Article::class)->published();
    }

    // Translation payment relationships removed (system cleaned up)

    // ========================================
    // RELAZIONI CON LE LINGUE
    // ========================================

    /**
     * Lingue conosciute dall'utente
     */
    public function languages()
    {
        return $this->hasMany(UserLanguage::class);
    }

    /**
     * Lingue madri dell'utente
     */
    public function nativeLanguages()
    {
        return $this->hasMany(UserLanguage::class)->native();
    }

    /**
     * Lingue parlate dall'utente
     */
    public function spokenLanguages()
    {
        return $this->hasMany(UserLanguage::class)->spoken();
    }

    /**
     * Lingue scritte dall'utente
     */
    public function writtenLanguages()
    {
        return $this->hasMany(UserLanguage::class)->written();
    }

    /**
     * Ottieni tutte le lingue dell'utente raggruppate per codice lingua
     */
    public function getLanguagesGroupedAttribute()
    {
        return $this->languages()->get()->groupBy('language_code');
    }

    /**
     * Get the public location based on privacy settings (renamed to avoid conflict)
     *
     * @return string|null
     */
    public function getPrivacyBasedLocationAttribute()
    {
        switch ($this->location_privacy) {
            case 'public':
                return $this->precise_address;
            case 'region':
                return $this->region ?: $this->city;
            case 'country':
                return $this->country;
            case 'private':
            default:
                return null;
        }
    }

    /**
     * Get the display location for public viewing
     *
     * @return string|null
     */
    public function getDisplayLocationAttribute()
    {
        // Usa la logica di privacy basata su location_privacy
        switch ($this->location_privacy) {
            case 'public':
                // Se precise_address è solo una via, costruisci l'indirizzo completo
                if ($this->precise_address && !str_contains($this->precise_address, ',')) {
                    // Costruisci indirizzo completo dai campi separati
                    $addressParts = array_filter([
                        $this->precise_address,
                        $this->city,
                        $this->region,
                        $this->country
                    ]);
                    return implode(', ', $addressParts);
                }
                // Altrimenti usa precise_address così com'è
                return $this->precise_address ?: $this->location;
            case 'region':
                return $this->region ?: $this->city;
            case 'country':
                return $this->country;
            case 'custom':
                // Per l'opzione custom, usa sempre public_location se disponibile
                return $this->public_location ?: $this->location;
            case 'private':
            default:
                // Se non c'è nulla da mostrare, fallback al campo location originale
                return $this->location;
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    // ========================================
    // RELAZIONI GAMIFICATION
    // ========================================

    /**
     * Badges earned by this user
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot(['earned_at', 'metadata', 'progress', 'awarded_by', 'admin_notes'])
            ->withTimestamps()
            ->orderByDesc('user_badges.earned_at');
    }

    /**
     * User badge pivot records
     */
    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }

    /**
     * User points record
     */
    public function userPoints()
    {
        return $this->hasOne(UserPoints::class);
    }

    /**
     * Point transactions history
     */
    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    /**
     * Event participations
     */
    public function eventParticipations()
    {
        return $this->hasMany(EventParticipant::class);
    }

    /**
     * Forum posts created by this user
     */
    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class);
    }

    /**
     * Event scores given (as judge)
     */
    public function eventScoresGiven()
    {
        return $this->hasMany(EventScore::class, 'judge_id');
    }

    /**
     * Event rankings
     */
    public function eventRankings()
    {
        return $this->hasManyThrough(
            EventRanking::class,
            EventParticipant::class,
            'user_id',
            'participant_id'
        );
    }

    /**
     * Get top 3 badges for display
     */
    public function getTop3BadgesAttribute()
    {
        return $this->badges()
            ->orderBy('badges.order')
            ->orderByDesc('user_badges.earned_at')
            ->limit(3)
            ->get();
    }

    /**
     * Get user level
     */
    public function getLevelAttribute(): int
    {
        return $this->userPoints ? $this->userPoints->level : 1;
    }

    /**
     * Get total points
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->userPoints ? $this->userPoints->total_points : 0;
    }

    /**
     * Get badges count
     */
    public function getBadgesCountAttribute(): int
    {
        return $this->badges()->count();
    }
    // ========================================
}



