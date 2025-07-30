<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\EventRequest;
use Illuminate\Support\Facades\DB;

class DiagnoseEventStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'diagnose:event-stats {user_id? : ID dell\'utente da diagnosticare}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnostica le statistiche degli eventi per identificare problemi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');

        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("Utente con ID {$userId} non trovato!");
                return 1;
            }
            $this->diagnoseUser($user);
        } else {
            $this->diagnoseAllUsers();
        }

        return 0;
    }

    private function diagnoseUser(User $user)
    {
        $this->info("=== DIAGNOSI STATISTICHE PER UTENTE: {$user->name} (ID: {$user->id}) ===");

        // 1. Conta eventi organizzati
        $organizedEvents = $user->organizedEvents()->get();
        $this->info("\n1. EVENTI ORGANIZZATI:");
        $this->info("   - Totale: " . $organizedEvents->count());

        if ($organizedEvents->count() > 0) {
            foreach ($organizedEvents as $event) {
                $this->info("     * ID: {$event->id}, Titolo: {$event->title}, Data: {$event->start_datetime}, Status: {$event->status}");
            }
        }

        // 2. Conta inviti ricevuti
        $receivedInvitations = $user->receivedInvitations()->with('event')->get();
        $this->info("\n2. INVITI RICEVUTI:");
        $this->info("   - Totale: " . $receivedInvitations->count());

        $acceptedInvitations = $receivedInvitations->where('status', 'accepted');
        $pendingInvitations = $receivedInvitations->where('status', 'pending');

        $this->info("   - Accettati: " . $acceptedInvitations->count());
        $this->info("   - In attesa: " . $pendingInvitations->count());

        if ($acceptedInvitations->count() > 0) {
            $this->info("   - Eventi con inviti accettati:");
            foreach ($acceptedInvitations as $invitation) {
                $event = $invitation->event;
                if ($event) {
                    $this->info("     * Evento ID: {$event->id}, Titolo: {$event->title}, Data: {$event->start_datetime}");
                } else {
                    $this->error("     * INVITO ORFANO - Evento ID {$invitation->event_id} non trovato!");
                }
            }
        }

        // 3. Conta richieste
        $eventRequests = $user->eventRequests()->with('event')->get();
        $this->info("\n3. RICHIESTE PARTECIPAZIONE:");
        $this->info("   - Totale: " . $eventRequests->count());

        $acceptedRequests = $eventRequests->where('status', 'accepted');
        $pendingRequests = $eventRequests->where('status', 'pending');

        $this->info("   - Accettate: " . $acceptedRequests->count());
        $this->info("   - In attesa: " . $pendingRequests->count());

        if ($acceptedRequests->count() > 0) {
            $this->info("   - Eventi con richieste accettate:");
            foreach ($acceptedRequests as $request) {
                $event = $request->event;
                if ($event) {
                    $this->info("     * Evento ID: {$event->id}, Titolo: {$event->title}, Data: {$event->start_datetime}");
                } else {
                    $this->error("     * RICHIESTA ORFANA - Evento ID {$request->event_id} non trovato!");
                }
            }
        }

        // 4. Calcola statistiche dashboard
        $this->info("\n4. STATISTICHE DASHBOARD:");

        // Eventi passati
        $pastOrganized = $user->organizedEvents()->where('start_datetime', '<', now())->count();
        $pastParticipated = $user->receivedInvitations()
                                 ->where('status', EventInvitation::STATUS_ACCEPTED)
                                 ->whereHas('event', function($q) {
                                     $q->where('start_datetime', '<', now());
                                 })->count();
        $pastRequests = $user->eventRequests()
                             ->where('status', EventRequest::STATUS_ACCEPTED)
                             ->whereHas('event', function($q) {
                                 $q->where('start_datetime', '<', now());
                             })->count();
        $pastEvents = $pastOrganized + $pastParticipated + $pastRequests;

        // Eventi futuri
        $futureOrganized = $user->organizedEvents()->where('start_datetime', '>=', now())->count();
        $futureParticipated = $user->receivedInvitations()
                                   ->where('status', EventInvitation::STATUS_ACCEPTED)
                                   ->whereHas('event', function($q) {
                                       $q->where('start_datetime', '>=', now());
                                   })->count();
        $futureRequests = $user->eventRequests()
                               ->where('status', EventRequest::STATUS_ACCEPTED)
                               ->whereHas('event', function($q) {
                                   $q->where('start_datetime', '>=', now());
                               })->count();
        $futureEvents = $futureOrganized + $futureParticipated + $futureRequests;

        // Eventi organizzati (tutti)
        $organizedEventsTotal = $user->organizedEvents()->count();

        // Inviti in attesa
        $pendingReceived = $user->receivedInvitations()
                                ->where('status', EventInvitation::STATUS_PENDING)
                                ->count();
        $pendingSent = $user->sentInvitations()
                            ->where('status', EventInvitation::STATUS_PENDING)
                            ->count();
        $pendingInvitations = $pendingReceived + $pendingSent;

        $this->info("   - Eventi Passati: {$pastEvents} (organizzati: {$pastOrganized}, partecipati: {$pastParticipated}, richieste: {$pastRequests})");
        $this->info("   - Eventi Futuri: {$futureEvents} (organizzati: {$futureOrganized}, partecipati: {$futureParticipated}, richieste: {$futureRequests})");
        $this->info("   - Eventi Organizzati: {$organizedEventsTotal}");
        $this->info("   - Inviti in Attesa: {$pendingInvitations} (ricevuti: {$pendingReceived}, inviati: {$pendingSent})");

        // 5. Verifica record orfani
        $this->info("\n5. VERIFICA RECORD ORFANI:");

        // Inviti orfani
        $orphanInvitations = EventInvitation::where('invited_user_id', $user->id)
                                           ->whereNotExists(function($query) {
                                               $query->select(DB::raw(1))
                                                     ->from('events')
                                                     ->whereRaw('events.id = event_invitations.event_id');
                                           })->count();

        if ($orphanInvitations > 0) {
            $this->error("   - INVITI ORFANI: {$orphanInvitations} inviti per eventi inesistenti!");
        } else {
            $this->info("   - Inviti: OK (nessun invito orfano)");
        }

        // Richieste orfane
        $orphanRequests = EventRequest::where('user_id', $user->id)
                                     ->whereNotExists(function($query) {
                                         $query->select(DB::raw(1))
                                               ->from('events')
                                               ->whereRaw('events.id = event_requests.event_id');
                                     })->count();

        if ($orphanRequests > 0) {
            $this->error("   - RICHIESTE ORFANE: {$orphanRequests} richieste per eventi inesistenti!");
        } else {
            $this->info("   - Richieste: OK (nessuna richiesta orfana)");
        }
    }

    private function diagnoseAllUsers()
    {
        $this->info("=== DIAGNOSI GENERALE STATISTICHE EVENTI ===");

        // Conta totale eventi
        $totalEvents = Event::count();
        $this->info("\n1. TOTALE EVENTI NEL DATABASE: {$totalEvents}");

        if ($totalEvents > 0) {
            $eventsByStatus = Event::select('status', DB::raw('count(*) as count'))
                                  ->groupBy('status')
                                  ->get();

            $this->info("   - Per status:");
            foreach ($eventsByStatus as $status) {
                $this->info("     * {$status->status}: {$status->count}");
            }
        }

        // Conta totale inviti
        $totalInvitations = EventInvitation::count();
        $this->info("\n2. TOTALE INVITI NEL DATABASE: {$totalInvitations}");

        if ($totalInvitations > 0) {
            $invitationsByStatus = EventInvitation::select('status', DB::raw('count(*) as count'))
                                                 ->groupBy('status')
                                                 ->get();

            $this->info("   - Per status:");
            foreach ($invitationsByStatus as $status) {
                $this->info("     * {$status->status}: {$status->count}");
            }
        }

        // Conta totale richieste
        $totalRequests = EventRequest::count();
        $this->info("\n3. TOTALE RICHIESTE NEL DATABASE: {$totalRequests}");

        if ($totalRequests > 0) {
            $requestsByStatus = EventRequest::select('status', DB::raw('count(*) as count'))
                                           ->groupBy('status')
                                           ->get();

            $this->info("   - Per status:");
            foreach ($requestsByStatus as $status) {
                $this->info("     * {$status->status}: {$status->count}");
            }
        }

        // Verifica record orfani globali
        $this->info("\n4. VERIFICA RECORD ORFANI GLOBALI:");

        $orphanInvitations = EventInvitation::whereNotExists(function($query) {
            $query->select(DB::raw(1))
                  ->from('events')
                  ->whereRaw('events.id = event_invitations.event_id');
        })->count();

        $orphanRequests = EventRequest::whereNotExists(function($query) {
            $query->select(DB::raw(1))
                  ->from('events')
                  ->whereRaw('events.id = event_requests.event_id');
        })->count();

        if ($orphanInvitations > 0) {
            $this->error("   - INVITI ORFANI: {$orphanInvitations} inviti per eventi inesistenti!");
        } else {
            $this->info("   - Inviti: OK (nessun invito orfano)");
        }

        if ($orphanRequests > 0) {
            $this->error("   - RICHIESTE ORFANE: {$orphanRequests} richieste per eventi inesistenti!");
        } else {
            $this->info("   - Richieste: OK (nessuna richiesta orfana)");
        }

        // Suggerisci utenti da diagnosticare
        $usersWithEvents = User::whereHas('organizedEvents')->orWhereHas('receivedInvitations')->orWhereHas('eventRequests')->count();
        $this->info("\n5. UTENTI CON ATTIVITÀ EVENTI: {$usersWithEvents}");

        if ($usersWithEvents > 0) {
            $this->info("   Per diagnosticare un utente specifico, usa: php artisan diagnose:event-stats {user_id}");
        }
    }
}
