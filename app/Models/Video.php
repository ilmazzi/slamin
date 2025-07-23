<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'video_url',
        'thumbnail',
        'thumbnail_path',
        'file_path',
        'user_id',
        'views',
        'is_public',
        'status',
        'tags',
        // Campi PeerTube esistenti
        'peertube_id',
        'peertube_uuid',
        'peertube_url',
        'peertube_embed_url',
        'peertube_thumbnail_url',
        'duration',
        'resolution',
        'file_size',
        'view_count',
        'like_count',
        'dislike_count',
        'comment_count',
        'moderation_status',
        'moderation_notes',
        // Nuovi campi PeerTube
        'peertube_access_token',
        'peertube_channel_id',
        'peertube_account_id',
        'upload_status',
        'upload_error',
        'uploaded_at',
        'last_sync_at',
        'needs_sync',
        'peertube_privacy',
        'peertube_tags',
        'peertube_description',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'views' => 'integer',
        'duration' => 'integer',
        'file_size' => 'integer',
        'view_count' => 'integer',
        'like_count' => 'integer',
        'dislike_count' => 'integer',
        'comment_count' => 'integer',
        'needs_sync' => 'boolean',
        'peertube_tags' => 'array',
        'uploaded_at' => 'datetime',
        'last_sync_at' => 'datetime',
    ];

    /**
     * Relazione con l'utente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione con i commenti
     */
    public function comments()
    {
        return $this->hasMany(VideoComment::class);
    }

    /**
     * Relazione con i commenti approvati
     */
    public function approvedComments()
    {
        return $this->hasMany(VideoComment::class)->approved();
    }

    /**
     * Relazione con gli snap
     */
    public function snaps()
    {
        return $this->hasMany(VideoSnap::class);
    }

    /**
     * Relazione con gli snap approvati
     */
    public function approvedSnaps()
    {
        return $this->hasMany(VideoSnap::class)->approved();
    }

    /**
     * Relazione con i like
     */
    public function likes()
    {
        return $this->hasMany(VideoLike::class);
    }

    /**
     * Relazione con i like positivi
     */
    public function positiveLikes()
    {
        return $this->hasMany(VideoLike::class)->likes();
    }

    /**
     * Relazione con i dislike
     */
    public function negativeLikes()
    {
        return $this->hasMany(VideoLike::class)->dislikes();
    }

    /**
     * Ottiene l'URL del thumbnail
     */
    public function getThumbnailUrlAttribute()
    {
        // Prima controlla se c'è un thumbnail locale
        if ($this->thumbnail_path) {
            return Storage::url($this->thumbnail_path);
        }

        // Poi controlla se c'è un thumbnail PeerTube
        if ($this->peertube_thumbnail_url) {
            return $this->peertube_thumbnail_url;
        }

        // Poi controlla il campo thumbnail generico
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }

        // Fallback: placeholder generico
        return asset('assets/images/placeholder/placeholder-1.jpg');
    }

    /**
     * Ottiene l'ID del video da YouTube/Vimeo
     */
    public function getVideoIdAttribute()
    {
        $url = $this->video_url;

        // YouTube
        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
            return $matches[1];
        }

        // YouTube short
        if (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
            return $matches[1];
        }

        // Vimeo
        if (preg_match('/vimeo\.com\/([^?]+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Ottiene l'URL di embed
     */
    public function getEmbedUrlAttribute()
    {
        $videoId = $this->video_id;

        if (strpos($this->video_url, 'youtube') !== false || strpos($this->video_url, 'youtu.be') !== false) {
            return "https://www.youtube.com/embed/{$videoId}";
        }

        if (strpos($this->video_url, 'vimeo') !== false) {
            return "https://player.vimeo.com/video/{$videoId}";
        }

        return $this->video_url;
    }

    /**
     * Incrementa le visualizzazioni
     */
            /**
     * Incrementa le visualizzazioni (metodo originale)
     */
    public function incrementViews()
    {
        $this->increment('view_count');
        return $this;
    }

    /**
     * Incrementa le visualizzazioni solo se l'utente non è il proprietario
     */
    public function incrementViewsIfNotOwner($userId = null)
    {
        // Se non viene passato un user ID, usa quello dell'utente autenticato
        if ($userId === null) {
            $userId = auth()->id();
        }

        // Se l'utente è autenticato e è il proprietario del video, non incrementare
        if ($userId !== null && $userId === $this->user_id) {
            return false;
        }

        // Incrementa in tutti gli altri casi (utente diverso o non autenticato)
        $this->increment('view_count');
        return true;
    }

    /**
     * Verifica se il video è su PeerTube
     */
    public function isOnPeerTube(): bool
    {
        return !empty($this->peertube_id);
    }

    /**
     * Verifica se il video è approvato
     */
    public function isApproved(): bool
    {
        return $this->moderation_status === 'approved';
    }

    /**
     * Verifica se il video è in moderazione
     */
    public function isPending(): bool
    {
        return $this->moderation_status === 'pending';
    }

    /**
     * Verifica se il video è rifiutato
     */
    public function isRejected(): bool
    {
        return $this->moderation_status === 'rejected';
    }

    /**
     * Ottiene la durata formattata
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration) {
            return '00:00';
        }

        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Ottiene la dimensione file formattata
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Ottiene il rating del video (like - dislike)
     */
    public function getRatingAttribute(): int
    {
        return $this->like_count - $this->dislike_count;
    }

    /**
     * Ottiene la percentuale di like
     */
    public function getLikePercentageAttribute(): float
    {
        $total = $this->like_count + $this->dislike_count;

        if ($total === 0) {
            return 0;
        }

        return round(($this->like_count / $total) * 100, 1);
    }

    /**
     * Incrementa le visualizzazioni PeerTube
     */
    public function incrementPeerTubeViews(): void
    {
        $this->increment('view_count');
    }

    /**
     * Incrementa i like PeerTube
     */
    public function incrementPeerTubeLikes(): void
    {
        $this->increment('like_count');
    }

    /**
     * Incrementa i dislike PeerTube
     */
    public function incrementPeerTubeDislikes(): void
    {
        $this->increment('dislike_count');
    }

    /**
     * Incrementa i commenti PeerTube
     */
    public function incrementPeerTubeComments(): void
    {
        $this->increment('comment_count');
    }

    /**
     * Verifica se il video è stato caricato su PeerTube
     */
    public function isUploadedToPeerTube(): bool
    {
        return $this->upload_status === 'completed' && !empty($this->peertube_id);
    }

    /**
     * Verifica se il video è in fase di upload
     */
    public function isUploading(): bool
    {
        return in_array($this->upload_status, ['pending', 'uploading', 'processing']);
    }

    /**
     * Verifica se l'upload è fallito
     */
    public function hasUploadFailed(): bool
    {
        return $this->upload_status === 'failed';
    }

    /**
     * Ottiene l'URL del video PeerTube
     */
    public function getPeerTubeVideoUrlAttribute(): ?string
    {
        if ($this->isUploadedToPeerTube()) {
            return $this->peertube_url;
        }
        return null;
    }

    /**
     * Ottiene l'URL di embed PeerTube
     */
    public function getPeerTubeEmbedUrlAttribute(): ?string
    {
        if ($this->isUploadedToPeerTube() && $this->peertube_uuid) {
            // Costruisci sempre l'URL di embed usando l'UUID
            $baseUrl = \App\Models\PeerTubeConfig::getValue('peertube_url', 'https://video.slamin.it');
            return $baseUrl . '/videos/embed/' . $this->peertube_uuid;
        }
        return null;
    }

    /**
     * Marca il video come necessitante sincronizzazione
     */
    public function markForSync(): void
    {
        $this->update(['needs_sync' => true]);
    }

    /**
     * Marca il video come sincronizzato
     */
    public function markAsSynced(): void
    {
        $this->update([
            'needs_sync' => false,
            'last_sync_at' => now()
        ]);
    }

    /**
     * Ottiene lo stato upload formattato
     */
    public function getUploadStatusTextAttribute(): string
    {
        $statuses = [
            'pending' => 'In attesa',
            'uploading' => 'Caricamento in corso',
            'processing' => 'Elaborazione',
            'completed' => 'Completato',
            'failed' => 'Fallito'
        ];

        return $statuses[$this->upload_status] ?? 'Sconosciuto';
    }

    /**
     * Scope per video caricati su PeerTube
     */
    public function scopeOnPeerTube($query)
    {
        return $query->where('upload_status', 'completed')
                    ->whereNotNull('peertube_id');
    }

    /**
     * Scope per video che necessitano sincronizzazione
     */
    public function scopeNeedsSync($query)
    {
        return $query->where('needs_sync', true);
    }

    /**
     * Scope per video con upload fallito
     */
    public function scopeUploadFailed($query)
    {
        return $query->where('upload_status', 'failed');
    }
}
