<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ChatMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Invia un nuovo messaggio
     */
    public function store(Request $request, Chat $chat)
    {
        $user = Auth::user();

        // Verifica che l'utente sia partecipante della chat
        if (!$chat->hasUser($user)) {
            return response()->json(['error' => 'Non hai accesso a questa chat'], 403);
        }

        // Verifica che l'utente non sia silenziato
        $participation = $chat->participants()->where('user_id', $user->id)->first();
        if (!$participation->canSendMessages()) {
            return response()->json(['error' => 'Non puoi inviare messaggi in questa chat'], 403);
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required_without:file|string|max:2000',
            'file' => 'nullable|file|max:15360', // 15MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $messageData = [
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'message' => $request->message ?: '',
        ];

        // Gestione upload file
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileData = $this->handleFileUpload($file);
            $messageData = array_merge($messageData, $fileData);
        }

        // Crea il messaggio
        $message = ChatMessage::createUserMessage($chat, $user, $messageData['message'], $fileData ?? null);

        // Carica le relazioni per la risposta
        $message->load('user');

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Modifica un messaggio esistente
     */
    public function update(Request $request, Chat $chat, ChatMessage $message)
    {
        $user = Auth::user();

        // Verifica che l'utente sia partecipante della chat
        if (!$chat->hasUser($user)) {
            return response()->json(['error' => 'Non hai accesso a questa chat'], 403);
        }

        // Verifica che l'utente possa modificare il messaggio
        if (!$message->canBeEditedBy($user)) {
            return response()->json(['error' => 'Non puoi modificare questo messaggio'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        // Modifica il messaggio
        $message->edit($request->message);

        return response()->json([
            'success' => true,
            'message' => $message->fresh(),
        ]);
    }

    /**
     * Elimina un messaggio
     */
    public function destroy(Chat $chat, ChatMessage $message)
    {
        $user = Auth::user();

        // Verifica che l'utente sia partecipante della chat
        if (!$chat->hasUser($user)) {
            return response()->json(['error' => 'Non hai accesso a questa chat'], 403);
        }

        // Verifica che l'utente possa eliminare il messaggio
        if (!$message->canBeDeletedBy($user)) {
            return response()->json(['error' => 'Non puoi eliminare questo messaggio'], 403);
        }

        // Elimina il file se presente
        if ($message->hasFile()) {
            if (Storage::disk('public')->exists($message->file_path)) {
                Storage::disk('public')->delete($message->file_path);
            }
        }

        // Elimina il messaggio
        $message->delete();

        // Aggiorna l'ultimo messaggio della chat
        $chat->updateLastMessage();

        return response()->json([
            'success' => true,
            'message' => 'Messaggio eliminato con successo',
        ]);
    }

    /**
     * Cerca messaggi in una chat
     */
    public function search(Request $request, Chat $chat)
    {
        $user = Auth::user();

        // Verifica che l'utente sia partecipante della chat
        if (!$chat->hasUser($user)) {
            return response()->json(['error' => 'Non hai accesso a questa chat'], 403);
        }

        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $query = $request->get('query');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);

        $messages = $chat->messages()
                        ->with('user')
                        ->where('message', 'like', "%{$query}%")
                        ->where('is_system_message', false)
                        ->orderBy('created_at', 'desc')
                        ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'messages' => $messages->items(),
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
            ],
        ]);
    }

    /**
     * Scarica un file allegato
     */
    public function downloadFile(Chat $chat, ChatMessage $message)
    {
        $user = Auth::user();

        // Verifica che l'utente sia partecipante della chat
        if (!$chat->hasUser($user)) {
            abort(403, 'Non hai accesso a questa chat.');
        }

        // Verifica che il messaggio abbia un file
        if (!$message->hasFile()) {
            abort(404, 'File non trovato.');
        }

        // Verifica che il file esista
        if (!Storage::disk('public')->exists($message->file_path)) {
            abort(404, 'File non trovato sul server.');
        }

        return Storage::disk('public')->download(
            $message->file_path,
            $message->file_name
        );
    }

    /**
     * Gestisce l'upload di un file
     */
    private function handleFileUpload($file)
    {
        // Validazione del tipo di file
        $allowedTypes = [
            // Immagini
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            // PDF
            'application/pdf',
            // Office documents
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];

        if (!in_array($file->getMimeType(), $allowedTypes)) {
            throw new \Exception('Tipo di file non supportato.');
        }

        // Validazione dimensione
        $maxSize = 15 * 1024 * 1024; // 15MB
        if ($file->getSize() > $maxSize) {
            throw new \Exception('File troppo grande. Dimensione massima: 15MB.');
        }

        // Genera nome file unico
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = 'chat-files/' . date('Y/m/d') . '/' . $fileName;

        // Salva il file
        Storage::disk('public')->put($filePath, file_get_contents($file));

        return [
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ];
    }

    /**
     * Ottieni messaggi recenti (per polling)
     */
    public function getRecentMessages(Chat $chat, Request $request)
    {
        $user = Auth::user();

        // Verifica che l'utente sia partecipante della chat
        if (!$chat->hasUser($user)) {
            return response()->json(['error' => 'Non hai accesso a questa chat'], 403);
        }

        $lastMessageId = $request->get('last_message_id', 0);

        $messages = $chat->messages()
                        ->with('user')
                        ->where('id', '>', $lastMessageId)
                        ->orderBy('created_at', 'asc')
                        ->get();

        // Marca come letti se ci sono nuovi messaggi
        if ($messages->count() > 0) {
            $chat->markAsRead($user);
        }

        return response()->json([
            'messages' => $messages,
            'has_new_messages' => $messages->count() > 0,
        ]);
    }

    /**
     * Reazione a un messaggio (like, etc.)
     */
    public function react(Request $request, Chat $chat, ChatMessage $message)
    {
        $user = Auth::user();

        // Verifica che l'utente sia partecipante della chat
        if (!$chat->hasUser($user)) {
            return response()->json(['error' => 'Non hai accesso a questa chat'], 403);
        }

        $request->validate([
            'reaction' => 'required|string|in:like,heart,laugh,wow,sad,angry',
        ]);

        // Per ora implementiamo solo il like, le altre reazioni possono essere aggiunte in futuro
        if ($request->reaction === 'like') {
            // Toggle like
            $liked = $message->likes()->where('user_id', $user->id)->exists();

            if ($liked) {
                $message->likes()->where('user_id', $user->id)->delete();
            } else {
                $message->likes()->create(['user_id' => $user->id]);
            }

            return response()->json([
                'success' => true,
                'liked' => !$liked,
                'likes_count' => $message->likes()->count(),
            ]);
        }

        return response()->json(['error' => 'Reazione non supportata'], 400);
    }
}
