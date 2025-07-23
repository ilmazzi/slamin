<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'content',
        'type',
        'metadata',
        'is_internal',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_internal' => 'boolean',
    ];

    // Relazioni
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    // Metodi helper
    public function getTypeIcon(): string
    {
        return match($this->type) {
            'status_update' => 'ph ph-arrow-clockwise',
            'time_log' => 'ph ph-clock',
            'review' => 'ph ph-eye',
            default => 'ph ph-chat-circle'
        };
    }

    public function getTypeColor(): string
    {
        return match($this->type) {
            'status_update' => 'info',
            'time_log' => 'warning',
            'review' => 'success',
            default => 'secondary'
        };
    }
}
