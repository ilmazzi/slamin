<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Gig;
use App\Models\GigApplication;
use App\Models\Notification;
use App\Observers\EventObserver;

class ResetGigsFromEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gigs:reset-from-events {--force : Forza il reset senza conferma}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resetta completamente i gig basandosi sugli eventi esistenti';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== RESET GIG DA EVENTI ===');
        $this->newLine();

        // Conta i gig esistenti
        $existingGigs = Gig::count();
        $existingApplications = GigApplication::count();

        $this->warn("Situazione attuale:");
        $this->line("- Gig esistenti: {$existingGigs}");
        $this->line("- Candidature esistenti: {$existingApplications}");
        $this->newLine();

        // Conta gli eventi con posizioni
        $eventsWithPositions = Event::whereNotNull('gig_positions')
                                   ->where('gig_positions', '!=', '[]')
                                   ->where('gig_positions', '!=', 'null')
                                   ->get();

        $this->info("Eventi con posizioni d'ingaggio trovati: {$eventsWithPositions->count()}");

        foreach ($eventsWithPositions as $event) {
            $positions = is_array($event->gig_positions) ? $event->gig_positions : json_decode($event->gig_positions, true) ?? [];
            $positionCount = count($positions);
            $this->line("- {$event->title} (ID: {$event->id}): {$positionCount} posizioni");
        }

        $this->newLine();

        // Chiedi conferma
        if (!$this->option('force') && !$this->confirm('Vuoi procedere con il reset completo dei gig?')) {
            $this->info('Operazione annullata.');
            return 0;
        }

        $this->info('Reset in corso...');

        // Elimina tutti i gig esistenti e le dipendenze
        $this->line('Eliminazione gig esistenti...');

        // Elimina le notifiche correlate ai gig
        $gigIds = Gig::pluck('id')->toArray();
        if (!empty($gigIds)) {
            $notificationCount = Notification::whereIn('type', [
                Notification::TYPE_GIG_APPLICATION,
                Notification::TYPE_GIG_APPLICATION_ACCEPTED,
                Notification::TYPE_GIG_APPLICATION_REJECTED,
                Notification::TYPE_GIG_APPLICATION_WITHDRAWN,
                Notification::TYPE_GIG_CLOSED,
                Notification::TYPE_GIG_REOPENED,
                Notification::TYPE_GIG_SHARED,
                Notification::TYPE_GIG_GLOBAL_MESSAGE
            ])->whereJsonContains('data->gig_id', $gigIds)->delete();
            $this->line("✅ Eliminate {$notificationCount} notifiche");
        }

        // Elimina le candidature
        $applicationCount = GigApplication::count();
        if ($applicationCount > 0) {
            GigApplication::query()->delete();
            $this->line("✅ Eliminate {$applicationCount} candidature");
        }

        // Elimina tutti i gig
        $deletedGigs = Gig::count();
        Gig::query()->delete();
        $this->line("✅ Eliminati {$deletedGigs} gig");

        $this->newLine();

        // Ricrea i gig dagli eventi
        $this->line('Ricreazione gig dagli eventi...');
        $observer = new EventObserver();
        $createdGigs = 0;

        foreach ($eventsWithPositions as $event) {
            $observer->syncGigPositions($event);
            $gigCount = $event->gigs()->count();
            $createdGigs += $gigCount;
            $this->line("✅ Evento '{$event->title}': creati {$gigCount} gig");
        }

        $this->newLine();
        $this->info("🎉 Reset completato!");
        $this->line("- Gig eliminati: {$deletedGigs}");
        $this->line("- Gig ricreati: {$createdGigs}");
        $this->line("- Candidature eliminate: {$applicationCount}");

        return 0;
    }
}
