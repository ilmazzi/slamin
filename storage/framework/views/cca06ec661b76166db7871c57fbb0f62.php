<?php $__env->startSection('title', __('groups.title')); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Header con titolo e pulsante crea -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-users me-2 text-primary"></i>
                        <?php echo e(__('groups.title')); ?>

                    </h4>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('groups.create')): ?>
                    <a href="<?php echo e(route('groups.create')); ?>" class="btn btn-primary">
                        <i class="ph-duotone ph-plus me-2"></i>
                        <?php echo e(__('groups.create_group')); ?>

                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Messaggi Flash -->
    <?php if(session('success')): ?>
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-check-circle me-2"></i>
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-x-circle me-2"></i>
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Filtri e ricerca -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('groups.index')); ?>" class="row g-3">
                        <!-- Ricerca -->
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph-duotone ph-magnifying-glass"></i>
                                </span>
                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       placeholder="<?php echo e(__('groups.search_placeholder')); ?>"
                                       value="<?php echo e(request('search')); ?>">
                            </div>
                        </div>

                        <!-- Filtro -->
                        <div class="col-md-3">
                            <select name="filter" class="form-select">
                                <option value=""><?php echo e(__('groups.filter_all')); ?></option>
                                <option value="my_groups" <?php echo e(request('filter') == 'my_groups' ? 'selected' : ''); ?>>
                                    <?php echo e(__('groups.filter_my_groups')); ?>

                                </option>
                                <option value="public" <?php echo e(request('filter') == 'public' ? 'selected' : ''); ?>>
                                    <?php echo e(__('groups.filter_public')); ?>

                                </option>
                                <option value="private" <?php echo e(request('filter') == 'private' ? 'selected' : ''); ?>>
                                    <?php echo e(__('groups.filter_private')); ?>

                                </option>
                                <?php if(auth()->user()->hasRole('admin')): ?>
                                <option value="admin" <?php echo e(request('filter') == 'admin' ? 'selected' : ''); ?>>
                                    <?php echo e(__('groups.filter_admin')); ?>

                                </option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Pulsanti -->
                        <div class="col-md-5">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ph-duotone ph-funnel me-1"></i>
                                <?php echo e(__('common.filter')); ?>

                            </button>
                            <a href="<?php echo e(route('groups.index')); ?>" class="btn btn-light">
                                <i class="ph-duotone ph-arrow-clockwise me-1"></i>
                                <?php echo e(__('common.reset')); ?>

                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista gruppi -->
    <div class="row">
        <?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-12 col-md-6 col-lg-4 mb-3">
            <div class="card hover-effect h-100">
                <div class="card-body">
                    <!-- Header del gruppo -->
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            <?php if($group->image): ?>
                                <img src="<?php echo e(asset('storage/' . $group->image)); ?>"
                                     alt="<?php echo e($group->name); ?>"
                                     class="rounded-circle"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 60px; height: 60px;">
                                    <i class="ph-duotone ph-users text-primary f-s-24"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1"><?php echo e($group->name); ?></h5>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-<?php echo e($group->visibility == 'public' ? 'success' : 'warning'); ?>">
                                    <?php echo e(__('groups.visibility_' . $group->visibility)); ?>

                                </span>
                                <?php if($group->hasMember(auth()->user())): ?>
                                    <?php $role = $group->getUserRole(auth()->user()); ?>
                                    <span class="badge bg-info">
                                        <?php echo e(__('groups.role_' . $role)); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Descrizione -->
                    <?php if($group->description): ?>
                    <p class="card-text text-muted mb-3">
                        <?php echo e(Str::limit($group->description, 100)); ?>

                    </p>
                    <?php endif; ?>

                    <!-- Statistiche -->
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="text-primary fw-bold"><?php echo e($group->getMembersCount()); ?></div>
                            <small class="text-muted"><?php echo e(__('groups.members_count_label')); ?></small>
                        </div>
                        <div class="col-4">
                            <div class="text-success fw-bold"><?php echo e($group->getAdmins()->count()); ?></div>
                            <small class="text-muted"><?php echo e(__('groups.admins_count')); ?></small>
                        </div>
                        <div class="col-4">
                            <div class="text-info fw-bold"><?php echo e($group->getModerators()->count()); ?></div>
                            <small class="text-muted"><?php echo e(__('groups.moderators_count')); ?></small>
                        </div>
                    </div>

                    <!-- Azioni -->
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('groups.show', $group)); ?>" class="btn btn-primary btn-sm flex-fill">
                            <i class="ph-duotone ph-eye me-1"></i>
                            <?php echo e(__('common.view')); ?>

                        </a>

                        <?php if($group->hasMember(auth()->user())): ?>
                            <?php if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin')): ?>
                            <a href="<?php echo e(route('groups.edit', $group)); ?>" class="btn btn-light-primary btn-sm">
                                <i class="ph-duotone ph-pencil"></i>
                            </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if($group->visibility == 'public'): ?>
                                <form action="<?php echo e(route('groups.join', $group)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="message" value="">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="ph-duotone ph-plus me-1"></i>
                                        <?php echo e(__('groups.join')); ?>

                                    </button>
                                </form>
                            <?php else: ?>
                                                                            <form action="<?php echo e(route('groups.requests.store', $group)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-info btn-sm">
                                        <i class="ph-duotone ph-hand-waving me-1"></i>
                                        <?php echo e(__('groups.send_request')); ?>

                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Footer con info aggiuntive -->
                <div class="card-footer bg-light-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="ph-duotone ph-user me-1"></i>
                            <?php echo e($group->creator->getDisplayName()); ?>

                        </small>
                        <small class="text-muted">
                            <i class="ph-duotone ph-calendar me-1"></i>
                            <?php echo e($group->created_at->format('d/m/Y')); ?>

                        </small>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ph-duotone ph-users text-muted f-s-64 mb-3"></i>
                    <h5 class="text-muted"><?php echo e(__('groups.no_groups')); ?></h5>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('groups.create')): ?>
                    <p class="text-muted mb-3"><?php echo e(__('groups.tips.create_group')); ?></p>
                    <a href="<?php echo e(route('groups.create')); ?>" class="btn btn-primary">
                        <i class="ph-duotone ph-plus me-2"></i>
                        <?php echo e(__('groups.create_group')); ?>

                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Paginazione -->
    <?php if($groups->hasPages()): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php echo e($groups->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/groups/index.blade.php ENDPATH**/ ?>