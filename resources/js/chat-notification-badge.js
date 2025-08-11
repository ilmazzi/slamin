// Chat Notification Badge Manager
// Gestisce l'aggiornamento in tempo reale del badge delle notifiche chat nella sidebar

(function () {
    'use strict';

    class ChatNotificationBadgeManager {
        constructor() {
            this.badgeElement = document.getElementById('chat-notification-badge');
            this.badgeContainer = document.querySelector('[data-chat-badge-container]');
            this.currentCount = 0;
            this.isInitialized = false;

            this.init();
        }

        init() {
            if (!this.badgeElement && !this.badgeContainer) {

                return;
            }

            this.currentCount = this.getCurrentBadgeCount();
            this.setupEchoListener();
            this.setupChatClickHandler();
            this.setupChatContactClickHandlers();
            this.setupGlobalBadgeIntegration();
            this.syncBadgesFromBackground();
            this.isInitialized = true;


        }

        getCurrentBadgeCount() {
            if (this.badgeElement) {
                return parseInt(this.badgeElement.textContent) || 0;
            }
            return 0;
        }

        setupEchoListener() {
            // Non serve più ascoltare le notifiche qui perché lo fa il sistema globale
            // Questo sistema ora si occupa solo dei badge individuali

        }

        setupChatClickHandler() {
            // Gestisce il click sul pulsante chat per nascondere il badge
            if (this.badgeContainer) {
                this.badgeContainer.addEventListener('click', () => {

                    this.hideBadgeOnChatOpen();
                });
            }
        }

        incrementBadge() {
            this.currentCount++;
            this.updateBadgeDisplay();
        }

        decrementBadge() {
            this.currentCount = Math.max(0, this.currentCount - 1);
            this.updateBadgeDisplay();
        }

        updateBadge(count) {
            this.currentCount = Math.max(0, count);
            this.updateBadgeDisplay();
        }

        updateBadgeDisplay() {
            if (this.currentCount > 0) {
                this.showBadge();
            } else {
                this.hideBadge();
            }
        }

        showBadge() {
            if (this.badgeElement) {
                this.badgeElement.textContent = this.currentCount;
                this.badgeElement.style.display = 'inline-block';
            } else if (this.badgeContainer) {
                this.createBadgeElement();
            }
        }

        hideBadge() {
            if (this.badgeElement) {
                this.badgeElement.style.display = 'none';
            }
        }

        hideBadgeOnChatOpen() {
            // Nasconde il badge quando si apre la chat
            // e marka le notifiche chat come lette
            this.currentCount = 0;
            this.updateBadgeDisplay();

            // Marka le notifiche chat come lette via API
            this.markChatNotificationsAsRead();
        }

        async markChatNotificationsAsRead() {
            try {
                const response = await fetch('/chat/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {

                } else {
                    console.warn('[ChatBadge] Failed to mark notifications as read');
                }
            } catch (error) {
                console.error('[ChatBadge] Error marking notifications as read:', error);
            }
        }

                updateBadgeForSpecificRoom(roomId) {
            // Aggiorna il badge quando si entra in una stanza specifica
            // Marka le notifiche di quella stanza come lette
            this.markRoomNotificationsAsRead(roomId);

            // Nasconde anche il badge individuale di quella chat
            this.hideIndividualChatBadge(roomId);
        }

        // Gestisce il click su un contatto chat per nascondere il badge individuale
        setupChatContactClickHandlers() {
            // Ascolta i click sui contatti chat
            document.addEventListener('click', (e) => {
                const chatContact = e.target.closest('[data-chat-room]');
                if (chatContact) {
                    const roomId = chatContact.getAttribute('data-chat-room');
                    if (roomId) {

                        this.hideIndividualChatBadge(roomId);
                    }
                }
            });
        }

        // Si integra con il sistema badge globale per aggiornare i badge individuali
                setupGlobalBadgeIntegration() {


                        // Ascolta gli eventi del sistema globale (NON per i badge individuali)
            document.addEventListener('globalBadgeUpdated', (e) => {



                // NON aggiornare i badge individuali da qui per evitare doppie incrementazioni

            });

            // Ascolta gli eventi di aggiornamento badge individuali
            document.addEventListener('individualBadgeUpdated', (e) => {

                const { roomId, count } = e.detail;

                if (roomId && count !== undefined) {

                    this.updateIndividualChatBadge(roomId, count);
                }
            });

            // Se il sistema globale è già disponibile, registra i listener
            if (window.GlobalChatBadge) {
                this.registerWithGlobalSystem();
            } else {
                // Aspetta che il sistema globale sia disponibile
                const checkGlobal = setInterval(() => {
                    if (window.GlobalChatBadge) {
                        this.registerWithGlobalSystem();
                        clearInterval(checkGlobal);
                    }
                }, 100);
            }
        }

        // Registra questo sistema con quello globale
        registerWithGlobalSystem() {


            // Sostituisci le funzioni globali per includere i badge individuali
            const originalIncrement = window.GlobalChatBadge.incrementBadge;
            const originalUpdate = window.GlobalChatBadge.updateBadge;
            const originalDecrement = window.GlobalChatBadge.decrementBadge;

            window.GlobalChatBadge.incrementBadge = () => {
                originalIncrement();
                // Aggiorna anche i badge individuali se siamo nella pagina chat
                this.updateAllIndividualBadges();
            };

            window.GlobalChatBadge.updateBadge = (count) => {
                originalUpdate(count);
                this.updateAllIndividualBadges();
            };

            window.GlobalChatBadge.decrementBadge = () => {
                originalDecrement();
                this.updateAllIndividualBadges();
            };
        }

        // Aggiorna tutti i badge individuali basandosi sui dati delle notifiche
        updateAllIndividualBadges() {
            // Questa funzione verrà chiamata quando il badge globale si aggiorna
            // Aggiorna i badge individuali basandosi sui dati delle notifiche

        }

        async markRoomNotificationsAsRead(roomId) {
            try {
                const response = await fetch(`/chat/notifications/room/${roomId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {

                    // Aggiorna il conteggio del badge
                    this.refreshBadgeCount();
                } else {
                    console.warn(`[ChatBadge] Failed to mark room notifications as read`);
                }
            } catch (error) {
                console.error('[ChatBadge] Error marking room notifications as read:', error);
            }
        }

        async refreshBadgeCount() {
            try {
                const response = await fetch('/chat/notifications/unread-count');
                if (response.ok) {
                    const data = await response.json();
                    this.updateBadge(data.count || 0);
                }
            } catch (error) {
                console.error('[ChatBadge] Error refreshing badge count:', error);
            }
        }

        // Gestione badge individuali per chat
        showIndividualChatBadge(roomId, count = 1) {

            const chatItem = document.querySelector(`[data-chat-room="${roomId}"]`);

            if (chatItem) {
                let badge = chatItem.querySelector('.chat-individual-badge');

                if (!badge) {
                    badge = this.createIndividualChatBadge();
                    chatItem.appendChild(badge);
                }
                badge.textContent = count;
                badge.style.display = 'inline-block';
            }
        }

        hideIndividualChatBadge(roomId) {
            const chatItem = document.querySelector(`[data-chat-room="${roomId}"]`);
            if (chatItem) {
                const badge = chatItem.querySelector('.chat-individual-badge');
                if (badge) {
                    badge.style.display = 'none';
                }
            }
        }

        createIndividualChatBadge() {
            const badge = document.createElement('span');
            badge.className = 'chat-individual-badge badge bg-danger badge-sm ms-2';
            badge.style.cssText = 'font-size: 10px; padding: 2px 6px; border-radius: 10px;';
            return badge;
        }

        // Aggiorna badge individuale quando arriva un messaggio
        updateIndividualChatBadge(roomId, count) {

            if (count > 0) {

                this.showIndividualChatBadge(roomId, count);
            } else {

                this.hideIndividualChatBadge(roomId);
            }
        }

                // Sincronizza i badge preparati in background
        syncBadgesFromBackground() {


            if (!window.GlobalChatBadge || !window.GlobalChatBadge.getAllIndividualBadgeCounts) {



                return;
            }


            const backgroundCounts = window.GlobalChatBadge.getAllIndividualBadgeCounts();




            // Aggiorna tutti i badge individuali con i conteggi preparati
            Object.entries(backgroundCounts).forEach(([roomId, count]) => {
                if (count > 0) {

                    this.updateIndividualChatBadge(roomId, count);
                }
            });


        }

        createBadgeElement() {
            if (!this.badgeContainer) return;

            // Crea il badge se non esiste
            const badge = document.createElement('span');
            badge.className = 'badge bg-danger badge-notification ms-2';
            badge.id = 'chat-notification-badge';
            badge.textContent = this.currentCount;

            this.badgeContainer.appendChild(badge);
            this.badgeElement = badge;
        }

        // Metodo pubblico per aggiornare il badge da codice esterno
        static updateBadgeCount(count) {
            if (window.chatBadgeManager && window.chatBadgeManager.isInitialized) {
                window.chatBadgeManager.updateBadge(count);
            }
        }

        // Metodo pubblico per incrementare il badge
        static incrementBadge() {
            if (window.chatBadgeManager && window.chatBadgeManager.isInitialized) {
                window.chatBadgeManager.incrementBadge();
            }
        }

        // Metodo pubblico per decrementare il badge
        static decrementBadge() {
            if (window.chatBadgeManager && window.chatBadgeManager.isInitialized) {
                window.chatBadgeManager.decrementBadge();
            }
        }

        // Metodo pubblico per nascondere il badge (per uso esterno)
        static hideBadge() {
            if (window.chatBadgeManager && window.chatBadgeManager.isInitialized) {
                window.chatBadgeManager.hideBadgeOnChatOpen();
            }
        }

        // Metodo pubblico per aggiornare il badge quando si entra in una stanza
        static updateBadgeForRoom(roomId) {
            if (window.chatBadgeManager && window.chatBadgeManager.isInitialized) {
                window.chatBadgeManager.updateBadgeForSpecificRoom(roomId);
            }
        }

        // Metodi per gestire badge individuali
        static showIndividualChatBadge(roomId, count) {
            if (window.chatBadgeManager && window.chatBadgeManager.isInitialized) {
                window.chatBadgeManager.showIndividualChatBadge(roomId, count);
            }
        }

        static hideIndividualChatBadge(roomId) {
            if (window.chatBadgeManager && window.chatBadgeManager.isInitialized) {
                window.chatBadgeManager.hideIndividualChatBadge(roomId);
            }
        }

        static updateIndividualChatBadge(roomId, count) {
            if (window.chatBadgeManager && window.chatBadgeManager.isInitialized) {
                window.chatBadgeManager.updateIndividualChatBadge(roomId, count);
            }
        }
    }

    // Inizializza quando il DOM è pronto
    function initChatBadge() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initChatBadge, { once: true });
        } else {
            // Crea l'istanza globale
            window.chatBadgeManager = new ChatNotificationBadgeManager();
        }
    }

    // Inizializza
    initChatBadge();

    // Esporta per uso esterno
    window.ChatNotificationBadge = {
        updateBadgeCount: ChatNotificationBadgeManager.updateBadgeCount,
        incrementBadge: ChatNotificationBadgeManager.incrementBadge,
        decrementBadge: ChatNotificationBadgeManager.decrementBadge,
        hideBadge: ChatNotificationBadgeManager.hideBadge,
        updateBadgeForRoom: ChatNotificationBadgeManager.updateBadgeForRoom,
        showIndividualChatBadge: ChatNotificationBadgeManager.showIndividualChatBadge,
        hideIndividualChatBadge: ChatNotificationBadgeManager.hideIndividualChatBadge,
        updateIndividualChatBadge: ChatNotificationBadgeManager.updateIndividualChatBadge,
    };

})();
