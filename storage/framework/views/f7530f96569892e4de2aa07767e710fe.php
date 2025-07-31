<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['content', 'type', 'size' => 'sm']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['content', 'type', 'size' => 'sm']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $isReported = $content->isReportedByUser();
    $reportCount = $content->active_reports_count;
?>

<div class="report-button-container">
    <?php if($isReported): ?>
        <!-- Pulsante per rimuovere la segnalazione -->
        <button type="button"
                class="btn btn-<?php echo e($size); ?> btn-outline-warning report-remove-btn"
                data-type="<?php echo e($type); ?>"
                data-id="<?php echo e($content->id); ?>"
                title="Rimuovi segnalazione">
            <i class="ph-duotone ph-flag-simple"></i>
            <span class="ms-1">Segnalato</span>
        </button>
    <?php else: ?>
        <!-- Pulsante per segnalare -->
        <button type="button"
                class="btn btn-<?php echo e($size); ?> btn-outline-secondary report-btn"
                data-type="<?php echo e($type); ?>"
                data-id="<?php echo e($content->id); ?>"
                title="Segnala contenuto">
            <i class="ph-duotone ph-flag"></i>
            <span class="ms-1">Segnala</span>
        </button>
    <?php endif; ?>

    <?php if($reportCount > 0): ?>
        <span class="badge bg-warning ms-1" title="<?php echo e($reportCount); ?> segnalazioni attive">
            <?php echo e($reportCount); ?>

        </span>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Gestione segnalazione
    $('.report-btn').on('click', function() {
        const type = $(this).data('type');
        const id = $(this).data('id');

        window.location.href = `<?php echo e(route('reports.create')); ?>?type=${type}&id=${id}`;
    });

    // Gestione rimozione segnalazione
    $('.report-remove-btn').on('click', function() {
        const type = $(this).data('type');
        const id = $(this).data('id');
        const button = $(this);

        if (confirm('Sei sicuro di voler rimuovere la tua segnalazione?')) {
            $.ajax({
                url: '<?php echo e(route('reports.remove')); ?>',
                method: 'POST',
                data: {
                    type: type,
                    id: id,
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        // Ricarica la pagina per aggiornare il pulsante
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Errore durante la rimozione della segnalazione');
                }
            });
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/components/report-button.blade.php ENDPATH**/ ?>