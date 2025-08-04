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
        <div class="report-remove-btn"
             data-type="<?php echo e($type); ?>"
             data-id="<?php echo e($content->id); ?>"
             title="Rimuovi segnalazione"
             style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; transition: all 0.2s;"
             onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
             onmouseout="this.style.backgroundColor='transparent'">
            <i class="ti ti-flag f-s-24 text-warning"></i>
            <span class="text-secondary f-s-12">Segnalato</span>
        </div>
    <?php else: ?>
        <!-- Pulsante per segnalare -->
        <div class="report-btn"
             data-type="<?php echo e($type); ?>"
             data-id="<?php echo e($content->id); ?>"
             title="Segnala contenuto"
             style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; transition: all 0.2s;"
             onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
             onmouseout="this.style.backgroundColor='transparent'">
            <i class="ti ti-flag f-s-24 text-muted"></i>
            <span class="text-secondary f-s-12">Segnala</span>
        </div>
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
                        Swal.fire(
                            'Successo!',
                            response.message,
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Errore!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Errore!',
                        'Errore durante la rimozione della segnalazione',
                        'error'
                    );
                }
            });
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/components/report-button.blade.php ENDPATH**/ ?>