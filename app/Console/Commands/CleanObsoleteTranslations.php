<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanObsoleteTranslations extends Command
{
    protected $signature = 'translations:clean-obsolete {--force : Esegui la pulizia senza conferma}';
    protected $description = 'Pulisce i groups di traduzione obsoleti dal database';

    // Groups che sono effettivamente usati nel codice Livewire
    private $usedGroups = [
        'auth',
        'common',
        'dashboard',
        'events',
        'forum',
        'gamification',
        'gigs',
        'home',
        'languages',
        'media',
        'photos',
        'poems',
        'profile',
        'videos',
        // Laravel defaults
        'pagination',
        'passwords',
        'validation',
    ];

    public function handle()
    {
        $this->info('🔍 Analisi traduzioni nel database...');

        // Ottieni tutti i groups dal database
        $dbGroups = DB::table('ltm_translations')
            ->distinct()
            ->pluck('group')
            ->sort()
            ->toArray();

        $this->info("📊 Groups nel database: " . count($dbGroups));
        $this->info("✅ Groups utilizzati: " . count($this->usedGroups));

        // Trova i groups obsoleti
        $obsoleteGroups = array_diff($dbGroups, $this->usedGroups);

        if (empty($obsoleteGroups)) {
            $this->info('✨ Nessun group obsoleto trovato!');
            return self::SUCCESS;
        }

        $this->warn("🗑️  Groups obsoleti trovati: " . count($obsoleteGroups));
        $this->newLine();

        // Mostra i groups obsoleti
        $this->table(
            ['Group', 'Chiavi'],
            collect($obsoleteGroups)->map(function ($group) {
                $count = DB::table('ltm_translations')
                    ->where('group', $group)
                    ->count();
                return [$group, $count];
            })->toArray()
        );

        // Conta totale chiavi da eliminare
        $totalKeys = DB::table('ltm_translations')
            ->whereIn('group', $obsoleteGroups)
            ->count();

        $this->newLine();
        $this->warn("⚠️  Verranno eliminate {$totalKeys} chiavi da " . count($obsoleteGroups) . " groups");

        // Conferma
        if (!$this->option('force')) {
            if (!$this->confirm('Vuoi procedere con l\'eliminazione?')) {
                $this->info('Operazione annullata.');
                return self::SUCCESS;
            }
        }

        // Elimina i groups obsoleti
        $this->info('🗑️  Eliminazione in corso...');
        
        $deleted = DB::table('ltm_translations')
            ->whereIn('group', $obsoleteGroups)
            ->delete();

        $this->info("✅ Eliminate {$deleted} chiavi obsolete!");
        $this->info('✨ Pulizia completata!');

        return self::SUCCESS;
    }
}

