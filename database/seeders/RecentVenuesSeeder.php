<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RecentVenue;

class RecentVenuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea luoghi recenti per l'utente 2 (che sappiamo esistere)
        RecentVenue::create([
            'user_id' => 2,
            'venue_name' => 'Teatro Comunale',
            'venue_address' => 'Via Roma 123',
            'city' => 'Milano',
            'postcode' => '20100',
            'country' => 'IT',
            'latitude' => 45.4642,
            'longitude' => 9.1900,
            'usage_count' => 3,
            'last_used_at' => now(),
        ]);

        RecentVenue::create([
            'user_id' => 2,
            'venue_name' => 'Auditorium Parco della Musica',
            'venue_address' => 'Viale Pietro de Coubertin 30',
            'city' => 'Roma',
            'postcode' => '00196',
            'country' => 'IT',
            'latitude' => 41.9289,
            'longitude' => 12.4707,
            'usage_count' => 2,
            'last_used_at' => now()->subDays(2),
        ]);

        RecentVenue::create([
            'user_id' => 2,
            'venue_name' => 'Teatro La Fenice',
            'venue_address' => 'Campo San Fantin 1965',
            'city' => 'Venezia',
            'postcode' => '30124',
            'country' => 'IT',
            'latitude' => 45.4336,
            'longitude' => 12.3337,
            'usage_count' => 1,
            'last_used_at' => now()->subDays(5),
        ]);

        RecentVenue::create([
            'user_id' => 2,
            'venue_name' => 'Teatro San Carlo',
            'venue_address' => 'Via San Carlo 98',
            'city' => 'Napoli',
            'postcode' => '80132',
            'country' => 'IT',
            'latitude' => 40.8371,
            'longitude' => 14.2496,
            'usage_count' => 1,
            'last_used_at' => now()->subDays(10),
        ]);

        // Crea luoghi recenti per l'utente 3
        RecentVenue::create([
            'user_id' => 3,
            'venue_name' => 'Sala Slam Poetry',
            'venue_address' => 'Via della Poesia 15',
            'city' => 'Milano',
            'postcode' => '20121',
            'country' => 'IT',
            'latitude' => 45.4642,
            'longitude' => 9.1900,
            'usage_count' => 5,
            'last_used_at' => now(),
        ]);

        RecentVenue::create([
            'user_id' => 3,
            'venue_name' => 'Circolo Culturale',
            'venue_address' => 'Piazza delle Arti 8',
            'city' => 'Roma',
            'postcode' => '00186',
            'country' => 'IT',
            'latitude' => 41.9028,
            'longitude' => 12.4964,
            'usage_count' => 3,
            'last_used_at' => now()->subDays(1),
        ]);

        RecentVenue::create([
            'user_id' => 1,
            'venue_name' => 'Teatro Underground',
            'venue_address' => 'Via Sotterranea 42',
            'city' => 'Firenze',
            'postcode' => '50100',
            'country' => 'IT',
            'latitude' => 43.7696,
            'longitude' => 11.2558,
            'usage_count' => 2,
            'last_used_at' => now()->subDays(3),
        ]);

        RecentVenue::create([
            'user_id' => 1,
            'venue_name' => 'Caffè Letterario',
            'venue_address' => 'Via dei Poeti 27',
            'city' => 'Bologna',
            'postcode' => '40100',
            'country' => 'IT',
            'latitude' => 44.4949,
            'longitude' => 11.3426,
            'usage_count' => 4,
            'last_used_at' => now()->subDays(7),
        ]);
    }
}
