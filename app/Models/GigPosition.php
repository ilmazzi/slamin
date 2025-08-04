<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GigPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope per posizioni attive
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope per ordinare per sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Ottiene tutte le posizioni attive ordinate
     */
    public static function getActivePositions()
    {
        return static::active()->ordered()->get();
    }

    /**
     * Ottiene le posizioni come array per i select
     */
    public static function getPositionsForSelect()
    {
        return static::getActivePositions()->pluck('name', 'key')->toArray();
    }

    /**
     * Relazione con i gigs che usano questa posizione
     */
    public function gigs()
    {
        return $this->hasMany(Gig::class, 'type', 'key');
    }
}
