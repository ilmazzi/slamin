<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GigApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'gig_id',
        'user_id',
        'message',
        'experience',
        'portfolio',
        'availability',
        'compensation_expectation',
        'status',
        'accepted_at',
        'rejected_at',
        'withdrawn_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'withdrawn_at' => 'datetime',
    ];

    protected $dates = [
        'accepted_at',
        'rejected_at',
        'withdrawn_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relazioni
    public function gig()
    {
        return $this->belongsTo(Gig::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relazione per negoziazioni traduzioni
    public function translationNegotiations()
    {
        return $this->hasMany(PoemTranslationNegotiation::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeWithdrawn($query)
    {
        return $query->where('status', 'withdrawn');
    }

    // Metodi
    public function accept()
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);
    }

    public function reject()
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
        ]);
    }

    public function withdraw()
    {
        $this->update([
            'status' => 'withdrawn',
            'withdrawn_at' => now(),
        ]);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isWithdrawn()
    {
        return $this->status === 'withdrawn';
    }

    public function canBeWithdrawn()
    {
        return $this->isPending();
    }

    public function canBeAccepted()
    {
        return $this->isPending();
    }

    public function canBeRejected()
    {
        return $this->isPending();
    }
}
