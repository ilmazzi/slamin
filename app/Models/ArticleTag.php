<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ArticleTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'is_active',
        'usage_count',
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
    ];

    // Relazioni
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_tag', 'article_tag_id', 'article_id')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopePopular(Builder $query): void
    {
        $query->orderBy('usage_count', 'desc');
    }

    // Accessors
    public function getNameAttribute($value)
    {
        $name = json_decode($value, true);
        $locale = app()->getLocale();
        return $name[$locale] ?? $name['it'] ?? $name['en'] ?? '';
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

    public function setSlugAttribute($value)
    {
        if (!$value) {
            $name = is_array($this->name) ? $this->name['it'] ?? '' : $this->name;
            $value = Str::slug($name);
        }
        $this->attributes['slug'] = $value;
    }

    // Metodi
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    public function decrementUsage()
    {
        $this->decrement('usage_count');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (!$tag->slug) {
                $name = is_array($tag->name) ? $tag->name['it'] ?? '' : $tag->name;
                $tag->slug = Str::slug($name);
            }
        });
    }
}
