<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

class LangAuditCommand extends Command
{
    /**
     * Nome comando.
     */
    protected $signature = 'lang:audit
                            {--paths=resources/views,app,resources/js : Cartelle da scansionare, separate da virgola}
                            {--locales=it,en : Lista locale da considerare per i file di lingua (es. it,en)}
                            {--primary=it : Locale primario su cui verificare le definizioni (default: it)}
                            {--extensions=php,blade.php,js,ts,vue : Estensioni file da scansionare}
                            {--max-hardcoded-length=200 : Scarta blocchi di testo troppo lunghi}
                            {--min-hardcoded-length=3 : Considera testo >= N caratteri come candidato}
                            {--attrs=placeholder,title,alt,aria-label : Attributi HTML (white-list) da cui estrarre testo}
                            {--collect-attrs=1 : 1=estrai testo da attributi utili; 0=ignora completamente gli attributi}
                            ';

    protected $description = 'Scansiona il progetto per audit delle traduzioni: chiavi mancanti, non usate e stringhe hardcoded (ripulite da HTML/CSS/JS).';

    /**
     * Collezioni interne.
     */
    protected array $usedKeys = [];         // es. ['auth.failed', 'validation.required', ...]
    protected array $usedJson = [];         // es. ['Welcome', 'Logout', ...] (JSON translations)
    protected array $hardcodedCandidates = [];

    protected array $definedKeysByLocale = [];   // es. ['it' => ['auth.failed', ...], 'en' => [...]]
    protected array $definedJsonByLocale = [];   // es. ['it' => ['Welcome', ...], 'en' => [...]]
    protected array $supportedStringFunctions = [
        // PHP/Blade
        '__', '@lang', 'trans', 'trans_choice',
        // JS tipici (se li usi, verranno riconosciuti)
        'i18n.t', 't'
    ];

    public function handle(): int
    {
        $paths          = array_filter(array_map('trim', explode(',', (string)$this->option('paths'))));
        $locales        = array_filter(array_map('trim', explode(',', (string)$this->option('locales'))));
        $primary        = (string)$this->option('primary');
        $extensions     = array_filter(array_map('trim', explode(',', (string)$this->option('extensions'))));
        $maxLen         = (int)$this->option('max-hardcoded-length');
        $minLen         = (int)$this->option('min-hardcoded-length');
        $collectAttrs   = (bool)((int)$this->option('collect-attrs'));
        $attrWhitelist  = array_filter(array_map('trim', explode(',', (string)$this->option('attrs'))));

        if (!in_array($primary, $locales, true)) {
            $this->warn("Il locale primario '{$primary}' non è nella lista --locales. Lo aggiungo.");
            $locales[] = $primary;
        }

        $this->info('== LANG AUDIT ==');
        $this->line('Paths:        ' . implode(', ', $paths));
        $this->line('Locales:      ' . implode(', ', $locales));
        $this->line('Primary:      ' . $primary);
        $this->line('Extensions:   ' . implode(', ', $extensions));
        $this->newLine();

        // 1) Carica tutte le chiavi definite nei file lang/ (PHP e JSON)
        $this->loadDefinedTranslations($locales);

        // 2) Scansiona il codice per trovare uso di chiavi e stringhe in chiaro
        $this->scanCode($paths, $extensions, $maxLen, $minLen, $collectAttrs, $attrWhitelist);

        // 3) Confronti
        $missingKeys = $this->computeMissingKeys($primary);
        $unusedKeys  = $this->computeUnusedKeys($primary);

        // 4) Output (console)
        $this->printSection('MISSING_KEYS (usate ma non presenti in resources/lang)', $missingKeys);
        $this->printSection('UNUSED_KEYS (presenti in resources/lang ma non usate)', $unusedKeys);
        $this->printSection('HARDCODED_STRINGS (candidati da mettere sotto traduzione)', $this->hardcodedCandidates);

        // Sintesi
        $this->newLine();
        $this->info('== SUMMARY ==');
        $this->line('Missing keys:   ' . count($missingKeys));
        $this->line('Unused keys:    ' . count($unusedKeys));
        $this->line('Hardcoded strs: ' . count($this->hardcodedCandidates));
        $this->newLine();
        $this->line('Suggerimento:');
        $this->line('- Per le MISSING_KEYS: aggiungi le chiavi nei file del locale primario (' . $primary . ').');
        $this->line('- Per le UNUSED_KEYS: valuta la rimozione (dopo verifica).');
        $this->line('- Per le HARDCODED_STRINGS: sostituisci con __(), @lang o JSON translations.');
        $this->newLine();

        // 5) Output su file (REPORT)
        $reportDir = storage_path('lang_audit');
        if (!is_dir($reportDir)) {
            @mkdir($reportDir, 0777, true);
        }

        // Salva i 3 report JSON
        file_put_contents(
            $reportDir . '/missing.json',
            json_encode($missingKeys, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        file_put_contents(
            $reportDir . '/unused.json',
            json_encode($unusedKeys, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        file_put_contents(
            $reportDir . '/hardcoded.json',
            json_encode($this->hardcodedCandidates, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        // Salva una sintesi testuale
        $summary = [];
        $summary[] = '== LANG AUDIT SUMMARY ==';
        $summary[] = 'Paths:      ' . implode(', ', $paths);
        $summary[] = 'Locales:    ' . implode(', ', $locales);
        $summary[] = 'Primary:    ' . $primary;
        $summary[] = 'Extensions: ' . implode(', ', $extensions);
        $summary[] = '';
        $summary[] = 'Missing keys:   ' . count($missingKeys);
        $summary[] = 'Unused keys:    ' . count($unusedKeys);
        $summary[] = 'Hardcoded strs: ' . count($this->hardcodedCandidates);
        $summary[] = '';
        $summary[] = 'Report files:';
        $summary[] = ' - ' . $reportDir . '/missing.json';
        $summary[] = ' - ' . $reportDir . '/unused.json';
        $summary[] = ' - ' . $reportDir . '/hardcoded.json';

        file_put_contents($reportDir . '/summary.txt', implode(PHP_EOL, $summary));

        $this->info("Report salvati in: {$reportDir}");

        return self::SUCCESS;
    }

    /**
     * Carica le traduzioni definite in resources/lang per i locali indicati.
     * Supporta:
     *   - PHP keyed files (ritornano array)
     *   - JSON translations (key = testo, value = traduzione)
     */
    protected function loadDefinedTranslations(array $locales): void
    {
        $base = base_path('resources/lang');

        foreach ($locales as $locale) {
            $phpDir = $base . DIRECTORY_SEPARATOR . $locale;
            $jsonFile = $base . DIRECTORY_SEPARATOR . $locale . '.json';

            $phpKeys = [];
            if (is_dir($phpDir)) {
                $files = glob($phpDir . DIRECTORY_SEPARATOR . '*.php') ?: [];
                foreach ($files as $file) {
                    $filename = pathinfo($file, PATHINFO_FILENAME); // es. 'auth', 'validation'
                    $arr = @include $file;
                    if (is_array($arr)) {
                        $flat = $this->arrayDot($arr, $filename);
                        $phpKeys = array_merge($phpKeys, array_keys($flat));
                    }
                }
            }

            $this->definedKeysByLocale[$locale] = array_values(array_unique($phpKeys));

            $jsonKeys = [];
            if (is_file($jsonFile)) {
                $json = json_decode((string) file_get_contents($jsonFile), true);
                if (is_array($json)) {
                    $jsonKeys = array_keys($json); // in JSON, la "chiave" è il testo originale
                }
            }
            $this->definedJsonByLocale[$locale] = array_values(array_unique($jsonKeys));
        }
    }

    /**
     * Scansiona i file sorgente e raccoglie:
     *  - chiavi usate con __(), @lang, trans(), trans_choice()
     *  - chiavi usate in JS (i18n.t('...') / t('...')) se presenti
     *  - stringhe “in chiaro” (euristiche ripulite)
     */
    protected function scanCode(array $paths, array $extensions, int $maxLen, int $minLen, bool $collectAttrs, array $attrWhitelist): void
    {
        $finder = new Finder();
        $finder->files();

        // filtra per estensioni
        foreach ($extensions as $ext) {
            $finder->name('*.' . $ext);
        }

        $finder->in($paths);

        foreach ($finder as $file) {
            $content = $file->getContents();
            $path = $file->getRealPath() ?: $file->getRelativePathname();

            // 2.1) Estrai chiavi da funzioni comuni PHP/Blade
            $this->extractPhpBladeTranslationCalls($content);

            // 2.2) Estrai chiavi da chiamate JS comuni (i18n.t / t)
            $this->extractJsTranslationCalls($content);

            // 2.3) Heuristica per hardcoded (ripulita)
            $this->extractHardcodedCandidatesClean($content, $path, $maxLen, $minLen, $collectAttrs, $attrWhitelist);
        }

        // normalizza set (unique)
        $this->usedKeys = array_values(array_unique($this->usedKeys));
        $this->usedJson = array_values(array_unique($this->usedJson));
        $this->hardcodedCandidates = $this->uniqueByValue($this->hardcodedCandidates);
    }

    /**
     * Trova pattern tipo:
     *   __('key'), __("key")
     *   @lang('key'), @lang("key")
     *   trans('key'), trans_choice('key', n)
     */
    protected function extractPhpBladeTranslationCalls(string $content): void
    {
        $patterns = [
            // __('key') o __("key")
            '/__\(\s*[\'"]([^\'"]+)[\'"]\s*[\),]/U',
            // @lang('key') o @lang("key")
            '/@lang\(\s*[\'"]([^\'"]+)[\'"]\s*\)/U',
            // trans('key')
            '/trans\(\s*[\'"]([^\'"]+)[\'"]\s*[\),]/U',
            // trans_choice('key', ...)
            '/trans_choice\(\s*[\'"]([^\'"]+)[\'"]\s*,/U',
        ];

        foreach ($patterns as $regex) {
            if (preg_match_all($regex, $content, $m)) {
                foreach ($m[1] as $key) {
                    if ($this->looksLikeKey($key)) {
                        $this->usedKeys[] = $key;
                    } else {
                        // Frase intera → candidata JSON (es. __("Welcome back"))
                        $this->usedJson[] = $key;
                    }
                }
            }
        }
    }

    /**
     * Trova pattern tipo i18n.t('key') o t("key")
     */
    protected function extractJsTranslationCalls(string $content): void
    {
        $patterns = [
            '/i18n\.t\(\s*[\'"]([^\'"]+)[\'"]\s*[\),]/U',
            '/\bt\(\s*[\'"]([^\'"]+)[\'"]\s*[\),]/U',
        ];


        // correct extraction (bugfix: swap $content/$m)
        foreach ($patterns as $regex) {
            if (preg_match_all($regex, $content, $m)) {
                foreach ($m[1] as $key) {
                    if ($this->looksLikeKey($key)) {
                        $this->usedKeys[] = $key;
                    } else {
                        $this->usedJson[] = $key;
                    }
                }
            }
        }
    }

    /**
     * Estrazione hardcoded “pulita”:
     * - rimuove script/style
     * - rimuove blade {{ }} e direttive @...
     * - converte i tag HTML in separatori, poi valuta SOLO testo visibile
     * - ignora classi/id/style e utility CSS, icone, JS e frammenti di codice
     * - opzionalmente estrae attributi testuali (placeholder, alt, title, aria-label)
     */
    protected function extractHardcodedCandidatesClean(
        string $content,
        string $path,
        int $maxLen,
        int $minLen,
        bool $collectAttrs,
        array $attrWhitelist
    ): void {
        $candidates = [];

        // 0) Rimuovi <script> e <style>
        $clean = preg_replace('/<script\b[^>]*>.*?<\/script>/is', ' ', $content);
        $clean = preg_replace('/<style\b[^>]*>.*?<\/style>/is', ' ', $clean);

        // 1) Rimuovi Blade echo/direttive
        $clean = preg_replace('/\{\{.*?\}\}/s', ' ', $clean);      // {{ ... }}
        $clean = preg_replace('/\@[\w]+\(?.*?\)?/s', ' ', $clean); // @if(...), @section(...), @csrf etc.

        // 2) Opzionale: estrai solo attributi testuali "utili"
        if ($collectAttrs && !empty($attrWhitelist)) {
            foreach ($attrWhitelist as $attr) {
                $regex = '/\b' . preg_quote($attr, '/') . '\s*=\s*"(.*?)"/i';
                if (preg_match_all($regex, $clean, $m)) {
                    foreach ($m[1] as $raw) {
                        $text = html_entity_decode(trim($raw), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        if ($this->isViableHumanText($text, $minLen, $maxLen)) {
                            $candidates[] = ['text' => $text, 'file' => $path];
                        }
                    }
                }
                // versione con apici singoli
                $regex2 = '/\b' . preg_quote($attr, '/') . "\s*=\s*'(.*?)'/i";
                if (preg_match_all($regex2, $clean, $m2)) {
                    foreach ($m2[1] as $raw) {
                        $text = html_entity_decode(trim($raw), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        if ($this->isViableHumanText($text, $minLen, $maxLen)) {
                            $candidates[] = ['text' => $text, 'file' => $path];
                        }
                    }
                }
            }
        }

        // 3) Rimpiazza i tag con separatori per isolare i "text nodes"
        $textOnly = preg_replace('/<[^>]+>/', '|', $clean);

        // 4) Split in nodi e filtra
        $nodes = array_filter(array_map('trim', explode('|', (string)$textOnly)));
        foreach ($nodes as $node) {
            $text = html_entity_decode($node, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            if (!$this->isViableHumanText($text, $minLen, $maxLen)) {
                continue;
            }
            if ($this->looksLikeCodeOrCss($text)) {
                continue;
            }
            if ($this->looksLikeCssUtilityOrIcon($text)) {
                continue;
            }
            // Evita duplicati tra JSON già usati
            if (in_array($text, $this->usedJson, true)) {
                continue;
            }

            $candidates[] = ['text' => $text, 'file' => $path];
        }

        // Append
        foreach ($candidates as $c) {
            $this->hardcodedCandidates[] = $c;
        }
    }

    /**
     * Heuristica: è testo "umano" plausibile?
     * - lunghezza tra min/max
     * - contiene lettere
     * - densità lettere >= 0.6 (evita classi/slug)
     * - NON contiene simboli tipici di codice
     */
    protected function isViableHumanText(string $text, int $minLen, int $maxLen): bool
    {
        $text = trim(preg_replace('/\s+/', ' ', $text));
        $len  = mb_strlen($text, 'UTF-8');
        if ($len < $minLen || $len > $maxLen) {
            return false;
        }

        if (!preg_match('/\p{L}/u', $text)) { // almeno una lettera
            return false;
        }

        // scarta se include simboli da codice/markup
        if (preg_match('/[{}$;=()<>]|<\/?|\/>/', $text)) {
            return false;
        }

        // densità di lettere
        $letters = preg_replace('/[^\\p{L} ]+/u', '', $text);
        $ratio = $letters === '' ? 0 : (mb_strlen($letters, 'UTF-8') / $len);
        if ($ratio < 0.6) {
            return false;
        }

        // scarta se sembra un path/slug o una chiave (tanto le chiavi vengono già estratte altrove)
        if (preg_match('/[\/\\\\]|^\w[\w\.\-:]*$/u', $text)) {
            return false;
        }

        return true;
    }

    /**
     * Rumore: frammenti JS/PHP/CSS tipici.
     */
    protected function looksLikeCodeOrCss(string $text): bool
    {
        // contiene pattern tipici di codice
        if (preg_match('/(@if|@foreach|function\s|\bconst\b|\blet\b|\bvar\b|=>|\$\w+|\);|\(\)|\?[:=]|:\s*{|return\b)/', $text)) {
            return true;
        }

        // sembra un valore CSS/inline
        if (preg_match('/\b(px|rem|em|vh|vw|%|#\p{Xan}{3,6})\b/u', $text)) {
            return true;
        }

        // numeri/ID isolati o timestampiformi
        if (preg_match('/^\d{1,4}(\s|$)/', $text)) {
            return true;
        }

        return false;
    }

    /**
     * Rumore: classi CSS/icone/utilities bootstrap/tailwind/tabler/fontawesome ecc.
     */
    protected function looksLikeCssUtilityOrIcon(string $text): bool
    {
        // classi comuni (bootstrap / tailwind / tabler / fontawesome / icone)
        $patterns = [
            '/\bbtn\b/i',
            '/\bbadge\b/i',
            '/\bcol-\d+\b/i',
            '/\bti([ -]|$)/i',         // tabler-icons
            '/\bfa([ -]|$)/i',         // fontawesome
            '/\btext-[a-z0-9-]+\b/i',  // tailwind utilities
            '/\bbg-[a-z0-9-]+\b/i',
            '/\brounded(-[a-z0-9]+)?\b/i',
            '/\bshadow(-[a-z0-9]+)?\b/i',
            '/\b(m|p)[trblxy]?-\d+\b/i', // margini/padding tailwind/bs
            '/\b(d|flex|grid|gap|order|align|justify)[-\w]*\b/i',
            '/\bposition-[\w-]+\b/i',
            '/\bcontainer(-\w+)?\b/i',
        ];
        foreach ($patterns as $rx) {
            if (preg_match($rx, $text)) {
                return true;
            }
        }
        return false;
    }

    protected function uniqueByValue(array $array): array
    {
        $seen = [];
        $out = [];
        foreach ($array as $item) {
            $hash = $item['text'] . '|' . $item['file'];
            if (!isset($seen[$hash])) {
                $seen[$hash] = true;
                $out[] = $item;
            }
        }
        return $out;
    }

    protected function looksLikeKey(string $key): bool
    {
        // Considero "chiave" se contiene almeno un punto oppure solo [\w-] e non spazi
        if (str_contains($key, '.')) {
            return true;
        }
        if (preg_match('/^[\w\-:]+$/', $key)) {
            return true;
        }
        return false;
    }

    /**
     * Calcola chiavi mancanti con riferimento al locale primario.
     * Include:
     *  - Keyed: usedKeys - definedKeys(primary)
     *  - JSON:  usedJson - definedJson(primary)
     */
    protected function computeMissingKeys(string $primary): array
    {
        $definedKeyed = $this->definedKeysByLocale[$primary] ?? [];
        $definedJson  = $this->definedJsonByLocale[$primary] ?? [];

        $missing = [];

        // Keyed
        foreach ($this->usedKeys as $k) {
            if (!in_array($k, $definedKeyed, true)) {
                $missing[] = ['type' => 'keyed', 'key' => $k];
            }
        }

        // JSON
        foreach ($this->usedJson as $txt) {
            if (!in_array($txt, $definedJson, true)) {
                $missing[] = ['type' => 'json', 'key' => $txt];
            }
        }

        return $missing;
    }

    /**
     * Calcola chiavi non usate (presenti nei file lang ma non trovate nel codice).
     * Solo sul locale primario, per non duplicare la lista.
     */
    protected function computeUnusedKeys(string $primary): array
    {
        $definedKeyed = $this->definedKeysByLocale[$primary] ?? [];
        $definedJson  = $this->definedJsonByLocale[$primary] ?? [];

        $unused = [];

        foreach ($definedKeyed as $k) {
            if (!in_array($k, $this->usedKeys, true)) {
                $unused[] = ['type' => 'keyed', 'key' => $k];
            }
        }

        foreach ($definedJson as $txt) {
            if (!in_array($txt, $this->usedJson, true)) {
                $unused[] = ['type' => 'json', 'key' => $txt];
            }
        }

        return $unused;
    }

    protected function printSection(string $title, array $items): void
    {
        $this->newLine();
        $this->info($title);
        $this->line(str_repeat('-', 80));
        if (empty($items)) {
            $this->line('(vuoto)');
            return;
        }

        foreach ($items as $i) {
            if (isset($i['text'], $i['file'])) {
                // hardcoded
                $this->line('[' . $i['file'] . '] ' . $i['text']);
            } elseif (isset($i['type'], $i['key'])) {
                $this->line('(' . $i['type'] . ') ' . $i['key']);
            } else {
                // fallback
                $this->line(is_string($i) ? $i : json_encode($i, JSON_UNESCAPED_UNICODE));
            }
        }
    }

    /**
     * Flatten array con prefisso file: es. file 'auth' + ['failed' => '...', 'passwords' => ['reset' => '...']]
     * diventa: ['auth.failed' => '...', 'auth.passwords.reset' => '...']
     */
    protected function arrayDot(array $array, string $prefix = ''): array
    {
        $results = [];
        $prepend = $prefix ? $prefix . '.' : '';

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, $this->arrayDot($value, $prepend . $key));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }
}
