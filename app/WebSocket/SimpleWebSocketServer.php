<?php

namespace App\WebSocket;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Chat;
use App\Models\ChatMessage;

class SimpleWebSocketServer
{
    protected $clients = [];
    protected $userConnections = [];
    protected $chatRooms = [];
    protected $server;
    protected $clientData = []; // Array per memorizzare i dati dei client

    public function __construct()
    {
        $this->server = null;
    }

    public function start($host = '0.0.0.0', $port = 8080)
    {
        $this->server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($this->server, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($this->server, $host, $port);
        socket_listen($this->server);

        Log::info("WebSocket server avviato su {$host}:{$port}");

        while (true) {
            $changed = array_merge([$this->server], $this->clients);
            socket_select($changed, $null, $null, 0, 10);

            if (in_array($this->server, $changed)) {
                $client = socket_accept($this->server);
                $this->clients[] = $client;
                
                // Handshake WebSocket
                $this->performHandshake($client);
                
                Log::info("Nuova connessione WebSocket");
            }

            foreach ($this->clients as $key => $client) {
                if (in_array($client, $changed)) {
                    $data = $this->receiveData($client);
                    
                    if ($data === false) {
                        unset($this->clients[$key]);
                        try {
                            socket_close($client);
                        } catch (\Exception $e) {
                            // Socket già chiuso, ignora l'errore
                        }
                        continue;
                    }

                    if ($data) {
                        $this->handleMessage($client, $data);
                    }
                }
            }
        }
    }

    protected function performHandshake($client)
    {
        $request = socket_read($client, 5000);
        preg_match('#Sec-WebSocket-Key: (.*)\r\n#', $request, $matches);
        
        if (isset($matches[1])) {
            $key = base64_encode(sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));
            $headers = "HTTP/1.1 101 Switching Protocols\r\n";
            $headers .= "Upgrade: websocket\r\n";
            $headers .= "Connection: Upgrade\r\n";
            $headers .= "Sec-WebSocket-Accept: $key\r\n\r\n";
            socket_write($client, $headers, strlen($headers));
        }
    }

    protected function receiveData($client)
    {
        $data = socket_read($client, 2048, PHP_BINARY_READ);
        
        if ($data === false || strlen($data) === 0) {
            // Client disconnesso
            $this->removeClient($client);
            return false;
        }

        return $this->decode($data);
    }

    protected function decode($data)
    {
        $length = ord($data[1]) & 127;
        
        if ($length == 126) {
            $masks = substr($data, 4, 4);
            $data = substr($data, 8);
        } elseif ($length == 127) {
            $masks = substr($data, 10, 4);
            $data = substr($data, 14);
        } else {
            $masks = substr($data, 2, 4);
            $data = substr($data, 6);
        }

        $text = '';
        for ($i = 0; $i < strlen($data); ++$i) {
            $text .= $data[$i] ^ $masks[$i % 4];
        }
        
        return $text;
    }

    protected function encode($text)
    {
        $b1 = 0x80 | (0x1 & 0x0f);
        $length = strlen($text);
        
        if ($length <= 125) {
            $header = pack('CC', $b1, $length);
        } elseif ($length > 125 && $length < 65536) {
            $header = pack('CCn', $b1, 126, $length);
        } else {
            $header = pack('CCNN', $b1, 127, $length);
        }
        
        return $header . $text;
    }

    protected function send($client, $data)
    {
        $encoded = $this->encode(json_encode($data));
        return socket_write($client, $encoded, strlen($encoded));
    }

    protected function handleMessage($client, $data)
    {
        $message = json_decode($data, true);
        
        if (!$message || !isset($message['type'])) {
            return;
        }

        switch ($message['type']) {
            case 'auth':
                $this->handleAuth($client, $message);
                break;
            case 'heartbeat':
                $this->handleHeartbeat($client, $message);
                break;
            case 'message':
                $this->handleChatMessage($client, $message);
                break;
            case 'typing':
                $this->handleTyping($client, $message);
                break;
            case 'call_request':
                $this->handleCallRequest($client, $message);
                break;
            case 'call_response':
                $this->handleCallResponse($client, $message);
                break;
            case 'webrtc_signal':
                $this->handleWebRTCSignal($client, $message);
                break;
            case 'join_chat':
                $this->handleJoinChat($client, $message);
                break;
            case 'leave_chat':
                $this->handleLeaveChat($client, $message);
                break;
        }
    }

    protected function handleAuth($client, $data)
    {
        if (!isset($data['user_id']) || !isset($data['token'])) {
            $this->send($client, [
                'type' => 'auth_error',
                'message' => 'Credenziali mancanti'
            ]);
            return;
        }

        $user = User::find($data['user_id']);
        if (!$user) {
            $this->send($client, [
                'type' => 'auth_error',
                'message' => 'Utente non trovato'
            ]);
            return;
        }

        // Salva la connessione dell'utente
        $this->userConnections[$user->id] = $client;
        $this->clientData[spl_object_hash($client)] = [
            'user_id' => $user->id,
            'authenticated' => true
        ];

        // Imposta l'utente come online
        $user->setOnline();

        // Conferma autenticazione
        $this->send($client, [
            'type' => 'auth_success',
            'user_id' => $user->id,
            'message' => 'Autenticato con successo'
        ]);

        // Notifica agli altri utenti che questo utente è online
        $this->broadcastUserStatus($user->id, 'online');
    }

    protected function handleHeartbeat($client, $data)
    {
        // Risponde al heartbeat per mantenere la connessione attiva
        $this->send($client, [
            'type' => 'heartbeat_ack',
            'timestamp' => $data['timestamp'] ?? time()
        ]);
    }

    protected function handleChatMessage($client, $data)
    {
        $clientHash = spl_object_hash($client);
        if (!isset($this->clientData[$clientHash]['user_id'])) {
            return;
        }
        
        $userId = $this->clientData[$clientHash]['user_id'];

        if (!isset($data['chat_id']) || !isset($data['message'])) {
            return;
        }

        $chat = Chat::find($data['chat_id']);
        if (!$chat) {
            return;
        }

        // Verifica che l'utente sia partecipante della chat
        $participation = $chat->participants()->where('user_id', $userId)->first();
        if (!$participation) {
            return;
        }

        // Salva il messaggio nel database
        $chatMessage = ChatMessage::create([
            'chat_id' => $chat->id,
            'user_id' => $userId,
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

    protected function handleTyping($client, $data)
    {
        $clientHash = spl_object_hash($client);
        if (!isset($this->clientData[$clientHash]['user_id']) || !isset($data['chat_id'])) {
            return;
        }

        $userId = $this->clientData[$clientHash]['user_id'];
        $typingData = [
            'type' => 'typing',
            'chat_id' => $data['chat_id'],
            'user_id' => $userId,
            'is_typing' => $data['is_typing'] ?? true
        ];

        $this->broadcastToChat($data['chat_id'], $typingData);
    }

    protected function handleCallRequest($client, $data)
    {
        $clientHash = spl_object_hash($client);
        if (!isset($this->clientData[$clientHash]['user_id']) || !isset($data['target_user_id'])) {
            return;
        }

        $userId = $this->clientData[$clientHash]['user_id'];
        $callData = [
            'type' => 'call_request',
            'from_user_id' => $userId,
            'call_type' => $data['call_type'] ?? 'audio',
            'offer' => $data['offer'] ?? null
        ];

        $this->sendToUser($data['target_user_id'], $callData);
    }

    protected function handleCallResponse($client, $data)
    {
        $clientHash = spl_object_hash($client);
        if (!isset($this->clientData[$clientHash]['user_id']) || !isset($data['from_user_id'])) {
            return;
        }

        $userId = $this->clientData[$clientHash]['user_id'];
        $responseData = [
            'type' => 'call_response',
            'to_user_id' => $userId,
            'accepted' => $data['accepted'] ?? false,
            'answer' => $data['answer'] ?? null
        ];

        $this->sendToUser($data['from_user_id'], $responseData);
    }

    protected function handleWebRTCSignal($client, $data)
    {
        $clientHash = spl_object_hash($client);
        if (!isset($this->clientData[$clientHash]['user_id']) || !isset($data['target_user_id'])) {
            return;
        }

        $userId = $this->clientData[$clientHash]['user_id'];
        $signalData = [
            'type' => 'webrtc_signal',
            'from_user_id' => $userId,
            'signal' => $data['signal'],
            'signal_type' => $data['signal_type'] ?? 'ice_candidate'
        ];

        $this->sendToUser($data['target_user_id'], $signalData);
    }

    protected function handleJoinChat($client, $data)
    {
        $clientHash = spl_object_hash($client);
        if (!isset($this->clientData[$clientHash]['user_id']) || !isset($data['chat_id'])) {
            return;
        }

        $userId = $this->clientData[$clientHash]['user_id'];
        $chatId = $data['chat_id'];
        if (!isset($this->chatRooms[$chatId])) {
            $this->chatRooms[$chatId] = [];
        }

        $this->chatRooms[$chatId][$userId] = $client;

        $this->send($client, [
            'type' => 'chat_joined',
            'chat_id' => $chatId
        ]);
    }

    protected function handleLeaveChat($client, $data)
    {
        $clientHash = spl_object_hash($client);
        if (!isset($this->clientData[$clientHash]['user_id']) || !isset($data['chat_id'])) {
            return;
        }

        $userId = $this->clientData[$clientHash]['user_id'];
        $chatId = $data['chat_id'];
        if (isset($this->chatRooms[$chatId][$userId])) {
            unset($this->chatRooms[$chatId][$userId]);
        }
    }

    protected function broadcastToChat($chatId, $data)
    {
        if (!isset($this->chatRooms[$chatId])) {
            return;
        }

        foreach ($this->chatRooms[$chatId] as $connection) {
            $this->send($connection, $data);
        }
    }

    protected function sendToUser($userId, $data)
    {
        if (!isset($this->userConnections[$userId])) {
            return;
        }

        $this->send($this->userConnections[$userId], $data);
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
            $this->send($client, $statusData);
        }
    }

    protected function removeClient($client)
    {
        $clientHash = spl_object_hash($client);
        
        // Rimuovi dai client connessi
        $key = array_search($client, $this->clients);
        if ($key !== false) {
            unset($this->clients[$key]);
        }
        
        // Rimuovi dalle connessioni utente
        if (isset($this->clientData[$clientHash]['user_id'])) {
            $userId = $this->clientData[$clientHash]['user_id'];
            unset($this->userConnections[$userId]);
            
            // Imposta l'utente come offline
            $user = User::find($userId);
            if ($user) {
                $user->setOffline();
                $this->broadcastUserStatus($userId, 'offline');
            }
        }
        
        // Rimuovi i dati del client
        unset($this->clientData[$clientHash]);
        
        // Rimuovi dalle chat rooms
        foreach ($this->chatRooms as $chatId => $users) {
            foreach ($users as $uid => $connection) {
                if ($connection === $client) {
                    unset($this->chatRooms[$chatId][$uid]);
                }
            }
        }
        
        try {
            socket_close($client);
        } catch (\Exception $e) {
            // Socket già chiuso, ignora l'errore
        }
    }
} 