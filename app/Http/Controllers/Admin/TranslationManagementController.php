<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            return back()->withErrors(['language_code' => __('admin.language_exists_error')]);
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
            ->with('success', __('admin.language_created_success'));
    }

    /**
     * Mostra le traduzioni per una lingua specifica
     */
    public function show($language)
    {
        if (!$this->languageExists($language)) {
            abort(404, __('admin.language_not_found_error'));
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
            abort(404, __('admin.language_not_found_error'));
        }

        $file = $request->input('file', 'admin');
        $translations = $request->input('translations', []);

        try {
            $this->saveTranslations($language, $file, $translations);

            return response()->json([
                'success' => true,
                'message' => __('admin.translations_saved_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.save_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina una lingua
     */
    public function destroy($language)
    {
        if ($language === 'it') {
            return back()->withErrors(['error' => __('admin.cannot_delete_italian')]);
        }

        if (!$this->languageExists($language)) {
            abort(404, __('admin.language_not_found_error'));
        }

        $languagePath = lang_path($language);
        if (File::exists($languagePath)) {
            File::deleteDirectory($languagePath);
        }

        return redirect()->route('admin.translations.index')
            ->with('success', __('admin.language_deleted_success'));
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
                'message' => __('admin.language_not_found_error')
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
            'message' => __('admin.sync_completed'),
            'files_updated' => $updatedFiles
        ]);
    }

    /**
     * Copia tutte le traduzioni dall'italiano
     */
    public function copyFromItalian(Request $request, $language)
    {
        if (!$this->languageExists($language)) {
            abort(404, __('admin.language_not_found_error'));
        }

        $file = $request->input('file', 'admin');
        $italianTranslations = $this->getTranslations('it', $file);

        try {
            $this->saveTranslations($language, $file, $italianTranslations);

            return response()->json([
                'success' => true,
                'message' => __('admin.copy_from_italian') . ' ' . __('admin.completed')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.save_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Svuota tutte le traduzioni
     */
    public function clearAll(Request $request, $language)
    {
        if (!$this->languageExists($language)) {
            abort(404, __('admin.language_not_found_error'));
        }

        $file = $request->input('file', 'admin');

        try {
            $this->saveTranslations($language, $file, []);

            return response()->json([
                'success' => true,
                'message' => __('admin.clear_all') . ' ' . __('admin.completed')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.save_error') . ': ' . $e->getMessage()
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
            'admin' => __('admin.file_admin'),
            'auth' => __('admin.file_auth'),
            'common' => __('admin.file_common'),
            'dashboard' => __('admin.file_dashboard'),
            'events' => __('admin.file_events'),
            'videos' => __('admin.file_videos'),
            'carousel' => __('admin.file_carousel'),
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
     * Escapa una stringa per PHP
     */
    private function escapePhpString($string)
    {
        return addslashes($string);
    }
}
