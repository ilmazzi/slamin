<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

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
                $model->moderation_status = $autoApprove ? 'approved' : 'pending';
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

        return $this->save();
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

        return $this->save();
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
}
