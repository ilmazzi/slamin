<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ArticleCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'color',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'is_active' => 'boolean',
    ];

    // Relazioni
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    // Scopes
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    // Accessors
    public function getNameAttribute($value)
    {
        $name = json_decode($value, true);
        $locale = app()->getLocale();
        return $name[$locale] ?? $name['it'] ?? $name['en'] ?? '';
    }

    public function getDescriptionAttribute($value)
    {
        if (!$value) return null;
        $description = json_decode($value, true);
        $locale = app()->getLocale();
        return $description[$locale] ?? $description['it'] ?? $description['en'] ?? '';
    }

    public function getArticlesCountAttribute()
    {
        return $this->articles()->published()->count();
    }

    // Mutators
    public function setNameAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['name'] = json_encode($value);
        } else {
            $locale = app()->getLocale();
            $name = json_decode($this->attributes['name'] ?? '{}', true);
            $name[$locale] = $value;
            $this->attributes['name'] = json_encode($name);
        }
    }

    public function setDescriptionAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['description'] = json_encode($value);
        } else {
            $locale = app()->getLocale();
            $description = json_decode($this->attributes['description'] ?? '{}', true);
            $description[$locale] = $value;
            $this->attributes['description'] = json_encode($description);
        }
    }

    public function setSlugAttribute($value)
    {
        if (!$value) {
            $name = is_array($this->name) ? $this->name['it'] ?? '' : $this->name;
            $value = Str::slug($name);
        }
        $this->attributes['slug'] = $value;
    }

    // Metodi
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (!$category->slug) {
                $name = is_array($category->name) ? $category->name['it'] ?? '' : $category->name;
                $category->slug = Str::slug($name);
            }
        });
    }
}
