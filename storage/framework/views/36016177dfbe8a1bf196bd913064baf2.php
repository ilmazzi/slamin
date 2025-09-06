<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Search Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-light-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">
                                <i class="ph ph-magnifying-glass me-2"></i>
                                <?php echo e(__('search.search_results')); ?>

                            </h4>
                            <?php if(!empty($query)): ?>
                                <p class="text-muted mb-0">
                                    <?php echo e(__('search.results_for')); ?> "<strong><?php echo e($query); ?></strong>"
                                    - <?php echo e($totalResults); ?> <?php echo e(__('search.results_found')); ?>

                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('search.index', ['q' => $query, 'type' => 'all'])); ?>"
                                   class="btn <?php echo e($type === 'all' ? 'btn-primary' : 'btn-outline-primary'); ?> btn-sm">
                                    <?php echo e(__('search.all')); ?>

                                </a>
                                <a href="<?php echo e(route('search.index', ['q' => $query, 'type' => 'poems'])); ?>"
                                   class="btn <?php echo e($type === 'poems' ? 'btn-primary' : 'btn-outline-primary'); ?> btn-sm">
                                    <?php echo e(__('search.poems')); ?>

                                </a>
                                <a href="<?php echo e(route('search.index', ['q' => $query, 'type' => 'events'])); ?>"
                                   class="btn <?php echo e($type === 'events' ? 'btn-primary' : 'btn-outline-primary'); ?> btn-sm">
                                    <?php echo e(__('search.events')); ?>

                                </a>
                                <a href="<?php echo e(route('search.index', ['q' => $query, 'type' => 'videos'])); ?>"
                                   class="btn <?php echo e($type === 'videos' ? 'btn-primary' : 'btn-outline-primary'); ?> btn-sm">
                                    <?php echo e(__('search.videos')); ?>

                                </a>
                                <a href="<?php echo e(route('search.index', ['q' => $query, 'type' => 'gigs'])); ?>"
                                   class="btn <?php echo e($type === 'gigs' ? 'btn-primary' : 'btn-outline-primary'); ?> btn-sm">
                                    <?php echo e(__('search.gigs')); ?>

                                </a>
                                <?php if(auth()->guard()->check()): ?>
                                <a href="<?php echo e(route('search.index', ['q' => $query, 'type' => 'users'])); ?>"
                                   class="btn <?php echo e($type === 'users' ? 'btn-primary' : 'btn-outline-primary'); ?> btn-sm">
                                    <?php echo e(__('search.users')); ?>

                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(empty($query)): ?>
        <!-- Search Form -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-light-primary">
                    <div class="card-body text-center">
                        <i class="ph ph-magnifying-glass display-1 text-primary mb-3"></i>
                        <h4 class="mb-3"><?php echo e(__('search.start_searching')); ?></h4>
                        <p class="text-muted mb-4"><?php echo e(__('search.search_description')); ?></p>

                        <form action="<?php echo e(route('search.index')); ?>" method="GET" class="d-flex">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">
                                    <i class="ph ph-magnifying-glass"></i>
                                </span>
                                <input type="text"
                                       class="form-control"
                                       name="q"
                                       placeholder="<?php echo e(__('search.search_placeholder')); ?>"
                                       value="<?php echo e($query); ?>">
                                <button class="btn btn-primary" type="submit">
                                    <?php echo e(__('search.search')); ?>

                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Search Results -->
        <div class="row">
            <?php if($totalResults > 0): ?>
                <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $categoryResults): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($categoryResults['count'] > 0): ?>
                        <div class="col-12 mb-4">
                            <div class="card card-light-<?php echo e($category === 'poems' ? 'info' : ($category === 'events' ? 'success' : ($category === 'videos' ? 'warning' : ($category === 'gigs' ? 'primary' : 'secondary')))); ?>">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="ph ph-<?php echo e($category === 'poems' ? 'pen-nib' : ($category === 'events' ? 'calendar' : ($category === 'videos' ? 'video' : ($category === 'gigs' ? 'briefcase' : 'users')))); ?> me-2"></i>
                                            <?php echo e(__('search.' . $category)); ?>

                                            <span class="badge bg-primary ms-2"><?php echo e($categoryResults['count']); ?></span>
                                        </h6>
                                        <?php if($categoryResults['total'] > $categoryResults['count']): ?>
                                            <a href="<?php echo e(route('search.index', ['q' => $query, 'type' => $category])); ?>"
                                               class="btn btn-sm btn-outline-primary">
                                                <?php echo e(__('search.view_all')); ?> (<?php echo e($categoryResults['total']); ?>)
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php $__currentLoopData = $categoryResults['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card hover-effect">
                                                    <div class="card-body">
                                                        <?php if($category === 'poems'): ?>
                                                            <h6 class="card-title">
                                                                <a href="<?php echo e(route('poems.show', $item->slug)); ?>" class="text-decoration-none">
                                                                    <?php echo e(Str::limit($item->title, 50)); ?>

                                                                </a>
                                                            </h6>
                                                            <p class="card-text text-muted small">
                                                                <?php echo e(Str::limit(strip_tags($item->content), 100)); ?>

                                                            </p>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">
                                                                    <i class="ph ph-user me-1"></i>
                                                                    <?php echo e($item->user->name); ?>

                                                                </small>
                                                                <small class="text-muted">
                                                                    <i class="ph ph-calendar me-1"></i>
                                                                    <?php echo e($item->published_at ? $item->published_at->format('d/m/Y') : 'N/A'); ?>

                                                                </small>
                                                            </div>
                                                        <?php elseif($category === 'events'): ?>
                                                            <h6 class="card-title">
                                                                <a href="<?php echo e(route('events.show', $item->id)); ?>" class="text-decoration-none">
                                                                    <?php echo e(Str::limit($item->title, 50)); ?>

                                                                </a>
                                                            </h6>
                                                            <p class="card-text text-muted small">
                                                                <?php echo e(Str::limit($item->description, 100)); ?>

                                                            </p>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">
                                                                    <i class="ph ph-map-pin me-1"></i>
                                                                    <?php echo e($item->city); ?>

                                                                </small>
                                                                <small class="text-muted">
                                                                    <i class="ph ph-calendar me-1"></i>
                                                                    <?php echo e($item->start_datetime ? $item->start_datetime->format('d/m/Y') : 'N/A'); ?>

                                                                </small>
                                                            </div>
                                                        <?php elseif($category === 'videos'): ?>
                                                            <h6 class="card-title">
                                                                <a href="<?php echo e(route('videos.show', $item->id)); ?>" class="text-decoration-none">
                                                                    <?php echo e(Str::limit($item->title, 50)); ?>

                                                                </a>
                                                            </h6>
                                                            <p class="card-text text-muted small">
                                                                <?php echo e(Str::limit($item->description, 100)); ?>

                                                            </p>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">
                                                                    <i class="ph ph-user me-1"></i>
                                                                    <?php echo e($item->user->name); ?>

                                                                </small>
                                                                <small class="text-muted">
                                                                    <i class="ph ph-clock me-1"></i>
                                                                    <?php echo e($item->duration ? gmdate('i:s', $item->duration) : 'N/A'); ?>

                                                                </small>
                                                            </div>
                                                        <?php elseif($category === 'gigs'): ?>
                                                            <h6 class="card-title">
                                                                <a href="<?php echo e(route('gigs.show', $item->id)); ?>" class="text-decoration-none">
                                                                    <?php echo e(Str::limit($item->title, 50)); ?>

                                                                </a>
                                                            </h6>
                                                            <p class="card-text text-muted small">
                                                                <?php echo e(Str::limit($item->description, 100)); ?>

                                                            </p>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">
                                                                    <i class="ph ph-map-pin me-1"></i>
                                                                    <?php echo e($item->location); ?>

                                                                </small>
                                                                <small class="text-muted">
                                                                    <i class="ph ph-calendar me-1"></i>
                                                                    <?php echo e($item->deadline ? $item->deadline->format('d/m/Y') : 'N/A'); ?>

                                                                </small>
                                                            </div>
                                                        <?php elseif($category === 'users'): ?>
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-3">
                                                                    <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($item)); ?>"
                                                                         alt="<?php echo e($item->name); ?>"
                                                                         class="rounded-circle"
                                                                         style="width: 40px; height: 40px;">
                                                                </div>
                                                                <div>
                                                                    <h6 class="card-title mb-1">
                                                                        <a href="<?php echo e(route('profile.show', $item->id)); ?>" class="text-decoration-none">
                                                                            <?php echo e($item->name); ?>

                                                                        </a>
                                                                    </h6>
                                                                    <small class="text-muted">
                                                                        <i class="ph ph-envelope me-1"></i>
                                                                        <?php echo e($item->email); ?>

                                                                    </small>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <!-- No Results -->
                <div class="col-12">
                    <div class="card card-light-secondary">
                        <div class="card-body text-center">
                            <i class="ph ph-magnifying-glass display-1 text-muted mb-3"></i>
                            <h4 class="mb-3"><?php echo e(__('search.no_results_found')); ?></h4>
                            <p class="text-muted mb-4"><?php echo e(__('search.try_different_keywords')); ?></p>
                            <a href="<?php echo e(route('search.index')); ?>" class="btn btn-primary">
                                <i class="ph ph-arrow-left me-2"></i>
                                <?php echo e(__('search.new_search')); ?>

                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/search/index.blade.php ENDPATH**/ ?>