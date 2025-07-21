<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'display_name',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Ottiene il valore di un'impostazione
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = "system_setting_{$key}";

        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return static::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Imposta il valore di un'impostazione
     */
    public static function set(string $key, $value, string $type = 'string'): bool
    {
        $setting = static::firstOrNew(['key' => $key]);

        $setting->value = is_array($value) ? json_encode($value) : (string) $value;
        $setting->type = $type;

        if (!$setting->exists) {
            $setting->display_name = ucfirst(str_replace('_', ' ', $key));
            $setting->group = 'general';
        }

        $result = $setting->save();

        if ($result) {
            Cache::forget("system_setting_{$key}");
        }

        return $result;
    }

    /**
     * Converte il valore in base al tipo
     */
    private static function castValue($value, string $type)
    {
        switch ($type) {
            case 'integer':
                return (int) $value;
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return json_decode($value, true);
            case 'float':
                return (float) $value;
            default:
                return $value;
        }
    }

    /**
     * Ottiene tutte le impostazioni di un gruppo
     */
    public static function getGroup(string $group): array
    {
        return static::where('group', $group)
            ->get()
            ->keyBy('key')
            ->map(function ($setting) {
                return [
                    'value' => static::castValue($setting->value, $setting->type),
                    'type' => $setting->type,
                    'display_name' => $setting->display_name,
                    'description' => $setting->description,
                ];
            })
            ->toArray();
    }

    /**
     * Inizializza le impostazioni di default
     */
    public static function initializeDefaults(): void
    {
        $defaults = [
            // Upload limits
            'profile_photo_max_size' => [
                'value' => '5120', // 5MB in KB
                'type' => 'integer',
                'group' => 'upload',
                'display_name' => 'Dimensione massima foto profilo (KB)',
                'description' => 'Dimensione massima consentita per le foto profilo in kilobyte'
            ],
            'video_max_size' => [
                'value' => '102400', // 100MB in KB
                'type' => 'integer',
                'group' => 'upload',
                'display_name' => 'Dimensione massima video (KB)',
                'description' => 'Dimensione massima consentita per i video in kilobyte'
            ],
            'image_max_size' => [
                'value' => '10240', // 10MB in KB
                'type' => 'integer',
                'group' => 'upload',
                'display_name' => 'Dimensione massima immagini (KB)',
                'description' => 'Dimensione massima consentita per le immagini in kilobyte'
            ],

            // Video limits
            'default_video_limit' => [
                'value' => '3',
                'type' => 'integer',
                'group' => 'video',
                'display_name' => 'Limite video di default',
                'description' => 'Numero di video che gli utenti gratuiti possono caricare'
            ],
            'premium_video_limit' => [
                'value' => '50',
                'type' => 'integer',
                'group' => 'video',
                'display_name' => 'Limite video premium',
                'description' => 'Numero di video che gli utenti premium possono caricare'
            ],

            // File types
            'allowed_image_types' => [
                'value' => json_encode(['jpeg', 'jpg', 'png', 'gif', 'webp']),
                'type' => 'json',
                'group' => 'upload',
                'display_name' => 'Tipi di immagine consentiti',
                'description' => 'Estensioni di file immagine consentite'
            ],
            'allowed_video_types' => [
                'value' => json_encode(['mp4', 'avi', 'mov', 'mkv', 'webm', 'flv']),
                'type' => 'json',
                'group' => 'upload',
                'display_name' => 'Tipi di video consentiti',
                'description' => 'Estensioni di file video consentite'
            ],

            // System settings
            'maintenance_mode' => [
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'system',
                'display_name' => 'Modalità manutenzione',
                'description' => 'Attiva la modalità manutenzione del sito'
            ],
            'registration_enabled' => [
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'system',
                'display_name' => 'Registrazione utenti abilitata',
                'description' => 'Consenti la registrazione di nuovi utenti'
            ],
        ];

        foreach ($defaults as $key => $config) {
            if (!static::where('key', $key)->exists()) {
                static::create([
                    'key' => $key,
                    'value' => $config['value'],
                    'type' => $config['type'],
                    'group' => $config['group'],
                    'display_name' => $config['display_name'],
                    'description' => $config['description'],
                ]);
            }
        }
    }
}
