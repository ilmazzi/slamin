<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'notification_type',
        'enabled',
        'group_id',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * Relazione con l'utente
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione con il gruppo (opzionale)
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Verifica se un utente ha le notifiche abilitate per un tipo specifico
     */
    public static function isEnabled($userId, $notificationType, $groupId = null): bool
    {
        $preference = static::where('user_id', $userId)
            ->where('notification_type', $notificationType)
            ->where('group_id', $groupId)
            ->first();

        // Se non esiste una preferenza specifica, controlla quella generale
        if (!$preference && $groupId) {
            $preference = static::where('user_id', $userId)
                ->where('notification_type', $notificationType)
                ->whereNull('group_id')
                ->first();
        }

        // Se non esiste nessuna preferenza, default è true
        return $preference ? $preference->enabled : true;
    }

    /**
     * Imposta una preferenza notifica per un utente
     */
    public static function setPreference($userId, $notificationType, $enabled, $groupId = null): void
    {
        static::updateOrCreate(
            [
                'user_id' => $userId,
                'notification_type' => $notificationType,
                'group_id' => $groupId,
            ],
            [
                'enabled' => $enabled,
            ]
        );
    }
}
