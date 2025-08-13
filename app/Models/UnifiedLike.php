<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UnifiedLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'likeable_type',
        'likeable_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con l'utente che ha messo il like
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione polimorfica con il contenuto likato
     */
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }
}
