<?php $__env->startSection('title', $group->name); ?>

<?php $__env->startSection('css'); ?>
<!-- Slick CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/vendor/slick/slick.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/vendor/slick/slick-theme.css')); ?>">

<style>
/* Stili per lo slider degli eventi del gruppo */
.events-slider {
    position: relative;
    margin: 0 -10px;
}

.events-slider .autoplay-item {
    padding: 0 10px;
}

.events-slider .card {
    height: 100%;
    transition: transform 0.3s ease;
}

.events-slider .card:hover {
    transform: translateY(-5px);
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Header del gruppo -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h2 class="mb-2"><?php echo e($group->name); ?></h2>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="badge bg-<?php echo e($group->visibility == 'public' ? 'success' : 'warning'); ?> fs-6">
                                            <?php echo e(__('groups.visibility_' . $group->visibility)); ?>

                                        </span>
                                        <?php if($group->hasMember(auth()->user())): ?>
                                            <?php $role = $group->getUserRole(auth()->user()); ?>
                                            <?php if($role): ?>
                                            <span class="badge bg-info fs-6">
                                                <?php echo e(__('groups.role_' . $role)); ?>

                                            </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($group->description): ?>
                                        <p class="text-muted mb-3"><?php echo e($group->description); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="dropdown">
                                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="ph-duotone ph-dots-three-outline"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <?php if($group->hasMember(auth()->user())): ?>
                                                <?php if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin')): ?>
                                                <li>
                                                    <a class="dropdown-item" href="<?php echo e(route('groups.edit', $group)); ?>">
                                                        <i class="ph-duotone ph-pencil me-2"></i>
                                                        <?php echo e(__('groups.edit')); ?>

                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="<?php echo e(route('groups.members.index', $group)); ?>">
                                                        <i class="ph-duotone ph-users me-2"></i>
                                                        <?php echo e(__('groups.group_members')); ?>

                                                    </a>
                                                </li>
                                                <?php endif; ?>
                                                <?php if(!$group->hasAdmin(auth()->user())): ?>
                                                <li>
                                                    <form action="<?php echo e(route('groups.leave', $group)); ?>" method="POST" class="d-inline" id="leaveGroupForm">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="button" class="dropdown-item text-danger"
                                                                onclick="confirmLeaveGroup()">
                                                            <i class="ph-duotone ph-sign-out me-2"></i>
                                                            <?php echo e(__('groups.leave')); ?>

                                                        </button>
                                                    </form>
                                                </li>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if($group->visibility == 'public'): ?>
                                                    <li>
                                                        <form action="<?php echo e(route('groups.join', $group)); ?>" method="POST" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="message" value="">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="ph-duotone ph-plus me-2"></i>
                                                                <?php echo e(__('groups.join')); ?>

                                                            </button>
                                                        </form>
                                                    </li>
                                                <?php else: ?>
                                                    <li>
                                                        <form action="<?php echo e(route('groups.requests.store', $group)); ?>" method="POST" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="ph-duotone ph-hand-waving me-2"></i>
                                                                <?php echo e(__('groups.send_request')); ?>

                                                            </button>
                                                        </form>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Immagine del gruppo in grande -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <?php echo group_banner_with_dimensions($group, '100%', '300px', 'w-100'); ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Eventi del gruppo -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-calendar me-2 text-primary"></i>
                        <?php echo e(__('groups.group_events')); ?>

                    </h5>
                    <?php if($group->hasMember(auth()->user())): ?>
                    <a href="<?php echo e(route('events.create', ['group_id' => $group->id])); ?>" class="btn btn-primary btn-sm">
                        <i class="ph-duotone ph-plus me-1"></i>
                        <?php echo e(__('groups.create_group_event')); ?>

                    </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php $groupEvents = $group->linkedEvents()->latest()->take(10)->get(); ?>
                    <?php if($groupEvents->count() > 0): ?>
                        <div class="events-slider app-arrow" id="group-events-slider">
                            <?php $__currentLoopData = $groupEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="autoplay-item">
                                <div class="card overflow-hidden hover-effect">
                                    <?php if($event->image_url): ?>
                                        <img src="<?php echo e($event->image_url); ?>" class="card-img-top" alt="<?php echo e($event->title); ?>" style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <?php
                                            $fallbackImages = [
                                                'assets/images/background/default-event-1.webp',
                                                'assets/images/background/default-event-2.webp',
                                                'assets/images/background/default-event-3.webp',
                                                'assets/images/background/default-event-4.webp'
                                            ];
                                            $randomImage = $fallbackImages[array_rand($fallbackImages)];
                                        ?>
                                        <img src="<?php echo e(asset($randomImage)); ?>" class="card-img-top" alt="<?php echo e($event->title); ?>" style="height: 200px; object-fit: cover;">
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title f-w-600"><?php echo e($event->title); ?></h5>
                                        <p class="card-text text-muted f-s-14">
                                            <i class="ph-duotone ph-map-pin f-s-12 me-1"></i>
                                            <?php echo e($event->venue_name ?: $event->city ?: 'Luogo da definire'); ?>

                                        </p>
                                        <?php if($event->description): ?>
                                            <p class="card-text"><?php echo e(Str::limit($event->description, 80)); ?></p>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="card-text">
                                                <small class="text-body-secondary">
                                                    <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                                    <?php echo e($event->start_datetime->format('d/m/Y H:i')); ?>

                                                </small>
                                            </p>
                                            <div class="d-flex gap-1">
                                                <?php if(auth()->guard()->check()): ?>
                                                    <button class="btn btn-sm btn-outline-danger wishlist-toggle" data-event-id="<?php echo e($event->id); ?>" title="Aggiungi/<?php echo e(__('wishlist.remove_from_wishlist')); ?>">
                                                        <img src="<?php echo e(asset('assets/images/like.png')); ?>" alt="Like" style="width: 16px; height: 16px; filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);">
                                                    </button>
                                                <?php endif; ?>
                                                <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-sm btn-warning">
                                                    <i class="ph-duotone ph-info f-s-14 me-1"></i>Dettagli
                                                </a>
                                                <?php if (isset($component)) { $__componentOriginalcab7032bfdfb17b0d85d7225950dd852 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcab7032bfdfb17b0d85d7225950dd852 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.report-button','data' => ['content' => $event,'type' => 'event','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('report-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($event),'type' => 'event','size' => 'sm']); ?>
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="ph-duotone ph-calendar text-muted f-s-48 mb-3"></i>
                            <p class="text-muted"><?php echo e(__('groups.no_group_events')); ?></p>
                            <?php if($group->hasMember(auth()->user())): ?>
                            <a href="<?php echo e(route('events.create', ['group_id' => $group->id])); ?>" class="btn btn-primary">
                                <i class="ph-duotone ph-plus me-2"></i>
                                <?php echo e(__('groups.create_group_event')); ?>

                            </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Eventi associati al gruppo -->
    <?php if($group->linkedEvents && $group->linkedEvents->count()): ?>
        <div class="row mt-4">
            <div class="col-12">
                <h4 class="mb-3"><i class="ph ph-calendar me-2"></i>Eventi collegati a questo gruppo</h4>
            </div>
            <?php $__currentLoopData = $group->linkedEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card card-light-primary h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-2">
                                <a href="<?php echo e(route('events.show', $event)); ?>" class="text-decoration-none text-primary">
                                    <i class="ph ph-calendar me-1"></i><?php echo e($event->title); ?>

                                </a>
                            </h5>
                            <div class="mb-1">
                                <i class="ph ph-clock me-1"></i><?php echo e(optional($event->start_datetime)->format('d/m/Y H:i')); ?>

                            </div>
                            <div class="mb-1">
                                <i class="ph ph-map-pin me-1"></i><?php echo e($event->city); ?>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <!-- Statistiche ridotte -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="d-flex flex-column align-items-center">
                                <i class="ph-duotone ph-users text-primary f-s-24 mb-1"></i>
                                <h6 class="text-primary mb-0"><?php echo e($group->getMembersCount()); ?></h6>
                                <small class="text-muted"><?php echo e(__('groups.total_members')); ?></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex flex-column align-items-center">
                                <i class="ph-duotone ph-crown text-success f-s-24 mb-1"></i>
                                <h6 class="text-success mb-0"><?php echo e($group->getAdmins()->count()); ?></h6>
                                <small class="text-muted"><?php echo e(__('groups.admins_count')); ?></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex flex-column align-items-center">
                                <i class="ph-duotone ph-shield-check text-info f-s-24 mb-1"></i>
                                <h6 class="text-info mb-0"><?php echo e($group->getModerators()->count()); ?></h6>
                                <small class="text-muted"><?php echo e(__('groups.moderators_count')); ?></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex flex-column align-items-center">
                                <i class="ph-duotone ph-calendar text-warning f-s-24 mb-1"></i>
                                <h6 class="text-warning mb-0"><?php echo e($group->events()->count()); ?></h6>
                                <small class="text-muted"><?php echo e(__('groups.group_events')); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenuto principale -->
    <div class="row">
        <!-- Informazioni del gruppo -->
        <div class="col-12 col-lg-8">

            <!-- Membri recenti -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-users me-2 text-success"></i>
                        <?php echo e(__('groups.group_members')); ?>

                    </h5>
                    <a href="<?php echo e(route('groups.members.index', $group)); ?>" class="btn btn-outline-primary btn-sm">
                        <?php echo e(__('common.view_all')); ?>

                    </a>
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $group->members()->with('user')->latest()->take(6)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($member->user)); ?>"
                                 alt="<?php echo e($member->user->getDisplayName()); ?>"
                                 class="rounded-circle"
                                 style="width: 40px; height: 40px; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">
                                <a href="<?php echo e(route('user.show', $member->user)); ?>" class="text-decoration-none hover-effect">
                                    <?php echo e($member->user->getDisplayName()); ?>

                                </a>
                            </h6>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-<?php echo e($member->role == 'admin' ? 'success' : ($member->role == 'moderator' ? 'info' : 'secondary')); ?>">
                                    <?php echo e(__('groups.role_' . $member->role)); ?>

                                </span>
                                <small class="text-muted">
                                    <?php echo e(__('groups.member_since')); ?> <?php echo e($member->joined_at->format('d/m/Y')); ?>

                                </small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4">
                        <i class="ph-duotone ph-users text-muted f-s-48 mb-3"></i>
                        <p class="text-muted"><?php echo e(__('groups.no_members')); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Bacheca annunci -->
            <?php if($group->hasMember(auth()->user())): ?>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-news me-2 text-primary"></i>
                        Bacheca
                    </h5>
                    <a href="<?php echo e(route('groups.announcements.index', $group)); ?>" class="btn btn-primary btn-sm">
                        Vedi tutti
                    </a>
                </div>
                <div class="card-body">
                    <?php
                        $recentAnnouncements = $group->announcements()
                            ->active()
                            ->with(['author'])
                            ->orderBy('is_pinned', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->limit(3)
                            ->get();
                    ?>
                    
                    <?php $__empty_1 = true; $__currentLoopData = $recentAnnouncements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="announcement-item mb-3 pb-3 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0">
                                    <?php if($announcement->is_pinned): ?>
                                        <i class="ph-duotone ph-pin text-warning me-1" title="Annuncio pinnato"></i>
                                    <?php endif; ?>
                                    <?php echo e($announcement->title); ?>

                                </h6>
                                <small class="text-muted"><?php echo e($announcement->created_at->format('d/m')); ?></small>
                            </div>
                            <p class="text-muted small mb-2">
                                <?php echo e(Str::limit($announcement->content, 100)); ?>

                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="ph-duotone ph-user me-1"></i>
                                    <?php echo e($announcement->author->name); ?>

                                </small>
                                <a href="<?php echo e(route('groups.announcements.show', [$group, $announcement])); ?>" 
                                   class="btn btn-sm btn-primary">
                                    Leggi
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-3">
                            <i class="ph-duotone ph-news f-s-32 text-muted mb-2"></i>
                            <p class="text-muted mb-0">Nessun annuncio ancora</p>
                            <a href="<?php echo e(route('groups.announcements.create', $group)); ?>" class="btn btn-sm btn-primary mt-2">
                                Crea annuncio
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar con informazioni aggiuntive -->
        <div class="col-12 col-lg-4">
            <!-- Informazioni del gruppo -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph-duotone ph-info me-2 text-info"></i>
                        <?php echo e(__('groups.group_info')); ?>

                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong><?php echo e(__('groups.created_by')); ?>:</strong>
                        <div class="d-flex align-items-center mt-1">
                                                    <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($group->creator)); ?>"
                             alt="<?php echo e($group->creator->getDisplayName()); ?>"
                             class="rounded-circle me-2"
                             style="width: 30px; height: 30px; object-fit: cover;">
                            <a href="<?php echo e(route('user.show', $group->creator)); ?>" class="text-decoration-none hover-effect">
                                <?php echo e($group->creator->getDisplayName()); ?>

                            </a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong><?php echo e(__('groups.created_at')); ?>:</strong>
                        <div class="text-muted"><?php echo e($group->created_at->format('d/m/Y H:i')); ?></div>
                    </div>
                    <?php if($group->hasMember(auth()->user())): ?>
                    <div>
                        <strong><?php echo e(__('groups.member_since')); ?>:</strong>
                        <div class="text-muted">
                            <?php echo e($group->members()->where('user_id', auth()->id())->first()->joined_at->format('d/m/Y')); ?>

                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Azioni rapide -->
            <?php if($group->hasMember(auth()->user())): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph-duotone ph-lightning me-2 text-warning"></i>
                        <?php echo e(__('groups.quick_actions')); ?>

                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('groups.members.index', $group)); ?>" class="btn btn-primary btn-sm">
                            <i class="ph-duotone ph-users me-2"></i>
                            <?php echo e(__('groups.view_members')); ?>

                        </a>
                        <a href="<?php echo e(route('groups.announcements.create', $group)); ?>" class="btn btn-success btn-sm">
                            <i class="ph-duotone ph-plus me-2"></i>
                            Nuovo annuncio
                        </a>
                        <?php if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin')): ?>
                        <a href="<?php echo e(route('groups.edit', $group)); ?>" class="btn btn-warning btn-sm">
                            <i class="ph-duotone ph-pencil me-2"></i>
                            Modifica gruppo
                        </a>
                        <a href="<?php echo e(route('groups.invitations.pending', $group)); ?>" class="btn btn-info btn-sm">
                            <i class="ph-duotone ph-envelope me-2"></i>
                            <?php echo e(__('groups.manage_invitations')); ?>

                        </a>
                        <a href="<?php echo e(route('groups.requests.pending', $group)); ?>" class="btn btn-secondary btn-sm">
                            <i class="ph-duotone ph-hand-waving me-2"></i>
                            <?php echo e(__('groups.manage_requests')); ?>

                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Social Links -->
            <?php if (isset($component)) { $__componentOriginal2d1b76dfc152c08a6bc56c8cae31f509 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2d1b76dfc152c08a6bc56c8cae31f509 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.group-social-links','data' => ['group' => $group]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('group-social-links'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['group' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($group)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2d1b76dfc152c08a6bc56c8cae31f509)): ?>
<?php $attributes = $__attributesOriginal2d1b76dfc152c08a6bc56c8cae31f509; ?>
<?php unset($__attributesOriginal2d1b76dfc152c08a6bc56c8cae31f509); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2d1b76dfc152c08a6bc56c8cae31f509)): ?>
<?php $component = $__componentOriginal2d1b76dfc152c08a6bc56c8cae31f509; ?>
<?php unset($__componentOriginal2d1b76dfc152c08a6bc56c8cae31f509); ?>
<?php endif; ?>

            <!-- Statistiche avanzate -->
            <?php if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin')): ?>
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph-duotone ph-chart-line me-2 text-success"></i>
                        <?php echo e(__('groups.stats')); ?>

                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span><?php echo e(__('groups.pending_invitations')); ?>:</span>
                            <span class="badge bg-warning"><?php echo e($group->getPendingInvitations()->count()); ?></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span><?php echo e(__('groups.pending_requests')); ?>:</span>
                            <span class="badge bg-info"><?php echo e($group->getPendingJoinRequests()->count()); ?></span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between">
                            <span><?php echo e(__('groups.group_events')); ?>:</span>
                            <span class="badge bg-primary"><?php echo e($group->events()->count()); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- Slick JS -->
<script src="<?php echo e(asset('assets/vendor/slick/slick.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/slick.js')); ?>"></script>

<script>
function confirmLeaveGroup() {
    Swal.fire({
        title: '<?php echo e(__("groups.confirm_leave_title")); ?>',
        text: '<?php echo e(__("groups.confirm_leave")); ?>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<?php echo e(__("groups.leave")); ?>',
        cancelButtonText: '<?php echo e(__("common.cancel")); ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('leaveGroupForm').submit();
        }
    });
}

// Inizializza lo slider degli eventi del gruppo
$(document).ready(function() {
    // Verifica se Slick è disponibile
    if (typeof $.fn.slick === 'undefined') {
        console.error('Slick non è caricato!');
        return;
    }

    const $groupSlider = $('#group-events-slider');
    if ($groupSlider.length > 0) {
        $groupSlider.slick({
            slidesToShow: 2,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true,
            dots: false,
            infinite: true,
            speed: 500,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/groups/show.blade.php ENDPATH**/ ?>