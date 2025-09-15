<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Translation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'locale',
        'group_name',
        'key_name',
        'value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope per filtrare per locale
     */
    public function scopeLocale(Builder $query, string $locale): Builder
    {
        return $query->where('locale', $locale);
    }

    /**
     * Scope per filtrare per gruppo
     */
    public function scopeGroup(Builder $query, string $group): Builder
    {
        return $query->where('group_name', $group);
    }

    /**
     * Scope per cercare per chiave
     */
    public function scopeKey(Builder $query, string $key): Builder
    {
        return $query->where('key_name', 'like', "%{$key}%");
    }

    /**
     * Scope per cercare per valore
     */
    public function scopeValue(Builder $query, string $value): Builder
    {
        return $query->where('value', 'like', "%{$value}%");
    }

    /**
     * Ottiene la chiave completa (group.key)
     */
    public function getFullKeyAttribute(): string
    {
        return $this->group_name . '.' . $this->key_name;
    }

    /**
     * Ottiene il nome del file di traduzione
     */
    public function getFileNameAttribute(): string
    {
        return $this->locale . '/' . $this->group_name . '.php';
    }

    /**
     * Verifica se la traduzione è vuota
     */
    public function isEmpty(): bool
    {
        return empty(trim($this->value));
    }

    /**
     * Verifica se la traduzione è completa
     */
    public function isComplete(): bool
    {
        return !$this->isEmpty();
    }
}
