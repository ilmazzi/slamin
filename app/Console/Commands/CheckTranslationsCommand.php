<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CheckTranslationsCommand extends Command
{
    protected $signature = 'translations:check';
    protected $description = 'Verifica lo stato delle traduzioni in tutte le lingue';

    private $languages = [
        'it' => 'Italian',
        'en' => 'English',
        'es' => 'Spanish',
        'fr' => 'French',
        'de' => 'German',
        'pt' => 'Portuguese'
    ];

    public function handle()
    {
        $this->info('🔍 Verifica stato traduzioni...');

        $italianPath = lang_path('it');
        if (!File::exists($italianPath)) {
            $this->error('Directory italiana non trovata!');
            return self::FAILURE;
        }

        $italianFiles = File::allFiles($italianPath);
        $totalKeys = 0;
        $italianKeys = [];

        // Conta le chiavi italiane
        foreach ($italianFiles as $file) {
            if ($file->getExtension() !== 'php') continue;

            $translations = include $file->getPathname();
            if (is_array($translations)) {
                $fileKeys = $this->countKeys($translations);
                $totalKeys += $fileKeys;
                $italianKeys[pathinfo($file->getFilename(), PATHINFO_FILENAME)] = $fileKeys;
            }
        }

        $this->info("📊 Totale chiavi italiane: {$totalKeys}");
        $this->line('');

        // Verifica ogni lingua
        foreach ($this->languages as $lang => $name) {
            if ($lang === 'it') continue;

            $this->info("🌍 Verifica {$name} ({$lang}):");

            $targetPath = lang_path($lang);
            if (!File::exists($targetPath)) {
                $this->error("  ❌ Directory non trovata");
                continue;
            }

            $translatedKeys = 0;
            $missingKeys = 0;
            $placeholderKeys = 0;

            foreach ($italianFiles as $file) {
                if ($file->getExtension() !== 'php') continue;

                $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $targetFile = $targetPath . '/' . $file->getRelativePathname();

                if (!File::exists($targetFile)) {
                    $missingKeys += $italianKeys[$filename] ?? 0;
                    continue;
                }

                $italianTranslations = include $file->getPathname();
                $targetTranslations = include $targetFile;

                if (!is_array($italianTranslations) || !is_array($targetTranslations)) {
                    continue;
                }

                $result = $this->analyzeTranslations($italianTranslations, $targetTranslations);
                $translatedKeys += $result['translated'];
                $missingKeys += $result['missing'];
                $placeholderKeys += $result['placeholders'];
            }

            $completion = $totalKeys > 0 ? round(($translatedKeys / $totalKeys) * 100, 1) : 0;

            $this->line("  ✅ Chiavi tradotte: {$translatedKeys}");
            $this->line("  ❌ Chiavi mancanti: {$missingKeys}");
            $this->line("  🔧 Placeholder da migliorare: {$placeholderKeys}");
            $this->line("  📈 Completamento: {$completion}%");
            $this->line('');
        }

        $this->info('🎉 Verifica completata!');
        return self::SUCCESS;
    }

    private function countKeys($array)
    {
        $count = 0;
        foreach ($array as $value) {
            if (is_array($value)) {
                $count += $this->countKeys($value);
            } else {
                $count++;
            }
        }
        return $count;
    }

    private function analyzeTranslations($italian, $target)
    {
        $translated = 0;
        $missing = 0;
        $placeholders = 0;

        foreach ($italian as $key => $value) {
            if (is_array($value)) {
                if (isset($target[$key]) && is_array($target[$key])) {
                    $result = $this->analyzeTranslations($value, $target[$key]);
                    $translated += $result['translated'];
                    $missing += $result['missing'];
                    $placeholders += $result['placeholders'];
                } else {
                    $missing += $this->countKeys($value);
                }
            } else {
                if (isset($target[$key])) {
                    if (preg_match('/^\[([a-z]{2})\]\s*(.+)$/', $target[$key])) {
                        $placeholders++;
                    } else {
                        $translated++;
                    }
                } else {
                    $missing++;
                }
            }
        }

        return [
            'translated' => $translated,
            'missing' => $missing,
            'placeholders' => $placeholders
        ];
    }
}
