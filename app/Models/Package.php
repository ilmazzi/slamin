<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'currency',
        'video_limit',
        'duration_days',
        'stripe_price_id',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Relazione con gli abbonamenti utente
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    /**
     * Scope per pacchetti attivi
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope per ordinamento
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    /**
     * Verifica se il pacchetto è gratuito
     */
    public function isFree(): bool
    {
        return $this->price == 0;
    }

    /**
     * Formatta il prezzo per la visualizzazione
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2) . ' ' . $this->currency;
    }

    /**
     * Ottiene la durata formattata
     */
    public function getFormattedDurationAttribute(): string
    {
        if ($this->duration_days == 30) {
            return '1 mese';
        } elseif ($this->duration_days == 365) {
            return '1 anno';
        } else {
            return $this->duration_days . ' giorni';
        }
    }
}
