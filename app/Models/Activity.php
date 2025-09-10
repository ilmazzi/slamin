<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'subject_type',
        'subject_id',
        'action',
        'description',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject of the activity (polymorphic)
     */
    public function subject(): MorphTo
    {
        return $this->morphTo('subject', 'subject_type', 'subject_id');
    }

    /**
     * Scope to get activities for a specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get activities of a specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get recent activities
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get formatted description for display
     */
    public function getFormattedDescriptionAttribute(): string
    {
        $metadata = $this->metadata ?? [];
        $subjectTitle = $metadata['title'] ?? $metadata['name'] ?? 'Elemento';

        return match($this->action) {
            'viewed' => "Ha visualizzato {$subjectTitle}",
            'uploaded' => "Ha caricato {$subjectTitle}",
            'commented_on' => "Ha commentato su {$subjectTitle}",
            'liked' => "Ha messo mi piace a {$subjectTitle}",
            'created' => "Ha creato {$subjectTitle}",
            'updated' => "Ha aggiornato {$subjectTitle}",
            'deleted' => "Ha eliminato {$subjectTitle}",
            'joined' => "Si è unito a {$subjectTitle}",
            'left' => "Ha lasciato {$subjectTitle}",
            'accepted' => "Ha accettato l'invito a {$subjectTitle}",
            'declined' => "Ha rifiutato l'invito a {$subjectTitle}",
            'shared' => "Ha condiviso {$subjectTitle}",
            'followed' => "Ha iniziato a seguire {$subjectTitle}",
            'unfollowed' => "Ha smesso di seguire {$subjectTitle}",
            default => $this->description ?? "Attività su {$subjectTitle}",
        };
    }

    /**
     * Get icon for activity type
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'view' => 'ph ph-eye',
            'upload' => 'ph ph-upload',
            'comment' => 'ph ph-chat-circle',
            'like' => 'ph ph-heart',
            'create' => 'ph ph-plus-circle',
            'update' => 'ph ph-pencil',
            'delete' => 'ph ph-trash',
            'join' => 'ph ph-user-plus',
            'leave' => 'ph ph-user-minus',
            'accept' => 'ph ph-check-circle',
            'decline' => 'ph ph-x-circle',
            'share' => 'ph ph-share',
            'follow' => 'ph ph-user-plus',
            'unfollow' => 'ph ph-user-minus',
            default => 'ph ph-activity',
        };
    }

    /**
     * Get color class for activity type
     */
    public function getColorClassAttribute(): string
    {
        return match($this->type) {
            'view' => 'primary',
            'upload' => 'success',
            'comment' => 'info',
            'like' => 'danger',
            'create' => 'success',
            'update' => 'warning',
            'delete' => 'danger',
            'join' => 'success',
            'leave' => 'secondary',
            'accept' => 'success',
            'decline' => 'danger',
            'share' => 'info',
            'follow' => 'primary',
            'unfollow' => 'secondary',
            default => 'primary',
        };
    }

    /**
     * Get content type badge for display
     */
    public function getContentTypeBadgeAttribute(): string
    {
        return match($this->subject_type) {
            'App\\Models\\Video' => 'Video',
            'App\\Models\\Poem' => 'Poesia',
            'App\\Models\\Article' => 'Articolo',
            'App\\Models\\Photo' => 'Foto',
            'App\\Models\\Event' => 'Evento',
            'App\\Models\\Group' => 'Gruppo',
            'App\\Models\\Gig' => 'Gig',
            'App\\Models\\User' => 'Utente',
            default => 'Contenuto',
        };
    }

    /**
     * Get content type color for badge
     */
    public function getContentTypeColorAttribute(): string
    {
        return match($this->subject_type) {
            'App\\Models\\Video' => 'danger',
            'App\\Models\\Poem' => 'warning',
            'App\\Models\\Article' => 'info',
            'App\\Models\\Photo' => 'success',
            'App\\Models\\Event' => 'primary',
            'App\\Models\\Group' => 'secondary',
            'App\\Models\\Gig' => 'dark',
            'App\\Models\\User' => 'light',
            default => 'primary',
        };
    }

    /**
     * Get thumbnail URL if available
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        $metadata = $this->metadata ?? [];
        return $metadata['thumbnail'] ?? $metadata['thumbnail_url'] ?? null;
    }

    /**
     * Check if activity has thumbnail
     */
    public function getHasThumbnailAttribute(): bool
    {
        return !empty($this->thumbnail_url);
    }
}
