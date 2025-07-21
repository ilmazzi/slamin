<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Video;

class ProfileTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Aggiorna l'utente esistente con dati di profilo
        $user = User::where('email', 'organizer@poetryslam.it')->first();

        if ($user) {
            $user->update([
                'bio' => 'Organizzatore appassionato di Poetry Slam con oltre 10 anni di esperienza nel settore. Amo creare spazi dove i poeti possano esprimersi liberamente e condividere le loro storie.',
                'nickname' => 'SlamMaster',
                'phone' => '+39 333 1234567',
                'website' => 'https://poetryslam.it',
                'social_facebook' => 'https://facebook.com/slammaster',
                'social_instagram' => 'https://instagram.com/slammaster',
                'social_youtube' => 'https://youtube.com/@slammaster',
                'social_twitter' => 'https://twitter.com/slammaster',
                'location' => 'Milano, Italia'
            ]);

            $this->command->info('✅ Profilo utente aggiornato con successo');
        }

        // Crea alcuni video di test
        $videos = [
            [
                'title' => 'La Mia Prima Performance',
                'description' => 'La mia prima performance di Poetry Slam al Caffè Letterario di Milano. Un momento emozionante che ricorderò per sempre.',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'user_id' => $user->id,
                'views' => 1250,
                'is_public' => true
            ],
            [
                'title' => 'Poesia sulla Libertà',
                'description' => 'Una poesia che ho scritto durante il lockdown, parla della libertà e del desiderio di tornare a vivere normalmente.',
                'video_url' => 'https://www.youtube.com/watch?v=9bZkp7q19f0',
                'user_id' => $user->id,
                'views' => 890,
                'is_public' => true
            ],
            [
                'title' => 'Workshop di Poetry Slam',
                'description' => 'Un workshop che ho tenuto per insegnare le basi del Poetry Slam ai principianti.',
                'video_url' => 'https://www.youtube.com/watch?v=ZZ5LpwO-An4',
                'user_id' => $user->id,
                'views' => 567,
                'is_public' => true
            ]
        ];

        foreach ($videos as $videoData) {
            Video::create($videoData);
        }

        $this->command->info('✅ ' . count($videos) . ' video di test creati');
        $this->command->info('🎉 Seeder completato! Ora puoi testare il sistema profilo.');
    }
}
