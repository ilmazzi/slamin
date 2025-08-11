<?php

namespace App\Observers;

use App\Models\ChatMessage;
use App\Models\Notification;
use App\Models\ChatParticipant;
use Illuminate\Support\Facades\Log;

class ChatMessageObserver
{
    /**
     * Handle the ChatMessage "created" event.
     */
    public function created(ChatMessage $chatMessage): void
    {
        try {
            // Ottieni tutti i partecipanti della chat tranne il mittente
            $participants = ChatParticipant::where('chat_room_id', $chatMessage->chat_room_id)
                ->where('user_id', '!=', $chatMessage->sender_id)
                ->get();

            foreach ($participants as $participant) {
                // Crea notifica per ogni partecipante
                Notification::createChatMessageNotification($chatMessage, $participant->user);
            }

            Log::info('Chat message notifications created', [
                'message_id' => $chatMessage->id,
                'chat_room_id' => $chatMessage->chat_room_id,
                'sender_id' => $chatMessage->sender_id,
                'recipients_count' => $participants->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create chat message notifications', [
                'message_id' => $chatMessage->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle the ChatMessage "updated" event.
     */
    public function updated(ChatMessage $chatMessage): void
    {
        // Gestisci aggiornamenti se necessario
    }

    /**
     * Handle the ChatMessage "deleted" event.
     */
    public function deleted(ChatMessage $chatMessage): void
    {
        // Gestisci cancellazioni se necessario
    }

    /**
     * Handle the ChatMessage "restored" event.
     */
    public function restored(ChatMessage $chatMessage): void
    {
        // Gestisci ripristini se necessario
    }

    /**
     * Handle the ChatMessage "force deleted" event.
     */
    public function forceDeleted(ChatMessage $chatMessage): void
    {
        // Gestisci cancellazioni forzate se necessario
    }
}
