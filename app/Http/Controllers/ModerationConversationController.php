<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ModerationConversation;
use App\Models\ModerationMessage;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ModerationConversationController extends Controller
{
    /**
     * Mostra la conversazione di moderazione
     */
    public function show(Request $request, $reportId): View
    {
        try {
            $report = Report::with(['conversation.messages.user', 'reportable', 'user'])->findOrFail($reportId);
            $conversation = $report->conversation;
            
            if (!$conversation) {
                abort(404, 'Conversazione non trovata');
            }

            // Verifica i permessi
            $user = Auth::user();
            $isAuthor = $conversation->content_author_id === $user->id;
            $isModerator = $user->hasAnyRole(['admin', 'moderator']);
            
            // Debug temporaneo
            Log::info('Conversation access check', [
                'report_id' => $reportId,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'content_author_id' => $conversation->content_author_id,
                'is_author' => $isAuthor,
                'is_moderator' => $isModerator,
                'user_roles' => $user->roles->pluck('name')->toArray()
            ]);
            
            if (!$isAuthor && !$isModerator) {
                abort(403, 'Non hai i permessi per visualizzare questa conversazione');
            }

            // Marca i messaggi come letti per l'utente corrente
            $conversation->markMessagesAsReadForUser($user);

            // Ottieni i messaggi (pubblici per l'autore, tutti per i moderatori)
            $messages = $isModerator 
                ? $conversation->messages()->with('user')->orderBy('created_at', 'asc')->get()
                : $conversation->publicMessages()->with('user')->orderBy('created_at', 'asc')->get();

            return view('moderation.conversation', compact('report', 'conversation', 'messages', 'isAuthor', 'isModerator'));
            
        } catch (\Exception $e) {
            Log::error('Error in conversation show', [
                'report_id' => $reportId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            abort(500, 'Errore interno del server: ' . $e->getMessage());
        }
    }

    /**
     * Invia un messaggio nella conversazione
     */
    public function sendMessage(Request $request, $reportId): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'is_internal' => 'boolean'
        ]);

        $report = Report::with('conversation')->findOrFail($reportId);
        $conversation = $report->conversation;
        
        if (!$conversation) {
            return response()->json(['success' => false, 'message' => 'Conversazione non trovata']);
        }

        $user = Auth::user();
        $isAuthor = $conversation->content_author_id === $user->id;
        $isModerator = $user->hasAnyRole(['admin', 'moderator']);
        $isInternal = $request->boolean('is_internal', false);

        // Verifica i permessi
        if (!$isAuthor && !$isModerator) {
            return response()->json(['success' => false, 'message' => 'Non hai i permessi per inviare messaggi']);
        }

        // Solo i moderatori possono inviare messaggi interni
        if ($isInternal && !$isModerator) {
            return response()->json(['success' => false, 'message' => 'Solo i moderatori possono inviare messaggi interni']);
        }

        // Crea il messaggio
        $message = null;
        if ($isAuthor) {
            $message = ModerationMessage::createAuthorMessage(
                $conversation,
                $user,
                $request->message
            );
            
            // Aggiorna lo status della conversazione
            $conversation->setWaitingModerator();
            
            // Notifica i moderatori
            $this->notifyModerators($message);
        } elseif ($isModerator) {
            $messageType = $user->hasRole('admin') ? ModerationMessage::TYPE_ADMIN : ModerationMessage::TYPE_MODERATOR;
            
            $message = ModerationMessage::create([
                'conversation_id' => $conversation->id,
                'user_id' => $user->id,
                'type' => $messageType,
                'message' => $request->message,
                'is_internal' => $isInternal,
            ]);
            
            // Aggiorna lo status della conversazione (solo per messaggi pubblici)
            if (!$isInternal) {
                $conversation->setWaitingAuthor();
                
                // Notifica l'autore
                $this->notifyAuthor($message);
            }
        }

        if (!$message) {
            return response()->json(['success' => false, 'message' => 'Errore durante l\'invio del messaggio']);
        }

        // Carica le relazioni per la risposta
        $message->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Messaggio inviato con successo',
            'data' => [
                'message' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'type' => $message->type,
                    'is_internal' => $message->is_internal,
                    'created_at' => $message->created_at->format('d/m/Y H:i'),
                    'user' => [
                        'id' => $message->user->id,
                        'name' => $message->user->name,
                    ],
                    'icon' => $message->icon,
                    'type_class' => $message->type_class,
                    'author_name' => $message->author_name,
                ],
                'conversation_status' => $conversation->status
            ]
        ]);
    }

    /**
     * Ottiene i messaggi della conversazione (per AJAX)
     */
    public function getMessages(Request $request, $reportId): JsonResponse
    {
        $report = Report::with('conversation')->findOrFail($reportId);
        $conversation = $report->conversation;
        
        if (!$conversation) {
            return response()->json(['success' => false, 'message' => 'Conversazione non trovata']);
        }

        $user = Auth::user();
        $isAuthor = $conversation->content_author_id === $user->id;
        $isModerator = $user->hasAnyRole(['admin', 'moderator']);
        
        if (!$isAuthor && !$isModerator) {
            return response()->json(['success' => false, 'message' => 'Non hai i permessi per visualizzare questa conversazione']);
        }

        // Ottieni i messaggi
        $messages = $isModerator 
            ? $conversation->messages()->with('user')->orderBy('created_at', 'asc')->get()
            : $conversation->publicMessages()->with('user')->orderBy('created_at', 'asc')->get();

        // Formatta i messaggi per la risposta
        $formattedMessages = $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'type' => $message->type,
                'is_internal' => $message->is_internal,
                'created_at' => $message->created_at->format('d/m/Y H:i'),
                'user' => [
                    'id' => $message->user->id,
                    'name' => $message->user->name,
                ],
                'icon' => $message->icon,
                'type_class' => $message->type_class,
                'author_name' => $message->author_name,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'messages' => $formattedMessages,
                'conversation_status' => $conversation->status
            ]
        ]);
    }

    /**
     * Marca i messaggi come letti
     */
    public function markAsRead(Request $request, $reportId): JsonResponse
    {
        $report = Report::with('conversation')->findOrFail($reportId);
        $conversation = $report->conversation;
        
        if (!$conversation) {
            return response()->json(['success' => false, 'message' => 'Conversazione non trovata']);
        }

        $user = Auth::user();
        $isAuthor = $conversation->content_author_id === $user->id;
        $isModerator = $user->hasAnyRole(['admin', 'moderator']);
        
        if (!$isAuthor && !$isModerator) {
            return response()->json(['success' => false, 'message' => 'Non hai i permessi per questa azione']);
        }

        $conversation->markMessagesAsReadForUser($user);

        return response()->json(['success' => true, 'message' => 'Messaggi marcati come letti']);
    }

    /**
     * Notifica i moderatori di un nuovo messaggio
     */
    private function notifyModerators(ModerationMessage $message): void
    {
        $conversation = $message->conversation;
        $report = $conversation->report;
        
        // Ottieni tutti i moderatori e admin
        $moderators = \App\Models\User::role(['admin', 'moderator'])->get();
        
        foreach ($moderators as $moderator) {
            // Non notificare se è il moderatore assegnato e ha già letto
            if ($conversation->assigned_moderator_id === $moderator->id) {
                continue;
            }
            
            Notification::createModerationResponseNotification($message, $moderator);
        }
    }

    /**
     * Notifica l'autore di un nuovo messaggio
     */
    private function notifyAuthor(ModerationMessage $message): void
    {
        $conversation = $message->conversation;
        $author = $conversation->contentAuthor;
        
        if ($author) {
            Notification::createModerationResponseNotification($message, $author);
        }
    }
}
