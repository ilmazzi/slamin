

<?php $__env->startSection('title', 'Bacheca - ' . $group->name); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">
                            <i class="ti ti-news me-2"></i>
                            Bacheca - <?php echo e($group->name); ?>

                        </h1>
                        <p class="page-description">
                            Annunci e comunicazioni del gruppo
                        </p>
                    </div>
                    <div>
                        <a href="<?php echo e(route('groups.show', $group)); ?>" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>Torna al gruppo
                        </a>
                        <a href="<?php echo e(route('groups.announcements.create', $group)); ?>" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>Nuovo annuncio
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filtri -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex gap-2">
                                <a href="<?php echo e(request()->fullUrlWithQuery(['filter' => 'all'])); ?>" 
                                   class="btn btn-sm <?php echo e(request('filter') === 'all' || !request('filter') ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                    Tutti
                                </a>
                                <a href="<?php echo e(request()->fullUrlWithQuery(['filter' => 'pinned'])); ?>" 
                                   class="btn btn-sm <?php echo e(request('filter') === 'pinned' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                    <i class="ti ti-pin me-1"></i>Pinnati
                                </a>
                                <a href="<?php echo e(request()->fullUrlWithQuery(['filter' => 'polls'])); ?>" 
                                   class="btn btn-sm <?php echo e(request('filter') === 'polls' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                    <i class="ti ti-chart-bar me-1"></i>Sondaggi
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" class="d-flex">
                                <input type="text" 
                                       name="search" 
                                       class="form-control form-control-sm me-2" 
                                       placeholder="Cerca annunci..." 
                                       value="<?php echo e(request('search')); ?>">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="ti ti-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista annunci -->
            <div class="announcements-list">
                <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if (isset($component)) { $__componentOriginalaaff4697327c86e3e652498f20cedfc1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaaff4697327c86e3e652498f20cedfc1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.group-announcement-card','data' => ['announcement' => $announcement,'group' => $group]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('group-announcement-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['announcement' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($announcement),'group' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($group)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaaff4697327c86e3e652498f20cedfc1)): ?>
<?php $attributes = $__attributesOriginalaaff4697327c86e3e652498f20cedfc1; ?>
<?php unset($__attributesOriginalaaff4697327c86e3e652498f20cedfc1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaaff4697327c86e3e652498f20cedfc1)): ?>
<?php $component = $__componentOriginalaaff4697327c86e3e652498f20cedfc1; ?>
<?php unset($__componentOriginalaaff4697327c86e3e652498f20cedfc1); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ti ti-news f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted">Nessun annuncio trovato</h5>
                            <p class="text-muted">
                                <?php if(request('search')): ?>
                                    Nessun annuncio corrisponde alla tua ricerca.
                                <?php else: ?>
                                    Non ci sono ancora annunci in questo gruppo.
                                <?php endif; ?>
                            </p>
                            <?php if(!request('search')): ?>
                                <a href="<?php echo e(route('groups.announcements.create', $group)); ?>" class="btn btn-success">
                                    <i class="ti ti-plus me-1"></i>Crea il primo annuncio
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Paginazione -->
            <?php if($announcements->hasPages()): ?>
                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($announcements->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/groups/announcements/index.blade.php ENDPATH**/ ?>