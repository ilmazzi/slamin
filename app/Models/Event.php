<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasModeration;
use App\Traits\Reportable;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory, HasModeration, Reportable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'requirements',
        'start_datetime',
        'end_datetime',
        'registration_deadline',
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
        'allow_requests',
        'tags',
        'category',
        'image_url',
        'is_recurring',
        'recurrence_type',
        'recurrence_interval',
        'recurrence_count',
        'recurrence_weekdays',
        'recurrence_monthday',
        'parent_event_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'registration_deadline' => 'datetime',
        'is_public' => 'boolean',
        'allow_requests' => 'boolean',
        'tags' => 'array',
        'entry_fee' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'moderated_at' => 'datetime',
        'is_recurring' => 'boolean',
        'recurrence_weekdays' => 'array',
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
        return $query->where('start_datetime', '>', Carbon::now());
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
     * Get category color class
     */
    public function getCategoryColorClassAttribute(): string
    {
        return match($this->category) {
            self::CATEGORY_CONCERT => 'bg-primary',
            self::CATEGORY_CONFERENCE => 'bg-info',
            self::CATEGORY_FESTIVAL => 'bg-success',
            self::CATEGORY_WORKSHOP => 'bg-warning',
            self::CATEGORY_OPEN_MIC => 'bg-secondary',
            self::CATEGORY_POETRY_ART => 'bg-purple',
            self::CATEGORY_POETRY_SLAM => 'bg-danger',
            self::CATEGORY_BOOK_PRESENTATION => 'bg-teal',
            self::CATEGORY_READING => 'bg-indigo',
            self::CATEGORY_RESIDENCY => 'bg-pink',
            self::CATEGORY_SPOKEN_WORD => 'bg-orange',
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
            self::CATEGORY_FESTIVAL => 'bg-light-success',
            self::CATEGORY_WORKSHOP => 'bg-light-warning',
            self::CATEGORY_OPEN_MIC => 'bg-light-secondary',
            self::CATEGORY_POETRY_ART => 'bg-light-purple',
            self::CATEGORY_POETRY_SLAM => 'bg-light-danger',
            self::CATEGORY_BOOK_PRESENTATION => 'bg-light-teal',
            self::CATEGORY_READING => 'bg-light-indigo',
            self::CATEGORY_RESIDENCY => 'bg-light-pink',
            self::CATEGORY_SPOKEN_WORD => 'bg-light-orange',
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
            'once' => 'Una volta sola',
            'count' => 'X volte',
            'daily' => 'Ogni giorno',
            'weekly' => 'Ogni settimana',
            'monthly' => 'Ogni mese',
            'yearly' => 'Ogni anno',
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
        $maxCount = $this->recurrence_count ?? 10; // Default to 10 if not specified

        while ($count < $maxCount) {
            $dates[] = $currentDate->copy();
            $count++;

            switch ($this->recurrence_type) {
                case 'once':
                    return $dates; // Only one occurrence

                case 'count':
                    if ($count >= $maxCount) break 2;
                    $currentDate->addDays($this->recurrence_interval);
                    break;

                case 'daily':
                    $currentDate->addDays($this->recurrence_interval);
                    break;

                case 'weekly':
                    if ($this->recurrence_weekdays) {
                        // Find next occurrence based on selected weekdays
                        $nextDate = $this->findNextWeekdayOccurrence($currentDate);
                        if (!$nextDate) break 2;
                        $currentDate = $nextDate;
                    } else {
                        $currentDate->addWeeks($this->recurrence_interval);
                    }
                    break;

                case 'monthly':
                    if ($this->recurrence_monthday) {
                        // Same day of month
                        $currentDate->addMonths($this->recurrence_interval);
                        $currentDate->day($this->recurrence_monthday);
                    } else {
                        // Same day of week
                        $currentDate->addMonths($this->recurrence_interval);
                    }
                    break;

                case 'yearly':
                    $currentDate->addYears($this->recurrence_interval);
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
}
