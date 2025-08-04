<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Gig;
use App\Models\GigApplication;
use App\Models\Notification;

class TestEventDeletion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:test-deletion {event_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa l\'eliminazione completa di un evento con tutte le sue dipendenze';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $eventId = $this->argument('event_id');
        $event = Event::find($eventId);

        if (!$event) {
            $this->error("Evento con ID {$eventId} non trovato!");
            return 1;
        }

        $this->info("=== TEST ELIMINAZIONE EVENTO ===");
        $this->info("Evento: {$event->title} (ID: {$event->id})");
        $this->newLine();

        // Conta le dipendenze prima dell'eliminazione
        $this->showDependenciesCount($event, 'PRIMA dell\'eliminazione');

        // Chiedi conferma
        if (!$this->confirm('Vuoi procedere con l\'eliminazione dell\'evento?')) {
            $this->info('Operazione annullata.');
            return 0;
        }

        // Elimina l'evento
        $this->info('Eliminazione in corso...');
        $event->delete();

        $this->info('Evento eliminato con successo!');
        $this->newLine();

        // Verifica che tutto sia stato eliminato
        $this->showDependenciesCount($eventId, 'DOPO l\'eliminazione');

        $this->info('Test completato!');
        return 0;
    }

    /**
     * Mostra il conteggio delle dipendenze
     */
    private function showDependenciesCount($eventOrId, string $phase): void
    {
        $eventId = is_numeric($eventOrId) ? $eventOrId : $eventOrId->id;

        $this->info("--- {$phase} ---");

        // Conta i gig
        $gigCount = Gig::where('event_id', $eventId)->count();
        $this->line("Gig associati: {$gigCount}");

        // Conta le candidature
        $gigIds = Gig::where('event_id', $eventId)->pluck('id')->toArray();
        $applicationCount = 0;
        if (!empty($gigIds)) {
            $applicationCount = GigApplication::whereIn('gig_id', $gigIds)->count();
        }
        $this->line("Candidature ai gig: {$applicationCount}");

        // Conta le notifiche correlate all'evento
        $eventNotificationCount = Notification::where(function($query) use ($eventId) {
            $query->where('type', Notification::TYPE_EVENT_INVITATION)
                  ->whereJsonContains('data->event_id', $eventId)
                  ->orWhere('type', Notification::TYPE_NEW_REQUEST)
                  ->whereJsonContains('data->event_id', $eventId)
                  ->orWhere('type', Notification::TYPE_EVENT_UPDATE)
                  ->whereJsonContains('data->event_id', $eventId)
                  ->orWhere('type', Notification::TYPE_EVENT_CANCELLED)
                  ->whereJsonContains('data->event_id', $eventId)
                  ->orWhere('type', Notification::TYPE_EVENT_REMINDER)
                  ->whereJsonContains('data->event_id', $eventId)
                  ->orWhere('type', Notification::TYPE_INVITATION_ACCEPTED)
                  ->whereJsonContains('data->event_id', $eventId)
                  ->orWhere('type', Notification::TYPE_INVITATION_DECLINED)
                  ->whereJsonContains('data->event_id', $eventId)
                  ->orWhere('type', Notification::TYPE_REQUEST_ACCEPTED)
                  ->whereJsonContains('data->event_id', $eventId)
                  ->orWhere('type', Notification::TYPE_REQUEST_DECLINED)
                  ->whereJsonContains('data->event_id', $eventId)
                  ->orWhere('type', Notification::TYPE_REQUEST_CANCELLED)
                  ->whereJsonContains('data->event_id', $eventId);
        })->count();
        $this->line("Notifiche correlate all'evento: {$eventNotificationCount}");

        // Conta le notifiche correlate ai gig
        $gigNotificationCount = 0;
        if (!empty($gigIds)) {
            $gigNotificationCount = Notification::whereIn('type', [
                Notification::TYPE_GIG_APPLICATION,
                Notification::TYPE_GIG_APPLICATION_ACCEPTED,
                Notification::TYPE_GIG_APPLICATION_REJECTED,
                Notification::TYPE_GIG_APPLICATION_WITHDRAWN,
                Notification::TYPE_GIG_CLOSED,
                Notification::TYPE_GIG_REOPENED,
                Notification::TYPE_GIG_SHARED,
                Notification::TYPE_GIG_GLOBAL_MESSAGE
            ])->whereJsonContains('data->gig_id', $gigIds)->count();
        }
        $this->line("Notifiche correlate ai gig: {$gigNotificationCount}");

        $this->newLine();
    }
}
