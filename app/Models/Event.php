<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

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
        'organizer_id',
        'venue_owner_id',
        'allow_requests',
        'tags',
        'category',
        'image_url',
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
}
