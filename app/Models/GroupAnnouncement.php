<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupAnnouncement extends Model
{
    protected $fillable = [
        'group_id',
        'author_id',
        'title',
        'content',
        'visibility',
        'is_pinned',
        'has_poll',
        'poll_options',
        'poll_votes',
        'expires_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'has_poll' => 'boolean',
        'poll_options' => 'array',
        'poll_votes' => 'array',
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
     * Relazione con l'autore
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Relazione con i commenti (se implementati in futuro)
     */
    // public function comments(): HasMany
    // {
    //     return $this->hasMany(GroupAnnouncementComment::class);
    // }

    /**
     * Scope per annunci pubblici
     */
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    /**
     * Scope per annunci visibili ai membri
     */
    public function scopeMembersOnly($query)
    {
        return $query->where('visibility', 'members_only');
    }

    /**
     * Scope per annunci degli admin
     */
    public function scopeAdminsOnly($query)
    {
        return $query->where('visibility', 'admins_only');
    }

    /**
     * Scope per annunci pinnati
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Scope per annunci con sondaggi
     */
    public function scopeWithPolls($query)
    {
        return $query->where('has_poll', true);
    }

    /**
     * Scope per annunci non scaduti
     */
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Verifica se l'annuncio è scaduto
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Verifica se l'annuncio ha un sondaggio
     */
    public function hasPoll(): bool
    {
        return $this->has_poll && !empty($this->poll_options);
    }

    /**
     * Ottieni i risultati del sondaggio
     */
    public function getPollResults(): array
    {
        if (!$this->hasPoll()) {
            return [];
        }

        $results = [];
        $totalVotes = 0;

        foreach ($this->poll_options as $index => $option) {
            $votes = $this->poll_votes[$index] ?? 0;
            $results[$index] = [
                'option' => $option,
                'votes' => $votes,
                'percentage' => 0
            ];
            $totalVotes += $votes;
        }

        // Calcola le percentuali
        if ($totalVotes > 0) {
            foreach ($results as $index => $result) {
                $results[$index]['percentage'] = round(($result['votes'] / $totalVotes) * 100, 1);
            }
        }

        return $results;
    }

    /**
     * Verifica se un utente può votare nel sondaggio
     */
    public function canUserVote(User $user): bool
    {
        if (!$this->hasPoll()) {
            return false;
        }

        // Verifica se l'utente è membro del gruppo
        if (!$this->group->hasMember($user)) {
            return false;
        }

        // Verifica se l'utente ha già votato
        $userVotes = $this->poll_votes['user_votes'] ?? [];
        return !in_array($user->id, $userVotes);
    }

    /**
     * Registra un voto nel sondaggio
     */
    public function recordVote(User $user, int $optionIndex): bool
    {
        if (!$this->canUserVote($user)) {
            return false;
        }

        $pollVotes = $this->poll_votes ?? [];
        $userVotes = $pollVotes['user_votes'] ?? [];
        
        // Incrementa il voto per l'opzione
        if (!isset($pollVotes[$optionIndex])) {
            $pollVotes[$optionIndex] = 0;
        }
        $pollVotes[$optionIndex]++;

        // Aggiungi l'utente alla lista di chi ha votato
        $userVotes[] = $user->id;
        $pollVotes['user_votes'] = $userVotes;

        $this->poll_votes = $pollVotes;
        return $this->save();
    }
}
