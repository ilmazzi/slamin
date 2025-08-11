<?php

namespace App\Http\Controllers;

use App\Events\MessageReactionAdded;
use App\Events\MessageReactionRemoved;
use App\Models\ChatMessage;
use App\Models\MessageReaction;
use App\Models\ChatParticipant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatReactionController extends Controller
{
    /**
     * Aggiunge una reazione a un messaggio
     */
    public function addReaction(Request $request, int $roomId, int $messageId): JsonResponse
    {
        $request->validate([
            'reaction' => 'required|string|max:10'
        ]);

        // Verifica che l'utente abbia accesso alla chat
        $participant = ChatParticipant::where('chat_room_id', $roomId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$participant) {
            return response()->json(['error' => 'Accesso negato'], 403);
        }

        // Verifica che il messaggio esista in questa chat
        $message = ChatMessage::where('chat_room_id', $roomId)
            ->where('id', $messageId)
            ->first();

        if (!$message) {
            return response()->json(['error' => 'Messaggio non trovato'], 404);
        }

        try {
            $reaction = MessageReaction::addReaction(
                $messageId,
                auth()->id(),
                $request->reaction
            );

                        // Carica la relazione user per il broadcast
            $reaction->load('user:id,name');

            // Usa AvatarHelper per generare l'URL dell'avatar
            $reaction->user->avatar = \App\Helpers\AvatarHelper::getUserAvatarUrl($reaction->user);

            // Broadcast dell'evento
            broadcast(new MessageReactionAdded($reaction, $roomId));

            return response()->json([
                'success' => true,
                'reaction' => $reaction,
                'reactions' => MessageReaction::getCachedReactions($messageId)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Errore nell\'aggiunta della reazione',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rimuove una reazione da un messaggio
     */
    public function removeReaction(Request $request, int $roomId, int $messageId): JsonResponse
    {
        // Verifica che l'utente abbia accesso alla chat
        $participant = ChatParticipant::where('chat_room_id', $roomId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$participant) {
            return response()->json(['error' => 'Accesso negato'], 403);
        }

        // Verifica che il messaggio esista in questa chat
        $message = ChatMessage::where('chat_room_id', $roomId)
            ->where('id', $messageId)
            ->first();

        if (!$message) {
            return response()->json(['error' => 'Messaggio non trovato'], 404);
        }

        try {
            $removed = MessageReaction::removeReaction($messageId, auth()->id());

            if ($removed) {
                // Broadcast dell'evento
                broadcast(new MessageReactionRemoved($messageId, $roomId, auth()->id()));

                return response()->json([
                    'success' => true,
                    'reactions' => MessageReaction::getCachedReactions($messageId)
                ]);
            }

            return response()->json([
                'error' => 'Reazione non trovata'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Errore nella rimozione della reazione',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ottiene le reazioni di un messaggio
     */
    public function getReactions(int $roomId, int $messageId): JsonResponse
    {
        // Verifica che l'utente abbia accesso alla chat
        $participant = ChatParticipant::where('chat_room_id', $roomId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$participant) {
            return response()->json(['error' => 'Accesso negato'], 403);
        }

        // Verifica che il messaggio esista in questa chat
        $message = ChatMessage::where('chat_room_id', $roomId)
            ->where('id', $messageId)
            ->first();

        if (!$message) {
            return response()->json(['error' => 'Messaggio non trovato'], 404);
        }

        $reactions = MessageReaction::getCachedReactions($messageId);

        return response()->json([
            'success' => true,
            'reactions' => $reactions
        ]);
    }

    /**
     * Ottiene le reazioni per più messaggi in una sola chiamata
     */
    public function getReactionsBatch(Request $request, int $roomId): JsonResponse
    {
        // Verifica che l'utente abbia accesso alla chat
        $participant = ChatParticipant::where('chat_room_id', $roomId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$participant) {
            return response()->json(['error' => 'Accesso negato'], 403);
        }

        $messageIds = $request->input('message_ids', []);

        if (empty($messageIds)) {
            return response()->json([
                'success' => true,
                'reactions' => []
            ]);
        }

        // Carica le reazioni per tutti i messaggi richiesti
        $allReactions = [];
        foreach ($messageIds as $messageId) {
            $reactions = MessageReaction::getCachedReactions($messageId);
            if (!empty($reactions)) {
                $allReactions[$messageId] = $reactions;
            }
        }

        return response()->json([
            'success' => true,
            'reactions' => $allReactions
        ]);
    }

    /**
     * Metodo semplice per aggiungere reazioni (compatibile con il frontend)
     */
    public function addReactionSimple(Request $request): JsonResponse
    {
        $request->validate([
            'message_id' => 'required|integer',
            'reaction' => 'required|string|max:10'
        ]);

        $messageId = $request->message_id;
        $reaction = $request->reaction;

        // Verifica che il messaggio esista
        $message = ChatMessage::find($messageId);
        if (!$message) {
            return response()->json(['error' => 'Messaggio non trovato'], 404);
        }

        // Verifica che l'utente abbia accesso alla chat
        $participant = ChatParticipant::where('chat_room_id', $message->chat_room_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$participant) {
            return response()->json(['error' => 'Accesso negato'], 403);
        }

        try {
            $reactionModel = MessageReaction::addReaction(
                $messageId,
                auth()->id(),
                $reaction
            );

            // Carica la relazione user per il broadcast
            $reactionModel->load('user:id,name');
            $reactionModel->user->avatar = \App\Helpers\AvatarHelper::getUserAvatarUrl($reactionModel->user);

            // Broadcast dell'evento
            broadcast(new MessageReactionAdded($reactionModel, $message->chat_room_id));

            return response()->json([
                'success' => true,
                'reaction' => $reactionModel,
                'reactions' => MessageReaction::getCachedReactions($messageId)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Errore nell\'aggiunta della reazione',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Metodo semplice per toggle delle reazioni (compatibile con il frontend)
     */
    public function toggleReactionSimple(Request $request): JsonResponse
    {
        $request->validate([
            'message_id' => 'required|integer',
            'reaction' => 'required|string|max:10'
        ]);

        $messageId = $request->message_id;
        $reaction = $request->reaction;

        // Verifica che il messaggio esista
        $message = ChatMessage::find($messageId);
        if (!$message) {
            return response()->json(['error' => 'Messaggio non trovato'], 404);
        }

        // Verifica che l'utente abbia accesso alla chat
        $participant = ChatParticipant::where('chat_room_id', $message->chat_room_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$participant) {
            return response()->json(['error' => 'Accesso negato'], 403);
        }

        try {
            // Controlla se l'utente ha già questa reazione
            $existingReaction = MessageReaction::where('message_id', $messageId)
                ->where('user_id', auth()->id())
                ->where('reaction', $reaction)
                ->first();

            if ($existingReaction) {
                // Rimuovi la reazione esistente
                $existingReaction->delete();

                // Broadcast dell'evento di rimozione
                broadcast(new MessageReactionRemoved($messageId, $message->chat_room_id, auth()->id()));
            } else {
                // Aggiungi la nuova reazione
                $reactionModel = MessageReaction::addReaction(
                    $messageId,
                    auth()->id(),
                    $reaction
                );

                // Carica la relazione user per il broadcast
                $reactionModel->load('user:id,name');
                $reactionModel->user->avatar = \App\Helpers\AvatarHelper::getUserAvatarUrl($reactionModel->user);

                // Broadcast dell'evento di aggiunta
                broadcast(new MessageReactionAdded($reactionModel, $message->chat_room_id));
            }

            return response()->json([
                'success' => true,
                'reactions' => MessageReaction::getCachedReactions($messageId)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Errore nel toggle della reazione',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
