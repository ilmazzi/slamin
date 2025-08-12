<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class ArticleLayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'position',
        'article_id',
        'order',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    // Relazioni
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    // Scopes
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeByPosition(Builder $query, $position): void
    {
        $query->where('position', $position);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('order', 'asc');
    }

    // Metodi
    public static function getPositions()
    {
        return [
            'banner' => 'Banner Principale',
            'column1' => 'Colonna 1',
            'column2' => 'Colonna 2',
            'horizontal1' => 'Orizzontale 1',
            'horizontal2' => 'Orizzontale 2',
            'sidebar1' => 'Sidebar 1',
            'sidebar2' => 'Sidebar 2',
            'sidebar3' => 'Sidebar 3',
            'sidebar4' => 'Sidebar 4',
            'sidebar5' => 'Sidebar 5',
        ];
    }

    public function getPositionNameAttribute()
    {
        $positions = self::getPositions();
        return $positions[$this->position] ?? $this->position;
    }

    public static function getLayoutForPosition($position)
    {
        return self::where('position', $position)
                  ->where('is_active', true)
                  ->orderBy('order', 'asc')
                  ->with('article')
                  ->get();
    }

    public static function updateLayout($position, $articleId, $order = 0)
    {
        // Rimuovi layout esistenti per questa posizione
        self::where('position', $position)->delete();

        // Crea nuovo layout
        if ($articleId) {
            return self::create([
                'position' => $position,
                'article_id' => $articleId,
                'order' => $order,
                'is_active' => true,
            ]);
        }

        return null;
    }
}
