<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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

        return view('admin.translations.edit', compact('language', 'file', 'translations', 'referenceTranslations'));
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

            Log::info("Traduzioni aggiornate per {$language}/{$file} da " . auth()->user()?->email);

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

            Log::info("Nuova lingua creata: {$languageCode} da " . auth()->user()?->email);

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

            Log::info("Lingua eliminata: {$language} da " . auth()->user()?->email);

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
        $languages = $this->getAvailableLanguages();
        $translationFiles = $this->getTranslationFiles();
        $syncedCount = 0;

        foreach ($languages as $languageCode => $languageName) {
            if ($languageCode === 'it') continue; // Salta l'italiano

            foreach ($translationFiles as $file => $displayName) {
                $italianFile = lang_path("it/{$file}.php");
                $targetFile = lang_path("{$languageCode}/{$file}.php");

                if (!File::exists($italianFile)) continue;

                $italianTranslations = include $italianFile;
                $targetTranslations = File::exists($targetFile) ? include $targetFile : [];

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
                    File::put($targetFile, $phpContent);
                    $syncedCount++;
                }
            }
        }

        Log::info("Sincronizzazione lingue completata da " . auth()->user()?->email . " - {$syncedCount} file aggiornati");

        return redirect()->route('admin.translations.index')
            ->with('success', "Sincronizzazione completata! {$syncedCount} file aggiornati.");
    }

    /**
     * Calcola le chiavi mancanti per ogni lingua
     */
    private function getMissingKeys($languages, $translationFiles)
    {
        $missingKeys = [];

        foreach ($languages as $languageCode => $languageName) {
            if ($languageCode === 'it') continue; // Salta l'italiano

            $missingKeys[$languageCode] = [];

            foreach ($translationFiles as $file => $displayName) {
                $italianFile = lang_path("it/{$file}.php");
                $targetFile = lang_path("{$languageCode}/{$file}.php");

                if (!File::exists($italianFile)) continue;

                $italianTranslations = include $italianFile;
                $targetTranslations = File::exists($targetFile) ? include $targetFile : [];

                $missingInFile = [];
                foreach ($italianTranslations as $key => $value) {
                    if (!array_key_exists($key, $targetTranslations)) {
                        $missingInFile[] = $key;
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
