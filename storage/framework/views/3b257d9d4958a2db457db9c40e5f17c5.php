<?php $__env->startSection('title', __('gigs.title')); ?>

<?php $__env->startSection('styles'); ?>
<style>
.f-s-10 { font-size: 10px !important; }
.f-s-12 { font-size: 12px !important; }
.f-s-14 { font-size: 14px !important; }

@media (max-width: 768px) {
    .f-s-10 { font-size: 9px !important; }
    .f-s-12 { font-size: 11px !important; }
    .f-s-14 { font-size: 13px !important; }

    .btn-sm {
        padding: 4px 8px !important;
        font-size: 12px !important;
        min-width: 32px !important;
        min-height: 32px !important;
    }
    
    .card-body {
        padding: 16px !important;
    }
    
    .card-title {
        font-size: 16px !important;
        line-height: 1.4 !important;
        word-wrap: break-word !important;
    }
    
    .badge {
        font-size: 9px !important;
        padding: 4px 6px !important;
    }
    
    .form-control-sm, .form-select-sm {
        font-size: 14px !important;
        padding: 8px 12px !important;
    }
}
</style>
<?php $__env->stopSection(); ?>

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
                        <li class="breadcrumb-item active">
                            <i class="ph ph-briefcase me-1"></i><?php echo e(__('gigs.title')); ?>

                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Mobile-First Statistiche -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card card-light-primary hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-primary rounded">
                                        <i class="ph ph-briefcase text-white f-s-14"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-1 f-s-12 f-w-500"><?php echo e(__('gigs.stats.total_gigs')); ?></h6>
                                <h4 class="mb-0 f-s-18 f-w-600"><?php echo e(number_format($stats['total_gigs'])); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-light-success hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-success rounded">
                                        <i class="ph ph-check-circle text-white f-s-14"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-1 f-s-12 f-w-500"><?php echo e(__('gigs.stats.open_gigs_count')); ?></h6>
                                <h4 class="mb-0 f-s-18 f-w-600"><?php echo e(number_format($stats['open_gigs_count'])); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-light-warning hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-warning rounded">
                                        <i class="ph ph-warning text-white f-s-14"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-1 f-s-12 f-w-500"><?php echo e(__('gigs.stats.urgent_gigs_count')); ?></h6>
                                <h4 class="mb-0 f-s-18 f-w-600"><?php echo e(number_format($stats['urgent_gigs_count'])); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-light-info hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-info rounded">
                                        <i class="ph ph-users text-white f-s-14"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-1 f-s-12 f-w-500"><?php echo e(__('gigs.stats.total_applications')); ?></h6>
                                <h4 class="mb-0 f-s-18 f-w-600"><?php echo e(number_format($gigs->sum('application_count'))); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile-First Filtri e Ricerca -->
        <div class="card hover-effect">
            <div class="card-header">
                <h5 class="card-title mb-0 f-s-16 f-w-600">
                    <i class="ph ph-funnel me-2"></i><?php echo e(__('gigs.filters.title')); ?>

                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('gigs.index')); ?>" class="row g-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="search" class="form-label f-s-14 f-w-500"><?php echo e(__('gigs.filters.search')); ?></label>
                        <input type="text" class="form-control form-control-sm" id="search" name="search"
                               value="<?php echo e(request('search')); ?>"
                               placeholder="<?php echo e(__('gigs.filters.search')); ?>">
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="category" class="form-label f-s-14 f-w-500"><?php echo e(__('gigs.filters.filter_by_category')); ?></label>
                        <select class="form-select form-select-sm" id="category" name="category">
                            <option value=""><?php echo e(__('common.all')); ?></option>
                            <?php $__currentLoopData = __('gigs.categories'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e(request('category') == $key ? 'selected' : ''); ?>>
                                    <?php echo e($category); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="type" class="form-label f-s-14 f-w-500"><?php echo e(__('gigs.filters.filter_by_type')); ?></label>
                        <select class="form-select form-select-sm" id="type" name="type">
                            <option value=""><?php echo e(__('common.all')); ?></option>
                            <?php $__currentLoopData = __('gigs.types'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e(request('type') == $key ? 'selected' : ''); ?>>
                                    <?php echo e($type); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="sort" class="form-label f-s-14 f-w-500"><?php echo e(__('gigs.filters.sort_by')); ?></label>
                        <select class="form-select form-select-sm" id="sort" name="sort">
                            <?php $__currentLoopData = __('gigs.filters.sort_options'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e(request('sort', 'recent') == $key ? 'selected' : ''); ?>>
                                    <?php echo e($option); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label class="form-label f-s-14 f-w-500">&nbsp;</label>
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="ph ph-magnifying-glass me-1"></i><?php echo e(__('common.search')); ?>

                            </button>
                            <a href="<?php echo e(route('gigs.index')); ?>" class="btn btn-light btn-sm">
                                <i class="ph ph-arrows-clockwise me-1"></i><?php echo e(__('common.reset')); ?>

                            </a>
                        </div>
                    </div>
                </form>

                <!-- Mobile-First Filtri rapidi -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remote" name="remote"
                                       value="1" <?php echo e(request('remote') ? 'checked' : ''); ?> onchange="this.form.submit()">
                                <label class="form-check-label f-s-14" for="remote">
                                    <?php echo e(__('gigs.filters.show_remote')); ?>

                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="urgent" name="urgent"
                                       value="1" <?php echo e(request('urgent') ? 'checked' : ''); ?> onchange="this.form.submit()">
                                <label class="form-check-label f-s-14" for="urgent">
                                    <?php echo e(__('gigs.filters.show_urgent')); ?>

                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="featured" name="featured"
                                       value="1" <?php echo e(request('featured') ? 'checked' : ''); ?> onchange="this.form.submit()">
                                <label class="form-check-label f-s-14" for="featured">
                                    <?php echo e(__('gigs.filters.show_featured')); ?>

                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile-First Sezione Organizzatore -->
        <?php if($showOrganizerSection && $userEvents->count() > 0): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card hover-effect">
                    <div class="card-header">
                        <h5 class="card-title mb-0 f-s-16 f-w-600">
                            <i class="ph ph-calendar me-2"></i><?php echo e(__('gigs.organizer_section.title')); ?>

                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php $__currentLoopData = $userEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card hover-effect h-100">
                                    <div class="card-body d-flex flex-column p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="flex-grow-1 me-2">
                                                <h6 class="card-title mb-2 fw-bold f-s-14" style="word-wrap: break-word; line-height: 1.4;">
                                                    <a href="<?php echo e(route('events.show', $event)); ?>" class="text-decoration-none text-dark">
                                                        <?php echo e(Str::limit($event->title, 50)); ?>

                                                    </a>
                                                </h6>
                                                <?php if($event->subtitle): ?>
                                                    <h6 class="text-muted mb-2 f-s-12" style="word-wrap: break-word;"><?php echo e(Str::limit($event->subtitle, 40)); ?></h6>
                                                <?php endif; ?>
                                                <p class="text-muted mb-2 f-s-12" style="word-wrap: break-word; line-height: 1.4;">
                                                    <?php echo e(Str::limit($event->description, 60)); ?>

                                                </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="bg-light-primary text-center d-flex flex-column align-items-center justify-content-center" style="min-width: 40px; min-height: 40px; font-size: 10px; border-radius: 6px;">
                                                    <div class="fw-bold f-s-12"><?php echo e($event->start_datetime->format('d')); ?></div>
                                                    <div class="f-s-10"><?php echo e($event->start_datetime->format('M')); ?></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex align-items-center text-muted mb-1">
                                                <i class="ph ph-clock me-1 f-s-12"></i>
                                                <span class="f-s-12"><?php echo e($event->start_datetime->format('H:i')); ?> - <?php echo e($event->end_datetime->format('H:i')); ?></span>
                                            </div>
                                            <div class="d-flex align-items-center text-muted mb-1">
                                                <i class="ph ph-user me-1 f-s-12"></i>
                                                <span class="f-s-12">
                                                    <a href="<?php echo e(route('user.show', $event->organizer)); ?>" class="text-decoration-none hover-effect">
                                                        <?php echo e($event->organizer->getDisplayName()); ?>

                                                    </a>
                                                </span>
                                            </div>
                                            <?php if($event->is_online): ?>
                                                <div class="d-flex align-items-center text-muted mb-1">
                                                    <i class="ph ph-globe me-1 f-s-12"></i>
                                                    <span class="f-s-12"><?php echo e($event->timezone); ?></span>
                                                </div>
                                            <?php else: ?>
                                                <div class="d-flex align-items-center text-muted mb-1">
                                                    <i class="ph ph-map-pin me-1 f-s-12"></i>
                                                    <span class="f-s-12"><?php echo e($event->city); ?>, <?php echo e($event->country); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="mt-auto">
                                            <!-- Event Info -->
                                            <div class="d-flex flex-wrap align-items-center gap-1 mb-2">
                                                <?php if($event->entry_fee > 0): ?>
                                                    <span class="badge bg-warning f-s-10"><?php echo e(__('events.entry_fee')); ?>: €<?php echo e($event->entry_fee); ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-success f-s-10"><?php echo e(__('events.free')); ?></span>
                                                <?php endif; ?>
                                                <?php if($event->max_participants): ?>
                                                    <small class="text-muted f-s-10"><?php echo e(__('events.max_participants')); ?>: <?php echo e($event->max_participants); ?></small>
                                                <?php endif; ?>
                                                <span class="badge bg-primary f-s-10">
                                                    <?php echo e($event->gigs()->count()); ?> <?php echo e(__('gigs.organizer_section.gigs')); ?>

                                                </span>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="d-flex flex-column flex-sm-row gap-2">
                                                <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-primary btn-sm">
                                                    <i class="ph ph-eye me-1"></i><?php echo e(__('common.view')); ?>

                                                </a>
                                                <a href="<?php echo e(route('gigs.create')); ?>?event=<?php echo e($event->id); ?>" class="btn btn-light btn-sm">
                                                    <i class="ph ph-plus me-1"></i><?php echo e(__('gigs.organizer_section.add_gig')); ?>

                                                </a>
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

        <!-- Mobile-First Azioni principali -->
        <?php if(auth()->guard()->check()): ?>
            <?php if (! (auth()->user()->hasRole('audience'))): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <a href="<?php echo e(route('gigs.create')); ?>" class="btn btn-primary btn-sm">
                                <i class="ph ph-plus me-2"></i><?php echo e(__('gigs.create_gig')); ?>

                            </a>
                            <a href="<?php echo e(route('gigs.my-gigs')); ?>" class="btn btn-light btn-sm">
                                <i class="ph ph-briefcase me-2"></i><?php echo e(__('gigs.my_gigs')); ?>

                            </a>
                            <a href="<?php echo e(route('gigs.my-applications')); ?>" class="btn btn-light btn-sm">
                                <i class="ph ph-user-plus me-2"></i><?php echo e(__('gigs.applications.my_applications')); ?>

                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Mobile-First Lista Gigs -->
        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $gigs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gig): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-12">
                    <div class="card hover-effect mb-3">
                        <div class="card-body p-3">
                            <!-- Header con badge di stato -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1 me-3">
                                    <h6 class="card-title mb-1 f-s-16 f-w-600" style="word-wrap: break-word; line-height: 1.4;">
                                        <a href="<?php echo e(route('gigs.show', $gig)); ?>" class="text-decoration-none hover-effect">
                                            <?php echo e($gig->title); ?>

                                        </a>
                                    </h6>
                                    <p class="text-muted f-s-12 mb-0">
                                        <i class="ph ph-user me-1"></i>
                                        <a href="<?php echo e(route('user.show', $gig->user)); ?>" class="text-decoration-none hover-effect">
                                            <?php echo e($gig->user->getDisplayName()); ?>

                                        </a>
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <?php if($gig->is_urgent): ?>
                                        <span class="badge bg-warning f-s-10">
                                            <i class="ph ph-warning me-1"></i><?php echo e(__('gigs.status.urgent')); ?>

                                        </span>
                                    <?php elseif($gig->is_featured): ?>
                                        <span class="badge bg-info f-s-10">
                                            <i class="ph ph-star me-1"></i><?php echo e(__('gigs.status.featured')); ?>

                                        </span>
                                    <?php elseif($gig->is_closed): ?>
                                        <span class="badge bg-secondary f-s-10">
                                            <i class="ph ph-lock me-1"></i><?php echo e(__('gigs.status.closed')); ?>

                                        </span>
                                    <?php elseif($gig->is_expired): ?>
                                        <span class="badge bg-danger f-s-10">
                                            <i class="ph ph-clock me-1"></i><?php echo e(__('gigs.status.expired')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success f-s-10">
                                            <i class="ph ph-check-circle me-1"></i><?php echo e(__('gigs.status.open')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Descrizione -->
                            <p class="card-text text-muted f-s-12 mb-3" style="word-wrap: break-word; line-height: 1.4;">
                                <?php echo e(Str::limit($gig->description, 100)); ?>

                            </p>

                            <!-- Categorie e tipo -->
                            <div class="mb-3">
                                <span class="badge bg-light-primary me-1 f-s-10">
                                    <?php echo e(__('gigs.categories.' . $gig->category)); ?>

                                </span>
                                <span class="badge bg-light-primary me-1 f-s-10">
                                    <?php echo e(__('gigs.types.' . $gig->type)); ?>

                                </span>
                                <?php if($gig->is_remote): ?>
                                    <span class="badge bg-light-success f-s-10">
                                        <i class="ph ph-globe me-1"></i><?php echo e(__('gigs.fields.is_remote')); ?>

                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Informazioni aggiuntive -->
                            <div class="row text-center mb-3 g-2">
                                <div class="col-4">
                                    <small class="text-muted d-block f-s-10"><?php echo e(__('gigs.stats.applications')); ?></small>
                                    <?php if($gig->application_count > 0): ?>
                                        <a href="<?php echo e(route('gigs.manage-applications', $gig)); ?>" class="text-decoration-none">
                                            <strong class="text-primary f-s-12"><?php echo e($gig->application_count); ?></strong>
                                            <i class="ph ph-arrow-right ms-1 f-s-10"></i>
                                        </a>
                                    <?php else: ?>
                                        <strong class="f-s-12"><?php echo e($gig->application_count); ?></strong>
                                    <?php endif; ?>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block f-s-10"><?php echo e(__('gigs.stats.accepted_applications_count')); ?></small>
                                    <strong class="text-success f-s-12"><?php echo e($gig->accepted_applications_count); ?></strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block f-s-10"><?php echo e(__('gigs.fields.deadline')); ?></small>
                                    <strong class="f-s-12"><?php echo e($gig->deadline ? $gig->deadline->format('d/m/Y') : 'N/A'); ?></strong>
                                </div>
                            </div>

                            <!-- Compenso e località -->
                            <div class="mb-3">
                                <?php if($gig->compensation): ?>
                                    <div class="text-success f-s-12">
                                        <i class="ph ph-currency-eur me-1"></i><?php echo e($gig->compensation); ?>

                                    </div>
                                <?php endif; ?>
                                <?php if($gig->location): ?>
                                    <div class="text-muted f-s-12">
                                        <i class="ph ph-map-pin me-1"></i><?php echo e($gig->location); ?>

                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Azioni -->
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <a href="<?php echo e(route('gigs.show', $gig)); ?>" class="btn btn-primary btn-sm">
                                    <i class="ph ph-eye me-1"></i><?php echo e(__('gigs.actions.read')); ?>

                                </a>
                                <?php if(auth()->guard()->check()): ?>
                                    <?php if (! (auth()->user()->hasRole('audience'))): ?>
                                        <?php if($gig->can_apply): ?>
                                            <button class="btn btn-success btn-sm" onclick="applyToGig(<?php echo e($gig->id); ?>)">
                                                <i class="ph ph-user-plus me-1"></i><?php echo e(__('gigs.apply_gig')); ?>

                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-light btn-sm" disabled>
                                                <i class="ph ph-lock me-1"></i><?php echo e(__('gigs.status.closed')); ?>

                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="<?php echo e(route('login')); ?>" class="btn btn-light btn-sm">
                                        <i class="ph ph-sign-in me-1"></i><?php echo e(__('gigs.messages.login_to_interact')); ?>

                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ph ph-briefcase text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3"><?php echo e(__('gigs.messages.no_gigs_found')); ?></h5>
                            <p class="text-muted"><?php echo e(__('gigs.messages.no_gigs_description')); ?></p>
                            <?php if(auth()->guard()->check()): ?>
                                <?php if (! (auth()->user()->hasRole('audience'))): ?>
                                    <a href="<?php echo e(route('gigs.create')); ?>" class="btn btn-primary">
                                        <i class="ph ph-plus me-2"></i><?php echo e(__('gigs.create_gig')); ?>

                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Paginazione -->
        <?php if($gigs->hasPages()): ?>
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <?php echo e($gigs->appends(request()->query())->links()); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal per candidatura -->
<div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applyModalLabel"><?php echo e(__('gigs.applications.apply')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="applyForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="message" class="form-label"><?php echo e(__('gigs.applications.message')); ?> <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="message" name="message" rows="4"
                                  placeholder="<?php echo e(__('gigs.applications.message_placeholder')); ?>" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="experience" class="form-label"><?php echo e(__('gigs.applications.experience')); ?></label>
                        <textarea class="form-control" id="experience" name="experience" rows="3"
                                  placeholder="<?php echo e(__('gigs.applications.experience_placeholder')); ?>"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="portfolio" class="form-label"><?php echo e(__('gigs.applications.portfolio')); ?></label>
                        <input type="text" class="form-control" id="portfolio" name="portfolio"
                               placeholder="<?php echo e(__('gigs.applications.portfolio_placeholder')); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="availability" class="form-label"><?php echo e(__('gigs.applications.availability')); ?></label>
                        <textarea class="form-control" id="availability" name="availability" rows="2"
                                  placeholder="<?php echo e(__('gigs.applications.availability_placeholder')); ?>"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="compensation_expectation" class="form-label"><?php echo e(__('gigs.applications.compensation_expectation')); ?></label>
                        <input type="text" class="form-control" id="compensation_expectation" name="compensation_expectation"
                               placeholder="<?php echo e(__('gigs.applications.compensation_expectation_placeholder')); ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('common.cancel')); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo e(__('gigs.applications.submit_application')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Fallback per toastr se viene caricato da qualche parte
if (typeof toastr === 'undefined') {
    window.toastr = {
        success: function(message) {
            Swal.fire('Successo!', message, 'success');
        },
        error: function(message) {
            Swal.fire('Errore!', message, 'error');
        },
        warning: function(message) {
            Swal.fire('Attenzione!', message, 'warning');
        },
        info: function(message) {
            Swal.fire('Info', message, 'info');
        }
    };
}

let currentGigId = null;

function applyToGig(gigId) {
    currentGigId = gigId;
    $('#applyModal').modal('show');
}

$('#applyForm').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(`/gigs/${currentGigId}/apply`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire(
                'Candidatura Inviata!',
                data.message,
                'success'
            ).then(() => {
                $('#applyModal').modal('hide');
                $('#applyForm')[0].reset();
                location.reload();
            });
        } else {
            Swal.fire(
                'Errore!',
                data.error || 'Errore durante l\'invio della candidatura',
                'error'
            );
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire(
            'Errore!',
            'Errore di connessione o server non disponibile',
            'error'
        );
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/gigs/index.blade.php ENDPATH**/ ?>