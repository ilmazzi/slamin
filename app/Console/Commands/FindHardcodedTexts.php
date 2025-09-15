<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Helpers\AutoTranslationHelper;

class FindHardcodedTexts extends Command
{
    protected $signature = 'translations:find-hardcoded
                            {--path=resources/views : Percorso da scansionare}
                            {--auto-capture : Cattura automaticamente i testi trovati}
                            {--min-length=3 : Lunghezza minima del testo}';

    protected $description = 'Trova e cattura automaticamente testi hardcoded nelle viste';

    public function handle()
    {
        $path = $this->option('path');
        $autoCapture = $this->option('auto-capture');
        $minLength = $this->option('min-length');

        $this->info("🔍 Scansionando: {$path}");
        $this->info("📏 Lunghezza minima: {$minLength} caratteri");
        $this->info("🤖 Cattura automatica: " . ($autoCapture ? 'SÌ' : 'NO'));
        $this->newLine();

        $files = $this->getBladeFiles($path);
        $foundTexts = [];

        foreach ($files as $file) {
            $this->info("📄 Analizzando: " . str_replace(base_path(), '', $file));

            $texts = $this->findTextsInFile($file, $minLength);
            $foundTexts = array_merge($foundTexts, $texts);
        }

        $this->newLine();
        $this->info("📊 RISULTATI:");
        $this->info("📁 File analizzati: " . count($files));
        $this->info("📝 Testi trovati: " . count($foundTexts));

        if (empty($foundTexts)) {
            $this->warn("✅ Nessun testo hardcoded trovato!");
            return;
        }

        $this->newLine();
        $this->info("📋 TESTI TROVATI:");
        $this->newLine();

        foreach ($foundTexts as $i => $text) {
            $this->line(($i + 1) . ". " . $text['text']);
            $this->line("   📁 File: " . str_replace(base_path(), '', $text['file']));
            $this->line("   📍 Linea: " . $text['line']);
            $this->newLine();

            if ($autoCapture) {
                try {
                    AutoTranslationHelper::capture(
                        $text['text'],
                        'blade_template',
                        $text['file'],
                        $text['line']
                    );
                    $this->info("   ✅ Catturato automaticamente");
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore nella cattura: " . $e->getMessage());
                }
            }
        }

        if ($autoCapture) {
            $this->newLine();
            $this->info("🎉 Tutti i testi sono stati catturati!");
            $this->info("🔗 Vai a /admin/translations/queue per gestirli");
        } else {
            $this->newLine();
            $this->info("💡 Per catturare automaticamente, usa: --auto-capture");
        }
    }

    private function getBladeFiles($path)
    {
        $fullPath = base_path($path);

        if (!is_dir($fullPath)) {
            $this->error("❌ Percorso non valido: {$fullPath}");
            return [];
        }

        return File::allFiles($fullPath, function ($file) {
            return $file->getExtension() === 'php' &&
                   str_contains($file->getFilename(), '.blade.php');
        });
    }

    private function findTextsInFile($filePath, $minLength)
    {
        $content = File::get($filePath);
        $lines = explode("\n", $content);
        $texts = [];

        // Pattern per trovare testi hardcoded (solo testi reali, non classi CSS)
        $patterns = [
            // Testi in tag HTML (solo contenuto, non attributi)
            '/>([^<>{' . $minLength . ',}]+)</',
            // Testi in attributi specifici (solo quelli che contengono testo da tradurre)
            '/title="([^"]{' . $minLength . ',})"/',
            '/placeholder="([^"]{' . $minLength . ',})"/',
            '/alt="([^"]{' . $minLength . ',})"/',
        ];

        foreach ($lines as $lineNumber => $line) {
            $lineNumber++; // 1-based

            // Salta righe che contengono già traduzioni o codice
            if (str_contains($line, '@t(') ||
                str_contains($line, '@trans(') ||
                str_contains($line, '@auto(') ||
                str_contains($line, '{{ __(') ||
                str_contains($line, '{{ trans(') ||
                str_contains($line, '{{ $') ||
                str_contains($line, '{{ ') ||
                str_contains($line, '<?php') ||
                str_contains($line, '@if') ||
                str_contains($line, '@foreach') ||
                str_contains($line, '@endforeach') ||
                str_contains($line, '@endif') ||
                str_contains($line, '@section') ||
                str_contains($line, '@extends') ||
                str_contains($line, '@yield') ||
                str_contains($line, '<!--') ||
                str_contains($line, '-->') ||
                str_contains($line, 'class=') ||
                str_contains($line, 'id=') ||
                str_contains($line, 'style=') ||
                str_contains($line, 'data-') ||
                str_contains($line, 'src=') ||
                str_contains($line, 'href=')) {
                continue;
            }

            foreach ($patterns as $pattern) {
                preg_match_all($pattern, $line, $matches);

                foreach ($matches[1] as $match) {
                    $text = trim($match);

                    // Filtra testi che non sono da tradurre
                    if (str_contains($text, '$') ||
                        str_contains($text, '{{') ||
                        str_contains($text, '}}') ||
                        str_contains($text, '@') ||
                        str_contains($text, 'php') ||
                        str_contains($text, 'html') ||
                        str_contains($text, 'css') ||
                        str_contains($text, 'js') ||
                        str_contains($text, 'http') ||
                        str_contains($text, 'www') ||
                        str_contains($text, '.com') ||
                        str_contains($text, '.it') ||
                        str_contains($text, '.org') ||
                        str_contains($text, '.jpg') ||
                        str_contains($text, '.png') ||
                        str_contains($text, '.gif') ||
                        str_contains($text, '.svg') ||
                        str_contains($text, '.webp') ||
                        str_contains($text, 'assets/') ||
                        str_contains($text, 'images/') ||
                        str_contains($text, 'js/') ||
                        str_contains($text, 'css/') ||
                        str_contains($text, 'ph-') ||
                        str_contains($text, 'ti-') ||
                        str_contains($text, 'btn-') ||
                        str_contains($text, 'text-') ||
                        str_contains($text, 'bg-') ||
                        str_contains($text, 'border-') ||
                        str_contains($text, 'd-') ||
                        str_contains($text, 'f-') ||
                        str_contains($text, 'h-') ||
                        str_contains($text, 'w-') ||
                        str_contains($text, 'm-') ||
                        str_contains($text, 'p-') ||
                        str_contains($text, 'col-') ||
                        str_contains($text, 'row') ||
                        str_contains($text, 'card') ||
                        str_contains($text, 'modal') ||
                        str_contains($text, 'form-') ||
                        str_contains($text, 'input-') ||
                        str_contains($text, 'table') ||
                        str_contains($text, 'nav') ||
                        str_contains($text, 'navbar') ||
                        str_contains($text, 'dropdown') ||
                        str_contains($text, 'alert') ||
                        str_contains($text, 'badge') ||
                        str_contains($text, 'spinner') ||
                        str_contains($text, 'progress') ||
                        str_contains($text, 'tooltip') ||
                        str_contains($text, 'popover') ||
                        str_contains($text, 'carousel') ||
                        str_contains($text, 'accordion') ||
                        str_contains($text, 'collapse') ||
                        str_contains($text, 'tab') ||
                        str_contains($text, 'pagination') ||
                        str_contains($text, 'breadcrumb') ||
                        str_contains($text, 'list-group') ||
                        str_contains($text, 'btn-group') ||
                        str_contains($text, 'input-group') ||
                        str_contains($text, 'form-group') ||
                        str_contains($text, 'form-control') ||
                        str_contains($text, 'form-select') ||
                        str_contains($text, 'form-check') ||
                        str_contains($text, 'form-switch') ||
                        str_contains($text, 'form-range') ||
                        str_contains($text, 'form-text') ||
                        str_contains($text, 'form-label') ||
                        str_contains($text, 'form-floating') ||
                        str_contains($text, 'form-inline') ||
                        str_contains($text, 'form-row') ||
                        str_contains($text, 'form-horizontal') ||
                        str_contains($text, 'form-vertical') ||
                        str_contains($text, 'form-inline') ||
                        str_contains($text, 'form-group') ||
                        str_contains($text, 'form-control') ||
                        str_contains($text, 'form-select') ||
                        str_contains($text, 'form-check') ||
                        str_contains($text, 'form-switch') ||
                        str_contains($text, 'form-range') ||
                        str_contains($text, 'form-text') ||
                        str_contains($text, 'form-label') ||
                        str_contains($text, 'form-floating') ||
                        str_contains($text, 'form-inline') ||
                        str_contains($text, 'form-row') ||
                        str_contains($text, 'form-horizontal') ||
                        str_contains($text, 'form-vertical') ||
                        str_contains($text, 'form-inline') ||
                        is_numeric($text) ||
                        strlen($text) < $minLength) {
                        continue;
                    }

                    $texts[] = [
                        'text' => $text,
                        'file' => $filePath,
                        'line' => $lineNumber,
                        'context' => trim($line)
                    ];
                }
            }
        }

        return $texts;
    }
}
