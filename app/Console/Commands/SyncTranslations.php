<?php

namespace App\Console\Commands;

use App\Helpers\TranslationHelper;
use App\Helpers\AutoTranslationHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SyncTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:sync
                            {--from-file : Sincronizza da file a database}
                            {--to-file : Sincronizza da database a file}
                            {--group= : Gruppo specifico da sincronizzare}
                            {--locale= : Locale specifico da sincronizzare}
                            {--all : Sincronizza tutti i gruppi e locale}
                            {--clean : Pulisce la cache dopo la sincronizzazione}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizza le traduzioni tra file e database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Avvio sincronizzazione traduzioni...');

        $fromFile = $this->option('from-file');
        $toFile = $this->option('to-file');
        $group = $this->option('group');
        $locale = $this->option('locale');
        $all = $this->option('all');
        $clean = $this->option('clean');

        // Se non specificato, sincronizza da file a database
        if (!$fromFile && !$toFile) {
            $fromFile = true;
        }

        try {
            if ($fromFile) {
                $this->syncFromFile($group, $locale, $all);
            }

            if ($toFile) {
                $this->syncToFile($group, $locale, $all);
            }

            if ($clean) {
                $this->clearCache();
            }

            $this->info('✅ Sincronizzazione completata con successo!');
        } catch (\Exception $e) {
            $this->error('❌ Errore durante la sincronizzazione: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Sincronizza da file a database
     */
    private function syncFromFile($group = null, $locale = null, $all = false)
    {
        $this->info('📁 Sincronizzazione da file a database...');

        if ($all) {
            $this->syncAllFromFile();
        } else {
            $this->syncSpecificFromFile($group, $locale);
        }
    }

    /**
     * Sincronizza tutti i file
     */
    private function syncAllFromFile()
    {
        $langPath = lang_path();

        if (!File::exists($langPath)) {
            $this->warn('⚠️  Directory lang non trovata: ' . $langPath);
            return;
        }

        $locales = File::directories($langPath);
        $totalSynced = 0;

        foreach ($locales as $localePath) {
            $locale = basename($localePath);
            $this->info("🌍 Elaborazione locale: {$locale}");

            $files = File::files($localePath);

            foreach ($files as $file) {
                $group = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $this->info("  📄 Elaborazione gruppo: {$group}");

                try {
                    $count = TranslationHelper::syncFromFile($group, $locale);
                    $totalSynced += $count;
                    $this->line("    ✅ {$count} traduzioni sincronizzate");
                } catch (\Exception $e) {
                    $this->error("    ❌ Errore: " . $e->getMessage());
                }
            }
        }

        $this->info("📊 Totale sincronizzate: {$totalSynced}");
    }

    /**
     * Sincronizza un gruppo/locale specifico
     */
    private function syncSpecificFromFile($group, $locale)
    {
        if (!$group || !$locale) {
            $this->error('❌ Specificare sia --group che --locale per la sincronizzazione specifica');
            return;
        }

        $this->info("📄 Sincronizzazione {$group} ({$locale})...");

        try {
            $count = TranslationHelper::syncFromFile($group, $locale);
            $this->info("✅ {$count} traduzioni sincronizzate");
        } catch (\Exception $e) {
            $this->error("❌ Errore: " . $e->getMessage());
        }
    }

    /**
     * Sincronizza da database a file
     */
    private function syncToFile($group = null, $locale = null, $all = false)
    {
        $this->info('💾 Sincronizzazione da database a file...');

        if ($all) {
            $this->syncAllToFile();
        } else {
            $this->syncSpecificToFile($group, $locale);
        }
    }

    /**
     * Sincronizza tutti i gruppi a file
     */
    private function syncAllToFile()
    {
        $groups = \App\Models\Translation::distinct('group_name')->pluck('group_name');
        $locales = \App\Models\Translation::distinct('locale')->pluck('locale');
        $totalSynced = 0;

        foreach ($locales as $locale) {
            $this->info("🌍 Elaborazione locale: {$locale}");

            foreach ($groups as $group) {
                $this->info("  📄 Elaborazione gruppo: {$group}");

                try {
                    $result = TranslationHelper::syncToFile($group, $locale);
                    if ($result) {
                        $totalSynced++;
                        $this->line("    ✅ File sincronizzato");
                    } else {
                        $this->warn("    ⚠️  Nessuna traduzione trovata");
                    }
                } catch (\Exception $e) {
                    $this->error("    ❌ Errore: " . $e->getMessage());
                }
            }
        }

        $this->info("📊 Totale file sincronizzati: {$totalSynced}");
    }

    /**
     * Sincronizza un gruppo/locale specifico a file
     */
    private function syncSpecificToFile($group, $locale)
    {
        if (!$group || !$locale) {
            $this->error('❌ Specificare sia --group che --locale per la sincronizzazione specifica');
            return;
        }

        $this->info("📄 Sincronizzazione {$group} ({$locale}) a file...");

        try {
            $result = TranslationHelper::syncToFile($group, $locale);
            if ($result) {
                $this->info("✅ File sincronizzato con successo");
            } else {
                $this->warn("⚠️  Nessuna traduzione trovata");
            }
        } catch (\Exception $e) {
            $this->error("❌ Errore: " . $e->getMessage());
        }
    }

    /**
     * Pulisce la cache
     */
    private function clearCache()
    {
        $this->info('🧹 Pulizia cache...');

        try {
            TranslationHelper::clearAllCache();
            $this->info('✅ Cache pulita');
        } catch (\Exception $e) {
            $this->error("❌ Errore nella pulizia cache: " . $e->getMessage());
        }
    }
}
