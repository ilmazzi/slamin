<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;
use App\Models\GamificationLevel;
use Illuminate\Support\Facades\DB;

class GamificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedLevels();
            $this->seedPortalBadges();
            $this->seedEventBadges();
        });
    }

    /**
     * Seed gamification levels
     */
    private function seedLevels(): void
    {
        $levels = [
            ['level' => 1, 'name' => 'Novizio', 'required_points' => 0, 'required_badges' => 0, 'order' => 1],
            ['level' => 2, 'name' => 'Apprendista', 'required_points' => 100, 'required_badges' => 3, 'order' => 2],
            ['level' => 3, 'name' => 'Poeta', 'required_points' => 500, 'required_badges' => 10, 'order' => 3],
            ['level' => 4, 'name' => 'Artista', 'required_points' => 1500, 'required_badges' => 20, 'order' => 4],
            ['level' => 5, 'name' => 'Maestro', 'required_points' => 3000, 'required_badges' => 35, 'order' => 5],
            ['level' => 6, 'name' => 'Virtuoso', 'required_points' => 6000, 'required_badges' => 50, 'order' => 6],
            ['level' => 7, 'name' => 'Leggenda', 'required_points' => 10000, 'required_badges' => 75, 'order' => 7],
        ];

        foreach ($levels as $level) {
            GamificationLevel::updateOrCreate(
                ['level' => $level['level']],
                array_merge($level, ['description' => 'Livello ' . $level['level']])
            );
        }
    }

    /**
     * Seed portal badges
     */
    private function seedPortalBadges(): void
    {
        $portalBadges = [
            // Video badges
            ['category' => 'videos', 'name' => 'Primo Video', 'description' => 'Carica il tuo primo video', 'criteria_value' => 1, 'criteria_type' => 'first_time', 'points' => 10, 'order' => 1],
            ['category' => 'videos', 'name' => 'Videomaker', 'description' => 'Carica 5 video', 'criteria_value' => 5, 'criteria_type' => 'milestone', 'points' => 25, 'order' => 2],
            ['category' => 'videos', 'name' => 'Regista', 'description' => 'Carica 10 video', 'criteria_value' => 10, 'criteria_type' => 'milestone', 'points' => 50, 'order' => 3],
            ['category' => 'videos', 'name' => 'Produttore', 'description' => 'Carica 50 video', 'criteria_value' => 50, 'criteria_type' => 'milestone', 'points' => 150, 'order' => 4],
            ['category' => 'videos', 'name' => 'Maestro del Video', 'description' => 'Carica 100 video', 'criteria_value' => 100, 'criteria_type' => 'milestone', 'points' => 300, 'order' => 5],

            // Poem badges
            ['category' => 'poems', 'name' => 'Prima Poesia', 'description' => 'Pubblica la tua prima poesia', 'criteria_value' => 1, 'criteria_type' => 'first_time', 'points' => 10, 'order' => 1],
            ['category' => 'poems', 'name' => 'Poeta Emergente', 'description' => 'Pubblica 5 poesie', 'criteria_value' => 5, 'criteria_type' => 'milestone', 'points' => 25, 'order' => 2],
            ['category' => 'poems', 'name' => 'Poeta Affermato', 'description' => 'Pubblica 10 poesie', 'criteria_value' => 10, 'criteria_type' => 'milestone', 'points' => 50, 'order' => 3],
            ['category' => 'poems', 'name' => 'Poeta Prolifico', 'description' => 'Pubblica 50 poesie', 'criteria_value' => 50, 'criteria_type' => 'milestone', 'points' => 150, 'order' => 4],
            ['category' => 'poems', 'name' => 'Maestro Poeta', 'description' => 'Pubblica 100 poesie', 'criteria_value' => 100, 'criteria_type' => 'milestone', 'points' => 300, 'order' => 5],

            // Photo badges
            ['category' => 'photos', 'name' => 'Prima Foto', 'description' => 'Carica la tua prima foto', 'criteria_value' => 1, 'criteria_type' => 'first_time', 'points' => 10, 'order' => 1],
            ['category' => 'photos', 'name' => 'Fotografo Amatoriale', 'description' => 'Carica 5 foto', 'criteria_value' => 5, 'criteria_type' => 'milestone', 'points' => 25, 'order' => 2],
            ['category' => 'photos', 'name' => 'Fotografo', 'description' => 'Carica 10 foto', 'criteria_value' => 10, 'criteria_type' => 'milestone', 'points' => 50, 'order' => 3],
            ['category' => 'photos', 'name' => 'Fotografo Esperto', 'description' => 'Carica 50 foto', 'criteria_value' => 50, 'criteria_type' => 'milestone', 'points' => 150, 'order' => 4],
            ['category' => 'photos', 'name' => 'Maestro Fotografo', 'description' => 'Carica 100 foto', 'criteria_value' => 100, 'criteria_type' => 'milestone', 'points' => 300, 'order' => 5],

            // Article badges
            ['category' => 'articles', 'name' => 'Primo Articolo', 'description' => 'Pubblica il tuo primo articolo', 'criteria_value' => 1, 'criteria_type' => 'first_time', 'points' => 15, 'order' => 1],
            ['category' => 'articles', 'name' => 'Giornalista', 'description' => 'Pubblica 5 articoli', 'criteria_value' => 5, 'criteria_type' => 'milestone', 'points' => 35, 'order' => 2],
            ['category' => 'articles', 'name' => 'Editorialista', 'description' => 'Pubblica 10 articoli', 'criteria_value' => 10, 'criteria_type' => 'milestone', 'points' => 75, 'order' => 3],
            ['category' => 'articles', 'name' => 'Scrittore', 'description' => 'Pubblica 50 articoli', 'criteria_value' => 50, 'criteria_type' => 'milestone', 'points' => 200, 'order' => 4],

            // Like badges
            ['category' => 'likes', 'name' => 'Primo Like', 'description' => 'Metti il tuo primo mi piace', 'criteria_value' => 1, 'criteria_type' => 'first_time', 'points' => 5, 'order' => 1],
            ['category' => 'likes', 'name' => 'Fan', 'description' => 'Metti 10 mi piace', 'criteria_value' => 10, 'criteria_type' => 'milestone', 'points' => 10, 'order' => 2],
            ['category' => 'likes', 'name' => 'Sostenitore', 'description' => 'Metti 50 mi piace', 'criteria_value' => 50, 'criteria_type' => 'milestone', 'points' => 25, 'order' => 3],
            ['category' => 'likes', 'name' => 'Super Fan', 'description' => 'Metti 100 mi piace', 'criteria_value' => 100, 'criteria_type' => 'milestone', 'points' => 50, 'order' => 4],
            ['category' => 'likes', 'name' => 'Appassionato', 'description' => 'Metti 500 mi piace', 'criteria_value' => 500, 'criteria_type' => 'milestone', 'points' => 100, 'order' => 5],
            ['category' => 'likes', 'name' => 'Entusiasta', 'description' => 'Metti 1000 mi piace', 'criteria_value' => 1000, 'criteria_type' => 'milestone', 'points' => 200, 'order' => 6],

            // Comment badges
            ['category' => 'comments', 'name' => 'Primo Commento', 'description' => 'Scrivi il tuo primo commento', 'criteria_value' => 1, 'criteria_type' => 'first_time', 'points' => 5, 'order' => 1],
            ['category' => 'comments', 'name' => 'Commentatore', 'description' => 'Scrivi 10 commenti', 'criteria_value' => 10, 'criteria_type' => 'milestone', 'points' => 15, 'order' => 2],
            ['category' => 'comments', 'name' => 'Conversatore', 'description' => 'Scrivi 50 commenti', 'criteria_value' => 50, 'criteria_type' => 'milestone', 'points' => 40, 'order' => 3],
            ['category' => 'comments', 'name' => 'Opinionista', 'description' => 'Scrivi 100 commenti', 'criteria_value' => 100, 'criteria_type' => 'milestone', 'points' => 80, 'order' => 4],
            ['category' => 'comments', 'name' => 'Critico', 'description' => 'Scrivi 500 commenti', 'criteria_value' => 500, 'criteria_type' => 'milestone', 'points' => 150, 'order' => 5],

            // Forum posts badges
            ['category' => 'posts', 'name' => 'Primo Post', 'description' => 'Crea il tuo primo post nel forum', 'criteria_value' => 1, 'criteria_type' => 'first_time', 'points' => 10, 'order' => 1],
            ['category' => 'posts', 'name' => 'Partecipante Attivo', 'description' => 'Crea 10 post nel forum', 'criteria_value' => 10, 'criteria_type' => 'milestone', 'points' => 30, 'order' => 2],
            ['category' => 'posts', 'name' => 'Membro Attivo', 'description' => 'Crea 50 post nel forum', 'criteria_value' => 50, 'criteria_type' => 'milestone', 'points' => 100, 'order' => 3],
            ['category' => 'posts', 'name' => 'Top Contributor', 'description' => 'Crea 100 post nel forum', 'criteria_value' => 100, 'criteria_type' => 'milestone', 'points' => 200, 'order' => 4],
        ];

        foreach ($portalBadges as $badge) {
            Badge::updateOrCreate(
                ['type' => 'portal', 'category' => $badge['category'], 'criteria_value' => $badge['criteria_value']],
                array_merge($badge, ['type' => 'portal', 'icon_path' => 'assets/images/draghetto.png'])
            );
        }
    }

    /**
     * Seed event badges (Poetry Slam)
     */
    private function seedEventBadges(): void
    {
        $eventBadges = [
            // Participation badges
            ['category' => 'event_participation', 'name' => 'Prima Esibizione', 'description' => 'Partecipa al tuo primo Poetry Slam', 'criteria_value' => 1, 'criteria_type' => 'first_time', 'points' => 20, 'order' => 1],
            ['category' => 'event_participation', 'name' => 'Performer', 'description' => 'Partecipa a 5 Poetry Slam', 'criteria_value' => 5, 'criteria_type' => 'milestone', 'points' => 50, 'order' => 2],
            ['category' => 'event_participation', 'name' => 'Slammer', 'description' => 'Partecipa a 10 Poetry Slam', 'criteria_value' => 10, 'criteria_type' => 'milestone', 'points' => 100, 'order' => 3],
            ['category' => 'event_participation', 'name' => 'Veterano', 'description' => 'Partecipa a 50 Poetry Slam', 'criteria_value' => 50, 'criteria_type' => 'milestone', 'points' => 300, 'order' => 4],

            // Winning badges
            ['category' => 'event_wins', 'name' => 'Campione - Oro', 'description' => 'Vinci il primo posto in un Poetry Slam', 'criteria_value' => 1, 'criteria_type' => 'special', 'points' => 100, 'order' => 1],
            ['category' => 'event_wins', 'name' => 'Finalista - Argento', 'description' => 'Arriva secondo in un Poetry Slam', 'criteria_value' => 1, 'criteria_type' => 'special', 'points' => 75, 'order' => 2],
            ['category' => 'event_wins', 'name' => 'Podio - Bronzo', 'description' => 'Arriva terzo in un Poetry Slam', 'criteria_value' => 1, 'criteria_type' => 'special', 'points' => 50, 'order' => 3],
            
            // Multiple wins
            ['category' => 'event_wins', 'name' => 'Campione Seriale', 'description' => 'Vinci 3 Poetry Slam', 'criteria_value' => 3, 'criteria_type' => 'milestone', 'points' => 300, 'order' => 4],
            ['category' => 'event_wins', 'name' => 'Leggenda del Slam', 'description' => 'Vinci 10 Poetry Slam', 'criteria_value' => 10, 'criteria_type' => 'milestone', 'points' => 1000, 'order' => 5],
        ];

        foreach ($eventBadges as $badge) {
            Badge::updateOrCreate(
                ['type' => 'event', 'category' => $badge['category'], 'name' => $badge['name']],
                array_merge($badge, ['type' => 'event', 'icon_path' => 'assets/images/draghetto.png'])
            );
        }
    }
}

