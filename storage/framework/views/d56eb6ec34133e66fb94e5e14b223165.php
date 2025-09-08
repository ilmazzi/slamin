<?php $__env->startSection('title', 'Gestione Conti di Pagamento'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Gestione Conti di Pagamento</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="<?php echo e(route('dashboard')); ?>" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="<?php echo e(route('dashboard')); ?>" class="f-s-14 f-w-500">Admin</a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">Conti di Pagamento</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card card-light-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="ph-duotone ph-users f-s-32 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Totale Utenti</h6>
                                <h4 class="mb-0"><?php echo e($stats['total_users']); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-light-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="ph-duotone ph-credit-card f-s-32 text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Stripe Attivi</h6>
                                <h4 class="mb-0"><?php echo e($stats['stripe_connected']); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-light-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="ph-duotone ph-paypal-logo f-s-32 text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">PayPal Verificati</h6>
                                <h4 class="mb-0"><?php echo e($stats['paypal_connected']); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-light-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="ph-duotone ph-clock f-s-32 text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Da Verificare</h6>
                                <h4 class="mb-0"><?php echo e($stats['pending_verification']); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">Azioni Rapide</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="<?php echo e(route('admin.payment-accounts.paypal-verification')); ?>" class="btn btn-warning">
                                <i class="ph-duotone ph-check-circle me-1"></i>Verifica PayPal
                            </a>
                            <a href="<?php echo e(route('admin.payment-accounts.stripe-issues')); ?>" class="btn btn-info">
                                <i class="ph-duotone ph-warning me-1"></i>Problemi Stripe
                            </a>
                            <a href="<?php echo e(route('admin.payment-accounts.statistics')); ?>" class="btn btn-primary">
                                <i class="ph-duotone ph-chart-bar me-1"></i>Statistiche
                            </a>
                            <button class="btn btn-success" onclick="exportData()">
                                <i class="ph-duotone ph-download me-1"></i>Export Dati
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Stripe Users -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-credit-card f-s-16 me-2"></i>
                            Account Stripe Connect
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if($stripeUsers->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Utente</th>
                                            <th>Stato</th>
                                            <th>Pagamenti</th>
                                            <th>Azioni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $stripeUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="ph-duotone ph-user"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?php echo e($user->name); ?></h6>
                                                        <small class="text-muted"><?php echo e($user->email); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php switch($user->stripe_connect_status):
                                                    case ('active'): ?>
                                                        <span class="badge bg-success">Attivo</span>
                                                        <?php break; ?>
                                                    <?php case ('pending'): ?>
                                                        <span class="badge bg-warning">In Attesa</span>
                                                        <?php break; ?>
                                                    <?php case ('restricted'): ?>
                                                        <span class="badge bg-danger">Limitato</span>
                                                        <?php break; ?>
                                                    <?php default: ?>
                                                        <span class="badge bg-secondary">Sconosciuto</span>
                                                <?php endswitch; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?php echo e($user->payments_count); ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="<?php echo e(route('admin.payment-accounts.show', $user)); ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="ph-duotone ph-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('admin.payment-accounts.update-stripe-status', $user)); ?>" class="btn btn-sm btn-outline-info">
                                                        <i class="ph-duotone ph-arrow-clockwise"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php echo e($stripeUsers->links()); ?>

                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="ph-duotone ph-credit-card f-s-48 text-muted mb-3"></i>
                                <h6 class="text-muted">Nessun account Stripe</h6>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- PayPal Users -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-paypal-logo f-s-16 me-2"></i>
                            Account PayPal
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if($paypalUsers->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Utente</th>
                                            <th>Email PayPal</th>
                                            <th>Stato</th>
                                            <th>Azioni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $paypalUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="ph-duotone ph-user"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?php echo e($user->name); ?></h6>
                                                        <small class="text-muted"><?php echo e($user->email); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <code><?php echo e($user->paypal_email); ?></code>
                                            </td>
                                            <td>
                                                <?php if($user->paypal_verified): ?>
                                                    <span class="badge bg-success">Verificato</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Da Verificare</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="<?php echo e(route('admin.payment-accounts.show', $user)); ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="ph-duotone ph-eye"></i>
                                                    </a>
                                                    <?php if($user->paypal_verified): ?>
                                                        <form method="POST" action="<?php echo e(route('admin.payment-accounts.unverify-paypal', $user)); ?>" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Sei sicuro di voler revocare la verifica PayPal per <?php echo e($user->name); ?>?')">
                                                                <i class="ph-duotone ph-x"></i>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <form method="POST" action="<?php echo e(route('admin.payment-accounts.verify-paypal', $user)); ?>" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Sei sicuro di voler verificare l\'account PayPal per <?php echo e($user->name); ?>?')">
                                                                <i class="ph-duotone ph-check"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php echo e($paypalUsers->links()); ?>

                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="ph-duotone ph-paypal-logo f-s-48 text-muted mb-3"></i>
                                <h6 class="text-muted">Nessun account PayPal</h6>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-receipt f-s-16 me-2"></i>
                            Pagamenti Recenti
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if($recentPayments->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Traduttore</th>
                                            <th>Cliente</th>
                                            <th>Poesia</th>
                                            <th>Importo</th>
                                            <th>Metodo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($payment->created_at->format('d/m/Y H:i')); ?></td>
                                            <td><?php echo e($payment->translator->name); ?></td>
                                            <td><?php echo e($payment->client->name); ?></td>
                                            <td><?php echo e(Str::limit($payment->poem->title, 30)); ?></td>
                                            <td>€<?php echo e(number_format($payment->amount, 2)); ?></td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo e(ucfirst($payment->payment_method)); ?></span>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="ph-duotone ph-receipt f-s-48 text-muted mb-3"></i>
                                <h6 class="text-muted">Nessun pagamento recente</h6>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Dati</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('admin.payment-accounts.export')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo di Dati</label>
                        <select class="form-select" name="type" required>
                            <option value="payments">Pagamenti</option>
                            <option value="users">Utenti</option>
                            <option value="accounts">Account di Pagamento</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Formato</label>
                        <select class="form-select" name="format" required>
                            <option value="csv">CSV</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function exportData() {
    new bootstrap.Modal(document.getElementById('exportModal')).show();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/payment-accounts/index.blade.php ENDPATH**/ ?>