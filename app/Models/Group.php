<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'visibility',
        'created_by',
        'website',
        'social_facebook',
        'social_instagram',
        'social_youtube',
        'social_twitter',
        'social_tiktok',
        'social_linkedin',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con l'utente che ha creato il gruppo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relazione con i membri del gruppo
     */
    public function members(): HasMany
    {
        return $this->hasMany(GroupMember::class);
    }

    /**
     * Relazione con gli utenti membri del gruppo
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_members')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Relazione con gli inviti del gruppo
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(GroupInvitation::class);
    }

    /**
     * Relazione con le richieste di partecipazione
     */
    public function joinRequests(): HasMany
    {
        return $this->hasMany(GroupJoinRequest::class);
    }

    /**
     * Relazione con gli annunci del gruppo
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(GroupAnnouncement::class);
    }

    /**
     * Relazione con gli eventi del gruppo
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Eventi associati tramite tabella pivot (many-to-many)
     */
    public function linkedEvents()
    {
        return $this->belongsToMany(Event::class, 'event_group')
                    ->withPivot('group_permissions')
                    ->withTimestamps();
    }

    /**
     * Scope per gruppi pubblici
     */
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    /**
     * Scope per gruppi privati
     */
    public function scopePrivate($query)
    {
        return $query->where('visibility', 'private');
    }

    /**
     * Verifica se un utente è membro del gruppo
     */
    public function hasMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Verifica se un utente è admin del gruppo
     */
    public function hasAdmin(User $user): bool
    {
        return $this->members()
                    ->where('user_id', $user->id)
                    ->where('role', 'admin')
                    ->exists();
    }

    /**
     * Verifica se un utente è moderatore del gruppo
     */
    public function hasModerator(User $user): bool
    {
        return $this->members()
                    ->where('user_id', $user->id)
                    ->whereIn('role', ['admin', 'moderator'])
                    ->exists();
    }

    /**
     * Ottieni il ruolo di un utente nel gruppo
     */
    public function getUserRole(User $user): ?string
    {
        $member = $this->members()->where('user_id', $user->id)->first();
        return $member ? $member->role : null;
    }

    /**
     * Ottieni tutti gli admin del gruppo
     */
    public function getAdmins()
    {
        return $this->members()->where('role', 'admin')->with('user');
    }

    /**
     * Ottieni tutti i moderatori del gruppo (inclusi admin)
     */
    public function getModerators()
    {
        return $this->members()->whereIn('role', ['admin', 'moderator'])->with('user');
    }

    /**
     * Conta i membri del gruppo
     */
    public function getMembersCount(): int
    {
        return $this->members()->count();
    }

    /**
     * Verifica se il gruppo ha inviti pendenti per un utente
     */
    public function hasPendingInvitation(User $user): bool
    {
        return $this->invitations()
                    ->where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->exists();
    }

    /**
     * Verifica se il gruppo ha richieste pendenti da un utente
     */
    public function hasPendingJoinRequest(User $user): bool
    {
        return $this->joinRequests()
                    ->where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->exists();
    }

    /**
     * Ottieni tutti gli inviti pendenti del gruppo
     */
    public function getPendingInvitations()
    {
        return $this->invitations()->where('status', 'pending')->with('user', 'invitedBy');
    }

    /**
     * Ottieni tutte le richieste pendenti del gruppo
     */
    public function getPendingJoinRequests()
    {
        return $this->joinRequests()->where('status', 'pending')->with('user');
    }
}
