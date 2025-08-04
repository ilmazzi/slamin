<?php $__env->startSection('title', __('gigs.applications.manage_applications')); ?>

<?php $__env->startSection('main-content'); ?>
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="page-title-box">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0 small">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('home')); ?>" class="text-decoration-none">
                                <i class="ph ph-house me-1"></i><?php echo e(__('common.home')); ?>

                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('gigs.index')); ?>" class="text-decoration-none">
                                <i class="ph ph-briefcase me-1"></i><?php echo e(__('gigs.title')); ?>

                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('gigs.show', $gig)); ?>" class="text-decoration-none">
                                <?php echo e(Str::limit($gig->title, 30)); ?>

                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <i class="ph ph-users me-1"></i><?php echo e(__('gigs.applications.manage_applications')); ?>

                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Header del Gig -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2"><?php echo e($gig->title); ?></h4>
                        <p class="text-muted mb-2"><?php echo e(Str::limit($gig->description, 150)); ?></p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-light-primary"><?php echo e(__('gigs.categories.' . $gig->category)); ?></span>
                            <span class="badge bg-light-primary"><?php echo e(__('gigs.types.' . $gig->type)); ?></span>
                            <?php if($gig->is_remote): ?>
                                <span class="badge bg-light-success">
                                    <i class="ph ph-globe me-1"></i><?php echo e(__('gigs.fields.is_remote')); ?>

                                </span>
                            <?php endif; ?>
                            <?php if($gig->is_urgent): ?>
                                <span class="badge bg-warning">
                                    <i class="ph ph-warning me-1"></i><?php echo e(__('gigs.status.urgent')); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="d-flex flex-column gap-2">
                            <div class="text-center">
                                <h5 class="mb-0 text-primary"><?php echo e($gig->application_count); ?></h5>
                                <small class="text-muted"><?php echo e(__('gigs.applications.total_applications')); ?></small>
                            </div>
                            <div class="text-center">
                                <h5 class="mb-0 text-success"><?php echo e($gig->accepted_applications_count); ?></h5>
                                <small class="text-muted"><?php echo e(__('gigs.applications.accepted_applications')); ?></small>
                            </div>
                            <?php if($gig->max_applications): ?>
                                <div class="text-center">
                                    <h5 class="mb-0 text-info"><?php echo e($gig->max_applications); ?></h5>
                                    <small class="text-muted"><?php echo e(__('gigs.applications.max_positions')); ?></small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiche Candidature -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card card-light-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-primary rounded">
                                        <i class="ph ph-clock text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1"><?php echo e(__('gigs.applications.pending')); ?></h6>
                                <h4 class="mb-0"><?php echo e($applications->where('status', 'pending')->count()); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-light-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-success rounded">
                                        <i class="ph ph-check-circle text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1"><?php echo e(__('gigs.applications.accepted')); ?></h6>
                                <h4 class="mb-0"><?php echo e($applications->where('status', 'accepted')->count()); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-light-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-danger rounded">
                                        <i class="ph ph-x-circle text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1"><?php echo e(__('gigs.applications.rejected')); ?></h6>
                                <h4 class="mb-0"><?php echo e($applications->where('status', 'rejected')->count()); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-light-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-warning rounded">
                                        <i class="ph ph-arrow-return-left text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1"><?php echo e(__('gigs.applications.withdrawn')); ?></h6>
                                <h4 class="mb-0"><?php echo e($applications->where('status', 'withdrawn')->count()); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Azioni -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2">
                    <?php if(!$gig->is_closed): ?>
                        <button class="btn btn-danger" onclick="closeGig(<?php echo e($gig->id); ?>)">
                            <i class="ph ph-lock me-2"></i><?php echo e(__('gigs.actions.close_gig')); ?>

                        </button>
                    <?php else: ?>
                        <button class="btn btn-success" onclick="reopenGig(<?php echo e($gig->id); ?>)">
                            <i class="ph ph-unlock me-2"></i><?php echo e(__('gigs.actions.reopen_gig')); ?>

                        </button>
                    <?php endif; ?>
                    <button class="btn btn-primary" onclick="sendGlobalMessage(<?php echo e($gig->id); ?>)">
                        <i class="ph ph-megaphone me-2"></i><?php echo e(__('gigs.actions.send_global_message')); ?>

                    </button>
                    <a href="<?php echo e(route('gigs.show', $gig)); ?>" class="btn btn-outline-primary">
                        <i class="ph ph-eye me-2"></i><?php echo e(__('gigs.actions.view_gig')); ?>

                    </a>
                </div>
            </div>
        </div>

        <!-- Lista Candidature -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ph ph-users me-2"></i><?php echo e(__('gigs.applications.applications_list')); ?>

                </h5>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar-sm me-3">
                                            <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($application->user)); ?>"
                                                 alt="<?php echo e($application->user->getDisplayName()); ?>"
                                                 class="rounded-circle" width="40" height="40">
                                        </div>
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="<?php echo e(route('user.show', $application->user)); ?>"
                                                   class="text-decoration-none hover-effect">
                                                    <?php echo e($application->user->getDisplayName()); ?>

                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="ph ph-clock me-1"></i>
                                                <?php echo e($application->created_at->diffForHumans()); ?>

                                            </small>
                                        </div>
                                    </div>

                                    <?php if($application->message): ?>
                                        <p class="mb-2"><?php echo e(Str::limit($application->message, 200)); ?></p>
                                    <?php endif; ?>

                                    <div class="row text-center">
                                        <?php if($application->experience): ?>
                                            <div class="col-4">
                                                <small class="text-muted d-block"><?php echo e(__('gigs.applications.experience')); ?></small>
                                                <strong><?php echo e(Str::limit($application->experience, 30)); ?></strong>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($application->portfolio): ?>
                                            <div class="col-4">
                                                <small class="text-muted d-block"><?php echo e(__('gigs.applications.portfolio')); ?></small>
                                                <a href="<?php echo e($application->portfolio); ?>" target="_blank" class="text-decoration-none">
                                                    <strong><?php echo e(__('gigs.applications.view_portfolio')); ?></strong>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($application->compensation_expectation): ?>
                                            <div class="col-4">
                                                <small class="text-muted d-block"><?php echo e(__('gigs.applications.compensation_expectation')); ?></small>
                                                <strong><?php echo e($application->compensation_expectation); ?></strong>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="text-end">
                                        <!-- Status Badge -->
                                        <div class="mb-2">
                                            <?php if($application->status === 'pending'): ?>
                                                <span class="badge bg-warning">
                                                    <i class="ph ph-clock me-1"></i><?php echo e(__('gigs.applications.pending')); ?>

                                                </span>
                                            <?php elseif($application->status === 'accepted'): ?>
                                                <span class="badge bg-success">
                                                    <i class="ph ph-check-circle me-1"></i><?php echo e(__('gigs.applications.accepted')); ?>

                                                </span>
                                            <?php elseif($application->status === 'rejected'): ?>
                                                <span class="badge bg-danger">
                                                    <i class="ph ph-x-circle me-1"></i><?php echo e(__('gigs.applications.rejected')); ?>

                                                </span>
                                            <?php elseif($application->status === 'withdrawn'): ?>
                                                <span class="badge bg-secondary">
                                                    <i class="ph ph-arrow-return-left me-1"></i><?php echo e(__('gigs.applications.withdrawn')); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Action Buttons -->
                                        <?php if($application->status === 'pending'): ?>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button class="btn btn-success btn-sm"
                                                        onclick="acceptApplication(<?php echo e($application->id); ?>)">
                                                    <i class="ph ph-check me-1"></i><?php echo e(__('gigs.applications.accept')); ?>

                                                </button>
                                                <button class="btn btn-danger btn-sm"
                                                        onclick="rejectApplication(<?php echo e($application->id); ?>)">
                                                    <i class="ph ph-x me-1"></i><?php echo e(__('gigs.applications.reject')); ?>

                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-5">
                        <i class="ph ph-users text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3"><?php echo e(__('gigs.applications.no_applications')); ?></h5>
                        <p class="text-muted"><?php echo e(__('gigs.applications.no_applications_description')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Paginazione -->
        <?php if($applications->hasPages()): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <?php echo e($applications->links()); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal per messaggio globale -->
<div class="modal fade" id="globalMessageModal" tabindex="-1" aria-labelledby="globalMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="globalMessageModalLabel"><?php echo e(__('gigs.actions.send_global_message')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="globalMessageForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="global_message" class="form-label"><?php echo e(__('gigs.actions.message')); ?> <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="global_message" name="message" rows="4"
                                  placeholder="<?php echo e(__('gigs.actions.message_placeholder')); ?>" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('common.cancel')); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo e(__('gigs.actions.send_message')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let currentGigId = <?php echo e($gig->id); ?>;

function acceptApplication(applicationId) {
    Swal.fire({
        title: '<?php echo e(__("gigs.applications.confirm_accept")); ?>',
        text: '<?php echo e(__("gigs.applications.confirm_accept_text")); ?>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<?php echo e(__("gigs.applications.accept")); ?>',
        cancelButtonText: '<?php echo e(__("common.cancel")); ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/gigs/applications/${applicationId}/accept`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Successo!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Errore!', data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Errore!', 'Errore di connessione', 'error');
            });
        }
    });
}

function rejectApplication(applicationId) {
    Swal.fire({
        title: '<?php echo e(__("gigs.applications.confirm_reject")); ?>',
        text: '<?php echo e(__("gigs.applications.confirm_reject_text")); ?>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<?php echo e(__("gigs.applications.reject")); ?>',
        cancelButtonText: '<?php echo e(__("common.cancel")); ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/gigs/applications/${applicationId}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Successo!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Errore!', data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Errore!', 'Errore di connessione', 'error');
            });
        }
    });
}

function closeGig(gigId) {
    Swal.fire({
        title: '<?php echo e(__("gigs.actions.confirm_close")); ?>',
        text: '<?php echo e(__("gigs.actions.confirm_close_text")); ?>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<?php echo e(__("gigs.actions.close_gig")); ?>',
        cancelButtonText: '<?php echo e(__("common.cancel")); ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/gigs/${gigId}/close`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Successo!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Errore!', data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Errore!', 'Errore di connessione', 'error');
            });
        }
    });
}

function reopenGig(gigId) {
    Swal.fire({
        title: '<?php echo e(__("gigs.actions.confirm_reopen")); ?>',
        text: '<?php echo e(__("gigs.actions.confirm_reopen_text")); ?>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<?php echo e(__("gigs.actions.reopen_gig")); ?>',
        cancelButtonText: '<?php echo e(__("common.cancel")); ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/gigs/${gigId}/reopen`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Successo!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Errore!', data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Errore!', 'Errore di connessione', 'error');
            });
        }
    });
}

function sendGlobalMessage(gigId) {
    $('#globalMessageModal').modal('show');
}

$('#globalMessageForm').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(`/gigs/${currentGigId}/global-message`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Successo!', data.message, 'success').then(() => {
                $('#globalMessageModal').modal('hide');
                $('#globalMessageForm')[0].reset();
            });
        } else {
            Swal.fire('Errore!', data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Errore!', 'Errore di connessione', 'error');
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/gigs/manage-applications.blade.php ENDPATH**/ ?>