<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleTranslation extends Model
{
    protected $fillable = [
        'article_id',
        'language',
        'title',
        'content',
        'excerpt',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'translator_id',
        'translation_type',
        'translation_metadata',
        'translated_at'
    ];

    protected $casts = [
        'translation_metadata' => 'array',
        'translated_at' => 'datetime'
    ];

    /**
     * Get the article that owns this translation
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the translator who created this translation
     */
    public function translator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'translator_id');
    }

    /**
     * Scope for published translations
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for specific language
     */
    public function scopeLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    /**
     * Scope for automatic translations
     */
    public function scopeAutomatic($query)
    {
        return $query->where('translation_type', 'automatic');
    }

    /**
     * Scope for manual translations
     */
    public function scopeManual($query)
    {
        return $query->where('translation_type', 'manual');
    }
}
