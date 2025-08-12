<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class ArticleReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'user_id',
        'reason',
        'description',
        'status',
        'reviewed_by',
        'admin_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Relazioni
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending(Builder $query): void
    {
        $query->where('status', 'pending');
    }

    public function scopeReviewed(Builder $query): void
    {
        $query->where('status', 'reviewed');
    }

    public function scopeResolved(Builder $query): void
    {
        $query->where('status', 'resolved');
    }

    // Accessors
    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    public function getIsReviewedAttribute()
    {
        return $this->status === 'reviewed';
    }

    public function getIsResolvedAttribute()
    {
        return $this->status === 'resolved';
    }

    public function getReasonTextAttribute()
    {
        $reasons = [
            'spam' => 'Spam',
            'inappropriate' => 'Contenuto inappropriato',
            'copyright' => 'Violazione copyright',
            'fake_news' => 'Fake news',
            'other' => 'Altro',
        ];

        return $reasons[$this->reason] ?? $this->reason;
    }

    // Metodi
    public function review($status, $adminNotes = null, $reviewerId = null)
    {
        $this->update([
            'status' => $status,
            'admin_notes' => $adminNotes,
            'reviewed_by' => $reviewerId ?? auth()->id(),
            'reviewed_at' => now(),
        ]);
    }

    public function markAsReviewed($adminNotes = null)
    {
        $this->review('reviewed', $adminNotes);
    }

    public function markAsResolved($adminNotes = null)
    {
        $this->review('resolved', $adminNotes);
    }
}
