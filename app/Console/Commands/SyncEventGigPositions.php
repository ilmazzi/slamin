<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Observers\EventObserver;

class SyncEventGigPositions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:sync-gig-positions {event_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizza le posizioni d\'ingaggio degli eventi con i gig';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $eventId = $this->argument('event_id');
        
        if ($eventId) {
            $event = Event::find($eventId);
            if (!$event) {
                $this->error("Evento con ID {$eventId} non trovato!");
                return 1;
            }
            
            $this->syncEvent($event);
        } else {
            // Sincronizza tutti gli eventi con posizioni d'ingaggio
            $events = Event::whereNotNull('gig_positions')
                          ->where('gig_positions', '!=', '[]')
                          ->where('gig_positions', '!=', 'null')
                          ->get();
            
            $this->info("Trovati {$events->count()} eventi con posizioni d'ingaggio da sincronizzare.");
            
            $bar = $this->output->createProgressBar($events->count());
            $bar->start();
            
            foreach ($events as $event) {
                $this->syncEvent($event, false);
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine();
        }
        
        $this->info('Sincronizzazione completata!');
        return 0;
    }
    
    /**
     * Sincronizza un singolo evento
     */
    private function syncEvent(Event $event, bool $showDetails = true): void
    {
        if ($showDetails) {
            $this->info("Sincronizzando evento: {$event->title} (ID: {$event->id})");
        }
        
        $gigPositions = $event->gig_positions ?? [];
        
        if (empty($gigPositions)) {
            if ($showDetails) {
                $this->warn("Nessuna posizione d'ingaggio trovata per questo evento.");
            }
            return;
        }
        
        if ($showDetails) {
            $this->info("Trovate " . count($gigPositions) . " posizioni d'ingaggio.");
        }
        
        // Usa l'observer per sincronizzare
        $observer = new EventObserver();
        $observer->syncGigPositions($event);
        
        if ($showDetails) {
            $gigCount = $event->gigs()->count();
            $this->info("Creati/aggiornati {$gigCount} gig per questo evento.");
        }
    }
} 