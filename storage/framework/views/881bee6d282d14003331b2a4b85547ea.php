<?php $__env->startSection('title', __('dashboard.dashboard') . ' - Slam In'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/vendor/fullcalendar/fullcalendar.bundle.css')); ?>">
<style>
    .dashboard-calendar .fc-toolbar {
        display: none !important;
    }
    .dashboard-calendar .fc-daygrid-day {
        cursor: pointer;
    }
    .dashboard-calendar .fc-event {
        cursor: pointer;
        font-size: 11px;
        padding: 2px 4px;
    }
    .dashboard-calendar .fc-daygrid-day-number {
        font-size: 12px;
    }
    .dashboard-calendar .fc-col-header-cell {
        font-size: 11px;
        padding: 4px 0;
    }
    .event-organizer {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
    }
    .event-participant {
        background-color: #007bff !important;
        border-color: #007bff !important;
    }
    .event-private {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
    }
    .event-wishlisted {
        background-color: #ff6b6b !important;
        border-color: #ff5252 !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
    <div class="container-fluid">

        <!-- User Welcome Card semplificata -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card hover-effect b-e-4-primary">

                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class=" mb-1 f-w-600"><?php echo e(__('dashboard.welcome', ['name' => $user->getDisplayName()])); ?></h4>
                                <p class="text-primary-50 mb-2 f-s-14"><?php echo e($user->getName()); ?></p>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php $__currentLoopData = $user->getRoleNames(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-light-success text-dark f-s-12">
                                            <?php echo e(__('auth.role_' . $role) ?: ucfirst($role)); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="bg-white-500 h-50 w-50 d-flex-center rounded-circle ms-auto">
                                    <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($user)); ?>"
                                         alt="<?php echo e($user->getDisplayName()); ?>"
                                         class="rounded-circle"
                                         style="width: 90px; height: 90px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


















            </div>
        </div>

        <!-- Calendario e Statistiche in riga -->
        <div class="row mb-4">
            <!-- Calendario a sinistra -->
            <div class="col-lg-8">
                <div class="card hover-effect equal-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-calendar me-2 text-warning"></i><?php echo e(__('dashboard.my_calendar')); ?>

                        </h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light-warning btn-sm" id="calendarPrev">
                                <i class="ph ph-caret-left"></i>
                            </button>
                            <button class="btn btn-light-warning btn-sm" id="calendarNext">
                                <i class="ph ph-caret-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body pa-20">
                        <div id="dashboardCalendar" style="height: 300px;"></div>
                        <div class="text-center mt-3">
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="<?php echo e(route('events.create')); ?>" class="btn btn-success btn-sm">
                                    <i class="ph ph-plus me-1"></i><?php echo e(__('dashboard.create_event_button')); ?>

                                </a>
                                <a href="<?php echo e(route('calendar')); ?>" class="btn btn-light-warning btn-sm">
                                    <i class="ph ph-calendar me-1"></i><?php echo e(__('dashboard.view_full_calendar')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiche a destra in griglia 2x2 -->
            <div class="col-lg-4">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-chart-bar me-2 text-primary"></i><?php echo e(__('dashboard.statistics')); ?>

                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        <div class="row g-3">
                            <!-- Statistica 1 - Eventi Passati -->
                            <div class="col-6">
                                <a href="<?php echo e(route('events.index', ['filter' => 'past'])); ?>" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-secondary">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-secondary h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-clock-counter-clockwise f-s-18 text-secondary"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-secondary mb-1 f-w-600"><?php echo e($stats['past_events']); ?></h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1"><?php echo e(__('dashboard.past_events')); ?></p>
                                                <span class="badge bg-light-secondary f-s-10"><?php echo e(__('dashboard.role_history')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 2 - Eventi Futuri -->
                            <div class="col-6">
                                <a href="<?php echo e(route('events.index', ['filter' => 'future'])); ?>" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-warning">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-warning h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-calendar-check f-s-18 text-warning"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-warning mb-1 f-w-600"><?php echo e($stats['future_events']); ?></h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1"><?php echo e(__('dashboard.future_events')); ?></p>
                                                <span class="badge bg-light-warning f-s-10"><?php echo e(__('dashboard.role_upcoming')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 3 - Eventi Organizzati -->
                            <div class="col-6">
                                <a href="<?php echo e(route('events.index', ['filter' => 'organized'])); ?>" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-primary">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-primary h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-article f-s-18 text-primary"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-primary mb-1 f-w-600"><?php echo e($stats['organized_events']); ?></h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1"><?php echo e(__('dashboard.organized_events')); ?></p>
                                                <span class="badge bg-light-primary f-s-10"><?php echo e(__('dashboard.role_organizer')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 4 - Inviti in Attesa -->
                            <div class="col-6">
                                <a href="<?php echo e(route('events.index', ['filter' => 'invitations'])); ?>" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-success">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-success h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-envelope f-s-18 text-success"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-success mb-1 f-w-600"><?php echo e($stats['pending_invitations']); ?></h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1"><?php echo e(__('dashboard.pending_invitations')); ?></p>
                                                <span class="badge bg-light-success f-s-10"><?php echo e(__('dashboard.role_invitations')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 5 - Inviti ai Gruppi in Attesa -->
                            <div class="col-6">
                                <a href="<?php echo e(route('group-invitations.index')); ?>" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-primary">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-primary h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-users f-s-18 text-primary"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-primary mb-1 f-w-600"><?php echo e($stats['pending_group_invitations']); ?></h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1"><?php echo e(__('dashboard.group_invitations')); ?></p>
                                                <span class="badge bg-light-primary f-s-10"><?php echo e(__('dashboard.groups')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenuto sotto il calendario e statistiche -->
        <div class="row">
            <!-- Titolo <?php echo e(__('invitations.actions')); ?> Rapide -->
            <div class="col-12 mb-3">
                <div class="text-center">
                    <h5 class="text-primary mb-2 f-w-600">
                        <i class="ph ph-lightning me-2"></i><?php echo e(__('dashboard.quick_actions')); ?>

                    </h5>
                    <hr class="w-25 mx-auto border-primary border-2 opacity-25">
                </div>
            </div>

            <!-- Quick Actions dinamiche dal controller -->
            <?php $__currentLoopData = $quickActions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-6 col-md-4 col-lg-2 mb-3">
                    <a href="<?php echo e($action['url']); ?>" class="card hover-effect h-100 text-decoration-none">
                        <div class="card-body text-center pa-15">
                            <div class="bg-light-<?php echo e($action['color']); ?> h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                <i class="<?php echo e($action['icon']); ?> text-<?php echo e($action['color']); ?> f-s-18"></i>
                            </div>
                            <h6 class="mb-1 fw-bold text-dark f-s-13"><?php echo e(__('dashboard.' . $action['key'])); ?></h6>
                            <small class="text-muted f-s-11"><?php echo e(__('dashboard.' . $action['key'] . '_desc')); ?></small>
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- <?php echo e(__('wishlist.wishlist')); ?> Slider -->
        <?php if(auth()->user()->wishlistedEvents()->count() > 0): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card hover-effect equal-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph-duotone ph-heart me-2 text-danger"></i><?php echo e(__('dashboard.my_wishlist')); ?>

                        </h6>
                        <a href="<?php echo e(route('wishlist.index')); ?>" class="btn btn-light-danger btn-sm">
                            <i class="ph ph-arrow-right me-1"></i><?php echo e(__('dashboard.view_all')); ?>

                        </a>
                    </div>
                    <div class="card-body pa-20">
                        <div class="row g-3">
                            <?php $__currentLoopData = auth()->user()->wishlistedEvents()->orderBy('start_datetime')->take(6)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card hover-effect h-100">
                                    <div class="card-body pa-15">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="flex-shrink-0 me-3">
                                                <?php if($event->image_url): ?>
                                                    <img src="<?php echo e($event->image_url); ?>" alt="<?php echo e($event->title); ?>" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light-secondary rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                        <i class="ph ph-calendar text-secondary f-s-20"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold text-dark f-s-14"><?php echo e(Str::limit($event->title, 30)); ?></h6>
                                                <p class="mb-1 text-muted f-s-12">
                                                    <i class="ph ph-map-pin me-1"></i><?php echo e($event->city); ?>

                                                </p>
                                                <p class="mb-0 text-muted f-s-12">
                                                    <i class="ph ph-calendar me-1"></i><?php echo e($event->start_datetime->format('d/m/Y H:i')); ?>

                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-light-<?php echo e($event->getCategoryColorClassAttribute()); ?> text-<?php echo e($event->getCategoryColorClassAttribute()); ?> f-s-11">
                                                <?php echo e($event->getCategoryDisplayName()); ?>

                                            </span>
                                            <div class="d-flex gap-1">
                                                <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-light-primary btn-sm">
                                                    <i class="ph ph-eye f-s-12"></i>
                                                </a>
                                                <button class="btn btn-light-danger btn-sm wishlist-toggle" data-event-id="<?php echo e($event->id); ?>">
                                                    <i class="ph-duotone ph-heart-fill f-s-12"></i>
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
            </div>
        </div>
        <?php endif; ?>

        <!-- Recent Activity e Role-Specific Sections -->
        <div class="row mt-4">
            <!-- Attività Recenti -->
            <div class="col-lg-4">
                <div class="card hover-effect equal-card">
                    <!-- Solo ribbon importante per novità -->
                    <div class="ribbon-top top-left ribbon-primary">
                        <i class="ph ph-sparkle f-s-12"></i>
                    </div>
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-bell me-2 text-primary"></i><?php echo e(__('dashboard.recent_activity')); ?>

                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        <?php if(count($recentActivity) > 0): ?>
                            <?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                                    <div class="flex-shrink-0">
                                        <div class="bg-light-primary h-35 w-35 d-flex-center rounded-circle">
                                            <i class="ph ph-bell text-primary f-s-14"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0 fw-500 f-s-14"><?php echo e($activity['message']); ?></p>
                                        <small class="text-muted f-s-12"><?php echo e($activity['time']); ?></small>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-light-primary btn-sm">
                                    <i class="ph ph-eye me-1"></i><?php echo e(__('dashboard.view_all_activity')); ?>

                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <div class="bg-light-primary h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph ph-bell-slash f-s-24 text-primary"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0"><?php echo e(__('dashboard.no_recent_activity')); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Inviti in Sospeso -->
            <?php if(auth()->user()->eventInvitations()->where('status', 'pending')->count() > 0): ?>
            <div class="col-lg-4">
                <div class="card hover-effect equal-card">
                    <div class="ribbon-top top-left ribbon-success">
                        <i class="ph ph-envelope f-s-12"></i>
                    </div>
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-envelope me-2 text-success"></i><?php echo e(__('dashboard.pending_invitations')); ?>

                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        <?php $__currentLoopData = auth()->user()->eventInvitations()->where('status', 'pending')->with('event')->take(3)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invitation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                                                    <div class="flex-shrink-0">
                                        <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($invitation->event->organizer)); ?>"
                                             alt="<?php echo e($invitation->event->organizer->getDisplayName()); ?>"
                                             class="h-35 w-35 rounded-circle"
                                             style="object-fit: cover;">
                                    </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1 fw-500 f-s-14">
                                        <a href="<?php echo e(route('events.show', $invitation->event)); ?>" class="text-decoration-none hover-effect">
                                            <?php echo e($invitation->event->title); ?>

                                        </a>
                                    </p>
                                    <small class="text-muted f-s-12">
                                        <i class="ph ph-calendar me-1"></i><?php echo e($invitation->event->start_datetime->format('d/m/Y H:i')); ?>

                                    </small>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="d-flex gap-1">
                                        <form action="<?php echo e(route('event-invitations.accept', ['event' => $invitation->event, 'invitation' => $invitation->id])); ?>" method="POST" class="d-inline invitation-form" data-invitation-id="<?php echo e($invitation->id); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="btn btn-success btn-sm" title="<?php echo e(__('invitations.accept')); ?>">
                                                <i class="ph ph-check f-s-12"></i>
                                            </button>
                                        </form>
                                        <form action="<?php echo e(route('event-invitations.decline', ['event' => $invitation->event, 'invitation' => $invitation->id])); ?>" method="POST" class="d-inline invitation-form" data-invitation-id="<?php echo e($invitation->id); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" title="<?php echo e(__('invitations.decline')); ?>">
                                                <i class="ph ph-x f-s-12"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="text-center mt-3">
                            <a href="<?php echo e(route('notifications.index')); ?>" class="btn btn-light-success btn-sm">
                                <i class="ph ph-eye me-1"></i><?php echo e(__('dashboard.view_all_invitations')); ?>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Inviti ai Gruppi in Sospeso -->
            <?php if(auth()->user()->groupInvitations()->where('status', 'pending')->count() > 0): ?>
            <div class="col-lg-4">
                <div class="card hover-effect equal-card">
                    <div class="ribbon-top top-left ribbon-primary">
                        <i class="ph ph-users f-s-12"></i>
                    </div>
                    <div class="card-header">
                                                    <h6 class="card-title mb-0 f-w-600">
                                <i class="ph ph-users me-2 text-primary"></i><?php echo e(__('dashboard.group_invitations')); ?>

                            </h6>
                    </div>
                    <div class="card-body pa-20">
                        <?php $__currentLoopData = auth()->user()->groupInvitations()->where('status', 'pending')->with(['group', 'invitedBy'])->take(3)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invitation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                <div class="flex-shrink-0">
                                    <?php if($invitation->invitedBy): ?>
                                        <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($invitation->invitedBy)); ?>"
                                             alt="<?php echo e($invitation->invitedBy->getDisplayName()); ?>"
                                             class="h-35 w-35 rounded-circle"
                                             style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light-primary h-35 w-35 d-flex-center rounded-circle">
                                            <i class="ph ph-user text-primary f-s-14"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1 fw-500 f-s-14">
                                        <a href="<?php echo e(route('groups.show', $invitation->group)); ?>" class="text-decoration-none hover-effect">
                                            <?php echo e($invitation->group->name); ?>

                                        </a>
                                    </p>
                                    <small class="text-muted f-s-12">
                                        <i class="ph ph-user me-1"></i>
                                        <?php if($invitation->invitedBy): ?>
                                            <a href="<?php echo e(route('user.show', $invitation->invitedBy)); ?>" class="text-decoration-none hover-effect">
                                                <?php echo e($invitation->invitedBy->getDisplayName()); ?>

                                            </a>
                                        <?php else: ?>
                                            <?php echo e(__('dashboard.user_not_found')); ?>

                                        <?php endif; ?>
                                    </small>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="d-flex gap-1">
                                        <form action="<?php echo e(route('group-invitations.accept', $invitation)); ?>" method="POST" class="d-inline group-invitation-form" data-invitation-id="<?php echo e($invitation->id); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-success btn-sm" title="<?php echo e(__('dashboard.accept')); ?>">
                                                <i class="ph ph-check f-s-12"></i>
                                            </button>
                                        </form>
                                        <form action="<?php echo e(route('group-invitations.decline', $invitation)); ?>" method="POST" class="d-inline group-invitation-form" data-invitation-id="<?php echo e($invitation->id); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" title="<?php echo e(__('dashboard.decline')); ?>">
                                                <i class="ph ph-x f-s-12"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="text-center mt-3">
                            <a href="<?php echo e(route('group-invitations.index')); ?>" class="btn btn-light-primary btn-sm">
                                <i class="ph ph-eye me-1"></i><?php echo e(__('dashboard.view_all_group_invitations')); ?>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Role-Specific Sections -->
            <div class="col-lg-8">
                <div class="row g-3">
                    <?php if(isset($roleContent['poet'])): ?>
                        <div class="col-md-6">
                            <div class="card card-light-success hover-effect equal-card">
                                <div class="card-body text-center pa-20">
                                    <div class="bg-success h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-pen-nib f-s-20 text-white"></i>
                                    </div>
                                    <h6 class="text-success f-w-600 mb-1"><?php echo e(__('dashboard.poet_section')); ?></h6>
                                    <p class="text-muted f-s-12 mb-2"><?php echo e(__('dashboard.poet_section_description')); ?></p>
                                    <a href="#" class="btn btn-success btn-sm">
                                        <i class="ph ph-arrow-right me-1"></i><?php echo e(__('dashboard.access_section')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($roleContent['organizer'])): ?>
                        <div class="col-md-6">
                            <div class="card card-light-danger hover-effect equal-card">
                                <div class="card-body text-center pa-20">
                                    <div class="bg-danger h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-calendar-plus f-s-20 text-white"></i>
                                    </div>
                                    <h6 class="text-danger f-w-600 mb-1"><?php echo e(__('dashboard.organizer_section')); ?></h6>
                                    <p class="text-muted f-s-12 mb-2"><?php echo e(__('dashboard.organizer_section_description')); ?></p>
                                    <a href="#" class="btn btn-danger btn-sm">
                                        <i class="ph ph-arrow-right me-1"></i><?php echo e(__('dashboard.access_section')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($roleContent['venue_owner'])): ?>
                        <div class="col-md-6">
                            <div class="card card-light-info hover-effect equal-card">
                                <div class="card-body text-center pa-20">
                                    <div class="bg-info h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-buildings f-s-20 text-white"></i>
                                    </div>
                                    <h6 class="text-info f-w-600 mb-1"><?php echo e(__('dashboard.venue_section')); ?></h6>
                                    <p class="text-muted f-s-12 mb-2"><?php echo e(__('dashboard.venue_section_description')); ?></p>
                                    <a href="#" class="btn btn-info btn-sm">
                                        <i class="ph ph-arrow-right me-1"></i><?php echo e(__('dashboard.access_section')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>


    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('assets/vendor/fullcalendar/global.js')); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestione inviti
    const invitationForms = document.querySelectorAll('.invitation-form');
    invitationForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const invitationId = this.getAttribute('data-invitation-id');
            const invitationRow = this.closest('.d-flex.align-items-center');

            // Nascondi immediatamente la riga dell'invito
            if (invitationRow) {
                invitationRow.style.opacity = '0.5';
                invitationRow.style.pointerEvents = 'none';
            }

            // Disabilita i pulsanti per evitare doppi click
            const buttons = this.querySelectorAll('button');
            buttons.forEach(button => {
                button.disabled = true;
                button.innerHTML = '<i class="ph ph-spinner ph-spin f-s-12"></i>';
            });
        });
    });

    // Calendar
    const calendarEl = document.getElementById('dashboardCalendar');

    if (calendarEl) {
        // Check if FullCalendar is available
        if (typeof FullCalendar === 'undefined') {
            console.error('FullCalendar library not loaded');
            calendarEl.innerHTML = `
                <div class="alert alert-warning text-center">
                    <i class="ph ph-warning me-2"></i>
                    <?php echo e(__('dashboard.calendar_not_available')); ?>

                    <br>
                    <small class="text-muted"><?php echo e(__('dashboard.calendar_reload_page')); ?></small>
                </div>
            `;

            // Show SweetAlert notification
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: '<?php echo e(__('dashboard.calendar')); ?>',
                    text: '<?php echo e(__('dashboard.calendar_not_available')); ?>',
                    confirmButtonText: 'OK'
                });
            }
            return;
        }

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            headerToolbar: false,
            dayMaxEvents: 2,
            moreLinkClick: 'popover',
            locale: 'it',
            firstDay: 1,
            dayHeaderFormat: { weekday: 'short' },
            dayCellDidMount: function(arg) {
                // Add custom styling for today
                if (arg.date.toDateString() === new Date().toDateString()) {
                    arg.el.style.backgroundColor = '#fff3cd';
                }
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                // Fetch events from API
                Promise.all([
                    fetch('/api/events/calendar'),
                    fetch('/wishlist/calendar')
                ])
                .then(responses => Promise.all(responses.map(r => r.json())))
                .then(([eventsData, wishlistData]) => {
                    const events = eventsData.map(event => ({
                        ...event,
                        className: event.className || 'event-participant'
                    }));

                    const wishlistEvents = wishlistData.map(event => ({
                        ...event,
                        className: 'event-wishlisted'
                    }));

                    successCallback([...events, ...wishlistEvents]);
                })
                .catch(error => {
                    console.error('Error fetching calendar events:', error);
                    failureCallback(error);
                });
            },
            eventClick: function(info) {
                // Navigate to event details
                if (info.event.url) {
                    window.location.href = info.event.url;
                }
            },
            eventDidMount: function(info) {
                // Add tooltip
                const event = info.event;
                const tooltip = new bootstrap.Tooltip(info.el, {
                    title: `${event.title}\n${event.start.toLocaleDateString('it-IT')}`,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            }
        });

        calendar.render();

        // Navigation buttons
        document.getElementById('calendarPrev').addEventListener('click', function() {
            calendar.prev();
        });

        document.getElementById('calendarNext').addEventListener('click', function() {
            calendar.next();
        });
    }

    // <?php echo e(__('wishlist.wishlist')); ?> toggle functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.wishlist-toggle')) {
            e.preventDefault();
            const button = e.target.closest('.wishlist-toggle');
            const eventId = button.dataset.eventId;

            fetch(`/wishlist/${eventId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance
                    const icon = button.querySelector('i');
                    if (data.in_wishlist) {
                        button.className = 'btn btn-light-danger btn-sm wishlist-toggle';
                        icon.className = 'ph-duotone ph-heart-fill f-s-12';
                    } else {
                        button.className = 'btn btn-outline-danger btn-sm wishlist-toggle';
                        icon.className = 'ph-duotone ph-heart f-s-12';

                        // Remove the card from the dashboard
                        const card = button.closest('.col-md-6, .col-lg-4');
                        if (card) {
                            card.style.transition = 'opacity 0.3s ease';
                            card.style.opacity = '0';
                            setTimeout(() => {
                                card.remove();

                                // Check if no more wishlist items
                                const remainingItems = document.querySelectorAll('.wishlist-toggle').length;
                                if (remainingItems === 0) {
                                    // Hide the entire wishlist section
                                    const wishlistSection = document.querySelector('.row.mt-4').previousElementSibling;
                                    if (wishlistSection && wishlistSection.querySelector('.wishlist-slider')) {
                                        wishlistSection.remove();
                                    }
                                }
                            }, 300);
                        }
                    }

                    // Show notification
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: data.in_wishlist ? 'success' : 'info',
                            title: data.in_wishlist ? '<?php echo e(__('dashboard.added_to_wishlist')); ?>' : '<?php echo e(__('dashboard.removed_from_wishlist')); ?>',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error toggling wishlist:', error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: '<?php echo e(__('dashboard.error')); ?>',
                        text: '<?php echo e(__('dashboard.error_message')); ?>',
                        confirmButtonText: '<?php echo e(__('dashboard.ok')); ?>'
                    });
                }
            });
        }
    });

    // Gestione form inviti ai gruppi
    const groupInvitationForms = document.querySelectorAll('.group-invitation-form');
    groupInvitationForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const invitationId = this.getAttribute('data-invitation-id');
            const invitationRow = this.closest('.d-flex.align-items-center');

            // Disable form and show loading state
            if (invitationRow) {
                invitationRow.style.opacity = '0.5';
                invitationRow.style.pointerEvents = 'none';
            }

            // Submit form
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: new URLSearchParams(new FormData(this))
            })
            .then(response => response.json())
                        .then(data => {
                if (data.success) {
                    // Show success notification
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo e(__('dashboard.success')); ?>',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // If there's a redirect URL (for accept), redirect
                            if (data.redirect_url) {
                                window.location.href = data.redirect_url;
                            } else {
                                // Remove invitation row for decline
                                if (invitationRow) {
                                    invitationRow.style.transition = 'opacity 0.3s ease';
                                    invitationRow.style.opacity = '0';
                                    setTimeout(() => {
                                        invitationRow.remove();

                                        // Check if no more invitations
                                        const remainingInvitations = document.querySelectorAll('.group-invitation-form').length;
                                        if (remainingInvitations === 0) {
                                            // Hide the entire group invitations section
                                            const groupInvitationsSection = document.querySelector('.col-lg-4:has(.ribbon-primary)');
                                            if (groupInvitationsSection) {
                                                groupInvitationsSection.remove();
                                            }
                                        }
                                    }, 300);
                                }
                            }
                        });
                    } else {
                        // Fallback if Swal is not available
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            location.reload();
                        }
                    }
                } else {
                    // Re-enable form
                    if (invitationRow) {
                        invitationRow.style.opacity = '1';
                        invitationRow.style.pointerEvents = 'auto';
                    }

                    // Show error notification
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: '<?php echo e(__('dashboard.error')); ?>',
                            text: data.message || '<?php echo e(__('dashboard.error_message')); ?>',
                            confirmButtonText: '<?php echo e(__('dashboard.ok')); ?>'
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error handling group invitation:', error);

                // Re-enable form
                if (invitationRow) {
                    invitationRow.style.opacity = '1';
                    invitationRow.style.pointerEvents = 'auto';
                }

                // Show error notification
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: '<?php echo e(__('dashboard.error')); ?>',
                        text: '<?php echo e(__('dashboard.error_message')); ?>',
                        confirmButtonText: '<?php echo e(__('dashboard.ok')); ?>'
                    });
                }
            });
        });
    });
});


</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/dashboard/index.blade.php ENDPATH**/ ?>