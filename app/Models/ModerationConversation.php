<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModerationConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'content_author_id',
        'assigned_moderator_id',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Status constants
    const STATUS_OPEN = 'open';
    const STATUS_WAITING_AUTHOR = 'waiting_author';
    const STATUS_WAITING_MODERATOR = 'waiting_moderator';
    const STATUS_CLOSED = 'closed';

    // Type constants
    const TYPE_SYSTEM = 'system';
    const TYPE_AUTHOR = 'author';
    const TYPE_MODERATOR = 'moderator';
    const TYPE_ADMIN = 'admin';

    /**
     * Relazione con la segnalazione
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Relazione con l'autore del contenuto
     */
    public function contentAuthor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'content_author_id');
    }

    /**
     * Relazione con il moderatore assegnato
     */
    public function assignedModerator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_moderator_id');
    }

    /**
     * Relazione con i messaggi
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ModerationMessage::class, 'conversation_id');
    }

    /**
     * Relazione con i messaggi pubblici (non interni)
     */
    public function publicMessages(): HasMany
    {
        return $this->hasMany(ModerationMessage::class, 'conversation_id')
                    ->where('is_internal', false);
    }

    /**
     * Relazione con i messaggi interni (solo per moderatori)
     */
    public function internalMessages(): HasMany
    {
        return $this->hasMany(ModerationMessage::class, 'conversation_id')
                    ->where('is_internal', true);
    }

    /**
     * Scope per conversazioni aperte
     */
    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    /**
     * Scope per conversazioni in attesa di risposta dall'autore
     */
    public function scopeWaitingAuthor($query)
    {
        return $query->where('status', self::STATUS_WAITING_AUTHOR);
    }

    /**
     * Scope per conversazioni in attesa di risposta dal moderatore
     */
    public function scopeWaitingModerator($query)
    {
        return $query->where('status', self::STATUS_WAITING_MODERATOR);
    }

    /**
     * Scope per conversazioni chiuse
     */
    public function scopeClosed($query)
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    /**
     * Verifica se la conversazione è aperta
     */
    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    /**
     * Verifica se la conversazione è in attesa di risposta dall'autore
     */
    public function isWaitingAuthor(): bool
    {
        return $this->status === self::STATUS_WAITING_AUTHOR;
    }

    /**
     * Verifica se la conversazione è in attesa di risposta dal moderatore
     */
    public function isWaitingModerator(): bool
    {
        return $this->status === self::STATUS_WAITING_MODERATOR;
    }

    /**
     * Verifica se la conversazione è chiusa
     */
    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    /**
     * Ottiene l'ultimo messaggio della conversazione
     */
    public function getLastMessageAttribute(): ?ModerationMessage
    {
        return $this->messages()->latest()->first();
    }

    /**
     * Ottiene il numero di messaggi non letti per un utente
     */
    public function getUnreadCountForUser(User $user): int
    {
        return $this->messages()
                    ->where('user_id', '!=', $user->id)
                    ->where('is_read', false)
                    ->count();
    }

    /**
     * Marca tutti i messaggi come letti per un utente
     */
    public function markMessagesAsReadForUser(User $user): void
    {
        $this->messages()
             ->where('user_id', '!=', $user->id)
             ->where('is_read', false)
             ->update([
                 'is_read' => true,
                 'read_at' => now(),
             ]);
    }

    /**
     * Crea una conversazione per una segnalazione
     */
    public static function createForReport(Report $report): self
    {
        // Ottieni l'autore del contenuto
        $contentAuthor = $report->reportable->user ?? null;
        
        if (!$contentAuthor) {
            throw new \Exception('Impossibile trovare l\'autore del contenuto segnalato');
        }

        return self::create([
            'report_id' => $report->id,
            'content_author_id' => $contentAuthor->id,
            'status' => self::STATUS_OPEN,
        ]);
    }

    /**
     * Assegna un moderatore alla conversazione
     */
    public function assignModerator(User $moderator): bool
    {
        return $this->update([
            'assigned_moderator_id' => $moderator->id,
        ]);
    }

    /**
     * Chiude la conversazione
     */
    public function close(): bool
    {
        return $this->update([
            'status' => self::STATUS_CLOSED,
        ]);
    }

    /**
     * Imposta lo status in attesa di risposta dall'autore
     */
    public function setWaitingAuthor(): bool
    {
        return $this->update([
            'status' => self::STATUS_WAITING_AUTHOR,
        ]);
    }

    /**
     * Imposta lo status in attesa di risposta dal moderatore
     */
    public function setWaitingModerator(): bool
    {
        return $this->update([
            'status' => self::STATUS_WAITING_MODERATOR,
        ]);
    }
}
