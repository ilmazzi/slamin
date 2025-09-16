<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Services\TranslationApiService;

class TranslatePageCommand extends Command
{
    protected $signature = 'translations:translate-page
                            {language : Target language code (e.g. en, es, fr)}
                            {file : Translation file to translate (e.g. admin, common, auth)}
                            {--provider=libre : Translation provider (google, deepl, microsoft, libre)}
                            {--api-key= : API key for the translation service}
                            {--force : Force translation even if already translated}';

    protected $description = 'Translate a specific translation file using external API';

    public function handle()
    {
        $language = $this->argument('language');
        $file = $this->argument('file');
        $provider = $this->option('provider');
        $apiKey = $this->option('api-key');
        $force = $this->option('force');

        $this->info("🌍 Translating {$file}.php to {$language} using {$provider}...");

        if (!$apiKey && $provider !== 'libre') {
            $this->error("❌ API key required for {$provider}. Use --api-key option or set TRANSLATION_API_KEY in .env");
            return self::FAILURE;
        }

        $sourceFile = lang_path("it/{$file}.php");
        $targetFile = lang_path("{$language}/{$file}.php");

        if (!File::exists($sourceFile)) {
            $this->error("❌ Source file not found: {$sourceFile}");
            return self::FAILURE;
        }

        if (!File::exists(lang_path($language))) {
            File::makeDirectory(lang_path($language), 0755, true);
        }

        try {
            $translationService = new TranslationApiService($provider, $apiKey);

            // Test connection first
            $testResult = $translationService->testConnection();
            if (!$testResult['success']) {
                $this->error("❌ API connection failed: " . $testResult['message']);
                return self::FAILURE;
            }

            $this->info("✅ API connection successful");

            // Load Italian translations
            $italianTranslations = include $sourceFile;
            if (!is_array($italianTranslations)) {
                $this->error("❌ Invalid translation file format");
                return self::FAILURE;
            }

            // Load existing translations
            $existingTranslations = [];
            if (File::exists($targetFile) && !$force) {
                $existingTranslations = include $targetFile;
                if (!is_array($existingTranslations)) {
                    $existingTranslations = [];
                }
            }

            // Translate the array
            $this->info("🔄 Starting translation...");
            $translatedTranslations = $this->translateArray($italianTranslations, $existingTranslations, $translationService, $language, $force);

            // Save translations
            $this->saveTranslations($targetFile, $translatedTranslations);

            $keysTranslated = $this->countNewTranslations($italianTranslations, $existingTranslations, $translatedTranslations);

            $this->info("🎉 Translation completed!");
            $this->line("  ✅ File: {$file}.php");
            $this->line("  🌍 Language: {$language}");
            $this->line("  🔄 Keys translated: {$keysTranslated}");

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return self::FAILURE;
        }
    }

    private function translateArray($source, $existing, $translationService, $language, $force)
    {
        $result = $existing;

        foreach ($source as $key => $value) {
            if (is_array($value)) {
                $existingValue = $existing[$key] ?? [];
                $result[$key] = $this->translateArray($value, $existingValue, $translationService, $language, $force);
            } else {
                $shouldTranslate = $force ||
                                 !isset($existing[$key]) ||
                                 $existing[$key] === $value ||
                                 str_starts_with($existing[$key], "[{$language}]") ||
                                 str_starts_with($existing[$key], "[") ||
                                 $this->isItalianText($existing[$key]);

                if ($shouldTranslate) {
                    $this->line("    🔄 Translating: " . substr($value, 0, 50) . "...");
                    $result[$key] = $translationService->translate($value, $language, 'it');
                } else {
                    $result[$key] = $existing[$key];
                }
            }
        }

        return $result;
    }

    private function isItalianText($text)
    {
        $italianWords = [
            'Pannello', 'Amministrazione', 'Dashboard', 'Impostazioni', 'Traduzioni',
            'Caroselli', 'Utenti', 'Permessi', 'Gestione', 'Lingue', 'Disponibili',
            'File', 'Aggiungi', 'Lingua', 'Chiave', 'Modifica', 'Elimina', 'Codice',
            'Nome', 'Crea', 'Successo', 'Errore', 'Trovata', 'Eliminata', 'Esiste'
        ];

        foreach ($italianWords as $word) {
            if (str_contains($text, $word)) {
                return true;
            }
        }

        return false;
    }

    private function countNewTranslations($source, $existing, $translated)
    {
        $count = 0;
        foreach ($source as $key => $value) {
            if (is_array($value)) {
                $count += $this->countNewTranslations($value, $existing[$key] ?? [], $translated[$key] ?? []);
            } elseif (isset($translated[$key]) && $translated[$key] !== ($existing[$key] ?? $value)) {
                $count++;
            }
        }
        return $count;
    }

    private function saveTranslations($filePath, $translations)
    {
        $directory = dirname($filePath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Pulisci le traduzioni per evitare problemi di encoding
        $translations = $this->cleanTranslations($translations);

        $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";

        // Assicurati che il contenuto sia UTF-8 valido
        if (!mb_check_encoding($content, 'UTF-8')) {
            $content = mb_convert_encoding($content, 'UTF-8', 'auto');
        }

        file_put_contents($filePath, $content, LOCK_EX);
    }

    /**
     * Pulisce le traduzioni per evitare problemi di encoding
     */
    private function cleanTranslations($translations)
    {
        if (is_array($translations)) {
            $cleaned = [];
            foreach ($translations as $key => $value) {
                if (is_array($value)) {
                    $cleaned[$key] = $this->cleanTranslations($value);
                } else {
                    $cleaned[$key] = $this->cleanText($value);
                }
            }
            return $cleaned;
        }

        return $this->cleanText($translations);
    }

    /**
     * Pulisce un singolo testo per evitare problemi di encoding
     */
    private function cleanText($text)
    {
        if (!is_string($text)) {
            return $text;
        }

        // Rimuovi caratteri di controllo e normalizza
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);

        // Normalizza gli apostrofi e le virgolette
        $text = str_replace(["\u2018", "\u2019", "\u201C", "\u201D"], ["'", "'", '"', '"'], $text);

        // Assicurati che il testo sia UTF-8 valido
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'auto');
        }

        // Rimuovi BOM se presente
        $text = preg_replace('/^\xEF\xBB\xBF/', '', $text);

        return trim($text);
    }
}
