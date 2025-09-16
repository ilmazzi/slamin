<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Helpers\TranslationDictionary;

class TranslateAllLanguagesCommand extends Command
{
    protected $signature = 'translations:translate-all {--force : Forza la traduzione anche se esistono già traduzioni}';
    protected $description = 'Traduce tutti i file di traduzione dall\'italiano alle altre lingue';

    private $languages = [
        'en' => 'English',
        'es' => 'Spanish',
        'fr' => 'French',
        'de' => 'German',
        'pt' => 'Portuguese'
    ];

    public function handle()
    {
        $this->info('🌍 Avvio traduzione di tutte le lingue...');

        $force = $this->option('force');

        foreach ($this->languages as $lang => $name) {
            $this->info("📝 Traduzione in {$name} ({$lang})...");

            try {
                $result = $this->translateLanguage($lang, $force);
                $this->line("  ✅ {$result['files']} file processati, {$result['keys']} chiavi tradotte");
            } catch (\Exception $e) {
                $this->error("  ❌ Errore: " . $e->getMessage());
            }
        }

        $this->info('🎉 Traduzione completata!');
        return self::SUCCESS;
    }

    private function translateLanguage($language, $force = false)
    {
        $italianPath = lang_path('it');
        $targetPath = lang_path($language);

        if (!File::exists($italianPath)) {
            throw new \Exception("Directory italiana non trovata: {$italianPath}");
        }

        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        $files = File::allFiles($italianPath);
        $filesProcessed = 0;
        $keysTranslated = 0;

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') continue;

            $filename = $file->getFilename();
            $relativePath = $file->getRelativePathname();
            $targetFile = $targetPath . '/' . $relativePath;

            // Carica traduzioni italiane
            $italianTranslations = include $file->getPathname();
            if (!is_array($italianTranslations)) continue;

            // Carica traduzioni esistenti
            $existingTranslations = [];
            if (File::exists($targetFile) && !$force) {
                $existingTranslations = include $targetFile;
                if (!is_array($existingTranslations)) {
                    $existingTranslations = [];
                }
            }

            // Traduci le chiavi
            $translatedTranslations = $this->translateArray($italianTranslations, $existingTranslations, $language);

            // Salva solo se ci sono modifiche
            if ($translatedTranslations !== $existingTranslations) {
                $this->saveTranslations($targetFile, $translatedTranslations);
                $filesProcessed++;
                $keysTranslated += $this->countNewKeys($italianTranslations, $existingTranslations);
            }
        }

        return ['files' => $filesProcessed, 'keys' => $keysTranslated];
    }

    private function translateArray($source, $existing, $language)
    {
        $result = $existing;

        foreach ($source as $key => $value) {
            if (is_array($value)) {
                $existingValue = $existing[$key] ?? [];
                $result[$key] = $this->translateArray($value, $existingValue, $language);
            } else {
                // Traduci sempre se non esiste o se è identico all'italiano o se è un placeholder
                $shouldTranslate = !isset($existing[$key]) ||
                                 $existing[$key] === $value ||
                                 str_starts_with($existing[$key], "[{$language}]") ||
                                 str_starts_with($existing[$key], "[") ||
                                 $this->isItalianText($existing[$key]);

                if ($shouldTranslate) {
                    $result[$key] = $this->translateText($value, $language);
                } else {
                    $result[$key] = $existing[$key];
                }
            }
        }

        return $result;
    }

    private function translateText($text, $language)
    {
        // Usa il dizionario di traduzioni comuni
        $commonTranslations = TranslationDictionary::getCommonTranslations();

        if (isset($commonTranslations[$language][$text])) {
            return $commonTranslations[$language][$text];
        }

        // Per testi non trovati, usa una traduzione di base
        return "[{$language}] {$text}";
    }

    private function isItalianText($text)
    {
        // Rileva testi italiani comuni
        $italianWords = [
            'Pannello', 'Amministrazione', 'Dashboard', 'Impostazioni', 'Traduzioni',
            'Caroselli', 'Utenti', 'Permessi', 'Gestione', 'Lingue', 'Disponibili',
            'File', 'Aggiungi', 'Lingua', 'Chiave', 'Modifica', 'Elimina', 'Codice',
            'Nome', 'Crea', 'Successo', 'Errore', 'Trovata', 'Eliminata', 'Esiste',
            'Riferimento', 'Italiano', 'Inserisci', 'Salva', 'Mostra', 'Nascondi',
            'Tutte', 'Copia', 'Svuota', 'Torna', 'Lista', 'Annulla', 'Statistiche',
            'Totali', 'Tradotte', 'Mancanti', 'Copiato', 'Svuotato', 'Completato',
            'Sconosciuto', 'Nuova', 'Obbligatorio', 'Già', 'Aggiunta', 'Esempi',
            'Coda', 'Cache', 'Gruppi', 'Recenti', 'Cerca', 'Filtra', 'Reset',
            'Risultati', 'Nessuna', 'Prima', 'Pulire', 'Sicuro', 'Voler',
            'Misto', 'Sistema', 'Seleziona', 'Descrittivo', 'Suggerimento',
            'Qualità', 'Accurate', 'Naturali', 'Consistente', 'Mantieni',
            'Stile', 'Coerente', 'Interfaccia', 'Autenticazione', 'Comuni',
            'Eventi', 'Profilo', 'Video', 'Chat', 'Processati', 'Catturati',
            'Automaticamente', 'Pulisci', 'Elementi', 'Attesa', 'Stato',
            'Contesto', 'Posizione', 'Creato', 'Converti', 'Processato',
            'Marca', 'Processati', 'Non', 'Verrano', 'Più', 'Mostrati',
            'Eliminare', 'Tutti', 'Visibili', 'Aggiorna', 'Visualizza',
            'Chiudi', 'Elimina', 'Traduzione', 'Azione', 'Annullata',
            'Usa', 'Riferimento', 'Mantieni', 'Stessa', 'Lunghezza',
            'Possibile', 'Controlla', 'Grammatica', 'Ortografia', 'Spesso',
            'Perdere', 'Modifiche', 'Sovrascriverà', 'Esistenti', 'Svuotare',
            'Non', 'Può', 'Essere', 'Annullata', 'Autenticazione', 'Comune',
            'Dashboard', 'Eventi', 'Video', 'Carousel', 'Italiano', 'English',
            'Español', 'Français', 'Deutsch', 'Breadcrumb', 'Informazioni',
            'Rapide', 'Completato', 'Sincronizza', 'Aggiungi', 'Mancanti',
            'Basandosi', 'Ora', 'Organizzate', 'Suggerimento', 'Riferimento',
            'Traduzioni', 'Non', 'Può', 'Essere', 'Annullata', 'Eliminerà',
            'Tutte', 'Per', 'Questa', 'Lingua', 'Directory', 'Rimossa',
            'Copiare', 'Dall', 'Italiano', 'Pulita', 'Con', 'Successo',
            'Durante', 'Pulizia', 'Testi', 'Hardcoded', 'Gestisci', 'Che',
            'Hanno', 'Chiavi', 'Aggiorna', 'Scansione', 'Trovati', 'Coinvolti',
            'Pronti', 'Conversione', 'Converti', 'Chiavi', 'Filtra', 'Tutti',
            'Cerca', 'Testo', 'Nei', 'Testi', 'Visibili', 'Pulisci', 'Filtri',
            'Lista', 'Riga', 'Suggerita', 'Nessun', 'Trovato', 'Già', 'Tradotti',
            'Correttamente', 'Suggerimento', 'Formato', 'Come', 'File', 'Chiave',
            'Descrittiva', 'Convertito', 'Selezione', 'Seleziona', 'Convertire',
            'Massa', 'Funzionalità', 'Arrivo', 'Contemporaneamente', 'Anteprima',
            'Aggiornamento', 'Scansionando', 'Progetto'
        ];

        foreach ($italianWords as $word) {
            if (str_contains($text, $word)) {
                return true;
            }
        }

        // Controlla pattern italiani comuni
        $italianPatterns = [
            '/\b(il|la|lo|gli|le|un|una|uno|dei|delle|del|della|dell|dello|degli|delle)\b/i',
            '/\b(che|chi|come|quando|dove|perché|perchè|quanto|quale|quali)\b/i',
            '/\b(con|senza|sopra|sotto|dentro|fuori|prima|dopo|durante|mentre)\b/i',
            '/\b(anche|sempre|mai|spesso|raramente|solo|soltanto|ancora|già|appena)\b/i',
            '/\b(qui|qua|lì|là|davanti|dietro|accanto|vicino|lontano|intorno)\b/i',
            '/\b(oggi|ieri|domani|stamattina|stasera|presto|tardi|subito|adesso|ora)\b/i',
            '/\b(bene|male|meglio|peggio|molto|poco|tanto|troppo|abbastanza|quasi)\b/i',
            '/\b(però|ma|invece|quindi|allora|così|cosi|però|tuttavia|comunque)\b/i'
        ];

        foreach ($italianPatterns as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }

        return false;
    }

    private function countNewKeys($source, $existing)
    {
        $count = 0;
        foreach ($source as $key => $value) {
            if (!isset($existing[$key])) {
                $count++;
            } elseif (is_array($value) && is_array($existing[$key])) {
                $count += $this->countNewKeys($value, $existing[$key]);
            }
        }
        return $count;
    }

    private function saveTranslations($filePath, $translations)
    {
        // Crea la directory se non esiste
        $directory = dirname($filePath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";
        file_put_contents($filePath, $content);
    }
}
