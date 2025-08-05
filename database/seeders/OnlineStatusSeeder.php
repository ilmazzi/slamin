<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class OnlineStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Imposta tutti gli utenti esistenti come offline con preferenze di privacy di default
        User::whereNull('online_status')->update([
            'is_online' => false,
            'online_status' => 'offline',
            'last_seen_at' => now()->subDays(rand(1, 30)), // Ultima attività casuale negli ultimi 30 giorni
            'online_preferences' => json_encode([
                'visibility' => 'all' // Tutti possono vedere lo stato di default
            ])
        ]);

        // Imposta alcuni utenti come online per test
        $onlineUsers = User::inRandomOrder()->limit(3)->get();
        foreach ($onlineUsers as $user) {
            $user->update([
                'is_online' => true,
                'online_status' => 'online',
                'last_seen_at' => now()->subMinutes(rand(1, 10)), // Online negli ultimi 10 minuti
            ]);
        }

        // Imposta alcuni utenti come "away"
        $awayUsers = User::inRandomOrder()->limit(2)->get();
        foreach ($awayUsers as $user) {
            if (!$onlineUsers->contains($user)) {
                $user->update([
                    'is_online' => true,
                    'online_status' => 'away',
                    'last_seen_at' => now()->subMinutes(rand(15, 30)), // Away da 15-30 minuti
                ]);
            }
        }

        // Imposta alcuni utenti con preferenze di privacy diverse
        $privacyUsers = User::inRandomOrder()->limit(2)->get();
        foreach ($privacyUsers as $user) {
            if (!$onlineUsers->contains($user) && !$awayUsers->contains($user)) {
                $user->update([
                    'online_preferences' => json_encode([
                        'visibility' => 'friends' // Solo amici possono vedere lo stato
                    ])
                ]);
            }
        }

        $this->command->info('Stato online degli utenti inizializzato con successo!');
        $this->command->info('Utenti online: ' . User::where('is_online', true)->count());
        $this->command->info('Utenti totali: ' . User::count());
    }
}
