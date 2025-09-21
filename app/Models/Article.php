<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Traits\HasLikes;
use App\Traits\HasComments;
use App\Traits\HasViews;
use App\Traits\HasModeration;
use App\Traits\Reportable;
use App\Helpers\PlaceholderHelper;

class Article extends Model
{
    use HasFactory, HasLikes, HasComments, HasViews, HasModeration, Reportable;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'excerpt',
        'featured_image',
        'status',
        'moderation_status',
        'is_public',
        'moderation_notes',
        'moderated_by',
        'moderated_at',
        'featured',
        'views_count',
        'likes_count',
        'comments_count',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
        'language',
        'original_language',
        'is_news',
        'needs_translation',
        'translation_status',
        'translated_at',
    ];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
        'excerpt' => 'array',
        'meta_title' => 'array',
        'meta_description' => 'array',
        'meta_keywords' => 'array',
        'featured' => 'boolean',
        'is_public' => 'boolean',
        'published_at' => 'datetime',
        'moderated_at' => 'datetime',
        'is_news' => 'boolean',
        'needs_translation' => 'boolean',
        'translation_status' => 'array',
        'translated_at' => 'datetime',
    ];

    protected $dates = [
        'published_at',
    ];

    // Relazioni
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ArticleTag::class, 'article_tag', 'article_id', 'article_tag_id')
                    ->withTimestamps();
    }



    public function reports(): HasMany
    {
        return $this->hasMany(ArticleReport::class, 'article_id');
    }

    public function layoutPositions(): HasMany
    {
        return $this->hasMany(ArticleLayout::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ArticleTranslation::class);
    }

    // Scopes
    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published')
              ->where('published_at', '<=', now());
    }

    public function scopeDraft(Builder $query): void
    {
        $query->where('status', 'draft');
    }

    public function scopeArchived(Builder $query): void
    {
        $query->where('status', 'archived');
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('featured', true);
    }

    public function scopeNotFeatured(Builder $query): void
    {
        $query->where('featured', false);
    }

    public function scopeByCategory(Builder $query, $categoryId): void
    {
        $query->where('category_id', $categoryId);
    }

    public function scopeByTag(Builder $query, $tagId): void
    {
        $query->whereHas('tags', function ($q) use ($tagId) {
            $q->where('article_tags.id', $tagId);
        });
    }

    public function scopeSearch(Builder $query, $search): void
    {
        $query->where(function ($q) use ($search) {
            $q->whereJsonContains('title', ['it' => $search])
              ->orWhereJsonContains('title', ['en' => $search])
              ->orWhereJsonContains('content', ['it' => $search])
              ->orWhereJsonContains('content', ['en' => $search])
              ->orWhereJsonContains('excerpt', ['it' => $search])
              ->orWhereJsonContains('excerpt', ['en' => $search]);
        });
    }

    public function scopePopular(Builder $query): void
    {
        $query->orderBy('views_count', 'desc')
              ->orderBy('likes_count', 'desc')
              ->orderBy('comments_count', 'desc');
    }

    public function scopeNews(Builder $query): void
    {
        $query->where('is_news', true);
    }

    public function scopeNeedsTranslation(Builder $query): void
    {
        $query->where('needs_translation', true);
    }

    public function scopeByLanguage(Builder $query, $language): void
    {
        $query->where('language', $language);
    }

    public function scopeByOriginalLanguage(Builder $query, $language): void
    {
        $query->where('original_language', $language);
    }

    public function scopeRecent(Builder $query): void
    {
        $query->orderBy('published_at', 'desc');
    }

    // Accessors
    public function getTitleAttribute($value)
    {
        $title = json_decode($value, true);
        $locale = app()->getLocale();
        return $title[$locale] ?? $title['it'] ?? $title['en'] ?? '';
    }

    public function getContentAttribute($value)
    {
        $content = json_decode($value, true);
        $locale = app()->getLocale();
        return $content[$locale] ?? $content['it'] ?? $content['en'] ?? '';
    }

    public function getExcerptAttribute($value)
    {
        if (!$value) return null;
        $excerpt = json_decode($value, true);
        $locale = app()->getLocale();
        return $excerpt[$locale] ?? $excerpt['it'] ?? $excerpt['en'] ?? '';
    }

    public function getMetaTitleAttribute($value)
    {
        if (!$value) return $this->title;
        $metaTitle = json_decode($value, true);
        $locale = app()->getLocale();
        return $metaTitle[$locale] ?? $metaTitle['it'] ?? $metaTitle['en'] ?? $this->title;
    }

    public function getMetaDescriptionAttribute($value)
    {
        if (!$value) return $this->excerpt;
        $metaDescription = json_decode($value, true);
        $locale = app()->getLocale();
        return $metaDescription[$locale] ?? $metaDescription['it'] ?? $metaDescription['en'] ?? $this->excerpt;
    }

    public function getMetaKeywordsAttribute($value)
    {
        if (!$value) return '';
        $metaKeywords = json_decode($value, true);
        $locale = app()->getLocale();
        return $metaKeywords[$locale] ?? $metaKeywords['it'] ?? $metaKeywords['en'] ?? '';
    }

    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->featured_image) {
            // Restituisce null se non c'è immagine, così il template può gestire il placeholder
            return null;
        }
        return asset('storage/' . $this->featured_image);
    }

    /**
     * Genera HTML per il placeholder se non c'è immagine
     */
    public function getFeaturedImageHtmlAttribute()
    {
        if ($this->featured_image) {
            return '<img src="' . asset('storage/' . $this->featured_image) . '" alt="' . htmlspecialchars($this->title) . '" class="img-fluid">';
        }

        // Usa il placeholder HTML personalizzato
        return PlaceholderHelper::getArticlePlaceholderHtml();
    }

    public function getReadTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readTime = ceil($wordCount / 200); // 200 parole al minuto
        return max(1, $readTime);
    }

    public function getIsPublishedAttribute()
    {
        return $this->status === 'published' && $this->published_at && $this->published_at <= now();
    }

    public function getCanEditAttribute()
    {
        if (!\Illuminate\Support\Facades\Auth::check()) return false;
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user->id === $this->user_id ||
               $user->hasRole(['admin', 'editor']) ||
               $user->can('articles.edit');
    }

    public function getCanDeleteAttribute()
    {
        if (!\Illuminate\Support\Facades\Auth::check()) return false;
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user->id === $this->user_id ||
               $user->hasRole(['admin', 'editor']) ||
               $user->can('articles.delete');
    }

    public function canBeDeletedBy($user)
    {
        if (!$user) return false;

        return $user->id === $this->user_id ||
               $user->hasRole(['admin', 'editor', 'moderator', 'organizer']) ||
               $user->hasPermissionTo('articles.delete');
    }

    // Mutators
    public function setTitleAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['title'] = json_encode($value);
        } else {
            $locale = app()->getLocale();
            $title = json_decode($this->attributes['title'] ?? '{}', true);
            $title[$locale] = $value;
            $this->attributes['title'] = json_encode($title);
        }
    }

    public function setContentAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['content'] = json_encode($value);
        } else {
            $locale = app()->getLocale();
            $content = json_decode($this->attributes['content'] ?? '{}', true);
            $content[$locale] = $value;
            $this->attributes['content'] = json_encode($content);
        }
    }

    public function setExcerptAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['excerpt'] = json_encode($value);
        } else {
            $locale = app()->getLocale();
            $excerpt = json_decode($this->attributes['excerpt'] ?? '{}', true);
            $excerpt[$locale] = $value;
            $this->attributes['excerpt'] = json_encode($excerpt);
        }
    }

    public function setSlugAttribute($value)
    {
        if (!$value) {
            $title = is_array($this->title) ? $this->title['it'] ?? '' : $this->title;
            $value = Str::slug($title);
        }
        $this->attributes['slug'] = $value;
    }

    // Metodi
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function unpublish()
    {
        $this->update([
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function archive()
    {
        $this->update(['status' => 'archived']);
    }

    public function toggleFeatured()
    {
        $this->update(['featured' => !$this->featured]);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getContentUrl(): string
    {
        return route('articles.show', $this);
    }

    /**
     * Verifica se l'articolo è stato segnalato dall'utente
     */
    public function isReportedByUser(?User $user = null): bool
    {
        if (!$user) {
            $user = \Illuminate\Support\Facades\Auth::user();
        }

        if (!$user) {
            return false;
        }

        return $this->reports()->where('user_id', $user->id)->exists();
    }

    /**
     * Ottiene il numero di segnalazioni attive
     */
    public function getActiveReportsCountAttribute(): int
    {
        return $this->reports()->where('status', 'active')->count();
    }





    // Boot method per eventi
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (!$article->slug) {
                $title = is_array($article->title) ? $article->title['it'] ?? '' : $article->title;
                $article->slug = Str::slug($title);
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title') && !$article->isDirty('slug')) {
                $title = is_array($article->title) ? $article->title['it'] ?? '' : $article->title;
                $article->slug = Str::slug($title);
            }
        });
    }
}
