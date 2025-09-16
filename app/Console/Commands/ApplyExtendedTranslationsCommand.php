<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Helpers\ExtendedTranslationDictionary;

class ApplyExtendedTranslationsCommand extends Command
{
    protected $signature = 'translations:apply-extended';
    protected $description = 'Applica le traduzioni estese ai file di traduzione';

    public function handle()
    {
        $this->info('🔧 Applicazione traduzioni estese...');

        $translations = ExtendedTranslationDictionary::getTranslations();
        $languages = array_keys($translations);

        foreach ($languages as $language) {
            $this->info("📝 Applicazione traduzioni per {$language}...");
            $this->applyTranslationsToLanguage($language, $translations[$language]);
        }

        $this->info('🎉 Applicazione completata!');
        return self::SUCCESS;
    }

    private function applyTranslationsToLanguage($language, $translations)
    {
        $langPath = lang_path($language);

        if (!File::exists($langPath)) {
            $this->error("  ❌ Directory {$language} non trovata");
            return;
        }

        $files = File::allFiles($langPath);
        $filesProcessed = 0;
        $keysUpdated = 0;

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') continue;

            $filePath = $file->getPathname();
            $relativePath = $file->getRelativePathname();

            try {
                $fileTranslations = include $filePath;
                if (!is_array($fileTranslations)) continue;

                $updated = $this->updateTranslationsInArray($fileTranslations, $translations);

                if ($updated) {
                    $this->saveTranslations($filePath, $fileTranslations);
                    $filesProcessed++;
                    $keysUpdated += $this->countUpdatedKeys($fileTranslations, $translations);
                }
            } catch (\Exception $e) {
                $this->error("  ❌ Errore nel file {$relativePath}: " . $e->getMessage());
            }
        }

        $this->line("  ✅ {$filesProcessed} file processati, {$keysUpdated} chiavi aggiornate");
    }

    private function updateTranslationsInArray(&$array, $translations)
    {
        $updated = false;

        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                if ($this->updateTranslationsInArray($value, $translations)) {
                    $updated = true;
                }
            } elseif (is_string($value)) {
                // Rimuovi placeholder [lang] se presente
                $cleanValue = preg_replace('/^\[[a-z]{2}\]\s*/', '', $value);

                // Cerca traduzione nel dizionario
                if (isset($translations[$cleanValue])) {
                    $value = $translations[$cleanValue];
                    $updated = true;
                }
            }
        }

        return $updated;
    }

    private function countUpdatedKeys($array, $translations)
    {
        $count = 0;

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $count += $this->countUpdatedKeys($value, $translations);
            } elseif (is_string($value)) {
                $cleanValue = preg_replace('/^\[[a-z]{2}\]\s*/', '', $value);
                if (isset($translations[$cleanValue])) {
                    $count++;
                }
            }
        }

        return $count;
    }

    private function saveTranslations($filePath, $translations)
    {
        $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";
        file_put_contents($filePath, $content);
    }
}
