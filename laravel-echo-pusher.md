# Laravel Echo + Pusher - Soluzione Professionale

## **Vantaggi Laravel Echo + Pusher:**
- ✅ **Gestito da terzi** (Pusher)
- ✅ **Scalabile** automaticamente
- ✅ **Supporto completo** per WebRTC
- ✅ **Dashboard** per monitoraggio
- ✅ **Fallback automatico**
- ✅ **SSL/TLS** incluso

## **Svantaggi:**
- ❌ **Costo** (Pusher è a pagamento)
- ❌ **Dipendenze esterne**
- ❌ **Configurazione** più complessa

## **1. Installazione Laravel Echo**

### **A. Installare pacchetti:**
```bash
composer require pusher/pusher-php-server
npm install --save laravel-echo pusher-js
```

### **B. Configurazione Broadcasting:**
```php
// config/broadcasting.php
'default' => env('BROADCAST_DRIVER', 'pusher'),

'connections' => [
    'pusher' => [
        'driver' => 'pusher',
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'app_id' => env('PUSHER_APP_ID'),
        'options' => [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true,
            'host' => env('PUSHER_HOST') ?: 'api-'.env('PUSHER_APP_CLUSTER', 'mt1').'.pusherapp.com',
            'port' => env('PUSHER_PORT', 443),
            'scheme' => env('PUSHER_SCHEME', 'https')
        ],
    ],
],
```

### **C. Environment Variables:**
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=mt1
```

## **2. Eventi Broadcasting**

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

## **3. Client JavaScript**

### **A. Configurazione Laravel Echo:**
```javascript
// resources/js/bootstrap.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true,
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }
});
```

### **B. Ascolto eventi:**
```javascript
// public/assets/js/echo-client.js
class EchoClient {
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
        // Mostra "sta scrivendo..."
        showTypingIndicator(data.user_id);
    }
    
    disconnect() {
        Object.keys(this.channels).forEach(chatId => {
            this.leaveChat(chatId);
        });
        this.isConnected = false;
    }
}

// Inizializza Echo
const echoClient = new EchoClient();
echoClient.connect();
```

## **4. WebRTC con Pusher**

### **A. Signaling per WebRTC:**
```javascript
// public/assets/js/webrtc-pusher.js
class WebRTCPusher {
    constructor() {
        this.peerConnection = null;
        this.localStream = null;
        this.remoteStream = null;
        this.signalingChannel = null;
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
            
            // Invia offer via Pusher
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

## **5. Configurazione Forge**

### **A. Environment Variables:**
```env
# Nel server Forge
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=mt1
```

### **B. Build Assets:**
```bash
# Nel server Forge
npm install
npm run build
```

### **C. Queue Worker (opzionale):**
```bash
# Per eventi in coda
php artisan queue:work
```

## **6. Vantaggi per Produzione**

### **A. Scalabilità:**
- ✅ Gestito da Pusher
- ✅ Load balancing automatico
- ✅ CDN globale

### **B. Affidabilità:**
- ✅ 99.9% uptime garantito
- ✅ Fallback automatico
- ✅ Monitoraggio 24/7

### **C. Funzionalità:**
- ✅ WebRTC completo
- ✅ Presence channels
- ✅ Private channels
- ✅ Whisper events

**Laravel Echo + Pusher è la soluzione più professionale!** 🚀 