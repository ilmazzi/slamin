<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Services\TranslationApiService;

class TranslateWithApiCommand extends Command
{
    protected $signature = 'translations:api-translate
                            {language : Target language code (e.g. en, es, fr)}
                            {--provider=google : Translation provider (google, deepl, microsoft, libre)}
                            {--api-key= : API key for the translation service}
                            {--file= : Specific file to translate (optional)}
                            {--force : Force translation even if already translated}
                            {--test : Test API connection only}';

    protected $description = 'Translate files using external API services';

    public function handle()
    {
        $language = $this->argument('language');
        $provider = $this->option('provider');
        $apiKey = $this->option('api-key');
        $file = $this->option('file');
        $force = $this->option('force');
        $test = $this->option('test');

        // Test API connection first
        $translationService = new TranslationApiService($provider, $apiKey);

        if ($test) {
            $this->info("🧪 Testing {$provider} API connection...");
            $result = $translationService->testConnection();

            if ($result['success']) {
                $this->info("✅ {$result['message']}");
            } else {
                $this->error("❌ {$result['message']}");
                return self::FAILURE;
            }
            return self::SUCCESS;
        }

        $this->info("🌍 Starting API translation to {$language} using {$provider}...");

        if (!$apiKey && $provider !== 'libre') {
            $this->error("❌ API key required for {$provider}. Use --api-key option or set TRANSLATION_API_KEY in .env");
            return self::FAILURE;
        }

        $sourcePath = lang_path('it');
        $targetPath = lang_path($language);

        if (!File::exists($sourcePath)) {
            $this->error("❌ Source language directory not found: {$sourcePath}");
            return self::FAILURE;
        }

        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        $files = $file ? [new \SplFileInfo($sourcePath . '/' . $file . '.php')] : File::allFiles($sourcePath);
        $filesProcessed = 0;
        $keysTranslated = 0;
        $errors = 0;

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') continue;

            $filename = $file->getFilename();
            $relativePath = $file instanceof \SplFileInfo ? $file->getFilename() : $file->getRelativePathname();
            $targetFile = $targetPath . '/' . $relativePath;

            $this->line("📝 Processing {$relativePath}...");

            try {
                // Load Italian translations
                $italianTranslations = include $file->getPathname();
                if (!is_array($italianTranslations)) continue;

                // Load existing translations
                $existingTranslations = [];
                if (File::exists($targetFile) && !$force) {
                    $existingTranslations = include $targetFile;
                    if (!is_array($existingTranslations)) {
                        $existingTranslations = [];
                    }
                }

                // Translate the array
                $translatedTranslations = $this->translateArray($italianTranslations, $existingTranslations, $translationService, $language, $force);

                // Save if there are changes
                if ($translatedTranslations !== $existingTranslations) {
                    $this->saveTranslations($targetFile, $translatedTranslations);
                    $filesProcessed++;
                    $keysTranslated += $this->countNewTranslations($italianTranslations, $existingTranslations, $translatedTranslations);
                }

            } catch (\Exception $e) {
                $this->error("  ❌ Error processing {$relativePath}: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("🎉 Translation completed!");
        $this->line("  ✅ {$filesProcessed} files processed");
        $this->line("  🔄 {$keysTranslated} keys translated");
        if ($errors > 0) {
            $this->line("  ❌ {$errors} errors occurred");
        }

        return self::SUCCESS;
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
        // Simple Italian text detection
        $italianWords = [
            'Pannello', 'Amministrazione', 'Dashboard', 'Impostazioni', 'Traduzioni',
            'Caroselli', 'Utenti', 'Permessi', 'Gestione', 'Lingue', 'Disponibili',
            'File', 'Aggiungi', 'Lingua', 'Chiave', 'Modifica', 'Elimina', 'Codice',
            'Nome', 'Crea', 'Successo', 'Errore', 'Trovata', 'Eliminata', 'Esiste',
            'Riferimento', 'Italiano', 'Inserisci', 'Salva', 'Mostra', 'Nascondi',
            'Tutte', 'Copia', 'Svuota', 'Torna', 'Lista', 'Annulla', 'Statistiche'
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

        $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";
        file_put_contents($filePath, $content);
    }
}
