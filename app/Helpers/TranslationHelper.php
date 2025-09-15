<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;

class TranslationHelper
{
    /**
     * Cache key prefix per le traduzioni
     */
    const CACHE_PREFIX = 'translation_';

    /**
     * Durata cache in minuti
     */
    const CACHE_DURATION = 60;

    /**
     * Ottiene una traduzione dal sistema misto (DB + File)
     *
     * @param string $key Chiave di traduzione (es. 'admin.dashboard')
     * @param array $replace Array di sostituzioni
     * @param string|null $locale Locale specifico
     * @return string
     */
    public static function get($key, $replace = [], $locale = null)
    {
        $locale = $locale ?: App::getLocale();

        // Prima controlla il DB
        $dbTranslation = self::getFromDatabase($key, $locale);
        if ($dbTranslation !== null) {
            return self::replacePlaceholders($dbTranslation, $replace);
        }

        // Poi usa i file tradizionali
        return trans($key, $replace, $locale);
    }

    /**
     * Ottiene una traduzione solo dal database
     *
     * @param string $key
     * @param string $locale
     * @return string|null
     */
    public static function getFromDatabase($key, $locale)
    {
        $parts = explode('.', $key);
        if (count($parts) < 2) {
            return null;
        }

        $group = $parts[0];
        $keyName = $parts[1];

        $cacheKey = self::CACHE_PREFIX . $locale . '_' . $group . '_' . $keyName;

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($locale, $group, $keyName) {
            return DB::table('translations')
                ->where('locale', $locale)
                ->where('group_name', $group)
                ->where('key_name', $keyName)
                ->value('value');
        });
    }

    /**
     * Salva una traduzione nel database
     *
     * @param string $key
     * @param string $value
     * @param string $locale
     * @return bool
     */
    public static function set($key, $value, $locale = null)
    {
        $locale = $locale ?: App::getLocale();
        $parts = explode('.', $key);

        if (count($parts) < 2) {
            return false;
        }

        $group = $parts[0];
        $keyName = $parts[1];

        $result = DB::table('translations')->updateOrInsert(
            [
                'locale' => $locale,
                'group_name' => $group,
                'key_name' => $keyName
            ],
            [
                'value' => $value,
                'updated_at' => now()
            ]
        );

        // Pulisce la cache
        self::clearCache($key, $locale);

        return $result;
    }

    /**
     * Elimina una traduzione dal database
     *
     * @param string $key
     * @param string $locale
     * @return bool
     */
    public static function delete($key, $locale = null)
    {
        $locale = $locale ?: App::getLocale();
        $parts = explode('.', $key);

        if (count($parts) < 2) {
            return false;
        }

        $group = $parts[0];
        $keyName = $parts[1];

        $result = DB::table('translations')
            ->where('locale', $locale)
            ->where('group_name', $group)
            ->where('key_name', $keyName)
            ->delete();

        // Pulisce la cache
        self::clearCache($key, $locale);

        return $result > 0;
    }

    /**
     * Ottiene tutte le traduzioni per un gruppo e locale
     *
     * @param string $group
     * @param string $locale
     * @return \Illuminate\Support\Collection
     */
    public static function getGroup($group, $locale = null)
    {
        $locale = $locale ?: App::getLocale();

        return DB::table('translations')
            ->where('locale', $locale)
            ->where('group_name', $group)
            ->get()
            ->pluck('value', 'key_name');
    }

    /**
     * Sincronizza le traduzioni da file a database
     *
     * @param string $group
     * @param string $locale
     * @return int Numero di traduzioni sincronizzate
     */
    public static function syncFromFile($group, $locale)
    {
        $filePath = lang_path($locale . '/' . $group . '.php');

        if (!file_exists($filePath)) {
            return 0;
        }

        $translations = include $filePath;
        $count = 0;

        foreach ($translations as $key => $value) {
            if (is_string($value)) {
                self::set($group . '.' . $key, $value, $locale);
                $count++;
            }
        }

        return $count;
    }

    /**
     * Sincronizza le traduzioni da database a file
     *
     * @param string $group
     * @param string $locale
     * @return bool
     */
    public static function syncToFile($group, $locale)
    {
        $translations = self::getGroup($group, $locale);

        if ($translations->isEmpty()) {
            return false;
        }

        $filePath = lang_path($locale . '/' . $group . '.php');
        $directory = dirname($filePath);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $content = "<?php\n\nreturn [\n";

        foreach ($translations as $key => $value) {
            $content .= "    '{$key}' => " . var_export($value, true) . ",\n";
        }

        $content .= "];\n";

        return file_put_contents($filePath, $content) !== false;
    }

    /**
     * Sostituisce i placeholder in una stringa
     *
     * @param string $text
     * @param array $replace
     * @return string
     */
    private static function replacePlaceholders($text, $replace)
    {
        if (empty($replace)) {
            return $text;
        }

        foreach ($replace as $key => $value) {
            $text = str_replace(':' . $key, $value, $text);
        }

        return $text;
    }

    /**
     * Pulisce la cache per una specifica traduzione
     *
     * @param string $key
     * @param string $locale
     */
    private static function clearCache($key, $locale)
    {
        $parts = explode('.', $key);
        if (count($parts) >= 2) {
            $group = $parts[0];
            $keyName = $parts[1];
            $cacheKey = self::CACHE_PREFIX . $locale . '_' . $group . '_' . $keyName;
            Cache::forget($cacheKey);
        }
    }

    /**
     * Pulisce tutta la cache delle traduzioni
     */
    public static function clearAllCache()
    {
        $pattern = self::CACHE_PREFIX . '*';
        // Nota: Laravel non ha un metodo per pulire per pattern
        // In produzione si potrebbe usare Redis con SCAN
        Cache::flush();
    }

    /**
     * Ottiene statistiche delle traduzioni
     *
     * @param string $locale
     * @return array
     */
    public static function getStats($locale = null)
    {
        $locale = $locale ?: App::getLocale();

        $total = DB::table('translations')
            ->where('locale', $locale)
            ->count();

        $groups = DB::table('translations')
            ->where('locale', $locale)
            ->select('group_name')
            ->distinct()
            ->count();

        $recent = DB::table('translations')
            ->where('locale', $locale)
            ->where('updated_at', '>=', now()->subDays(7))
            ->count();

        return [
            'total' => $total,
            'groups' => $groups,
            'recent' => $recent
        ];
    }
}

