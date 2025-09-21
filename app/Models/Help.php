<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
    protected $fillable = [
        'type',
        'title',
        'content',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    // Scope per filtrare per tipo
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope per contenuti attivi
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope per ordinare
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('title');
    }
}
