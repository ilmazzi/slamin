<?php

namespace App\Traits;

use App\Models\Report;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Reportable
{
    /**
     * Relazione con le segnalazioni
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * Relazione con le segnalazioni attive (non risolte)
     */
    public function activeReports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable')->active();
    }

    /**
     * Relazione con le segnalazioni in attesa
     */
    public function pendingReports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable')->pending();
    }

    /**
     * Verifica se il contenuto è stato segnalato dall'utente corrente
     */
    public function isReportedByUser($user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user || !$user->id) {
            return false;
        }

        return $this->reports()->where('user_id', $user->id)->exists();
    }

    /**
     * Ottiene il numero di segnalazioni attive
     */
    public function getActiveReportsCountAttribute(): int
    {
        return $this->activeReports()->count();
    }

    /**
     * Ottiene il numero di segnalazioni in attesa
     */
    public function getPendingReportsCountAttribute(): int
    {
        return $this->pendingReports()->count();
    }

    /**
     * Verifica se il contenuto ha segnalazioni attive
     */
    public function hasActiveReports(): bool
    {
        return $this->active_reports_count > 0;
    }

    /**
     * Verifica se il contenuto ha segnalazioni in attesa
     */
    public function hasPendingReports(): bool
    {
        return $this->pending_reports_count > 0;
    }

    /**
     * Ottiene la segnalazione dell'utente corrente
     */
    public function getUserReport($user = null): ?Report
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user || !$user->id) {
            return null;
        }

        return $this->reports()->where('user_id', $user->id)->first();
    }
}
