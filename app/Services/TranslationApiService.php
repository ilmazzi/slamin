<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationApiService
{
    private $providers = [
        'google' => [
            'name' => 'Google Translate',
            'url' => 'https://translation.googleapis.com/language/translate/v2',
            'requires_key' => true
        ],
        'deepl' => [
            'name' => 'DeepL',
            'url' => 'https://api-free.deepl.com/v2/translate',
            'requires_key' => true
        ],
        'microsoft' => [
            'name' => 'Microsoft Translator',
            'url' => 'https://api.cognitive.microsofttranslator.com/translate',
            'requires_key' => true
        ],
        'libre' => [
            'name' => 'LibreTranslate (Self-hosted)',
            'url' => 'http://localhost:5000/translate',
            'fallback_url' => 'https://translate.argosopentech.com/translate',
            'requires_key' => false
        ]
    ];

    private $apiKey;
    private $provider;

    public function __construct($provider = 'google', $apiKey = null)
    {
        $this->provider = $provider;
        $this->apiKey = $apiKey ?: config('services.translation.api_key');
    }

    public function translate($text, $targetLanguage, $sourceLanguage = 'it')
    {
        if (empty($text) || $targetLanguage === $sourceLanguage) {
            return $text;
        }

        // Pulisci e normalizza il testo per evitare problemi di encoding
        $text = $this->cleanText($text);

        try {
            switch ($this->provider) {
                case 'google':
                    return $this->translateWithGoogle($text, $targetLanguage, $sourceLanguage);
                case 'deepl':
                    return $this->translateWithDeepL($text, $targetLanguage, $sourceLanguage);
                case 'microsoft':
                    return $this->translateWithMicrosoft($text, $targetLanguage, $sourceLanguage);
                case 'libre':
                    return $this->translateWithLibre($text, $targetLanguage, $sourceLanguage);
                default:
                    throw new \Exception("Provider {$this->provider} not supported");
            }
        } catch (\Exception $e) {
            Log::error("Translation API error: " . $e->getMessage());
            // Non aggiungere prefissi, restituisci il testo originale
            return $text;
        }
    }

    /**
     * Pulisce e normalizza il testo per evitare problemi di encoding
     */
    private function cleanText($text)
    {
        // Rimuovi caratteri di controllo e normalizza
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);

        // Normalizza gli apostrofi e le virgolette
        $text = str_replace(["\u2018", "\u2019", "\u201C", "\u201D"], ["'", "'", '"', '"'], $text);

        // Assicurati che il testo sia UTF-8 valido
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'auto');
        }

        // Rimuovi BOM se presente
        $text = preg_replace('/^\xEF\xBB\xBF/', '', $text);

        return trim($text);
    }

    private function translateWithGoogle($text, $targetLanguage, $sourceLanguage)
    {
        $response = Http::post($this->providers['google']['url'], [
            'q' => $text,
            'target' => $targetLanguage,
            'source' => $sourceLanguage,
            'key' => $this->apiKey,
            'format' => 'text'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['data']['translations'][0]['translatedText'] ?? $text;
        }

        throw new \Exception("Google Translate API error: " . $response->body());
    }

    private function translateWithDeepL($text, $targetLanguage, $sourceLanguage)
    {
        $response = Http::withHeaders([
            'Authorization' => 'DeepL-Auth-Key ' . $this->apiKey,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->post($this->providers['deepl']['url'], [
            'text' => $text,
            'target_lang' => strtoupper($targetLanguage),
            'source_lang' => strtoupper($sourceLanguage)
        ]);

        if ($response->successful()) {
            $body = $response->body();
            if (empty(trim($body))) {
                throw new \Exception("DeepL returned empty response.");
            }

            $data = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("DeepL returned invalid JSON: " . json_last_error_msg() . ". Response: " . substr($body, 0, 200));
            }

            return $data['translations'][0]['text'] ?? $text;
        }

        throw new \Exception("DeepL API error: " . $response->body());
    }

    private function translateWithMicrosoft($text, $targetLanguage, $sourceLanguage)
    {
        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $this->apiKey,
            'Content-Type' => 'application/json'
        ])->post($this->providers['microsoft']['url'], [
            [
                'text' => $text
            ]
        ], [
            'api-version' => '3.0',
            'from' => $sourceLanguage,
            'to' => $targetLanguage
        ]);

        if ($response->successful()) {
            $body = $response->body();
            if (empty(trim($body))) {
                throw new \Exception("Microsoft Translator returned empty response.");
            }

            $data = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Microsoft Translator returned invalid JSON: " . json_last_error_msg() . ". Response: " . substr($body, 0, 200));
            }

            return $data[0]['translations'][0]['text'] ?? $text;
        }

        throw new \Exception("Microsoft Translator API error: " . $response->body());
    }

    private function translateWithLibre($text, $targetLanguage, $sourceLanguage)
    {
        try {
            // Prova prima l'istanza locale, poi quella pubblica
            $url = $this->providers['libre']['url'];
            $fallbackUrl = $this->providers['libre']['fallback_url'];

            // Prova prima l'istanza locale
            $response = $this->tryLibreTranslate($url, $text, $targetLanguage, $sourceLanguage);

            // Se fallisce, prova il fallback
            if (!$response || !$response->successful()) {
                $response = $this->tryLibreTranslate($fallbackUrl, $text, $targetLanguage, $sourceLanguage);
            }

            if ($response && $response->successful()) {
                $body = $response->body();

                // Controlla se la risposta è HTML invece di JSON
                if (strpos($body, '<!DOCTYPE') !== false || strpos($body, '<html') !== false) {
                    throw new \Exception("LibreTranslate returned HTML instead of JSON. The service might be down or changed.");
                }

                // Controlla se la risposta è vuota
                if (empty(trim($body))) {
                    throw new \Exception("LibreTranslate returned empty response.");
                }

                // Prova a decodificare il JSON con gestione errori
                $data = json_decode($body, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception("LibreTranslate returned invalid JSON: " . json_last_error_msg() . ". Response: " . substr($body, 0, 200));
                }

                if (isset($data['translatedText'])) {
                    return $data['translatedText'];
                }
                // Fallback se la struttura è diversa
                if (isset($data['translation'])) {
                    return $data['translation'];
                }
                if (isset($data['text'])) {
                    return $data['text'];
                }
                return $text;
            }

            // Se entrambe le chiamate falliscono, usa il dizionario locale
            \Log::info("LibreTranslate not available, using local dictionary for: {$text} -> {$targetLanguage}");
            return $this->getLocalTranslation($text, $targetLanguage);

        } catch (\Exception $e) {
            // Se l'API fallisce, prova con un dizionario locale
            \Log::warning("LibreTranslate API failed: " . $e->getMessage());
            return $this->getLocalTranslation($text, $targetLanguage);
        }
    }

    public function getSupportedLanguages()
    {
        return [
            'en' => 'English',
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
            'pt' => 'Portuguese',
            'it' => 'Italian',
            'ru' => 'Russian',
            'ja' => 'Japanese',
            'ko' => 'Korean',
            'zh' => 'Chinese',
            'ar' => 'Arabic',
            'hi' => 'Hindi',
            'nl' => 'Dutch',
            'sv' => 'Swedish',
            'da' => 'Danish',
            'no' => 'Norwegian',
            'fi' => 'Finnish',
            'pl' => 'Polish',
            'tr' => 'Turkish',
            'cs' => 'Czech',
            'hu' => 'Hungarian',
            'ro' => 'Romanian',
            'bg' => 'Bulgarian',
            'hr' => 'Croatian',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'et' => 'Estonian',
            'lv' => 'Latvian',
            'lt' => 'Lithuanian',
            'uk' => 'Ukrainian',
            'be' => 'Belarusian',
            'mk' => 'Macedonian',
            'sq' => 'Albanian',
            'sr' => 'Serbian',
            'bs' => 'Bosnian',
            'mt' => 'Maltese',
            'is' => 'Icelandic',
            'ga' => 'Irish',
            'cy' => 'Welsh',
            'eu' => 'Basque',
            'ca' => 'Catalan',
            'gl' => 'Galician'
        ];
    }

    public function getProviders()
    {
        return $this->providers;
    }

    public function testConnection()
    {
        try {
            $testText = "Hello";
            $result = $this->translate($testText, 'it', 'en');
            return [
                'success' => true,
                'message' => "Test successful: '{$testText}' translated to '{$result}'",
                'provider' => $this->providers[$this->provider]['name']
            ];
        } catch (\Exception $e) {
            \Log::error("Translation API test failed: " . $e->getMessage());
            return [
                'success' => false,
                'message' => "Test failed: " . $e->getMessage(),
                'provider' => $this->providers[$this->provider]['name']
            ];
        }
    }

    /**
     * Ottiene una traduzione dal dizionario locale
     */
    private function getLocalTranslation($text, $targetLanguage)
    {
        // Usa il dizionario di traduzioni comuni
        $commonTranslations = [
            'en' => [
                'Pannello Amministrazione' => 'Administration Panel',
                'Dashboard' => 'Dashboard',
                'Impostazioni' => 'Settings',
                'Traduzioni' => 'Translations',
                'Caroselli' => 'Carousels',
                'Utenti' => 'Users',
                'Permessi' => 'Permissions',
                'Gestione' => 'Management',
                'Lingue' => 'Languages',
                'Disponibili' => 'Available',
                'File' => 'Files',
                'Aggiungi' => 'Add',
                'Lingua' => 'Language',
                'Chiave' => 'Key',
                'Modifica' => 'Edit',
                'Elimina' => 'Delete',
                'Codice' => 'Code',
                'Nome' => 'Name',
                'Crea' => 'Create',
                'Successo' => 'Success',
                'Errore' => 'Error',
                'Trovata' => 'Found',
                'Eliminata' => 'Deleted',
                'Esiste' => 'Exists',
                'Riferimento' => 'Reference',
                'Italiano' => 'Italian',
                'Inserisci' => 'Enter',
                'Salva' => 'Save',
                'Mostra' => 'Show',
                'Nascondi' => 'Hide',
                'Tutte' => 'All',
                'Copia' => 'Copy',
                'Svuota' => 'Clear',
                'Torna' => 'Back',
                'Lista' => 'List',
                'Annulla' => 'Cancel',
                'Statistiche' => 'Statistics',
                'Totali' => 'Total',
                'Tradotte' => 'Translated',
                'Mancanti' => 'Missing',
                'OK' => 'OK',
                'Sì' => 'Yes',
                'No' => 'No'
            ],
            'es' => [
                'Pannello Amministrazione' => 'Panel de Administración',
                'Dashboard' => 'Panel de Control',
                'Impostazioni' => 'Configuración',
                'Traduzioni' => 'Traducciones',
                'Caroselli' => 'Carruseles',
                'Utenti' => 'Usuarios',
                'Permessi' => 'Permisos',
                'Gestione' => 'Gestión',
                'Lingue' => 'Idiomas',
                'Disponibili' => 'Disponibles',
                'File' => 'Archivos',
                'Aggiungi' => 'Agregar',
                'Lingua' => 'Idioma',
                'Chiave' => 'Clave',
                'Modifica' => 'Editar',
                'Elimina' => 'Eliminar',
                'Codice' => 'Código',
                'Nome' => 'Nombre',
                'Crea' => 'Crear',
                'Successo' => 'Éxito',
                'Errore' => 'Error',
                'Trovata' => 'Encontrada',
                'Eliminata' => 'Eliminada',
                'Esiste' => 'Existe',
                'Riferimento' => 'Referencia',
                'Italiano' => 'Italiano',
                'Inserisci' => 'Ingresar',
                'Salva' => 'Guardar',
                'Mostra' => 'Mostrar',
                'Nascondi' => 'Ocultar',
                'Tutte' => 'Todas',
                'Copia' => 'Copiar',
                'Svuota' => 'Limpiar',
                'Torna' => 'Volver',
                'Lista' => 'Lista',
                'Annulla' => 'Cancelar',
                'Statistiche' => 'Estadísticas',
                'Totali' => 'Total',
                'Tradotte' => 'Traducidas',
                'Mancanti' => 'Faltantes',
                'OK' => 'OK',
                'Sì' => 'Sí',
                'No' => 'No'
            ],
            'fr' => [
                'Pannello Amministrazione' => 'Panneau d\'Administration',
                'Dashboard' => 'Tableau de Bord',
                'Impostazioni' => 'Paramètres',
                'Traduzioni' => 'Traductions',
                'Caroselli' => 'Carrousels',
                'Utenti' => 'Utilisateurs',
                'Permessi' => 'Permissions',
                'Gestione' => 'Gestion',
                'Lingue' => 'Langues',
                'Disponibili' => 'Disponibles',
                'File' => 'Fichiers',
                'Aggiungi' => 'Ajouter',
                'Lingua' => 'Langue',
                'Chiave' => 'Clé',
                'Modifica' => 'Modifier',
                'Elimina' => 'Supprimer',
                'Codice' => 'Code',
                'Nome' => 'Nom',
                'Crea' => 'Créer',
                'Successo' => 'Succès',
                'Errore' => 'Erreur',
                'Trovata' => 'Trouvée',
                'Eliminata' => 'Supprimée',
                'Esiste' => 'Existe',
                'Riferimento' => 'Référence',
                'Italiano' => 'Italien',
                'Inserisci' => 'Entrer',
                'Salva' => 'Sauvegarder',
                'Mostra' => 'Afficher',
                'Nascondi' => 'Masquer',
                'Tutte' => 'Toutes',
                'Copia' => 'Copier',
                'Svuota' => 'Vider',
                'Torna' => 'Retour',
                'Lista' => 'Liste',
                'Annulla' => 'Annuler',
                'Statistiche' => 'Statistiques',
                'Totali' => 'Total',
                'Tradotte' => 'Traduites',
                'Mancanti' => 'Manquantes',
                'OK' => 'OK',
                'Sì' => 'Oui',
                'No' => 'Non'
            ],
            'de' => [
                'Pannello Amministrazione' => 'Administrationspanel',
                'Dashboard' => 'Dashboard',
                'Impostazioni' => 'Einstellungen',
                'Traduzioni' => 'Übersetzungen',
                'Caroselli' => 'Karussells',
                'Utenti' => 'Benutzer',
                'Permessi' => 'Berechtigungen',
                'Gestione' => 'Verwaltung',
                'Lingue' => 'Sprachen',
                'Disponibili' => 'Verfügbar',
                'File' => 'Dateien',
                'Aggiungi' => 'Hinzufügen',
                'Lingua' => 'Sprache',
                'Chiave' => 'Schlüssel',
                'Modifica' => 'Bearbeiten',
                'Elimina' => 'Löschen',
                'Codice' => 'Code',
                'Nome' => 'Name',
                'Crea' => 'Erstellen',
                'Successo' => 'Erfolg',
                'Errore' => 'Fehler',
                'Trovata' => 'Gefunden',
                'Eliminata' => 'Gelöscht',
                'Esiste' => 'Existiert',
                'Riferimento' => 'Referenz',
                'Italiano' => 'Italienisch',
                'Inserisci' => 'Eingeben',
                'Salva' => 'Speichern',
                'Mostra' => 'Anzeigen',
                'Nascondi' => 'Verstecken',
                'Tutte' => 'Alle',
                'Copia' => 'Kopieren',
                'Svuota' => 'Leeren',
                'Torna' => 'Zurück',
                'Lista' => 'Liste',
                'Annulla' => 'Abbrechen',
                'Statistiche' => 'Statistiken',
                'Totali' => 'Gesamt',
                'Tradotte' => 'Übersetzt',
                'Mancanti' => 'Fehlend',
                'OK' => 'OK',
                'Sì' => 'Ja',
                'No' => 'Nein'
            ]
        ];

        if (isset($commonTranslations[$targetLanguage][$text])) {
            return $commonTranslations[$targetLanguage][$text];
        }

        // Se non trova una traduzione, restituisci il testo originale
        return $text;
    }

    /**
     * Prova a tradurre con LibreTranslate
     */
    private function tryLibreTranslate($url, $text, $targetLanguage, $sourceLanguage)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'Laravel Translation Service'
            ])->timeout(10)->post($url, [
                'q' => $text,
                'source' => $sourceLanguage,
                'target' => $targetLanguage,
                'format' => 'text'
            ]);

            // Controlla se la risposta contiene errori di modelli non disponibili
            if ($response->successful()) {
                $body = $response->body();
                if (strpos($body, 'not supported') !== false || strpos($body, 'not available') !== false) {
                    \Log::info("LibreTranslate model not available for {$sourceLanguage}->{$targetLanguage}");
                    return null;
                }
            }

            return $response;
        } catch (\Exception $e) {
            \Log::info("LibreTranslate connection failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Verifica se LibreTranslate locale è disponibile
     */
    private function isLocalLibreTranslateAvailable()
    {
        try {
            $response = Http::timeout(5)->get('http://localhost:5000/languages');
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
