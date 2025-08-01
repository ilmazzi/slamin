<?php $__env->startSection('title', request('filter') ? __('dashboard.' . request('filter') . '_events') : __('events.events_poetry_slam')); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/vendor/leafletmaps/leaflet.css')); ?>">
<style>
            .custom-marker { background: transparent; border: none; }
            </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>
    <?php if(request('filter')): ?>
        <?php switch(request('filter')):
            case ('past'): ?>
                <?php echo e(__('dashboard.past_events')); ?>

                <?php break; ?>
            <?php case ('future'): ?>
                <?php echo e(__('dashboard.future_events')); ?>

                <?php break; ?>
            <?php case ('organized'): ?>
                <?php echo e(__('dashboard.organized_events')); ?>

                <?php break; ?>
            <?php case ('invitations'): ?>
                <?php echo e(__('dashboard.pending_invitations')); ?>

                <?php break; ?>
            <?php default: ?>
                <?php echo e(__('events.events_poetry_slam')); ?>

        <?php endswitch; ?>
    <?php else: ?>
        <?php echo e(__('events.events_poetry_slam')); ?>

    <?php endif; ?>
</h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('events.dashboard')); ?></a></li>
<li class="breadcrumb-item active"><?php echo e(__('events.events')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">

    <!-- Map Container (Always Visible) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div id="eventsMap" style="height: 400px; border-radius: 10px; overflow: hidden; position: relative;">
                        <div class="map-controls position-absolute top-0 end-0 p-3" style="z-index: 1000;">
                            <button class="btn btn-light btn-sm mb-2 d-block" onclick="centerOnUser()" title="Centra sulla mia posizione (richiede HTTPS)">
                                <i class="ph ph-crosshairs"></i>
                            </button>
                            <button class="btn btn-light btn-sm mb-2 d-block" onclick="refreshEvents()" title="Aggiorna eventi">
                                <i class="ph ph-arrow-clockwise"></i>
                            </button>
                            <button class="btn btn-light btn-sm d-block" onclick="showAllEvents()" title="Mostra tutti gli eventi">
                                <i class="ph ph-globe"></i>
                            </button>
                </div>
                                </div>
                        </div>
                                </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" id="filterForm">
                        <!-- First Row: Main Filters -->
                        <div class="row g-3 mb-3">
                            <div class="col-lg-3 col-md-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-light-primary border-end-0">
                                        <i class="ph ph-magnifying-glass text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0"
                                           placeholder="<?php echo e(__('events.search_events')); ?>"
                                           value="<?php echo e(request('search')); ?>">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <select name="city" class="form-select">
                                    <option value=""><?php echo e(__('events.filter_by_city')); ?></option>
                                    <?php $__currentLoopData = $events->pluck('city')->unique()->filter(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($city); ?>" <?php echo e(request('city') == $city ? 'selected' : ''); ?>>
                                            <?php echo e($city); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <select name="type" class="form-select">
                                    <option value=""><?php echo e(__('events.all_types')); ?></option>
                                    <option value="public" <?php echo e(request('type') === 'public' ? 'selected' : ''); ?>><?php echo e(__('events.public_events')); ?></option>
                                    <option value="private" <?php echo e(request('type') === 'private' ? 'selected' : ''); ?>><?php echo e(__('events.private_events')); ?></option>
                                </select>
                            </div>
                        </div>

                        <!-- Second Row: Quick Filters and Action Buttons -->
                        <div class="row g-3">
                            <div class="col-lg-9 col-md-12">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="bg-light-primary rounded px-3 py-2" data-filter="today" style="cursor: pointer;">
                                        <i class="ph ph-calendar me-1"></i> <?php echo e(__('events.today')); ?>

                                    </span>
                                    <span class="bg-light-info rounded px-3 py-2" data-filter="tomorrow" style="cursor: pointer;">
                                        <i class="ph ph-calendar-plus me-1"></i> <?php echo e(__('events.tomorrow')); ?>

                                    </span>
                                    <span class="bg-light-success rounded px-3 py-2" data-filter="weekend" style="cursor: pointer;">
                                        <i class="ph ph-calendar-check me-1"></i> <?php echo e(__('events.weekend')); ?>

                                    </span>
                                    <span class="bg-light-warning rounded px-3 py-2" data-filter="free" style="cursor: pointer;">
                                        <i class="ph ph-currency-circle-dollar me-1"></i> <?php echo e(__('events.free_events')); ?>

                                    </span>
                                    <span class="bg-light-secondary rounded px-3 py-2" data-filter="nearby" style="cursor: pointer;">
                                        <i class="ph ph-map-pin me-1"></i> <?php echo e(__('events.nearby')); ?>

                                    </span>
                                    <?php if(auth()->guard()->check()): ?>
                                        <span class="bg-light-primary rounded px-3 py-2" data-filter="my" style="cursor: pointer;">
                                            <i class="ph ph-user me-1"></i> <?php echo e(__('events.my_events')); ?>

                                        </span>
                                        <span class="bg-light-warning rounded px-3 py-2" data-filter="private" style="cursor: pointer;">
                                            <i class="ph ph-lock me-1"></i> <?php echo e(__('events.my_private_events')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ph ph-funnel me-1"></i><?php echo e(__('common.filter')); ?>

                                    </button>
                                    <?php if(auth()->guard()->check()): ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('events.create.public')): ?>
                                            <a href="<?php echo e(route('events.create')); ?>" class="btn btn-success">
                                                <i class="ph ph-plus me-1"></i><?php echo e(__('common.create')); ?>

                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Grid with Pagination Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><?php echo e(__('events.events_list')); ?></h5>
                <div class="d-flex align-items-center gap-2">
                    <label class="form-label mb-0"><?php echo e(__('events.show')); ?>:</label>
                    <select class="form-select form-select-sm" style="width: auto;" onchange="changePerPage(this.value)">
                        <option value="10" <?php echo e(request('per_page', 10) == 10 ? 'selected' : ''); ?>>10</option>
                        <option value="20" <?php echo e(request('per_page', 10) == 20 ? 'selected' : ''); ?>>20</option>
                        <option value="50" <?php echo e(request('per_page', 10) == 50 ? 'selected' : ''); ?>>50</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="eventsGrid">
        <?php $__empty_1 = true; $__currentLoopData = $events->take(request('per_page', 10)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 position-relative">
                    <!-- Event Status Badge -->
                    <div class="position-absolute top-0 end-0 p-3" style="z-index: 3;">
                        <?php if($event->is_public): ?>
                            <span class="badge bg-success"><?php echo e(__('events.public')); ?></span>
                        <?php else: ?>
                            <span class="badge bg-warning"><?php echo e(__('events.private')); ?></span>
                        <?php endif; ?>
                        <?php if($event->acceptsRequests()): ?>
                            <span class="badge bg-primary ms-1">
                                <i class="ph ph-check me-1"></i><?php echo e(__('events.apply_to_event')); ?>

                            </span>
                        <?php endif; ?>
                        <?php if($event->category): ?>
                            <span class="badge <?php echo e($event->category_color_class); ?> ms-1">
                                <?php echo e($event->getCategoryDisplayName()); ?>

                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Event Image with Overlay Info -->
                    <div class="position-relative overflow-hidden" style="height: 200px;">
                        <?php if($event->image_url): ?>
                            <img src="<?php echo e($event->image_url); ?>" alt="<?php echo e($event->title); ?>" class="position-absolute w-100 h-100" style="object-fit: cover;">
                            <div class="position-absolute w-100 h-100" style="background: linear-gradient(135deg, rgba(15, 98, 106, 0.7) 0%, rgba(12, 78, 85, 0.7) 100%);"></div>
                        <?php else: ?>
                            <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0f626a 0%, #0c4e55 100%);">
                                <div class="text-center text-white">
                                    <i class="ph ph-calendar f-s-48 mb-2"></i>
                                    <div class="f-s-14 f-w-500"><?php echo e($event->title); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="position-absolute bottom-0 start-0 text-white p-3 w-100" style="z-index: 2;">
                            <?php if($event->is_online): ?>
                                <h6 class="mb-1 text-white"><?php echo e(__('events.online_event')); ?></h6>
                                <small class="text-white-50"><i class="ph ph-globe me-1"></i><?php echo e(__('events.virtual_event')); ?></small>
                            <?php else: ?>
                            <h6 class="mb-1 text-white"><?php echo e($event->venue_name); ?></h6>
                            <small class="text-white-50"><i class="ph ph-map-pin me-1"></i><?php echo e($event->city); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-2 fw-bold">
                                    <a href="<?php echo e(route('events.show', $event)); ?>" class="text-decoration-none text-dark">
                                        <?php echo e($event->title); ?>

                                    </a>
                                </h5>
                                <?php if($event->subtitle): ?>
                                    <h6 class="text-muted mb-2"><?php echo e($event->subtitle); ?></h6>
                                <?php endif; ?>
                                <p class="text-muted mb-2">
                                    <?php echo e(Str::limit($event->description, 80)); ?>

                                </p>
                            </div>
                            <div class="flex-shrink-0 ms-3">
                                <div class="bg-light-primary text-center d-flex flex-column align-items-center justify-content-center" style="min-width: 50px; min-height: 50px; font-size: 12px; border-radius: 8px;">
                                    <div class="fw-bold fs-6"><?php echo e($event->start_datetime->format('d')); ?></div>
                                    <div class="small"><?php echo e($event->start_datetime->format('M')); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex align-items-center text-muted mb-2">
                                <i class="ph ph-clock me-2"></i>
                                <span><?php echo e($event->start_datetime->format('H:i')); ?> - <?php echo e($event->end_datetime->format('H:i')); ?></span>
                            </div>
                            <div class="d-flex align-items-center text-muted mb-2">
                                <i class="ph ph-user me-2"></i>
                                <span><?php echo e($event->organizer->name); ?></span>
                            </div>
                            <?php if($event->is_online): ?>
                                <div class="d-flex align-items-center text-muted mb-2">
                                    <i class="ph ph-globe me-2"></i>
                                    <span><?php echo e($event->timezone); ?></span>
                                </div>
                            <?php else: ?>
                                <div class="d-flex align-items-center text-muted mb-2">
                                    <i class="ph ph-map-pin me-2"></i>
                                    <span><?php echo e($event->city); ?>, <?php echo e($event->country); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-auto">
                            <!-- Event Info -->
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <?php if($event->entry_fee > 0): ?>
                                    <span class="badge bg-warning"><?php echo e(__('events.entry_fee')); ?>: €<?php echo e($event->entry_fee); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?php echo e(__('events.free')); ?></span>
                                <?php endif; ?>
                                <?php if($event->max_participants): ?>
                                    <small class="text-muted"><?php echo e(__('events.max_participants')); ?>: <?php echo e($event->max_participants); ?></small>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Social Actions & Action Buttons -->
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Social Actions -->
                                <?php if(Auth::check()): ?>
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if (isset($component)) { $__componentOriginal723641259025d9a0842581325b5584a2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal723641259025d9a0842581325b5584a2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-like-button','data' => ['content' => $event,'type' => 'event']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-like-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($event),'type' => 'event']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal723641259025d9a0842581325b5584a2)): ?>
<?php $attributes = $__attributesOriginal723641259025d9a0842581325b5584a2; ?>
<?php unset($__attributesOriginal723641259025d9a0842581325b5584a2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal723641259025d9a0842581325b5584a2)): ?>
<?php $component = $__componentOriginal723641259025d9a0842581325b5584a2; ?>
<?php unset($__componentOriginal723641259025d9a0842581325b5584a2); ?>
<?php endif; ?>
                                        <?php if (isset($component)) { $__componentOriginalf1bddb52d2d13581ea13eec9962d253b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf1bddb52d2d13581ea13eec9962d253b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-view-display','data' => ['content' => $event,'type' => 'event']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-view-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($event),'type' => 'event']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf1bddb52d2d13581ea13eec9962d253b)): ?>
<?php $attributes = $__attributesOriginalf1bddb52d2d13581ea13eec9962d253b; ?>
<?php unset($__attributesOriginalf1bddb52d2d13581ea13eec9962d253b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf1bddb52d2d13581ea13eec9962d253b)): ?>
<?php $component = $__componentOriginalf1bddb52d2d13581ea13eec9962d253b; ?>
<?php unset($__componentOriginalf1bddb52d2d13581ea13eec9962d253b); ?>
<?php endif; ?>
                                        <?php if (isset($component)) { $__componentOriginalcab7032bfdfb17b0d85d7225950dd852 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcab7032bfdfb17b0d85d7225950dd852 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.report-button','data' => ['content' => $event,'type' => 'event']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('report-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($event),'type' => 'event']); ?>
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
                                <?php else: ?>
                                    <div></div>
                                <?php endif; ?>
                                
                                <!-- Action Buttons -->
                                <div class="d-flex gap-2">
                                    <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-primary btn-sm">
                                        <i class="ti ti-eye me-1"></i><?php echo e(__('common.view')); ?>

                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('events.manage.own')): ?>
                                        <?php if(Auth::user()->hasRole(['admin', 'moderator']) || $event->organizer_id === Auth::id()): ?>
                                            <button type="button" class="btn btn-light btn-sm" 
                                                    onclick="confirmDeleteEvent(<?php echo e($event->id); ?>, '<?php echo e(addslashes($event->title)); ?>')"
                                                    title="Elimina evento">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                                            </div>
                                        </div>
                                            </div>
                                        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph ph-calendar-x f-s-48 text-muted mb-3"></i>
                        <h5 class="text-muted"><?php echo e(__('events.no_events_found')); ?></h5>
                        <p class="text-muted"><?php echo e(__('events.try_adjusting_filters')); ?></p>
                        <?php if(auth()->guard()->check()): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('events.create.public')): ?>
                                <a href="<?php echo e(route('events.create')); ?>" class="btn btn-primary">
                                    <i class="ph ph-plus me-1"></i><?php echo e(__('events.create_first_event')); ?>

                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
                                </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="ph ph-calendar" style="font-size: 24px;"></i>
                                        </div>
                                    </div>
                    <h4 class="mb-1"><?php echo e($statistics['total_events']); ?></h4>
                    <p class="text-muted mb-0"><?php echo e(__('events.total_events')); ?></p>
                                    </div>
                        </div>
                            </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-light-info d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="ph ph-globe" style="font-size: 24px;"></i>
                                </div>
                                </div>
                    <h4 class="mb-1"><?php echo e($statistics['public_events']); ?></h4>
                    <p class="text-muted mb-0"><?php echo e(__('events.public_events_count')); ?></p>
                                </div>
                                </div>
                            </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-light-success d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="ph ph-clock" style="font-size: 24px;"></i>
                        </div>
                    </div>
                    <h4 class="mb-1"><?php echo e($statistics['upcoming_events']); ?></h4>
                    <p class="text-muted mb-0"><?php echo e(__('events.upcoming_events_count')); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
                <div class="card">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-light-warning d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="ph ph-map-pin" style="font-size: 24px;"></i>
                    </div>
                </div>
                    <h4 class="mb-1"><?php echo e($statistics['cities_count']); ?></h4>
                    <p class="text-muted mb-0"><?php echo e(__('events.cities_count')); ?></p>
            </div>
    </div>
            </div>
        </div>
</div>

    <!-- Event Details Modal -->
    <div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailsModalLabel">Dettagli Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body" id="eventDetailsModalBody">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    <a href="#" class="btn btn-primary" id="eventDetailsModalLink">Vedi Dettagli Completi</a>
                </div>
        </div>
    </div>
</div>

<!-- Delete Event Modal -->
<div class="modal fade" id="deleteEventModal" tabindex="-1" aria-labelledby="deleteEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteEventModalLabel">
                    <i class="ph ph-warning me-2"></i>Elimina Evento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Sei sicuro di voler eliminare l'evento "<strong id="deleteEventTitle"></strong>"?</p>
                <div class="alert alert-warning">
                    <i class="ph ph-warning me-2"></i>
                    <strong>Attenzione:</strong> Questa azione non può essere annullata e:
                    <ul class="mb-0 mt-2">
                        <li>Tutti i partecipanti riceveranno una notifica di cancellazione</li>
                        <li>Tutti gli inviti e le richieste verranno eliminati</li>
                        <li>L'evento verrà rimosso dai preferiti di tutti gli utenti</li>
                        <li>Se l'evento fa parte di un festival, verrà rimosso dal festival</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <form id="deleteEventForm" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="ph ph-trash me-2"></i>Elimina Definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function changePerPage(value) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', value);
    window.location.href = url.toString();
}

function confirmDeleteEvent(eventId, eventTitle) {
    // Set the event title in the modal
    document.getElementById('deleteEventTitle').textContent = eventTitle;
    
    // Set the form action
    document.getElementById('deleteEventForm').action = `/events/${eventId}`;
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('deleteEventModal'));
    modal.show();
}
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('assets/vendor/leafletmaps/leaflet.js')); ?>"></script>
<script>
// Variabili globali per la mappa
let map = null;
let markers = [];

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize map
            initMap();

    // Quick filter functionality
    document.querySelectorAll('[data-filter]').forEach(filter => {
        filter.addEventListener('click', function() {
            const filterType = this.dataset.filter;
            applyQuickFilter(filterType);
        });
    });

    // Live Search
    let searchTimeout;
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });
    }
});

function initMap() {
    console.log('Initializing map...');
    
    // Inizializza la mappa
    map = L.map('eventsMap').setView([41.9028, 12.4964], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Carica gli eventi con i filtri correnti
    loadEventsWithCurrentFilters();
}

function loadEventsWithCurrentFilters() {
    const params = {};
    
    // Ottieni i parametri correnti dall'URL
    const urlParams = new URLSearchParams(window.location.search);
    
    // Applica i filtri correnti
    if (urlParams.has('search')) {
        params.search = urlParams.get('search');
    }
    if (urlParams.has('date_from')) {
        params.date_from = urlParams.get('date_from');
    }
    if (urlParams.has('date_to')) {
        params.date_to = urlParams.get('date_to');
    }
    if (urlParams.has('free_only')) {
        params.free_only = urlParams.get('free_only');
    }
    if (urlParams.has('filter')) {
        params.filter = urlParams.get('filter');
    }
    if (urlParams.has('city')) {
        params.city = urlParams.get('city');
    }
    if (urlParams.has('type')) {
        params.type = urlParams.get('type');
    }
    
    // Applica coordinate solo se esplicitamente specificate o se è il filtro 'nearby'
    if (urlParams.has('lat') && urlParams.has('lng')) {
        params.latitude = parseFloat(urlParams.get('lat'));
        params.longitude = parseFloat(urlParams.get('lng'));
        // Centra la mappa sulla posizione del filtro
        map.setView([params.latitude, params.longitude], 12);
    } else if (urlParams.has('filter') && urlParams.get('filter') === 'nearby') {
        // Per il filtro nearby, usa la posizione dell'utente se disponibile
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                params.latitude = position.coords.latitude;
                params.longitude = position.coords.longitude;
                params.radius = urlParams.get('radius') || '10';
                console.log('Loading events with nearby filter:', params);
                loadEventsOnMapWithFilter(params);
            }, function(error) {
                console.log('Geolocation not available, loading without location filter');
                loadEventsOnMapWithFilter(params);
            });
            return;
        }
    }
    
    console.log('Loading events with current filters:', params);
    loadEventsOnMapWithFilter(params);
}

function loadEventsOnMap(lat = 45.59614070, lng = 8.91219860) {
    loadEventsOnMapWithFilter({
        latitude: lat,
        longitude: lng
    });
}

function loadEventsOnMapWithFilter(params) {
    console.log('loadEventsOnMapWithFilter called with params:', params);
    
    // Clear existing markers
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];

    // Build URL with parameters
    let url;
    if (params.latitude && params.longitude) {
        // Se abbiamo coordinate, usa l'endpoint /api/events/near
        url = new URL('/api/events/near', window.location.origin);
    } else {
        // Se non abbiamo coordinate, usa l'endpoint /api/events (senza filtro di posizione)
        url = new URL('/api/events', window.location.origin);
    }
    
    Object.keys(params).forEach(key => {
        if (params[key] !== null && params[key] !== undefined) {
            url.searchParams.append(key, params[key]);
        }
    });
    
    console.log('Fetching from URL:', url.toString());
    
    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
        return response.json();
    })
    .then(events => {
            console.log('Events received:', events);
            console.log('Number of events:', events.length);
            
            if (events.length === 0) {
                console.log('No events found with current filters');
                showNotification('Nessun evento trovato con i filtri applicati.', 'info');
                return;
            }
            
            events.forEach((event, index) => {
                console.log(`Adding marker ${index + 1}:`, event);
                
            if (event.latitude && event.longitude) {
                    // Determina il colore del marker basato sulla categoria
                    let markerColor = '#6c757d'; // Default secondary (grigio)
                    if (event.category_color_class) {
                        // Mappa le classi CSS ai colori esatti delle categorie (corrispondenti al modello Event)
                        const colorMap = {
                            'bg-primary': '#007bff',          // Concert
                            'bg-secondary': '#6c757d',        // Open Mic
                            'bg-success': '#28a745',          // Festival
                            'bg-danger': '#dc3545',           // Poetry Slam
                            'bg-warning': '#ffc107',          // Workshop
                            'bg-info': '#17a2b8',             // Conference
                            'bg-light': '#f8f9fa',            // Light
                            'bg-dark': '#343a40',             // Book Presentation
                            'bg-primary-600': '#0056b3',      // Poetry Art (blu scuro)
                            'bg-outline-primary': '#0d6efd',  // Reading (blu con bordo)
                            'bg-success-600': '#1e7e34',      // Residency (verde scuro)
                            'bg-warning-600': '#e0a800'       // Spoken Word (giallo scuro)
                        };
                        markerColor = colorMap[event.category_color_class] || '#6c757d';
                    }
                    
                    console.log(`Marker color for event ${event.id} (${event.category}): ${markerColor}`);
                    
                    // Gestione marker sovrapposti - sposta leggermente i marker alla stessa posizione
                    let lat = parseFloat(event.latitude);
                    let lng = parseFloat(event.longitude);
                    
                    // Controlla se ci sono altri marker alla stessa posizione
                    const existingMarkersAtPosition = markers.filter(marker => {
                        const markerLat = marker.getLatLng().lat;
                        const markerLng = marker.getLatLng().lng;
                        return Math.abs(markerLat - lat) < 0.0001 && Math.abs(markerLng - lng) < 0.0001;
                    });
                    
                    if (existingMarkersAtPosition.length > 0) {
                        // Sposta il marker di una piccola quantità per renderlo visibile
                        const offset = 0.0002; // Circa 20 metri
                        lat += (existingMarkersAtPosition.length * offset);
                        lng += (existingMarkersAtPosition.length * offset);
                        console.log(`Marker ${event.id} offset to [${lat}, ${lng}] due to overlap`);
                    }
                    
                    // Crea icona personalizzata con colore ma stile standard
                    const customIcon = L.divIcon({
                        className: 'custom-marker',
                        html: `<div style="background-color: ${markerColor}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    });
                    
                    const marker = L.marker([lat, lng], {
                        icon: customIcon
                    }).addTo(map);
                    
                    console.log(`Marker added to map at [${lat}, ${lng}]`);
                    
                    // Add click handler to open modal instead of popup
                    marker.on('click', function() {
                        openEventDetailsModal(event);
                    });
                    
                markers.push(marker);
                } else {
                    console.log(`Event ${event.id} has no coordinates:`, event);
                }
            });
            
            console.log(`Total markers added: ${markers.length}`);
            
            // Fit map to show all markers
            if (markers.length > 0) {
                const group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
    })
    .catch(error => {
            console.error('Error loading events:', error);
            showNotification('Errore nel caricamento degli eventi.', 'error');
    });
}

// Funzione per centrare sulla posizione dell'utente
function centerOnUser() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                map.setView([userLat, userLng], 12);
                loadEventsOnMap(userLat, userLng);
                showNotification('Mappa centrata sulla tua posizione', 'success');
            },
            function(error) {
                let message = error.code === 1 ?
                    'Geolocalizzazione richiede HTTPS. Usa il bottone refresh per eventi nell\'area corrente.' :
                    'Impossibile ottenere la tua posizione';
                showNotification(message, 'warning');
            }
        );
    }
}

// Funzione per aggiornare gli eventi
function refreshEvents() {
    const center = map.getCenter();
    loadEventsOnMap(center.lat, center.lng);
    showNotification('Eventi aggiornati', 'success');
}

// Funzione per mostrare tutti gli eventi (senza filtro geografico)
function showAllEvents() {
    // Rimuovi la logica di distanza temporaneamente
    fetch('/api/events/test')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.events) {
                // Pulisci markers esistenti
                markers.forEach(marker => map.removeLayer(marker));
                markers = [];

                data.events.forEach(event => {
                    if (event.latitude && event.longitude) {
                        L.marker([parseFloat(event.latitude), parseFloat(event.longitude)])
                            .addTo(map)
                            .bindPopup(`
                                <div class="p-2">
                                    <h6>${event.title}</h6>
                                    ${event.is_online ? 
                                        `<p class="mb-2"><i class="ph ph-globe me-1"></i>Evento Online</p>` :
                                        `<p class="mb-2"><i class="ph ph-map-pin me-1"></i>${event.venue_name}, ${event.city}</p>`
                                    }
                                    <a href="/events/${event.id}" class="btn btn-primary btn-sm mt-2"><?php echo e(__('common.view_details')); ?></a>
                                </div>
                            `);
                    }
                                        });
                        showNotification(`Mostrati ${data.events.length} eventi`, 'success');

                        // Centra la mappa se ci sono eventi
                        if (data.events.length > 0) {
                            const firstEvent = data.events[0];
                            map.setView([parseFloat(firstEvent.latitude), parseFloat(firstEvent.longitude)], 10);
                        }
            }
        })
        .catch(error => {
            console.error('Error loading all events:', error);
            showNotification('<?php echo e(__('common.loading_error')); ?> degli eventi', 'error');
        });
}

function showNotification(message, type) {
    // Simple notification system - will be enhanced with real-time notifications
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

function applyQuickFilter(filterType) {
    // Applica i filtri sia alla mappa che alla lista
    applyFilterToMap(filterType);
    applyFilterToList(filterType);
}

function updateEventsList(params) {
    // Costruisci l'URL con i parametri di filtro
    const url = new URL(window.location);
    
    // Rimuovi parametri esistenti
    url.searchParams.delete('date_from');
    url.searchParams.delete('date_to');
    url.searchParams.delete('free_only');
    url.searchParams.delete('filter');
    url.searchParams.delete('lat');
    url.searchParams.delete('lng');
    url.searchParams.delete('radius');
    
    // Aggiungi i nuovi parametri
    if (params.date_from) url.searchParams.set('date_from', params.date_from);
    if (params.date_to) url.searchParams.set('date_to', params.date_to);
    if (params.free_only) url.searchParams.set('free_only', params.free_only);
    if (params.filter) url.searchParams.set('filter', params.filter);
    if (params.lat) url.searchParams.set('lat', params.lat);
    if (params.lng) url.searchParams.set('lng', params.lng);
    if (params.radius) url.searchParams.set('radius', params.radius);
    
    // Aggiorna la pagina con i nuovi filtri
    window.location.href = url.toString();
}

function applyFilterToList(filterType) {
    const now = new Date();
    const params = {};

    switch(filterType) {
        case 'today':
            const today = now.toISOString().split('T')[0];
            params.date_from = today;
            params.date_to = today;
            break;

        case 'tomorrow':
            const tomorrow = new Date(now);
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowStr = tomorrow.toISOString().split('T')[0];
            params.date_from = tomorrowStr;
            params.date_to = tomorrowStr;
            break;

        case 'weekend':
            const saturday = new Date(now);
            const sunday = new Date(now);
            const daysUntilSaturday = (6 - now.getDay()) % 7;
            saturday.setDate(now.getDate() + daysUntilSaturday);
            sunday.setDate(saturday.getDate() + 1);
            params.date_from = saturday.toISOString().split('T')[0];
            params.date_to = sunday.toISOString().split('T')[0];
            break;

        case 'free':
            params.free_only = '1';
            break;

        case 'nearby':
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const form = document.getElementById('filterForm');
                    addHiddenInput(form, 'filter', 'nearby');
                    addHiddenInput(form, 'lat', position.coords.latitude);
                    addHiddenInput(form, 'lng', position.coords.longitude);
                    addHiddenInput(form, 'radius', '10');
                    
                    // Crea i parametri per la mappa
                    const mapParams = {
                        filter: 'nearby',
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        radius: '10'
                    };
                    
                    // Aggiorna la mappa
                    loadEventsOnMapWithFilter(mapParams);
                    
                    // Aggiorna la lista
                    updateEventsList({
                        filter: 'nearby',
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                        radius: '10'
                    });
                });
                return;
            }
            break;

        case 'my':
            params.filter = 'my';
            break;

        case 'private':
            params.filter = 'my_private';
            break;
    }

    // Aggiorna solo la mappa con i nuovi filtri (senza posizione automatica)
    const mapParams = { ...params };
    
    console.log('Applying filter to map only:', filterType, mapParams);
    loadEventsOnMapWithFilter(mapParams);
    
    // Aggiorna anche la lista ricaricando la pagina
    updateEventsList(params);
}

function applyFilterToMap(filterType) {
    const center = map.getCenter();
    const params = {
        latitude: center.lat,
        longitude: center.lng
    };

    const now = new Date();

    switch(filterType) {
        case 'today':
            params.date_from = now.toISOString().split('T')[0];
            params.date_to = now.toISOString().split('T')[0];
            console.log('Today filter applied:', params.date_from);
            break;

        case 'tomorrow':
            const tomorrow = new Date(now);
            tomorrow.setDate(tomorrow.getDate() + 1);
            params.date_from = tomorrow.toISOString().split('T')[0];
            params.date_to = tomorrow.toISOString().split('T')[0];
            console.log('Tomorrow filter applied:', params.date_from);
            break;

        case 'weekend':
            const saturday = new Date(now);
            const sunday = new Date(now);
            const daysUntilSaturday = (6 - now.getDay()) % 7;
            saturday.setDate(now.getDate() + daysUntilSaturday);
            sunday.setDate(saturday.getDate() + 1);
            params.date_from = saturday.toISOString().split('T')[0];
            params.date_to = sunday.toISOString().split('T')[0];
            console.log('Weekend filter applied:', params.date_from, 'to', params.date_to);
            break;

        case 'free':
            params.free_only = '1';
            console.log('Free filter applied');
            break;

        case 'nearby':
            params.filter = 'nearby';
            params.radius = '10';
            console.log('Nearby filter applied');
            break;

        case 'my':
            params.filter = 'my';
            console.log('My events filter applied');
            break;

        case 'private':
            params.filter = 'my_private';
            console.log('My private events filter applied');
            break;
    }

    console.log('Applying filter to map:', filterType, params);
    loadEventsOnMapWithFilter(params);
}

// Funzione per aggiungere campi nascosti al form
function addHiddenInput(form, name, value) {
    // Rimuovi input esistenti con lo stesso nome
    const existingInput = form.querySelector(`input[name="${name}"]`);
    if (existingInput) {
        existingInput.remove();
    }
    
    // Aggiungi nuovo input
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value;
    form.appendChild(input);
}

// Function to open event details modal
function openEventDetailsModal(event) {
    const modalBody = document.getElementById('eventDetailsModalBody');
    const modalLink = document.getElementById('eventDetailsModalLink');
    
    // Create modal content with horizontal layout
    let modalContent = `
        <div class="row">
            <div class="col-md-4">
                <img src="${event.image_url || '/assets/images/events/default-event.jpg'}" 
                     class="img-fluid rounded" 
                     alt="${event.title}" 
                     onerror="this.src='/assets/images/events/default-event.jpg'">
            </div>
            <div class="col-md-8">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h4 class="mb-0">${event.title}</h4>
                    <span class="badge ${event.category_color_class} fs-6">${event.category_name || 'N/A'}</span>
                </div>
                
                <div class="row mb-3">
                    <div class="col-12">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        <strong>Data e Ora:</strong> ${event.start_datetime}
                    </div>
                </div>
    `;
    
    if (event.is_online) {
        modalContent += `
                <div class="row mb-3">
                    <div class="col-12">
                        <i class="fas fa-globe text-success me-2"></i>
                        <strong class="text-success">Evento Online</strong>
                        ${event.timezone ? `<br><small class="text-muted">Fuso orario: ${event.timezone}</small>` : ''}
                    </div>
                </div>
        `;
    } else {
        modalContent += `
                <div class="row mb-3">
                    <div class="col-12">
                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                        <strong>Luogo:</strong> ${event.venue_name || 'N/A'}
                        ${event.city ? `<br><small class="text-muted">${event.city}</small>` : ''}
                    </div>
                </div>
        `;
    }
    
    modalContent += `
                <div class="row mb-3">
                    <div class="col-12">
                        <i class="fas fa-user text-info me-2"></i>
                        <strong>Organizzatore:</strong> ${event.organizer}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <i class="fas fa-users text-warning me-2"></i>
                        <strong>Partecipanti:</strong> ${event.max_participants || 'Illimitato'}
                    </div>
                    <div class="col-6">
                        <i class="fas fa-euro-sign text-success me-2"></i>
                        <strong>Prezzo:</strong> ${event.entry_fee ? event.entry_fee + '€' : 'Gratuito'}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    modalBody.innerHTML = modalContent;
    modalLink.href = event.url;
    
    // Open the modal
    const modal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
    modal.show();
}

// Add event listeners for quick filters
document.addEventListener('DOMContentLoaded', function() {
    // Quick filter click handlers
    const quickFilters = document.querySelectorAll('[data-filter]');
    quickFilters.forEach(filter => {
        filter.addEventListener('click', function(e) {
            e.preventDefault();
            const filterType = this.getAttribute('data-filter');
            console.log('Quick filter clicked:', filterType);
            
            // Update form with quick filter
            const form = document.getElementById('filterForm');
            addHiddenInput(form, 'quick_filter', filterType);
            
            // Submit form
            form.submit();
    });
});

    // Remove ALL auto-submit behavior
    const form = document.getElementById('filterForm');
    
    // Prevent form from auto-submitting on any input change
    form.addEventListener('submit', function(e) {
        // Only allow submit if it's the filter button or quick filter
        const submitter = e.submitter;
        if (!submitter || (submitter.type !== 'submit' && !submitter.hasAttribute('data-filter'))) {
            e.preventDefault();
            return false;
        }
    });
    
    // Remove any existing change/input listeners that might cause auto-submit
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        // Clone the input to remove all event listeners
        const newInput = input.cloneNode(true);
        input.parentNode.replaceChild(newInput, input);
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/events/index.blade.php ENDPATH**/ ?>