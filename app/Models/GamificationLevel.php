<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamificationLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'name',
        'description',
        'required_points',
        'required_badges',
        'icon_path',
        'perks',
        'order',
    ];

    protected $casts = [
        'level' => 'integer',
        'required_points' => 'integer',
        'required_badges' => 'integer',
        'perks' => 'array',
        'order' => 'integer',
    ];

    /**
     * Get icon URL
     */
    public function getIconUrlAttribute(): ?string
    {
        if ($this->icon_path && file_exists(public_path($this->icon_path))) {
            return asset($this->icon_path);
        }
        
        return null;
    }

    /**
     * Scope: ordered by level
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('level');
    }

    /**
     * Get next level
     */
    public function getNextLevelAttribute()
    {
        return static::where('level', $this->level + 1)->first();
    }

    /**
     * Get previous level
     */
    public function getPreviousLevelAttribute()
    {
        return static::where('level', $this->level - 1)->first();
    }
}

