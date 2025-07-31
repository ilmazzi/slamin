<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    use HasFactory;

    protected $table = 'unified_likes';

    protected $fillable = [
        'user_id',
        'likeable_type',
        'likeable_id',
    ];

    /**
     * Relazione con l'utente
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione polimorfa con il contenuto likato
     */
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }
}
