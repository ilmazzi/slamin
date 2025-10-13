<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\EventInvitation;
use App\Models\EventRequest;
use Carbon\Carbon;

class TestEventsWithParticipantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users with role 'poet' or 'user'
        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['poet', 'user']);
        })->get();

        // Get admin/organizer for event creation
        $organizer = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'organizer']);
        })->first();

        if (!$organizer) {
            $this->command->error('No admin or organizer found. Please create at least one admin user.');
            return;
        }

        if ($users->count() < 3) {
            $this->command->warn('Need at least 3 users with poet/user role. Creating sample events anyway...');
        }

        $this->command->info('Creating 5 test Poetry Slam events with participants...');

        // Event 1: Future event with invitations
        $event1 = Event::create([
            'organizer_id' => $organizer->id,
            'title' => 'Slam Poetry Night - Edizione Primavera',
            'subtitle' => 'Una serata di poesia e passione',
            'description' => 'Unisciti a noi per una serata indimenticabile di slam poetry. I migliori poeti della città si sfideranno sul palco!',
            'category' => 'poetry_slam',
            'start_datetime' => Carbon::now()->addDays(15)->setTime(20, 0),
            'end_datetime' => Carbon::now()->addDays(15)->setTime(23, 30),
            'venue_name' => 'Teatro Comunale',
            'venue_address' => 'Via Roma, 123',
            'city' => 'Milano',
            'country' => 'IT',
            'is_public' => true,
            'is_online' => false,
            'max_participants' => 12,
            'entry_fee' => 0,
            'status' => 'published',
        ]);

        // Add 4 accepted invitations
        if ($users->count() >= 4) {
            foreach ($users->take(4) as $user) {
                EventInvitation::create([
                    'event_id' => $event1->id,
                    'inviter_id' => $organizer->id,
                    'invited_user_id' => $user->id,
                    'status' => 'accepted',
                    'invited_at' => Carbon::now()->subDays(5),
                    'responded_at' => Carbon::now()->subDays(3),
                ]);
            }
        }

        $this->command->info("✓ Event 1: {$event1->title} - 4 invitations");

        // Event 2: Upcoming event with requests
        $event2 = Event::create([
            'organizer_id' => $organizer->id,
            'title' => 'Battaglia di Rime - Open Mic',
            'subtitle' => 'Microfono aperto per tutti i poeti',
            'description' => 'Porta la tua poesia sul palco! Evento aperto a tutti, iscriviti e partecipa alla competizione.',
            'category' => 'poetry_slam',
            'start_datetime' => Carbon::now()->addDays(7)->setTime(19, 30),
            'end_datetime' => Carbon::now()->addDays(7)->setTime(22, 30),
            'venue_name' => 'Caffè Letterario',
            'venue_address' => 'Piazza della Poesia, 5',
            'city' => 'Roma',
            'country' => 'IT',
            'is_public' => true,
            'is_online' => false,
            'max_participants' => 15,
            'entry_fee' => 5,
            'status' => 'published',
        ]);

        // Add 3 accepted requests
        if ($users->count() >= 7) {
            foreach ($users->skip(4)->take(3) as $user) {
                EventRequest::create([
                    'event_id' => $event2->id,
                    'user_id' => $user->id,
                    'status' => 'accepted',
                    'user_message' => 'Vorrei partecipare con una mia poesia originale!',
                    'organizer_response' => 'Benvenuto! Non vediamo l\'ora di ascoltarti.',
                    'requested_at' => Carbon::now()->subDays(4),
                    'responded_at' => Carbon::now()->subDays(2),
                    'responded_by' => $organizer->id,
                ]);
            }
        }

        $this->command->info("✓ Event 2: {$event2->title} - 3 requests");

        // Event 3: Past event (ready to score)
        $event3 = Event::create([
            'organizer_id' => $organizer->id,
            'title' => 'Poetry Slam Championship - Finale Inverno',
            'subtitle' => 'La finale del campionato invernale',
            'description' => 'I migliori poeti dell\'inverno si sfidano per il titolo. Serata conclusiva del campionato!',
            'category' => 'poetry_slam',
            'start_datetime' => Carbon::now()->subDays(2)->setTime(20, 0),
            'end_datetime' => Carbon::now()->subDays(2)->setTime(23, 0),
            'venue_name' => 'Auditorium Centrale',
            'venue_address' => 'Corso Italia, 456',
            'city' => 'Torino',
            'country' => 'IT',
            'is_public' => true,
            'is_online' => false,
            'max_participants' => 10,
            'entry_fee' => 10,
            'status' => 'published',
        ]);

        // Add 6 invitations (5 accepted, 1 declined)
        if ($users->count() >= 13) {
            foreach ($users->skip(7)->take(5) as $user) {
                EventInvitation::create([
                    'event_id' => $event3->id,
                    'inviter_id' => $organizer->id,
                    'invited_user_id' => $user->id,
                    'status' => 'accepted',
                    'invited_at' => Carbon::now()->subDays(10),
                    'responded_at' => Carbon::now()->subDays(8),
                ]);
            }
            
            // One declined
            if ($users->count() >= 14) {
                EventInvitation::create([
                    'event_id' => $event3->id,
                    'inviter_id' => $organizer->id,
                    'invited_user_id' => $users->skip(13)->first()->id,
                    'status' => 'declined',
                    'invited_at' => Carbon::now()->subDays(10),
                    'responded_at' => Carbon::now()->subDays(7),
                    'response_message' => 'Mi dispiace, non posso partecipare.',
                ]);
            }
        }

        $this->command->info("✓ Event 3: {$event3->title} - 5 accepted, 1 declined");

        // Event 4: Private event with mixed invitations/requests
        $event4 = Event::create([
            'organizer_id' => $organizer->id,
            'title' => 'Slam Privato - Circolo Poetico',
            'subtitle' => 'Solo su invito',
            'description' => 'Evento privato per i membri del circolo poetico della città.',
            'category' => 'poetry_slam',
            'start_datetime' => Carbon::now()->addDays(20)->setTime(18, 0),
            'end_datetime' => Carbon::now()->addDays(20)->setTime(21, 0),
            'venue_name' => 'Villa Rosa',
            'venue_address' => 'Via dei Poeti, 789',
            'city' => 'Firenze',
            'country' => 'IT',
            'is_public' => false,
            'is_online' => false,
            'max_participants' => 8,
            'entry_fee' => 0,
            'status' => 'published',
        ]);

        // Add 3 invitations and 2 requests
        if ($users->count() >= 17) {
            // 3 invitations
            foreach ($users->skip(14)->take(3) as $user) {
                EventInvitation::create([
                    'event_id' => $event4->id,
                    'inviter_id' => $organizer->id,
                    'invited_user_id' => $user->id,
                    'status' => 'accepted',
                    'invited_at' => Carbon::now()->subDays(3),
                    'responded_at' => Carbon::now()->subDays(1),
                ]);
            }

            // 2 requests
            foreach ($users->skip(17)->take(2) as $user) {
                EventRequest::create([
                    'event_id' => $event4->id,
                    'user_id' => $user->id,
                    'status' => 'accepted',
                    'user_message' => 'Posso partecipare anche se è privato?',
                    'organizer_response' => 'Certo, sei il benvenuto!',
                    'requested_at' => Carbon::now()->subDays(2),
                    'responded_at' => Carbon::now()->subHours(12),
                    'responded_by' => $organizer->id,
                ]);
            }
        }

        $this->command->info("✓ Event 4: {$event4->title} - 3 invitations, 2 requests");

        // Event 5: Online event with many participants
        $event5 = Event::create([
            'organizer_id' => $organizer->id,
            'title' => 'Slam Poetry Online - Edizione Internazionale',
            'subtitle' => 'Poeti da tutta Italia online',
            'description' => 'Il primo slam poetry interamente online. Partecipa da casa tua e sfida i migliori poeti italiani!',
            'category' => 'poetry_slam',
            'start_datetime' => Carbon::now()->addDays(30)->setTime(21, 0),
            'end_datetime' => Carbon::now()->addDays(30)->setTime(23, 30),
            'venue_name' => 'Piattaforma Zoom',
            'venue_address' => null,
            'city' => null,
            'country' => 'IT',
            'timezone' => 'Europe/Rome',
            'is_public' => true,
            'is_online' => true,
            'max_participants' => 20,
            'entry_fee' => 0,
            'status' => 'published',
        ]);

        // Add many requests (8)
        if ($users->count() >= 8) {
            foreach ($users->take(8) as $user) {
                EventRequest::create([
                    'event_id' => $event5->id,
                    'user_id' => $user->id,
                    'status' => 'accepted',
                    'user_message' => 'Voglio partecipare all\'evento online!',
                    'organizer_response' => 'Perfetto, ti invieremo il link!',
                    'requested_at' => Carbon::now()->subDays(1),
                    'responded_at' => Carbon::now()->subHours(6),
                    'responded_by' => $organizer->id,
                ]);
            }
        }

        $this->command->info("✓ Event 5: {$event5->title} - 8 requests");

        $this->command->info("\n✅ Created 5 test events with participants!");
        $this->command->info("Total participants across all events:");
        $this->command->info("- Event 1: 4 participants (invitations)");
        $this->command->info("- Event 2: 3 participants (requests)");
        $this->command->info("- Event 3: 5 participants (invitations) - Ready to score!");
        $this->command->info("- Event 4: 5 participants (3 invitations, 2 requests)");
        $this->command->info("- Event 5: 8 participants (requests)");
    }
}
