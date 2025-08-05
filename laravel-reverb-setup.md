# Laravel Reverb - Soluzione Ufficiale Laravel

## **Perché Laravel Reverb?**

Laravel Reverb è la **nuova soluzione ufficiale** di Laravel per il broadcasting in tempo reale, rilasciata con Laravel 11. È **gratuita**, **open source** e **integrata nativamente** con Laravel.

### **Vantaggi:**
- ✅ **Gratuito** - Nessun costo mensile
- ✅ **Open Source** - Controllo completo
- ✅ **Integrato Laravel** - Supporto nativo
- ✅ **WebSocket nativo** - Performance ottimali
- ✅ **Supporto WebRTC** - Chiamate audio/video
- ✅ **Scalabile** - Migliaia di connessioni
- ✅ **SSL/TLS** - Sicuro per produzione

## **1. Installazione Laravel Reverb**

### **A. Installare Reverb:**
```bash
composer require laravel/reverb
```

### **B. Pubblicare configurazione:**
```bash
php artisan reverb:install
```

### **C. Eseguire migrazioni:**
```bash
php artisan migrate
```

## **2. Configurazione**

### **A. Environment Variables:**
```env
BROADCAST_DRIVER=reverb
REVERB_APP_ID=slamin
REVERB_APP_KEY=your-secret-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=0.0.0.0
REVERB_PORT=8080
REVERB_SCHEME=http
```

### **B. Configurazione Broadcasting:**
```php
// config/broadcasting.php
'default' => env('BROADCAST_DRIVER', 'reverb'),

'connections' => [
    'reverb' => [
        'driver' => 'reverb',
        'app_id' => env('REVERB_APP_ID'),
        'app_key' => env('REVERB_APP_KEY'),
        'app_secret' => env('REVERB_APP_SECRET'),
        'host' => env('REVERB_HOST', '127.0.0.1'),
        'port' => env('REVERB_PORT', 8080),
        'scheme' => env('REVERB_SCHEME', 'http'),
        'options' => [
            'cluster' => env('REVERB_CLUSTER'),
            'encrypted' => env('REVERB_ENCRYPTED', false),
        ],
    ],
],
```

## **3. Eventi Broadcasting**

### **A. Event per nuovi messaggi:**
```php
// app/Events/NewChatMessage.php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message->chat_id);
    }

    public function broadcastAs()
    {
        return 'new-message';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'user_id' => $this->message->user_id,
            'message' => $this->message->message,
            'created_at' => $this->message->created_at,
            'user' => $this->message->user
        ];
    }
}
```

### **B. Event per stato utente:**
```php
// app/Events/UserStatusChanged.php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $status;

    public function __construct($user, $status)
    {
        $this->user = $user;
        $this->status = $status;
    }

    public function broadcastOn()
    {
        return new Channel('user-status');
    }

    public function broadcastAs()
    {
        return 'status-changed';
    }
}
```

## **4. Client JavaScript**

### **A. Installare Laravel Echo:**
```bash
npm install --save laravel-echo
```

### **B. Configurazione Echo:**
```javascript
// resources/js/bootstrap.js
import Echo from 'laravel-echo';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: process.env.MIX_REVERB_APP_KEY,
    wsHost: process.env.MIX_REVERB_HOST || window.location.hostname,
    wsPort: process.env.MIX_REVERB_PORT || 8080,
    wssPort: process.env.MIX_REVERB_PORT || 8080,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }
});
```

### **C. Client per Chat:**
```javascript
// public/assets/js/reverb-client.js
class ReverbClient {
    constructor() {
        this.isConnected = false;
        this.channels = {};
    }
    
    connect() {
        if (this.isConnected) return;
        
        // Ascolta stato utenti
        window.Echo.channel('user-status')
            .listen('.status-changed', (e) => {
                this.handleUserStatus(e.user, e.status);
            });
        
        this.isConnected = true;
        console.log('Reverb connesso');
    }
    
    joinChat(chatId) {
        if (this.channels[chatId]) {
            this.channels[chatId].unsubscribe();
        }
        
        this.channels[chatId] = window.Echo.private(`chat.${chatId}`)
            .listen('.new-message', (e) => {
                this.handleNewMessage(e);
            })
            .listen('.typing', (e) => {
                this.handleTyping(e);
            });
    }
    
    leaveChat(chatId) {
        if (this.channels[chatId]) {
            this.channels[chatId].unsubscribe();
            delete this.channels[chatId];
        }
    }
    
    handleNewMessage(data) {
        if (data.chat_id == currentChatId) {
            addMessageToChat(data);
        }
        updateUnreadMessagesBadge();
    }
    
    handleUserStatus(user, status) {
        updateUserStatusIndicator(user.id, status);
    }
    
    handleTyping(data) {
        showTypingIndicator(data.user_id);
    }
    
    disconnect() {
        Object.keys(this.channels).forEach(chatId => {
            this.leaveChat(chatId);
        });
        this.isConnected = false;
    }
}

// Inizializza Reverb
const reverbClient = new ReverbClient();
reverbClient.connect();
```

## **5. WebRTC con Reverb**

### **A. Signaling per WebRTC:**
```javascript
// public/assets/js/webrtc-reverb.js
class WebRTCReverb {
    constructor() {
        this.peerConnection = null;
        this.localStream = null;
        this.remoteStream = null;
    }
    
    async startCall(targetUserId, callType = 'audio') {
        try {
            // Ottieni stream locale
            this.localStream = await navigator.mediaDevices.getUserMedia({
                audio: callType === 'audio' || callType === 'video',
                video: callType === 'video'
            });
            
            // Crea peer connection
            this.peerConnection = new RTCPeerConnection({
                iceServers: [
                    { urls: 'stun:stun.l.google.com:19302' },
                    { urls: 'stun:stun1.l.google.com:19302' }
                ]
            });
            
            // Aggiungi stream locale
            this.localStream.getTracks().forEach(track => {
                this.peerConnection.addTrack(track, this.localStream);
            });
            
            // Gestisci ICE candidates
            this.peerConnection.onicecandidate = (event) => {
                if (event.candidate) {
                    this.sendSignal(targetUserId, {
                        type: 'ice_candidate',
                        candidate: event.candidate
                    });
                }
            };
            
            // Gestisci stream remoto
            this.peerConnection.ontrack = (event) => {
                this.remoteStream = event.streams[0];
                this.onRemoteStreamReceived(this.remoteStream);
            };
            
            // Crea offer
            const offer = await this.peerConnection.createOffer();
            await this.peerConnection.setLocalDescription(offer);
            
            // Invia offer via Reverb
            this.sendSignal(targetUserId, {
                type: 'offer',
                offer: offer
            });
            
            return true;
            
        } catch (error) {
            console.error('Errore avvio chiamata:', error);
            return false;
        }
    }
    
    sendSignal(targetUserId, signal) {
        window.Echo.private(`webrtc.${targetUserId}`)
            .whisper('signal', {
                from_user_id: currentUserId,
                signal: signal
            });
    }
    
    listenForSignals() {
        window.Echo.private(`webrtc.${currentUserId}`)
            .listenForWhisper('signal', (e) => {
                this.handleSignal(e.signal);
            });
    }
    
    async handleSignal(signal) {
        if (!this.peerConnection) return;
        
        try {
            if (signal.type === 'ice_candidate') {
                await this.peerConnection.addIceCandidate(signal.candidate);
            } else if (signal.type === 'offer') {
                await this.peerConnection.setRemoteDescription(signal.offer);
                const answer = await this.peerConnection.createAnswer();
                await this.peerConnection.setLocalDescription(answer);
                this.sendSignal(signal.from_user_id, {
                    type: 'answer',
                    answer: answer
                });
            } else if (signal.type === 'answer') {
                await this.peerConnection.setRemoteDescription(signal.answer);
            }
        } catch (error) {
            console.error('Errore gestione segnale:', error);
        }
    }
}
```

## **6. Configurazione Forge**

### **A. Avviare Reverb Server:**
```bash
# Nel server Forge
php artisan reverb:start
```

### **B. Process Manager (Supervisor):**
1. **Forge Dashboard** > **Server** > **Processes**
2. **Add Process:**
   - **Name:** reverb
   - **Command:** `php /home/forge/slamin.it/artisan reverb:start`
   - **User:** forge
   - **Directory:** `/home/forge/slamin.it`

### **C. Environment Variables:**
```env
# Nel server Forge
BROADCAST_DRIVER=reverb
REVERB_APP_ID=slamin
REVERB_APP_KEY=your-secret-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=0.0.0.0
REVERB_PORT=8080
REVERB_SCHEME=https
```

### **D. Nginx Configuration:**
```nginx
# Nel sito Forge
location /app/ {
    proxy_pass http://127.0.0.1:8080;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
    proxy_cache_bypass $http_upgrade;
}
```

## **7. Vantaggi per Produzione**

### **A. Gratuito:**
- ✅ Nessun costo mensile
- ✅ Nessun limite di connessioni
- ✅ Controllo completo

### **B. Integrato:**
- ✅ Supporto nativo Laravel
- ✅ Facile da configurare
- ✅ Documentazione ufficiale

### **C. Scalabile:**
- ✅ Gestione efficiente memoria
- ✅ Supporto clustering
- ✅ Performance ottimali

## **8. Confronto con Alternative**

| Feature | Reverb | Pusher | WebSocket Custom |
|---------|--------|--------|------------------|
| **Costo** | Gratuito | A pagamento | Gratuito |
| **Setup** | Facile | Facile | Complesso |
| **Manutenzione** | Bassa | Zero | Alta |
| **Scalabilità** | Alta | Molto alta | Media |
| **WebRTC** | ✅ | ✅ | ✅ |
| **SSL/TLS** | ✅ | ✅ | ✅ |

**Laravel Reverb è la scelta migliore per il tuo progetto!** 🚀 