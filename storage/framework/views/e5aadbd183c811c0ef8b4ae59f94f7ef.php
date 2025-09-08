<?php $__env->startSection('title', __('poems.title')); ?>

<?php $__env->startSection('main-content'); ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title"><?php echo e(__('poems.title')); ?></h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="<?php echo e(route('home')); ?>" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> <?php echo e(__('dashboard.dashboard')); ?>

                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500"><?php echo e(__('poems.title')); ?></a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->

        <!-- Filtri e Ricerca -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo e(route('poems.index')); ?>" method="GET" class="row g-3">
                            <div class="col-12 col-md-4">
                                <input type="text" name="search" class="form-control"
                                       placeholder="<?php echo e(__('poems.placeholders.search')); ?>"
                                       value="<?php echo e(request('search')); ?>">
                            </div>
                            <div class="col-6 col-md-2">
                                <select name="category" class="form-select">
                                    <option value=""><?php echo e(__('poems.filters.filter_by_category')); ?></option>
                                    <?php $__currentLoopData = config('poems.categories'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(request('category') == $key ? 'selected' : ''); ?>>
                                            <?php echo e($category); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <select name="language" class="form-select">
                                    <option value=""><?php echo e(__('poems.filters.filter_by_language')); ?></option>
                                    <?php $__currentLoopData = config('poems.languages'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(request('language') == $key ? 'selected' : ''); ?>>
                                            <?php echo e($language); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <select name="sort" class="form-select">
                                    <?php $__currentLoopData = __('poems.filters.sort_options'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(request('sort', 'recent') == $key ? 'selected' : ''); ?>>
                                            <?php echo e($option); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ph-duotone ph-magnifying-glass me-2"></i>
                                    <?php echo e(__('poems.actions.search')); ?>

                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Azioni -->
        <?php if(auth()->guard()->check()): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-2">
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="<?php echo e(route('poems.create')); ?>" class="btn btn-primary">
                            <i class="ph ph-pen-nib"></i>
                            <?php echo e(__('poems.actions.create')); ?>

                        </a>
                        <a href="<?php echo e(route('poems.my-poems')); ?>" class="btn btn-outline-primary">
                            <i class="ph-duotone ph-book-open me-2"></i>
                            <?php echo e(__('poems.my_poems.title')); ?>

                        </a>
                        <a href="<?php echo e(route('poems.drafts')); ?>" class="btn btn-outline-secondary">
                            <i class="ph-duotone ph-file-text me-2"></i>
                            <?php echo e(__('poems.filters.drafts')); ?>

                        </a>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="<?php echo e(route('poems.bookmarks')); ?>" class="btn btn-outline-warning">
                            <i class="ph-duotone ph-bookmark me-2"></i>
                            <?php echo e(__('poems.filters.bookmarks')); ?>

                        </a>
                        <a href="<?php echo e(route('poems.liked')); ?>" class="btn btn-outline-danger">
                            <i class="ph-duotone ph-heart me-2"></i>
                            <?php echo e(__('poems.filters.liked')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Lista Poesie -->
        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $poems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $poem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-12 col-sm-6 col-lg-4 mb-4">
                <div class="card hover-effect h-100">
                    <?php if($poem->thumbnail): ?>
                    <img src="<?php echo e($poem->thumbnail); ?>" class="card-img-top" alt="<?php echo e($poem->title); ?>" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title f-w-600 mb-0 flex-grow-1">
                                <a href="<?php echo e(route('poems.show', $poem)); ?>" class="text-decoration-none">
                                    <?php echo e($poem->title); ?>

                                </a>
                            </h5>
                            <?php if($poem->is_featured): ?>
                            <span class="badge bg-warning ms-2">
                                <i class="ph-duotone ph-star me-1"></i>
                                <?php echo e(__('poems.filters.featured')); ?>

                            </span>
                            <?php endif; ?>
                        </div>

                        <p class="card-text text-muted f-s-14 mb-2">
                            <i class="ph-duotone ph-user f-s-12 me-1"></i>
                            <a href="<?php echo e(route('user.show', $poem->user)); ?>" class="text-decoration-none hover-effect">
                                <?php echo e($poem->user->getDisplayName()); ?>

                            </a>
                        </p>

                        <?php if($poem->description): ?>
                        <p class="card-text flex-grow-1"><?php echo e(Str::limit($poem->description, 100)); ?></p>
                        <?php endif; ?>

                        <div class="mt-auto">
                            <div class="d-flex flex-column align-items-center gap-2">
                                <div class="d-flex gap-3 justify-content-center mb-2">
                                    <span class="text-muted f-s-14">
                                        <i class="ph-duotone ph-eye f-s-12 me-1"></i>
                                        <?php echo e($poem->view_count); ?>

                                    </span>
                                    <span class="text-muted f-s-14">
                                        <i class="ph-duotone ph-heart f-s-12 me-1"></i>
                                        <?php echo e($poem->like_count); ?>

                                    </span>
                                    <span class="text-muted f-s-14">
                                        <i class="ph-duotone ph-chat-circle f-s-12 me-1"></i>
                                        <?php echo e($poem->comment_count); ?>

                                    </span>
                                </div>
                                <div class="d-flex gap-2 justify-content-center align-items-center">
                                    <a href="<?php echo e(route('poems.show', $poem)); ?>" class="btn btn-primary px-4">
                                        <i class="ph-bold  ph-read-cv-logo"></i>
                                        <?php echo e(__('poems.actions.read')); ?>

                                    </a>
                                    <?php if(auth()->guard()->check()): ?>
                                    <?php if (isset($component)) { $__componentOriginalcab7032bfdfb17b0d85d7225950dd852 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcab7032bfdfb17b0d85d7225950dd852 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.report-button','data' => ['content' => $poem,'type' => 'poem','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('report-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($poem),'type' => 'poem','size' => 'sm']); ?>
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
                        <i class="ph-duotone ph-book-open f-s-48 text-muted mb-3"></i>
                        <h5 class="text-muted"><?php echo e(__('poems.no_poems_found')); ?></h5>
                        <p class="text-muted"><?php echo e(__('poems.no_poems_description')); ?></p>
                        <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('poems.create')); ?>" class="btn btn-primary">
                            <i class="ph ph-pen-nib"></i>
                            <?php echo e(__('poems.actions.create')); ?>

                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Paginazione -->
        <?php if($poems->hasPages()): ?>
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    <?php echo e($poems->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.card.hover-effect:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/poems/index.blade.php ENDPATH**/ ?>