<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PeerTubeConfig extends Model
{
    protected $table = 'peertube_configs';
    
    protected $fillable = [
        'key', 'value', 'type', 'description', 'is_encrypted'
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
    ];

    /**
     * Get the value attribute with automatic decryption
     */
    public function getValueAttribute($value)
    {
        if ($this->is_encrypted && $value) {
            try {
                $value = Crypt::decryptString($value);
            } catch (\Exception $e) {
                // Se la decrittazione fallisce, ritorna il valore originale
                return $value;
            }
        }

        // Converti il valore in base al tipo
        switch ($this->type) {
            case 'json':
                return json_decode($value, true);
            case 'boolean':
                return (bool) $value;
            case 'integer':
                return (int) $value;
            case 'datetime':
                return $value ? \Carbon\Carbon::parse($value) : null;
            default:
                return $value;
        }
    }

    /**
     * Set the value attribute with automatic encryption
     */
    public function setValueAttribute($value)
    {
        // Converti il valore in stringa se necessario
        if ($this->type === 'json' && is_array($value)) {
            $value = json_encode($value);
        } elseif ($this->type === 'boolean') {
            $value = (bool) $value;
        } elseif ($this->type === 'integer') {
            $value = (int) $value;
        } elseif ($this->type === 'datetime' && $value instanceof \Carbon\Carbon) {
            $value = $value->toDateTimeString();
        }

        // Cripta se necessario
        if ($this->is_encrypted && $value !== null) {
            $value = Crypt::encryptString($value);
        }

        $this->attributes['value'] = $value;
    }

    /**
     * Get a configuration value by key
     */
    public static function getValue($key, $default = null)
    {
        $config = self::where('key', $key)->first();
        
        if (!$config) {
            return $default;
        }

        return $config->value;
    }

    /**
     * Set a configuration value by key
     */
    public static function setValue($key, $value, $type = 'string', $description = null, $isEncrypted = false)
    {
        $config = self::where('key', $key)->first();
        
        if (!$config) {
            $config = new self();
            $config->key = $key;
            $config->type = $type;
            $config->description = $description;
            $config->is_encrypted = $isEncrypted;
        }

        $config->value = $value;
        $config->save();

        return $config;
    }

    /**
     * Get all configurations as an array
     */
    public static function getAllAsArray()
    {
        $configs = self::all();
        $result = [];

        foreach ($configs as $config) {
            $result[$config->key] = $config->value;
        }

        return $result;
    }

    /**
     * Check if PeerTube is configured
     */
    public static function isConfigured()
    {
        $url = self::getValue('peertube_url');
        $username = self::getValue('peertube_admin_username');
        $password = self::getValue('peertube_admin_password');
        
        return !empty($url) && !empty($username) && !empty($password);
    }

    /**
     * Check if access token is valid and not expired
     */
    public static function hasValidToken()
    {
        $token = self::getValue('peertube_access_token');
        $expiresAt = self::getValue('peertube_token_expires_at');
        
        if (empty($token)) {
            return false;
        }

        if ($expiresAt && $expiresAt->isPast()) {
            return false;
        }

        return true;
    }
} 