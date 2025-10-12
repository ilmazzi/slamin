<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\EventRound;
use App\Models\EventScore;
use App\Models\User;
use Carbon\Carbon;

class PoetrySlamTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizer = User::first(); // User ID 1

        if (!$organizer) {
            $this->command->error('Nessun utente trovato! Crea un utente prima.');
            return;
        }

        // Event 1: Poetry Slam Milano - Futuro (da organizzare)
        $event1 = Event::create([
            'title' => 'Poetry Slam Milano - Primavera 2025',
            'subtitle' => 'La gara di poesia più attesa dell\'anno',
            'description' => 'Un evento imperdibile per gli amanti della poesia performativa. Vieni a sfidarti sul palco del teatro più prestigioso di Milano!',
            'category' => 'poetry_slam',
            'start_datetime' => Carbon::now()->addMonths(2),
            'end_datetime' => Carbon::now()->addMonths(2)->addHours(4),
            'registration_deadline' => Carbon::now()->addMonth(),
            'venue_name' => 'Teatro della Poesia',
            'venue_address' => 'Via dei Versi 10',
            'city' => 'Milano',
            'country' => 'IT',
            'postcode' => '20100',
            'is_public' => true,
            'max_participants' => 15,
            'entry_fee' => 0,
            'status' => 'published',
            'organizer_id' => $organizer->id,
            'allow_requests' => true,
        ]);

        // Event 2: Poetry Slam Roma - Con partecipanti (in corso)
        $event2 = Event::create([
            'title' => 'Poetry Slam Roma - Torneo Autunnale',
            'subtitle' => 'La sfida delle parole nella Capitale',
            'description' => 'Torna il Poetry Slam più emozionante di Roma! Iscrizioni aperte per poeti di tutti i livelli.',
            'category' => 'poetry_slam',
            'start_datetime' => Carbon::now()->addWeeks(2),
            'end_datetime' => Carbon::now()->addWeeks(2)->addHours(3),
            'registration_deadline' => Carbon::now()->addWeek(),
            'venue_name' => 'Caffè Letterario',
            'venue_address' => 'Piazza delle Muse 5',
            'city' => 'Roma',
            'country' => 'IT',
            'postcode' => '00100',
            'is_public' => true,
            'max_participants' => 12,
            'entry_fee' => 5,
            'status' => 'published',
            'organizer_id' => $organizer->id,
            'allow_requests' => true,
        ]);

        // Aggiungi round per evento 2
        EventRound::create([
            'event_id' => $event2->id,
            'round_number' => 1,
            'name' => 'Primo Turno',
            'scoring_type' => 'average',
            'order' => 1,
        ]);

        // Aggiungi alcuni partecipanti all'evento 2
        $this->addParticipants($event2, $organizer);

        // Event 3: Poetry Slam Completato (con classifica)
        $event3 = Event::create([
            'title' => 'Poetry Slam Firenze - Edizione Estiva',
            'subtitle' => 'La finale che ha infiammato il pubblico',
            'description' => 'Evento concluso con grande successo! Guarda la classifica finale e i vincitori.',
            'category' => 'poetry_slam',
            'start_datetime' => Carbon::now()->subWeeks(2),
            'end_datetime' => Carbon::now()->subWeeks(2)->addHours(4),
            'registration_deadline' => Carbon::now()->subMonth(),
            'venue_name' => 'Teatro Comunale',
            'venue_address' => 'Piazza della Repubblica 1',
            'city' => 'Firenze',
            'country' => 'IT',
            'postcode' => '50100',
            'is_public' => true,
            'max_participants' => 10,
            'entry_fee' => 0,
            'status' => 'completed',
            'organizer_id' => $organizer->id,
            'allow_requests' => false,
        ]);

        // Aggiungi round per evento 3
        $round1 = EventRound::create([
            'event_id' => $event3->id,
            'round_number' => 1,
            'name' => 'Semifinale',
            'scoring_type' => 'average',
            'order' => 1,
        ]);

        $round2 = EventRound::create([
            'event_id' => $event3->id,
            'round_number' => 2,
            'name' => 'Finale',
            'scoring_type' => 'average',
            'order' => 2,
        ]);

        // Aggiungi partecipanti e punteggi all'evento 3
        $this->addParticipantsWithScores($event3, $organizer);

        $this->command->info('✅ Creati 3 eventi Poetry Slam di test!');
        $this->command->info('📅 Evento 1: Futuro (Milano) - Da organizzare');
        $this->command->info('📅 Evento 2: Prossimo (Roma) - Con partecipanti');
        $this->command->info('📅 Evento 3: Completato (Firenze) - Con classifica');
    }

    protected function addParticipants($event, $organizer)
    {
        // Partecipante 1: User registrato (organizer stesso)
        EventParticipant::create([
            'event_id' => $event->id,
            'user_id' => $organizer->id,
            'registration_type' => 'organizer_added',
            'status' => 'confirmed',
            'performance_order' => 1,
            'added_by' => $organizer->id,
        ]);

        // Partecipante 2: Guest
        EventParticipant::create([
            'event_id' => $event->id,
            'guest_name' => 'Marco Rossi',
            'guest_email' => 'marco.rossi@example.com',
            'guest_bio' => 'Poeta emergente da Bologna',
            'registration_type' => 'guest',
            'status' => 'confirmed',
            'performance_order' => 2,
            'added_by' => $organizer->id,
        ]);

        // Partecipante 3: Guest
        EventParticipant::create([
            'event_id' => $event->id,
            'guest_name' => 'Laura Bianchi',
            'guest_email' => 'laura.bianchi@example.com',
            'guest_bio' => 'Poetessa veterana, già vincitrice di 5 Poetry Slam',
            'registration_type' => 'guest',
            'status' => 'confirmed',
            'performance_order' => 3,
            'added_by' => $organizer->id,
        ]);

        // Partecipante 4: Guest
        EventParticipant::create([
            'event_id' => $event->id,
            'guest_name' => 'Alessandro Verdi',
            'registration_type' => 'guest',
            'status' => 'confirmed',
            'performance_order' => 4,
            'added_by' => $organizer->id,
        ]);
    }

    protected function addParticipantsWithScores($event, $organizer)
    {
        // Crea 6 partecipanti
        $participants = [
            ['name' => 'Sofia Ferrari', 'email' => 'sofia.ferrari@example.com', 'scores' => [9.5, 9.8]],
            ['name' => 'Luca Marino', 'email' => 'luca.marino@example.com', 'scores' => [9.2, 9.6]],
            ['name' => 'Giulia Romano', 'email' => 'giulia.romano@example.com', 'scores' => [9.3, 9.4]],
            ['name' => 'Francesco Costa', 'email' => 'francesco.costa@example.com', 'scores' => [8.9, 9.1]],
            ['name' => 'Elena Ricci', 'email' => 'elena.ricci@example.com', 'scores' => [8.7, 8.9]],
            ['user_id' => $organizer->id, 'scores' => [8.5, 8.7]], // Organizer partecipa
        ];

        foreach ($participants as $index => $participantData) {
            $participant = EventParticipant::create([
                'event_id' => $event->id,
                'user_id' => $participantData['user_id'] ?? null,
                'guest_name' => $participantData['name'] ?? null,
                'guest_email' => $participantData['email'] ?? null,
                'registration_type' => isset($participantData['user_id']) ? 'organizer_added' : 'guest',
                'status' => 'performed',
                'performance_order' => $index + 1,
                'added_by' => $organizer->id,
            ]);

            // Aggiungi punteggi per entrambi i round
            foreach ($participantData['scores'] as $roundNum => $score) {
                EventScore::create([
                    'event_id' => $event->id,
                    'participant_id' => $participant->id,
                    'judge_id' => $organizer->id,
                    'round' => $roundNum + 1,
                    'score' => $score,
                    'scored_at' => now(),
                ]);
            }
        }

        // Calcola la classifica automaticamente
        $scoringService = app(\App\Services\EventScoringService::class);
        $scoringService->calculateRankings($event);

        $this->command->info('✅ Evento 3: Creato con 6 partecipanti e classifica completa!');
    }
}
