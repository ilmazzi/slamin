// Wishlist Management - Global Functions
window.WishlistManager = {
    // Verifica che le dipendenze siano caricate
    checkDependencies: function() {
        if (typeof $ === 'undefined') {
            console.error('❌ jQuery non è caricato! WishlistManager non può funzionare.');
            return false;
        }
        return true;
    },
    // Inizializza tutti i pulsanti wishlist nella pagina
    init: function() {
        

        if (!this.checkDependencies()) {
            return;
        }

        this.setupEventListeners();
        this.checkInitialState();
    },

    // Configura gli event listener per i pulsanti wishlist
    setupEventListeners: function() {
        // Rimuovi event listener esistenti per evitare duplicati
        $(document).off('click', '.wishlist-toggle');

        // Aggiungi event listener globale
        $(document).on('click', '.wishlist-toggle', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const eventId = $(this).data('event-id');
            const button = $(this);

            if (!eventId) {
                console.error('❌ No event-id found on wishlist button');
                return;
            }

            
            WishlistManager.toggleWishlist(eventId, button);
        });
    },

    // Controlla lo stato iniziale di tutti i pulsanti wishlist
    checkInitialState: function() {
        $('.wishlist-toggle').each(function() {
            const eventId = $(this).data('event-id');
            const button = $(this);

            if (eventId) {
                WishlistManager.checkWishlistState(eventId, button);
            }
        });
    },

    // Toggle wishlist per un evento specifico
    toggleWishlist: function(eventId, button) {
        const icon = button.find('.wishlist-icon');

        // Mostra loading state
        const originalContent = button.html();
        button.html('<i class="ph-duotone ph-spinner f-s-14"></i>');
        button.prop('disabled', true);

        $.ajax({
            url: `/wishlist/${eventId}/toggle`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                

                if (response.success) {
                    WishlistManager.updateButtonState(button, response.in_wishlist);

                    // Notifica di successo
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: response.in_wishlist ? 'Aggiunto alla wishlist' : 'Rimosso dalla wishlist',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        // Fallback per notifiche semplici
                        WishlistManager.showNotification(response.message, 'success');
                    }
                } else {
                    console.error('❌ Wishlist toggle failed:', response);
                    WishlistManager.showNotification('Errore nell\'aggiornamento della wishlist', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Wishlist toggle error:', error);
                WishlistManager.showNotification('Impossibile aggiornare la wishlist', 'error');
            },
            complete: function() {
                // Ripristina il pulsante
                button.html(originalContent);
                button.prop('disabled', false);
            }
        });
    },

    // Controlla lo stato della wishlist per un evento
    checkWishlistState: function(eventId, button) {
        $.ajax({
            url: `/wishlist/${eventId}/check`,
            method: 'GET',
            success: function(response) {
                if (response.in_wishlist) {
                    WishlistManager.updateButtonState(button, true);
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error checking wishlist state:', error);
            }
        });
    },

        // Aggiorna lo stato visivo del pulsante
    updateButtonState: function(button, inWishlist) {
        const icon = button.find('.wishlist-icon');
        const text = button.find('.wishlist-text');

        if (inWishlist) {
            // Aggiunto alla wishlist
            button.removeClass('btn-outline-danger btn-light-danger').addClass('btn-danger');
            icon.removeClass('ph-heart').addClass('ph-heart-fill');
            button.attr('title', 'Rimuovi dalla wishlist');

            // Aggiorna il testo se presente
            if (text.length > 0) {
                text.text('Rimuovi dalla Wishlist');
            }
        } else {
            // Rimosso dalla wishlist
            button.removeClass('btn-danger').addClass('btn-outline-danger');
            icon.removeClass('ph-heart-fill').addClass('ph-heart');
            button.attr('title', 'Aggiungi alla wishlist');

            // Aggiorna il testo se presente
            if (text.length > 0) {
                text.text('Aggiungi alla Wishlist');
            }
        }
    },

    // Mostra notifica semplice
    showNotification: function(message, type) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        alert.style.top = '20px';
        alert.style.right = '20px';
        alert.style.zIndex = '9999';
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);

        setTimeout(() => {
            alert.remove();
        }, 5000);
    },

    // Inizializza wishlist per contenuto caricato dinamicamente
    initForDynamicContent: function(container) {
        
        const buttons = $(container).find('.wishlist-toggle');

        buttons.each(function() {
            const eventId = $(this).data('event-id');
            const button = $(this);

            if (eventId) {
                WishlistManager.checkWishlistState(eventId, button);
            }
        });
    }
};

// Inizializza automaticamente quando il documento è pronto
$(document).ready(function() {
    
    WishlistManager.init();
});

// Usa MutationObserver invece di DOMNodeInserted (deprecato)
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.type === 'childList') {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1 && node.classList && node.classList.contains('wishlist-toggle')) {
                    const eventId = node.dataset.eventId;
                    if (eventId) {
                        WishlistManager.checkWishlistState(eventId, $(node));
                    }
                }
            });
        }
    });
});

// Inizia a osservare quando il documento è pronto
$(document).ready(function() {
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});
