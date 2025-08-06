<!-- SweetAlert2 js-->
<script src="<?php echo e(asset('assets/vendor/sweetalert/sweetalert.js')); ?>"></script>

<!-- latest jquery-->
<script src="<?php echo e(asset('assets/js/jquery-3.6.3.min.js')); ?>"></script>

<!-- Bootstrap js-->
<script src="<?php echo e(asset('assets/vendor/bootstrap/bootstrap.bundle.min.js')); ?>"></script>

<!-- Select2 js -->
<script src="<?php echo e(asset('assets/vendor/select/select2.min.js')); ?>"></script>

<!-- phosphor js -->
<script src="<?php echo e(asset('assets/vendor/phosphor/phosphor.js')); ?>"></script>

<!-- Simple bar js-->
<script src="<?php echo e(asset('assets/vendor/simplebar/simplebar.js')); ?>"></script>

<!-- Customizer js-->
<script src="<?php echo e(asset('assets/js/customizer.js')); ?>"></script>

<!-- prism js-->
<script src="<?php echo e(asset('assets/vendor/prism/prism.min.js')); ?>"></script>

<!-- App js-->
<script src="<?php echo e(asset('assets/js/script.js')); ?>?v=<?php echo e(time()); ?>"></script>

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
<script src="<?php echo e(asset('assets/js/tooltips_popovers.js')); ?>"></script>

<!-- Sidebar logo responsive -->
<script src="<?php echo e(asset('assets/js/sidebar-logo.js')); ?>"></script>

<!-- <?php echo e(__('wishlist.wishlist')); ?> Management -->
<script src="<?php echo e(asset('assets/js/wishlist.js')); ?>"></script>



<?php echo $__env->yieldPushContent('scripts'); ?>
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/layout/script.blade.php ENDPATH**/ ?>