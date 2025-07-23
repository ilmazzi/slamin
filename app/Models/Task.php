<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'category',
        'assigned_to',
        'created_by',
        'reviewed_by',
        'due_date',
        'started_at',
        'completed_at',
        'estimated_hours',
        'actual_hours',
        'progress_percentage',
        'attachments',
        'links',
        'tags',
        'notes',
        'review_notes',
        'parent_task_id',
        'dependencies',
        'version',
        'branch',
        'commit_hash',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'attachments' => 'array',
        'links' => 'array',
        'tags' => 'array',
        'dependencies' => 'array',
    ];

    // Relazioni
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function parentTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    // Scopes per filtri
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())->where('status', '!=', 'done');
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today());
    }

    // Metodi helper
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'done';
    }

    public function isDueToday(): bool
    {
        return $this->due_date && $this->due_date->isToday();
    }

    public function getPriorityColor(): string
    {
        return match($this->priority) {
            'urgent' => 'danger',
            'high' => 'warning',
            'medium' => 'info',
            'low' => 'success',
            default => 'secondary'
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'todo' => 'secondary',
            'in_progress' => 'primary',
            'review' => 'warning',
            'testing' => 'info',
            'done' => 'success',
            default => 'secondary'
        };
    }

    public function getCategoryIcon(): string
    {
        return match($this->category) {
            'frontend' => 'ph ph-browser',
            'backend' => 'ph ph-server',
            'database' => 'ph ph-database',
            'design' => 'ph ph-palette',
            'testing' => 'ph ph-test-tube',
            'deployment' => 'ph ph-rocket',
            'documentation' => 'ph ph-file-text',
            'bug_fix' => 'ph ph-bug',
            'feature' => 'ph ph-star',
            'maintenance' => 'ph ph-wrench',
            'optimization' => 'ph ph-lightning',
            'security' => 'ph ph-shield-check',
            default => 'ph ph-circle'
        };
    }

    public function getEstimatedTimeFormatted(): string
    {
        if (!$this->estimated_hours) return 'Non specificato';
        
        if ($this->estimated_hours < 1) {
            return ($this->estimated_hours * 60) . ' min';
        }
        
        $hours = floor($this->estimated_hours);
        $minutes = ($this->estimated_hours - $hours) * 60;
        
        if ($minutes > 0) {
            return $hours . 'h ' . round($minutes) . 'm';
        }
        
        return $hours . 'h';
    }

    public function getActualTimeFormatted(): string
    {
        if (!$this->actual_hours) return 'Non registrato';
        
        if ($this->actual_hours < 1) {
            return round($this->actual_hours * 60) . ' min';
        }
        
        $hours = floor($this->actual_hours);
        $minutes = ($this->actual_hours - $hours) * 60;
        
        if ($minutes > 0) {
            return $hours . 'h ' . round($minutes) . 'm';
        }
        
        return $hours . 'h';
    }

    public function getProgressBarColor(): string
    {
        if ($this->progress_percentage >= 100) return 'success';
        if ($this->progress_percentage >= 75) return 'info';
        if ($this->progress_percentage >= 50) return 'warning';
        return 'danger';
    }
}
