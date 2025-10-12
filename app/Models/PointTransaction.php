<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PointTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'type',
        'source_type',
        'source_id',
        'description',
        'created_by',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    /**
     * User who received/lost points
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Source of the points (polymorphic)
     */
    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Admin who created this transaction (for manual adjustments)
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if points were added
     */
    public function isAddition(): bool
    {
        return $this->points > 0;
    }

    /**
     * Check if points were deducted
     */
    public function isDeduction(): bool
    {
        return $this->points < 0;
    }

    /**
     * Scope: recent transactions
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: by type
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }
}

