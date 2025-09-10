<?php $__env->startSection('title', 'Impostazioni - Admin'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-gear me-2"></i>
                Impostazioni
            </h4>
            
        </div>
    </div>

    <!-- Quick Navigation Links -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-navigation-arrow me-2"></i>
                        <?php echo e(__('admin.quick_navigation')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('admin.settings.upload.index')); ?>" class="card card-light-primary hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-upload f-s-30 text-primary mb-2"></i>
                                    <h6 class="mb-1">Impostazioni Upload</h6>
                                    <small class="text-muted">Gestisci limiti e tipi di file</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('admin.settings.payment.index')); ?>" class="card card-light-success hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-credit-card f-s-30 text-success mb-2"></i>
                                    <h6 class="mb-1">Impostazioni Pagamenti</h6>
                                    <small class="text-muted">Configura Stripe e PayPal</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('admin.carousels.index')); ?>" class="card card-light-info hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-squares-four f-s-30 text-info mb-2"></i>
                                    <h6 class="mb-1"><?php echo e(__('admin.carousel_management')); ?></h6>
                                    <small class="text-muted"><?php echo e(__('admin.manage_carousel')); ?></small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('admin.settings.placeholder')); ?>" class="card card-light-info hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-palette f-s-30 text-info mb-2"></i>
                                    <h6 class="mb-1">Impostazioni Placeholder</h6>
                                    <small class="text-muted">Colori placeholder immagini</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('articles.layout.index')); ?>" class="card card-light-warning hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-newspaper f-s-30 text-warning mb-2"></i>
                                    <h6 class="mb-1">Layout Articoli</h6>
                                    <small class="text-muted">Gestisci posizioni articoli</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('admin.translations.index')); ?>" class="card card-light-success hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-translate f-s-30 text-success mb-2"></i>
                                    <h6 class="mb-1">Gestione Traduzioni</h6>
                                    <small class="text-muted">Gestisci traduzioni del sito</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('admin.peertube.index')); ?>" class="card card-light-warning hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-video-camera f-s-30 text-warning mb-2"></i>
                                    <h6 class="mb-1">Configurazione PeerTube</h6>
                                    <small class="text-muted">Gestisci integrazione PeerTube</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('admin.payment-accounts.index')); ?>" class="card card-light-info hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-users f-s-30 text-info mb-2"></i>
                                    <h6 class="mb-1">Conti di Pagamento</h6>
                                    <small class="text-muted">Gestione account utenti</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Form -->
    <form id="settingsForm" action="<?php echo e(route('admin.settings.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="row">
            <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupKey => $groupName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-6 mb-4">
                <div class="card hover-effect">
                    <div class="card-header">
                        <h5 class="mb-0 f-w-600 text-dark">
                            <i class="ph ph-<?php echo e($groupKey === 'upload' ? 'upload' : ($groupKey === 'video' ? 'video-camera' : ($groupKey === 'payment' ? 'credit-card' : 'gear'))); ?> me-2"></i>
                            <?php echo e($groupName); ?>

                        </h5>
                    </div>
                    <div class="card-body pa-30">
                        <?php if(isset($settings[$groupKey])): ?>
                            <?php $__currentLoopData = $settings[$groupKey]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="mb-4">
                                    <label class="form-label f-w-600"><?php echo e($setting['display_name']); ?></label>

                                    <?php if($setting['type'] === 'boolean'): ?>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="settings[<?php echo e($key); ?>]" value="1"
                                                   <?php echo e($setting['value'] ? 'checked' : ''); ?>>
                                            <label class="form-check-label">
                                                <?php echo e($setting['value'] ? 'Abilitato' : 'Disabilitato'); ?>

                                            </label>
                                        </div>
                                    <?php elseif($setting['type'] === 'json'): ?>
                                        <textarea class="form-control" name="settings[<?php echo e($key); ?>]" rows="3"
                                                  placeholder="<?php echo e($setting['description']); ?>"><?php echo e(is_array($setting['value']) ? json_encode($setting['value'], JSON_PRETTY_PRINT) : $setting['value']); ?></textarea>
                                    <?php elseif($setting['type'] === 'integer' || $setting['type'] === 'float'): ?>
                                        <input type="number" class="form-control" name="settings[<?php echo e($key); ?>]"
                                               value="<?php echo e($setting['value']); ?>" step="<?php echo e($setting['type'] === 'float' ? '0.01' : '1'); ?>"
                                               placeholder="<?php echo e($setting['description']); ?>">
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="settings[<?php echo e($key); ?>]"
                                               value="<?php echo e($setting['value']); ?>"
                                               placeholder="<?php echo e($setting['description']); ?>">
                                    <?php endif; ?>

                                    <?php if(isset($setting['description'])): ?>
                                        <div class="form-text"><?php echo e($setting['description']); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <p class="text-muted">Nessuna impostazione disponibile per questo gruppo.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <i class="ph ph-floppy-disk me-2"></i>
                                Salva Impostazioni
                            </button>
                            <button type="button" class="btn btn-warning" id="resetBtn">
                                <i class="ph ph-arrow-clockwise me-2"></i>
                                Reset ai Default
                            </button>
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary">
                                <i class="ph ph-arrow-left me-2"></i>
                                Torna alla Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('settingsForm');
    const saveBtn = document.getElementById('saveBtn');
    const resetBtn = document.getElementById('resetBtn');
    const originalText = saveBtn.innerHTML;

    // Save form
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Show loading state
        saveBtn.innerHTML = '<i class="ph ph-spinner ph-spin me-2"></i>Salvataggio...';
        saveBtn.disabled = true;

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Success message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Successo!',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    alert(data.message);
                }
            } else {
                // Error message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore!',
                        text: data.message,
                        footer: data.errors && Array.isArray(data.errors) ? data.errors.join('<br>') : ''
                    });
                } else {
                    alert('Errore: ' + data.message);
                }
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: 'Errore durante il salvataggio: ' + (error.message || 'Errore sconosciuto')
                });
            } else {
                alert('Errore durante il salvataggio: ' + (error.message || 'Errore sconosciuto'));
            }
        })
        .finally(() => {
            // Reset button
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        });
    });

    // Reset button
    resetBtn.addEventListener('click', function() {
        if (confirm('Sei sicuro di voler ripristinare tutte le impostazioni ai valori di default?')) {
            // Reset form to original values
            form.reset();

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Reset Completato',
                    text: 'Le impostazioni sono state ripristinate ai valori di default. Ricorda di salvare per applicare le modifiche.',
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                alert('Le impostazioni sono state ripristinate ai valori di default. Ricorda di salvare per applicare le modifiche.');
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>