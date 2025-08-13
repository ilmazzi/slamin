<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Services\LoggingService;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'description',
        'status',
        'resolved_by',
        'resolved_at',
        'resolution_notes',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Boot del modello
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($report) {
            LoggingService::logAdmin('report_created', [
                'report_id' => $report->id,
                'content_type' => $report->content_type,
                'content_id' => $report->reportable_id,
                'content_title' => $report->reportable_title,
                'reason' => $report->reason,
                'reason_text' => $report->reason_text,
                'reporter_id' => $report->user_id,
                'reporter_name' => $report->user->name ?? 'N/A',
                'description' => $report->description
            ], $report->reportable_type, $report->reportable_id);
        });
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_INVESTIGATING = 'investigating';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_DISMISSED = 'dismissed';

    // Reason constants
    const REASON_SPAM = 'spam';
    const REASON_INAPPROPRIATE = 'inappropriate';
    const REASON_VIOLENCE = 'violence';
    const REASON_HARASSMENT = 'harassment';
    const REASON_COPYRIGHT = 'copyright';
    const REASON_MISINFORMATION = 'misinformation';
    const REASON_OTHER = 'other';

    /**
     * Relazione con l'utente che ha fatto la segnalazione
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione polimorfa con il contenuto segnalato
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relazione con l'admin/moderatore che ha risolto la segnalazione
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Relazione con la conversazione di moderazione
     */
    public function conversation(): HasOne
    {
        return $this->hasOne(ModerationConversation::class, 'report_id');
    }

    /**
     * Scope per segnalazioni in attesa
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }



    /**
     * Scope per segnalazioni in investigazione
     */
    public function scopeInvestigating($query)
    {
        return $query->where('status', self::STATUS_INVESTIGATING);
    }

    /**
     * Scope per segnalazioni risolte
     */
    public function scopeResolved($query)
    {
        return $query->where('status', self::STATUS_RESOLVED);
    }

    /**
     * Scope per segnalazioni respinte
     */
    public function scopeDismissed($query)
    {
        return $query->where('status', self::STATUS_DISMISSED);
    }

    /**
     * Scope per segnalazioni attive (non risolte)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_INVESTIGATING]);
    }

    /**
     * Verifica se la segnalazione è in attesa
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Verifica se la segnalazione è in investigazione
     */
    public function isInvestigating(): bool
    {
        return $this->status === self::STATUS_INVESTIGATING;
    }

    /**
     * Verifica se la segnalazione è risolta
     */
    public function isResolved(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    /**
     * Verifica se la segnalazione è respinta
     */
    public function isDismissed(): bool
    {
        return $this->status === self::STATUS_DISMISSED;
    }

    /**
     * Ottiene il nome del contenuto segnalato
     */
    public function getReportableTitleAttribute(): string
    {
        if (!$this->reportable) {
            return 'Contenuto non trovato';
        }

        // Metodi comuni per ottenere il titolo
        $methods = ['title', 'name', 'subject'];
        foreach ($methods as $method) {
            if (method_exists($this->reportable, $method)) {
                return $this->reportable->$method;
            }
        }

        return 'Contenuto #' . $this->reportable_id;
    }

    /**
     * Ottiene il tipo di contenuto in formato leggibile
     */
    public function getContentTypeAttribute(): string
    {
        $types = [
            'App\Models\Video' => 'Video',
            'App\Models\Poem' => 'Poesia',
            'App\Models\Event' => 'Evento',
            'App\Models\Photo' => 'Foto',
            'App\Models\Article' => 'Articolo',
            'App\Models\Carousel' => 'Carousel',
            'App\Models\VideoComment' => 'Commento Video',
            'App\Models\PoemComment' => 'Commento Poesia',
        ];

        return $types[$this->reportable_type] ?? 'Contenuto';
    }

    /**
     * Ottiene il tipo di contenuto per l'API (formato semplice)
     */
    public function getApiContentTypeAttribute(): string
    {
        $typeMap = [
            'App\Models\Video' => 'videos',
            'App\Models\Poem' => 'poems',
            'App\Models\Event' => 'events',
            'App\Models\Photo' => 'photos',
            'App\Models\Article' => 'articles',
            'App\Models\Carousel' => 'carousels',
            'App\Models\VideoComment' => 'video_comments',
            'App\Models\PoemComment' => 'poem_comments',
        ];

        return $typeMap[$this->reportable_type] ?? 'unknown';
    }

    /**
     * Ottiene la ragione in formato leggibile
     */
    public function getReasonTextAttribute(): string
    {
        $reasons = [
            self::REASON_SPAM => 'Spam',
            self::REASON_INAPPROPRIATE => 'Contenuto inappropriato',
            self::REASON_VIOLENCE => 'Violenza',
            self::REASON_HARASSMENT => 'Molestie',
            self::REASON_COPYRIGHT => 'Violazione copyright',
            self::REASON_MISINFORMATION => 'Disinformazione',
            self::REASON_OTHER => 'Altro',
        ];

        return $reasons[$this->reason] ?? $this->reason;
    }

    /**
     * Ottiene il contenuto completo per la visualizzazione
     */
    public function getReportableContentAttribute(): ?string
    {
        if (!$this->reportable) {
            return null;
        }

        // Metodi comuni per ottenere il contenuto
        $methods = ['content', 'description', 'body', 'text'];
        foreach ($methods as $method) {
            if (method_exists($this->reportable, $method)) {
                return $this->reportable->$method;
            }
        }

        return null;
    }

    /**
     * Ottiene l'URL del contenuto per la visualizzazione
     */
    public function getReportableUrlAttribute(): ?string
    {
        if (!$this->reportable) {
            return null;
        }

        // Metodi comuni per ottenere l'URL
        $methods = ['getContentUrl', 'getUrl', 'url'];
        foreach ($methods as $method) {
            if (method_exists($this->reportable, $method)) {
                return $this->reportable->$method();
            }
        }

        return null;
    }

    /**
     * Ottiene l'immagine/thumbnail del contenuto
     */
    public function getReportableThumbnailAttribute(): ?string
    {
        if (!$this->reportable) {
            return null;
        }

        // Metodi comuni per ottenere l'immagine
        $methods = ['thumbnail', 'image', 'cover', 'thumbnail_url', 'image_url'];
        foreach ($methods as $method) {
            if (method_exists($this->reportable, $method)) {
                $value = $this->reportable->$method;
                if (is_string($value) && !empty($value)) {
                    return $value;
                }
            }
        }

        return null;
    }

    /**
     * Ottiene lo status in formato leggibile
     */
    public function getStatusTextAttribute(): string
    {
        $statuses = [
            self::STATUS_PENDING => 'In attesa',
            self::STATUS_INVESTIGATING => 'In investigazione',
            self::STATUS_RESOLVED => 'Risolta',
            self::STATUS_DISMISSED => 'Respinta',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Ottiene la classe CSS per lo status
     */
    public function getStatusClassAttribute(): string
    {
        $classes = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_INVESTIGATING => 'info',
            self::STATUS_RESOLVED => 'success',
            self::STATUS_DISMISSED => 'secondary',
        ];

        return $classes[$this->status] ?? 'secondary';
    }
}
