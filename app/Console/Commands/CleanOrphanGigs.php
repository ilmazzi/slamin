<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gig;
use App\Models\GigApplication;
use App\Models\Notification;

class CleanOrphanGigs extends Command
{
    protected $signature = 'gigs:clean-orphans {--force : Forza l\'eliminazione senza conferma}';
    protected $description = 'Pulisce i gig orfani (senza evento associato) e le relative dipendenze';

    public function handle()
    {
        $this->info('=== PULIZIA GIG ORFANI ===');
        $orphanGigs = Gig::whereDoesntHave('event')->get();
        if ($orphanGigs->isEmpty()) {
            $this->info('✅ Nessun gig orfano trovato!');
            return 0;
        }
        $this->warn("Trovati {$orphanGigs->count()} gig orfani:");
        foreach ($orphanGigs as $gig) {
            $this->line("- {$gig->title} (ID: {$gig->id}, Event ID: {$gig->event_id})");
        }
        $gigIds = $orphanGigs->pluck('id')->toArray();
        $applicationCount = GigApplication::whereIn('gig_id', $gigIds)->count();
        $notificationCount = Notification::whereIn('type', [
            Notification::TYPE_GIG_APPLICATION,
            Notification::TYPE_GIG_APPLICATION_ACCEPTED,
            Notification::TYPE_GIG_APPLICATION_REJECTED,
            Notification::TYPE_GIG_APPLICATION_WITHDRAWN,
            Notification::TYPE_GIG_CLOSED,
            Notification::TYPE_GIG_REOPENED,
            Notification::TYPE_GIG_SHARED,
            Notification::TYPE_GIG_GLOBAL_MESSAGE
        ])->whereJsonContains('data->gig_id', $gigIds)->count();
        $this->info("Dipendenze da eliminare:");
        $this->line("- Candidature: {$applicationCount}");
        $this->line("- Notifiche: {$notificationCount}");
        if (!$this->option('force') && !$this->confirm('Vuoi procedere con l\'eliminazione?')) {
            $this->info('Operazione annullata.');
            return 0;
        }
        Notification::whereIn('type', [
            Notification::TYPE_GIG_APPLICATION,
            Notification::TYPE_GIG_APPLICATION_ACCEPTED,
            Notification::TYPE_GIG_APPLICATION_REJECTED,
            Notification::TYPE_GIG_APPLICATION_WITHDRAWN,
            Notification::TYPE_GIG_CLOSED,
            Notification::TYPE_GIG_REOPENED,
            Notification::TYPE_GIG_SHARED,
            Notification::TYPE_GIG_GLOBAL_MESSAGE
        ])->whereJsonContains('data->gig_id', $gigIds)->delete();
        GigApplication::whereIn('gig_id', $gigIds)->delete();
        $deletedCount = Gig::whereIn('id', $gigIds)->delete();
        $this->info("✅ Eliminati {$deletedCount} gig orfani e relative dipendenze!");
        return 0;
    }
}
