<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'invited_by',
        'status',
        'message',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con il gruppo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Relazione con l'utente invitato
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione con l'utente che ha inviato l'invito
     */
    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Verifica se l'invito è pendente
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Verifica se l'invito è stato accettato
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Verifica se l'invito è stato rifiutato
     */
    public function isDeclined(): bool
    {
        return $this->status === 'declined';
    }

    /**
     * Verifica se l'invito è scaduto
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' || 
               ($this->expires_at && $this->expires_at->isPast());
    }

    /**
     * Scope per inviti pendenti
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope per inviti accettati
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope per inviti rifiutati
     */
    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }

    /**
     * Scope per inviti scaduti
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                    ->orWhere('expires_at', '<', now());
    }

    /**
     * Accetta l'invito
     */
    public function accept(): bool
    {
        if (!$this->isPending() || $this->isExpired()) {
            return false;
        }

        $this->update(['status' => 'accepted']);
        return true;
    }

    /**
     * Rifiuta l'invito
     */
    public function decline(): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $this->update(['status' => 'declined']);
        return true;
    }

    /**
     * Marca l'invito come scaduto
     */
    public function markAsExpired(): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $this->update(['status' => 'expired']);
        return true;
    }
}
