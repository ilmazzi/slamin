<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\User;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ottieni un utente per essere il creatore dei gruppi
        $user = User::first();
        
        if (!$user) {
            $this->command->warn('Nessun utente trovato nel database. Impossibile creare gruppi.');
            return;
        }

        $groups = [
            [
                'name' => 'Associazione Poetry Slam Roma',
                'description' => 'Associazione dedicata alla promozione del Poetry Slam nella capitale',
                'visibility' => 'public',
                'created_by' => $user->id,
            ],
            [
                'name' => 'Circolo Culturale Milano',
                'description' => 'Circolo culturale che organizza eventi di Poetry Slam a Milano',
                'visibility' => 'public',
                'created_by' => $user->id,
            ],
            [
                'name' => 'Local Jazz Club Torino',
                'description' => 'Locale che ospita serate di Poetry Slam e musica jazz',
                'visibility' => 'public',
                'created_by' => $user->id,
            ],
            [
                'name' => 'Campionato Nazionale Poetry Slam',
                'description' => 'Organizzazione del campionato nazionale di Poetry Slam',
                'visibility' => 'public',
                'created_by' => $user->id,
            ],
            [
                'name' => 'Poetry Slam Firenze',
                'description' => 'Gruppo di poeti e organizzatori di eventi a Firenze',
                'visibility' => 'public',
                'created_by' => $user->id,
            ],
        ];

        foreach ($groups as $groupData) {
            Group::firstOrCreate(
                ['name' => $groupData['name']],
                $groupData
            );
        }

        $this->command->info('Gruppi di test creati con successo!');
    }
}
