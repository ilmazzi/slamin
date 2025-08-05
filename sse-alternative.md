# Server-Sent Events (SSE) - Alternativa ai WebSocket

## **Vantaggi SSE:**
- ✅ **Più semplice** da configurare
- ✅ **Nessuna porta aggiuntiva** necessaria
- ✅ **Funziona con HTTPS** senza problemi
- ✅ **Supporto nativo** in tutti i browser
- ✅ **Fallback automatico** a polling

## **Svantaggi SSE:**
- ❌ **Unidirezionale** (solo server → client)
- ❌ **Non supporta chiamate audio/video** (solo notifiche)
- ❌ **Limitato a 6 connessioni** per browser

## **1. Implementazione SSE**

### **A. Controller SSE:**
```php
// app/Http/Controllers/SSEController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\ChatMessage;

class SSEController extends Controller
{
    public function stream(Request $request)
    {
        $userId = auth()->id();
        
        return response()->stream(function() use ($userId) {
            // Headers per SSE
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('Access-Control-Allow-Origin: *');
            
            // Mantieni connessione aperta
            while (true) {
                // Controlla nuovi messaggi
                $newMessages = ChatMessage::where('chat_id', function($query) use ($userId) {
                    $query->select('chat_id')
                          ->from('chat_participants')
                          ->where('user_id', $userId);
                })->where('created_at', '>', now()->subSeconds(5))->get();
                
                if ($newMessages->count() > 0) {
                    echo "data: " . json_encode([
                        'type' => 'new_messages',
                        'messages' => $newMessages
                    ]) . "\n\n";
                }
                
                // Controlla stato utenti
                $onlineUsers = User::where('is_online', true)
                                  ->where('last_seen_at', '>', now()->subMinutes(5))
                                  ->get(['id', 'name', 'online_status']);
                
                echo "data: " . json_encode([
                    'type' => 'user_status',
                    'users' => $onlineUsers
                ]) . "\n\n";
                
                // Flush output
                ob_flush();
                flush();
                
                // Pausa
                sleep(2);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }
}
```

### **B. Route SSE:**
```php
// routes/web.php
Route::get('/sse/stream', [App\Http\Controllers\SSEController::class, 'stream'])
     ->middleware('auth')
     ->name('sse.stream');
```

### **C. Client JavaScript:**
```javascript
// public/assets/js/sse-client.js
class SSEClient {
    constructor() {
        this.eventSource = null;
        this.isConnected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
    }
    
    connect() {
        if (this.isConnected) return;
        
        try {
            this.eventSource = new EventSource('/sse/stream');
            
            this.eventSource.onopen = () => {
                console.log('SSE connesso');
                this.isConnected = true;
                this.reconnectAttempts = 0;
            };
            
            this.eventSource.onmessage = (event) => {
                const data = JSON.parse(event.data);
                this.handleMessage(data);
            };
            
            this.eventSource.onerror = (error) => {
                console.error('Errore SSE:', error);
                this.isConnected = false;
                this.reconnect();
            };
            
        } catch (error) {
            console.error('Errore connessione SSE:', error);
            this.reconnect();
        }
    }
    
    handleMessage(data) {
        switch (data.type) {
            case 'new_messages':
                this.handleNewMessages(data.messages);
                break;
            case 'user_status':
                this.handleUserStatus(data.users);
                break;
        }
    }
    
    handleNewMessages(messages) {
        // Aggiorna chat con nuovi messaggi
        messages.forEach(message => {
            if (message.chat_id == currentChatId) {
                addMessageToChat(message);
            }
        });
        
        // Aggiorna badge messaggi non letti
        updateUnreadMessagesBadge();
    }
    
    handleUserStatus(users) {
        // Aggiorna indicatori stato online
        users.forEach(user => {
            updateUserStatusIndicator(user.id, user.online_status);
        });
    }
    
    reconnect() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            setTimeout(() => {
                this.connect();
            }, 2000 * this.reconnectAttempts);
        }
    }
    
    disconnect() {
        if (this.eventSource) {
            this.eventSource.close();
            this.eventSource = null;
        }
        this.isConnected = false;
    }
}

// Inizializza SSE
const sseClient = new SSEClient();
sseClient.connect();
```

## **2. Polling come Fallback**

### **A. Se SSE non funziona:**
```javascript
// public/assets/js/polling-client.js
class PollingClient {
    constructor() {
        this.interval = null;
        this.isActive = false;
    }
    
    start() {
        if (this.isActive) return;
        
        this.isActive = true;
        this.interval = setInterval(() => {
            this.checkForUpdates();
        }, 5000); // Poll ogni 5 secondi
    }
    
    async checkForUpdates() {
        try {
            const response = await fetch('/api/chat/updates', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.new_messages) {
                this.handleNewMessages(data.new_messages);
            }
            
            if (data.user_status) {
                this.handleUserStatus(data.user_status);
            }
            
        } catch (error) {
            console.error('Errore polling:', error);
        }
    }
    
    stop() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
        this.isActive = false;
    }
}
```

## **3. API per Polling**

### **A. Controller API:**
```php
// app/Http/Controllers/Api/ChatController.php
public function getUpdates(Request $request)
{
    $userId = auth()->id();
    $lastCheck = $request->get('last_check', now()->subMinutes(5));
    
    // Nuovi messaggi
    $newMessages = ChatMessage::where('chat_id', function($query) use ($userId) {
        $query->select('chat_id')
              ->from('chat_participants')
              ->where('user_id', $userId);
    })->where('created_at', '>', $lastCheck)->get();
    
    // Stato utenti
    $userStatus = User::where('is_online', true)
                      ->where('last_seen_at', '>', now()->subMinutes(5))
                      ->get(['id', 'name', 'online_status']);
    
    return response()->json([
        'new_messages' => $newMessages,
        'user_status' => $userStatus,
        'timestamp' => now()
    ]);
}
```

## **4. Configurazione Forge (SSE)**

### **A. Nginx per SSE:**
```nginx
# Nel sito Forge
location /sse/ {
    proxy_pass http://127.0.0.1:80;
    proxy_http_version 1.1;
    proxy_set_header Connection "";
    proxy_buffering off;
    proxy_cache off;
    proxy_read_timeout 86400s;
    proxy_send_timeout 86400s;
}
```

### **B. PHP-FPM:**
```ini
; /etc/php/8.1/fpm/php.ini
max_execution_time = 0
max_input_time = -1
memory_limit = 512M
```

## **5. Vantaggi per Produzione**

### **A. Semplicità:**
- ✅ Nessuna porta aggiuntiva
- ✅ Nessun processo separato
- ✅ Configurazione minima

### **B. Affidabilità:**
- ✅ Fallback automatico
- ✅ Reconnessione automatica
- ✅ Gestione errori robusta

### **C. Scalabilità:**
- ✅ Funziona con load balancer
- ✅ Nessun stato server-side
- ✅ Stateless architecture

**SSE è perfetto per notifiche e aggiornamenti in tempo reale!** 📡 