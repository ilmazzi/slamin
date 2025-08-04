<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Carbon\Carbon;

class AnalyzeEvents extends Command
{
    protected $signature = 'events:analyze';
    protected $description = 'Analizza gli eventi nel database per capire perché non sono visibili';

    public function handle()
    {
        $this->info('=== ANALISI EVENTI NEL DATABASE ===');
        $this->newLine();

        $events = Event::all();

        if ($events->isEmpty()) {
            $this->info('✅ Nessun evento trovato nel database!');
            return 0;
        }

        $this->info("Trovati {$events->count()} eventi nel database:");
        $this->newLine();

        foreach ($events as $event) {
            $this->line("📅 Evento ID: {$event->id}");
            $this->line("   Titolo: {$event->title}");
            $this->line("   Status: {$event->status}");
            $this->line("   Pubblico: " . ($event->is_public ? 'Sì' : 'No'));
            $this->line("   Data inizio: {$event->start_datetime}");
            $this->line("   Organizzatore ID: {$event->organizer_id}");

            // Verifica se l'evento dovrebbe essere visibile
            $this->analyzeVisibility($event);

            $this->newLine();
        }

        return 0;
    }

    private function analyzeVisibility(Event $event): void
    {
        $this->line("   🔍 ANALISI VISIBILITÀ:");

        // Controllo 1: Status published
        if ($event->status !== 'published') {
            $this->warn("   ❌ Status non 'published': {$event->status}");
        } else {
            $this->line("   ✅ Status: published");
        }

        // Controllo 2: Data futura (upcoming)
        $now = Carbon::now();
        if ($event->start_datetime < $now) {
            $this->warn("   ❌ Evento passato: {$event->start_datetime} < {$now}");
        } else {
            $this->line("   ✅ Evento futuro: {$event->start_datetime}");
        }

        // Controllo 3: Evento pubblico o privato con accesso
        if (!$event->is_public) {
            $this->warn("   ❌ Evento privato (is_public = false)");
        } else {
            $this->line("   ✅ Evento pubblico");
        }

        // Controllo 4: Organizzatore esistente
        if (!$event->organizer) {
            $this->warn("   ❌ Organizzatore non trovato (ID: {$event->organizer_id})");
        } else {
            $this->line("   ✅ Organizzatore: {$event->organizer->name}");
        }

        // Controllo 5: Moderation status
        if ($event->moderation_status && $event->moderation_status !== 'approved') {
            $this->warn("   ❌ Status moderazione: {$event->moderation_status}");
        } else {
            $this->line("   ✅ Moderazione: OK");
        }
    }
}
