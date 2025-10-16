<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'description',
        'avatar',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'participants')
                    ->withPivot('role', 'last_read_at')
                    ->withTimestamps();
    }

    public function isGroup(): bool
    {
        return $this->type === 'group';
    }

    public function isPrivate(): bool
    {
        return $this->type === 'private';
    }

    public function getLastMessage()
    {
        return $this->messages()->first();
    }

    public function getUnreadCountForUser($userId): int
    {
        $participant = $this->participants()->where('user_id', $userId)->first();
        if (!$participant || !$participant->pivot->last_read_at) {
            return $this->messages()->count();
        }
        
        return $this->messages()->where('created_at', '>', $participant->pivot->last_read_at)->count();
    }
}
