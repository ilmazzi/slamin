<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasModeration;
use App\Traits\Reportable;
use App\Traits\HasLikes;
use App\Traits\HasViews;
use App\Traits\HasComments;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory, HasModeration, Reportable, HasLikes, HasViews, HasComments;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'requirements',
        'start_datetime',
        'end_datetime',
        'registration_deadline',
        'invitation_deadline',
        'venue_name',
        'venue_address',
        'city',
        'postcode',
        'country',
        'latitude',
        'longitude',
        'is_public',
        'max_participants',
        'entry_fee',
        'status',
        'moderation_status',
        'moderation_notes',
        'moderated_by',
        'moderated_at',
        'organizer_id',
        'venue_owner_id',
        'group_id',
        'group_permissions',
        'allow_requests',
        'tags',
        'category',
        'image_url',
        'gig_positions',
        'is_recurring',
        'recurrence_type',
        'recurrence_interval',
        'recurrence_count',
        'recurrence_weekdays',
        'recurrence_monthday',
        'parent_event_id',
        'is_online',
        'timezone',
        'online_url',
        'is_availability_based',
        'availability_deadline',
        'availability_instructions',
        'festival_id',
        'festival_events',
        'like_count',
        'comment_count',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'registration_deadline' => 'datetime',
        'invitation_deadline' => 'datetime',
        'is_public' => 'boolean',
        'allow_requests' => 'boolean',
        'tags' => 'array',
        'gig_positions' => 'array',
        'entry_fee' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'moderated_at' => 'datetime',
        'is_recurring' => 'boolean',
        'recurrence_weekdays' => 'array',
        'is_online' => 'boolean',
        'is_availability_based' => 'boolean',
        'availability_deadline' => 'datetime',
        'festival_events' => 'array',
        'like_count' => 'integer',
        'comment_count' => 'integer',
    ];

    /**
     * Event status constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    /**
     * Event category constants
     */
    const CATEGORY_CONCERT = 'concert';
    const CATEGORY_CONFERENCE = 'conference';
    const CATEGORY_CONTEST = 'contest';
    const CATEGORY_FESTIVAL = 'festival';
    const CATEGORY_WORKSHOP = 'workshop';
    const CATEGORY_OPEN_MIC = 'open_mic';
    const CATEGORY_POETRY_ART = 'poetry_art';
    const CATEGORY_POETRY_SLAM = 'poetry_slam';
    const CATEGORY_BOOK_PRESENTATION = 'book_presentation';
    const CATEGORY_READING = 'reading';
    const CATEGORY_RESIDENCY = 'residency';
    const CATEGORY_SPOKEN_WORD = 'spoken_word';

    /**
     * Get the organizer of the event
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Get the venue owner (optional)
     */
    public function venueOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'venue_owner_id');
    }

    /**
     * Get the image URL for the event
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->attributes['image_url'] ?? null) {
            return $this->attributes['image_url'];
        }
        return null;
    }

    /**
     * Get the image path for the event (for backward compatibility)
     */
    public function getImagePathAttribute(): ?string
    {
        return $this->attributes['image_url'] ?? null;
    }

    /**
     * Get all invitations for this event
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(EventInvitation::class);
    }

    /**
     * Get all participation requests for this event
     */
    public function requests(): HasMany
    {
        return $this->hasMany(EventRequest::class);
    }

    /**
     * Get pending invitations
     */
    public function pendingInvitations(): HasMany
    {
        return $this->invitations()->where('status', 'pending');
    }

    /**
     * Get pending requests
     */
    public function pendingRequests(): HasMany
    {
        return $this->requests()->where('status', 'pending');
    }

    /**
     * Get accepted invitations
     */
    public function acceptedInvitations(): HasMany
    {
        return $this->invitations()->where('status', 'accepted');
    }

    /**
     * Get declined invitations
     */
    public function declinedInvitations(): HasMany
    {
        return $this->invitations()->where('status', 'declined');
    }

    /**
     * Get accepted requests
     */
    public function acceptedRequests(): HasMany
    {
        return $this->requests()->where('status', 'accepted');
    }

    /**
     * Get declined requests
     */
    public function declinedRequests(): HasMany
    {
        return $this->requests()->where('status', 'declined');
    }

    /**
     * Get all availability options for this event
     */
    public function availabilityOptions(): HasMany
    {
        return $this->hasMany(EventAvailabilityOption::class);
    }

    /**
     * Get all availability responses for this event
     */
    public function availabilityResponses(): HasMany
    {
        return $this->hasMany(EventAvailabilityResponse::class);
    }

    /**
     * Get active availability options
     */
    public function activeAvailabilityOptions(): HasMany
    {
        return $this->availabilityOptions()->active()->ordered();
    }

    /**
     * Scope: Published events
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Scope: Public events
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope: Upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where(function ($q) {
            $q->where('start_datetime', '>', Carbon::now())
              ->orWhere('is_availability_based', true);
        });
    }

    /**
     * Scope: Events by location (radius in km)
     */
    public function scopeNearLocation($query, $latitude, $longitude, $radius = 50)
    {
        return $query->whereRaw(
            "( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) < ?",
            [$latitude, $longitude, $latitude, $radius]
        );
    }

    /**
     * Check if event accepts requests
     */
    public function acceptsRequests(): bool
    {
        return $this->allow_requests &&
               $this->is_public &&
               $this->status === self::STATUS_PUBLISHED &&
               $this->start_datetime > Carbon::now();
    }

    /**
     * Check if event is full
     */
    public function isFull(): bool
    {
        if (!$this->max_participants) {
            return false;
        }

        $acceptedCount = $this->invitations()->where('status', 'accepted')->count() +
                        $this->requests()->where('status', 'accepted')->count();

        return $acceptedCount >= $this->max_participants;
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddressAttribute(): string
    {
        return $this->venue_name . ', ' . $this->venue_address . ', ' . $this->city;
    }

    /**
     * Get duration in hours
     */
    public function getDurationAttribute(): float
    {
        return $this->start_datetime->diffInHours($this->end_datetime);
    }

    /**
     * Check if registration is still open
     */
    public function isRegistrationOpen(): bool
    {
        if ($this->registration_deadline) {
            return Carbon::now() <= $this->registration_deadline;
        }

        return $this->start_datetime > Carbon::now();
    }

    /**
     * Get all available categories
     */
    public static function getCategories(): array
    {
        return [
            self::CATEGORY_CONCERT => 'Concerto (musica)',
            self::CATEGORY_CONFERENCE => 'Conferenza/Tavola rotonda',
            self::CATEGORY_CONTEST => 'Concorso',
            self::CATEGORY_FESTIVAL => 'Festival',
            self::CATEGORY_WORKSHOP => 'Laboratorio/Corso',
            self::CATEGORY_OPEN_MIC => 'Open mic',
            self::CATEGORY_POETRY_ART => 'Poesia + altra arte',
            self::CATEGORY_POETRY_SLAM => 'Poetry Slam',
            self::CATEGORY_BOOK_PRESENTATION => 'Presentazione libro',
            self::CATEGORY_READING => 'Reading',
            self::CATEGORY_RESIDENCY => 'Residenza',
            self::CATEGORY_SPOKEN_WORD => 'Spoken Word',
        ];
    }

    /**
     * Get category display name
     */
    public function getCategoryDisplayName(): string
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? 'Categoria non definita';
    }

    /**
     * Get category color class
     */
    public function getCategoryColorClassAttribute(): string
    {
        return match($this->category) {
            self::CATEGORY_CONCERT => 'bg-primary',           // Blu
            self::CATEGORY_CONFERENCE => 'bg-info',           // Azzurro
            self::CATEGORY_CONTEST => 'bg-warning',           // Giallo/Oro
            self::CATEGORY_FESTIVAL => 'bg-success',          // Verde
            self::CATEGORY_WORKSHOP => 'bg-warning',          // Giallo
            self::CATEGORY_OPEN_MIC => 'bg-secondary',        // Grigio
            self::CATEGORY_POETRY_ART => 'bg-primary-600',    // Blu scuro
            self::CATEGORY_POETRY_SLAM => 'bg-danger',        // Rosso
            self::CATEGORY_BOOK_PRESENTATION => 'bg-dark',    // Grigio scuro
            self::CATEGORY_READING => 'bg-outline-primary',   // Blu con bordo
            self::CATEGORY_RESIDENCY => 'bg-success-600',     // Verde scuro
            self::CATEGORY_SPOKEN_WORD => 'bg-warning-600',   // Giallo scuro
            default => 'bg-secondary',
        };
    }

    /**
     * Get category light color class
     */
    public function getCategoryLightColorClassAttribute(): string
    {
        return match($this->category) {
            self::CATEGORY_CONCERT => 'bg-light-primary',
            self::CATEGORY_CONFERENCE => 'bg-light-info',
            self::CATEGORY_CONTEST => 'bg-light-warning',
            self::CATEGORY_FESTIVAL => 'bg-light-success',
            self::CATEGORY_WORKSHOP => 'bg-light-warning',
            self::CATEGORY_OPEN_MIC => 'bg-light-secondary',
            self::CATEGORY_POETRY_ART => 'bg-light-primary',
            self::CATEGORY_POETRY_SLAM => 'bg-light-danger',
            self::CATEGORY_BOOK_PRESENTATION => 'bg-light-dark',
            self::CATEGORY_READING => 'bg-light-primary',
            self::CATEGORY_RESIDENCY => 'bg-light-success',
            self::CATEGORY_SPOKEN_WORD => 'bg-light-warning',
            default => 'bg-light-secondary',
        };
    }

    // ========================================
    // RECURRENCE METHODS
    // ========================================

    /**
     * Get the parent event (for recurring events)
     */
    public function parentEvent(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'parent_event_id');
    }

    /**
     * Get all child events (for recurring events)
     */
    public function childEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'parent_event_id');
    }

    /**
     * Relazione con la wishlist
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Utenti che hanno questo evento nella wishlist
     */
    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists', 'event_id', 'user_id');
    }

    /**
     * Get all events in the same recurrence series
     */
    public function recurrenceSeries(): HasMany
    {
        if ($this->parent_event_id) {
            // This is a child event, get all siblings
            return $this->parentEvent->childEvents();
        } else {
            // This is a parent event, get all children
            return $this->childEvents();
        }
    }

    /**
     * Check if this event is part of a recurrence series
     */
    public function isPartOfRecurrence(): bool
    {
        return $this->is_recurring || $this->parent_event_id !== null;
    }

    /**
     * Get the root event of the recurrence series
     */
    public function getRootEvent(): Event
    {
        if ($this->parent_event_id) {
            return $this->parentEvent->getRootEvent();
        }
        return $this;
    }

    /**
     * Get recurrence type options
     */
    public static function getRecurrenceTypes(): array
    {
        return [
            'daily' => 'Giornaliera',
            'weekly' => 'Settimanale',
            'monthly' => 'Mensile',
            'yearly' => 'Annuale',
        ];
    }

    /**
     * Get weekday options for weekly recurrence
     */
    public static function getWeekdayOptions(): array
    {
        return [
            1 => 'Lunedì',
            2 => 'Martedì',
            3 => 'Mercoledì',
            4 => 'Giovedì',
            5 => 'Venerdì',
            6 => 'Sabato',
            7 => 'Domenica',
        ];
    }

    /**
     * Generate recurrence dates based on settings
     */
    public function generateRecurrenceDates(): array
    {
        if (!$this->is_recurring || !$this->recurrence_type) {
            return [];
        }

        $dates = [];
        $currentDate = $this->start_datetime->copy();
        $count = 0;
        $maxCount = (int)($this->recurrence_count ?? 10); // Convert to int, default to 10 if not specified
        $interval = (int)($this->recurrence_interval ?? 1); // Convert to int, default to 1

        while ($count < $maxCount) {
            $dates[] = $currentDate->copy();
            $count++;

            switch ($this->recurrence_type) {
                case 'daily':
                    $currentDate->addDays($interval);
                    break;

                case 'weekly':
                    if ($this->recurrence_weekdays) {
                        // Find next occurrence based on selected weekdays
                        $nextDate = $this->findNextWeekdayOccurrence($currentDate);
                        if (!$nextDate) break 2;
                        $currentDate = $nextDate;
                    } else {
                        $currentDate->addWeeks($interval);
                    }
                    break;

                case 'monthly':
                    if ($this->recurrence_monthday) {
                        // Same day of month
                        $currentDate->addMonths($interval);
                        $currentDate->day((int)$this->recurrence_monthday);
                    } else {
                        // Same day of week
                        $currentDate->addMonths($interval);
                    }
                    break;

                case 'yearly':
                    $currentDate->addYears($interval);
                    break;
            }
        }

        return $dates;
    }

    /**
     * Find next weekday occurrence for weekly recurrence
     */
    private function findNextWeekdayOccurrence(Carbon $currentDate): ?Carbon
    {
        if (!$this->recurrence_weekdays) {
            return null;
        }

        $weekdays = $this->recurrence_weekdays;
        $nextDate = $currentDate->copy()->addDay();

        // Look for next occurrence within next 8 weeks
        for ($week = 0; $week < 8; $week++) {
            for ($day = 0; $day < 7; $day++) {
                if (in_array($nextDate->dayOfWeek, $weekdays)) {
                    return $nextDate;
                }
                $nextDate->addDay();
            }
        }

        return null;
    }

    /**
     * Create recurring events based on current event settings
     */
    public function createRecurringEvents(): array
    {
        if (!$this->is_recurring) {
            return [];
        }

        $dates = $this->generateRecurrenceDates();
        $createdEvents = [];

        foreach ($dates as $index => $date) {
            if ($index === 0) continue; // Skip first date (current event)

            $duration = $this->start_datetime->diffInSeconds($this->end_datetime);
            $newStartDate = $date;
            $newEndDate = $date->copy()->addSeconds($duration);

            $newEvent = $this->replicate();
            $newEvent->start_datetime = $newStartDate;
            $newEvent->end_datetime = $newEndDate;
            $newEvent->parent_event_id = $this->id;
            $newEvent->is_recurring = false; // Child events are not recurring themselves
            $newEvent->save();

            $createdEvents[] = $newEvent;
        }

        return $createdEvents;
    }

    /**
     * Check if event is online
     */
    public function isOnlineEvent(): bool
    {
        return $this->is_online;
    }

    /**
     * Get formatted timezone display name
     */
    public function getTimezoneDisplayName(): string
    {
        if (!$this->timezone) {
            return 'UTC';
        }

        $timezone = new \DateTimeZone($this->timezone);
        $offset = $timezone->getOffset(new \DateTime()) / 3600;
        $sign = $offset >= 0 ? '+' : '';

        return $this->timezone . ' (UTC' . $sign . $offset . ')';
    }

    /**
     * Get start time in user's timezone
     */
    public function getStartTimeInTimezone(string $userTimezone = null): string
    {
        if (!$this->start_datetime) {
            return '';
        }

        $userTimezone = $userTimezone ?: config('app.timezone');

        return $this->start_datetime
            ->setTimezone($userTimezone)
            ->format('d/m/Y H:i');
    }

    /**
     * Get end time in user's timezone
     */
    public function getEndTimeInTimezone(string $userTimezone = null): string
    {
        if (!$this->end_datetime) {
            return '';
        }

        $userTimezone = $userTimezone ?: config('app.timezone');

        return $this->end_datetime
            ->setTimezone($userTimezone)
            ->format('d/m/Y H:i');
    }

    /**
     * Get original timezone info for display
     */
    public function getOriginalTimezoneInfo(): string
    {
        if (!$this->is_online || !$this->timezone) {
            return '';
        }

        $originalStart = $this->start_datetime->format('H:i');
        $originalEnd = $this->end_datetime->format('H:i');

        return "($originalStart-$originalEnd {$this->timezone})";
    }

    /**
     * Festival relationship - event belongs to a festival
     */
    public function festival(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'festival_id');
    }

    /**
     * Festival events relationship - festival has many events
     */
    public function festivalEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'festival_id');
    }

    /**
     * Get events that are part of this festival (from festival_events JSON)
     */
    public function getFestivalEventIds(): array
    {
        return $this->festival_events ?? [];
    }

    /**
     * Get actual Event models for festival events
     */
    public function getFestivalEventModels()
    {
        $eventIds = $this->getFestivalEventIds();
        if (empty($eventIds)) {
            return collect();
        }

        return Event::whereIn('id', $eventIds)
                   ->where('is_public', true)
                   ->orderBy('start_datetime')
                   ->get();
    }

    /**
     * Check if this event is part of a festival
     */
    public function isPartOfFestival(): bool
    {
        return !is_null($this->festival_id);
    }

    /**
     * Check if this event is a festival
     */
    public function isFestival(): bool
    {
        return $this->category === self::CATEGORY_FESTIVAL;
    }

    // ========================================
    // RELAZIONI CON I GRUPPI
    // ========================================

    /**
     * Relazione con i gruppi dell'evento (many-to-many)
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'event_group')
                    ->withPivot('group_permissions')
                    ->withTimestamps();
    }

    /**
     * Relazione con il gruppo principale dell'evento (per compatibilità)
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Verifica se l'evento appartiene a un gruppo
     */
    public function belongsToGroup(): bool
    {
        return $this->groups()->exists();
    }

    /**
     * Verifica se l'evento appartiene a un gruppo specifico
     */
    public function belongsToGroupId($groupId): bool
    {
        return $this->groups()->where('group_id', $groupId)->exists();
    }

    /**
     * Verifica se un utente può modificare l'evento in base ai permessi del gruppo
     */
    public function canBeModifiedBy(User $user): bool
    {
        // Se l'evento non appartiene a un gruppo, solo l'organizzatore può modificarlo
        if (!$this->belongsToGroup()) {
            return $user->id === $this->organizer_id;
        }

        // Se l'utente è admin del sito, può sempre modificare
        if ($user->hasRole('admin')) {
            return true;
        }

        // Se l'utente è l'organizzatore dell'evento
        if ($user->id === $this->organizer_id) {
            return true;
        }

        // Verifica i permessi del gruppo
        switch ($this->group_permissions) {
            case 'creator_only':
                // Solo il creatore può modificare
                return $user->id === $this->organizer_id;

            case 'group_admins':
                // Gli admin del gruppo possono modificare
                return $user->isAdminOf($this->group);

            case 'group_members':
                // Tutti i membri del gruppo possono modificare
                return $user->isMemberOf($this->group);

            default:
                return false;
        }
    }

    /**
     * Verifica se un utente può visualizzare l'evento
     */
    public function canBeViewedBy(User $user): bool
    {
        // Se l'evento è pubblico, tutti possono vederlo
        if ($this->is_public) {
            return true;
        }

        // Se l'utente è admin del sito, può sempre vedere
        if ($user->hasRole('admin')) {
            return true;
        }

        // Se l'utente è l'organizzatore, può vedere
        if ($user->id === $this->organizer_id) {
            return true;
        }

        // Se l'evento appartiene a un gruppo, i membri del gruppo possono vedere
        if ($this->belongsToGroup()) {
            return $user->isMemberOf($this->group);
        }

        return false;
    }

    /**
     * Relazione con i gigs dell'evento
     */
    public function gigs()
    {
        return $this->hasMany(Gig::class);
    }

    /**
     * Ottiene i gigs aperti dell'evento
     */
    public function openGigs()
    {
        return $this->gigs()->where('is_closed', false);
    }

    /**
     * Ottiene i gigs chiusi dell'evento
     */
    public function closedGigs()
    {
        return $this->gigs()->where('is_closed', true);
    }

    /**
     * Check if event is availability-based
     */
    public function isAvailabilityBased(): bool
    {
        return $this->is_availability_based;
    }

    /**
     * Get availability options count
     */
    public function getAvailabilityOptionsCount(): int
    {
        return $this->availabilityOptions()->active()->count();
    }

    /**
     * Check if user has responded to availability
     */
    public function hasUserRespondedToAvailability(User $user): bool
    {
        return $this->availabilityResponses()->where('user_id', $user->id)->exists();
    }

    /**
     * Get user's availability responses
     */
    public function getUserAvailabilityResponses(User $user)
    {
        return $this->availabilityResponses()->where('user_id', $user->id)->with('availabilityOption');
    }

    /**
     * Get availability summary for organizer
     */
    public function getAvailabilitySummary(): array
    {
        $options = $this->activeAvailabilityOptions()->with('responses')->get();

        $summary = [];
        foreach ($options as $option) {
            $summary[] = [
                'option' => $option,
                'preferred_count' => $option->preferred_count,
                'available_count' => $option->available_count,
                'unavailable_count' => $option->unavailable_count,
                'total_responses' => $option->total_responses,
            ];
        }

        return $summary;
    }

    /**
     * Check if availability deadline has passed
     */
    public function isAvailabilityDeadlinePassed(): bool
    {
        if (!$this->availability_deadline) {
            return false;
        }

        return now()->isAfter($this->availability_deadline);
    }

    // ========================================
    // RELAZIONI GAMIFICATION & SCORING
    // ========================================

    /**
     * Participants in this event (registered users + guests)
     */
    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    /**
     * Rounds configured for this event
     */
    public function rounds()
    {
        return $this->hasMany(EventRound::class)->ordered();
    }

    /**
     * All scores for this event
     */
    public function scores()
    {
        return $this->hasMany(EventScore::class);
    }

    /**
     * Rankings for this event
     */
    public function rankings()
    {
        return $this->hasMany(EventRanking::class)->ordered();
    }

    /**
     * Check if event has scoring enabled
     */
    public function hasScoringEnabled(): bool
    {
        return $this->category === self::CATEGORY_POETRY_SLAM && $this->rounds()->exists();
    }

    /**
     * Check if event has completed rankings
     */
    public function hasRankings(): bool
    {
        return $this->rankings()->exists();
    }
}
