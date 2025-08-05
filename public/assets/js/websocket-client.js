/**
 * WebSocket Client per Chat in Tempo Reale
 * Gestisce connessioni WebSocket, messaggi, chiamate audio/video
 */

class WebSocketClient {
    constructor() {
        this.ws = null;
        this.isConnected = false;
        this.userId = null;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 10;
        this.reconnectDelay = 2000;
        this.heartbeatInterval = null;
        this.lastHeartbeat = Date.now();
        
        // WebRTC
        this.peerConnection = null;
        this.localStream = null;
        this.remoteStream = null;
        this.currentCall = null;
        
        // Configurazione
        const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
        const host = window.location.hostname === 'slamin.local' ? 'slamin.local' : '127.0.0.1';
        
        // Usa il proxy WSS se siamo su HTTPS, altrimenti WebSocket diretto
        if (window.location.protocol === 'https:') {
            this.wsUrl = `${protocol}//${host}/ws`;
        } else {
            this.wsUrl = `${protocol}//${host}:8080`;
        }
        this.iceServers = [
            { urls: 'stun:stun.l.google.com:19302' },
            { urls: 'stun:stun1.l.google.com:19302' }
        ];
        
        // Event listeners
        this.onMessageCallback = null;
        this.onUserStatusCallback = null;
        this.onCallRequestCallback = null;
        this.onCallResponseCallback = null;
        this.onWebRTCSignalCallback = null;
    }

    /**
     * Connette al WebSocket server
     */
    connect(userId, token) {
        if (this.isConnected) {
            console.log('Già connesso al WebSocket server');
            return;
        }

        try {
            this.ws = new WebSocket(this.wsUrl);
            this.userId = userId;

            this.ws.onopen = () => {
                console.log('Connesso al WebSocket server');
                this.isConnected = true;
                this.reconnectAttempts = 0;
                this.lastHeartbeat = Date.now();
                
                // Avvia heartbeat
                this.startHeartbeat();
                
                // Autenticazione
                this.send({
                    type: 'auth',
                    user_id: userId,
                    token: token
                });
            };

            this.ws.onmessage = (event) => {
                this.handleMessage(JSON.parse(event.data));
            };

            this.ws.onclose = () => {
                console.log('Disconnesso dal WebSocket server');
                this.isConnected = false;
                this.stopHeartbeat();
                this.handleReconnect();
            };

            this.ws.onerror = (error) => {
                console.error('Errore WebSocket:', error);
                this.isConnected = false;
            };

        } catch (error) {
            console.error('Errore nella connessione WebSocket:', error);
            this.handleReconnect();
        }
    }

    /**
     * Gestisce la riconnessione automatica
     */
    handleReconnect() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            console.log(`Tentativo di riconnessione ${this.reconnectAttempts}/${this.maxReconnectAttempts}`);
            
            setTimeout(() => {
                this.connect(this.userId, this.getAuthToken());
            }, this.reconnectDelay * this.reconnectAttempts);
        } else {
            console.error('Impossibile riconnettersi al WebSocket server');
        }
    }

    /**
     * Avvia il heartbeat per mantenere la connessione attiva
     */
    startHeartbeat() {
        this.stopHeartbeat(); // Ferma heartbeat precedente se presente
        
        this.heartbeatInterval = setInterval(() => {
            if (this.isConnected && this.ws && this.ws.readyState === WebSocket.OPEN) {
                this.send({
                    type: 'heartbeat',
                    timestamp: Date.now()
                });
                this.lastHeartbeat = Date.now();
            }
        }, 30000); // Heartbeat ogni 30 secondi
    }

    /**
     * Ferma il heartbeat
     */
    stopHeartbeat() {
        if (this.heartbeatInterval) {
            clearInterval(this.heartbeatInterval);
            this.heartbeatInterval = null;
        }
    }

    /**
     * Invia messaggio al WebSocket server
     */
    send(data) {
        if (this.ws && this.ws.readyState === WebSocket.OPEN) {
            this.ws.send(JSON.stringify(data));
        } else {
            console.error('WebSocket non connesso');
        }
    }

    /**
     * Gestisce i messaggi ricevuti
     */
    handleMessage(data) {
        console.log('Messaggio ricevuto:', data);

        switch (data.type) {
            case 'auth_success':
                console.log('Autenticazione WebSocket riuscita');
                break;
                
            case 'auth_error':
                console.error('Errore autenticazione WebSocket:', data.message);
                break;
                
            case 'heartbeat_ack':
                console.log('Heartbeat confermato dal server');
                this.lastHeartbeat = Date.now();
                break;
                
            case 'message':
                this.handleChatMessage(data);
                break;
                
            case 'typing':
                this.handleTyping(data);
                break;
                
            case 'user_status':
                this.handleUserStatus(data);
                break;
                
            case 'call_request':
                this.handleCallRequest(data);
                break;
                
            case 'call_response':
                this.handleCallResponse(data);
                break;
                
            case 'webrtc_signal':
                this.handleWebRTCSignal(data);
                break;
                
            case 'chat_joined':
                console.log('Entrato nella chat:', data.chat_id);
                break;
        }
    }

    /**
     * Gestisce messaggi della chat
     */
    handleChatMessage(data) {
        if (this.onMessageCallback) {
            this.onMessageCallback(data);
        }
    }

    /**
     * Gestisce indicatori di digitazione
     */
    handleTyping(data) {
        // Implementa la logica per mostrare "sta scrivendo..."
        console.log(`Utente ${data.user_id} sta scrivendo in chat ${data.chat_id}`);
    }

    /**
     * Gestisce cambi di stato utente
     */
    handleUserStatus(data) {
        if (this.onUserStatusCallback) {
            this.onUserStatusCallback(data);
        }
    }

    /**
     * Gestisce richieste di chiamata
     */
    handleCallRequest(data) {
        if (this.onCallRequestCallback) {
            this.onCallRequestCallback(data);
        }
    }

    /**
     * Gestisce risposte alle chiamate
     */
    handleCallResponse(data) {
        if (this.onCallResponseCallback) {
            this.onCallResponseCallback(data);
        }
    }

    /**
     * Gestisce segnali WebRTC
     */
    handleWebRTCSignal(data) {
        if (this.onWebRTCSignalCallback) {
            this.onWebRTCSignalCallback(data);
        }
    }

    /**
     * Entra in una chat
     */
    joinChat(chatId) {
        this.send({
            type: 'join_chat',
            chat_id: chatId
        });
    }

    /**
     * Esce da una chat
     */
    leaveChat(chatId) {
        this.send({
            type: 'leave_chat',
            chat_id: chatId
        });
    }

    /**
     * Invia messaggio
     */
    sendMessage(chatId, message, messageType = 'text') {
        this.send({
            type: 'message',
            chat_id: chatId,
            message: message,
            message_type: messageType
        });
    }

    /**
     * Invia indicatore di digitazione
     */
    sendTyping(chatId, isTyping = true) {
        this.send({
            type: 'typing',
            chat_id: chatId,
            is_typing: isTyping
        });
    }

    /**
     * Inizia una chiamata
     */
    async startCall(targetUserId, callType = 'audio') {
        try {
            // Verifica che WebRTC sia supportato
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error('WebRTC non è supportato in questo browser');
            }

            // Test preliminare del microfono
            console.log('Test preliminare del microfono...');
            try {
                const testStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                testStream.getTracks().forEach(track => track.stop());
                console.log('Test microfono completato con successo');
            } catch (testError) {
                console.error('Test microfono fallito:', testError);
                throw new Error(`Microfono non disponibile: ${testError.message}`);
            }

            // Verifica che il sito sia su HTTPS (richiesto per WebRTC)
            if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1' && !location.hostname.includes('local')) {
                console.warn('WebRTC richiede HTTPS in produzione');
            }

            // Ottieni stream locale con gestione errori migliorata
            try {
                this.localStream = await navigator.mediaDevices.getUserMedia({
                    audio: callType === 'audio' || callType === 'video',
                    video: callType === 'video'
                });
                console.log('Stream locale ottenuto con successo:', this.localStream.getTracks());
            } catch (mediaError) {
                console.error('Errore nell\'accesso ai dispositivi media:', mediaError);
                throw new Error(`Could not start audio source: ${mediaError.message}`);
            }

            // Crea peer connection
            this.peerConnection = new RTCPeerConnection({
                iceServers: this.iceServers
            });

            // Gestisci eventi di connessione
            this.peerConnection.onconnectionstatechange = () => {
                console.log('Stato connessione WebRTC (startCall):', this.peerConnection.connectionState);
                if (this.peerConnection.connectionState === 'connected') {
                    console.log('WebRTC connesso!');
                    if (typeof window.updateCallInterface === 'function') {
                        window.updateCallInterface(this.remoteStream);
                    }
                }
            };

            this.peerConnection.oniceconnectionstatechange = () => {
                console.log('Stato ICE (startCall):', this.peerConnection.iceConnectionState);
            };

            // Aggiungi stream locale
            this.localStream.getTracks().forEach(track => {
                this.peerConnection.addTrack(track, this.localStream);
            });

            // Gestisci ICE candidates
            this.peerConnection.onicecandidate = (event) => {
                if (event.candidate) {
                    this.send({
                        type: 'webrtc_signal',
                        target_user_id: targetUserId,
                        signal: event.candidate,
                        signal_type: 'ice_candidate'
                    });
                }
            };

            // Gestisci stream remoto
            this.peerConnection.ontrack = (event) => {
                console.log('Stream remoto ricevuto via ontrack (startCall):', event.streams);
                this.remoteStream = event.streams[0];
                this.onRemoteStreamReceived(this.remoteStream);
            };

            // Crea offer
            const offer = await this.peerConnection.createOffer();
            await this.peerConnection.setLocalDescription(offer);

            // Salva informazioni chiamata
            this.currentCall = {
                targetUserId: targetUserId,
                callType: callType,
                isInitiator: true
            };

            // Invia richiesta chiamata
            this.send({
                type: 'call_request',
                target_user_id: targetUserId,
                call_type: callType,
                offer: offer
            });

            return true;

        } catch (error) {
            console.error('Errore nell\'avvio della chiamata:', error);
            
            // Gestisci errori specifici
            if (error.name === 'NotAllowedError') {
                alert('Permesso negato per microfono/camera. Verifica le impostazioni del browser.');
            } else if (error.name === 'NotFoundError') {
                alert('Microfono/camera non trovato. Verifica che sia collegato.');
            } else if (error.name === 'NotSupportedError') {
                alert('WebRTC non è supportato in questo browser.');
            } else {
                alert('Errore nell\'avvio della chiamata: ' + error.message);
            }
            
            return false;
        }
    }

    /**
     * Risponde a una chiamata
     */
    async answerCall(fromUserId, accepted, offer = null) {
        if (!accepted) {
            this.send({
                type: 'call_response',
                from_user_id: fromUserId,
                accepted: false
            });
            return;
        }

        try {
            // Verifica che WebRTC sia supportato
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error('WebRTC non è supportato in questo browser');
            }

            // Ottieni stream locale
            this.localStream = await navigator.mediaDevices.getUserMedia({
                audio: true,
                video: true
            });

            // Crea peer connection
            this.peerConnection = new RTCPeerConnection({
                iceServers: this.iceServers
            });

            // Gestisci eventi di connessione
            this.peerConnection.onconnectionstatechange = () => {
                console.log('Stato connessione WebRTC (answerCall):', this.peerConnection.connectionState);
                if (this.peerConnection.connectionState === 'connected') {
                    console.log('WebRTC connesso!');
                    if (typeof window.updateCallInterface === 'function') {
                        window.updateCallInterface(this.remoteStream);
                    }
                }
            };

            this.peerConnection.oniceconnectionstatechange = () => {
                console.log('Stato ICE (answerCall):', this.peerConnection.iceConnectionState);
            };

            // Aggiungi stream locale
            this.localStream.getTracks().forEach(track => {
                this.peerConnection.addTrack(track, this.localStream);
            });

            // Gestisci ICE candidates
            this.peerConnection.onicecandidate = (event) => {
                if (event.candidate) {
                    this.send({
                        type: 'webrtc_signal',
                        target_user_id: fromUserId,
                        signal: event.candidate,
                        signal_type: 'ice_candidate'
                    });
                }
            };

            // Gestisci stream remoto
            this.peerConnection.ontrack = (event) => {
                console.log('Stream remoto ricevuto via ontrack (answerCall):', event.streams);
                this.remoteStream = event.streams[0];
                this.onRemoteStreamReceived(this.remoteStream);
            };

            // Imposta offer remoto
            if (offer) {
                await this.peerConnection.setRemoteDescription(offer);
            }

            // Crea answer
            const answer = await this.peerConnection.createAnswer();
            await this.peerConnection.setLocalDescription(answer);

            // Salva informazioni chiamata
            this.currentCall = {
                targetUserId: fromUserId,
                callType: 'audio', // o video
                isInitiator: false
            };

            // Invia risposta
            this.send({
                type: 'call_response',
                from_user_id: fromUserId,
                accepted: true,
                answer: answer
            });

            return true;

        } catch (error) {
            console.error('Errore nella risposta alla chiamata:', error);
            
            // Gestisci errori specifici
            if (error.name === 'NotAllowedError') {
                alert('Permesso negato per microfono/camera. Verifica le impostazioni del browser.');
            } else if (error.name === 'NotFoundError') {
                alert('Microfono/camera non trovato. Verifica che sia collegato.');
            } else if (error.name === 'NotSupportedError') {
                alert('WebRTC non è supportato in questo browser.');
            } else {
                alert('Errore nella risposta alla chiamata: ' + error.message);
            }
            
            return false;
        }
    }

    /**
     * Gestisce segnali WebRTC
     */
    async handleWebRTCSignal(data) {
        if (!this.peerConnection) {
            console.warn('PeerConnection non disponibile per segnale WebRTC');
            return;
        }

        try {
            console.log('Gestione segnale WebRTC:', data.signal_type);
            
            if (data.signal_type === 'ice_candidate') {
                console.log('Aggiunta ICE candidate:', data.signal);
                await this.peerConnection.addIceCandidate(data.signal);
            } else if (data.signal_type === 'offer') {
                console.log('Impostazione offer remoto');
                await this.peerConnection.setRemoteDescription(data.signal);
            } else if (data.signal_type === 'answer') {
                console.log('Impostazione answer remoto');
                await this.peerConnection.setRemoteDescription(data.signal);
            }
        } catch (error) {
            console.error('Errore nella gestione segnale WebRTC:', error);
        }
    }

    /**
     * Termina la chiamata corrente
     */
    endCall() {
        if (this.peerConnection) {
            this.peerConnection.close();
            this.peerConnection = null;
        }

        if (this.localStream) {
            this.localStream.getTracks().forEach(track => track.stop());
            this.localStream = null;
        }

        this.remoteStream = null;
        this.currentCall = null;

        console.log('Chiamata terminata');
    }

    /**
     * Disconnette dal WebSocket server
     */
    disconnect() {
        if (this.ws) {
            this.ws.close();
            this.ws = null;
        }
        this.isConnected = false;
        this.userId = null;
    }

    /**
     * Ottiene il token di autenticazione
     */
    getAuthToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    /**
     * Verifica se WebRTC è supportato
     */
    isWebRTCSupported() {
        return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
    }

    /**
     * Verifica se il browser supporta le chiamate audio
     */
    isAudioCallSupported() {
        return this.isWebRTCSupported();
    }

    /**
     * Verifica se il browser supporta le videochiamate
     */
    isVideoCallSupported() {
        return this.isWebRTCSupported() && navigator.mediaDevices.getUserMedia({ video: true });
    }

    /**
     * Callback per stream remoto ricevuto
     */
    onRemoteStreamReceived(stream) {
        // Implementa la logica per mostrare il video remoto
        console.log('Stream remoto ricevuto:', stream);
        
        // Aggiorna l'interfaccia della chiamata
        if (typeof window.updateCallInterface === 'function') {
            window.updateCallInterface(stream);
        }
    }

    /**
     * Setter per i callback
     */
    onMessage(callback) {
        this.onMessageCallback = callback;
    }

    onUserStatus(callback) {
        this.onUserStatusCallback = callback;
    }

    onCallRequest(callback) {
        this.onCallRequestCallback = callback;
    }

    onCallResponse(callback) {
        this.onCallResponseCallback = callback;
    }

    onWebRTCSignal(callback) {
        this.onWebRTCSignalCallback = callback;
    }
}

// Esporta per uso globale
window.WebSocketClient = WebSocketClient; 