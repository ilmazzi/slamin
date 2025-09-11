<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\HasModeration;
use App\Traits\Reportable;
use App\Traits\HasLikes;
use App\Traits\HasViews;
use App\Traits\HasComments;

class Carousel extends Model
{
    use HasFactory, HasModeration, Reportable, HasLikes, HasViews, HasComments;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'video_path',
        'link_url',
        'link_text',
        'order',
        'is_active',
        'moderation_status',
        'moderation_notes',
        'moderated_by',
        'moderated_at',
        'start_date',
        'end_date',
        // Nuovi campi per contenuti esistenti
        'content_type',
        'content_id',
        'content_title',
        'content_description',
        'content_image_url',
        'content_url',
        'like_count',
        'comment_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'moderated_at' => 'datetime',
        'like_count' => 'integer',
        'comment_count' => 'integer',
    ];

    // Costanti per i tipi di contenuto
    const CONTENT_TYPE_VIDEO = 'video';
    const CONTENT_TYPE_EVENT = 'event';
    const CONTENT_TYPE_USER = 'user';
    const CONTENT_TYPE_POEM = 'poem';
    const CONTENT_TYPE_ARTICLE = 'article';

    /**
     * Scope per i carousel attivi
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('start_date')
                          ->orWhere('start_date', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    });
    }

    /**
     * Scope per ordinare per ordine
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Scope per contenuti esistenti
     */
    public function scopeWithContent($query)
    {
        return $query->whereNotNull('content_type')->whereNotNull('content_id');
    }

    /**
     * Scope per contenuti caricati (non referenziati)
     */
    public function scopeWithUploadedContent($query)
    {
        return $query->whereNull('content_type')->whereNull('content_id');
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        // Se è un contenuto referenziato, usa l'immagine del contenuto
        if ($this->content_image_url) {
            return $this->content_image_url;
        }

        if (!$this->image_path) {
            return null;
        }

        // Se il percorso inizia già con http, controlla se il file esiste
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            // Per URL esterni, restituisci sempre l'URL (non possiamo controllare l'esistenza)
            return $this->image_path;
        }

        // Controlla se il file esiste localmente
        $filePath = public_path('storage/' . $this->image_path);
        if (!file_exists($filePath)) {
            return null;
        }

        // Altrimenti usa Storage::url
        return Storage::url($this->image_path);
    }

    /**
     * Get video URL
     */
    public function getVideoUrlAttribute()
    {
        if (!$this->video_path) {
            return null;
        }

        // Se il percorso inizia già con http, restituiscilo così com'è
        if (filter_var($this->video_path, FILTER_VALIDATE_URL)) {
            return $this->video_path;
        }

        // Altrimenti usa Storage::url
        return Storage::url($this->video_path);
    }

    /**
     * Get display title (contenuto o titolo personalizzato)
     */
    public function getDisplayTitleAttribute()
    {
        return $this->content_title ?: $this->title;
    }

    /**
     * Get display description (contenuto o descrizione personalizzata)
     */
    public function getDisplayDescriptionAttribute()
    {
        return $this->content_description ?: $this->description;
    }

    /**
     * Get display URL (contenuto o link personalizzato)
     */
    public function getDisplayUrlAttribute()
    {
        return $this->content_url ?: $this->link_url;
    }

    /**
     * Check if carousel is currently active
     */
    public function isCurrentlyActive()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->start_date && $this->start_date->isFuture()) {
            return false;
        }

        if ($this->end_date && $this->end_date->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Check if this carousel references existing content
     */
    public function isContentReference()
    {
        return !empty($this->content_type) && !empty($this->content_id);
    }

    /**
     * Get the referenced content model
     */
    public function getReferencedContent()
    {
        if (!$this->isContentReference()) {
            return null;
        }

        switch ($this->content_type) {
            case self::CONTENT_TYPE_VIDEO:
                return Video::find($this->content_id);
            case self::CONTENT_TYPE_EVENT:
                return Event::find($this->content_id);
            case self::CONTENT_TYPE_USER:
                return User::find($this->content_id);
            case self::CONTENT_TYPE_POEM:
                return Poem::find($this->content_id);
            case self::CONTENT_TYPE_ARTICLE:
                return Article::find($this->content_id);
            default:
                return null;
        }
    }

    /**
     * Update content cache from referenced content
     * Preserva le personalizzazioni dell'utente (title, description, link_url)
     */
    public function updateContentCache()
    {
        if (!$this->isContentReference()) {
            return;
        }

        $content = $this->getReferencedContent();
        if (!$content) {
            // Se il contenuto non esiste più, pulisci la cache
            $this->update([
                'content_title' => null,
                'content_description' => null,
                'content_image_url' => null,
                'content_url' => null,
            ]);
            return;
        }

        $cacheData = $this->getContentCacheData($content);
        
        // Preserva le personalizzazioni dell'utente se esistono
        $updateData = [];
        
        // Solo se non c'è un titolo personalizzato, usa quello del contenuto
        if (empty($this->title) || $this->title === $this->content_title) {
            $updateData['content_title'] = $cacheData['content_title'] ?? null;
        }
        
        // Solo se non c'è una descrizione personalizzata, usa quella del contenuto
        if (empty($this->description) || $this->description === $this->content_description) {
            $description = $cacheData['content_description'] ?? null;
            // Tronca la descrizione se è troppo lunga (limite di 500 caratteri per il carosello)
            if ($description && strlen($description) > 500) {
                $description = \Illuminate\Support\Str::limit($description, 500, '...');
            }
            $updateData['content_description'] = $description;
        }
        
        // Solo se non c'è un link personalizzato, usa quello del contenuto
        if (empty($this->link_url) || $this->link_url === $this->content_url) {
            $updateData['content_url'] = $cacheData['content_url'] ?? null;
        }
        
        // L'immagine viene sempre aggiornata dalla cache
        $updateData['content_image_url'] = $cacheData['content_image_url'] ?? null;
        
        if (!empty($updateData)) {
            $this->update($updateData);
        }
    }

    /**
     * Get content cache data for a specific content type
     */
    protected function getContentCacheData($content)
    {
        switch ($this->content_type) {
            case self::CONTENT_TYPE_VIDEO:
                return [
                    'content_title' => $content->title,
                    'content_description' => $content->description,
                    'content_image_url' => $content->thumbnail_url,
                    'content_url' => route('videos.show', $content),
                ];

            case self::CONTENT_TYPE_EVENT:
                return [
                    'content_title' => $content->title,
                    'content_description' => $content->description,
                    'content_image_url' => $content->image_url,
                    'content_url' => route('events.show', $content),
                ];

            case self::CONTENT_TYPE_USER:
                return [
                    'content_title' => $content->getDisplayName(),
                    'content_description' => $content->bio,
                    'content_image_url' => $content->profile_photo ? asset('storage/' . $content->profile_photo) : null,
                    'content_url' => route('user.show', $content),
                ];

        case self::CONTENT_TYPE_POEM:
            return [
                'content_title' => $content->title,
                'content_description' => $content->description,
                'content_image_url' => $content->thumbnail_url,
                'content_url' => route('poems.show', $content),
            ];

            case self::CONTENT_TYPE_ARTICLE:
                return [
                    'content_title' => $content->title,
                    'content_description' => $content->excerpt,
                    'content_image_url' => $content->featured_image_url,
                    'content_url' => route('articles.show', $content),
                ];

            default:
                return [];
        }
    }

    /**
     * Get available content types
     */
    public static function getAvailableContentTypes()
    {
        return [
            self::CONTENT_TYPE_VIDEO => 'Video',
            self::CONTENT_TYPE_EVENT => 'Eventi',
            self::CONTENT_TYPE_USER => 'Utenti',
            self::CONTENT_TYPE_POEM => 'Poesie',
            self::CONTENT_TYPE_ARTICLE => 'Articoli',
        ];
    }
}
