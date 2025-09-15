<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Traits\Reportable;
use App\Traits\HasModeration;
use App\Traits\HasLikes;
use App\Traits\HasViews;
use App\Traits\HasComments;
use App\Helpers\PlaceholderHelper;
use App\Models\PoemTranslation;

class Poem extends Model
{
    use HasFactory, Reportable, HasModeration, HasLikes, HasViews, HasComments;

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
        'moderated_by',
        'moderated_at',
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
        'seo_meta' => 'array',
        'donation_info' => 'array',
        'is_public' => 'boolean',
        'is_featured' => 'boolean',
        'is_draft' => 'boolean',
        'is_premium' => 'boolean',
        'published_at' => 'datetime',
        'draft_saved_at' => 'datetime',
        'moderated_at' => 'datetime',
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

    public function poemTranslations(): HasMany
    {
        return $this->hasMany(PoemTranslation::class);
    }

    public function gigs(): HasMany
    {
        return $this->hasMany(Gig::class);
    }

    // Relazioni like e commenti gestite dai trait HasLikes e HasComments

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'poem_bookmarks')->withTimestamps();
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_public', true)
                    ->where(function($q) {
                        $q->where('moderation_status', 'approved')
                          ->orWhereNull('moderation_status');
                    })
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

        // Restituisce null se non c'è immagine, così il template può gestire il placeholder
        return null;
    }

    /**
     * Genera HTML per il placeholder se non c'è immagine
     */
    public function getThumbnailHtmlAttribute()
    {
        if ($this->thumbnail_path) {
            return '<img src="' . asset('storage/' . $this->thumbnail_path) . '" alt="' . htmlspecialchars($this->title) . '" class="img-fluid">';
        }

        // Usa il placeholder HTML personalizzato
        return PlaceholderHelper::getPoemPlaceholderHtml();
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

    // Metodi per gestione traduzioni
    public function getAvailableLanguagesAttribute()
    {
        $languages = collect();

        // Aggiungi la lingua originale
        $languages->push([
            'code' => $this->original_language ?: $this->language,
            'name' => $this->getLanguageName($this->original_language ?: $this->language),
            'is_original' => true,
            'is_official' => true
        ]);

        // Carica le traduzioni approvate dal database
        $translations = PoemTranslation::where('poem_id', $this->id)
            ->where('status', 'approved')
            ->whereNotNull('completed_at')
            ->get();

        foreach ($translations as $translation) {
            $languages->push([
                'code' => $translation->language,
                'name' => $this->getLanguageName($translation->language),
                'is_original' => false,
                'is_official' => true
            ]);
        }

        return $languages->unique('code')->values();
    }


    public function canBeTranslatedBy($user)
    {
        if (!$user) return false;

        // L'autore può sempre tradurre
        if ($this->user_id === $user->id) return true;

        return false;
    }


    public function getLanguageName($code)
    {
        $languages = config('poems.languages', []);
        return $languages[$code] ?? ucfirst($code);
    }



}
