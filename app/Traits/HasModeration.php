<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Services\LoggingService;
use App\Models\ArticleReport;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasModeration
{
    /**
     * Boot del trait
     */
    protected static function bootHasModeration()
    {
        static::creating(function ($model) {
            if (empty($model->moderation_status)) {
                $autoApprove = self::getModerationConfig($model->getTable(), 'auto_approve', false);
                
                if ($autoApprove) {
                    $model->moderation_status = 'approved';
                    
                    // Gestione specifica per tipo di contenuto (auto-approval)
                    $contentType = get_class($model);
                    switch ($contentType) {
                        case 'App\Models\Article':
                            // Per gli articoli, imposta status a 'published' e is_public a true
                            $model->status = 'published';
                            $model->is_public = true;
                            break;
                        case 'App\Models\Video':
                        case 'App\Models\Poem':
                        case 'App\Models\Event':
                        case 'App\Models\Photo':
                            // Per altri contenuti, imposta is_public a true se esiste
                            if (property_exists($model, 'is_public')) {
                                $model->is_public = true;
                            }
                            break;
                    }
                } else {
                    $model->moderation_status = 'pending';
                }
            }
        });
    }

    /**
     * Ottiene la configurazione di moderazione per il tipo di contenuto
     */
    protected static function getModerationConfig(string $contentType, string $key, $default = null)
    {
        $settingKey = "moderation.{$contentType}.{$key}";
        return \App\Models\SystemSetting::get($settingKey, $default);
    }

    /**
     * Scope per contenuti approvati
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('moderation_status', 'approved');
    }

    /**
     * Scope per contenuti in attesa di moderazione
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('moderation_status', 'pending');
    }

    /**
     * Scope per contenuti rifiutati
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('moderation_status', 'rejected');
    }

    /**
     * Scope per contenuti pubblicati (approvati e pubblici)
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('moderation_status', 'approved')
                    ->where(function($q) {
                        $q->where('is_public', true)
                          ->orWhereNull('is_public');
                    });
    }

    /**
     * Verifica se il contenuto è approvato
     */
    public function isApproved(): bool
    {
        return $this->moderation_status === 'approved';
    }

    /**
     * Verifica se il contenuto è in attesa di moderazione
     */
    public function isPending(): bool
    {
        return $this->moderation_status === 'pending';
    }

    /**
     * Verifica se il contenuto è rifiutato
     */
    public function isRejected(): bool
    {
        return $this->moderation_status === 'rejected';
    }

    /**
     * Verifica se il contenuto è pubblicato
     */
    public function isPublished(): bool
    {
        return $this->isApproved() && ($this->is_public ?? true);
    }

    /**
     * Approva il contenuto
     */
    public function approve(?User $moderator = null, ?string $notes = null): bool
    {
        $this->moderation_status = 'approved';
        $this->moderated_by = $moderator?->id;
        $this->moderated_at = now();

        if ($notes) {
            $this->moderation_notes = $notes;
        }

        // Gestione specifica per tipo di contenuto
        $contentType = get_class($this);
        switch ($contentType) {
            case 'App\Models\Article':
                // Per gli articoli, imposta status a 'published' e is_public a true
                $this->status = 'published';
                $this->is_public = true;
                break;
            case 'App\Models\Video':
            case 'App\Models\Poem':
            case 'App\Models\Event':
            case 'App\Models\Photo':
                // Per altri contenuti, imposta is_public a true se esiste
                if (property_exists($this, 'is_public')) {
                    $this->is_public = true;
                }
                break;
        }

        $result = $this->save();

        if ($result && $moderator) {
            LoggingService::logAdmin('content_auto_approved', [
                'content_type' => $this->getContentType(),
                'content_id' => $this->id,
                'content_title' => $this->title ?? $this->content ?? 'N/A',
                'moderator_id' => $moderator->id,
                'moderator_name' => $moderator->name,
                'notes' => $notes
            ], get_class($this), $this->id);
        }

        return $result;
    }

    /**
     * Rifiuta il contenuto
     */
    public function reject(?User $moderator = null, ?string $notes = null): bool
    {
        $this->moderation_status = 'rejected';
        $this->moderated_by = $moderator?->id;
        $this->moderated_at = now();

        if ($notes) {
            $this->moderation_notes = $notes;
        }

        // Gestione specifica per tipo di contenuto
        $contentType = get_class($this);
        switch ($contentType) {
            case 'App\Models\Article':
                // Per gli articoli, imposta status a 'draft' e is_public a false
                $this->status = 'draft';
                $this->is_public = false;
                break;
            case 'App\Models\Video':
            case 'App\Models\Poem':
            case 'App\Models\Event':
            case 'App\Models\Photo':
                // Per altri contenuti, imposta is_public a false se esiste
                if (property_exists($this, 'is_public')) {
                    $this->is_public = false;
                }
                break;
        }

        $result = $this->save();

        if ($result && $moderator) {
            LoggingService::logAdmin('content_auto_rejected', [
                'content_type' => $this->getContentType(),
                'content_id' => $this->id,
                'content_title' => $this->title ?? $this->content ?? 'N/A',
                'moderator_id' => $moderator->id,
                'moderator_name' => $moderator->name,
                'notes' => $notes
            ], get_class($this), $this->id);
        }

        return $result;
    }

    /**
     * Mette in attesa il contenuto
     */
    public function setPending(?string $notes = null): bool
    {
        $this->moderation_status = 'pending';

        if ($notes) {
            $this->moderation_notes = $notes;
        }

        return $this->save();
    }

    /**
     * Verifica se l'utente può moderare questo contenuto
     */
    public function canBeModeratedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $user->hasAnyRole(['admin', 'moderator']);
    }

    /**
     * Ottiene il moderatore
     */
    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    /**
     * Ottiene il tipo di contenuto per la configurazione
     */
    public function getContentType(): string
    {
        return $this->getTable();
    }

    /**
     * Get all reports for this model
     */
    public function articleReports(): HasMany
    {
        return $this->hasMany(ArticleReport::class, 'article_id');
    }

    /**
     * Get pending reports for this model
     */
    public function pendingArticleReports(): HasMany
    {
        return $this->articleReports()->where('status', 'pending');
    }

    /**
     * Get reviewed reports for this model
     */
    public function reviewedArticleReports(): HasMany
    {
        return $this->articleReports()->where('status', 'reviewed');
    }

    /**
     * Get resolved reports for this model
     */
    public function resolvedArticleReports(): HasMany
    {
        return $this->articleReports()->where('status', 'resolved');
    }

    /**
     * Report this model by a user
     */
    public function reportArticle(User $user, string $reason, string $description = null): ArticleReport
    {
        return $this->articleReports()->create([
            'user_id' => $user->id,
            'reason' => $reason,
            'description' => $description,
            'status' => 'pending',
        ]);
    }

    /**
     * Check if a user has reported this model
     */
    public function isArticleReportedByUser(User $user): bool
    {
        return $this->articleReports()->where('user_id', $user->id)->exists();
    }

    /**
     * Get the number of pending reports
     */
    public function getPendingArticleReportsCountAttribute(): int
    {
        return $this->pendingArticleReports()->count();
    }

    /**
     * Get the number of total reports
     */
    public function getTotalArticleReportsCountAttribute(): int
    {
        return $this->articleReports()->count();
    }

    /**
     * Check if this model has any pending reports
     */
    public function hasPendingArticleReports(): bool
    {
        return $this->pendingArticleReports()->exists();
    }

    /**
     * Get the most common report reason
     */
    public function getMostCommonArticleReportReasonAttribute(): ?string
    {
        $reason = $this->articleReports()
            ->selectRaw('reason, COUNT(*) as count')
            ->groupBy('reason')
            ->orderBy('count', 'desc')
            ->first();

        return $reason ? $reason->reason : null;
    }
}
