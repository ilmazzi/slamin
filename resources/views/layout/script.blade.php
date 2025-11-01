<!-- Laravel Echo -->


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

<!-- INIZIALIZZA TOOLTIP BOOTSTRAP CORRETTAMENTE -->
<script>
// Inizializza tooltip Bootstrap con auto-hide
document.addEventListener('DOMContentLoaded', function() {
    // Inizializza tutti i tooltip
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            trigger: 'hover focus',
            delay: { show: 300, hide: 100 },
            animation: true,
            html: false,
            placement: 'auto'
        });
    });

    // Auto-hide tooltip quando si clicca sull'elemento
    tooltipTriggerList.forEach(element => {
        element.addEventListener('click', function() {
            const tooltip = bootstrap.Tooltip.getInstance(this);
            if (tooltip) {
                tooltip.hide();
            }
        });
    });

    // Auto-hide tutti i tooltip quando si clicca fuori
    document.addEventListener('click', function(e) {
        if (!e.target.closest('[data-bs-toggle="tooltip"]')) {
            tooltipTriggerList.forEach(element => {
                const tooltip = bootstrap.Tooltip.getInstance(element);
                if (tooltip) {
                    tooltip.hide();
                }
            });
        }
    });
});

// Cleanup tooltip con jQuery (se disponibile)
$(document).ready(function() {
    // Auto-hide tooltip dopo 3 secondi se resta aperto
    $(document).on('shown.bs.tooltip', function(e) {
        setTimeout(function() {
            const tooltip = bootstrap.Tooltip.getInstance(e.target);
            if (tooltip) {
                tooltip.hide();
            }
        }, 3000);
    });
});
</script>

<!-- Sidebar logo responsive -->


<!-- {{ __('wishlist.wishlist') }} Management -->
<script src="{{asset('assets/js/wishlist.js')}}"></script>

<script src="{{asset('assets/vendor/toastify/toastify.js')}}"></script>

<!-- Chat Notification Badge -->
<script src="{{asset('js/chat-notification-badge.js')}}"></script>





@stack('scripts')
