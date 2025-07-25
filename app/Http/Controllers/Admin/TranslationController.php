<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\AutoTranslationService;

class TranslationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Mostra il pannello principale delle traduzioni
     */
    public function index()
    {
        $languages = $this->getAvailableLanguages();
        $translationFiles = $this->getTranslationFiles();

        // Calcola le chiavi mancanti per ogni lingua
        $missingKeys = $this->getMissingKeys($languages, $translationFiles);

        return view('admin.translations.index', compact('languages', 'translationFiles', 'missingKeys'));
    }

    /**
     * Mostra il contenuto di un file di traduzione specifico
     */
    public function show(Request $request, $language, $file)
    {
        $filePath = lang_path("{$language}/{$file}.php");

        if (!File::exists($filePath)) {
            return redirect()->route('admin.translations.index')
                ->with('error', __('admin.translation_file_not_found'));
        }

        $translations = include $filePath;
        $referenceTranslations = $this->getReferenceTranslations($file);

        // Calcola le chiavi mancanti per questa lingua e file specifico
        $missingKeys = $this->getMissingKeysForFile($language, $file, $referenceTranslations, $translations);

        return view('admin.translations.edit', compact('language', 'file', 'translations', 'referenceTranslations', 'missingKeys'));
    }

    /**
     * Salva le traduzioni modificate
     */
    public function update(Request $request, $language, $file)
    {
        $filePath = lang_path("{$language}/{$file}.php");

        if (!File::exists($filePath)) {
            return redirect()->route('admin.translations.index')
                ->with('error', 'File di traduzione non trovato.');
        }

        $translations = $request->input('translations', []);

        // Rimuovi le chiavi vuote
        $translations = array_filter($translations, function($value) {
            return $value !== null && $value !== '';
        });

        // Genera il contenuto PHP
        $phpContent = $this->generatePhpContent($translations, $file);

        try {
            // Crea il backup
            $backupPath = $filePath . '.backup.' . date('Y-m-d-H-i-s');
            File::copy($filePath, $backupPath);

            // Salva il nuovo contenuto
            File::put($filePath, $phpContent);

            // Pulisci la cache delle traduzioni
            if (function_exists('cache')) {
                cache()->forget("translations.{$language}.{$file}");
            }

            Log::info("Traduzioni aggiornate per {$language}/{$file} da " . Auth::user()?->email);

            return redirect()->route('admin.translations.show', [$language, $file])
                ->with('success', 'Traduzioni salvate con successo!');

        } catch (\Exception $e) {
            Log::error("Errore nel salvataggio traduzioni: " . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Errore nel salvataggio delle traduzioni: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Crea una nuova lingua
     */
    public function createLanguage(Request $request)
    {
        $request->validate([
            'language_code' => 'required|string|size:2|unique:languages,code',
            'language_name' => 'required|string|max:50',
        ]);

        $languageCode = strtolower($request->language_code);
        $languagePath = lang_path($languageCode);

        if (File::exists($languagePath)) {
            return redirect()->route('admin.translations.index')
                ->with('error', 'La lingua esiste già.');
        }

        try {
            // Crea la directory
            File::makeDirectory($languagePath, 0755, true);

            // Copia i file di traduzione dall'italiano
            $italianPath = lang_path('it');
            if (File::exists($italianPath)) {
                $files = File::files($italianPath);
                foreach ($files as $file) {
                    $fileName = $file->getFilename();
                    $newPath = $languagePath . '/' . $fileName;
                    File::copy($file->getPathname(), $newPath);
                }
            }

            Log::info("Nuova lingua creata: {$languageCode} da " . Auth::user()?->email);

            return redirect()->route('admin.translations.index')
                ->with('success', "Lingua {$request->language_name} creata con successo!");

        } catch (\Exception $e) {
            Log::error("Errore nella creazione lingua: " . $e->getMessage());

            return redirect()->route('admin.translations.index')
                ->with('error', 'Errore nella creazione della lingua: ' . $e->getMessage());
        }
    }

    /**
     * Elimina una lingua
     */
    public function deleteLanguage(Request $request, $language)
    {
        if ($language === 'it') {
            return redirect()->route('admin.translations.index')
                ->with('error', 'Non puoi eliminare la lingua italiana (lingua di riferimento).');
        }

        $languagePath = lang_path($language);

        if (!File::exists($languagePath)) {
            return redirect()->route('admin.translations.index')
                ->with('error', 'Lingua non trovata.');
        }

        try {
            // Crea un backup prima di eliminare
            $backupPath = storage_path("backups/languages/{$language}_" . date('Y-m-d-H-i-s'));
            File::copyDirectory($languagePath, $backupPath);

            // Elimina la directory
            File::deleteDirectory($languagePath);

            Log::info("Lingua eliminata: {$language} da " . Auth::user()?->email);

            return redirect()->route('admin.translations.index')
                ->with('success', "Lingua {$language} eliminata con successo!");

        } catch (\Exception $e) {
            Log::error("Errore nell'eliminazione lingua: " . $e->getMessage());

            return redirect()->route('admin.translations.index')
                ->with('error', 'Errore nell\'eliminazione della lingua: ' . $e->getMessage());
        }
    }

    /**
     * Ottieni le lingue disponibili
     */
    private function getAvailableLanguages()
    {
        $languages = [];
        $langPath = lang_path();

        if (File::exists($langPath)) {
            $directories = File::directories($langPath);

            foreach ($directories as $directory) {
                $languageCode = basename($directory);
                $languages[$languageCode] = $this->getLanguageName($languageCode);
            }
        }

        return $languages;
    }

    /**
     * Ottieni i file di traduzione disponibili
     */
    private function getTranslationFiles()
    {
        $files = [];
        $italianPath = lang_path('it');

        if (File::exists($italianPath)) {
            $phpFiles = File::glob($italianPath . '/*.php');

            foreach ($phpFiles as $file) {
                $fileName = basename($file, '.php');
                $files[$fileName] = $this->getFileDisplayName($fileName);
            }
        }

        return $files;
    }

    /**
     * Ottieni le traduzioni di riferimento (italiano)
     */
    private function getReferenceTranslations($file)
    {
        $filePath = lang_path("it/{$file}.php");

        if (File::exists($filePath)) {
            return include $filePath;
        }

        return [];
    }

    /**
     * Genera il contenuto PHP per il file di traduzione
     */
    public function generatePhpContent($translations, $file)
    {
        $content = "<?php\n\nreturn [\n\n";

        foreach ($translations as $key => $value) {
            if (is_array($value)) {
                // Gestisci array annidati
                $content .= "    '{$key}' => [\n";
                $content .= $this->generateArrayContent($value, 2);
                $content .= "    ],\n";
            } else {
                // Gestisci stringhe
                $escapedValue = str_replace("'", "\\'", (string)$value);
                $content .= "    '{$key}' => '{$escapedValue}',\n";
            }
        }

        $content .= "\n];\n";

        return $content;
    }

    /**
     * Genera il contenuto per array annidati
     */
    private function generateArrayContent($array, $indent = 0)
    {
        $content = '';
        $spaces = str_repeat('    ', $indent);

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $content .= "{$spaces}'{$key}' => [\n";
                $content .= $this->generateArrayContent($value, $indent + 1);
                $content .= "{$spaces}],\n";
            } else {
                $escapedValue = str_replace("'", "\\'", (string)$value);
                $content .= "{$spaces}'{$key}' => '{$escapedValue}',\n";
            }
        }

        return $content;
    }

    /**
     * Ottieni il nome della lingua
     */
    private function getLanguageName($code)
    {
        $names = [
            'it' => 'Italiano',
            'en' => 'English',
            'es' => 'Español',
            'fr' => 'Français',
            'de' => 'Deutsch',
        ];

        return $names[$code] ?? ucfirst($code);
    }

    /**
     * Ottieni il nome di visualizzazione del file
     */
    private function getFileDisplayName($fileName)
    {
        $names = [
            'auth' => 'Autenticazione',
            'common' => 'Comune',
            'dashboard' => 'Dashboard',
            'events' => 'Eventi',
            'videos' => 'Video',
            'carousel' => 'Carousel',
        ];

        return $names[$fileName] ?? ucfirst($fileName);
    }

    /**
     * Sincronizza tutte le lingue con le chiavi italiane
     */
    public function syncLanguages()
    {
        try {
            $languages = $this->getAvailableLanguages();
            $translationFiles = $this->getTranslationFiles();
            $syncedCount = 0;
            $errors = [];

            foreach ($languages as $languageCode => $languageName) {
                if ($languageCode === 'it') continue; // Salta l'italiano

                foreach ($translationFiles as $file => $displayName) {
                    try {
                        $italianFile = lang_path("it/{$file}.php");
                        $targetFile = lang_path("{$languageCode}/{$file}.php");

                        if (!File::exists($italianFile)) {
                            Log::warning("File italiano non trovato: {$italianFile}");
                            continue;
                        }

                        $italianTranslations = include $italianFile;
                        if (!is_array($italianTranslations)) {
                            Log::warning("File italiano non contiene array valido: {$italianFile}");
                            continue;
                        }

                        $targetTranslations = [];
                        if (File::exists($targetFile)) {
                            $targetTranslations = include $targetFile;
                            if (!is_array($targetTranslations)) {
                                $targetTranslations = [];
                            }
                        }

                        // Aggiungi solo le chiavi mancanti
                        $updated = false;
                        foreach ($italianTranslations as $key => $value) {
                            if (!array_key_exists($key, $targetTranslations)) {
                                $targetTranslations[$key] = ''; // Chiave vuota da tradurre
                                $updated = true;
                            }
                        }

                        if ($updated) {
                            $phpContent = $this->generatePhpContent($targetTranslations, $file);
                            
                            // Crea la directory se non esiste
                            $targetDir = dirname($targetFile);
                            if (!File::exists($targetDir)) {
                                File::makeDirectory($targetDir, 0755, true);
                            }
                            
                            File::put($targetFile, $phpContent);
                            $syncedCount++;
                            Log::info("File sincronizzato: {$languageCode}/{$file}.php");
                        }
                    } catch (\Exception $e) {
                        $errorMsg = "Errore nel file {$languageCode}/{$file}.php: " . $e->getMessage();
                        $errors[] = $errorMsg;
                        Log::error($errorMsg);
                    }
                }
            }

            Log::info("Sincronizzazione lingue completata da " . Auth::user()?->email . " - {$syncedCount} file aggiornati");

            // Pulisci la cache delle traduzioni
            if (function_exists('cache')) {
                cache()->flush();
            }

            $message = "Sincronizzazione completata! {$syncedCount} file aggiornati.";
            if (!empty($errors)) {
                $message .= " Errori: " . implode(', ', $errors);
            }

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'synced_count' => $syncedCount,
                    'errors' => $errors
                ]);
            }

            return redirect()->route('admin.translations.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            $errorMsg = "Errore generale nella sincronizzazione: " . $e->getMessage();
            Log::error($errorMsg);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg
                ], 500);
            }

            return redirect()->route('admin.translations.index')
                ->with('error', $errorMsg);
        }
    }

    /**
     * Calcola le chiavi mancanti per un file specifico
     */
    private function getMissingKeysForFile($language, $file, $referenceTranslations, $translations)
    {
        $missingKeys = [];
        
        if ($language === 'it') {
            // Per l'italiano, controlla solo le chiavi vuote o mancanti
            foreach ($referenceTranslations as $key => $italianValue) {
                if (empty($italianValue) || $italianValue === '') {
                    $missingKeys[] = $key;
                }
            }
        } else {
            // Per le altre lingue, controlla solo se la traduzione è vuota o mancante
            // NON considerare uguale all'italiano come mancante (alcune parole sono uguali in più lingue)
            foreach ($referenceTranslations as $key => $italianValue) {
                if (!array_key_exists($key, $translations) || 
                    empty($translations[$key]) || 
                    $translations[$key] === '') {
                    $missingKeys[] = $key;
                }
            }
        }
        
        return $missingKeys;
    }

    /**
     * Traduzione automatica di un file
     */
    public function autoTranslate(Request $request, $language, $file)
    {
        if ($language === 'it') {
            return response()->json([
                'success' => false,
                'message' => 'Non è possibile tradurre automaticamente la lingua di riferimento (italiano)'
            ], 400);
        }

        $translationService = new AutoTranslationService();
        
        if (!$translationService->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'Servizio di traduzione automatica non configurato'
            ], 400);
        }

        try {
            $italianFile = lang_path("it/{$file}.php");
            $targetFile = lang_path("{$language}/{$file}.php");

            if (!File::exists($italianFile)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File di riferimento italiano non trovato'
                ], 404);
            }

            $italianTranslations = include $italianFile;
            $targetTranslations = File::exists($targetFile) ? include $targetFile : [];

            // Traduci solo le chiavi mancanti o vuote
            $keysToTranslate = [];
            foreach ($italianTranslations as $key => $italianValue) {
                if (!array_key_exists($key, $targetTranslations) || 
                    empty($targetTranslations[$key]) || 
                    $targetTranslations[$key] === '') {
                    $keysToTranslate[$key] = $italianValue;
                }
            }

            if (empty($keysToTranslate)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Nessuna traduzione necessaria',
                    'translated_count' => 0
                ]);
            }

            // Traduci le chiavi
            $translatedKeys = $translationService->translateArray($keysToTranslate, 'it', $language);

            // Unisci con le traduzioni esistenti
            $finalTranslations = array_merge($targetTranslations, $translatedKeys);

            // Salva il file
            $phpContent = $this->generatePhpContent($finalTranslations, $file);
            File::put($targetFile, $phpContent);

            // Pulisci la cache
            if (function_exists('cache')) {
                cache()->forget("translations.{$language}.{$file}");
            }

            $translatedCount = count($keysToTranslate);
            Log::info("Traduzione automatica completata", [
                'language' => $language,
                'file' => $file,
                'translated_keys' => $translatedCount,
                'user' => Auth::user()?->email
            ]);

            return response()->json([
                'success' => true,
                'message' => "Traduzione automatica completata! {$translatedCount} chiavi tradotte.",
                'translated_count' => $translatedCount
            ]);

        } catch (\Exception $e) {
            Log::error("Errore traduzione automatica: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la traduzione automatica: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Traduzione automatica di tutte le lingue
     */
    public function autoTranslateAll(Request $request)
    {
        $translationService = new AutoTranslationService();
        
        if (!$translationService->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'Servizio di traduzione automatica non configurato'
            ], 400);
        }

        try {
            $languages = $this->getAvailableLanguages();
            $translationFiles = $this->getTranslationFiles();
            $totalTranslated = 0;
            $errors = [];

            foreach ($languages as $languageCode => $languageName) {
                if ($languageCode === 'it') continue; // Salta l'italiano

                foreach ($translationFiles as $file => $displayName) {
                    try {
                        $italianFile = lang_path("it/{$file}.php");
                        $targetFile = lang_path("{$languageCode}/{$file}.php");

                        if (!File::exists($italianFile)) continue;

                        $italianTranslations = include $italianFile;
                        $targetTranslations = File::exists($targetFile) ? include $targetFile : [];

                        // Trova chiavi da tradurre
                        $keysToTranslate = [];
                        foreach ($italianTranslations as $key => $italianValue) {
                            if (!array_key_exists($key, $targetTranslations) || 
                                empty($targetTranslations[$key]) || 
                                $targetTranslations[$key] === '') {
                                $keysToTranslate[$key] = $italianValue;
                            }
                        }

                        if (!empty($keysToTranslate)) {
                            $translatedKeys = $translationService->translateArray($keysToTranslate, 'it', $languageCode);
                            $finalTranslations = array_merge($targetTranslations, $translatedKeys);
                            
                            $phpContent = $this->generatePhpContent($finalTranslations, $file);
                            File::put($targetFile, $phpContent);
                            
                            $totalTranslated += count($keysToTranslate);
                        }
                    } catch (\Exception $e) {
                        $errorMsg = "Errore nel file {$languageCode}/{$file}.php: " . $e->getMessage();
                        $errors[] = $errorMsg;
                        Log::error($errorMsg);
                    }
                }
            }

            // Pulisci la cache
            if (function_exists('cache')) {
                cache()->flush();
            }

            Log::info("Traduzione automatica globale completata", [
                'total_translated' => $totalTranslated,
                'user' => Auth::user()?->email
            ]);

            $message = "Traduzione automatica completata! {$totalTranslated} chiavi tradotte.";
            if (!empty($errors)) {
                $message .= " Errori: " . implode(', ', $errors);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'total_translated' => $totalTranslated,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            Log::error("Errore traduzione automatica globale: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la traduzione automatica: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test del servizio di traduzione
     */
    public function testTranslationService(Request $request)
    {
        $translationService = new AutoTranslationService();
        
        if (!$translationService->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'Servizio di traduzione automatica non configurato',
                'configured' => false
            ]);
        }

        try {
            $testText = 'Hello world';
            $translation = $translationService->translate($testText, 'en', 'it');
            
            $success = !empty($translation) && $translation !== $testText;
            
            return response()->json([
                'success' => $success,
                'message' => $success ? 'Test connessione riuscito' : 'Test connessione fallito',
                'configured' => true,
                'test_text' => $testText,
                'translation' => $translation,
                'stats' => $translationService->getUsageStats()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore test connessione: ' . $e->getMessage(),
                'configured' => true
            ], 500);
        }
    }

    /**
     * Calcola le traduzioni mancanti per ogni lingua
     */
    private function getMissingKeys($languages, $translationFiles)
    {
        $missingKeys = [];

        foreach ($languages as $languageCode => $languageName) {
            $missingKeys[$languageCode] = [];

            foreach ($translationFiles as $file => $displayName) {
                $italianFile = lang_path("it/{$file}.php");
                $targetFile = lang_path("{$languageCode}/{$file}.php");

                if (!File::exists($italianFile)) continue;

                $italianTranslations = include $italianFile;
                $targetTranslations = File::exists($targetFile) ? include $targetFile : [];

                $missingInFile = [];
                
                if ($languageCode === 'it') {
                    // Per l'italiano, controlla solo le chiavi vuote o mancanti
                    foreach ($italianTranslations as $key => $italianValue) {
                        if (empty($italianValue) || $italianValue === '') {
                            $missingInFile[] = $key;
                        }
                    }
                } else {
                    // Per le altre lingue, controlla solo se la traduzione è vuota o mancante
                    // NON considerare uguale all'italiano come mancante (alcune parole sono uguali in più lingue)
                    foreach ($italianTranslations as $key => $italianValue) {
                        if (!array_key_exists($key, $targetTranslations) || 
                            empty($targetTranslations[$key]) || 
                            $targetTranslations[$key] === '') {
                            $missingInFile[] = $key;
                        }
                    }
                }

                if (!empty($missingInFile)) {
                    $missingKeys[$languageCode][$file] = $missingInFile;
                }
            }
        }

        return $missingKeys;
    }
}
