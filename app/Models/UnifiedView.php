<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UnifiedView extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'viewable_type',
        'viewable_id',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con l'utente che ha visualizzato il contenuto
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione polimorfica con il contenuto visualizzato
     */
    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }
}
