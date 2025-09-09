<?php $__env->startSection('title', __('groups.my_group_invitations')); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-envelope me-2 text-primary"></i>
                        <?php echo e(__('groups.my_group_invitations')); ?>

                    </h4>
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('group-invitations.sent')); ?>" class="btn btn-outline-primary">
                            <i class="ph-duotone ph-paper-plane me-2"></i>
                            Inviti Inviati
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($invitations->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                                                <th><?php echo e(__('groups.group_column')); ?></th>
                        <th><?php echo e(__('groups.sent_by_column')); ?></th>
                        <th><?php echo e(__('groups.message_column')); ?></th>
                        <th><?php echo e(__('groups.invite_date_column')); ?></th>
                        <th><?php echo e(__('groups.expires_column')); ?></th>
                        <th><?php echo e(__('groups.status_column')); ?></th>
                        <th><?php echo e(__('groups.actions_column')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $invitations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invitation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        <?php if($invitation->group->avatar): ?>
                                                            <img src="<?php echo e(asset('storage/' . $invitation->group->avatar)); ?>"
                                                                 class="rounded-circle"
                                                                 width="32"
                                                                 height="32"
                                                                 alt="<?php echo e($invitation->group->name); ?>">
                                                        <?php else: ?>
                                                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                                                 style="width: 32px; height: 32px;">
                                                                <i class="ph-duotone ph-users text-white" style="font-size: 16px;"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-medium"><?php echo e($invitation->group->name); ?></div>
                                                        <small class="text-muted"><?php echo e($invitation->group->description ? Str::limit($invitation->group->description, 30) : __('groups.no_description')); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($invitation->invitedBy): ?>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($invitation->invitedBy)); ?>"
                                                                 class="rounded-circle"
                                                                 width="24"
                                                                 height="24"
                                                                 alt="<?php echo e($invitation->invitedBy->name); ?>">
                                                        </div>
                                                        <span><?php echo e($invitation->invitedBy->name); ?></span>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted"><?php echo e(__('groups.user_not_found')); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($invitation->message): ?>
                                                    <span class="text-muted"><?php echo e(Str::limit($invitation->message, 50)); ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted"><?php echo e(__('groups.no_message')); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?php echo e($invitation->created_at->format('d/m/Y H:i')); ?></small>
                                            </td>
                                            <td>
                                                <?php if($invitation->expires_at): ?>
                                                    <small class="text-muted"><?php echo e($invitation->expires_at->format('d/m/Y H:i')); ?></small>
                                                <?php else: ?>
                                                    <small class="text-muted"><?php echo e(__('groups.never_expires')); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($invitation->isPending()): ?>
                                                    <?php if($invitation->isExpired()): ?>
                                                        <span class="badge bg-danger"><?php echo e(__('groups.expired')); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning"><?php echo e(__('groups.pending')); ?></span>
                                                    <?php endif; ?>
                                                <?php elseif($invitation->isAccepted()): ?>
                                                    <span class="badge bg-success"><?php echo e(__('groups.status_accepted')); ?></span>
                                                <?php elseif($invitation->isDeclined()): ?>
                                                    <span class="badge bg-secondary"><?php echo e(__('groups.status_declined')); ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-light text-dark"><?php echo e(ucfirst($invitation->status)); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($invitation->isPending() && !$invitation->isExpired()): ?>
                                                    <div class="btn-group" role="group">
                                                                                                                <button type="button"
                                                                class="btn btn-sm btn-success"
                                                                onclick="confirmAcceptInvitation('<?php echo e($invitation->id); ?>', '<?php echo e($invitation->group->name); ?>')">
                                                            <i class="ph-duotone ph-check me-1"></i>
                                                            <?php echo e(__('groups.accept')); ?>

                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="confirmDeclineInvitation('<?php echo e($invitation->id); ?>', '<?php echo e($invitation->group->name); ?>')">
                                                            <i class="ph-duotone ph-x me-1"></i>
                                                            <?php echo e(__('groups.decline')); ?>

                                                        </button>
                                                    </div>
                                                <?php elseif($invitation->isPending() && $invitation->isExpired()): ?>
                                                    <span class="text-muted small"><?php echo e(__('groups.invitation_expired')); ?></span>
                                                <?php else: ?>
                                                    <a href="<?php echo e(route('group-invitations.show', $invitation)); ?>"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="ph-duotone ph-eye me-1"></i>
                                                        Dettagli
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            <?php echo e($invitations->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="ph-duotone ph-envelope text-muted" style="font-size: 3rem;"></i>
                            </div>
                                                <h5 class="text-muted"><?php echo e(__('groups.no_invitations_received')); ?></h5>
                    <p class="text-muted"><?php echo e(__('groups.no_invitations_received_description')); ?></p>
                            <a href="<?php echo e(route('groups.index')); ?>" class="btn btn-primary">
                                <i class="ph-duotone ph-users me-2"></i>
                                Esplora i Gruppi
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card card-light-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-clock text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">In Attesa</h6>
                            <h4 class="mb-0"><?php echo e($invitations->where('status', 'pending')->where('expires_at', '>', now())->count()); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-light-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-check-circle text-success" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1"><?php echo e(__('groups.invitations_accepted')); ?></h6>
                            <h4 class="mb-0"><?php echo e($invitations->where('status', 'accepted')->count()); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-light-secondary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-x-circle text-secondary" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1"><?php echo e(__('groups.invitations_declined')); ?></h6>
                            <h4 class="mb-0"><?php echo e($invitations->where('status', 'declined')->count()); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-light-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-warning text-danger" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Scaduti</h6>
                            <h4 class="mb-0"><?php echo e($invitations->where('status', 'pending')->where('expires_at', '<', now())->count()); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form nascosti per le azioni -->
<form id="acceptInvitationForm" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
</form>
<form id="declineInvitationForm" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmAcceptInvitation(invitationId, groupName) {
    Swal.fire({
        title: '<?php echo e(__("groups.accept_invitation")); ?>',
        text: '<?php echo e(__("groups.confirm_accept_invitation")); ?>'.replace(':group', groupName),
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<?php echo e(__("groups.accept")); ?>',
        cancelButtonText: '<?php echo e(__("common.cancel")); ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('acceptInvitationForm');
            form.action = `/group-invitations/${invitationId}/accept`;
            form.submit();
        }
    });
}

function confirmDeclineInvitation(invitationId, groupName) {
    Swal.fire({
        title: '<?php echo e(__("groups.decline_invitation")); ?>',
        text: '<?php echo e(__("groups.confirm_decline_invitation")); ?>'.replace(':group', groupName),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<?php echo e(__("groups.decline")); ?>',
        cancelButtonText: '<?php echo e(__("common.cancel")); ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('declineInvitationForm');
            form.action = `/group-invitations/${invitationId}/decline`;
            form.submit();
        }
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/groups/invitations/index.blade.php ENDPATH**/ ?>