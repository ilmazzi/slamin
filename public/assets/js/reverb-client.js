/**
 * Laravel Reverb Client
 * Gestisce connessioni Reverb, messaggi, stato utente e chiamate WebRTC
 */

class ReverbClient {
    constructor() {
        this.isConnected = false;
        this.channels = {};
        this.currentCall = null;
        this.peerConnection = null;
        this.localStream = null;
        this.remoteStream = null;
        
        // Configurazione WebRTC
        this.iceServers = [
            { urls: 'stun:stun.l.google.com:19302' },
            { urls: 'stun:stun1.l.google.com:19302' }
        ];
        
        // Callbacks
        this.onMessageCallback = null;
        this.onUserStatusCallback = null;
        this.onCallRequestCallback = null;
        this.onCallResponseCallback = null;
        this.onWebRTCSignalCallback = null;
    }

    /**
     * Inizializza la connessione Reverb
     */
    connect() {
        if (this.isConnected) {
            console.log('Reverb già connesso');
            return;
        }

        try {
            // Verifica che Laravel Echo sia disponibile
            if (typeof window.Echo === 'undefined') {
                console.error('Laravel Echo non è caricato. Assicurati di includere i file compilati.');
                return;
            }

            // Usa la configurazione Echo già impostata in bootstrap.js
            console.log('Echo configurato:', window.Echo);

            // Ascolta stato utenti
            window.Echo.channel('user-status')
                .listen('.status-changed', (e) => {
                    this.handleUserStatus(e.user, e.status);
                });

            // Ascolta canali privati per chiamate
            window.Echo.private(`user.${currentUserId}`)
                .listen('.call-request', (e) => {
                    this.handleCallRequest(e);
                })
                .listen('.call-response', (e) => {
                    this.handleCallResponse(e);
                });

            // Ascolta canali WebRTC
            window.Echo.private(`webrtc.${currentUserId}`)
                .listen('.webrtc-signal', (e) => {
                    this.handleWebRTCSignal(e);
                });

            this.isConnected = true;
            console.log('Reverb connesso con successo');

        } catch (error) {
            console.error('Errore connessione Reverb:', error);
        }
    }

    /**
     * Entra in una chat
     */
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

        console.log(`Entrato nella chat ${chatId}`);
    }

    /**
     * Esce da una chat
     */
    leaveChat(chatId) {
        if (this.channels[chatId]) {
            this.channels[chatId].unsubscribe();
            delete this.channels[chatId];
            console.log(`Uscito dalla chat ${chatId}`);
        }
    }

    /**
     * Invia messaggio
     */
    async sendMessage(chatId, message, messageType = 'text') {
        try {
            const response = await fetch(`/chat/${chatId}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    message: message,
                    message_type: messageType
                })
            });

            const data = await response.json();
            
            if (data.success) {
                console.log('Messaggio inviato:', data.message);
                return data.message;
            } else {
                console.error('Errore invio messaggio:', data.message);
                return null;
            }

        } catch (error) {
            console.error('Errore invio messaggio:', error);
            return null;
        }
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

            // Ottieni stream locale
            this.localStream = await navigator.mediaDevices.getUserMedia({
                audio: callType === 'audio' || callType === 'video',
                video: callType === 'video'
            });

            // Crea peer connection
            this.peerConnection = new RTCPeerConnection({
                iceServers: this.iceServers
            });

            // Aggiungi stream locale
            this.localStream.getTracks().forEach(track => {
                this.peerConnection.addTrack(track, this.localStream);
            });

            // Gestisci ICE candidates
            this.peerConnection.onicecandidate = (event) => {
                if (event.candidate) {
                    this.sendWebRTCSignal(targetUserId, {
                        type: 'ice_candidate',
                        candidate: event.candidate
                    });
                }
            };

            // Gestisci stream remoto
            this.peerConnection.ontrack = (event) => {
                console.log('Stream remoto ricevuto:', event.streams);
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

            // Invia richiesta chiamata via API
            const response = await fetch('/calls/start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    target_user_id: targetUserId,
                    call_type: callType,
                    offer: offer
                })
            });

            const data = await response.json();
            
            if (data.success) {
                console.log('Chiamata avviata');
                return true;
            } else {
                console.error('Errore avvio chiamata:', data.message);
                return false;
            }

        } catch (error) {
            console.error('Errore nell\'avvio della chiamata:', error);
            return false;
        }
    }

    /**
     * Risponde a una chiamata
     */
    async answerCall(fromUserId, accepted, offer = null) {
        if (!accepted) {
            await this.sendCallResponse(fromUserId, false);
            return;
        }

        try {
            // Ottieni stream locale
            this.localStream = await navigator.mediaDevices.getUserMedia({
                audio: true,
                video: true
            });

            // Crea peer connection
            this.peerConnection = new RTCPeerConnection({
                iceServers: this.iceServers
            });

            // Aggiungi stream locale
            this.localStream.getTracks().forEach(track => {
                this.peerConnection.addTrack(track, this.localStream);
            });

            // Gestisci ICE candidates
            this.peerConnection.onicecandidate = (event) => {
                if (event.candidate) {
                    this.sendWebRTCSignal(fromUserId, {
                        type: 'ice_candidate',
                        candidate: event.candidate
                    });
                }
            };

            // Gestisci stream remoto
            this.peerConnection.ontrack = (event) => {
                console.log('Stream remoto ricevuto:', event.streams);
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
                callType: 'audio',
                isInitiator: false
            };

            // Invia risposta
            await this.sendCallResponse(fromUserId, true, answer);

            return true;

        } catch (error) {
            console.error('Errore nella risposta alla chiamata:', error);
            return false;
        }
    }

    /**
     * Termina la chiamata corrente
     */
    async endCall() {
        if (this.currentCall) {
            await fetch('/calls/end', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    target_user_id: this.currentCall.targetUserId
                })
            });
        }

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
     * Invia risposta alla chiamata
     */
    async sendCallResponse(fromUserId, accepted, answer = null) {
        try {
            const response = await fetch('/calls/answer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    from_user_id: fromUserId,
                    accepted: accepted,
                    answer: answer
                })
            });

            const data = await response.json();
            console.log('Risposta chiamata inviata:', data.message);

        } catch (error) {
            console.error('Errore invio risposta chiamata:', error);
        }
    }

    /**
     * Invia segnale WebRTC
     */
    async sendWebRTCSignal(targetUserId, signal) {
        try {
            const response = await fetch('/calls/signal', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    target_user_id: targetUserId,
                    signal: signal,
                    signal_type: signal.type
                })
            });

            const data = await response.json();
            
            if (!data.success) {
                console.error('Errore invio segnale WebRTC:', data.message);
            }

        } catch (error) {
            console.error('Errore invio segnale WebRTC:', error);
        }
    }

    /**
     * Gestisce nuovi messaggi
     */
    handleNewMessage(data) {
        console.log('Nuovo messaggio ricevuto:', data);
        
        if (this.onMessageCallback) {
            this.onMessageCallback(data);
        }
    }

    /**
     * Gestisce cambi di stato utente
     */
    handleUserStatus(user, status) {
        console.log('Cambio stato utente:', user, status);
        
        if (this.onUserStatusCallback) {
            this.onUserStatusCallback(user, status);
        }
    }

    /**
     * Gestisce richieste di chiamata
     */
    handleCallRequest(data) {
        console.log('Richiesta chiamata ricevuta:', data);
        
        if (this.onCallRequestCallback) {
            this.onCallRequestCallback(data);
        }
    }

    /**
     * Gestisce risposte alle chiamate
     */
    handleCallResponse(data) {
        console.log('Risposta chiamata ricevuta:', data);
        
        if (this.onCallResponseCallback) {
            this.onCallResponseCallback(data);
        }
    }

    /**
     * Gestisce segnali WebRTC
     */
    async handleWebRTCSignal(data) {
        console.log('Segnale WebRTC ricevuto:', data);
        
        if (!this.peerConnection) {
            console.warn('PeerConnection non disponibile per segnale WebRTC');
            return;
        }

        try {
            if (data.signal_type === 'ice_candidate') {
                await this.peerConnection.addIceCandidate(data.signal.candidate);
            } else if (data.signal_type === 'offer') {
                await this.peerConnection.setRemoteDescription(data.signal.offer);
                const answer = await this.peerConnection.createAnswer();
                await this.peerConnection.setLocalDescription(answer);
                this.sendWebRTCSignal(data.from_user_id, {
                    type: 'answer',
                    answer: answer
                });
            } else if (data.signal_type === 'answer') {
                await this.peerConnection.setRemoteDescription(data.signal.answer);
            }
        } catch (error) {
            console.error('Errore nella gestione segnale WebRTC:', error);
        }

        if (this.onWebRTCSignalCallback) {
            this.onWebRTCSignalCallback(data);
        }
    }

    /**
     * Gestisce indicatori di digitazione
     */
    handleTyping(data) {
        console.log(`Utente ${data.user_id} sta scrivendo in chat ${data.chat_id}`);
    }

    /**
     * Callback per stream remoto ricevuto
     */
    onRemoteStreamReceived(stream) {
        console.log('Stream remoto ricevuto:', stream);
        
        if (typeof window.updateCallInterface === 'function') {
            window.updateCallInterface(stream);
        }
    }

    /**
     * Disconnette da Reverb
     */
    disconnect() {
        Object.keys(this.channels).forEach(chatId => {
            this.leaveChat(chatId);
        });

        if (this.currentCall) {
            this.endCall();
        }

        this.isConnected = false;
        console.log('Disconnesso da Reverb');
    }

    /**
     * Verifica supporto WebRTC
     */
    isWebRTCSupported() {
        return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
    }

    /**
     * Verifica supporto chiamate audio
     */
    isAudioCallSupported() {
        return this.isWebRTCSupported();
    }

    /**
     * Verifica supporto videochiamate
     */
    isVideoCallSupported() {
        return this.isWebRTCSupported();
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
window.ReverbClient = ReverbClient; 