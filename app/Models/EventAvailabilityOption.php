<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class EventAvailabilityOption extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'event_id',
        'datetime',
        'description',
        'sort_order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'datetime' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the event this option belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get all responses for this availability option
     */
    public function responses(): HasMany
    {
        return $this->hasMany(EventAvailabilityResponse::class, 'availability_option_id');
    }

    /**
     * Get formatted datetime string
     */
    public function getFormattedDatetimeAttribute(): string
    {
        return $this->datetime->format('d/m/Y H:i');
    }

    /**
     * Get formatted date string
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->datetime->format('d/m/Y');
    }

    /**
     * Get formatted time string
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->datetime->format('H:i');
    }

    /**
     * Scope: Active options
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('datetime');
    }

    /**
     * Get response count by status
     */
    public function getResponseCountByStatus(string $status): int
    {
        return $this->responses()->where('status', $status)->count();
    }

    /**
     * Get preferred responses count
     */
    public function getPreferredCountAttribute(): int
    {
        return $this->getResponseCountByStatus('preferred');
    }

    /**
     * Get available responses count
     */
    public function getAvailableCountAttribute(): int
    {
        return $this->getResponseCountByStatus('available');
    }

    /**
     * Get unavailable responses count
     */
    public function getUnavailableCountAttribute(): int
    {
        return $this->getResponseCountByStatus('unavailable');
    }

    /**
     * Get total responses count
     */
    public function getTotalResponsesAttribute(): int
    {
        return $this->responses()->count();
    }
}
