<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'stripe_subscription_id',
        'stripe_customer_id',
        'start_date',
        'end_date',
        'status',
        'video_limit_override',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Relazione con l'utente
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione con il pacchetto
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Scope per abbonamenti attivi
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('end_date', '>', now());
    }

    /**
     * Scope per abbonamenti scaduti
     */
    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now());
    }

    /**
     * Verifica se l'abbonamento è attivo
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_date->isFuture();
    }

    /**
     * Verifica se l'abbonamento è scaduto
     */
    public function isExpired(): bool
    {
        return $this->end_date->isPast();
    }

    /**
     * Ottiene il limite video effettivo (considerando override)
     */
    public function getEffectiveVideoLimitAttribute(): int
    {
        return $this->video_limit_override ?? $this->package->video_limit;
    }

    /**
     * Ottiene i giorni rimanenti
     */
    public function getDaysRemainingAttribute(): int
    {
        return max(0, now()->diffInDays($this->end_date, false));
    }

    /**
     * Ottiene la percentuale di completamento
     */
    public function getProgressPercentageAttribute(): float
    {
        $totalDays = $this->start_date->diffInDays($this->end_date);
        $elapsedDays = $this->start_date->diffInDays(now());

        return min(100, max(0, ($elapsedDays / $totalDays) * 100));
    }
}
