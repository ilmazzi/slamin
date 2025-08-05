<?php

namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Chat;
use App\Models\ChatMessage;

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $userConnections;
    protected $chatRooms;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->userConnections = [];
        $this->chatRooms = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        Log::info("Nuova connessione! ({$conn->resourceId})");
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        
        if (!$data || !isset($data['type'])) {
            return;
        }

        switch ($data['type']) {
            case 'auth':
                $this->handleAuth($from, $data);
                break;
            case 'message':
                $this->handleMessage($from, $data);
                break;
            case 'typing':
                $this->handleTyping($from, $data);
                break;
            case 'call_request':
                $this->handleCallRequest($from, $data);
                break;
            case 'call_response':
                $this->handleCallResponse($from, $data);
                break;
            case 'webrtc_signal':
                $this->handleWebRTCSignal($from, $data);
                break;
            case 'join_chat':
                $this->handleJoinChat($from, $data);
                break;
            case 'leave_chat':
                $this->handleLeaveChat($from, $data);
                break;
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        
        // Rimuovi l'utente dalle connessioni
        foreach ($this->userConnections as $userId => $connection) {
            if ($connection === $conn) {
                unset($this->userConnections[$userId]);
                $this->broadcastUserStatus($userId, 'offline');
                break;
            }
        }

        Log::info("Connessione {$conn->resourceId} chiusa");
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        Log::error("Errore WebSocket: {$e->getMessage()}");
        $conn->close();
    }

    protected function handleAuth($conn, $data)
    {
        if (!isset($data['user_id']) || !isset($data['token'])) {
            $conn->send(json_encode([
                'type' => 'auth_error',
                'message' => 'Credenziali mancanti'
            ]));
            return;
        }

        // Verifica il token (implementa la tua logica di autenticazione)
        $user = User::find($data['user_id']);
        if (!$user) {
            $conn->send(json_encode([
                'type' => 'auth_error',
                'message' => 'Utente non trovato'
            ]));
            return;
        }

        // Salva la connessione dell'utente
        $this->userConnections[$user->id] = $conn;
        $conn->user_id = $user->id;

        // Imposta l'utente come online
        $user->setOnline();

        // Conferma autenticazione
        $conn->send(json_encode([
            'type' => 'auth_success',
            'user_id' => $user->id,
            'message' => 'Autenticato con successo'
        ]));

        // Notifica agli altri utenti che questo utente è online
        $this->broadcastUserStatus($user->id, 'online');
    }

    protected function handleMessage($conn, $data)
    {
        if (!isset($conn->user_id)) {
            return;
        }

        if (!isset($data['chat_id']) || !isset($data['message'])) {
            return;
        }

        $chat = Chat::find($data['chat_id']);
        if (!$chat) {
            return;
        }

        // Verifica che l'utente sia partecipante della chat
        $participation = $chat->participants()->where('user_id', $conn->user_id)->first();
        if (!$participation) {
            return;
        }

        // Salva il messaggio nel database
        $chatMessage = ChatMessage::create([
            'chat_id' => $chat->id,
            'user_id' => $conn->user_id,
            'message' => $data['message'],
            'message_type' => $data['message_type'] ?? 'text'
        ]);

        // Prepara il messaggio per il broadcast
        $messageData = [
            'type' => 'message',
            'chat_id' => $chat->id,
            'message' => [
                'id' => $chatMessage->id,
                'user_id' => $chatMessage->user_id,
                'message' => $chatMessage->message,
                'message_type' => $chatMessage->message_type,
                'created_at' => $chatMessage->created_at->toISOString()
            ]
        ];

        // Invia il messaggio a tutti i partecipanti della chat
        $this->broadcastToChat($chat->id, $messageData);
    }

    protected function handleTyping($conn, $data)
    {
        if (!isset($conn->user_id) || !isset($data['chat_id'])) {
            return;
        }

        $typingData = [
            'type' => 'typing',
            'chat_id' => $data['chat_id'],
            'user_id' => $conn->user_id,
            'is_typing' => $data['is_typing'] ?? true
        ];

        $this->broadcastToChat($data['chat_id'], $typingData);
    }

    protected function handleCallRequest($conn, $data)
    {
        if (!isset($conn->user_id) || !isset($data['target_user_id'])) {
            return;
        }

        $callData = [
            'type' => 'call_request',
            'from_user_id' => $conn->user_id,
            'call_type' => $data['call_type'] ?? 'audio', // audio o video
            'offer' => $data['offer'] ?? null
        ];

        $this->sendToUser($data['target_user_id'], $callData);
    }

    protected function handleCallResponse($conn, $data)
    {
        if (!isset($conn->user_id) || !isset($data['from_user_id'])) {
            return;
        }

        $responseData = [
            'type' => 'call_response',
            'to_user_id' => $conn->user_id,
            'accepted' => $data['accepted'] ?? false,
            'answer' => $data['answer'] ?? null
        ];

        $this->sendToUser($data['from_user_id'], $responseData);
    }

    protected function handleWebRTCSignal($conn, $data)
    {
        if (!isset($conn->user_id) || !isset($data['target_user_id'])) {
            return;
        }

        $signalData = [
            'type' => 'webrtc_signal',
            'from_user_id' => $conn->user_id,
            'signal' => $data['signal'],
            'signal_type' => $data['signal_type'] ?? 'ice_candidate'
        ];

        $this->sendToUser($data['target_user_id'], $signalData);
    }

    protected function handleJoinChat($conn, $data)
    {
        if (!isset($conn->user_id) || !isset($data['chat_id'])) {
            return;
        }

        $chatId = $data['chat_id'];
        if (!isset($this->chatRooms[$chatId])) {
            $this->chatRooms[$chatId] = [];
        }

        $this->chatRooms[$chatId][$conn->user_id] = $conn;

        $conn->send(json_encode([
            'type' => 'chat_joined',
            'chat_id' => $chatId
        ]));
    }

    protected function handleLeaveChat($conn, $data)
    {
        if (!isset($conn->user_id) || !isset($data['chat_id'])) {
            return;
        }

        $chatId = $data['chat_id'];
        if (isset($this->chatRooms[$chatId][$conn->user_id])) {
            unset($this->chatRooms[$chatId][$conn->user_id]);
        }
    }

    protected function broadcastToChat($chatId, $data)
    {
        if (!isset($this->chatRooms[$chatId])) {
            return;
        }

        $message = json_encode($data);
        foreach ($this->chatRooms[$chatId] as $connection) {
            $connection->send($message);
        }
    }

    protected function sendToUser($userId, $data)
    {
        if (!isset($this->userConnections[$userId])) {
            return;
        }

        $this->userConnections[$userId]->send(json_encode($data));
    }

    protected function broadcastUserStatus($userId, $status)
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        $statusData = [
            'type' => 'user_status',
            'user_id' => $userId,
            'status' => $status,
            'online_status' => $user->online_status,
            'last_seen' => $user->last_seen_at ? $user->last_seen_at->toISOString() : null
        ];

        // Invia a tutti i client connessi
        foreach ($this->clients as $client) {
            $client->send(json_encode($statusData));
        }
    }
} 