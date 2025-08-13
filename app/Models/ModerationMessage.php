<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ModerationMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'type',
        'message',
        'data',
        'is_internal',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_internal' => 'boolean',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Type constants
    const TYPE_SYSTEM = 'system';
    const TYPE_AUTHOR = 'author';
    const TYPE_MODERATOR = 'moderator';
    const TYPE_ADMIN = 'admin';

    /**
     * Relazione con la conversazione
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ModerationConversation::class, 'conversation_id');
    }

    /**
     * Relazione con l'autore del messaggio
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope per messaggi di sistema
     */
    public function scopeSystem($query)
    {
        return $query->where('type', self::TYPE_SYSTEM);
    }

    /**
     * Scope per messaggi dell'autore
     */
    public function scopeAuthor($query)
    {
        return $query->where('type', self::TYPE_AUTHOR);
    }

    /**
     * Scope per messaggi del moderatore
     */
    public function scopeModerator($query)
    {
        return $query->where('type', self::TYPE_MODERATOR);
    }

    /**
     * Scope per messaggi dell'amministratore
     */
    public function scopeAdmin($query)
    {
        return $query->where('type', self::TYPE_ADMIN);
    }

    /**
     * Scope per messaggi pubblici (non interni)
     */
    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    /**
     * Scope per messaggi interni
     */
    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    /**
     * Scope per messaggi non letti
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope per messaggi letti
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Verifica se il messaggio è di sistema
     */
    public function isSystem(): bool
    {
        return $this->type === self::TYPE_SYSTEM;
    }

    /**
     * Verifica se il messaggio è dell'autore
     */
    public function isAuthor(): bool
    {
        return $this->type === self::TYPE_AUTHOR;
    }

    /**
     * Verifica se il messaggio è del moderatore
     */
    public function isModerator(): bool
    {
        return $this->type === self::TYPE_MODERATOR;
    }

    /**
     * Verifica se il messaggio è dell'amministratore
     */
    public function isAdmin(): bool
    {
        return $this->type === self::TYPE_ADMIN;
    }

    /**
     * Verifica se il messaggio è interno
     */
    public function isInternal(): bool
    {
        return $this->is_internal;
    }

    /**
     * Verifica se il messaggio è pubblico
     */
    public function isPublic(): bool
    {
        return !$this->is_internal;
    }

    /**
     * Verifica se il messaggio è stato letto
     */
    public function isRead(): bool
    {
        return $this->is_read;
    }

    /**
     * Marca il messaggio come letto
     */
    public function markAsRead(): bool
    {
        if (!$this->is_read) {
            return $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
        return true;
    }

    /**
     * Ottiene l'icona per il tipo di messaggio
     */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_SYSTEM => 'ph ph-gear',
            self::TYPE_AUTHOR => 'ph ph-user',
            self::TYPE_MODERATOR => 'ph ph-shield-check',
            self::TYPE_ADMIN => 'ph ph-crown',
            default => 'ph ph-message',
        };
    }

    /**
     * Ottiene la classe CSS per il tipo di messaggio
     */
    public function getTypeClassAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_SYSTEM => 'text-muted',
            self::TYPE_AUTHOR => 'text-primary',
            self::TYPE_MODERATOR => 'text-warning',
            self::TYPE_ADMIN => 'text-danger',
            default => 'text-secondary',
        };
    }

    /**
     * Ottiene il nome dell'autore del messaggio
     */
    public function getAuthorNameAttribute(): string
    {
        if ($this->isSystem()) {
            return 'Sistema';
        }

        return $this->user->name ?? 'Utente sconosciuto';
    }

    /**
     * Crea un messaggio di sistema
     */
    public static function createSystemMessage(
        ModerationConversation $conversation,
        string $message,
        array $data = []
    ): self {
        // Trova l'utente sistema
        $systemUser = User::where('email', 'sistema@slamin.local')->first();
        
        if (!$systemUser) {
            throw new \Exception('System user not found. Run fix:production-moderation first.');
        }
        
        return self::create([
            'conversation_id' => $conversation->id,
            'user_id' => $systemUser->id,
            'type' => self::TYPE_SYSTEM,
            'message' => $message,
            'data' => $data,
            'is_internal' => false,
        ]);
    }

    /**
     * Crea un messaggio dell'autore
     */
    public static function createAuthorMessage(
        ModerationConversation $conversation,
        User $author,
        string $message,
        array $data = []
    ): self {
        return self::create([
            'conversation_id' => $conversation->id,
            'user_id' => $author->id,
            'type' => self::TYPE_AUTHOR,
            'message' => $message,
            'data' => $data,
            'is_internal' => false,
        ]);
    }

    /**
     * Crea un messaggio del moderatore
     */
    public static function createModeratorMessage(
        ModerationConversation $conversation,
        User $moderator,
        string $message,
        array $data = [],
        bool $isInternal = false
    ): self {
        return self::create([
            'conversation_id' => $conversation->id,
            'user_id' => $moderator->id,
            'type' => self::TYPE_MODERATOR,
            'message' => $message,
            'data' => $data,
            'is_internal' => $isInternal,
        ]);
    }

    /**
     * Crea un messaggio dell'amministratore
     */
    public static function createAdminMessage(
        ModerationConversation $conversation,
        User $admin,
        string $message,
        array $data = [],
        bool $isInternal = false
    ): self {
        return self::create([
            'conversation_id' => $conversation->id,
            'user_id' => $admin->id,
            'type' => self::TYPE_ADMIN,
            'message' => $message,
            'data' => $data,
            'is_internal' => $isInternal,
        ]);
    }
}
