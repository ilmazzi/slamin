<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentVenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'venue_name',
        'venue_address',
        'city',
        'postcode',
        'country',
        'latitude',
        'longitude',
        'usage_count',
        'last_used_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Salva o aggiorna un luogo recente
     */
    public static function saveRecentVenue($data)
    {
        $userId = auth()->id();
        if (!$userId) return null;

        // Cerca se esiste già un luogo simile
        $existing = self::where('user_id', $userId)
            ->where('venue_name', $data['venue_name'])
            ->where('venue_address', $data['venue_address'])
            ->where('city', $data['city'])
            ->first();

        if ($existing) {
            // Aggiorna il contatore e la data di ultimo utilizzo
            $existing->update([
                'usage_count' => $existing->usage_count + 1,
                'last_used_at' => now(),
                'latitude' => $data['latitude'] ?? $existing->latitude,
                'longitude' => $data['longitude'] ?? $existing->longitude,
            ]);
            return $existing;
        } else {
            // Crea un nuovo record
            return self::create([
                'user_id' => $userId,
                'venue_name' => $data['venue_name'],
                'venue_address' => $data['venue_address'],
                'city' => $data['city'],
                'postcode' => $data['postcode'] ?? '',
                'country' => $data['country'] ?? 'Italia',
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'usage_count' => 1,
                'last_used_at' => now(),
            ]);
        }
    }

    /**
     * Ottiene i luoghi recenti dell'utente
     */
    public static function getRecentVenues($limit = 4)
    {
        $userId = auth()->id();
        if (!$userId) return collect();

        return self::where('user_id', $userId)
            ->orderBy('last_used_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Ottiene i luoghi più popolari di tutti gli utenti
     */
    public static function getPopularVenues($limit = 8)
    {
        return self::selectRaw('
                venue_name,
                venue_address,
                city,
                postcode,
                country,
                latitude,
                longitude,
                SUM(usage_count) as total_usage,
                MAX(last_used_at) as last_used_at,
                COUNT(DISTINCT user_id) as unique_users
            ')
            ->whereNotNull('venue_name')
            ->where('venue_name', '!=', '')
            ->groupBy('venue_name', 'venue_address', 'city', 'postcode', 'country', 'latitude', 'longitude')
            ->having('total_usage', '>=', 2) // Solo luoghi usati almeno 2 volte
            ->orderBy('total_usage', 'desc')
            ->orderBy('unique_users', 'desc')
            ->orderBy('last_used_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Ottiene i luoghi recenti di tutti gli utenti (ultimi 30 giorni)
     */
    public static function getRecentGlobalVenues($limit = 8)
    {
        return self::selectRaw('
                venue_name,
                venue_address,
                city,
                postcode,
                country,
                latitude,
                longitude,
                SUM(usage_count) as total_usage,
                MAX(last_used_at) as last_used_at,
                COUNT(DISTINCT user_id) as unique_users
            ')
            ->whereNotNull('venue_name')
            ->where('venue_name', '!=', '')
            ->where('last_used_at', '>=', now()->subDays(30))
            ->groupBy('venue_name', 'venue_address', 'city', 'postcode', 'country', 'latitude', 'longitude')
            ->orderBy('last_used_at', 'desc')
            ->orderBy('total_usage', 'desc')
            ->limit($limit)
            ->get();
    }
}
