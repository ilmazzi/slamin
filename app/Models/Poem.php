<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Poem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'description',
        'thumbnail',
        'thumbnail_path',
        'user_id',
        'is_public',
        'moderation_status',
        'moderation_notes',
        'view_count',
        'like_count',
        'comment_count',
        'tags',
        'language',
        'category',
        'is_featured',
        'published_at',
        'original_language',
        'translated_from',
        'translation_price',
        'translation_available',
        'translation_requests',
        'poem_type',
        'word_count',
        'is_draft',
        'draft_saved_at',
        'share_count',
        'bookmark_count',
        'seo_meta',
        'slug',
        'is_premium',
        'price',
        'donation_info'
    ];

    protected $casts = [
        'tags' => 'array',
        'translation_requests' => 'array',
        'seo_meta' => 'array',
        'donation_info' => 'array',
        'is_public' => 'boolean',
        'is_featured' => 'boolean',
        'translation_available' => 'boolean',
        'is_draft' => 'boolean',
        'is_premium' => 'boolean',
        'published_at' => 'datetime',
        'draft_saved_at' => 'datetime',
        'translation_price' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    protected $dates = [
        'published_at',
        'draft_saved_at'
    ];

    // Relazioni
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function originalPoem(): BelongsTo
    {
        return $this->belongsTo(Poem::class, 'translated_from');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(Poem::class, 'translated_from');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'poem_likes')->withTimestamps();
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'poem_bookmarks')->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PoemComment::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_public', true)
                    ->where('moderation_status', 'approved')
                    ->where('is_draft', false)
                    ->whereNotNull('published_at');
    }

    public function scopeDrafts($query)
    {
        return $query->where('is_draft', true);
    }

    public function scopePending($query)
    {
        return $query->where('moderation_status', 'pending');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('poem_type', $type);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('view_count', 'desc')
                    ->orderBy('like_count', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    // Accessors
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return asset('storage/' . $this->thumbnail_path);
        }
        return asset('assets/images/background/default-poem.webp');
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    public function getIsLikedByCurrentUserAttribute()
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->likes()->where('user_id', Auth::id())->exists();
    }

    public function getIsBookmarkedByCurrentUserAttribute()
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->bookmarks()->where('user_id', Auth::id())->exists();
    }

    public function getCanBeTranslatedAttribute()
    {
        return $this->translation_available && $this->translation_price > 0;
    }

    public function getTranslationRequestCountAttribute()
    {
        return $this->translation_requests ? count($this->translation_requests) : 0;
    }

    // Mutators
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($poem) {
            if (empty($poem->slug)) {
                $poem->slug = Str::slug($poem->title);
            }
            if (empty($poem->word_count)) {
                $poem->word_count = str_word_count(strip_tags($poem->content));
            }
        });

        static::updating(function ($poem) {
            if ($poem->isDirty('content')) {
                $poem->word_count = str_word_count(strip_tags($poem->content));
            }
            if ($poem->isDirty('title') && empty($poem->slug)) {
                $poem->slug = Str::slug($poem->title);
            }
        });
    }

    // Metodi
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function incrementLikeCount()
    {
        $this->increment('like_count');
    }

    public function decrementLikeCount()
    {
        $this->decrement('like_count');
    }

    public function incrementShareCount()
    {
        $this->increment('share_count');
    }

    public function incrementBookmarkCount()
    {
        $this->increment('bookmark_count');
    }

    public function decrementBookmarkCount()
    {
        $this->decrement('bookmark_count');
    }

    public function canBeEditedBy($user)
    {
        return $user && ($user->id === $this->user_id || $user->hasRole('admin'));
    }

    public function canBeDeletedBy($user)
    {
        return $user && ($user->id === $this->user_id || $user->hasRole('admin'));
    }

    public function canBeModeratedBy($user)
    {
        return $user && $user->hasRole('admin');
    }

    public function isPublished()
    {
        return $this->is_public && 
               $this->moderation_status === 'approved' && 
               !$this->is_draft && 
               $this->published_at !== null;
    }

    public function isDraft()
    {
        return $this->is_draft;
    }

    public function isPending()
    {
        return $this->moderation_status === 'pending';
    }

    public function isRejected()
    {
        return $this->moderation_status === 'rejected';
    }

    public function isTranslated()
    {
        return $this->translated_from !== null;
    }

    public function isOriginal()
    {
        return $this->translated_from === null;
    }
}
