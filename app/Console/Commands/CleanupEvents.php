<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\EventRequest;
use Illuminate\Support\Facades\DB;

class CleanupEvents extends Command
{
    protected $signature = 'cleanup:events {--force : Forza l\'eliminazione senza conferma}';
    protected $description = 'Elimina tutti gli eventi dal database';

    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('Sei sicuro di voler eliminare TUTTI gli eventi dal database? Questa operazione non può essere annullata!')) {
                $this->info('Operazione annullata.');
                return 0;
            }
        }

        $this->info("=== PULIZIA EVENTI ===");

        // Conta prima
        $eventsCount = Event::count();
        $invitationsCount = EventInvitation::count();
        $requestsCount = EventRequest::count();

        $this->info("Eventi da eliminare: {$eventsCount}");
        $this->info("Inviti da eliminare: {$invitationsCount}");
        $this->info("Richieste da eliminare: {$requestsCount}");

        if ($eventsCount == 0) {
            $this->info("Nessun evento da eliminare.");
            return 0;
        }

                        // Elimina tutto in una transazione
        DB::transaction(function () {
            // Elimina inviti
            EventInvitation::query()->delete();
            $this->info("Inviti eliminati.");

            // Elimina richieste
            EventRequest::query()->delete();
            $this->info("Richieste eliminate.");

            // Elimina eventi
            Event::query()->delete();
            $this->info("Eventi eliminati.");
        });

        $this->info("Pulizia completata con successo!");

        // Verifica
        $remainingEvents = Event::count();
        $remainingInvitations = EventInvitation::count();
        $remainingRequests = EventRequest::count();

        $this->info("Verifica finale:");
        $this->info("- Eventi rimanenti: {$remainingEvents}");
        $this->info("- Inviti rimanenti: {$remainingInvitations}");
        $this->info("- Richieste rimanenti: {$remainingRequests}");

        return 0;
    }
}
