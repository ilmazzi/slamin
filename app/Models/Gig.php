<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasModeration;

class Gig extends Model
{
    use HasFactory, SoftDeletes, HasModeration;

    protected $fillable = [
        'title',
        'description',
        'requirements',
        'compensation',
        'deadline',
        'event_id',
        'group_id',
        'user_id',
        'category',
        'type',
        'language',
        'location',
        'is_remote',
        'is_urgent',
        'is_featured',
        'is_closed',
        'max_applications',
        'allow_group_admin_edit',
        'status',
        'application_count',
        'accepted_applications_count',
        'moderation_status',
        'moderation_notes',
        'moderated_by',
        'moderated_at',
        // Campi per traduzioni
        'gig_type',
        'poem_id',
        'target_languages',
        'translation_instructions',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'is_remote' => 'boolean',
        'is_urgent' => 'boolean',
        'is_featured' => 'boolean',
        'is_closed' => 'boolean',
        'allow_group_admin_edit' => 'boolean',
        'application_count' => 'integer',
        'accepted_applications_count' => 'integer',
        // Cast per traduzioni
        'target_languages' => 'array',
        'moderated_at' => 'datetime',
    ];

    protected $dates = [
        'deadline',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relazioni
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function applications()
    {
        return $this->hasMany(GigApplication::class);
    }

    public function pendingApplications()
    {
        return $this->hasMany(GigApplication::class)->where('status', 'pending');
    }

    public function acceptedApplications()
    {
        return $this->hasMany(GigApplication::class)->where('status', 'accepted');
    }

    public function rejectedApplications()
    {
        return $this->hasMany(GigApplication::class)->where('status', 'rejected');
    }

    // Relazioni per traduzioni
    public function poem(): BelongsTo
    {
        return $this->belongsTo(Poem::class);
    }

    public function poemTranslations(): HasMany
    {
        return $this->hasMany(PoemTranslation::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('is_closed', false)->where('deadline', '>', now());
    }

    public function scopeClosed($query)
    {
        return $query->where(function($q) {
            $q->where('is_closed', true)->orWhere('deadline', '<=', now());
        });
    }

    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeRemote($query)
    {
        return $query->where('is_remote', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', '%' . $location . '%');
    }

    // Scope per traduzioni
    public function scopeTranslationGigs($query)
    {
        return $query->where('gig_type', 'translation');
    }

    public function scopeEventGigs($query)
    {
        return $query->where('gig_type', 'event');
    }

    public function scopeForPoem($query, $poemId)
    {
        return $query->where('poem_id', $poemId);
    }

    // Accessors
    public function getStatusAttribute($value)
    {
        if ($this->is_closed) {
            return 'closed';
        }
        if ($this->deadline <= now()) {
            return 'expired';
        }
        if ($this->is_urgent) {
            return 'urgent';
        }
        if ($this->is_featured) {
            return 'featured';
        }
        return 'open';
    }

    public function getIsExpiredAttribute()
    {
        if (!$this->deadline) {
            return false;
        }
        return $this->deadline <= now();
    }

    public function getDaysUntilDeadlineAttribute()
    {
        if (!$this->deadline) {
            return null;
        }
        return now()->diffInDays($this->deadline, false);
    }

    public function getCanApplyAttribute()
    {
        return !$this->is_closed && !$this->is_expired &&
               ($this->max_applications === null || $this->application_count < $this->max_applications);
    }

    public function can_apply()
    {
        return $this->can_apply;
    }

    /**
     * Verifica se un utente può candidarsi a questo gig
     */
    public function canUserApply(User $user)
    {
        // Controlli base
        if (!$this->can_apply) {
            return false;
        }

        // L'utente non può candidarsi ai propri gig
        if ($this->user_id === $user->id) {
            return false;
        }

        // Per gigs di traduzione, l'autore della poesia non può candidarsi
        if ($this->gig_type === 'translation' && $this->poem && $this->poem->user_id === $user->id) {
            return false;
        }

        // Verifica se l'utente ha già candidato
        $existingApplication = $this->applications()->where('user_id', $user->id)->first();
        if ($existingApplication) {
            return false;
        }

        return true;
    }

    /**
     * Verifica se tutte le posizioni sono state coperte
     */
    public function areAllPositionsFilled()
    {
        if ($this->max_applications === null) {
            return false; // Se non c'è limite, non sono mai tutte coperte
        }

        return $this->accepted_applications_count >= $this->max_applications;
    }

    /**
     * Verifica se il gig dovrebbe essere chiuso automaticamente
     */
    public function shouldBeClosed()
    {
        return $this->areAllPositionsFilled() || $this->is_expired;
    }

    // Metodi

    public function close()
    {
        $this->update(['is_closed' => true]);
    }

    public function reopen()
    {
        $this->update(['is_closed' => false]);
    }

    public function markAsUrgent()
    {
        $this->update(['is_urgent' => true]);
    }

    public function markAsFeatured()
    {
        $this->update(['is_featured' => true]);
    }

    public function canBeEditedBy(User $user)
    {
        // Il proprietario può sempre modificare
        if ($this->user_id === $user->id) {
            return true;
        }

        // Se è abilitata la modifica da admin del gruppo
        if ($this->allow_group_admin_edit && $this->group_id) {
            $groupMember = $this->group->members()->where('user_id', $user->id)->first();
            return $groupMember && in_array($groupMember->role, ['admin', 'moderator']);
        }

        return false;
    }

    public function canBeViewedBy(User $user = null)
    {
        // Se non c'è utente, solo gig pubblici
        if (!$user) {
            return true;
        }

        // Gli utenti audience non possono vedere i gig
        if ($user->hasRole('audience')) {
            return false;
        }

        return true;
    }

    /**
     * Condividi il gig inviando notifiche a tutti gli utenti non-audience
     */
    public function share()
    {
        // Ottieni tutti gli utenti non-audience
        $users = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'audience');
        })->get();

        // Invia notifica a tutti
        foreach ($users as $user) {
            $user->notify(new \App\Notifications\GigShared($this));
        }

        return $users->count();
    }
}
