<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\Gig;
use App\Models\Notification;
use App\Models\GigApplication;
use Illuminate\Support\Facades\Log;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        // Sincronizza le posizioni d'ingaggio con i gig
        $this->syncGigPositions($event);
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        // Se le posizioni d'ingaggio sono cambiate, sincronizza
        if ($event->wasChanged('gig_positions')) {
            $this->syncGigPositions($event);
        }
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        try {
            // Elimina tutte le notifiche correlate all'evento
            $this->deleteEventNotifications($event);

            // Elimina tutte le candidature ai gig dell'evento
            $this->deleteGigApplications($event);

            // Elimina tutti i gig associati all'evento
            $event->gigs()->delete();

            Log::info('Evento eliminato con successo', [
                'event_id' => $event->id,
                'event_title' => $event->title
            ]);

        } catch (\Exception $e) {
            Log::error('Errore durante l\'eliminazione dell\'evento', [
                'event_id' => $event->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Sincronizza le posizioni d'ingaggio dell'evento con i gig
     */
    public function syncGigPositions(Event $event): void
    {
        try {
            $gigPositions = $event->gig_positions ?? [];

            // Se è una stringa JSON, decodificala
            if (is_string($gigPositions)) {
                $gigPositions = json_decode($gigPositions, true) ?? [];
            }

            if (empty($gigPositions)) {
                // Se non ci sono posizioni, elimina tutti i gig esistenti
                $event->gigs()->delete();
                return;
            }

            // Ottieni i gig esistenti per questo evento
            $existingGigs = $event->gigs()->get()->keyBy('id');
            $processedGigIds = [];

            foreach ($gigPositions as $positionIndex => $position) {
                // Crea un gig per ogni posizione
                $gigData = $this->prepareGigData($event, $position, $positionIndex);

                // Cerca se esiste già un gig per questa posizione
                $existingGig = $existingGigs->where('title', $gigData['title'])->first();

                if ($existingGig) {
                    // Aggiorna il gig esistente
                    $existingGig->update($gigData);
                    $processedGigIds[] = $existingGig->id;
                } else {
                    // Crea un nuovo gig
                    $gig = $event->gigs()->create($gigData);
                    $processedGigIds[] = $gig->id;
                }
            }

            // Elimina i gig che non sono più nelle posizioni
            $event->gigs()->whereNotIn('id', $processedGigIds)->delete();

        } catch (\Exception $e) {
            Log::error('Errore durante la sincronizzazione delle posizioni d\'ingaggio', [
                'event_id' => $event->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Prepara i dati per creare/aggiornare un gig
     */
    private function prepareGigData(Event $event, array $position, int $positionIndex): array
    {
        $positionType = $position['type'] ?? 'poetry_slam';
        $title = $event->title . " - " . $this->getPositionTypeDisplayName($positionType);

        return [
            'title' => $title,
            'description' => $position['description'] ?? "Posizione per l'evento {$event->title}",
            'requirements' => $position['requirements'] ?? null,
            'compensation' => $position['cachet_amount'] ?? null,
            'deadline' => $event->invitation_deadline ?? $event->registration_deadline ?? $event->start_datetime,
            'event_id' => $event->id,
            'group_id' => $event->group_id,
            'user_id' => $event->organizer_id,
            'category' => $position['type'] ?? 'poetry_slam',
            'type' => $this->mapPositionTypeToGigType($position['type'] ?? 'poetry_slam'),
            'language' => $position['language'] ?? 'italian',
            'location' => $event->city ?? null,
            'is_remote' => $event->is_online ?? false,
            'is_urgent' => false,
            'is_featured' => false,
            'is_closed' => false,
            'max_applications' => $position['quantity'] ?? 1,
            'allow_group_admin_edit' => $event->group_permissions === 'group_admins',
            'application_count' => 0,
            'accepted_applications_count' => 0,
        ];
    }

    /**
     * Ottiene il nome visualizzato del tipo di posizione
     */
    private function getPositionTypeDisplayName(string $positionType): string
    {
        $mapping = [
            'poetry_slam' => 'Poetry Slam',
            'poeta' => 'Poeta',
            'mc' => 'MC',
            'technical' => 'Supporto Tecnico',
            'volunteer' => 'Volontario',
        ];

        return $mapping[$positionType] ?? 'Artista';
    }

    /**
     * Mappa il tipo di posizione al tipo di gig
     */
    private function mapPositionTypeToGigType(string $positionType): string
    {
        $mapping = [
            'poetry_slam' => 'artist_poet',
            'poeta' => 'artist_poet',
            'mc' => 'mc_guest',
            'technical' => 'technical_support',
            'volunteer' => 'volunteer',
        ];

        return $mapping[$positionType] ?? 'artist_poet';
    }

    /**
     * Elimina tutte le notifiche correlate all'evento
     */
    private function deleteEventNotifications(Event $event): void
    {
        // Elimina notifiche di inviti all'evento
        Notification::where('type', Notification::TYPE_EVENT_INVITATION)
            ->whereJsonContains('data->event_id', $event->id)
            ->delete();

        // Elimina notifiche di richieste all'evento
        Notification::where('type', Notification::TYPE_NEW_REQUEST)
            ->whereJsonContains('data->event_id', $event->id)
            ->delete();

        // Elimina notifiche di aggiornamenti dell'evento
        Notification::where('type', Notification::TYPE_EVENT_UPDATE)
            ->whereJsonContains('data->event_id', $event->id)
            ->delete();

        // Elimina notifiche di cancellazione dell'evento
        Notification::where('type', Notification::TYPE_EVENT_CANCELLED)
            ->whereJsonContains('data->event_id', $event->id)
            ->delete();

        // Elimina notifiche di promemoria dell'evento
        Notification::where('type', Notification::TYPE_EVENT_REMINDER)
            ->whereJsonContains('data->event_id', $event->id)
            ->delete();

        // Elimina notifiche di risposta agli inviti
        Notification::whereIn('type', [
            Notification::TYPE_INVITATION_ACCEPTED,
            Notification::TYPE_INVITATION_DECLINED
        ])->whereJsonContains('data->event_id', $event->id)
            ->delete();

        // Elimina notifiche di risposta alle richieste
        Notification::whereIn('type', [
            Notification::TYPE_REQUEST_ACCEPTED,
            Notification::TYPE_REQUEST_DECLINED,
            Notification::TYPE_REQUEST_CANCELLED
        ])->whereJsonContains('data->event_id', $event->id)
            ->delete();

        // Elimina notifiche correlate ai gig dell'evento
        $gigIds = $event->gigs()->pluck('id')->toArray();
        if (!empty($gigIds)) {
            Notification::whereIn('type', [
                Notification::TYPE_GIG_APPLICATION,
                Notification::TYPE_GIG_APPLICATION_ACCEPTED,
                Notification::TYPE_GIG_APPLICATION_REJECTED,
                Notification::TYPE_GIG_APPLICATION_WITHDRAWN,
                Notification::TYPE_GIG_CLOSED,
                Notification::TYPE_GIG_REOPENED,
                Notification::TYPE_GIG_SHARED,
                Notification::TYPE_GIG_GLOBAL_MESSAGE
            ])->whereJsonContains('data->gig_id', $gigIds)
                ->delete();
        }
    }

    /**
     * Elimina tutte le candidature ai gig dell'evento
     */
    private function deleteGigApplications(Event $event): void
    {
        // Elimina tutte le candidature ai gig dell'evento
        $gigIds = $event->gigs()->pluck('id')->toArray();
        if (!empty($gigIds)) {
            GigApplication::whereIn('gig_id', $gigIds)->delete();
        }
    }
}
