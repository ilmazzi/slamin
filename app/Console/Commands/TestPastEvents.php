<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Carbon\Carbon;

class TestPastEvents extends Command
{
    protected $signature = 'events:test-past';
    protected $description = 'Testa la query degli eventi passati per debug';

    public function handle()
    {
        $this->info('=== TEST EVENTI PASSATI ===');
        $this->newLine();

        // Test 1: Tutti gli eventi nel database
        $allEvents = Event::all();
        $this->info("1. Tutti gli eventi nel database: {$allEvents->count()}");
        foreach ($allEvents as $event) {
            $this->line("   - ID: {$event->id}, Titolo: {$event->title}, Data: {$event->start_datetime}, Pubblico: " . ($event->is_public ? 'Sì' : 'No'));
        }
        $this->newLine();

        // Test 2: Eventi passati (solo data)
        $pastEvents = Event::where('start_datetime', '<', now())->get();
        $this->info("2. Eventi passati (solo data): {$pastEvents->count()}");
        foreach ($pastEvents as $event) {
            $this->line("   - ID: {$event->id}, Titolo: {$event->title}, Data: {$event->start_datetime}, Pubblico: " . ($event->is_public ? 'Sì' : 'No'));
        }
        $this->newLine();

        // Test 3: Eventi passati pubblici
        $pastPublicEvents = Event::where('start_datetime', '<', now())
                                ->where('is_public', true)
                                ->get();
        $this->info("3. Eventi passati pubblici: {$pastPublicEvents->count()}");
        foreach ($pastPublicEvents as $event) {
            $this->line("   - ID: {$event->id}, Titolo: {$event->title}, Data: {$event->start_datetime}");
        }
        $this->newLine();

        // Test 4: Query completa come nel controller
        $query = Event::with(['organizer', 'venueOwner', 'invitations.invitedUser', 'requests.user'])
                     ->published()
                     ->orderBy('start_datetime');

        $query->where('start_datetime', '<', now());
        $query->where('is_public', true);

        $finalEvents = $query->get();
        $this->info("4. Query completa (come nel controller): {$finalEvents->count()}");
        foreach ($finalEvents as $event) {
            $this->line("   - ID: {$event->id}, Titolo: {$event->title}, Data: {$event->start_datetime}, Status: {$event->status}");
        }
        $this->newLine();

        // Test 5: Verifica scope published
        $this->info("5. Verifica scope 'published':");
        $publishedEvents = Event::published()->get();
        $this->line("   Eventi con status 'published': {$publishedEvents->count()}");
        foreach ($publishedEvents as $event) {
            $this->line("   - ID: {$event->id}, Titolo: {$event->title}, Status: {$event->status}");
        }

        return 0;
    }
}
