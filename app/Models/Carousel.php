<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Carousel extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'video_path',
        'link_url',
        'link_text',
        'order',
        'is_active',
        'start_date',
        'end_date',
        // Nuovi campi per contenuti esistenti
        'content_type',
        'content_id',
        'content_title',
        'content_description',
        'content_image_url',
        'content_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // Costanti per i tipi di contenuto
    const CONTENT_TYPE_VIDEO = 'video';
    const CONTENT_TYPE_EVENT = 'event';
    const CONTENT_TYPE_USER = 'user';
    const CONTENT_TYPE_SNAP = 'snap';

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

        // Se il percorso inizia già con http, restituiscilo così com'è
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
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
            case self::CONTENT_TYPE_SNAP:
                return VideoSnap::find($this->content_id);
            default:
                return null;
        }
    }

    /**
     * Update content cache from referenced content
     */
    public function updateContentCache()
    {
        if (!$this->isContentReference()) {
            return;
        }

        $content = $this->getReferencedContent();
        if (!$content) {
            return;
        }

        $cacheData = $this->getContentCacheData($content);
        $this->update($cacheData);
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
                    'content_image_url' => $content->profile_photo_url,
                    'content_url' => route('user.show', $content),
                ];

            case self::CONTENT_TYPE_SNAP:
                return [
                    'content_title' => $content->title ?: "Snap di {$content->video->title}",
                    'content_description' => $content->description,
                    'content_image_url' => $content->thumbnail_url,
                    'content_url' => route('videos.show', $content->video) . "#snap-{$content->id}",
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
            self::CONTENT_TYPE_SNAP => 'Snap',
        ];
    }
}
