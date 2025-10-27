<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearTranslationManagerDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translation-manager:clear-db {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all data from ltm_translations table (Barryvdh Translation Manager)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('Sei sicuro di voler cancellare TUTTE le traduzioni dal database?')) {
                $this->info('Operazione annullata.');
                return 0;
            }
        }

        $this->info('🗑️  Pulizia tabella ltm_translations in corso...');
        
        try {
            $count = \DB::table('ltm_translations')->count();
            \DB::table('ltm_translations')->truncate();
            
            $this->info("✅ Tabella pulita con successo!");
            $this->info("📊 Records eliminati: {$count}");
            $this->info("📊 Records attuali: " . \DB::table('ltm_translations')->count());
            
            $this->newLine();
            $this->warn('💡 Ricorda: Ora devi importare le traduzioni dai file PHP!');
            $this->info('   Vai su: /admin/translation-manager');
            $this->info('   Click: "Importa gruppi"');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Errore durante la pulizia: ' . $e->getMessage());
            return 1;
        }
    }
}
