<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_featured' => 'boolean',
        'tags' => 'array',
        'view_count' => 'integer',
        'like_count' => 'integer',
        'comment_count' => 'integer',
        'published_at' => 'datetime',
    ];

    /**
     * Relazione con l'utente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope per poesie approvate
     */
    public function scopeApproved($query)
    {
        return $query->where('moderation_status', 'approved');
    }

    /**
     * Scope per poesie pubbliche
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope per poesie in evidenza
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Verifica se la poesia è approvata
     */
    public function isApproved(): bool
    {
        return $this->moderation_status === 'approved';
    }

    /**
     * Verifica se la poesia è in attesa
     */
    public function isPending(): bool
    {
        return $this->moderation_status === 'pending';
    }

    /**
     * Verifica se la poesia è rifiutata
     */
    public function isRejected(): bool
    {
        return $this->moderation_status === 'rejected';
    }

    /**
     * Incrementa il contatore delle visualizzazioni
     */
    public function incrementViews()
    {
        $this->increment('view_count');
    }

    /**
     * Incrementa il contatore dei like
     */
    public function incrementLikes()
    {
        $this->increment('like_count');
    }

    /**
     * Incrementa il contatore dei commenti
     */
    public function incrementComments()
    {
        $this->increment('comment_count');
    }

    /**
     * Ottiene l'URL del thumbnail
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return asset('storage/' . $this->thumbnail_path);
        }
        
        if ($this->thumbnail) {
            return $this->thumbnail;
        }
        
        return asset('assets/images/placeholder/poem-placeholder.jpg');
    }

    /**
     * Ottiene un estratto del contenuto
     */
    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }
}
