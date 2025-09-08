<?php $__env->startSection('title', 'Impostazioni Pagamenti - Admin'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-credit-card me-2"></i>
                Impostazioni Pagamenti
            </h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="<?php echo e(route('dashboard')); ?>" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> <?php echo e(__('dashboard.dashboard')); ?>

                        </span>
                    </a>
                </li>
                <li class="">
                    <a href="<?php echo e(route('admin.settings.index')); ?>" class="f-s-14 f-w-500">
                        <span>Impostazioni</span>
                    </a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">Pagamenti</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ph ph-check-circle me-2"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ph ph-warning-circle me-2"></i>
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Payment Settings Form -->
    <div class="row">
        <div class="col-12">
                <form id="paymentSettingsForm" method="POST" action="<?php echo e(route('admin.settings.payment.update')); ?>">
                <?php echo csrf_field(); ?>

                <!-- Commissioni -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-percent me-2"></i>
                            Commissioni Traduzioni
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="translation_commission_percentage" class="form-label">
                                    Commissione Percentuale (%)
                                </label>
                                <input type="number"
                                       class="form-control"
                                       id="translation_commission_percentage"
                                       name="settings[translation_commission_percentage]"
                                       value="<?php echo e($paymentSettings['translation_commission_percentage'] ?? '0.10'); ?>"
                                       step="0.01"
                                       min="0"
                                       max="1">
                                <div class="form-text">Percentuale di commissione (es: 0.10 = 10%)</div>
                            </div>
                            <div class="col-md-6">
                                <label for="translation_commission_fixed" class="form-label">
                                    Commissione Fissa (€)
                                </label>
                                <input type="number"
                                       class="form-control"
                                       id="translation_commission_fixed"
                                       name="settings[translation_commission_fixed]"
                                       value="<?php echo e($paymentSettings['translation_commission_fixed'] ?? '0.00'); ?>"
                                       step="0.01"
                                       min="0">
                                <div class="form-text">Commissione fissa in euro</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stripe Configuration -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-credit-card me-2"></i>
                            Configurazione Stripe
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="stripe_enabled"
                                           name="settings[stripe_enabled]"
                                           value="true"
                                           <?php echo e(($paymentSettings['stripe_enabled'] ?? 'true') === 'true' ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="stripe_enabled">
                                        Abilita pagamenti Stripe
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="stripe_connect_enabled"
                                           name="settings[stripe_connect_enabled]"
                                           value="true"
                                           <?php echo e(($paymentSettings['stripe_connect_enabled'] ?? 'false') === 'true' ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="stripe_connect_enabled">
                                        Abilita Stripe Connect (per pagamenti ai traduttori)
                                    </label>
                                    <div class="form-text">Richiede l'attivazione di Stripe Connect nel tuo account Stripe</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="stripe_public_key" class="form-label">
                                    Chiave Pubblica Stripe
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="stripe_public_key"
                                       name="settings[stripe_public_key]"
                                       value="<?php echo e($paymentSettings['stripe_public_key'] ?? ''); ?>"
                                       placeholder="pk_test_...">
                                <div class="form-text">Chiave pubblica per il frontend</div>
                            </div>
                            <div class="col-md-6">
                                <label for="stripe_secret_key" class="form-label">
                                    Chiave Segreta Stripe
                                </label>
                                <input type="password"
                                       class="form-control"
                                       id="stripe_secret_key"
                                       name="settings[stripe_secret_key]"
                                       value="<?php echo e($paymentSettings['stripe_secret_key'] ?? ''); ?>"
                                       placeholder="sk_test_...">
                                <div class="form-text">Chiave segreta per il backend</div>
                            </div>
                            <div class="col-md-6">
                                <label for="stripe_webhook_secret" class="form-label">
                                    Webhook Secret Stripe
                                </label>
                                <input type="password"
                                       class="form-control"
                                       id="stripe_webhook_secret"
                                       name="settings[stripe_webhook_secret]"
                                       value="<?php echo e($paymentSettings['stripe_webhook_secret'] ?? ''); ?>"
                                       placeholder="whsec_...">
                                <div class="form-text">Chiave segreta per i webhook</div>
                            </div>
                            <div class="col-md-6">
                                <label for="stripe_mode" class="form-label">
                                    Modalità Stripe
                                </label>
                                <select class="form-select" id="stripe_mode" name="settings[stripe_mode]">
                                    <option value="test" <?php echo e(($paymentSettings['stripe_mode'] ?? 'test') === 'test' ? 'selected' : ''); ?>>
                                        Test (Sviluppo)
                                    </option>
                                    <option value="live" <?php echo e(($paymentSettings['stripe_mode'] ?? 'test') === 'live' ? 'selected' : ''); ?>>
                                        Live (Produzione)
                                    </option>
                                </select>
                                <div class="form-text">Modalità di funzionamento</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PayPal Configuration -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-paypal-logo me-2"></i>
                            Configurazione PayPal
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="paypal_enabled"
                                           name="settings[paypal_enabled]"
                                           value="true"
                                           <?php echo e(($paymentSettings['paypal_enabled'] ?? 'true') === 'true' ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="paypal_enabled">
                                        Abilita pagamenti PayPal
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="paypal_client_id" class="form-label">
                                    PayPal Client ID
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="paypal_client_id"
                                       name="settings[paypal_client_id]"
                                       value="<?php echo e($paymentSettings['paypal_client_id'] ?? ''); ?>"
                                       placeholder="Client ID PayPal">
                                <div class="form-text">Client ID dalla dashboard PayPal</div>
                            </div>
                            <div class="col-md-6">
                                <label for="paypal_client_secret" class="form-label">
                                    PayPal Client Secret
                                </label>
                                <input type="password"
                                       class="form-control"
                                       id="paypal_client_secret"
                                       name="settings[paypal_client_secret]"
                                       value="<?php echo e($paymentSettings['paypal_client_secret'] ?? ''); ?>"
                                       placeholder="Client Secret PayPal">
                                <div class="form-text">Client Secret dalla dashboard PayPal</div>
                            </div>
                            <div class="col-md-6">
                                <label for="paypal_mode" class="form-label">
                                    Modalità PayPal
                                </label>
                                <select class="form-select" id="paypal_mode" name="settings[paypal_mode]">
                                    <option value="sandbox" <?php echo e(($paymentSettings['paypal_mode'] ?? 'sandbox') === 'sandbox' ? 'selected' : ''); ?>>
                                        Sandbox (Sviluppo)
                                    </option>
                                    <option value="live" <?php echo e(($paymentSettings['paypal_mode'] ?? 'sandbox') === 'live' ? 'selected' : ''); ?>>
                                        Live (Produzione)
                                    </option>
                                </select>
                                <div class="form-text">Modalità di funzionamento</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <i class="ph ph-floppy-disk me-2"></i>
                                Salva Impostazioni
                            </button>
                            <a href="<?php echo e(route('admin.settings.payment.reset')); ?>"
                               class="btn btn-warning"
                               onclick="return confirm('Sei sicuro di voler ripristinare le impostazioni ai valori di default?')">
                                <i class="ph ph-arrow-clockwise me-2"></i>
                                Reset ai Default
                            </a>
                            <a href="<?php echo e(route('admin.settings.index')); ?>" class="btn btn-secondary">
                                <i class="ph ph-arrow-left me-2"></i>
                                Torna alle Impostazioni
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentSettingsForm');
    const saveBtn = document.getElementById('saveBtn');
    const originalText = saveBtn.innerHTML;

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
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/settings/payment.blade.php ENDPATH**/ ?>