<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GigPosition;

class GigPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            [
                'name' => 'Artista/Poeta',
                'key' => 'artist_poet',
                'description' => 'Posizione per artisti e poeti che si esibiscono durante l\'evento',
                'sort_order' => 1,
            ],
            [
                'name' => 'MC/Ospite',
                'key' => 'mc_guest',
                'description' => 'Posizione per presentatori, MC e ospiti dell\'evento',
                'sort_order' => 2,
            ],
            [
                'name' => 'Supporto Tecnico',
                'key' => 'technical_support',
                'description' => 'Posizione per supporto tecnico, audio, luci e assistenza tecnica',
                'sort_order' => 3,
            ],
            [
                'name' => 'Volontaria/Volontario',
                'key' => 'volunteer',
                'description' => 'Posizione per volontari che supportano l\'organizzazione dell\'evento',
                'sort_order' => 4,
            ],
        ];

        foreach ($positions as $position) {
            GigPosition::updateOrCreate(
                ['key' => $position['key']],
                $position
            );
        }
    }
}
