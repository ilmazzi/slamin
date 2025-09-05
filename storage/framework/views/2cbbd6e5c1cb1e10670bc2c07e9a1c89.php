<?php $__env->startSection('title', __('events.manage_event') . ' ' . $event->title); ?>
<?php $__env->startSection('css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php echo e(__('events.manage_event')); ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('events.dashboard')); ?></a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('events.index')); ?>"><?php echo e(__('events.events')); ?></a></li>
<li class="breadcrumb-item"><a href="<?php echo e(route('events.show', $event)); ?>"><?php echo e($event->title); ?></a></li>
<li class="breadcrumb-item active"><?php echo e(__('common.manage')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">

    <!-- <?php echo e(__('dashboard.dashboard')); ?> Header -->
    <div class="row m-1 mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white position-relative overflow-hidden" style="min-height: 200px;">
                <?php if($event->image_url): ?>
                    <div class="position-absolute w-100 h-100" style="background: url('<?php echo e($event->image_url); ?>') center/cover; opacity: 0.3;"></div>
                <?php endif; ?>
                <div class="card-body p-4 position-relative" style="z-index: 2;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="text-white mb-3 fw-bold"><?php echo e($event->title); ?></h2>
                            <?php if($event->start_datetime && $event->start_datetime->isPast()): ?>
                                <span class="badge bg-white text-primary fs-6 px-3 py-2">
                                    <i class="ph ph-clock me-2"></i><?php echo e(__('events.event_ended')); ?>

                                </span>
                            <?php elseif($event->start_datetime && $event->start_datetime->diffInDays(now()) <= 7): ?>
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                    <i class="ph ph-warning me-2"></i><?php echo e(__('events.event_imminent')); ?>

                                </span>
                            <?php elseif($event->is_availability_based): ?>
                                <span class="badge bg-info fs-6 px-3 py-2">
                                    <i class="ph ph-calendar-check me-2"></i><?php echo e(__('events.availability_based_event')); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <i class="ph ph-calendar-check me-2"></i><?php echo e(__('events.upcoming_events')); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-white text-primary me-2 px-4">
                                <i class="ph ph-eye me-2"></i><?php echo e(__('events.view_event')); ?>

                            </a>
                            <a href="<?php echo e(route('events.edit', $event)); ?>" class="btn btn-light-white text-white px-4">
                                <i class="ph ph-pencil me-2"></i><?php echo e(__('events.edit_event_action')); ?>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row m-1 mb-4">
        <div class="col-12">
            <div class="row g-4">
                    <div class="col-6 col-md-3">
                        <div class="card">
                            <?php $pendingInvites = $event->pendingInvitations->count(); ?>
                            <span class="bg-warning h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                                <i class="ph ph-envelope f-s-24"></i>
                            </span>
                            <div class="card-body eshop-cards">
                                <span class="ripple-effect"></span>
                                <div class="overflow-hidden">
                                    <h3 class="text-warning mb-0"><?php echo e($pendingInvites); ?></h3>
                                    <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1"><?php echo e(__('events.pending_invitations')); ?></p>
                                    <span class="badge bg-light-warning">📨 <?php echo e(__('events.waiting_status')); ?></span>
                    </div>
                </div>
            </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card">
                            <?php $pendingRequests = $event->pendingRequests->count(); ?>
                            <span class="bg-info h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                                <i class="ph ph-hand-waving f-s-24"></i>
                            </span>
                            <div class="card-body eshop-cards">
                                <span class="ripple-effect"></span>
                                <div class="overflow-hidden">
                                    <h3 class="text-info mb-0"><?php echo e($pendingRequests); ?></h3>
                                    <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1"><?php echo e(__('events.pending_requests')); ?></p>
                                    <span class="badge bg-light-info">🙋 <?php echo e(__('events.applications_status')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card">
                            <?php $confirmed = $event->acceptedInvitations->count() + $event->acceptedRequests->count(); ?>
                            <span class="bg-success h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                                <i class="ph ph-check-circle f-s-24"></i>
                            </span>
                            <div class="card-body eshop-cards">
                                <span class="ripple-effect"></span>
                                <div class="overflow-hidden">
                                    <h3 class="text-success mb-0"><?php echo e($confirmed); ?></h3>
                                    <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1"><?php echo e(__('events.confirmed_participants')); ?></p>
                                    <span class="badge bg-light-success">✅ <?php echo e(__('events.participants_status')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card">
                            <?php
                                if ($event->start_datetime) {
                                    $daysToEvent = $event->start_datetime->diffInDays(now());
                                    $isPast = $event->start_datetime->isPast();
                                } else {
                                    $daysToEvent = null;
                                    $isPast = false;
                                }
                            ?>
                            <span class="bg-<?php echo e($isPast ? 'secondary' : 'primary'); ?> h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                                <i class="ph ph-calendar-<?php echo e($isPast ? 'x' : 'check'); ?> f-s-24"></i>
                            </span>
                            <div class="card-body eshop-cards">
                                <span class="ripple-effect"></span>
                                <div class="overflow-hidden">
                                    <h3 class="text-<?php echo e($isPast ? 'secondary' : 'primary'); ?> mb-0">
                                        <?php if($daysToEvent !== null): ?>
                                            <?php echo e(abs(ceil($daysToEvent))); ?>

                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </h3>
                                    <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">
                                        <?php if($event->is_availability_based): ?>
                                            <?php echo e(__('events.availability_based_event')); ?>

                                        <?php elseif($daysToEvent !== null): ?>
                                            <?php echo e($isPast ? __('events.days_ago') : __('events.days_remaining')); ?>

                                        <?php else: ?>
                                            <?php echo e(__('events.not_specified')); ?>

                                        <?php endif; ?>
                                    </p>
                                    <span class="badge bg-light-<?php echo e($isPast ? 'secondary' : 'primary'); ?>">
                                        <?php echo e($isPast ? '🕒 ' . __('events.past') : '⏰ ' . __('events.imminent')); ?>

                                    </span>
                                </div>
                            </div>
                                            </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Image Section -->
    <?php if($event->image_url): ?>
    <div class="row m-1 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ph ph-image me-2"></i>Immagine <?php echo e(__('invitations.event')); ?></h6>
                </div>
                <div class="card-body text-center">
                    <img src="<?php echo e($event->image_url); ?>" alt="<?php echo e($event->title); ?>" class="img-fluid rounded" style="max-height: 300px; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Quick Actions -->
    <div class="row m-1 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ph ph-lightning me-2"></i><?php echo e(__('invitations.actions')); ?> Rapide</h6>
                </div>
                <div class="card-body">
        <div class="row g-3">
                        <div class="col-md-3 col-6">
                <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#inviteModal">
                    <i class="ph ph-envelope me-2"></i>Invita Artisti
                </button>
            </div>
                        <div class="col-md-3 col-6">
                            <button class="btn btn-light-primary w-100" onclick="bulkAcceptRequests()">
                    <i class="ph ph-check-circle me-2"></i>Accetta Tutte
                </button>
            </div>
                        <div class="col-md-3 col-6">
                            <button class="btn btn-light-secondary w-100" onclick="exportParticipants()">
                    <i class="ph ph-download me-2"></i>Esporta Lista
                </button>
            </div>
                        <div class="col-md-3 col-6">
                            <button class="btn btn-light-success w-100" onclick="sendUpdateNotification()">
                    <i class="ph ph-megaphone me-2"></i>Notifica Update
                </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row m-1">
        <!-- Left Column: Pending Actions -->
        <div class="col-lg-8">

            <!-- Pending Requests -->
            <?php if($event->pendingRequests->count() > 0): ?>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ph ph-hand-waving me-2"></i><?php echo e(__('events.participation_requests')); ?>

                        <span class="badge bg-warning ms-2"><?php echo e($event->pendingRequests->count()); ?></span>
                    </h5>

                    <!-- Bulk Actions -->
                    <div class="alert alert-primary d-none" id="bulkActionsRequests">
                        <div class="d-flex align-items-center justify-content-between">
                            <span><i class="ph ph-selection-all me-2"></i><span id="selectedRequestsCount">0</span> <?php echo e(__('events.requests_selected')); ?></span>
                            <div class="d-flex gap-2">
                                <button class="btn btn-light-success btn-sm" onclick="bulkActionRequests('accept')">
                                <i class="ph ph-check me-1"></i>Accetta
                            </button>
                                <button class="btn btn-light-danger btn-sm" onclick="bulkActionRequests('decline')">
                                <i class="ph ph-x me-1"></i>Rifiuta
                            </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php $__currentLoopData = $event->pendingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="participant-item border-bottom" data-request-id="<?php echo e($request->id); ?>" style="border-color: #f0f0f0 !important;">
                            <div class="p-3">
                                <div class="d-flex align-items-start">
                                    <div class="form-check me-3 mt-1">
                                        <input type="checkbox" class="form-check-input request-checkbox" value="<?php echo e($request->id); ?>">
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1 fw-bold">
                                                    Da <a href="<?php echo e(route('user.show', $request->user)); ?>" class="text-decoration-none hover-effect"><?php echo e($request->user->getDisplayName()); ?></a>
                                                    <?php if($request->user->username): ?>
                                                        <span class="text-muted">(<?php echo e($request->user->username); ?>)</span>
                                                    <?php endif; ?>
                                                </h6>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-primary"><?php echo e(ucfirst($request->requested_role)); ?></span>
                                                    <small class="text-muted">
                                                        <i class="ph ph-clock me-1"></i><?php echo e($request->created_at->diffForHumans()); ?>

                                                    </small>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-success btn-sm" onclick="quickResponse(<?php echo e($request->id); ?>, 'accept')"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Accetta richiesta">
                                                    <i class="ph ph-check me-1"></i>Accetta
                                                </button>
                                                <button class="btn btn-danger btn-sm" onclick="quickResponse(<?php echo e($request->id); ?>, 'decline')"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Rifiuta richiesta">
                                                    <i class="ph ph-x me-1"></i>Rifiuta
                                                </button>
                                                <button class="btn btn-light-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#requestDetailModal" data-request-id="<?php echo e($request->id); ?>"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Vedi dettagli">
                                                    <i class="ph ph-eye"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="message-preview mb-2">
                                            <strong>Messaggio:</strong> <?php echo e($request->message); ?>

                                        </div>

                                        <?php if($request->experience): ?>
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <strong>Esperienza:</strong> <?php echo e($request->experience); ?>

                                                </small>
                                            </div>
                                        <?php endif; ?>

                                        <?php if($request->portfolio_links && count($request->portfolio_links) > 0): ?>
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <strong>Portfolio:</strong>
                                                    <?php $__currentLoopData = $request->portfolio_links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <a href="<?php echo e($link); ?>" target="_blank" class="me-2 text-decoration-none">
                                                            <i class="ph ph-link me-1"></i>Link <?php echo e($loop->iteration); ?>

                                                        </a>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Pending Invitations -->
            <?php if($event->pendingInvitations->count() > 0): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-envelope me-2"></i>Inviti in Attesa di Risposta
                        <span class="badge bg-primary ms-2"><?php echo e($event->pendingInvitations->count()); ?></span>
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $event->pendingInvitations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invitation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item">
                                <div class="row">
                                    <div class="col-auto">
                                        <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($invitation->invitedUser)); ?>"
                                             alt="<?php echo e($invitation->invitedUser->getDisplayName()); ?>"
                                             class="h-45 w-45 rounded-circle"
                                             style="object-fit: cover;">
                                    </div>
                                    <div class="col text-truncate">
                                        <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <a href="<?php echo e(route('user.show', $invitation->invitedUser)); ?>" class="text-decoration-none hover-effect">
                                                    <?php echo e($invitation->invitedUser->getDisplayName()); ?>

                                                </a>
                                            </h6>
                                                <div class="mb-2">
                                                    <span class="badge bg-light-info"><?php echo e(ucfirst($invitation->role)); ?></span>
                                            <small class="text-muted ms-2">
                                                Invitato <?php echo e($invitation->created_at->diffForHumans()); ?>

                                            </small>
                                                </div>
                                            <?php if($invitation->expires_at): ?>
                                                    <div class="mb-2">
                                                        <small class="text-warning">
                                                    <i class="ph ph-clock me-1"></i>Scade <?php echo e($invitation->expires_at->diffForHumans()); ?>

                                                </small>
                                                    </div>
                                            <?php endif; ?>
                                                <?php if($invitation->message): ?>
                                                    <div class="alert alert-light-primary p-2 mt-2">
                                                        <small><em>"<?php echo e($invitation->message); ?>"</em></small>
                                        </div>
                                                <?php endif; ?>
                                            </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-outline-primary btn-sm" onclick="resendInvitation(<?php echo e($invitation->id); ?>)">
                                                <i class="ph ph-arrow-clockwise me-1"></i>Reinvia
                                            </button>
                                                                                <button class="btn btn-light-danger btn-sm" onclick="cancelInvitation(<?php echo e($invitation->id); ?>)">
                                                <i class="ph ph-x me-1"></i>Cancella
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Confirmed Participants -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-users me-2"></i><?php echo e(__('events.confirmed_participants_list')); ?>

                        <span class="badge bg-success ms-2"><?php echo e($event->acceptedInvitations->count() + $event->acceptedRequests->count()); ?></span>
                    </h5>
                </div>
                <div class="card-body p-3">
                    <?php
                        $confirmedParticipants = collect();
                        $confirmedParticipants = $confirmedParticipants->merge(
                            $event->acceptedInvitations->map(function($invitation) {
                                return [
                                    'user' => $invitation->invitedUser,
                                    'role' => $invitation->role,
                                    'type' => 'invited',
                                    'confirmed_at' => $invitation->responded_at
                                ];
                            })
                        );
                        $confirmedParticipants = $confirmedParticipants->merge(
                            $event->acceptedRequests->map(function($request) {
                                return [
                                    'user' => $request->user,
                                    'role' => $request->requested_role,
                                    'type' => 'requested',
                                    'confirmed_at' => $request->reviewed_at
                                ];
                            })
                        );
                    ?>

                    <?php if($confirmedParticipants->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $confirmedParticipants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item">
                                    <div class="row">
                                        <div class="col-auto">
                                            <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($participant['user'])); ?>"
                                                 alt="<?php echo e($participant['user']->getDisplayName()); ?>"
                                                 class="h-45 w-45 rounded-circle"
                                                 style="object-fit: cover;">
                                        </div>
                                        <div class="col">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                <h6 class="mb-1">
                                                    <a href="<?php echo e(route('user.show', $participant['user'])); ?>" class="text-decoration-none hover-effect">
                                                        <?php echo e($participant['user']->getDisplayName()); ?>

                                                    </a>
                                                </h6>
                                                    <div class="mb-1">
                                                        <span class="badge bg-light-primary"><?php echo e(ucfirst($participant['role'])); ?></span>
                                                        <span class="badge bg-light-success ms-1">
                                                            <?php echo e($participant['type'] === 'invited' ? __('events.invited_badge') : __('events.request_badge')); ?>

                                                    </span>
                                                </div>
                                                <small class="text-muted">
                                                    Confermato <?php echo e($participant['confirmed_at']->diffForHumans()); ?>

                                                </small>
                                            </div>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="contactParticipant('<?php echo e($participant['user']->id); ?>')">
                                                <i class="ph ph-chat-circle"></i>
                                            </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="ph ph-users-three display-4 text-muted mb-3"></i>
                            <p class="text-muted">Nessun partecipante confermato ancora</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Timeline & Analytics -->
        <div class="col-lg-4 mt-4 mt-lg-0">

            <!-- Event Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-clock me-2"></i>Timeline <?php echo e(__('invitations.event')); ?>

                    </h6>
                </div>
                <div class="card-body p-3">
                    <ul class="app-timeline-box">
                    <?php if($event->registration_deadline && $event->registration_deadline > now()): ?>
                            <li class="timeline-section">
                                <div class="timeline-icon">
                                    <span class="text-light-danger h-35 w-35 d-flex-center b-r-50">
                                        <i class="ph ph-clock f-s-20"></i>
                                    </span>
                                </div>
                                <div class="timeline-content bg-light-danger b-1-danger">
                                    <div class="d-flex justify-content-between align-items-center timeline-flex">
                                        <h6 class="mb-1 text-danger">Scadenza Iscrizioni</h6>
                                        <span class="badge bg-danger">Urgente</span>
                                    </div>
                            <p class="text-muted mb-0"><?php echo e($event->registration_deadline->format('d/m/Y H:i')); ?></p>
                            <small class="text-danger"><?php echo e($event->registration_deadline->diffForHumans()); ?></small>
                        </div>
                            </li>
                    <?php endif; ?>

                        <li class="timeline-section">
                            <div class="timeline-icon">
                                <span class="text-light-primary h-35 w-35 d-flex-center b-r-50">
                                    <i class="ph ph-play f-s-20"></i>
                                </span>
                            </div>
                            <div class="timeline-content bg-light-primary b-1-primary">
                                <div class="d-flex justify-content-between align-items-center timeline-flex">
                                    <h6 class="mb-1 text-primary"><?php echo e(__('events.start_event')); ?></h6>
                                    <?php if($event->start_datetime): ?>
                                        <span class="badge bg-primary"><?php echo e($event->start_datetime->diffForHumans()); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-info"><?php echo e(__('events.availability_based_event')); ?></span>
                                    <?php endif; ?>
                                </div>
                        <p class="text-muted mb-0">
                            <?php if($event->start_datetime): ?>
                                <?php echo e($event->start_datetime->format('d/m/Y H:i')); ?>

                            <?php elseif($event->is_availability_based): ?>
                                <?php echo e(__('events.availability_based_event_description')); ?>

                            <?php else: ?>
                                <?php echo e(__('events.not_specified')); ?>

                            <?php endif; ?>
                        </p>
                    </div>
                        </li>

                        <li class="timeline-section">
                            <div class="timeline-icon">
                                <span class="text-light-success h-35 w-35 d-flex-center b-r-50">
                                    <i class="ph ph-check f-s-20"></i>
                                </span>
                            </div>
                            <div class="timeline-content bg-light-success b-1-success">
                                <div class="d-flex justify-content-between align-items-center timeline-flex">
                                    <h6 class="mb-1 text-success"><?php echo e(__('events.end_event')); ?></h6>
                                    <?php if($event->start_datetime && $event->end_datetime): ?>
                                        <span class="badge bg-success"><?php echo e($event->duration); ?>h</span>
                                    <?php else: ?>
                                        <span class="badge bg-info"><?php echo e(__('events.availability_based_event')); ?></span>
                                    <?php endif; ?>
                                </div>
                        <p class="text-muted mb-0">
                            <?php if($event->end_datetime): ?>
                                <?php echo e($event->end_datetime->format('d/m/Y H:i')); ?>

                            <?php elseif($event->is_availability_based): ?>
                                <?php echo e(__('events.availability_based_event_description')); ?>

                            <?php else: ?>
                                <?php echo e(__('events.not_specified')); ?>

                            <?php endif; ?>
                        </p>
                    </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-chart-bar me-2"></i>Statistiche Rapide
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="h4 text-primary mb-1"><?php echo e($event->invitations->count()); ?></div>
                                <small class="text-muted"><?php echo e(__('invitations.total_invitations')); ?></small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="h4 text-success mb-1"><?php echo e($event->requests->count()); ?></div>
                                <small class="text-muted"><?php echo e(__('events.total_requests')); ?></small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="h4 text-warning mb-1">
                                    <?php echo e($event->acceptedInvitations->count()); ?>

                                </div>
                                <small class="text-muted">Inviti <?php echo e(__('invitations.accepted_invitations')); ?></small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="h4 text-info mb-1">
                                    <?php echo e(round(($event->acceptedInvitations->count() / max($event->invitations->count(), 1)) * 100)); ?>%
                                </div>
                                <small class="text-muted">Tasso Accettazione</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Artists -->
            <?php if($availableArtists->count() > 0): ?>
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-user-plus me-2"></i>Artisti Disponibili
                        <span class="badge bg-secondary ms-2"><?php echo e($availableArtists->count()); ?></span>
                    </h6>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $availableArtists->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="participant-avatar me-3" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                <?php echo e(substr($artist->name, 0, 2)); ?>

                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 small"><?php echo e($artist->name); ?></h6>
                                <small class="text-muted"><?php echo e($artist->role_display_name); ?></small>
                            </div>
                            <button class="btn btn-outline-primary btn-sm" onclick="quickInvite(<?php echo e($artist->id); ?>)">
                                <i class="ph ph-plus"></i>
                            </button>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php if($availableArtists->count() > 5): ?>
                        <button class="btn btn-light-secondary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#inviteModal">
                            Vedi tutti (<?php echo e($availableArtists->count() - 5); ?> altri)
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

    <!-- Floating Action Button -->
    <div class="position-fixed" style="bottom: 30px; right: 30px; z-index: 1000;">
        <button class="btn btn-primary rounded-circle p-3 position-relative"
                data-bs-toggle="modal" data-bs-target="#inviteModal"
                title="Invita Artisti"
                style="width: 60px; height: 60px;">
            <i class="ph ph-envelope f-s-20"></i>
        <?php if($event->pendingRequests->count() > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo e($event->pendingRequests->count()); ?>

                </span>
        <?php endif; ?>
    </button>
</div>

<!-- Invite Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-envelope me-2"></i>Invita Artisti
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="inviteForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Seleziona Artisti *</label>
                            <div style="max-height: 200px; overflow-y: auto; border: 1px solid #e9ecef; border-radius: 5px; padding: 10px;">
                                <?php $__currentLoopData = $availableArtists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check">
                                        <input type="checkbox" name="invited_user_ids[]" value="<?php echo e($artist->id); ?>" class="form-check-input" id="artist_<?php echo e($artist->id); ?>">
                                        <label for="artist_<?php echo e($artist->id); ?>" class="form-check-label d-flex align-items-center">
                                            <div class="participant-avatar me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                <?php echo e(substr($artist->name, 0, 2)); ?>

                                            </div>
                                            <div>
                                                <div><?php echo e($artist->name); ?></div>
                                                <small class="text-muted"><?php echo e($artist->role_display_name); ?></small>
                                            </div>
                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?php echo e(__('invitations.role')); ?> *</label>
                                <select name="role" class="form-select" required>
                                    <option value="performer">Performer</option>
                                    <option value="judge">Judge</option>
                                    <option value="technician">Technician</option>
                                    <option value="host">Host</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Compenso (€)</label>
                                <input type="number" name="compensation" class="form-control" min="0" step="0.01" placeholder="0.00">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Scadenza Invito</label>
                                <input type="datetime-local" name="expires_at" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Messaggio Personalizzato</label>
                        <textarea name="message" class="form-control" rows="4" placeholder="Scrivi un messaggio personalizzato per gli invitati..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-paper-plane me-2"></i><?php echo e(__('videos.send')); ?> Inviti
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Request Detail Modal -->
<div class="modal fade" id="requestDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dettagli Richiesta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="requestDetailContent">
                <!-- Content loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                                    <button type="button" class="btn btn-light-danger me-2" onclick="respondToRequest('decline')">
                    <i class="ph ph-x me-2"></i>Rifiuta
                </button>
                    <button type="button" class="btn btn-light-success" onclick="respondToRequest('accept')">
                    <i class="ph ph-check me-2"></i>Accetta
                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Traduzioni JavaScript
const translations = {
    accept_action: '<?php echo e(__('events.accept_action')); ?>',
    reject_action: '<?php echo e(__('events.reject_action')); ?>',
    requests: '<?php echo e(__('events.requests')); ?>',
    message_for_action: '<?php echo e(__('events.message_for_action')); ?>',
    this_request: '<?php echo e(__('events.this_request')); ?>'
};
let selectedRequests = [];
let currentRequestId = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Request checkboxes
    document.querySelectorAll('.request-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                selectedRequests.push(this.value);
            } else {
                selectedRequests = selectedRequests.filter(id => id !== this.value);
            }
            updateBulkActions();
        });
    });

    // Invite form
    const inviteForm = document.getElementById('inviteForm');
    if (inviteForm) {
        inviteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            sendInvitations();
        });
    }

    // Request detail modal
    const requestDetailModal = document.getElementById('requestDetailModal');
    if (requestDetailModal) {
        requestDetailModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const requestId = button.getAttribute('data-request-id');
            loadRequestDetail(requestId);
        });
    }

    // Auto-refresh pending items every 30 seconds
    setInterval(refreshPendingItems, 30000);
});

function updateBulkActions() {
    const bulkActions = document.getElementById('bulkActionsRequests');
    const count = selectedRequests.length;

    if (count > 0) {
        bulkActions.classList.remove('d-none');
        document.getElementById('selectedRequestsCount').textContent = count;
    } else {
        bulkActions.classList.add('d-none');
    }
}

function quickResponse(requestId, action) {
    const actionText = action === 'accept' ? 'accettare' : 'rifiutare';

    if (!confirm(`Sei sicuro di voler ${actionText} questa richiesta?`)) {
        return;
    }

    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="ph ph-spinner ph-spin me-1"></i>Elaborazione...';

    fetch(`/requests/${requestId}/${action}-ajax`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken() || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`Richiesta ${action === 'accept' ? 'accettata' : 'rifiutata'} con successo!`, 'success');

            // Remove the request item with animation
            const requestElement = document.querySelector(`[data-request-id="${requestId}"]`);
            if (requestElement) {
                requestElement.style.transition = 'all 0.3s ease';
                requestElement.style.opacity = '0';
                requestElement.style.transform = 'translateX(-100%)';
                setTimeout(() => {
                    requestElement.remove();

                    // Update the count badge
                    const badge = document.querySelector('.card-header .badge');
                    if (badge) {
                        const currentCount = parseInt(badge.textContent);
                        if (currentCount > 1) {
                            badge.textContent = currentCount - 1;
                        } else {
                            // Hide the entire section if no more requests
                            const card = requestElement.closest('.card');
                            if (card) {
                                card.style.transition = 'all 0.3s ease';
                                card.style.opacity = '0';
                                setTimeout(() => card.remove(), 300);
                            }
                        }
                    }
                }, 300);
            }
        } else {
            showNotification(`Errore nell'${action === 'accept' ? 'accettazione' : 'rifiuto'} della richiesta`, 'error');
            button.disabled = false;
            button.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Errore di connessione', 'error');
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function bulkActionRequests(action) {
    if (selectedRequests.length === 0) return;

    const message = prompt(`${translations.message_for_action} ${action === 'accept' ? translations.accept_action : translations.reject_action} ${selectedRequests.length} ${translations.requests}:`);
    if (message === null) return;

    const data = {
        action: action,
        request_ids: selectedRequests,
        response_message: message
    };

    fetch(`/requests/api/events/<?php echo e($event->id); ?>/bulk-action`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken() || ''
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            selectedRequests.forEach(id => removeParticipantItem(id));
            selectedRequests = [];
            updateBulkActions();
            updateStats();
        } else {
            showNotification('Errore nell\'operazione', 'error');
        }
    });
}

function sendInvitations() {
    const formData = new FormData(document.getElementById('inviteForm'));
    const data = {
        event_id: <?php echo e($event->id); ?>,
        invited_user_ids: formData.getAll('invited_user_ids[]'),
        role: formData.get('role'),
        compensation: formData.get('compensation'),
        expires_at: formData.get('expires_at'),
        message: formData.get('message')
    };

    if (data.invited_user_ids.length === 0) {
        showNotification('Seleziona almeno un artista', 'error');
        return;
    }

    fetch('/events/<?php echo e($event->id); ?>/invitations', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken() || ''
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('inviteModal')).hide();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message || 'Errore nell\'invio', 'error');
        }
    })
    .catch(error => {
        showNotification('Errore di connessione', 'error');
    });
}

function quickInvite(artistId) {
    // Controllo sicurezza CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showNotification('Errore di sicurezza: token CSRF mancante', 'error');
        return;
    }

    const data = {
        event_id: <?php echo e($event->id); ?>,
        invited_user_ids: [artistId],
        role: 'performer',
        message: 'Ti invito a partecipare al mio evento!'
    };

    fetch('/events/<?php echo e($event->id); ?>/invitations', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Invito inviato!', 'success');
        } else {
            showNotification('Errore nell\'invio', 'error');
        }
    });
}

function resendInvitation(invitationId) {
    // Controllo sicurezza CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showNotification('Errore di sicurezza: token CSRF mancante', 'error');
        return;
    }

    fetch(`/events/<?php echo e($event->id); ?>/invitations/${invitationId}/resend`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
        } else {
            showNotification('Errore nel reinvio', 'error');
        }
    });
}

function cancelInvitation(invitationId) {
    if (confirm('Sei sicuro di voler cancellare questo invito?')) {
        fetch(`/events/<?php echo e($event->id); ?>/invitations/${invitationId}`, {
            method: 'DELETE',
            headers: {
            'X-CSRF-TOKEN': getCSRFToken() || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Invito cancellato', 'success');
                location.reload();
            } else {
                showNotification('Errore nella cancellazione', 'error');
            }
        });
    }
}

function loadRequestDetail(requestId) {
    currentRequestId = requestId;

    // Find request data from page
    const requestElement = document.querySelector(`[data-request-id="${requestId}"]`);
    if (requestElement) {
        // For now, show basic info. In a real app, you'd fetch full details
        document.getElementById('requestDetailContent').innerHTML = `
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Caricamento...</span>
                </div>
            </div>
        `;

        // Simulate loading
        setTimeout(() => {
            document.getElementById('requestDetailContent').innerHTML = `
                <div class="alert alert-info">
                    <p>Dettagli completi della richiesta verranno caricati qui.</p>
                    <p>Per ora, utilizza i pulsanti di azione rapida nella lista principale.</p>
                </div>
            `;
        }, 1000);
    }
}

function respondToRequest(action) {
    if (!currentRequestId) return;

    const message = prompt(`${translations.message_for_action} ${action === 'accept' ? translations.accept_action : translations.reject_action} ${translations.this_request}:`);
    if (message === null) return;

    quickResponse(currentRequestId, action);
    bootstrap.Modal.getInstance(document.getElementById('requestDetailModal')).hide();
}

function removeParticipantItem(requestId) {
    const element = document.querySelector(`[data-request-id="${requestId}"]`);
    if (element) {
        element.style.transition = 'all 0.3s ease';
        element.style.opacity = '0';
        element.style.transform = 'translateX(-100%)';
        setTimeout(() => element.remove(), 300);
    }
}

function updateStats() {
    // Update stats in real-time
    fetch(`/requests/api/events/<?php echo e($event->id); ?>/statistics`)
        .then(response => response.json())
        .then(data => {
            // Update stats displays

        });
}

function refreshPendingItems() {
    // Auto-refresh pending items
    fetch(window.location.href + '?ajax=1')
        .then(response => response.text())
        .then(html => {
            // In a real implementation, you'd update only the changed parts

        });
}

function exportParticipants() {
    window.open(`/events/<?php echo e($event->id); ?>/export`, '_blank');
}

function sendUpdateNotification() {
    const message = prompt('Inserisci il messaggio di aggiornamento:');
    if (message) {
        // Send notification to all participants
        fetch(`/events/<?php echo e($event->id); ?>/notify`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken() || ''
            },
            body: JSON.stringify({ message })
        })
        .then(response => response.json())
        .then(data => {
            showNotification('Notifica inviata a tutti i partecipanti', 'success');
        });
    }
}

function contactParticipant(userId) {
    // Open chat or contact modal
    showNotification('Funzione chat in sviluppo', 'info');
}

function bulkAcceptRequests() {
    const checkboxes = document.querySelectorAll('.request-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = true;
        if (!selectedRequests.includes(cb.value)) {
            selectedRequests.push(cb.value);
        }
    });
    updateBulkActions();

    if (selectedRequests.length > 0) {
        bulkActionRequests('accept');
    }
}

// Helper function per CSRF token sicuro
function getCSRFToken() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        return null;
    }
    return csrfToken.content;
}

function showNotification(message, type) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
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
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/events/manage.blade.php ENDPATH**/ ?>