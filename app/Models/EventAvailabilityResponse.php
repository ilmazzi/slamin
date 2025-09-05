<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class EventAvailabilityResponse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'event_id',
        'user_id',
        'availability_option_id',
        'status',
        'notes',
    ];

    /**
     * Status constants
     */
    const STATUS_PREFERRED = 'preferred';
    const STATUS_AVAILABLE = 'available';
    const STATUS_UNAVAILABLE = 'unavailable';

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PREFERRED => 'Preferita',
            self::STATUS_AVAILABLE => 'Disponibile',
            self::STATUS_UNAVAILABLE => 'Non disponibile',
        ];
    }

    /**
     * Get status color for UI
     */
    public static function getStatusColor(string $status): string
    {
        return match($status) {
            self::STATUS_PREFERRED => 'success', // Verde
            self::STATUS_AVAILABLE => 'warning', // Giallo
            self::STATUS_UNAVAILABLE => 'danger', // Rosso
            default => 'secondary',
        };
    }

    /**
     * Get the event this response belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who made this response
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the availability option this response is for
     */
    public function availabilityOption(): BelongsTo
    {
        return $this->belongsTo(EventAvailabilityOption::class, 'availability_option_id');
    }

    /**
     * Get status label in Italian
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return self::getStatusColor($this->status);
    }

    /**
     * Scope: By status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Preferred responses
     */
    public function scopePreferred($query)
    {
        return $query->where('status', self::STATUS_PREFERRED);
    }

    /**
     * Scope: Available responses
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope: Unavailable responses
     */
    public function scopeUnavailable($query)
    {
        return $query->where('status', self::STATUS_UNAVAILABLE);
    }
}
