<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'is_system_message',
        'is_edited',
        'edited_at',
    ];

    protected $casts = [
        'is_system_message' => 'boolean',
        'is_edited' => 'boolean',
        'edited_at' => 'datetime',
        'file_size' => 'integer',
    ];

    // Relazioni
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeSystem($query)
    {
        return $query->where('is_system_message', true);
    }

    public function scopeUser($query)
    {
        return $query->where('is_system_message', false);
    }

    public function scopeWithFile($query)
    {
        return $query->whereNotNull('file_path');
    }

    // Metodi
    public function hasFile(): bool
    {
        return !empty($this->file_path);
    }

    public function isImage(): bool
    {
        if (!$this->hasFile()) {
            return false;
        }

        $imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        return in_array($this->file_type, $imageTypes);
    }

    public function isPdf(): bool
    {
        return $this->file_type === 'application/pdf';
    }

    public function isOfficeDocument(): bool
    {
        $officeTypes = [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];

        return in_array($this->file_type, $officeTypes);
    }

    public function getFileIcon(): string
    {
        if ($this->isImage()) {
            return 'ph-duotone ph-image';
        }

        if ($this->isPdf()) {
            return 'ph-duotone ph-file-pdf';
        }

        if ($this->isOfficeDocument()) {
            if (str_contains($this->file_type, 'word')) {
                return 'ph-duotone ph-file-doc';
            }
            if (str_contains($this->file_type, 'excel')) {
                return 'ph-duotone ph-file-xls';
            }
            if (str_contains($this->file_type, 'powerpoint')) {
                return 'ph-duotone ph-file-ppt';
            }
        }

        return 'ph-duotone ph-file';
    }

    public function getFileSizeFormatted(): string
    {
        if (!$this->file_size) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    public function getFileUrl(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function canBeEditedBy(User $user): bool
    {
        // Solo l'autore può modificare il messaggio entro 5 minuti
        if ($this->user_id !== $user->id) {
            return false;
        }

        return $this->created_at->diffInMinutes(now()) <= 5;
    }

    public function canBeDeletedBy(User $user): bool
    {
        // L'autore può eliminare il proprio messaggio
        if ($this->user_id === $user->id) {
            return true;
        }

        // Admin e moderatori possono eliminare qualsiasi messaggio
        if ($this->chat->isParticipantModerator($user)) {
            return true;
        }

        return false;
    }

    public function edit(string $newMessage): bool
    {
        $this->update([
            'message' => $newMessage,
            'is_edited' => true,
            'edited_at' => now(),
        ]);

        return true;
    }

    // Metodi statici per creare messaggi
    public static function createSystemMessage(Chat $chat, string $message): ChatMessage
    {
        return self::create([
            'chat_id' => $chat->id,
            'user_id' => null,
            'message' => $message,
            'is_system_message' => true,
        ]);
    }

    public static function createUserMessage(Chat $chat, User $user, string $message, array $fileData = null): ChatMessage
    {
        $messageData = [
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'message' => $message,
            'is_system_message' => false,
        ];

        if ($fileData) {
            $messageData = array_merge($messageData, $fileData);
        }

        $chatMessage = self::create($messageData);

        // Aggiorna l'ultimo messaggio della chat
        $chat->updateLastMessage();

        return $chatMessage;
    }
}
