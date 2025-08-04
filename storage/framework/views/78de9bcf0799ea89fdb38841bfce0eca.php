<?php $__env->startSection('title', $event->title); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/vendor/leafletmaps/leaflet.css')); ?>">
<style>
/* Mobile-First Responsive Styles for Events Show */
@media (max-width: 576px) {
    .card-body {
        padding: 1rem !important;
    }
    
    .card-header {
        padding: 0.75rem 1rem !important;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .badge {
        font-size: 0.625rem;
    }
    
    .f-s-10 {
        font-size: 0.625rem !important;
    }
    
    .f-s-12 {
        font-size: 0.75rem !important;
    }
    
    .f-s-14 {
        font-size: 0.875rem !important;
    }
    
    .f-s-16 {
        font-size: 1rem !important;
    }
    
    .f-s-18 {
        font-size: 1.125rem !important;
    }
    
    .f-s-48 {
        font-size: 3rem !important;
    }
    
    /* Hero section mobile optimization */
    .position-relative.overflow-hidden.rounded-3 {
        height: 200px !important;
    }
    
    /* Event info mobile optimization */
    .d-flex.align-items-center {
        margin-bottom: 0.5rem !important;
    }
    
    /* Participants cards mobile optimization */
    .card.border-0 .card-body {
        padding: 0.75rem !important;
    }
    
    /* Sidebar mobile optimization */
    .position-sticky {
        position: static !important;
        top: auto !important;
    }
    
    /* Map mobile optimization */
    #eventMap {
        height: 200px !important;
    }
    
    /* Alert mobile optimization */
    .alert {
        padding: 0.75rem !important;
        font-size: 0.875rem !important;
    }
    
    /* Timeline mobile optimization */
    .border-start.border-4 {
        padding-left: 0.75rem !important;
    }
}

/* Tablet optimization */
@media (min-width: 577px) and (max-width: 768px) {
    .card-body {
        padding: 1.25rem !important;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .position-relative.overflow-hidden.rounded-3 {
        height: 225px !important;
    }
    
    #eventMap {
        height: 225px !important;
    }
}

/* Desktop optimization */
@media (min-width: 769px) {
    .position-relative.overflow-hidden.rounded-3 {
        height: 250px !important;
    }
    
    #eventMap {
        height: 250px !important;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php echo e($event->title); ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('events.index')); ?>">Eventi</a></li>
<li class="breadcrumb-item active"><?php echo e($event->title); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">

    <!-- Event Hero Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="position-relative overflow-hidden rounded-3" style="height: 250px;">
                <?php if($event->image_url): ?>
                    <img src="<?php echo e($event->image_url); ?>" alt="<?php echo e($event->title); ?>" class="position-absolute w-100 h-100" style="object-fit: cover;">
                    <div class="position-absolute w-100 h-100" style="background: linear-gradient(135deg, rgba(15, 98, 106, 0.7) 0%, rgba(12, 78, 85, 0.7) 100%);"></div>
                <?php else: ?>
                    <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0f626a 0%, #0c4e55 100%);">
                        <div class="text-center text-white">
                            <i class="ph ph-calendar f-s-48 mb-2"></i>
                            <div class="f-s-16 f-w-600"><?php echo e(Str::limit($event->title, 50)); ?></div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if($event->is_public): ?>
                    <span class="badge bg-success position-absolute top-0 end-0 m-2 f-s-10">
                        <i class="ph ph-globe me-1"></i> <?php echo e(__('events.event_public_badge')); ?>

                    </span>
                <?php else: ?>
                    <span class="badge bg-warning position-absolute top-0 end-0 m-2 f-s-10">
                        <i class="ph ph-lock me-1"></i> <?php echo e(__('events.event_private_badge')); ?>

                    </span>
                <?php endif; ?>

                <!-- Category Badge -->
                <?php if($event->category): ?>
                    <span class="badge <?php echo e($event->category_color_class); ?> position-absolute top-0 start-0 m-2 f-s-10">
                        <i class="ph ph-tag me-1"></i> <?php echo e(__('events.category_' . $event->category)); ?>

                    </span>
                <?php endif; ?>

                <div class="position-absolute bottom-0 start-0 text-white p-3 w-100" style="z-index: 2;">
                    <h2 class="f-s-18 fw-bold mb-2 text-white"><?php echo e(Str::limit($event->title, 60)); ?></h2>
                    <?php if($event->subtitle): ?>
                        <h6 class="text-white-50 mb-2 f-s-14"><?php echo e(Str::limit($event->subtitle, 40)); ?></h6>
                    <?php endif; ?>
                    <?php if($event->groups && $event->groups->count()): ?>
                        <div class="mb-2">
                            <small class="text-white mb-1 d-block f-s-10">
                                <i class="ph ph-link me-1"></i><?php echo e(__('events.associated_groups')); ?>:
                            </small>
                            <div class="d-flex flex-wrap gap-1">
                                <?php $__currentLoopData = $event->groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('groups.show', $group)); ?>" class="badge bg-primary text-decoration-none f-s-10">
                                        <i class="ph ph-users me-1"></i><?php echo e(Str::limit($group->name, 15)); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="d-flex align-items-center mb-1">
                        <i class="ph ph-calendar-check me-1 f-s-12"></i>
                        <span class="f-s-12"><?php echo e($event->start_datetime->format('d/m/Y, H:i')); ?></span>
                    </div>
                    <div class="d-flex align-items-center mb-1">
                        <i class="ph ph-map-pin me-1 f-s-12"></i>
                        <span class="f-s-12">
                            <?php if($event->is_online): ?>
                                <i class="ph ph-globe me-1"></i><?php echo e(__('events.online_event')); ?>

                                <?php if($event->online_url): ?>
                                    - <a href="<?php echo e($event->online_url); ?>" target="_blank" class="text-white text-decoration-underline f-s-10"><?php echo e(__('events.join_online')); ?></a>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php echo e(Str::limit($event->venue_name, 25)); ?>, <?php echo e($event->city); ?>

                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ph ph-user me-1 f-s-12"></i>
                        <span class="f-s-12"><?php echo e(__('events.organized_by')); ?> <a href="<?php echo e(route('user.show', $event->organizer)); ?>" class="text-decoration-none hover-effect"><?php echo e($event->organizer->getDisplayName()); ?></a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-12 col-lg-8">

            <!-- Private Event Notice -->
            <?php if(!$event->is_public): ?>
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center">
                        <i class="ph ph-info-circle me-3 fs-4"></i>
                        <div>
                            <h6 class="mb-1"><?php echo e(__('events.private_event_notice_title')); ?></h6>
                            <p class="mb-0"><?php echo e(__('events.private_event_notice_text')); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Event Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-info me-2"></i><?php echo e(__('events.event_information')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if($event->category): ?>
                        <div class="col-12 col-sm-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="ph ph-tag me-2 text-muted f-s-14"></i>
                                <div>
                                    <small class="text-muted d-block f-s-12"><?php echo e(__('events.category')); ?></small>
                                    <span class="badge <?php echo e($event->category_color_class); ?> f-s-12"><?php echo e(__('events.category_' . $event->category)); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if($event->entry_fee > 0): ?>
                        <div class="col-12 col-sm-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="ph ph-currency-eur me-2 text-muted f-s-14"></i>
                                <div>
                                    <small class="text-muted d-block f-s-12"><?php echo e(__('events.entry_fee')); ?></small>
                                    <span class="fw-semibold f-s-14"><?php echo e(number_format($event->entry_fee, 2)); ?>€</span>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="col-12 col-sm-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="ph ph-currency-eur me-2 text-muted f-s-14"></i>
                                <div>
                                    <small class="text-muted d-block f-s-12"><?php echo e(__('events.entry_fee')); ?></small>
                                    <span class="badge bg-success f-s-12"><?php echo e(__('common.free')); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Event Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-file-text me-2"></i><?php echo e(__('events.description_event')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <p class="f-s-14 lh-lg"><?php echo e($event->description); ?></p>

                    <?php if($event->tags): ?>
                        <div class="mt-3">
                            <h6 class="mb-2 f-s-14">Tags:</h6>
                            <div class="d-flex flex-wrap gap-1">
                                <?php $__currentLoopData = $event->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-light text-dark f-s-10">#<?php echo e($tag); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Requirements -->
            <?php if($event->requirements): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-list-checks me-2"></i><?php echo e(__('events.requirements')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <p class="mb-0 f-s-14"><?php echo e($event->requirements); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Event Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-clock me-2"></i><?php echo e(__('events.timeline_event')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <?php if($event->registration_deadline): ?>
                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1 f-s-14" style="color: rgb(15, 98, 106);"><?php echo e(__('events.deadline_registration')); ?></h6>
                        <p class="text-muted mb-0 f-s-12"><?php echo e($event->registration_deadline->format('d/m/Y, H:i')); ?></p>
                    </div>
                    <?php endif; ?>

                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1 f-s-14" style="color: rgb(15, 98, 106);"><?php echo e(__('events.start_event')); ?></h6>
                        <p class="text-muted mb-0 f-s-12"><?php echo e($event->start_datetime->format('d/m/Y, H:i')); ?></p>
                    </div>

                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1 f-s-14" style="color: rgb(15, 98, 106);"><?php echo e(__('events.end_event')); ?></h6>
                        <p class="text-muted mb-0 f-s-12"><?php echo e($event->end_datetime->format('d/m/Y, H:i')); ?></p>
                        <small class="text-muted f-s-10"><?php echo e(__('events.duration')); ?>: <?php echo e($event->duration); ?> <?php echo e(__('events.duration_hours')); ?></small>
                    </div>
                </div>
            </div>

            <!-- Participants -->
            <div class="card mb-4">
                <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <h5 class="mb-0">
                        <i class="ph ph-users me-2"></i><?php echo e(__('events.participants')); ?>

                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light-primary f-s-12">
                            <?php echo e($event->invitations->where('status', 'accepted')->count() + $event->requests->where('status', 'accepted')->count()); ?>

                            <?php if($event->max_participants): ?>
                                / <?php echo e($event->max_participants); ?>

                            <?php endif; ?>
                        </span>
                        <?php if(auth()->guard()->check()): ?>
                            <?php if($event->organizer_id === auth()->id()): ?>
                                <a href="<?php echo e(route('events.manage', $event)); ?>" class="btn btn-sm btn-light-primary">
                                    <i class="ph ph-gear me-1"></i>Gestisci
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                        $acceptedInvitations = $event->invitations->where('status', 'accepted');
                        $acceptedRequests = $event->requests->where('status', 'accepted');
                        $pendingInvitations = $event->invitations->where('status', 'pending');
                        $pendingRequests = $event->requests->where('status', 'pending');
                    ?>

                    <!-- Confirmed Participants -->
                    <?php if($acceptedInvitations->count() + $acceptedRequests->count() > 0): ?>
                        <div class="mb-4">
                            <h6 class="mb-3 text-success">
                                <i class="ph ph-check-circle me-2"></i><?php echo e(__('events.confirmed_participants')); ?>

                            </h6>
                            <div class="row">
                                <!-- Invited Participants -->
                                <?php $__currentLoopData = $acceptedInvitations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invitation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-12 col-sm-6 mb-3">
                                        <div class="card border-0">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3 overflow-hidden" style="width: 40px; height: 40px; font-weight: bold; font-size: 14px;">
                                                        <?php if($invitation->invitedUser->profile_photo): ?>
                                                            <img src="<?php echo e($invitation->invitedUser->profile_photo_url); ?>" alt="<?php echo e($invitation->invitedUser->getDisplayName()); ?>" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                                        <?php else: ?>
                                                            <?php echo e(substr($invitation->invitedUser->getDisplayName(), 0, 2)); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold f-s-14">
                                                            <a href="<?php echo e(route('user.show', $invitation->invitedUser)); ?>" class="text-decoration-none hover-effect">
                                                                <?php echo e($invitation->invitedUser->getDisplayName()); ?>

                                                            </a>
                                                        </h6>
                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                            <span class="badge bg-light-success f-s-10"><?php echo e(ucfirst($invitation->role)); ?></span>
                                                            <span class="badge bg-light-secondary"><?php echo e(__('events.participant_invited')); ?></span>
                                                        </div>
                                                        <?php if($invitation->compensation): ?>
                                                            <small class="text-muted">
                                                                <i class="ph ph-currency-eur me-1"></i><?php echo e($invitation->compensation); ?>

                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <!-- Requested Participants -->
                                <?php $__currentLoopData = $acceptedRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-12 col-sm-6 mb-3">
                                        <div class="card card-light-success border-0">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3 overflow-hidden" style="width: 40px; height: 40px; font-weight: bold; font-size: 14px;">
                                                        <?php if($request->user->profile_photo): ?>
                                                            <img src="<?php echo e($request->user->profile_photo_url); ?>" alt="<?php echo e($request->user->getDisplayName()); ?>" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                                        <?php else: ?>
                                                            <?php echo e(substr($request->user->getDisplayName(), 0, 2)); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold f-s-14">
                                                            <a href="<?php echo e(route('user.show', $request->user)); ?>" class="text-decoration-none hover-effect">
                                                                <?php echo e($request->user->getDisplayName()); ?>

                                                            </a>
                                                        </h6>
                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                            <span class="badge bg-success f-s-10"><?php echo e(ucfirst($request->requested_role)); ?></span>
                                                            <span class="badge bg-light-warning f-s-10"><?php echo e(__('events.participant_applied')); ?></span>
                                                        </div>
                                                        <?php if($request->experience): ?>
                                                            <small class="text-muted f-s-10">
                                                                <i class="ph ph-star me-1"></i><?php echo e(Str::limit($request->experience, 50)); ?>

                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Pending Participants -->
                    <?php if($pendingInvitations->count() + $pendingRequests->count() > 0): ?>
                        <div class="mb-4">
                            <h6 class="mb-3 text-warning">
                                <i class="ph ph-clock me-2"></i><?php echo e(__('events.pending_participants')); ?>

                            </h6>
                            <div class="row">
                                <!-- Pending Invitations -->
                                <?php $__currentLoopData = $pendingInvitations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invitation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-12 col-sm-6 mb-3">
                                        <div class="card card-light-warning border-0">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-light-warning text-white d-flex align-items-center justify-content-center me-3 overflow-hidden" style="width: 40px; height: 40px; font-weight: bold; font-size: 14px;">
                                                        <?php if($invitation->invitedUser->profile_photo): ?>
                                                            <img src="<?php echo e($invitation->invitedUser->profile_photo_url); ?>" alt="<?php echo e($invitation->invitedUser->getDisplayName()); ?>" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                                        <?php else: ?>
                                                            <?php echo e(substr($invitation->invitedUser->getDisplayName(), 0, 2)); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold f-s-14">
                                                            <a href="<?php echo e(route('user.show', $invitation->invitedUser)); ?>" class="text-decoration-none hover-effect">
                                                                <?php echo e($invitation->invitedUser->getDisplayName()); ?>

                                                            </a>
                                                        </h6>
                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                            <span class="badge bg-light-warning f-s-10"><?php echo e(ucfirst($invitation->role)); ?></span>
                                                            <span class="badge bg-light-secondary f-s-10"><?php echo e(__('events.participant_invited')); ?></span>
                                                        </div>
                                                        <?php if($invitation->expires_at): ?>
                                                            <small class="text-muted f-s-10">
                                                                <i class="ph ph-timer me-1"></i>Scade <?php echo e($invitation->expires_at->diffForHumans()); ?>

                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <!-- Pending Requests -->
                                <?php $__currentLoopData = $pendingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-12 col-sm-6 mb-3">
                                        <div class="card card-light-warning border-0">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center me-3 overflow-hidden" style="width: 40px; height: 40px; font-weight: bold; font-size: 14px;">
                                                        <?php if($request->user->profile_photo): ?>
                                                            <img src="<?php echo e($request->user->profile_photo_url); ?>" alt="<?php echo e($request->user->getDisplayName()); ?>" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                                        <?php else: ?>
                                                            <?php echo e(substr($request->user->getDisplayName(), 0, 2)); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold f-s-14">
                                                            <a href="<?php echo e(route('user.show', $request->user)); ?>" class="text-decoration-none hover-effect">
                                                                <?php echo e($request->user->getDisplayName()); ?>

                                                            </a>
                                                        </h6>
                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                            <span class="badge bg-light-warning f-s-10"><?php echo e(ucfirst($request->requested_role)); ?></span>
                                                            <span class="badge bg-light-warning f-s-10"><?php echo e(__('events.participant_applied')); ?></span>
                                                        </div>
                                                        <?php if($request->message): ?>
                                                            <small class="text-muted f-s-10">
                                                                <i class="ph ph-chat-circle me-1"></i><?php echo e(Str::limit($request->message, 50)); ?>

                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- No Participants -->
                    <?php if($acceptedInvitations->count() + $acceptedRequests->count() + $pendingInvitations->count() + $pendingRequests->count() === 0): ?>
                        <div class="text-center py-4">
                            <i class="ph ph-users-three display-4 text-muted mb-3"></i>
                            <p class="text-muted mb-3"><?php echo e(__('events.no_participants')); ?></p>
                            <?php if(auth()->guard()->check()): ?>
                                <?php if($canApply): ?>
                                    <button class="btn btn-light-success" data-bs-toggle="modal" data-bs-target="#applyModal">
                                        <i class="ph ph-hand-waving me-2"></i><?php echo e(__('events.first_participant')); ?>

                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Role Statistics -->
                    <?php if($event->invitations->count() + $event->requests->count() > 0): ?>
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="mb-3"><?php echo e(__('events.participant_stats')); ?></h6>
                            <div class="row g-2">
                                <?php
                                    $roleStats = collect();
                                    foreach($event->invitations as $inv) {
                                        $roleStats->put($inv->role, $roleStats->get($inv->role, 0) + 1);
                                    }
                                    foreach($event->requests as $req) {
                                        $roleStats->put($req->requested_role, $roleStats->get($req->requested_role, 0) + 1);
                                    }
                                ?>
                                <?php $__currentLoopData = $roleStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-auto">
                                        <span class="badge bg-light-primary"><?php echo e(ucfirst($role)); ?>: <?php echo e($count); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Festival Information -->
            <?php if($event->isFestival() || $event->isPartOfFestival()): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-trophy me-2"></i><?php echo e(__('events.festival_info')); ?>

                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if($event->isFestival()): ?>
                            <!-- Questo è un festival - mostra gli eventi collegati -->
                            <div class="mb-3">
                                <h6 class="mb-3 text-primary">
                                    <i class="ph ph-calendar-check me-2"></i><?php echo e(__('events.festival_events_list')); ?>

                                </h6>
                                <?php
                                    $festivalEvents = $event->getFestivalEventModels();
                                ?>
                                <?php if($festivalEvents->count() > 0): ?>
                                    <div class="row">
                                        <?php $__currentLoopData = $festivalEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $festivalEvent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-md-6 mb-3">
                                                <div class="card card-light-primary border-0">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                                                <i class="ph ph-calendar f-s-18"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1 fw-bold"><?php echo e($festivalEvent->title); ?></h6>
                                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                                    <span class="badge bg-primary"><?php echo e($festivalEvent->start_datetime->format('d/m/Y')); ?></span>
                                                                    <span class="badge bg-light-secondary"><?php echo e($festivalEvent->city); ?></span>
                                                                </div>
                                                                <small class="text-muted">
                                                                    <i class="ph ph-user me-1"></i><?php echo e($festivalEvent->organizer->getDisplayName()); ?>

                                                                </small>
                                                            </div>
                                                            <div class="ms-auto">
                                                                <a href="<?php echo e(route('events.show', $festivalEvent)); ?>" class="btn btn-sm btn-light-primary">
                                                                    <i class="ph ph-eye me-1"></i><?php echo e(__('events.view')); ?>

                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-3">
                                        <i class="ph ph-calendar-x display-4 text-muted mb-3"></i>
                                        <p class="text-muted mb-0"><?php echo e(__('events.no_festival_events')); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php elseif($event->isPartOfFestival()): ?>
                            <!-- Questo evento fa parte di un festival - mostra il festival -->
                            <div class="mb-3">
                                <h6 class="mb-3 text-primary">
                                    <i class="ph ph-trophy me-2"></i><?php echo e(__('events.part_of_festival')); ?>

                                </h6>
                                <?php
                                    $festival = $event->festival;
                                ?>
                                <?php if($festival): ?>
                                    <div class="card card-light-primary border-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                                    <i class="ph ph-trophy f-s-18"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold"><?php echo e($festival->title); ?></h6>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <span class="badge bg-primary"><?php echo e($festival->start_datetime->format('d/m/Y')); ?></span>
                                                        <span class="badge bg-light-secondary"><?php echo e($festival->city); ?></span>
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="ph ph-user me-1"></i><?php echo e($festival->organizer->getDisplayName()); ?>

                                                    </small>
                                                </div>
                                                <div class="ms-auto">
                                                    <a href="<?php echo e(route('events.show', $festival)); ?>" class="btn btn-sm btn-light-primary">
                                                        <i class="ph ph-eye me-1"></i><?php echo e(__('events.view_festival')); ?>

                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Posizioni d'Ingaggio -->
            <?php
                $gigPositions = $event->gig_positions;
                if (is_string($gigPositions)) {
                    $gigPositions = json_decode($gigPositions, true);
                }
            ?>
            <?php if($gigPositions && is_array($gigPositions) && count($gigPositions) > 0): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-briefcase me-2"></i>Posizioni d'Ingaggio Aperte
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-border-success mb-3" role="alert">
                        <h6>
                            <i class="ph ph-info-circle f-s-18 me-2 text-success"></i>
                            Opportunità di Collaborazione
                        </h6>
                        <p class="mb-0">
                            Questo evento ha posizioni d'ingaggio aperte. Se sei interessato, contatta l'organizzatore.
                        </p>
                    </div>

                    <!-- Scadenza Risposte -->
                    <?php if($event->invitation_deadline): ?>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-border-info" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="ph ph-clock f-s-18 me-2 text-info"></i>
                                    <div>
                                        <strong>Risposte entro il:</strong>
                                        <?php echo e($event->invitation_deadline->format('d/m/Y H:i')); ?>

                                        <?php
                                            $daysLeft = now()->diffInDays($event->invitation_deadline, false);
                                        ?>
                                        <?php if($daysLeft > 0): ?>
                                            <span class="badge bg-info ms-2">
                                                <?php echo e($daysLeft); ?> <?php echo e($daysLeft == 1 ? 'giorno' : 'giorni'); ?> rimasti
                                            </span>
                                        <?php elseif($daysLeft == 0): ?>
                                            <span class="badge bg-warning ms-2">
                                                Scade oggi!
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary ms-2">
                                                <?php echo e(__('invitations.expired')); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row">
                        <?php $__currentLoopData = $gigPositions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $position): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-12 mb-3">
                            <div class="card card-light-success">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="ph ph-briefcase me-2"></i>
                                            <?php echo e(__('events.gig_type_' . $position['type'])); ?>

                                        </h6>
                                        <span class="badge bg-success">
                                            <?php echo e($position['quantity']); ?> <?php echo e($position['quantity'] == 1 ? 'posizione' : 'posizioni'); ?>

                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php if(!empty($position['language'])): ?>
                                        <div class="col-md-6 mb-2">
                                            <small class="text-muted">
                                                <i class="ph ph-translate me-1"></i>
                                                <?php echo e(__('common.language_selector')); ?>: <?php echo e(__('events.language_' . $position['language'])); ?>

                                            </small>
                                        </div>
                                        <?php endif; ?>

                                        <?php if(!empty($position['cachet_amount'])): ?>
                                        <div class="col-md-6 mb-2">
                                            <small class="text-success">
                                                <i class="ph ph-currency-eur me-1"></i>
                                                Cachet: <?php echo e($position['cachet_amount']); ?> <?php echo e($position['cachet_currency']); ?>

                                            </small>
                                        </div>
                                        <?php endif; ?>

                                        <?php if(!empty($position['travel_max'])): ?>
                                        <div class="col-md-6 mb-2">
                                            <small class="text-info">
                                                <i class="ph ph-airplane me-1"></i>
                                                Viaggio: fino a <?php echo e($position['travel_max']); ?> <?php echo e($position['cachet_currency'] ?? 'EUR'); ?>

                                            </small>
                                        </div>
                                        <?php endif; ?>

                                        <?php if(!empty($position['accommodation_details'])): ?>
                                        <div class="col-12 mt-2">
                                            <small class="text-muted">
                                                <i class="ph ph-house me-1"></i>
                                                <strong>Vitto e Alloggio:</strong><br>
                                                <?php echo e($position['accommodation_details']); ?>

                                            </small>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if(auth()->guard()->check()): ?>
                                        <?php if($event->organizer_id !== auth()->id()): ?>
                                        <div class="mt-3">
                                            <button class="btn btn-sm btn-outline-success" onclick="contactOrganizer('<?php echo e($event->organizer->email); ?>', '<?php echo e(__('events.gig_type_' . $position['type'])); ?>')">
                                                <i class="ph ph-envelope me-1"></i>
                                                Contatta <?php echo e(__('events.organizer')); ?>

                                            </button>
                                        </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Location Map or Online Event Info -->
            <?php if($event->is_online): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-globe me-2"></i><?php echo e(__('events.online_event')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if($event->online_url): ?>
                        <div class="col-12 col-sm-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="ph ph-link me-2 text-muted f-s-14"></i>
                                <div>
                                    <small class="text-muted d-block f-s-12"><?php echo e(__('events.online_url')); ?></small>
                                    <a href="<?php echo e($event->online_url); ?>" target="_blank" class="text-decoration-none f-s-14">
                                        <?php echo e(__('events.join_online')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($event->timezone): ?>
                        <div class="col-12 col-sm-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="ph ph-clock me-2 text-muted f-s-14"></i>
                                <div>
                                    <small class="text-muted d-block f-s-12"><?php echo e(__('events.timezone')); ?></small>
                                    <span class="fw-semibold f-s-14"><?php echo e($event->timezone); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="alert alert-info">
                        <i class="ph ph-info me-2"></i>
                        <strong><?php echo e(__('events.online_event_notice')); ?></strong>
                    </div>
                </div>
            </div>
            <?php elseif($event->latitude && $event->longitude): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-map-pin me-2"></i><?php echo e(__('events.location')); ?>

                    </h5>
                </div>
                <div class="card-body p-0">
                    <div id="eventMap" style="height: 250px; border-radius: 10px; overflow: hidden;"></div>
                </div>
                <div class="card-footer">
                    <p class="mb-0">
                        <strong><?php echo e($event->venue_name); ?></strong><br>
                        <?php echo e($event->venue_address); ?><br>
                        <?php echo e($event->city); ?>, <?php echo e($event->country); ?>

                    </p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-12 col-lg-4">

            <!-- Action Buttons -->
            <div class="position-sticky" style="top: 20px;">

                <!-- Posizioni d'Ingaggio Riepilogo -->
                <?php if($gigPositions && is_array($gigPositions) && count($gigPositions) > 0): ?>
                <div class="card mb-4">
                    <div class="card-header bg-light-success">
                        <h6 class="mb-0">
                            <i class="ph ph-briefcase me-2"></i>Posizioni Aperte
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="ph ph-info-circle me-1"></i>
                                <?php echo e(count($gigPositions)); ?> <?php echo e(count($gigPositions) == 1 ? 'posizione' : 'posizioni'); ?> d'ingaggio disponibili
                            </small>
                        </div>

                        <?php $__currentLoopData = $gigPositions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $position): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="f-s-14">
                                <i class="ph ph-briefcase me-1"></i>
                                <?php echo e(__('events.gig_type_' . $position['type'])); ?>

                            </span>
                            <span class="badge bg-success f-s-12">
                                <?php echo e($position['quantity']); ?>

                            </span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if(auth()->guard()->check()): ?>
                            <?php if($event->organizer_id !== auth()->id()): ?>
                            <div class="mt-3">
                                <button class="btn btn-sm btn-success w-100" onclick="contactOrganizer('<?php echo e($event->organizer->email); ?>', 'Posizioni d\'Ingaggio')">
                                    <i class="ph ph-envelope me-1"></i>
                                    Contatta <?php echo e(__('events.organizer')); ?>

                                </button>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Scadenza Inviti -->
                <?php if($event->invitation_deadline): ?>
                <div class="card mb-4">
                    <div class="card-header bg-light-info">
                        <h6 class="mb-0">
                            <i class="ph ph-clock me-2"></i>Scadenza Inviti
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div class="f-s-18 f-w-600 text-info mb-2">
                                <?php echo e($event->invitation_deadline->format('d/m/Y')); ?>

                            </div>
                            <div class="f-s-14 text-muted mb-2">
                                <?php echo e($event->invitation_deadline->format('H:i')); ?>

                            </div>
                            <?php
                                $daysLeft = now()->diffInDays($event->invitation_deadline, false);
                            ?>
                            <?php if($daysLeft > 0): ?>
                                <span class="badge bg-info">
                                    <?php echo e($daysLeft); ?> <?php echo e($daysLeft == 1 ? 'giorno' : 'giorni'); ?> rimasti
                                </span>
                            <?php elseif($daysLeft == 0): ?>
                                <span class="badge bg-warning">
                                    Scade oggi!
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <?php echo e(__('invitations.expired')); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Statistiche Evento -->
                <div class="card mb-4">
                    <div class="card-header bg-light-primary">
                        <h6 class="mb-0">
                            <i class="ph ph-chart-line me-2"></i>Statistiche
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="ph ph-eye f-s-24 text-primary mb-2"></i>
                                    <div class="f-s-18 f-w-600"><?php echo e(number_format($event->view_count ?? 0)); ?></div>
                                    <small class="text-muted">Visualizzazioni</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="ph ph-heart f-s-24 text-danger mb-2"></i>
                                    <div class="f-s-18 f-w-600"><?php echo e(number_format($event->like_count ?? 0)); ?></div>
                                    <small class="text-muted">Mi piace</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <?php if(auth()->guard()->check()): ?>
                            <?php if($event->organizer_id === auth()->id() || auth()->user()->hasAnyRole(['admin', 'moderator'])): ?>
                                <!-- Organizer/Admin Actions -->
                                <?php if($event->organizer_id === auth()->id()): ?>
                                    <a href="<?php echo e(route('events.manage', $event)); ?>" class="btn btn-light-primary w-100 mb-2">
                                        <i class="ph ph-gear me-2"></i><?php echo e(__('events.manage_event_action')); ?>

                                    </a>
                                    <a href="<?php echo e(route('events.edit', $event)); ?>" class="btn btn-light-secondary w-100 mb-2">
                                        <i class="ph ph-pencil me-2"></i><?php echo e(__('events.edit_event_action')); ?>

                                    </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('events.manage.own')): ?>
                                    <?php if(Auth::user()->hasRole(['admin', 'moderator']) || $event->organizer_id === Auth::id()): ?>
                                        <button class="btn btn-light-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="ph ph-trash me-2"></i><?php echo e(__('events.delete_event_action')); ?>

                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>

                            <?php elseif($hasInvitation): ?>
                                <!-- User has invitation -->
                                <?php if($userInvitation->status === 'pending'): ?>
                                    <div class="alert alert-info mb-3">
                                        <i class="ph ph-envelope me-2"></i>Hai ricevuto un invito per questo evento!
                                    </div>
                                    <a href="<?php echo e(route('invitations.index')); ?>" class="btn btn-light-primary w-100 mb-2">
                                        <i class="ph ph-envelope-open me-2"></i>Gestisci Invito
                                    </a>
                                <?php elseif($userInvitation->status === 'accepted'): ?>
                                    <div class="alert alert-success mb-3">
                                        <i class="ph ph-check-circle me-2"></i>Hai accettato l'invito! Sei un partecipante confermato.
                                    </div>
                                <?php elseif($userInvitation->status === 'declined'): ?>
                                    <div class="alert alert-secondary mb-3">
                                        <i class="ph ph-x-circle me-2"></i>Hai rifiutato l'invito per questo evento.
                                    </div>
                                <?php endif; ?>

                            <?php elseif($hasRequest): ?>
                                <!-- User has request -->
                                <?php if($userRequest->status === 'pending'): ?>
                                    <div class="alert alert-warning mb-3">
                                        <i class="ph ph-clock me-2"></i>La tua richiesta è in attesa di approvazione.
                                    </div>
                                    <form action="<?php echo e(route('requests.cancel', $userRequest)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-light-danger w-100">
                                            <i class="ph ph-x me-2"></i>Cancella Richiesta
                                        </button>
                                    </form>
                                <?php elseif($userRequest->status === 'accepted'): ?>
                                    <div class="alert alert-success mb-3">
                                        <i class="ph ph-party-popper me-2"></i>La tua richiesta è stata accettata! Sei un partecipante confermato.
                                    </div>
                                <?php elseif($userRequest->status === 'declined'): ?>
                                    <div class="alert alert-danger mb-3">
                                        <i class="ph ph-x-circle me-2"></i>La tua richiesta è stata rifiutata.
                                    </div>
                                <?php endif; ?>

                            <?php elseif($canApply): ?>
                                <!-- User can apply -->
                                <button class="btn btn-light-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#applyModal">
                                    <i class="ph ph-hand-waving me-2"></i>Richiedi Partecipazione
                                </button>
                                <small class="text-muted"><?php echo e(__('events.accepts_requests')); ?></small>

                            <?php else: ?>
                                <!-- Cannot apply -->
                                <div class="alert alert-secondary mb-3">
                                    <i class="ph ph-lock me-2"></i>Non puoi richiedere di partecipare a questo evento.
                                </div>
                            <?php endif; ?>

                            <!-- <?php echo e(__('wishlist.wishlist')); ?> Button -->
                            <button class="btn btn-outline-danger w-100 mb-2 wishlist-toggle" data-event-id="<?php echo e($event->id); ?>" title="Aggiungi/<?php echo e(__('wishlist.remove_from_wishlist')); ?>">
                                <img src="<?php echo e(asset('assets/images/like.png')); ?>" alt="Like" style="width: 16px; height: 16px; filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);">
                                <span class="wishlist-text">Aggiungi alla <?php echo e(__('wishlist.wishlist')); ?></span>
                            </button>

                            <!-- Always show share button -->
                            <button class="btn btn-light-primary w-100 mt-2" onclick="shareEvent()">
                                <i class="ph ph-share me-2"></i>Condividi <?php echo e(__('invitations.event')); ?>

                            </button>

                            <!-- Report Button -->
                            <?php if (isset($component)) { $__componentOriginalcab7032bfdfb17b0d85d7225950dd852 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcab7032bfdfb17b0d85d7225950dd852 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.report-button','data' => ['content' => $event,'type' => 'event','class' => 'w-100 mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('report-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($event),'type' => 'event','class' => 'w-100 mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcab7032bfdfb17b0d85d7225950dd852)): ?>
<?php $attributes = $__attributesOriginalcab7032bfdfb17b0d85d7225950dd852; ?>
<?php unset($__attributesOriginalcab7032bfdfb17b0d85d7225950dd852); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcab7032bfdfb17b0d85d7225950dd852)): ?>
<?php $component = $__componentOriginalcab7032bfdfb17b0d85d7225950dd852; ?>
<?php unset($__componentOriginalcab7032bfdfb17b0d85d7225950dd852); ?>
<?php endif; ?>

                        <?php else: ?>
                            <!-- Not logged in -->
                            <div class="alert alert-info mb-3">
                                <i class="ph ph-sign-in me-2"></i><?php echo e(__('auth.login')); ?> per partecipare a questo evento
                            </div>
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-light-primary w-100">
                                <i class="ph ph-sign-in me-2"></i><?php echo e(__('auth.login')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Event Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-info me-2"></i><?php echo e(__('events.event_info')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1" style="color: rgb(15, 98, 106);"><?php echo e(__('events.date_time')); ?></h6>
                        <p class="mb-0"><?php echo e($event->start_datetime->format('d F Y')); ?></p>
                        <small class="text-muted"><?php echo e($event->start_datetime->format('H:i')); ?> - <?php echo e($event->end_datetime->format('H:i')); ?></small>
                    </div>

                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1" style="color: rgb(15, 98, 106);"><?php echo e(__('events.duration')); ?></h6>
                        <p class="mb-0"><?php echo e($event->duration); ?> <?php echo e(__('events.duration_hours')); ?></p>
                    </div>

                    <?php if($event->entry_fee > 0): ?>
                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1" style="color: rgb(15, 98, 106);"><?php echo e(__('events.cost')); ?></h6>
                        <p class="mb-0">€<?php echo e(number_format($event->entry_fee, 2)); ?></p>
                    </div>
                    <?php else: ?>
                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(40, 167, 69) !important;">
                        <h6 class="mb-1" style="color: rgb(40, 167, 69);"><?php echo e(__('events.free')); ?></h6>
                        <p class="mb-0"><?php echo e(__('events.no_fee')); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if($event->max_participants): ?>
                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1" style="color: rgb(15, 98, 106);"><?php echo e(__('events.participants')); ?></h6>
                        <p class="mb-0">
                            <?php echo e($event->invitations->where('status', 'accepted')->count() + $event->requests->where('status', 'accepted')->count()); ?> / <?php echo e($event->max_participants); ?>

                        </p>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-light-primary" style="width: <?php echo e((($event->invitations->where('status', 'accepted')->count() + $event->requests->where('status', 'accepted')->count()) / $event->max_participants) * 100); ?>%"></div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($event->registration_deadline): ?>
                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1" style="color: rgb(15, 98, 106);"><?php echo e(__('events.deadline_registration')); ?></h6>
                        <p class="mb-0"><?php echo e($event->registration_deadline->format('d F Y, H:i')); ?></p>
                        <?php if($event->registration_deadline > now()): ?>
                            <small style="color: rgb(40, 167, 69);"><?php echo e($event->registration_deadline->diffForHumans()); ?></small>
                        <?php else: ?>
                            <small style="color: rgb(220, 53, 69);"><?php echo e(__('events.expired')); ?></small>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Organizer Info -->
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center me-3 overflow-hidden" style="width: 40px; height: 40px; font-weight: bold;">
                            <?php if($event->organizer->profile_photo): ?>
                                <img src="<?php echo e($event->organizer->profile_photo_url); ?>" alt="<?php echo e($event->organizer->getDisplayName()); ?>" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <?php echo e(substr($event->organizer->getDisplayName(), 0, 2)); ?>

                            <?php endif; ?>
                        </div>
                        <div>
                            <h6 class="mb-0 text-white"><?php echo e($event->organizer->getDisplayName()); ?></h6>
                            <small class="text-white-50"><?php echo e(__('events.organizer')); ?></small>
                        </div>
                    </div>
                    <?php if($event->organizer->bio): ?>
                        <p class="small text-white-75"><?php echo e(Str::limit($event->organizer->bio, 100)); ?></p>
                    <?php endif; ?>

                    <?php if(auth()->guard()->check()): ?>
                        <?php if($event->organizer_id !== auth()->id()): ?>
                            <button class="btn btn-light btn-sm w-100">
                                <i class="ph ph-chat-circle me-2"></i>Contatta <?php echo e(__('events.organizer')); ?>

                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Event Stats -->
            <div class="row g-3">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body text-center bg-light-primary">
                            <div class="fs-5 fw-bold"><?php echo e($event->invitations->count()); ?></div>
                            <small><?php echo e(__('events.invitations_sent')); ?></small>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-body text-center bg-light-success">
                            <div class="fs-5 fw-bold"><?php echo e($event->requests->count()); ?></div>
                            <small><?php echo e(__('events.requests_received')); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Apply Modal -->
<?php if(auth()->guard()->check()): ?>
<?php if($canApply): ?>
<div class="modal fade" id="applyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-success">
                <h5 class="modal-title text-success">
                    <i class="ph ph-hand-waving me-2"></i><?php echo e(__('events.participant_apply_title')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('events.apply', $event)); ?>" method="POST" id="applyForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <!-- Event Info -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="ph ph-info-circle me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-1"><?php echo e($event->title); ?></h6>
                                <p class="mb-0 small">
                                    <i class="ph ph-calendar me-1"></i><?php echo e($event->start_datetime->format('d F Y, H:i')); ?><br>
                                    <i class="ph ph-map-pin me-1"></i>
                                    <?php if($event->is_online): ?>
                                        <i class="ph ph-globe me-1"></i><?php echo e(__('events.online_event')); ?>

                                    <?php else: ?>
                                        <?php echo e($event->venue_name); ?>, <?php echo e($event->city); ?>

                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="ph ph-user-circle me-2"></i><?php echo e(__('events.participant_apply_role')); ?> *
                        </label>
                        <select name="requested_role" class="form-select form-select-lg" required>
                            <option value=""><?php echo e(__('events.participant_apply_role_help')); ?></option>
                            <?php if(auth()->user()->hasRole('poet')): ?>
                                <option value="performer" data-description="Interpreterai le tue poesie o quelle di altri artisti">
                                    🎭 Performer
                                </option>
                            <?php endif; ?>
                            <?php if(auth()->user()->hasRole('judge')): ?>
                                <option value="judge" data-description="Valuterai le performance degli artisti">
                                    ⚖️ Judge
                                </option>
                            <?php endif; ?>
                            <?php if(auth()->user()->hasRole('technician')): ?>
                                <option value="technician" data-description="Gestirai audio, luci e supporto tecnico">
                                    🔧 Technician
                                </option>
                            <?php endif; ?>
                            <option value="host" data-description="Presenterai l'evento e gestirai il pubblico">
                                🎤 Host
                            </option>
                        </select>
                        <div id="roleDescription" class="form-text mt-2"></div>
                    </div>

                    <!-- Personal Message -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="ph ph-chat-circle-text me-2"></i><?php echo e(__('events.participant_apply_message')); ?> *
                        </label>
                        <textarea name="message" class="form-control" rows="4"
                                  placeholder="<?php echo e(__('events.participant_apply_message_help')); ?>" required></textarea>
                        <div class="form-text">
                            <i class="ph ph-lightbulb me-1"></i><?php echo e(__('events.participant_apply_message_suggestion')); ?>

                        </div>
                    </div>

                    <!-- Experience -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="ph ph-star me-2"></i><?php echo e(__('events.participant_apply_experience')); ?>

                        </label>
                        <textarea name="experience" class="form-control" rows="3"
                                  placeholder="<?php echo e(__('events.participant_apply_experience_help')); ?>"></textarea>
                        <div class="form-text">
                            <i class="ph ph-info me-1"></i><?php echo e(__('events.participant_apply_experience_optional')); ?>

                        </div>
                    </div>

                    <!-- Portfolio Links -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="ph ph-link me-2"></i><?php echo e(__('events.participant_links')); ?> (Opzionale)
                        </label>
                        <div id="portfolioLinks">
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    <i class="ph ph-link"></i>
                                </span>
                                <input type="url" name="portfolio_links[]" class="form-control"
                                       placeholder="<?php echo e(__('events.participant_links_placeholder')); ?>">
                                <button type="button" class="btn btn-outline-secondary" onclick="addPortfolioLink()">
                                    <i class="ph ph-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-text">
                            <i class="ph ph-video-camera me-1"></i><?php echo e(__('events.participant_links_help')); ?>

                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="alert alert-warning">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="termsAccepted" required>
                            <label class="form-check-label" for="termsAccepted">
                                                                <small>
                                    <?php echo e(__('events.participant_terms_accept')); ?>

                                </small>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="ph ph-x me-2"></i><?php echo e(__('events.participant_apply_cancel')); ?>

                    </button>
                    <button type="submit" class="btn btn-light-success" id="submitBtn">
                        <i class="ph ph-paper-plane me-2"></i><?php echo e(__('events.participant_apply_send')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<!-- Delete Modal -->
<?php if(auth()->guard()->check()): ?>
<?php if($event->organizer_id === auth()->id() || auth()->user()->hasAnyRole(['admin', 'moderator'])): ?>
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="ph ph-warning me-2"></i>Elimina <?php echo e(__('invitations.event')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Sei sicuro di voler eliminare questo evento?</p>
                <p class="text-muted">Questa azione non può essere annullata. Tutti i partecipanti riceveranno una notifica di cancellazione.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Annulla</button>
                <form action="<?php echo e(route('events.destroy', $event)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-light-danger">
                        <i class="ph ph-trash me-2"></i>Elimina Definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php if($event->latitude && $event->longitude): ?>
<script src="<?php echo e(asset('assets/vendor/leafletmaps/leaflet.js')); ?>"></script>
<script>
// Clear event draft from localStorage if coming from successful creation
<?php if(session('success') && strpos(session('success'), 'creato') !== false): ?>
    localStorage.removeItem('eventDraft');
    console.log('Event creation draft cleared from localStorage');
<?php endif; ?>
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('eventMap').setView([<?php echo e($event->latitude); ?>, <?php echo e($event->longitude); ?>], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add marker for the event
    L.marker([<?php echo e($event->latitude); ?>, <?php echo e($event->longitude); ?>])
        .addTo(map)
        .bindPopup(`
            <div class="p-2">
                <h6><?php echo e($event->venue_name); ?></h6>
                <p class="mb-0"><?php echo e($event->venue_address); ?></p>
            </div>
        `)
        .openPopup();
});
</script>
<?php endif; ?>

<script>
// Role description functionality
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.querySelector('select[name="requested_role"]');
    const roleDescription = document.getElementById('roleDescription');

    if (roleSelect && roleDescription) {
        roleSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const description = selectedOption.getAttribute('data-description');

            if (description) {
                roleDescription.innerHTML = `<i class="ph ph-info-circle me-1"></i>${description}`;
                roleDescription.style.display = 'block';
            } else {
                roleDescription.style.display = 'none';
            }
        });
    }

    // Form validation
    const applyForm = document.getElementById('applyForm');
    const submitBtn = document.getElementById('submitBtn');

    if (applyForm && submitBtn) {
        applyForm.addEventListener('submit', function(e) {
            const message = document.querySelector('textarea[name="message"]').value.trim();
            const role = document.querySelector('select[name="requested_role"]').value;
            const terms = document.getElementById('termsAccepted').checked;

                        if (!role) {
                e.preventDefault();
                showNotification('<?php echo e(__("events.participant_apply_validation_role")); ?>', 'error');
                return;
            }

            if (message.length < 10) {
                e.preventDefault();
                showNotification('<?php echo e(__("events.participant_apply_validation_message")); ?>', 'error');
                return;
            }

            if (!terms) {
                e.preventDefault();
                showNotification('<?php echo e(__("events.participant_apply_validation_terms")); ?>', 'error');
                return;
            }

            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ph ph-spinner ph-spin me-2"></i><?php echo e(__("events.participant_apply_sending")); ?>';
        });
    }
});

// Add portfolio link functionality
function addPortfolioLink() {
    const portfolioLinks = document.getElementById('portfolioLinks');
    const newLink = document.createElement('div');
    newLink.className = 'input-group mb-2';
    newLink.innerHTML = `
        <span class="input-group-text">
            <i class="ph ph-link"></i>
        </span>
                                        <input type="url" name="portfolio_links[]" class="form-control"
                                       placeholder="<?php echo e(__('events.participant_links_placeholder')); ?>">
        <button type="button" class="btn btn-outline-danger" onclick="removePortfolioLink(this)">
            <i class="ph ph-minus"></i>
        </button>
    `;
    portfolioLinks.appendChild(newLink);
}

function removePortfolioLink(button) {
    button.closest('.input-group').remove();
}

function shareEvent() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo e($event->title); ?>',
            text: '<?php echo e(Str::limit($event->description, 100)); ?>',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href);
        showNotification('Link copiato negli appunti!', 'success');
    }
}

function showNotification(message, type) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    alert.style.top = '20px';
    alert.style.right = '20px';
    alert.style.zIndex = '9999';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 5000);
}

// <?php echo e(__('wishlist.wishlist')); ?> è gestita globalmente da WishlistManager
// Non serve codice duplicato qui

// Funzione per contattare l'organizzatore per posizioni d'ingaggio
function contactOrganizer(email, positionType) {
    const subject = encodeURIComponent(`Interesse per posizione: ${positionType} - <?php echo e(__('invitations.event')); ?>: <?php echo e($event->title); ?>`);
    const body = encodeURIComponent(`Ciao!

Sono interessato alla posizione di ${positionType} per il tuo evento "<?php echo e($event->title); ?>" che si terrà il <?php echo e($event->start_datetime->format('d/m/Y H:i')); ?>.

Potresti fornirmi maggiori dettagli su:
- Requisiti specifici per la posizione
- Modalità di selezione
- Contratto e condizioni

Grazie per l'attenzione!

Cordiali saluti,
<?php echo e(auth()->user()->name ?? 'Un utente'); ?>`);

    const mailtoLink = `mailto:${email}?subject=${subject}&body=${body}`;
    window.open(mailtoLink);
}

// Incrementa visualizzazioni evento
document.addEventListener('DOMContentLoaded', function() {
    // Incrementa solo se l'utente non è l'organizer dell'evento
    <?php if(Auth::check() && Auth::id() !== $event->organizer_id): ?>
        incrementEventView();
    <?php elseif(!Auth::check()): ?>
        incrementEventView();
    <?php endif; ?>
});

function incrementEventView() {
    fetch('/api/social/views/increment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            viewable_type: 'event',
            viewable_id: <?php echo e($event->id); ?>

        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Aggiorna il contatore delle visualizzazioni nella pagina
            const viewCountElement = document.querySelector('.f-s-18.f-w-600');
            if (viewCountElement) {
                viewCountElement.textContent = data.view_count.toLocaleString();
            }
        }
    })
    .catch(error => {
        console.error('Errore incremento visualizzazioni:', error);
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/events/show.blade.php ENDPATH**/ ?>