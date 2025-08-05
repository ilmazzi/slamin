<!-- SweetAlert2 js-->
<script src="{{asset('assets/vendor/sweetalert/sweetalert.js')}}"></script>

<!-- latest jquery-->
<script src="{{asset('assets/js/jquery-3.6.3.min.js')}}"></script>

<!-- Bootstrap js-->
<script src="{{asset('assets/vendor/bootstrap/bootstrap.bundle.min.js')}}"></script>

<!-- Select2 js -->
<script src="{{asset('assets/vendor/select/select2.min.js')}}"></script>

<!-- phosphor js -->
<script src="{{asset('assets/vendor/phosphor/phosphor.js')}}"></script>

<!-- Simple bar js-->
<script src="{{asset('assets/vendor/simplebar/simplebar.js')}}"></script>

<!-- Customizer js-->
<script src="{{asset('assets/js/customizer.js')}}"></script>

<!-- prism js-->
<script src="{{asset('assets/vendor/prism/prism.min.js')}}"></script>

<!-- App js-->
<script src="{{asset('assets/js/script.js')}}?v={{ time() }}"></script>

<!-- DISABILITA TOOLTIP BOOTSTRAP GLOBALMENTE -->
<script>
// Disabilita completamente i tooltip Bootstrap prima che si inizializzino
document.addEventListener('DOMContentLoaded', function() {
    // Rimuovi tutti gli attributi tooltip dal DOM
    const tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipElements.forEach(function(element) {
        element.removeAttribute('data-bs-toggle');
        element.removeAttribute('data-bs-title');
        element.removeAttribute('data-bs-placement');
    });
    
    // Override delle funzioni Bootstrap Tooltip
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        // Salva le funzioni originali
        const originalGetOrCreateInstance = bootstrap.Tooltip.getOrCreateInstance;
        const originalGetInstance = bootstrap.Tooltip.getInstance;
        
        // Override per impedire l'inizializzazione
        bootstrap.Tooltip.getOrCreateInstance = function(element, config) {
            return null;
        };
        
        bootstrap.Tooltip.getInstance = function(element) {
            return null;
        };
        
        // Disabilita anche la funzione enable
        if (bootstrap.Tooltip.enable) {
            bootstrap.Tooltip.enable = function() {
                return null;
            };
        }
    }
    
    // Rimuovi eventuali tooltip già creati
    const existingTooltips = document.querySelectorAll('.tooltip');
    existingTooltips.forEach(function(tooltip) {
        tooltip.remove();
    });
});

// Disabilita anche con jQuery se disponibile
$(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').removeAttr('data-bs-toggle data-bs-title data-bs-placement');
    $('.tooltip').remove();
});
</script>

<!-- Tooltips js-->
<script src="{{asset('assets/js/tooltips_popovers.js')}}"></script>

<!-- Sidebar logo responsive -->
<script src="{{asset('assets/js/sidebar-logo.js')}}"></script>

<!-- {{ __('wishlist.wishlist') }} Management -->
<script src="{{asset('assets/js/wishlist.js')}}"></script>

<!-- Badge messaggi non letti globale -->
@auth
<script>
// Variabile per l'intervallo dei messaggi non letti globale
let globalUnreadMessagesInterval;

// Inizializza l'aggiornamento del badge dei messaggi non letti globale
function initGlobalUnreadMessagesBadge() {
    console.log('Inizializzazione badge messaggi non letti globale...');
    
    // Aggiorna immediatamente
    updateGlobalUnreadMessagesBadge();
    
    // Imposta intervallo per aggiornamento ogni 30 secondi
    globalUnreadMessagesInterval = setInterval(updateGlobalUnreadMessagesBadge, 30000);
}

// Aggiorna il badge dei messaggi non letti nella sidebar globale
function updateGlobalUnreadMessagesBadge() {
    fetch('{{ route("online-status.unread-messages-count") }}', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const unreadCount = data.unread_count;
            const chatLink = $('a[href="{{ route("chat.index") }}"]');
            const existingBadge = chatLink.find('.badge-notification');
            
            if (unreadCount > 0) {
                // Aggiorna il badge se esiste, altrimenti crealo
                if (existingBadge.length > 0) {
                    existingBadge.text(unreadCount);
                } else {
                    // Crea il badge se non esiste
                    chatLink.append(
                        `<span class="badge bg-danger badge-notification ms-2">${unreadCount}</span>`
                    );
                }
            } else {
                // Rimuovi il badge se non ci sono messaggi non letti
                existingBadge.remove();
            }
            
            console.log(`Badge messaggi non letti globale aggiornato: ${unreadCount}`);
        }
    })
    .catch(error => {
        console.error('Errore aggiornamento badge messaggi non letti globale:', error);
    });
}

// Inizializza quando il documento è pronto
$(document).ready(function() {
    initGlobalUnreadMessagesBadge();
});

// Pulisci l'intervallo quando si lascia la pagina
window.addEventListener('beforeunload', function() {
    if (globalUnreadMessagesInterval) {
        clearInterval(globalUnreadMessagesInterval);
    }
});
</script>
@endauth

@stack('scripts')
