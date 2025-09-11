<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupActivities extends Command
{
    protected $signature = 'db:cleanup-activities {--force} {--dry-run}';
    protected $description = 'Elimina TUTTE le attività e i log attività mantenendo gli utenti';

    public function handle()
    {
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 MODALITÀ DRY-RUN: Nessuna eliminazione verrà eseguita');
        }

        $this->line('');
        $this->line('📊 PULIZIA ATTIVITÀ E LOG');
        $this->line('');

        // Mostra statistiche attuali
        $this->showCurrentStats();

        if (!$force && !$dryRun) {
            if (!$this->confirm('⚠️  ATTENZIONE: Eliminerà TUTTE le attività e i log attività. Continuare?')) {
                $this->info('Operazione annullata.');
                return;
            }
        }

        $this->line('');
        $this->line('🚀 Inizio pulizia attività...');
        $this->line('');

        // Disabilita foreign key checks
        if (!$dryRun) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $this->line('🔓 Foreign key checks disabilitati');
        }

        // Pulisci attività e log
        $this->cleanupActivitiesAndLogs($dryRun);

        // Riabilita foreign key checks
        if (!$dryRun) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->line('🔒 Foreign key checks riabilitati');
        }

        $this->line('');
        $this->line('✅ Pulizia attività completata!');
        $this->line('');
        $this->showFinalStats();
    }

    private function showCurrentStats()
    {
        $this->line('📊 STATISTICHE ATUALI ATTIVITÀ:');
        $this->line("   📊 Attività: " . DB::table('activities')->count());
        $this->line("   📊 Log attività: " . DB::table('activity_logs')->count());
        $this->line("   👤 Utenti: " . DB::table('users')->count() . ' (MANTENUTI)');
        $this->line('');
    }

    private function showFinalStats()
    {
        $this->line('📊 STATISTICHE FINALI ATTIVITÀ:');
        $this->line("   📊 Attività: " . DB::table('activities')->count());
        $this->line("   📊 Log attività: " . DB::table('activity_logs')->count());
        $this->line("   👤 Utenti: " . DB::table('users')->count() . ' (MANTENUTI)');
        $this->line('');
    }

    private function cleanupActivitiesAndLogs($dryRun)
    {
        $this->line('🗑️  Eliminazione attività e log...');

        // Lista delle tabelle da pulire
        $tablesToClean = [
            'activities',      // Attività utenti
            'activity_logs',   // Log attività
        ];

        foreach ($tablesToClean as $table) {
            try {
                $count = DB::table($table)->count();
                $this->line("   Trovati {$count} record in {$table}");
                
                if (!$dryRun && $count > 0) {
                    // Elimina TUTTI i record dalla tabella
                    DB::statement("DELETE FROM {$table}");
                    $this->line("   ✅ {$count} record eliminati da {$table}");
                } elseif ($dryRun) {
                    $this->line("   🔍 DRY-RUN: Eliminerebbe {$count} record da {$table}");
                }
            } catch (\Exception $e) {
                $this->error("   ❌ Errore eliminazione {$table}: " . $e->getMessage());
            }
        }
    }
}
