<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\TranslationApiService;
use App\Models\TranslationReview;

class TranslationManagementController extends Controller
{
    /**
     * Mostra la lista delle lingue disponibili
     */
    public function index()
    {
        $languages = $this->getAvailableLanguages();
        $translationFiles = $this->getTranslationFiles();

        // Calcola statistiche per ogni lingua
        $languageStats = [];
        $totalTranslated = 0;
        $totalMissing = 0;
        $totalKeys = 0;

        foreach ($languages as $language) {
            $stats = $this->getLanguageStats($language);
            $languageStats[$language] = $stats;
            $totalTranslated += $stats['translated_keys'];
            $totalMissing += $stats['missing_keys'];
            $totalKeys += $stats['total_keys'];
        }

        // Aggiungi statistiche totali
        $languageStats['total_translated'] = $totalTranslated;
        $languageStats['total_missing'] = $totalMissing;
        $languageStats['total_keys'] = $totalKeys;

        return view('admin.translations.index', compact('languages', 'translationFiles', 'languageStats'));
    }

    /**
     * Mostra il form per aggiungere una nuova lingua
     */
    public function create()
    {
        return view('admin.translations.create');
    }

    /**
     * Crea una nuova lingua
     */
    public function store(Request $request)
    {
        $request->validate([
            'language_code' => 'required|string|size:2|regex:/^[a-z]{2}$/',
            'language_name' => 'required|string|max:50',
        ]);

        $languageCode = strtolower($request->language_code);
        $languageName = $request->language_name;

        // Verifica che la lingua non esista già
        if ($this->languageExists($languageCode)) {
            return back()->withErrors(['language_code' => __('admin_general.language_exists_error')]);
        }

        // Crea la directory della lingua
        $languagePath = lang_path($languageCode);
        if (!File::exists($languagePath)) {
            File::makeDirectory($languagePath, 0755, true);
        }

        // Copia tutti i file di traduzione dall'italiano
        $italianPath = lang_path('it');
        if (File::exists($italianPath)) {
            $files = File::allFiles($italianPath);
            foreach ($files as $file) {
                $relativePath = $file->getRelativePathname();
                $targetPath = $languagePath . '/' . $relativePath;

                // Copia il file
                File::copy($file->getPathname(), $targetPath);
            }
        }

        return redirect()->route('admin.translations.index')
            ->with('success', __('admin_general.language_created_success'));
    }

    /**
     * Mostra le traduzioni per una lingua specifica
     */
    public function show($language)
    {
        if (!$this->languageExists($language)) {
            abort(404, __('admin_general.language_not_found_error'));
        }

        $translationFiles = $this->getTranslationFiles();
        $selectedFile = request('file', 'admin');

        // Ottieni tutte le chiavi italiane come riferimento
        $referenceTranslations = $this->getTranslations('it', $selectedFile);

        // Ottieni le traduzioni della lingua selezionata
        $translations = $this->getTranslations($language, $selectedFile);

        // Assicurati che siano sempre array
        $referenceTranslations = is_array($referenceTranslations) ? $referenceTranslations : [];
        $translations = is_array($translations) ? $translations : [];

        // Unisci le chiavi (mantieni quelle italiane come riferimento)
        $allKeys = array_unique(array_merge(array_keys($referenceTranslations), array_keys($translations)));

        // Prepara i dati per la view
        $translationData = [];
        foreach ($allKeys as $key) {
            $referenceValue = $referenceTranslations[$key] ?? '';
            $translationValue = $translations[$key] ?? '';

            // Se il valore è un array, convertilo in stringa
            if (is_array($referenceValue)) {
                $referenceValue = json_encode($referenceValue, JSON_UNESCAPED_UNICODE);
            }
            if (is_array($translationValue)) {
                $translationValue = json_encode($translationValue, JSON_UNESCAPED_UNICODE);
            }

            $translationData[$key] = [
                'reference' => $referenceValue,
                'translation' => $translationValue,
                'is_translated' => !empty($translations[$key]),
                'is_missing' => empty($translations[$key]) && !empty($referenceTranslations[$key])
            ];
        }

        // Statistiche
        $stats = [
            'total_keys' => count($allKeys),
            'translated_keys' => count(array_filter($translationData, fn($item) => $item['is_translated'])),
            'missing_keys' => count(array_filter($translationData, fn($item) => $item['is_missing'])),
            'progress_percentage' => count($allKeys) > 0 ? round((count(array_filter($translationData, fn($item) => $item['is_translated'])) / count($allKeys)) * 100, 1) : 0
        ];

        return view('admin.translations.show', compact(
            'language',
            'translationFiles',
            'selectedFile',
            'translationData',
            'stats'
        ))->with('file', $selectedFile);
    }

    /**
     * Salva le traduzioni
     */
    public function update(Request $request, $language)
    {
        if (!$this->languageExists($language)) {
            abort(404, __('admin_general.language_not_found_error'));
        }

        $file = $request->input('file', 'admin');
        $translations = $request->input('translations', []);

        try {
            $this->saveTranslations($language, $file, $translations);

            return response()->json([
                'success' => true,
                'message' => __('admin_general.translations_saved_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin_general.save_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina una lingua
     */
    public function destroy($language)
    {
        if ($language === 'it') {
            return back()->withErrors(['error' => __('admin_general.cannot_delete_italian')]);
        }

        if (!$this->languageExists($language)) {
            abort(404, __('admin_general.language_not_found_error'));
        }

        $languagePath = lang_path($language);
        if (File::exists($languagePath)) {
            File::deleteDirectory($languagePath);
        }

        return redirect()->route('admin.translations.index')
            ->with('success', __('admin_general.language_deleted_success'));
    }

    /**
     * Sincronizza le chiavi di traduzione
     */
    public function sync(Request $request)
    {
        $language = $request->input('language');

        if ($language && !$this->languageExists($language)) {
            return response()->json([
                'success' => false,
                'message' => __('admin_general.language_not_found_error')
            ], 404);
        }

        $updatedFiles = 0;
        $languages = $language ? [$language] : $this->getAvailableLanguages();

        foreach ($languages as $lang) {
            if ($lang === 'it') continue; // Skip reference language

            $updatedFiles += $this->syncLanguage($lang);
        }

        return response()->json([
            'success' => true,
            'message' => __('admin_general.sync_completed'),
            'files_updated' => $updatedFiles
        ]);
    }

    /**
     * Sincronizza tutte le lingue partendo dall'italiano
     */
    public function syncAllLanguages(Request $request)
    {
        try {
            $languages = $this->getAvailableLanguages();
            $italianFiles = $this->getTranslationFiles('it');
            $results = [];

            foreach ($languages as $lang) {
                if ($lang === 'it') continue;

                $results[$lang] = [
                    'files_processed' => 0,
                    'keys_added' => 0,
                    'keys_updated' => 0,
                    'errors' => []
                ];

                foreach ($italianFiles as $file) {
                    try {
                        $result = $this->syncLanguageFile('it', $lang, $file);
                        $results[$lang]['files_processed']++;
                        $results[$lang]['keys_added'] += $result['added'] ?? 0;
                        $results[$lang]['keys_updated'] += $result['updated'] ?? 0;
                    } catch (\Exception $e) {
                        $results[$lang]['errors'][] = "File {$file}: " . $e->getMessage();
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => __('admin_general.translations.sync_all_success'),
                'results' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.translations.sync_all_error', ['error' => $e->getMessage()])
            ], 500);
        }
    }

    /**
     * Copia tutte le traduzioni dall'italiano
     */
    public function copyFromItalian(Request $request, $language)
    {
        if (!$this->languageExists($language)) {
            abort(404, __('admin_general.language_not_found_error'));
        }

        $file = $request->input('file', 'admin');
        $italianTranslations = $this->getTranslations('it', $file);

        try {
            $this->saveTranslations($language, $file, $italianTranslations);

            return response()->json([
                'success' => true,
                'message' => __('admin_general.copy_from_italian') . ' ' . __('admin_general.completed')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin_general.save_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Svuota tutte le traduzioni
     */
    public function clearAll(Request $request, $language)
    {
        if (!$this->languageExists($language)) {
            abort(404, __('admin_general.language_not_found_error'));
        }

        $file = $request->input('file', 'admin');

        try {
            $this->saveTranslations($language, $file, []);

            return response()->json([
                'success' => true,
                'message' => __('admin_general.clear_all') . ' ' . __('admin_general.completed')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin_general.save_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ottiene le lingue disponibili
     */
    private function getAvailableLanguages()
    {
        $languages = [];
        $langPath = lang_path();

        if (File::exists($langPath)) {
            $directories = File::directories($langPath);
            foreach ($directories as $dir) {
                $languageCode = basename($dir);
                $languages[] = $languageCode;
            }
        }

        sort($languages);
        return $languages;
    }

    /**
     * Ottiene i file di traduzione disponibili
     */
    private function getTranslationFiles()
    {
        $files = [];
        $italianPath = lang_path('it');

        if (File::exists($italianPath)) {
            $fileObjects = File::allFiles($italianPath);
            foreach ($fileObjects as $file) {
                // Salta le directory
                if ($file->isDir()) {
                    continue;
                }

                $filename = $file->getFilenameWithoutExtension();
                // Salta i file di backup
                if (strpos($filename, 'backup_') === 0) {
                    continue;
                }

                $files[$filename] = $this->getFileDisplayName($filename);
            }
        }

        ksort($files);
        return $files;
    }

    /**
     * Ottiene il nome visualizzato per un file
     */
    private function getFileDisplayName($filename)
    {
        $displayNames = [
            'admin' => __('admin_general.file_admin'),
            'auth' => __('admin_general.file_auth'),
            'common' => __('admin_general.file_common'),
            'dashboard' => __('admin_general.file_dashboard'),
            'events' => __('admin_general.file_events'),
            'videos' => __('admin_general.file_videos'),
            'carousel' => __('admin_general.file_carousel'),
            'home' => 'Home',
            'poems' => 'Poems',
            'profile' => 'Profile',
            'register' => 'Register',
            'login' => 'Login',
            'notifications' => 'Notifications',
            'permissions' => 'Permissions',
            'premium' => 'Premium',
            'sidebar' => 'Sidebar',
            'wishlist' => 'Wishlist',
            'invitations' => 'Invitations',
        ];

        return $displayNames[$filename] ?? ucfirst($filename);
    }

    /**
     * Verifica se una lingua esiste
     */
    private function languageExists($language)
    {
        return File::exists(lang_path($language));
    }

    /**
     * Ottiene le traduzioni per una lingua e file specifici
     */
    private function getTranslations($language, $file)
    {
        $filePath = lang_path($language . '/' . $file . '.php');

        if (!File::exists($filePath)) {
            return [];
        }

        try {
            $result = include $filePath;
            return is_array($result) ? $result : [];
        } catch (\ParseError $e) {
            // Log syntax error for debugging
            \Log::error("Syntax error in translation file: {$filePath} - " . $e->getMessage());
            return [];
        } catch (\Exception $e) {
            // Log other errors
            \Log::error("Error loading translation file: {$filePath} - " . $e->getMessage());
            return [];
        }
    }

    /**
     * Salva le traduzioni
     */
    private function saveTranslations($language, $file, $translations)
    {
        $filePath = lang_path($language . '/' . $file . '.php');

        // Crea la directory se non esiste
        $directory = dirname($filePath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Genera il contenuto PHP
        $content = "<?php\n\nreturn [\n";

        foreach ($translations as $key => $value) {
            $escapedKey = $this->escapePhpString($key);
            $escapedValue = $this->escapePhpString($value);
            $content .= "    '{$escapedKey}' => '{$escapedValue}',\n";
        }

        $content .= "\n];\n";

        // Salva il file
        File::put($filePath, $content);
    }

    /**
     * Sincronizza una lingua con l'italiano
     */
    private function syncLanguage($language)
    {
        $updatedFiles = 0;
        $italianPath = lang_path('it');
        $languagePath = lang_path($language);

        if (!File::exists($italianPath) || !File::exists($languagePath)) {
            return $updatedFiles;
        }

        $italianFiles = File::allFiles($italianPath);

        foreach ($italianFiles as $file) {
            $filename = $file->getFilename();
            $relativePath = $file->getRelativePathname();
            $targetPath = $languagePath . '/' . $relativePath;

            // Carica le traduzioni italiane
            try {
                $italianTranslations = include $file->getPathname();
                $italianTranslations = is_array($italianTranslations) ? $italianTranslations : [];
            } catch (\ParseError $e) {
                \Log::error("Syntax error in Italian translation file: {$file->getPathname()} - " . $e->getMessage());
                $italianTranslations = [];
            } catch (\Exception $e) {
                \Log::error("Error loading Italian translation file: {$file->getPathname()} - " . $e->getMessage());
                $italianTranslations = [];
            }

            // Carica le traduzioni esistenti della lingua target
            $existingTranslations = [];
            if (File::exists($targetPath)) {
                try {
                    $existingTranslations = include $targetPath;
                    $existingTranslations = is_array($existingTranslations) ? $existingTranslations : [];
                } catch (\ParseError $e) {
                    \Log::error("Syntax error in target translation file: {$targetPath} - " . $e->getMessage());
                    $existingTranslations = [];
                } catch (\Exception $e) {
                    \Log::error("Error loading target translation file: {$targetPath} - " . $e->getMessage());
                    $existingTranslations = [];
                }
            }

            // Unisci le traduzioni (mantieni quelle esistenti, aggiungi quelle mancanti)
            $mergedTranslations = array_merge($italianTranslations, $existingTranslations);

            // Salva solo se ci sono state modifiche
            if ($mergedTranslations !== $existingTranslations) {
                $this->saveTranslations($language, pathinfo($filename, PATHINFO_FILENAME), $mergedTranslations);
                $updatedFiles++;
            }
        }

        return $updatedFiles;
    }

    /**
     * Sincronizza un file di traduzione specifico
     */
    private function syncLanguageFile($sourceLanguage, $targetLanguage, $file)
    {
        $sourceFile = lang_path("{$sourceLanguage}/{$file}.php");
        $targetFile = lang_path("{$targetLanguage}/{$file}.php");

        if (!file_exists($sourceFile)) {
            return false;
        }

        $sourceTranslations = include $sourceFile;
        $targetTranslations = file_exists($targetFile) ? include $targetFile : [];

        // Assicurati che entrambi siano array
        if (!is_array($sourceTranslations)) {
            $sourceTranslations = [];
        }
        if (!is_array($targetTranslations)) {
            $targetTranslations = [];
        }

        $added = 0;
        $updated = 0;

        // Aggiungi chiavi mancanti
        foreach ($sourceTranslations as $key => $value) {
            if (!isset($targetTranslations[$key])) {
                $targetTranslations[$key] = $value;
                $added++;
            } elseif (is_array($value) && is_array($targetTranslations[$key])) {
                // Sincronizza array annidati
                $nestedResult = $this->syncNestedTranslations($value, $targetTranslations[$key]);
                $added += $nestedResult['added'];
                $updated += $nestedResult['updated'];
            }
        }

        // Rimuovi chiavi orfane (opzionale)
        $orphanedKeys = array_diff_key($targetTranslations, $sourceTranslations);
        foreach ($orphanedKeys as $key => $value) {
            unset($targetTranslations[$key]);
        }

        // Salva il file aggiornato
        if ($added > 0 || $updated > 0 || count($orphanedKeys) > 0) {
            $this->saveTranslationFile($targetFile, $targetTranslations);
            return ['added' => $added, 'updated' => $updated, 'removed' => count($orphanedKeys)];
        }

        return false;
    }

    /**
     * Sincronizza traduzioni annidate
     */
    private function syncNestedTranslations($source, $target)
    {
        $added = 0;
        $updated = 0;

        foreach ($source as $key => $value) {
            if (!isset($target[$key])) {
                $target[$key] = $value;
                $added++;
            } elseif (is_array($value) && is_array($target[$key])) {
                $nestedResult = $this->syncNestedTranslations($value, $target[$key]);
                $added += $nestedResult['added'];
                $updated += $nestedResult['updated'];
            }
        }

        return ['added' => $added, 'updated' => $updated];
    }

    /**
     * Salva un file di traduzione
     */
    private function saveTranslationFile($filePath, $translations)
    {
        $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";
        file_put_contents($filePath, $content);
    }

    /**
     * Ottiene le statistiche per una lingua
     */
    private function getLanguageStats($language)
    {
        $translationFiles = $this->getTranslationFiles();
        $totalKeys = 0;
        $translatedKeys = 0;
        $missingKeys = 0;

        foreach ($translationFiles as $fileKey => $fileDisplayName) {
            $referenceTranslations = $this->getTranslations('it', $fileKey);
            $translations = $this->getTranslations($language, $fileKey);

            // Assicurati che siano sempre array
            $referenceTranslations = is_array($referenceTranslations) ? $referenceTranslations : [];
            $translations = is_array($translations) ? $translations : [];

            // Conta le chiavi totali (quelle italiane)
            $totalKeys += count($referenceTranslations);

            // Conta le chiavi tradotte e mancanti
            foreach ($referenceTranslations as $key => $referenceValue) {
                $translationValue = $translations[$key] ?? '';

                // Se il valore è un array, convertilo in stringa
                if (is_array($translationValue)) {
                    $translationValue = json_encode($translationValue, JSON_UNESCAPED_UNICODE);
                }

                if (isset($translations[$key]) && !empty(trim($translationValue))) {
                    $translatedKeys++;
                } else {
                    $missingKeys++;
                }
            }
        }

        return [
            'total_keys' => $totalKeys,
            'translated_keys' => $translatedKeys,
            'missing_keys' => $missingKeys,
            'progress_percentage' => $totalKeys > 0 ? round(($translatedKeys / $totalKeys) * 100, 1) : 0
        ];
    }


    /**
     * Pulisce la cache delle traduzioni
     */
    public function clearCache()
    {
        try {
            // Pulisce la cache delle traduzioni di Laravel
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');

            return response()->json([
                'success' => true,
                'message' => __('admin_general.cache_cleared_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin_general.cache_clear_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostra i testi hardcoded trovati nel progetto
     */
    public function hardcoded()
    {
        $hardcodedTexts = $this->findHardcodedTexts();
        $languages = $this->getAvailableLanguages();

        return view('admin.translations.hardcoded', compact('hardcodedTexts', 'languages'));
    }

    /**
     * Converte un testo hardcoded in una chiave di traduzione
     */
    public function convertToKey(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:500',
            'suggested_key' => 'required|string|max:255',
            'file' => 'required|string|max:100',
            'language' => 'required|string|size:2'
        ]);

        $text = $request->text;
        $suggestedKey = $request->suggested_key;
        $file = $request->file;
        $language = $request->language;

        try {
            // Aggiungi la traduzione al file specificato
            $this->addTranslationToFile($language, $file, $suggestedKey, $text);

            return response()->json([
                'success' => true,
                'message' => __('admin_general.hardcoded_converted_success'),
                'key' => $suggestedKey
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin_general.hardcoded_convert_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Trova tutti i testi hardcoded nel progetto
     */
    private function findHardcodedTexts()
    {
        $hardcodedTexts = [];
        $paths = [
            'resources/views',
            'resources/js',
            'app/Http/Controllers',
            'app/Models'
        ];

        foreach ($paths as $path) {
            $fullPath = base_path($path);
            if (is_dir($fullPath)) {
                $files = $this->scanDirectory($fullPath);
                foreach ($files as $file) {
                    $texts = $this->extractHardcodedFromFile($file);
                    $hardcodedTexts = array_merge($hardcodedTexts, $texts);
                }
            }
        }

        // Rimuovi duplicati e filtra
        return $this->filterAndCleanHardcodedTexts($hardcodedTexts);
    }

    /**
     * Scansiona una directory ricorsivamente
     */
    private function scanDirectory($directory)
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && in_array($file->getExtension(), ['php', 'blade.php', 'js', 'vue'])) {
                // Escludi file nella root di views tranne home.blade.php
                if ($this->shouldExcludeFile($file->getPathname())) {
                    continue;
                }
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    /**
     * Verifica se un file deve essere escluso dalla scansione
     */
    private function shouldExcludeFile($filePath)
    {
        // Escludi file nella root di views tranne home.blade.php
        $viewsRoot = base_path('resources/views');
        $relativePath = str_replace($viewsRoot . DIRECTORY_SEPARATOR, '', $filePath);

        // Se il file è nella root di views (non in sottocartelle)
        if (!strpos($relativePath, DIRECTORY_SEPARATOR) && strpos($filePath, $viewsRoot) === 0) {
            // Escludi tutti i file tranne home.blade.php
            if (basename($filePath) !== 'home.blade.php') {
                return true;
            }
        }

        return false;
    }

    /**
     * Estrae testi hardcoded da un file
     */
    private function extractHardcodedFromFile($filePath)
    {
        $content = file_get_contents($filePath);
        $texts = [];
        $relativePath = str_replace(base_path() . '/', '', $filePath);

        // Pattern migliorati per trovare SOLO testi che devono essere tradotti
        $patterns = [
            // Testi in elementi HTML specifici (solo quelli che contengono testo visibile)
            '/<(h[1-6]|p|span|div|label|button|a)[^>]*>([^<{]+)<\/(h[1-6]|p|span|div|label|button|a)>/',
            // Testi in placeholder, title, alt, aria-label (solo se contengono lettere)
            '/(placeholder|title|alt|aria-label)\s*=\s*["\']([^"\']*[a-zA-Z][^"\']*)["\']/',
            // Testi in option (solo se non sono valori)
            '/<option[^>]*>([^<{]+)<\/option>/',
            // Testi in th, td (solo se non sono classi CSS)
            '/<(th|td)[^>]*>([^<{]+)<\/(th|td)>/',
            // Testi in small, em, strong
            '/<(small|em|strong)[^>]*>([^<{]+)<\/(small|em|strong)>/',
            // Testi in li (solo se contengono lettere)
            '/<li[^>]*>([^<{]+)<\/li>/',
            // Testi in caption
            '/<caption[^>]*>([^<{]+)<\/caption>/',
            // Testi in legend
            '/<legend[^>]*>([^<{]+)<\/legend>/',
            // Testi in figcaption
            '/<figcaption[^>]*>([^<{]+)<\/figcaption>/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $content, $matches)) {
                // Controlla se ci sono abbastanza gruppi di cattura
                if (isset($matches[2])) {
                    foreach ($matches[2] as $index => $match) {
                        $text = trim($match);
                        if ($this->isValidHardcodedText($text)) {
                            $texts[] = [
                                'text' => $text,
                                'file' => $relativePath,
                                'line' => $this->getLineNumber($content, $text),
                                'suggested_key' => $this->generateSuggestedKey($text, $relativePath)
                            ];
                        }
                    }
                }
            }
        }

        return $texts;
    }

    /**
     * Verifica se un testo è valido per la traduzione
     */
    private function isValidHardcodedText($text)
    {
        // Filtra testi troppo corti o troppo lunghi
        if (strlen($text) < 3 || strlen($text) > 200) {
            return false;
        }

        // Filtra testi che contengono solo numeri, simboli o spazi
        if (!preg_match('/[a-zA-Z]/', $text)) {
            return false;
        }

        // Filtra testi che sembrano codice CSS/HTML
        if (preg_match('/^[a-z-]+$/', $text) || preg_match('/^[A-Z_]+$/', $text)) {
            return false;
        }

        // Filtra classi CSS comuni
        if (preg_match('/^(btn|col-|text-|bg-|border-|rounded-|shadow-|m-|p-|d-|flex|grid|row|container|card|table|form|input|select|textarea|nav|menu|dropdown|modal|alert|badge|progress|spinner|loading|icon|fa-|ph-|ti-)/', $text)) {
            return false;
        }

        // Filtra attributi HTML
        if (preg_match('/^(id|class|style|data-|aria-|role|tabindex|disabled|readonly|required|checked|selected|multiple|autofocus|autocomplete|pattern|min|max|step|placeholder|title|alt|src|href|target|rel|type|name|value|method|action|enctype|novalidate)/', $text)) {
            return false;
        }

        // Filtra valori di input comuni
        if (preg_match('/^(text|password|email|number|tel|url|search|date|time|datetime|checkbox|radio|submit|button|reset|file|hidden|image|color|range|week|month)/', $text)) {
            return false;
        }

        // Filtra testi che sembrano variabili PHP o Blade
        if (preg_match('/^\$[a-zA-Z_]/', $text) || preg_match('/^{{.*}}$/', $text) || preg_match('/^@[a-zA-Z]/', $text)) {
            return false;
        }

        // Filtra testi che contengono solo caratteri speciali
        if (preg_match('/^[^a-zA-Z0-9\s]+$/', $text)) {
            return false;
        }

        // Filtra testi che sembrano percorsi o URL
        if (preg_match('/^[\/\\\\]|^https?:\/\/|^www\.|^[a-zA-Z]:\\\\/', $text)) {
            return false;
        }

        // Filtra testi che sembrano ID o codici
        if (preg_match('/^[a-zA-Z0-9_-]{8,}$/', $text) && !preg_match('/\s/', $text)) {
            return false;
        }

        // Filtra testi che contengono solo una parola e sembrano tecnici
        $singleWords = ['div', 'span', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'a', 'img', 'br', 'hr', 'ul', 'ol', 'li', 'table', 'tr', 'td', 'th', 'form', 'input', 'button', 'select', 'option', 'textarea', 'label', 'fieldset', 'legend', 'nav', 'header', 'footer', 'main', 'section', 'article', 'aside', 'figure', 'figcaption', 'blockquote', 'cite', 'code', 'pre', 'kbd', 'samp', 'var', 'mark', 'small', 'sub', 'sup', 'del', 'ins', 's', 'u', 'i', 'b', 'em', 'strong'];
        if (in_array(strtolower($text), $singleWords)) {
            return false;
        }

        // Filtra testi che sembrano nomi di file o estensioni
        if (preg_match('/\.(php|js|css|html|htm|blade|vue|jsx|tsx|json|xml|yaml|yml|md|txt|log|sql|env|ini|conf|config)$/', $text)) {
            return false;
        }

        // Filtra testi che sembrano già essere chiavi di traduzione
        if (preg_match('/^[a-z_]+\.[a-z_]+$/', $text) || preg_match('/^[a-z]+\.[a-z]+\.[a-z_]+$/', $text)) {
            return false;
        }

        // Filtra testi che contengono solo numeri e simboli
        if (preg_match('/^[0-9\s\-_.,:;!?()]+$/', $text)) {
            return false;
        }

        // Filtra testi che sembrano indirizzi email o URL
        if (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $text) || preg_match('/^https?:\/\/|^www\./', $text)) {
            return false;
        }

        // Filtra testi che sembrano date o orari
        if (preg_match('/^\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4}$/', $text) || preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', $text)) {
            return false;
        }

        // Filtra testi che sembrano numeri di telefono
        if (preg_match('/^[\+]?[0-9\s\-\(\)]{8,}$/', $text)) {
            return false;
        }

        // Filtra testi che contengono solo caratteri speciali comuni
        if (preg_match('/^[&<>"\'\/\\\]+$/', $text)) {
            return false;
        }

        // Filtra testi che sembrano messaggi di debug o log
        if (preg_match('/^(Debug|Error|Warning|Info|Log|Test|TODO|FIXME|NOTE|HACK)/i', $text)) {
            return false;
        }

        // Filtra testi che contengono solo numeri e simboli di punteggiatura
        if (preg_match('/^[0-9\s\-_.,:;!?()\[\]{}]+$/', $text)) {
            return false;
        }

        // Filtra testi che sembrano nomi di variabili o funzioni
        if (preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $text) && strlen($text) < 20) {
            return false;
        }

        // Filtra testi che contengono solo caratteri maiuscoli e sembrano codici
        if (preg_match('/^[A-Z0-9_\-]+$/', $text) && strlen($text) > 3) {
            return false;
        }

        // Filtra testi che sembrano percorsi di file
        if (preg_match('/^[a-zA-Z0-9_\-\.\/\\\\]+$/', $text) && (strpos($text, '.') !== false || strpos($text, '/') !== false || strpos($text, '\\') !== false)) {
            return false;
        }

        return true;
    }

    /**
     * Genera una chiave suggerita per un testo
     */
    private function generateSuggestedKey($text, $file)
    {
        // Estrai il nome del file senza estensione
        $fileName = pathinfo($file, PATHINFO_FILENAME);
        $fileName = str_replace('.blade', '', $fileName);

        // Pulisci il testo per creare la chiave
        $cleanText = strtolower($text);
        $cleanText = preg_replace('/[^a-z0-9\s]/', '', $cleanText);
        $cleanText = preg_replace('/\s+/', '_', trim($cleanText));
        $cleanText = substr($cleanText, 0, 50); // Limita la lunghezza

        return $fileName . '.' . $cleanText;
    }

    /**
     * Ottiene il numero di riga di un testo nel file
     */
    private function getLineNumber($content, $text)
    {
        $lines = explode("\n", $content);
        foreach ($lines as $lineNumber => $line) {
            if (strpos($line, $text) !== false) {
                return $lineNumber + 1;
            }
        }
        return 1;
    }

    /**
     * Filtra e pulisce i testi hardcoded
     */
    private function filterAndCleanHardcodedTexts($texts)
    {
        $uniqueTexts = [];
        $seenTexts = [];

        foreach ($texts as $text) {
            $key = $text['text'] . '|' . $text['file'];
            if (!isset($seenTexts[$key])) {
                $seenTexts[$key] = true;
                $uniqueTexts[] = $text;
            }
        }

        // Ordina per file e poi per testo
        usort($uniqueTexts, function($a, $b) {
            if ($a['file'] === $b['file']) {
                return strcmp($a['text'], $b['text']);
            }
            return strcmp($a['file'], $b['file']);
        });

        return $uniqueTexts;
    }

    /**
     * Aggiunge una traduzione a un file specifico
     */
    private function addTranslationToFile($language, $file, $key, $value)
    {
        $filePath = lang_path($language . '/' . $file . '.php');

        // Carica le traduzioni esistenti
        $translations = [];
        if (file_exists($filePath)) {
            $translations = include $filePath;
            if (!is_array($translations)) {
                $translations = [];
            }
        }

        // Aggiungi la nuova traduzione
        $translations[$key] = $value;

        // Salva il file
        $this->saveTranslations($language, $file, $translations);
    }

    /**
     * Escapa una stringa per PHP
     */
    private function escapePhpString($string)
    {
        return addslashes($string);
    }

    /**
     * Test API connection
     */
    public function testApi(Request $request)
    {
        $provider = $request->input('provider', 'google');
        $apiKey = $request->input('api_key');

        try {
            $translationService = new TranslationApiService($provider, $apiKey);
            $result = $translationService->testConnection();

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Translate using API
     */
    public function translateWithApi(Request $request)
    {
        $request->validate([
            'language' => 'required|string|max:2',
            'provider' => 'required|string',
            'api_key' => 'required|string',
            'file' => 'nullable|string',
            'force' => 'boolean'
        ]);

        $language = $request->input('language');
        $provider = $request->input('provider');
        $apiKey = $request->input('api_key');
        $file = $request->input('file');
        $force = $request->input('force', false);

        try {
            $translationService = new TranslationApiService($provider, $apiKey);

            // Test connection first
            $testResult = $translationService->testConnection();
            if (!$testResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'API connection failed: ' . $testResult['message']
                ], 400);
            }

            // Execute translation command
            $command = "php artisan translations:api-translate {$language} --provider={$provider} --api-key={$apiKey}";
            if ($file) {
                $command .= " --file={$file}";
            }
            if ($force) {
                $command .= " --force";
            }

            $output = [];
            $returnCode = 0;
            exec($command . ' 2>&1', $output, $returnCode);

            if ($returnCode === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Translation completed successfully',
                    'output' => implode("\n", $output)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Translation failed',
                    'output' => implode("\n", $output)
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available providers
     */
    public function getProviders()
    {
        $translationService = new TranslationApiService();
        $providers = $translationService->getProviders();
        $supportedLanguages = $translationService->getSupportedLanguages();

        return response()->json([
            'providers' => $providers,
            'supported_languages' => $supportedLanguages
        ]);
    }

    /**
     * Get translation status for API
     */
    public function getTranslationStatus(Request $request)
    {
        $language = $request->input('language', 'en');
        $stats = $this->getLanguageStats($language);

        return response()->json([
            'language' => $language,
            'stats' => $stats,
            'completion_percentage' => $stats['total_keys'] > 0 ?
                round(($stats['translated_keys'] / $stats['total_keys']) * 100, 2) : 0
        ]);
    }

    /**
     * Translate a specific page/file
     */
    public function translatePage(Request $request)
    {
        $request->validate([
            'language' => 'required|string|max:2',
            'file' => 'required|string',
            'provider' => 'required|string',
            'api_key' => 'nullable|string',
            'force' => 'boolean'
        ]);

        $language = $request->input('language');
        $file = $request->input('file');
        $provider = $request->input('provider');
        $apiKey = $request->input('api_key');
        $force = $request->input('force', false);

        try {
            // Execute translation command with proper encoding
            $command = "php artisan translations:translate-page {$language} {$file} --provider={$provider}";
            if ($apiKey) {
                $command .= " --api-key={$apiKey}";
            }
            if ($force) {
                $command .= " --force";
            }

            $output = [];
            $returnCode = 0;

            // Use exec for simpler handling
            $output = [];
            $returnCode = 0;

            // Execute command and capture output
            exec($command . ' 2>&1', $output, $returnCode);
            $fullOutput = implode("\n", $output);

            // Clean the output
            $fullOutput = $this->safeConvertEncoding($fullOutput);

            // Check if translation was successful (look for success indicators in output)
            $isSuccess = strpos($fullOutput, 'Translation completed!') !== false ||
                        strpos($fullOutput, 'Keys translated:') !== false ||
                        strpos($fullOutput, '🎉 Translation completed!') !== false ||
                        $returnCode === 0;

            if ($isSuccess) {
                return response()->json([
                    'success' => true,
                    'message' => 'Translation completed successfully',
                    'output' => $fullOutput,
                    'file' => $file,
                    'language' => $language
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Translation failed: ' . $fullOutput,
                    'output' => $fullOutput
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available translation files
     */
    public function getAvailableFiles()
    {
        $files = $this->getTranslationFiles();
        $fileList = [];

        foreach ($files as $file) {
            $fileList[] = [
                'name' => $file,
                'display_name' => ucfirst(str_replace('.php', '', $file))
            ];
        }

        return response()->json([
            'files' => $fileList
        ]);
    }

    /**
     * Safely convert encoding with fallback
     */
    private function safeConvertEncoding($text)
    {
        if (empty($text)) {
            return '';
        }

        try {
            // Remove any BOM
            $text = preg_replace('/^\xEF\xBB\xBF/', '', $text);

            // Check if already valid UTF-8
            if (mb_check_encoding($text, 'UTF-8')) {
                return $text;
            }

            // Try to convert from auto-detected encoding
            $converted = mb_convert_encoding($text, 'UTF-8', 'auto');
            if ($converted !== false && mb_check_encoding($converted, 'UTF-8')) {
                return $converted;
            }

            // Try Windows-1252 encoding (common on Windows)
            $converted = mb_convert_encoding($text, 'UTF-8', 'Windows-1252');
            if ($converted !== false && mb_check_encoding($converted, 'UTF-8')) {
                return $converted;
            }

            // Fallback: force UTF-8 conversion and clean
            $converted = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
            return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $converted);
        } catch (\Exception $e) {
            // If all else fails, return cleaned text
            return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        }
    }

    /**
     * Segna una chiave come revisionata
     */
    public function markAsReviewed(Request $request)
    {
        $request->validate([
            'language' => 'required|string',
            'file' => 'required|string',
            'key' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        $review = TranslationReview::updateOrCreate(
            [
                'language' => $request->language,
                'file' => $request->file,
                'key' => $request->key,
            ],
            [
                'is_reviewed' => true,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'notes' => $request->notes,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => __('admin_general.key_marked_as_reviewed'),
            'review' => $review,
        ]);
    }

    /**
     * Rimuovi la revisione da una chiave
     */
    public function unmarkAsReviewed(Request $request)
    {
        $request->validate([
            'language' => 'required|string',
            'file' => 'required|string',
            'key' => 'required|string',
        ]);

        $review = TranslationReview::where('language', $request->language)
            ->where('file', $request->file)
            ->where('key', $request->key)
            ->first();

        if ($review) {
            $review->unmarkAsReviewed();
        }

        return response()->json([
            'success' => true,
            'message' => __('admin_general.key_unmarked_as_reviewed'),
        ]);
    }

    /**
     * Auto-save di una traduzione
     */
    public function autoSave(Request $request)
    {
        $request->validate([
            'language' => 'required|string',
            'file' => 'required|string',
            'key' => 'required|string',
            'value' => 'nullable|string',
        ]);

        try {
            // Ottieni le traduzioni esistenti
            $translations = $this->getTranslations($request->language, $request->file);
            
            // Aggiorna la chiave specifica
            $translations[$request->key] = $request->value;
            
            // Salva
            $this->saveTranslations($request->language, $request->file, $translations);

            return response()->json([
                'success' => true,
                'message' => __('admin_general.translation_auto_saved'),
                'timestamp' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin_general.auto_save_error') . ': ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ottieni statistiche dettagliate per una lingua e file
     */
    public function getDetailedStats(Request $request)
    {
        $language = $request->get('language');
        $file = $request->get('file');

        if (!$this->languageExists($language)) {
            return response()->json(['error' => 'Language not found'], 404);
        }

        $referenceTranslations = $this->getTranslations('it', $file);
        $translations = $this->getTranslations($language, $file);
        
        // Ottieni le revisioni
        $reviews = TranslationReview::forLanguageAndFile($language, $file)->get()->keyBy('key');

        $stats = [
            'total_keys' => count($referenceTranslations),
            'translated_keys' => count(array_filter($translations, fn($v) => !empty($v))),
            'missing_keys' => count($referenceTranslations) - count(array_filter($translations, fn($v) => !empty($v))),
            'reviewed_keys' => $reviews->where('is_reviewed', true)->count(),
            'not_reviewed_keys' => count($referenceTranslations) - $reviews->where('is_reviewed', true)->count(),
            'progress_percentage' => count($referenceTranslations) > 0 
                ? round((count(array_filter($translations, fn($v) => !empty($v))) / count($referenceTranslations)) * 100, 1) 
                : 0,
            'review_percentage' => count($referenceTranslations) > 0 
                ? round(($reviews->where('is_reviewed', true)->count() / count($referenceTranslations)) * 100, 1) 
                : 0,
        ];

        return response()->json($stats);
    }

    /**
     * Mostra l'interfaccia smart per le traduzioni
     */
    public function showSmart($language)
    {
        if (!$this->languageExists($language)) {
            abort(404, __('admin_general.language_not_found_error'));
        }

        $translationFiles = $this->getTranslationFiles();
        $selectedFile = request('file', 'admin');

        // Ottieni tutte le chiavi italiane come riferimento
        $referenceTranslations = $this->getTranslations('it', $selectedFile);
        $translations = $this->getTranslations($language, $selectedFile);

        // Ottieni le revisioni
        $reviews = TranslationReview::forLanguageAndFile($language, $selectedFile)
            ->get()
            ->keyBy('key');

        // Prepara i dati
        $translationData = [];
        foreach ($referenceTranslations as $key => $referenceValue) {
            $translationValue = $translations[$key] ?? '';
            $review = $reviews->get($key);

            // Converti array in JSON per visualizzazione
            if (is_array($referenceValue)) {
                $referenceValue = json_encode($referenceValue, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            }
            if (is_array($translationValue)) {
                $translationValue = json_encode($translationValue, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            }

            $translationData[$key] = [
                'reference' => $referenceValue,
                'translation' => $translationValue,
                'is_translated' => !empty($translations[$key]),
                'is_missing' => empty($translations[$key]),
                'is_reviewed' => $review ? $review->is_reviewed : false,
                'reviewed_at' => $review ? $review->reviewed_at?->format('d/m/Y H:i') : null,
                'reviewed_by_name' => $review && $review->reviewer ? $review->reviewer->getDisplayName() : null,
                'notes' => $review ? $review->notes : null,
            ];
        }

        // Statistiche
        $stats = [
            'total_keys' => count($referenceTranslations),
            'translated_keys' => count(array_filter($translationData, fn($item) => $item['is_translated'])),
            'missing_keys' => count(array_filter($translationData, fn($item) => $item['is_missing'])),
            'reviewed_keys' => count(array_filter($translationData, fn($item) => $item['is_reviewed'])),
            'not_reviewed_keys' => count($referenceTranslations) - count(array_filter($translationData, fn($item) => $item['is_reviewed'])),
            'progress_percentage' => count($referenceTranslations) > 0 
                ? round((count(array_filter($translationData, fn($item) => $item['is_translated'])) / count($referenceTranslations)) * 100, 1) 
                : 0,
            'review_percentage' => count($referenceTranslations) > 0 
                ? round((count(array_filter($translationData, fn($item) => $item['is_reviewed'])) / count($referenceTranslations)) * 100, 1) 
                : 0,
        ];

        return view('admin.translations.smart', compact(
            'language',
            'translationFiles',
            'selectedFile',
            'translationData',
            'stats'
        ));
    }

    /**
     * Trova dove viene utilizzata una chiave di traduzione
     */
    public function findKeyUsage(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'file' => 'required|string',
        ]);

        $key = $request->key;
        $file = $request->file;
        $usage = [];
        $foundPositions = []; // Per evitare duplicati

        try {
            // Cerca nei file Blade
            $bladeFiles = glob(resource_path('views/**/*.blade.php'));
            foreach ($bladeFiles as $bladeFile) {
                $content = file_get_contents($bladeFile);
                $relativePath = str_replace(resource_path('views/'), '', $bladeFile);
                
                // Cerca pattern più flessibili
                $searchPattern = $file . '.' . $key;
                
                // Pattern per __('file.key'), __("file.key"), trans('file.key'), etc.
                $patterns = [
                    "__\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*\)",
                    "__\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*,\s*\[",  // Con parametri
                    "trans\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*\)",
                    "trans\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*,\s*\[",  // Con parametri
                    "@lang\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*\)",
                    "@lang\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*,\s*\[",  // Con parametri
                    "{{\s*__\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*\)\s*}}",
                    "{{\s*__\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*,\s*\[",  // Con parametri
                    "{{\s*trans\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*\)\s*}}",
                    "{{\s*trans\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*,\s*\[",  // Con parametri
                ];

                foreach ($patterns as $pattern) {
                    if (preg_match_all('/' . $pattern . '/i', $content, $matches, PREG_OFFSET_CAPTURE)) {
                        foreach ($matches[0] as $match) {
                            $line = substr_count(substr($content, 0, $match[1]), "\n") + 1;
                            
                            // Crea una chiave unica per evitare duplicati
                            $uniqueKey = $relativePath . ':' . $line;
                            
                            if (!in_array($uniqueKey, $foundPositions)) {
                                $foundPositions[] = $uniqueKey;
                                $usage[] = [
                                    'file' => $relativePath,
                                    'line' => $line,
                                    'type' => 'blade',
                                    'context' => $this->getLineContext($content, $match[1])
                                ];
                            }
                        }
                    }
                }
            }

            // Cerca nei file PHP
            $phpFiles = array_merge(
                glob(app_path('**/*.php')),
                glob(config_path('*.php'))
            );
            
            foreach ($phpFiles as $phpFile) {
                $content = file_get_contents($phpFile);
                $relativePath = str_replace(base_path() . '/', '', $phpFile);
                
                // Cerca pattern più flessibili per PHP
                $searchPattern = $file . '.' . $key;
                
                $patterns = [
                    "__\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*\)",
                    "trans\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*\)",
                ];

                foreach ($patterns as $pattern) {
                    if (preg_match_all('/' . $pattern . '/i', $content, $matches, PREG_OFFSET_CAPTURE)) {
                        foreach ($matches[0] as $match) {
                            $line = substr_count(substr($content, 0, $match[1]), "\n") + 1;
                            
                            // Crea una chiave unica per evitare duplicati
                            $uniqueKey = $relativePath . ':' . $line;
                            
                            if (!in_array($uniqueKey, $foundPositions)) {
                                $foundPositions[] = $uniqueKey;
                                $usage[] = [
                                    'file' => $relativePath,
                                    'line' => $line,
                                    'type' => 'php',
                                    'context' => $this->getLineContext($content, $match[1])
                                ];
                            }
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'usage' => $usage,
                'count' => count($usage)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la ricerca: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ottieni il contesto della linea (3 righe prima e dopo)
     */
    private function getLineContext($content, $position)
    {
        $lines = explode("\n", $content);
        $lineNumber = substr_count(substr($content, 0, $position), "\n");
        
        $start = max(0, $lineNumber - 3);
        $end = min(count($lines) - 1, $lineNumber + 3);
        
        $context = [];
        for ($i = $start; $i <= $end; $i++) {
            $context[] = [
                'number' => $i + 1,
                'content' => $lines[$i],
                'highlight' => $i === $lineNumber
            ];
        }
        
        return $context;
    }

    /**
     * Trova tutte le chiavi non utilizzate in un file
     */
    public function findUnusedKeys(Request $request)
    {
        $request->validate([
            'file' => 'required|string',
        ]);

        $file = $request->file;
        $unusedKeys = [];

        try {
            // Ottieni tutte le chiavi dal file di traduzione
            $translations = $this->getTranslations('it', $file);
            
            foreach ($translations as $key => $value) {
                // Cerca l'utilizzo di questa chiave
                $usage = $this->searchKeyUsage($file, $key);
                
                if (empty($usage)) {
                    $unusedKeys[] = [
                        'key' => $key,
                        'value' => $value,
                        'file' => $file
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'unused_keys' => $unusedKeys,
                'count' => count($unusedKeys),
                'total_keys' => count($translations)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la ricerca: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cerca l'utilizzo di una singola chiave
     */
    private function searchKeyUsage($file, $key)
    {
        $usage = [];
        $searchPattern = $file . '.' . $key;

        // Cerca nei file Blade
        $bladeFiles = glob(resource_path('views/**/*.blade.php'));
        foreach ($bladeFiles as $bladeFile) {
            $content = file_get_contents($bladeFile);
            $relativePath = str_replace(resource_path('views/'), '', $bladeFile);
            
            $patterns = [
                "__\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*\)",
                "__\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*,\s*\[",  // Con parametri
                "trans\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*\)",
                "trans\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*,\s*\[",  // Con parametri
                "@lang\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*\)",
                "@lang\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*,\s*\[",  // Con parametri
                "{{\s*__\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*\)\s*}}",
                "{{\s*__\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*,\s*\[",  // Con parametri
                "{{\s*trans\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*\)\s*}}",
                "{{\s*trans\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*,\s*\[",  // Con parametri
            ];

            foreach ($patterns as $pattern) {
                if (preg_match('/' . $pattern . '/i', $content)) {
                    $usage[] = [
                        'file' => $relativePath,
                        'type' => 'blade'
                    ];
                    break; // Trovata almeno un'occorrenza, passa alla prossima chiave
                }
            }
        }

        // Cerca nei file PHP
        $phpFiles = array_merge(
            glob(app_path('**/*.php')),
            glob(config_path('*.php'))
        );
        
        foreach ($phpFiles as $phpFile) {
            $content = file_get_contents($phpFile);
            $relativePath = str_replace(base_path() . '/', '', $phpFile);
            
            $patterns = [
                "__\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*\)",
                "trans\s*\(\s*['\"]" . preg_quote($searchPattern, '/') . "['\"]\s*\)",
            ];

            foreach ($patterns as $pattern) {
                if (preg_match('/' . $pattern . '/i', $content)) {
                    $usage[] = [
                        'file' => $relativePath,
                        'type' => 'php'
                    ];
                    break; // Trovata almeno un'occorrenza, passa alla prossima chiave
                }
            }
        }

        return $usage;
    }

    /**
     * Crea un backup delle chiavi prima della rimozione
     */
    private function createBackup($file, $keysToRemove)
    {
        $translations = $this->getTranslations('it', $file);
        $removedKeys = [];
        
        foreach ($keysToRemove as $key) {
            if (isset($translations[$key])) {
                $removedKeys[$key] = $translations[$key];
            }
        }
        
        if (!empty($removedKeys)) {
            $backupDir = storage_path('app/translation-backups');
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $backupFile = $backupDir . '/' . $file . '_removed_' . date('Y-m-d_H-i-s') . '.json';
            file_put_contents($backupFile, json_encode($removedKeys, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * Rimuove le chiavi non utilizzate
     */
    public function removeUnusedKeys(Request $request)
    {
        $request->validate([
            'file' => 'required|string',
            'keys' => 'required|array',
        ]);

        $file = $request->file;
        $keysToRemove = $request->keys;

        try {
            // Crea un backup prima della rimozione
            $this->createBackup($file, $keysToRemove);
            
            // Ottieni le traduzioni attuali
            $translations = $this->getTranslations('it', $file);
            
            // Rimuovi le chiavi specificate
            foreach ($keysToRemove as $key) {
                unset($translations[$key]);
            }
            
            // Salva le traduzioni aggiornate
            $this->saveTranslations('it', $file, $translations);
            
            return response()->json([
                'success' => true,
                'message' => 'Chiavi rimosse con successo',
                'removed_count' => count($keysToRemove)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la rimozione: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crea una nuova chiave di traduzione
     */
    public function createKey(Request $request)
    {
        $request->validate([
            'file' => 'required|string',
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        $file = $request->file;
        $key = $request->key;
        $value = $request->value;

        try {
            // Ottieni le traduzioni attuali
            $translations = $this->getTranslations('it', $file);
            
            // Aggiungi la nuova chiave
            $translations[$key] = $value;
            
            // Salva le traduzioni aggiornate
            $this->saveTranslations('it', $file, $translations);
            
            return response()->json([
                'success' => true,
                'message' => 'Chiave creata con successo',
                'key' => $key,
                'value' => $value
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la creazione: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Trova chiavi mancanti nel codice
     */
    public function findMissingKeys(Request $request)
    {
        $request->validate([
            'file' => 'nullable|string',
        ]);

        $file = $request->file;
        $missingKeys = [];

        try {
            if ($file) {
                // Cerca solo per un file specifico
                $existingKeys = array_keys($this->getTranslations('it', $file));
                $usedKeys = $this->findUsedKeysInCode($file);
                
                foreach ($usedKeys as $key) {
                    if (!in_array($key, $existingKeys)) {
                        $missingKeys[] = [
                            'key' => $key,
                            'file' => $file,
                            'suggested_value' => $this->generateSuggestedValue($key)
                        ];
                    }
                }
            } else {
                // Cerca in TUTTI i file
                $allUsedKeys = $this->findAllUsedKeysInCode();
                
                foreach ($allUsedKeys as $fullKey) {
                    // Salta chiavi dinamiche (con concatenazione)
                    if (strpos($fullKey, ' . ') !== false || strpos($fullKey, '.\'') !== false) {
                        continue;
                    }
                    
                    // Dividi file.key
                    $parts = explode('.', $fullKey, 2);
                    if (count($parts) !== 2) {
                        continue;
                    }
                    
                    list($keyFile, $key) = $parts;
                    
                    // Verifica se il file esiste
                    if (!$this->languageExists('it') || !in_array($keyFile, $this->getTranslationFiles())) {
                        continue;
                    }
                    
                    // Verifica se la chiave esiste
                    $translations = $this->getTranslations('it', $keyFile);
                    if (!isset($translations[$key])) {
                        $missingKeys[] = [
                            'key' => $key,
                            'file' => $keyFile,
                            'full_key' => $fullKey,
                            'suggested_value' => $this->generateSuggestedValue($key)
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'missing_keys' => $missingKeys,
                'count' => count($missingKeys)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la ricerca: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Trova TUTTE le chiavi utilizzate in TUTTO il codice
     */
    private function findAllUsedKeysInCode()
    {
        $allKeys = [];
        
        // Cerca nei file Blade
        $bladeFiles = glob(resource_path('views/**/*.blade.php'));
        foreach ($bladeFiles as $bladeFile) {
            $content = file_get_contents($bladeFile);
            
            // Pattern per trovare QUALSIASI chiave di traduzione
            $patterns = [
                "/__\s*\(\s*['\"]([a-zA-Z0-9_]+\.[a-zA-Z0-9_\.]+)['\",\s\)]/",
                "/trans\s*\(\s*['\"]([a-zA-Z0-9_]+\.[a-zA-Z0-9_\.]+)['\",\s\)]/",
                "/@lang\s*\(\s*['\"]([a-zA-Z0-9_]+\.[a-zA-Z0-9_\.]+)['\",\s\)]/",
            ];
            
            foreach ($patterns as $pattern) {
                if (preg_match_all($pattern, $content, $matches)) {
                    foreach ($matches[1] as $fullKey) {
                        $allKeys[] = $fullKey;
                    }
                }
            }
        }
        
        // Cerca nei file PHP
        $phpFiles = array_merge(
            glob(app_path('**/*.php')),
            glob(config_path('*.php'))
        );
        
        foreach ($phpFiles as $phpFile) {
            $content = file_get_contents($phpFile);
            
            $patterns = [
                "/__\s*\(\s*['\"]([a-zA-Z0-9_]+\.[a-zA-Z0-9_\.]+)['\",\s\)]/",
                "/trans\s*\(\s*['\"]([a-zA-Z0-9_]+\.[a-zA-Z0-9_\.]+)['\",\s\)]/",
            ];
            
            foreach ($patterns as $pattern) {
                if (preg_match_all($pattern, $content, $matches)) {
                    foreach ($matches[1] as $fullKey) {
                        $allKeys[] = $fullKey;
                    }
                }
            }
        }
        
        return array_unique($allKeys);
    }

    /**
     * Trova tutte le chiavi utilizzate nel codice per un file specifico
     */
    private function findUsedKeysInCode($file)
    {
        $usedKeys = [];
        
        // Cerca nei file Blade
        $bladeFiles = glob(resource_path('views/**/*.blade.php'));
        foreach ($bladeFiles as $bladeFile) {
            $content = file_get_contents($bladeFile);
            
            // Pattern per trovare tutte le chiavi utilizzate
            $patterns = [
                "/__\s*\(\s*['\"]" . preg_quote($file, '/') . "\.([^'\"\\s,)]+)/",
                "/trans\s*\(\s*['\"]" . preg_quote($file, '/') . "\.([^'\"\\s,)]+)/",
                "/@lang\s*\(\s*['\"]" . preg_quote($file, '/') . "\.([^'\"\\s,)]+)/",
            ];
            
            foreach ($patterns as $pattern) {
                if (preg_match_all($pattern, $content, $matches)) {
                    foreach ($matches[1] as $key) {
                        $usedKeys[] = $key;
                    }
                }
            }
        }
        
        // Cerca nei file PHP
        $phpFiles = array_merge(
            glob(app_path('**/*.php')),
            glob(config_path('*.php'))
        );
        
        foreach ($phpFiles as $phpFile) {
            $content = file_get_contents($phpFile);
            
            $patterns = [
                "/__\s*\(\s*['\"]" . preg_quote($file, '/') . "\.([^'\"\\s,)]+)/",
                "/trans\s*\(\s*['\"]" . preg_quote($file, '/') . "\.([^'\"\\s,)]+)/",
            ];
            
            foreach ($patterns as $pattern) {
                if (preg_match_all($pattern, $content, $matches)) {
                    foreach ($matches[1] as $key) {
                        $usedKeys[] = $key;
                    }
                }
            }
        }
        
        return array_unique($usedKeys);
    }

    /**
     * Genera un valore suggerito per una chiave
     */
    private function generateSuggestedValue($key)
    {
        // Converti la chiave in un valore leggibile
        $value = str_replace('_', ' ', $key);
        $value = ucwords($value);
        
        // Alcune sostituzioni comuni
        $replacements = [
            'welcome' => 'Benvenuto',
            'title' => 'Titolo',
            'description' => 'Descrizione',
            'name' => 'Nome',
            'email' => 'Email',
            'password' => 'Password',
            'save' => 'Salva',
            'cancel' => 'Annulla',
            'delete' => 'Elimina',
            'edit' => 'Modifica',
            'create' => 'Crea',
            'update' => 'Aggiorna',
            'view' => 'Visualizza',
            'search' => 'Cerca',
            'filter' => 'Filtra',
            'sort' => 'Ordina',
            'back' => 'Indietro',
            'next' => 'Avanti',
            'previous' => 'Precedente',
            'submit' => 'Invia',
            'confirm' => 'Conferma',
            'success' => 'Successo',
            'error' => 'Errore',
            'warning' => 'Avviso',
            'info' => 'Informazione',
        ];
        
        foreach ($replacements as $search => $replace) {
            $value = str_ireplace($search, $replace, $value);
        }
        
        return $value;
    }
}
