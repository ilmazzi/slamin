

<?php $__env->startSection('main-content'); ?>
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">🎭 I Miei Inviti</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('dashboard.dashboard')); ?></a></li>
                            <li class="breadcrumb-item active">Inviti</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium text-truncate d-block"><?php echo e(__('invitations.total_invitations')); ?></span>
                                <h4 class="fs-4 fw-semibold mb-3"><?php echo e($invitations->total()); ?></h4>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="ph ph-envelope-simple text-primary fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium text-truncate d-block"><?php echo e(__('invitations.pending_invitations')); ?></span>
                                <h4 class="fs-4 fw-semibold mb-3 text-warning"><?php echo e($invitations->where('status', 'pending')->count()); ?></h4>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="ph ph-clock text-warning fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium text-truncate d-block"><?php echo e(__('invitations.accepted_invitations')); ?></span>
                                <h4 class="fs-4 fw-semibold mb-3 text-success"><?php echo e($invitations->where('status', 'accepted')->count()); ?></h4>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="ph ph-check-circle text-success fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium text-truncate d-block"><?php echo e(__('invitations.rejected_invitations')); ?></span>
                                <h4 class="fs-4 fw-semibold mb-3 text-danger"><?php echo e($invitations->where('status', 'declined')->count()); ?></h4>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="ph ph-x-circle text-danger fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ph ph-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('info')): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="ph ph-info me-2"></i><?php echo e(session('info')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ph ph-warning me-2"></i><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Invitations List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0">📨 Inviti Ricevuti</h4>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                                    <i class="ph ph-arrows-clockwise"></i> Aggiorna
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if($invitations->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th><?php echo e(__('invitations.event')); ?></th>
                                            <th><?php echo e(__('invitations.role')); ?></th>
                                            <th><?php echo e(__('events.organizer')); ?></th>
                                            <th>Data <?php echo e(__('invitations.event')); ?></th>
                                            <th><?php echo e(__('invitations.status')); ?></th>
                                            <th><?php echo e(__('invitations.actions')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $invitations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invitation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                            <i class="ph ph-calendar text-muted"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0"><?php echo e($invitation->event->title ?? 'N/A'); ?></h6>
                                                            <small class="text-muted"><?php echo e($invitation->event->city ?? 'N/A'); ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary"><?php echo e(ucfirst($invitation->role ?? 'N/A')); ?></span>
                                                    <?php if($invitation->compensation): ?>
                                                        <br><small class="text-success">€<?php echo e($invitation->compensation); ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xs me-2">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <?php echo e(substr($invitation->inviter->name ?? 'N', 0, 1)); ?>

                                                            </span>
                                                        </div>
                                                        <span><?php echo e($invitation->inviter->name ?? 'N/A'); ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong><?php echo e($invitation->event->start_datetime ? $invitation->event->start_datetime->format('d/m/Y') : 'N/A'); ?></strong>
                                                        <br>
                                                        <small class="text-muted"><?php echo e($invitation->event->start_datetime ? $invitation->event->start_datetime->format('H:i') : 'N/A'); ?></small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php switch($invitation->status):
                                                        case ('pending'): ?>
                                                            <span class="badge bg-warning">
                                                                <i class="ph ph-clock me-1"></i><?php echo e(__('invitations.pending_invitations')); ?>

                                                            </span>
                                                            <?php if($invitation->expires_at && $invitation->expires_at->isPast()): ?>
                                                                <br><small class="text-danger"><?php echo e(__('invitations.expired')); ?></small>
                                                            <?php elseif($invitation->expires_at): ?>
                                                                <br><small class="text-muted">Scade: <?php echo e($invitation->expires_at->format('d/m/Y H:i')); ?></small>
                                                            <?php endif; ?>
                                                            <?php break; ?>
                                                        <?php case ('accepted'): ?>
                                                            <span class="badge bg-success">
                                                                <i class="ph ph-check-circle me-1"></i>Accettato
                                                            </span>
                                                            <?php break; ?>
                                                        <?php case ('declined'): ?>
                                                            <span class="badge bg-danger">
                                                                <i class="ph ph-x-circle me-1"></i>Rifiutato
                                                            </span>
                                                            <?php break; ?>
                                                    <?php endswitch; ?>
                                                </td>
                                                <td>
                                                    <?php if($invitation->status === 'pending'): ?>
                                                        <div class="d-flex gap-1">
                                                            <button type="button"
                                                                    class="btn btn-success btn-sm"
                                                                    onclick="confirmAcceptInvitation(<?php echo e($invitation->id); ?>, '<?php echo e($invitation->event->title ?? 'N/A'); ?>')">
                                                                <i class="ph ph-check"></i> Accetta
                                                            </button>
                                                            <button type="button"
                                                                    class="btn btn-danger btn-sm"
                                                                    onclick="confirmDeclineInvitation(<?php echo e($invitation->id); ?>, '<?php echo e($invitation->event->title ?? 'N/A'); ?>')">
                                                                <i class="ph ph-x"></i> Rifiuta
                                                            </button>
                                                        </div>
                                                    <?php else: ?>
                                                        <a href="<?php echo e(route('events.show', $invitation->event)); ?>"
                                                           class="btn btn-outline-primary btn-sm">
                                                            <i class="ph ph-eye"></i> Vedi <?php echo e(__('invitations.event')); ?>

                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                <?php echo e($invitations->links()); ?>

                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="ph ph-envelope-simple text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted"><?php echo e(__('invitations.no_invitations')); ?></h5>
                                <p class="text-muted">Non hai ancora ricevuto inviti per eventi poetry slam.</p>
                                <a href="<?php echo e(route('events.index')); ?>" class="btn btn-primary">
                                    <i class="ph ph-calendar-plus"></i> Cerca Eventi
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function confirmAcceptInvitation(invitationId, eventTitle) {
        Swal.fire({
            title: '🎭 Conferma Accettazione',
            text: `Sei sicuro di voler accettare l'invito per l'evento "${eventTitle}"?`,
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Accetta Invito',
            cancelButtonText: 'Annulla'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/invitations/${invitationId}/accept`;
            }
        });
    }

    function confirmDeclineInvitation(invitationId, eventTitle) {
        Swal.fire({
            title: '❌ Conferma Rifiuto',
            text: `Sei sicuro di voler rifiutare l'invito per l'evento "${eventTitle}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Rifiuta Invito',
            cancelButtonText: 'Annulla'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/invitations/${invitationId}/decline`;
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/invitations/fixed.blade.php ENDPATH**/ ?>