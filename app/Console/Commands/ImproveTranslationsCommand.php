<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Helpers\TranslationDictionary;

class ImproveTranslationsCommand extends Command
{
    protected $signature = 'translations:improve {--language= : Lingua specifica da migliorare}';
    protected $description = 'Migliora le traduzioni esistenti sostituendo i placeholder con traduzioni reali';

    private $languages = [
        'en' => 'English',
        'es' => 'Spanish',
        'fr' => 'French',
        'de' => 'German',
        'pt' => 'Portuguese'
    ];

    public function handle()
    {
        $this->info('🔧 Miglioramento traduzioni in corso...');

        $targetLanguage = $this->option('language');
        $languagesToProcess = $targetLanguage ? [$targetLanguage => $this->languages[$targetLanguage]] : $this->languages;

        foreach ($languagesToProcess as $lang => $name) {
            if (!isset($this->languages[$lang])) {
                $this->error("Lingua non supportata: {$lang}");
                continue;
            }

            $this->info("📝 Miglioramento traduzioni in {$name} ({$lang})...");

            try {
                $result = $this->improveLanguage($lang);
                $this->line("  ✅ {$result['files']} file processati, {$result['improvements']} traduzioni migliorate");
            } catch (\Exception $e) {
                $this->error("  ❌ Errore: " . $e->getMessage());
            }
        }

        $this->info('🎉 Miglioramento completato!');
        return self::SUCCESS;
    }

    private function improveLanguage($language)
    {
        $targetPath = lang_path($language);

        if (!File::exists($targetPath)) {
            throw new \Exception("Directory lingua non trovata: {$targetPath}");
        }

        $files = File::allFiles($targetPath);
        $filesProcessed = 0;
        $improvements = 0;

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') continue;

            $translations = include $file->getPathname();
            if (!is_array($translations)) continue;

            $originalTranslations = $translations;
            $translations = $this->improveArray($translations, $language);

            // Salva solo se ci sono modifiche
            if ($translations !== $originalTranslations) {
                $this->saveTranslations($file->getPathname(), $translations);
                $filesProcessed++;
                $improvements += $this->countImprovements($originalTranslations, $translations);
            }
        }

        return ['files' => $filesProcessed, 'improvements' => $improvements];
    }

    private function improveArray($array, $language)
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->improveArray($value, $language);
            } else {
                $result[$key] = $this->improveText($value, $language);
            }
        }

        return $result;
    }

    private function improveText($text, $language)
    {
        // Se il testo è un placeholder, prova a migliorarlo
        if (preg_match('/^\[([a-z]{2})\]\s*(.+)$/', $text, $matches)) {
            $placeholderLang = $matches[1];
            $originalText = $matches[2];

            if ($placeholderLang === $language) {
                // Prova a trovare una traduzione migliore
                $commonTranslations = TranslationDictionary::getCommonTranslations();

                if (isset($commonTranslations[$language][$originalText])) {
                    return $commonTranslations[$language][$originalText];
                }

                // Se non trova una traduzione migliore, rimuovi almeno il placeholder
                return $originalText;
            }
        }

        // Se il testo è in italiano, traducilo
        if ($this->isItalianText($text)) {
            return $this->translateText($text, $language);
        }

        return $text;
    }

    private function countImprovements($original, $improved)
    {
        $count = 0;

        foreach ($original as $key => $value) {
            if (is_array($value) && is_array($improved[$key])) {
                $count += $this->countImprovements($value, $improved[$key]);
            } elseif ($value !== $improved[$key]) {
                $count++;
            }
        }

        return $count;
    }

    private function saveTranslations($filePath, $translations)
    {
        $content = "<?php\n\nreturn " . var_export($translations, true) . ";\n";
        file_put_contents($filePath, $content);
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
}
