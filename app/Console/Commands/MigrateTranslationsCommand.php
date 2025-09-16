<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class MigrateTranslationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'translations:migrate
                            {--backup : Crea un backup dei file esistenti}
                            {--force : Forza la migrazione anche se esistono già traduzioni}';

    /**
     * The console command description.
     */
    protected $description = 'Migra le traduzioni dal sistema complesso a quello semplificato';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Inizio migrazione traduzioni...');

        // Verifica se esiste la tabella translations
        if (!DB::getSchemaBuilder()->hasTable('translations')) {
            $this->warn('⚠️  La tabella translations non esiste. Niente da migrare.');
            return self::SUCCESS;
        }

        // Crea backup se richiesto
        if ($this->option('backup')) {
            $this->createBackup();
        }

        // Verifica se esistono già traduzioni
        if (!$this->option('force') && $this->hasExistingTranslations()) {
            $this->error('❌ Esistono già traduzioni nel sistema. Usa --force per forzare la migrazione.');
            return self::FAILURE;
        }

        // Migra le traduzioni
        $this->migrateTranslations();

        $this->info('✅ Migrazione completata con successo!');
        return self::SUCCESS;
    }

    /**
     * Crea un backup dei file di traduzione esistenti
     */
    private function createBackup()
    {
        $this->info('📦 Creazione backup...');

        $backupDir = storage_path('backups/translations/' . now()->format('Y-m-d_H-i-s'));
        File::makeDirectory($backupDir, 0755, true);

        $langPath = lang_path();
        if (File::exists($langPath)) {
            File::copyDirectory($langPath, $backupDir . '/lang');
            $this->info("✅ Backup creato in: {$backupDir}");
        }
    }

    /**
     * Verifica se esistono già traduzioni
     */
    private function hasExistingTranslations()
    {
        $langPath = lang_path();
        if (!File::exists($langPath)) {
            return false;
        }

        $directories = File::directories($langPath);
        return count($directories) > 0;
    }

    /**
     * Migra le traduzioni dal database ai file
     */
    private function migrateTranslations()
    {
        $this->info('🔄 Migrazione traduzioni dal database...');

        // Ottieni tutte le traduzioni dal database
        $translations = DB::table('translations')
            ->select('key', 'locale', 'value')
            ->orderBy('locale')
            ->orderBy('key')
            ->get();

        if ($translations->isEmpty()) {
            $this->warn('⚠️  Nessuna traduzione trovata nel database.');
            return;
        }

        // Raggruppa per locale
        $translationsByLocale = $translations->groupBy('locale');

        $this->info("📊 Trovate traduzioni per " . count($translationsByLocale) . " lingue:");
        foreach ($translationsByLocale as $locale => $localeTranslations) {
            $this->line("  - {$locale}: " . count($localeTranslations) . " traduzioni");
        }

        // Crea le directory e i file per ogni lingua
        foreach ($translationsByLocale as $locale => $localeTranslations) {
            $this->migrateLocale($locale, $localeTranslations);
        }

        $this->info('✅ Migrazione completata!');
    }

    /**
     * Migra le traduzioni per una singola lingua
     */
    private function migrateLocale($locale, $translations)
    {
        $this->info("🌍 Migrazione lingua: {$locale}");

        // Crea la directory della lingua
        $localePath = lang_path($locale);
        if (!File::exists($localePath)) {
            File::makeDirectory($localePath, 0755, true);
        }

        // Raggruppa per file (basato sulla chiave)
        $translationsByFile = $this->groupTranslationsByFile($translations);

        foreach ($translationsByFile as $file => $fileTranslations) {
            $this->migrateFile($locale, $file, $fileTranslations);
        }
    }

    /**
     * Raggruppa le traduzioni per file basandosi sulla chiave
     */
    private function groupTranslationsByFile($translations)
    {
        $grouped = [];

        foreach ($translations as $translation) {
            $key = $translation->key;
            $value = $translation->value;

            // Determina il file basandosi sulla chiave
            $file = $this->determineFileFromKey($key);

            if (!isset($grouped[$file])) {
                $grouped[$file] = [];
            }

            // Rimuovi il prefisso del file dalla chiave
            $cleanKey = $this->removeFilePrefixFromKey($key, $file);
            $grouped[$file][$cleanKey] = $value;
        }

        return $grouped;
    }

    /**
     * Determina il file basandosi sulla chiave
     */
    private function determineFileFromKey($key)
    {
        $parts = explode('.', $key);
        $file = $parts[0];

        // Mappa dei file comuni
        $fileMap = [
            'auth' => 'auth',
            'validation' => 'validation',
            'admin' => 'admin',
            'common' => 'common',
            'dashboard' => 'dashboard',
            'events' => 'events',
            'videos' => 'videos',
            'carousel' => 'carousel',
            'home' => 'home',
            'poems' => 'poems',
            'profile' => 'profile',
            'register' => 'register',
            'login' => 'login',
            'notifications' => 'notifications',
            'permissions' => 'permissions',
            'premium' => 'premium',
            'sidebar' => 'sidebar',
            'wishlist' => 'wishlist',
            'invitations' => 'invitations',
        ];

        return $fileMap[$file] ?? 'common';
    }

    /**
     * Rimuove il prefisso del file dalla chiave
     */
    private function removeFilePrefixFromKey($key, $file)
    {
        $prefix = $file . '.';
        if (str_starts_with($key, $prefix)) {
            return substr($key, strlen($prefix));
        }
        return $key;
    }

    /**
     * Migra le traduzioni per un singolo file
     */
    private function migrateFile($locale, $file, $translations)
    {
        $filePath = lang_path($locale . '/' . $file . '.php');

        // Genera il contenuto PHP
        $content = "<?php\n\nreturn [\n";

        foreach ($translations as $key => $value) {
            $escapedKey = addslashes($key);
            $escapedValue = addslashes($value);
            $content .= "    '{$escapedKey}' => '{$escapedValue}',\n";
        }

        $content .= "\n];\n";

        // Salva il file
        File::put($filePath, $content);

        $this->line("  ✅ File {$file}.php: " . count($translations) . " traduzioni");
    }
}
