<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class AutoTranslationService
{
    protected $apiKey;
    protected $baseUrl;
    protected $provider;

    public function __construct()
    {
        $this->provider = config('services.translation.provider', 'google');
        $this->apiKey = config('services.translation.api_key');
        $this->baseUrl = config('services.translation.base_url');
    }

    /**
     * Traduce un testo da una lingua all'altra
     */
    public function translate(string $text, string $from, string $to): ?string
    {
        if (empty($text) || $from === $to) {
            return $text;
        }

        // Cache per evitare traduzioni duplicate
        $cacheKey = "translation_{$from}_{$to}_" . md5($text);
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $translation = match($this->provider) {
                'google' => $this->translateWithGoogle($text, $from, $to),
                'deepl' => $this->translateWithDeepL($text, $from, $to),
                'openai' => $this->translateWithOpenAI($text, $from, $to),
                default => throw new Exception("Provider di traduzione non supportato: {$this->provider}")
            };

            // Cache per 30 giorni
            Cache::put($cacheKey, $translation, now()->addDays(30));

            Log::info("Traduzione automatica completata", [
                'from' => $from,
                'to' => $to,
                'text_length' => strlen($text),
                'provider' => $this->provider
            ]);

            return $translation;

        } catch (Exception $e) {
            Log::error("Errore traduzione automatica: " . $e->getMessage(), [
                'from' => $from,
                'to' => $to,
                'text' => substr($text, 0, 100) . '...',
                'provider' => $this->provider
            ]);

            return null;
        }
    }

    /**
     * Traduce un array di chiavi
     */
    public function translateArray(array $translations, string $from, string $to): array
    {
        $results = [];
        $totalKeys = count($translations);
        $translatedKeys = 0;

        foreach ($translations as $key => $value) {
            if (is_array($value)) {
                // Gestisci array annidati ricorsivamente
                $results[$key] = $this->translateArray($value, $from, $to);
            } else {
                if (!empty($value)) {
                    $translated = $this->translate($value, $from, $to);
                    if ($translated) {
                        $results[$key] = $translated;
                        $translatedKeys++;
                    } else {
                        $results[$key] = $value; // Mantieni originale se traduzione fallisce
                    }
                } else {
                    $results[$key] = $value;
                }
            }
        }

        Log::info("Traduzione array completata", [
            'from' => $from,
            'to' => $to,
            'total_keys' => $totalKeys,
            'translated_keys' => $translatedKeys,
            'success_rate' => $totalKeys > 0 ? round(($translatedKeys / $totalKeys) * 100, 2) : 0
        ]);

        return $results;
    }

    /**
     * Traduzione con Google Translate API
     */
    protected function translateWithGoogle(string $text, string $from, string $to): string
    {
        $response = Http::timeout(30)
            ->post("https://translation.googleapis.com/language/translate/v2", [
                'q' => $text,
                'source' => $from,
                'target' => $to,
                'key' => $this->apiKey,
                'format' => 'text'
            ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['data']['translations'][0]['translatedText'] ?? $text;
        }

        throw new Exception("Google Translate API error: " . $response->body());
    }

    /**
     * Traduzione con DeepL API
     */
    protected function translateWithDeepL(string $text, string $from, string $to): string
    {
        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => 'DeepL-Auth-Key ' . $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])
            ->post('https://api-free.deepl.com/v2/translate', [
                'text' => $text,
                'source_lang' => strtoupper($from),
                'target_lang' => strtoupper($to)
            ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['translations'][0]['text'] ?? $text;
        }

        throw new Exception("DeepL API error: " . $response->body());
    }

    /**
     * Traduzione con OpenAI GPT
     */
    protected function translateWithOpenAI(string $text, string $from, string $to): string
    {
        $fromLanguage = $this->getLanguageName($from);
        $toLanguage = $this->getLanguageName($to);

        $prompt = "Traduci il seguente testo da {$fromLanguage} a {$toLanguage}. " .
                  "Mantieni il tono e lo stile originale. Restituisci solo la traduzione senza spiegazioni:\n\n" .
                  $text;

        $response = Http::timeout(60)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ])
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Sei un traduttore professionale. Traduci accuratamente mantenendo il significato e lo stile.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 1000,
                'temperature' => 0.3
            ]);

        if ($response->successful()) {
            $data = $response->json();
            return trim($data['choices'][0]['message']['content'] ?? $text);
        }

        throw new Exception("OpenAI API error: " . $response->body());
    }

    /**
     * Ottieni il nome completo della lingua
     */
    protected function getLanguageName(string $code): string
    {
        $languages = [
            'it' => 'italiano',
            'en' => 'inglese',
            'es' => 'spagnolo',
            'fr' => 'francese',
            'de' => 'tedesco'
        ];

        return $languages[$code] ?? $code;
    }

    /**
     * Verifica se il servizio è configurato
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Testa la connessione al servizio di traduzione
     */
    public function testConnection(): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        try {
            $testText = 'Hello world';
            $translation = $this->translate($testText, 'en', 'it');
            return !empty($translation) && $translation !== $testText;
        } catch (Exception $e) {
            Log::error("Test connessione traduzione fallito: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Ottieni statistiche di utilizzo
     */
    public function getUsageStats(): array
    {
        $cacheKeys = Cache::get('translation_cache_keys', []);
        $totalTranslations = count($cacheKeys);
        
        return [
            'total_translations' => $totalTranslations,
            'provider' => $this->provider,
            'configured' => $this->isConfigured(),
            'connection_ok' => $this->testConnection()
        ];
    }
} 