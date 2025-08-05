<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Invia un messaggio in una chat
     */
    public function store(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'message_type' => 'sometimes|string|in:text,image,file'
        ]);

        $user = Auth::user();

        // Verifica che l'utente sia partecipante della chat
        if (!$chat->hasUser($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Non hai accesso a questa chat.'
            ], 403);
        }

        try {
            // Crea il messaggio
            $message = $chat->messages()->create([
                'user_id' => $user->id,
                'message' => $request->message,
                'message_type' => $request->message_type ?? 'text'
            ]);

            // Carica la relazione user
            $message->load('user');

            // Marca la chat come aggiornata
            $chat->update([
                'last_message_at' => now()
            ]);

            // Broadcast del messaggio via Reverb
            broadcast(new NewChatMessage($message))->toOthers();

            return response()->json([
                'success' => true,
                'message' => $message->load('user')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nell\'invio del messaggio.'
            ], 500);
        }
    }

    /**
     * Ottieni i messaggi di una chat
     */
    public function index(Request $request, Chat $chat)
    {
        $user = Auth::user();

        // Verifica che l'utente sia partecipante della chat
        if (!$chat->hasUser($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Non hai accesso a questa chat.'
            ], 403);
        }

        $messages = $chat->messages()
                        ->with('user')
                        ->orderBy('created_at', 'desc')
                        ->paginate(50);

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    /**
     * Elimina un messaggio
     */
    public function destroy(ChatMessage $message)
    {
        $user = Auth::user();

        // Verifica che l'utente sia il proprietario del messaggio
        if ($message->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Non puoi eliminare questo messaggio.'
            ], 403);
        }

        try {
            $message->delete();

            return response()->json([
                'success' => true,
                'message' => 'Messaggio eliminato con successo.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nell\'eliminazione del messaggio.'
            ], 500);
        }
    }
}
