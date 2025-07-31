<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class View extends Model
{
    use HasFactory;

    protected $table = 'unified_views';

    protected $fillable = [
        'user_id',
        'viewable_type',
        'viewable_id',
    ];

    /**
     * Relazione con l'utente
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione polimorfa con il contenuto visualizzato
     */
    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }
}
