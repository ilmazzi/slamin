<?php $__env->startSection('title', 'Inviti Pendenti - ' . $group->name); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-clock me-2 text-warning"></i>
                        Inviti Pendenti - <?php echo e($group->name); ?>

                    </h4>
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('groups.invitations.create', $group)); ?>" class="btn btn-success">
                            <i class="ph-duotone ph-plus me-2"></i>
                            Nuovo Invito
                        </a>
                        <a href="<?php echo e(route('groups.members.index', $group)); ?>" class="btn btn-light">
                            <i class="ph-duotone ph-arrow-left me-2"></i>
                            Indietro
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($invitations->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Utente</th>
                                        <th>Messaggio</th>
                                        <th>Inviato da</th>
                                        <th>Data Invito</th>
                                        <th>Scade il</th>
                                        <th>Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $invitations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invitation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                                                                        <td>
                                                <?php if($invitation->user): ?>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($invitation->user)); ?>"
                                                                 class="rounded-circle"
                                                                 width="32"
                                                                 height="32"
                                                                 alt="<?php echo e($invitation->user->getDisplayName()); ?>">
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="fw-medium"><?php echo e($invitation->user->getDisplayName()); ?></div>
                                                            <small class="text-muted"><?php echo e($invitation->user->getPrivacySafeIdentifier()); ?></small>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">Utente non trovato</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($invitation->message): ?>
                                                    <span class="text-muted"><?php echo e(Str::limit($invitation->message, 50)); ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">Nessun messaggio</span>
                                                <?php endif; ?>
                                            </td>
                                                                                        <td>
                                                <?php if($invitation->invitedBy): ?>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($invitation->invitedBy)); ?>"
                                                                 class="rounded-circle"
                                                                 width="24"
                                                                 height="24"
                                                                 alt="<?php echo e($invitation->invitedBy->getDisplayName()); ?>">
                                                        </div>
                                                        <span><?php echo e($invitation->invitedBy->getDisplayName()); ?></span>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">Utente non trovato</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?php echo e($invitation->created_at ? $invitation->created_at->format('d/m/Y H:i') : 'Data non disponibile'); ?></small>
                                            </td>
                                            <td>
                                                <?php if($invitation->expires_at): ?>
                                                    <small class="text-muted"><?php echo e($invitation->expires_at->format('d/m/Y H:i')); ?></small>
                                                    <?php if($invitation->isExpired()): ?>
                                                        <span class="badge bg-danger ms-1">Scaduto</span>
                                                    <?php elseif($invitation->expires_at->diffInHours(now()) < 24): ?>
                                                        <span class="badge bg-warning ms-1">Scade presto</span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <small class="text-muted">Non scade</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <?php if($invitation->isExpired()): ?>
                                                        <span class="badge bg-danger">Scaduto</span>
                                                    <?php else: ?>
                                                                                                                                                                        <form action="<?php echo e(route('group-invitations.resend', $invitation)); ?>"
                                                              method="POST"
                                                              class="d-inline"
                                                              onsubmit="return confirm('Rinviare l\'invito?')">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                                <i class="ph-duotone ph-arrow-clockwise me-1"></i>
                                                                Rinvio
                                                            </button>
                                                        </form>
                                                                                                                                                                        <form action="<?php echo e(route('group-invitations.cancel', $invitation)); ?>"
                                                              method="POST"
                                                              class="d-inline"
                                                              onsubmit="return confirm('Cancellare l\'invito?')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="ph-duotone ph-x me-1"></i>
                                                                Cancella
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

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            <?php echo e($invitations->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="ph-duotone ph-clock text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-muted">Nessun invito pendente</h5>
                            <p class="text-muted">Non ci sono inviti in attesa di risposta per questo gruppo.</p>
                            <a href="<?php echo e(route('groups.invitations.create', $group)); ?>" class="btn btn-success">
                                <i class="ph-duotone ph-plus me-2"></i>
                                Invia il primo invito
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card card-light-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-clock text-info" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Inviti Pendenti</h6>
                            <h4 class="mb-0"><?php echo e($invitations->total()); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-light-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-warning text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Scadono Presto</h6>
                            <h4 class="mb-0"><?php echo e($invitations->where('expires_at', '>', now())->where('expires_at', '<', now()->addDay())->count()); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-light-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-x-circle text-danger" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Scaduti</h6>
                            <h4 class="mb-0"><?php echo e($invitations->where('expires_at', '<', now())->count()); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/groups/invitations/pending.blade.php ENDPATH**/ ?>