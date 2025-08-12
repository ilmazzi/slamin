<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AutoTranslationService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.google.translate_api_key');
        $this->baseUrl = 'https://translation.googleapis.com/language/translate/v2';
    }

    /**
     * Translate text from source language to target language
     */
    public function translate($text, $sourceLang = 'it', $targetLang = 'en')
    {
        if (empty($text)) {
            return $text;
        }

        // Check cache first
        $cacheKey = "translation_{$sourceLang}_{$targetLang}_" . md5($text);
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }

        try {
            $response = Http::post($this->baseUrl, [
                'q' => $text,
                'source' => $sourceLang,
                'target' => $targetLang,
                'key' => $this->apiKey,
                'format' => 'html'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $translatedText = $data['data']['translations'][0]['translatedText'] ?? $text;

                // Cache the result for 24 hours
                Cache::put($cacheKey, $translatedText, now()->addHours(24));

                return $translatedText;
            } else {
                Log::error('Translation API error', [
                    'response' => $response->body(),
                    'status' => $response->status()
                ]);
                return $text;
            }
        } catch (\Exception $e) {
            Log::error('Translation service error', [
                'message' => $e->getMessage(),
                'text' => $text,
                'source' => $sourceLang,
                'target' => $targetLang
            ]);
            return $text;
        }
    }

    /**
     * Translate article content
     */
    public function translateArticle($article, $targetLang = 'en')
    {
        $sourceLang = config('app.locale', 'it');
        
        if ($sourceLang === $targetLang) {
            return $article;
        }

        $translated = [];

        // Translate title
        if (!empty($article['title'])) {
            $translated['title'] = $this->translate($article['title'], $sourceLang, $targetLang);
        }

        // Translate content
        if (!empty($article['content'])) {
            $translated['content'] = $this->translate($article['content'], $sourceLang, $targetLang);
        }

        // Translate excerpt
        if (!empty($article['excerpt'])) {
            $translated['excerpt'] = $this->translate($article['excerpt'], $sourceLang, $targetLang);
        }

        // Translate meta title
        if (!empty($article['meta_title'])) {
            $translated['meta_title'] = $this->translate($article['meta_title'], $sourceLang, $targetLang);
        }

        // Translate meta description
        if (!empty($article['meta_description'])) {
            $translated['meta_description'] = $this->translate($article['meta_description'], $sourceLang, $targetLang);
        }

        return $translated;
    }

    /**
     * Translate multiple articles
     */
    public function translateArticles($articles, $targetLang = 'en')
    {
        $translated = [];
        
        foreach ($articles as $article) {
            $translated[] = $this->translateArticle($article, $targetLang);
        }

        return $translated;
    }

    /**
     * Detect language of text
     */
    public function detectLanguage($text)
    {
        if (empty($text)) {
            return null;
        }

        try {
            $response = Http::post('https://translation.googleapis.com/language/translate/v2/detect', [
                'q' => $text,
                'key' => $this->apiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['data']['detections'][0][0]['language'] ?? null;
            }
        } catch (\Exception $e) {
            Log::error('Language detection error', [
                'message' => $e->getMessage(),
                'text' => $text
            ]);
        }

        return null;
    }

    /**
     * Get supported languages
     */
    public function getSupportedLanguages($targetLang = 'it')
    {
        $cacheKey = "supported_languages_{$targetLang}";
        $cached = Cache::get($cacheKey);
        
        if ($cached) {
            return $cached;
        }

        try {
            $response = Http::get('https://translation.googleapis.com/language/translate/v2/languages', [
                'target' => $targetLang,
                'key' => $this->apiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $languages = $data['data']['languages'] ?? [];

                // Cache for 24 hours
                Cache::put($cacheKey, $languages, now()->addHours(24));

                return $languages;
            }
        } catch (\Exception $e) {
            Log::error('Get supported languages error', [
                'message' => $e->getMessage()
            ]);
        }

        return [];
    }

    /**
     * Check if translation service is available
     */
    public function isAvailable()
    {
        return !empty($this->apiKey);
    }

    /**
     * Get translation quota usage
     */
    public function getQuotaUsage()
    {
        // This would require additional API calls to Google Cloud Console
        // For now, return basic info
        return [
            'available' => $this->isAvailable(),
            'api_key_configured' => !empty($this->apiKey)
        ];
    }

    /**
     * Clear translation cache
     */
    public function clearCache()
    {
        $keys = Cache::get('translation_cache_keys', []);
        
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        
        Cache::forget('translation_cache_keys');
        Cache::forget('supported_languages_*');
    }

    /**
     * Batch translate multiple texts
     */
    public function batchTranslate($texts, $sourceLang = 'it', $targetLang = 'en')
    {
        if (empty($texts)) {
            return [];
        }

        $results = [];
        $batchSize = 10; // Google Translate API limit
        $batches = array_chunk($texts, $batchSize);

        foreach ($batches as $batch) {
            try {
                $response = Http::post($this->baseUrl, [
                    'q' => $batch,
                    'source' => $sourceLang,
                    'target' => $targetLang,
                    'key' => $this->apiKey,
                    'format' => 'html'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $translations = $data['data']['translations'] ?? [];
                    
                    foreach ($translations as $index => $translation) {
                        $results[] = $translation['translatedText'] ?? $batch[$index];
                    }
                } else {
                    // If batch fails, translate individually
                    foreach ($batch as $text) {
                        $results[] = $this->translate($text, $sourceLang, $targetLang);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Batch translation error', [
                    'message' => $e->getMessage(),
                    'batch' => $batch
                ]);
                
                // Fallback to individual translation
                foreach ($batch as $text) {
                    $results[] = $this->translate($text, $sourceLang, $targetLang);
                }
            }
        }

        return $results;
    }
} 
