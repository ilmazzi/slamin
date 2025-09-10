<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaceholderSetting extends Model
{
    protected $fillable = [
        'poem_placeholder_color',
        'article_placeholder_color',
    ];

    protected $casts = [
        'poem_placeholder_color' => 'string',
        'article_placeholder_color' => 'string',
    ];

    /**
     * Ottieni le impostazioni dei placeholder (singleton)
     */
    public static function getSettings()
    {
        return static::first() ?? static::create([
            'poem_placeholder_color' => '#6c757d',
            'article_placeholder_color' => '#007bff',
        ]);
    }

    /**
     * Aggiorna le impostazioni dei placeholder
     */
    public static function updateSettings(array $data)
    {
        $settings = static::getSettings();
        $settings->update($data);
        return $settings;
    }
}
