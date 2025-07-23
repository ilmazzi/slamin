<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Photo;
use App\Models\User;
use Faker\Factory as Faker;

class PhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('it_IT');
        
        // Ottieni tutti gli utenti esistenti
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->error('Nessun utente trovato. Crea prima alcuni utenti.');
            return;
        }

        // Array di immagini locali di alta qualità
        $placeholderImages = [
            'assets/images/profile-app/01.jpg',
            'assets/images/profile-app/03.jpg',
            'assets/images/profile-app/04.jpg',
            'assets/images/profile-app/05.jpg',
            'assets/images/profile-app/06.jpg',
            'assets/images/profile-app/07.jpg',
            'assets/images/profile-app/08.jpg',
            'assets/images/profile-app/09.jpg',
            'assets/images/profile-app/10.jpg',
            'assets/images/profile-app/11.jpg',
            'assets/images/profile-app/12.jpg',
            'assets/images/profile-app/13.jpg',
            'assets/images/profile-app/14.jpg',
            'assets/images/profile-app/15.jpg',
            'assets/images/profile-app/16.jpg',
            'assets/images/profile-app/17.jpg',
            'assets/images/profile-app/18.jpg',
            'assets/images/profile-app/19.jpg',
            'assets/images/profile-app/20.jpg',
            'assets/images/profile-app/21.jpg',
            'assets/images/profile-app/22.jpg',
            'assets/images/profile-app/23.jpg',
            'assets/images/profile-app/24.jpg',
            'assets/images/profile-app/25.jpg',
            'assets/images/profile-app/26.jpg',
            'assets/images/profile-app/27.jpg',
            'assets/images/profile-app/28.jpg',
            'assets/images/profile-app/29.jpg',
            'assets/images/profile-app/30.jpg',
            'assets/images/profile-app/31.jpg',
            'assets/images/profile-app/32.jpg',
        ];

        // Array di titoli per le foto
        $photoTitles = [
            'Momento perfetto',
            'Sorpresa della giornata',
            'Bellezza naturale',
            'Attimo fugace',
            'Colori della vita',
            'Estate infinita',
            'Sguardo profondo',
            'Sorriso sincero',
            'Paesaggio mozzafiato',
            'Giornata speciale',
            'Ricordo prezioso',
            'Emozione pura',
            'Vista panoramica',
            'Dettaglio artistico',
            'Momento magico',
            'Espressione autentica',
            'Scena quotidiana',
            'Bellezza nascosta',
            'Attimo di pace',
            'Vibrazione positiva',
        ];

        // Array di descrizioni per le foto
        $photoDescriptions = [
            'Un momento speciale catturato al volo',
            'La bellezza si trova nei dettagli più semplici',
            'Ogni foto racconta una storia unica',
            'I colori della natura sono sempre sorprendenti',
            'Un attimo di felicità condivisa',
            'La vita è fatta di piccoli momenti perfetti',
            'Ogni scatto è un ricordo prezioso',
            'La fotografia è l\'arte di fermare il tempo',
            'Un sorriso può illuminare una giornata',
            'I paesaggi ci ricordano la grandezza del mondo',
            'Ogni angolo ha la sua bellezza nascosta',
            'I momenti spontanei sono i più autentici',
            'La luce giusta può trasformare tutto',
            'Un dettaglio può raccontare molto',
            'La fotografia è poesia visiva',
            'Ogni immagine ha la sua emozione',
            'I colori parlano più delle parole',
            'Un momento di tranquillità',
            'La bellezza è ovunque, basta saperla vedere',
            'Ogni foto è un pezzo di storia personale',
        ];

        $this->command->info('Creazione foto finte in corso...');

        // Crea 50 foto finte
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $imageUrl = $placeholderImages[array_rand($placeholderImages)];
            $title = $photoTitles[array_rand($photoTitles)];
            $description = $photoDescriptions[array_rand($photoDescriptions)];
            
            // Genera like e visualizzazioni casuali
            $likeCount = $faker->numberBetween(0, 150);
            $viewCount = $faker->numberBetween($likeCount, 500);
            
            // Genera metadati casuali
            $metadata = [
                'width' => $faker->randomElement([800, 1024, 1200, 1600]),
                'height' => $faker->randomElement([600, 768, 900, 1200]),
                'file_size' => $faker->numberBetween(500000, 3000000), // 500KB - 3MB
                'camera' => $faker->randomElement(['iPhone 14', 'Samsung Galaxy S23', 'Canon EOS R5', 'Sony A7III', 'Nikon Z6']),
                'aperture' => $faker->randomFloat(1, 1.4, 8.0),
                'shutter_speed' => $faker->randomElement(['1/60', '1/125', '1/250', '1/500', '1/1000']),
                'iso' => $faker->randomElement([100, 200, 400, 800, 1600]),
                'focal_length' => $faker->randomElement([24, 35, 50, 85, 135]),
                'location' => $faker->randomElement([
                    'Milano, Italia',
                    'Roma, Italia',
                    'Firenze, Italia',
                    'Venezia, Italia',
                    'Napoli, Italia',
                    'Torino, Italia',
                    'Bologna, Italia',
                    'Genova, Italia',
                    'Palermo, Italia',
                    'Bari, Italia'
                ]),
                'tags' => $faker->randomElements([
                    'natura', 'paesaggio', 'ritratto', 'street', 'architettura', 
                    'macro', 'bianco e nero', 'colore', 'astratto', 'documentario',
                    'eventi', 'sport', 'cibo', 'viaggio', 'arte', 'moda', 'musica'
                ], $faker->numberBetween(2, 5))
            ];

            Photo::create([
                'user_id' => $user->id,
                'title' => $title,
                'description' => $description,
                'image_path' => $imageUrl,
                'thumbnail_path' => $imageUrl, // Usa la stessa immagine come thumbnail
                'alt_text' => $title . ' - Foto di ' . $user->getDisplayName(),
                'status' => 'approved',
                'like_count' => $likeCount,
                'view_count' => $viewCount,
                'moderation_notes' => null,
                'metadata' => $metadata,
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => $faker->dateTimeBetween('-6 months', 'now'),
            ]);
        }

        $this->command->info('✅ 50 foto finte create con successo!');
        $this->command->info('📸 Foto distribuite tra ' . $users->count() . ' utenti diversi');
        $this->command->info('🎨 Immagini placeholder di alta qualità da Picsum Photos');
        $this->command->info('📊 Like e visualizzazioni casuali generate');
        $this->command->info('📅 Date di creazione distribuite negli ultimi 6 mesi');
    }
}
