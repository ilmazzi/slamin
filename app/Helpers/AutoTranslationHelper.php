<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoTranslationHelper
{
    /**
     * Cattura automaticamente un testo per la traduzione
     *
     * @param string $text Testo da catturare
     * @param string|null $context Contesto del testo
     * @param string|null $filePath Percorso del file
     * @param int|null $lineNumber Numero di riga
     * @return string Restituisce il testo originale
     */
    public static function capture($text, $context = null, $filePath = null, $lineNumber = null)
    {
        // Pulisce il testo
        $cleanText = trim($text);

        // Ignora testi vuoti o troppo corti
        if (empty($cleanText) || strlen($cleanText) < 3) {
            return $text;
        }

        // Ignora testi che sembrano già essere chiavi di traduzione
        if (preg_match('/^[a-z_]+\.[a-z_]+$/', $cleanText)) {
            return $text;
        }

        // Ignora testi che contengono solo numeri o simboli
        if (preg_match('/^[\d\s\-\+\(\)\[\]{}.,;:!?]+$/', $cleanText)) {
            return $text;
        }

        $hash = md5($cleanText);

        try {
            DB::table('translation_queue')->updateOrInsert(
                ['text_hash' => $hash],
                [
                    'original_text' => $cleanText,
                    'context' => $context,
                    'file_path' => $filePath,
                    'line_number' => $lineNumber,
                    'processed' => false,
                    'updated_at' => now()
                ]
            );
        } catch (\Exception $e) {
            Log::warning('Failed to capture text for translation', [
                'text' => $cleanText,
                'error' => $e->getMessage()
            ]);
        }

        return $text;
    }

    /**
     * Cattura automaticamente testi da un array di stringhe
     *
     * @param array $texts Array di testi
     * @param string|null $context Contesto
     * @return array Array originale
     */
    public static function captureArray($texts, $context = null)
    {
        foreach ($texts as $text) {
            if (is_string($text)) {
                self::capture($text, $context);
            }
        }

        return $texts;
    }

    /**
     * Ottiene la coda delle traduzioni non processate
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public static function getQueue($limit = 50)
    {
        return DB::table('translation_queue')
            ->where('processed', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Marca un elemento della coda come processato
     *
     * @param int $id
     * @return bool
     */
    public static function markAsProcessed($id)
    {
        return DB::table('translation_queue')
            ->where('id', $id)
            ->update(['processed' => true, 'updated_at' => now()]) > 0;
    }

    /**
     * Converte un testo della coda in una chiave di traduzione
     *
     * @param int $id
     * @param string $group
     * @param string $key
     * @param string $locale
     * @return bool
     */
    public static function convertToTranslation($id, $group, $key, $locale = 'it')
    {
        $item = DB::table('translation_queue')->find($id);

        if (!$item) {
            return false;
        }

        // Salva la traduzione
        $result = TranslationHelper::set($group . '.' . $key, $item->original_text, $locale);

        if ($result) {
            // Marca come processato
            self::markAsProcessed($id);
        }

        return $result;
    }

    /**
     * Genera automaticamente una chiave per un testo
     *
     * @param string $text
     * @param string $group
     * @return string
     */
    public static function generateKey($text, $group = 'common')
    {
        // Pulisce il testo e lo converte in snake_case
        $cleanText = strtolower($text);
        $cleanText = preg_replace('/[^a-z0-9\s]/', '', $cleanText);
        $cleanText = preg_replace('/\s+/', '_', trim($cleanText));

        // Limita la lunghezza
        $cleanText = substr($cleanText, 0, 50);

        // Rimuove underscore finali
        $cleanText = rtrim($cleanText, '_');

        // Se è vuoto, usa un hash
        if (empty($cleanText)) {
            $cleanText = 'text_' . substr(md5($text), 0, 8);
        }

        return $cleanText;
    }

    /**
     * Suggerisce un gruppo per un testo basato sul contesto
     *
     * @param string $context
     * @param string|null $filePath
     * @return string
     */
    public static function suggestGroup($context = null, $filePath = null)
    {
        if ($context) {
            $context = strtolower($context);

            if (strpos($context, 'admin') !== false) return 'admin';
            if (strpos($context, 'auth') !== false) return 'auth';
            if (strpos($context, 'email') !== false) return 'emails';
            if (strpos($context, 'error') !== false) return 'errors';
            if (strpos($context, 'validation') !== false) return 'validation';
        }

        if ($filePath) {
            $filePath = strtolower($filePath);

            if (strpos($filePath, 'admin') !== false) return 'admin';
            if (strpos($filePath, 'auth') !== false) return 'auth';
            if (strpos($filePath, 'email') !== false) return 'emails';
            if (strpos($filePath, 'error') !== false) return 'errors';
        }

        return 'common';
    }

    /**
     * Pulisce la coda delle traduzioni processate
     *
     * @param int $days Giorni di ritenzione
     * @return int Numero di elementi eliminati
     */
    public static function cleanProcessed($days = 30)
    {
        return DB::table('translation_queue')
            ->where('processed', true)
            ->where('updated_at', '<', now()->subDays($days))
            ->delete();
    }

    /**
     * Ottiene statistiche della coda
     *
     * @return array
     */
    public static function getQueueStats()
    {
        $total = DB::table('translation_queue')->count();
        $processed = DB::table('translation_queue')->where('processed', true)->count();
        $pending = $total - $processed;

        $recent = DB::table('translation_queue')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        return [
            'total' => $total,
            'processed' => $processed,
            'pending' => $pending,
            'recent' => $recent
        ];
    }
}

