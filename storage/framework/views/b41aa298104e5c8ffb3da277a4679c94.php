<?php $__env->startSection('title', ($photo->title ?: 'Foto di ' . $photo->user->getDisplayName()) . ' - Slam In'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <!-- Titolo su mobile, breadcrumb su desktop -->
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0 small">
                            <li class="breadcrumb-item">
                                <a href="<?php echo e(route('home')); ?>" class="text-decoration-none">
                                    <i class="ph ph-house me-1"></i><?php echo e(__('common.home')); ?>

                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?php echo e(route('media.index')); ?>" class="text-decoration-none">
                                    <i class="ph ph-images me-1"></i><?php echo e(__('media.media')); ?>

                                </a>
                            </li>
                            <li class="breadcrumb-item active text-truncate" style="max-width: 200px;">
                                <?php echo e($photo->title ?: 'Foto di ' . $photo->user->getDisplayName()); ?>

                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Photo Display -->
            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="position-relative">
                        <img src="<?php echo e($photo->image_url); ?>"
                             alt="<?php echo e($photo->alt_text ?: ($photo->title ?: 'Foto di ' . $photo->user->getDisplayName())); ?>"
                             class="img-fluid w-100"
                             style="max-height: 600px; object-fit: contain;">

                        <!-- Photo Overlay Info -->
                        <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                            <div class="d-flex justify-content-between align-items-end text-white">
                                <div>
                                    <?php if($photo->title): ?>
                                        <h4 class="mb-1 text-white"><?php echo e($photo->title); ?></h4>
                                    <?php endif; ?>
                                    <p class="mb-0">
                                        <i class="ph ph-user me-1"></i>
                                        <a href="<?php echo e(route('user.show', $photo->user)); ?>" class="text-decoration-none text-white hover-effect">
                                            <?php echo e($photo->user->getDisplayName()); ?>

                                        </a>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <small class="d-block">
                                        <i class="ph ph-calendar me-1"></i><?php echo e($photo->created_at->format('d/m/Y H:i')); ?>

                                    </small>
                                    <?php if (isset($component)) { $__componentOriginal74a3c73fa2014a1304a7d68280593565 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal74a3c73fa2014a1304a7d68280593565 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-view-counter','data' => ['content' => $photo,'type' => 'photo','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-view-counter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($photo),'type' => 'photo','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal74a3c73fa2014a1304a7d68280593565)): ?>
<?php $attributes = $__attributesOriginal74a3c73fa2014a1304a7d68280593565; ?>
<?php unset($__attributesOriginal74a3c73fa2014a1304a7d68280593565); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal74a3c73fa2014a1304a7d68280593565)): ?>
<?php $component = $__componentOriginal74a3c73fa2014a1304a7d68280593565; ?>
<?php unset($__componentOriginal74a3c73fa2014a1304a7d68280593565); ?>
<?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photo Details -->
            <?php if($photo->description): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-file-text me-2"></i><?php echo e(__('media.description')); ?>

                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?php echo e($photo->description); ?></p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Social Interactions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-heart me-2"></i><?php echo e(__('media.interactions')); ?>

                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center gap-3">
                        <?php if (isset($component)) { $__componentOriginal723641259025d9a0842581325b5584a2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal723641259025d9a0842581325b5584a2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-like-button','data' => ['content' => $photo,'type' => 'photo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-like-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($photo),'type' => 'photo']); ?>
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
                        <?php if (isset($component)) { $__componentOriginal74a3c73fa2014a1304a7d68280593565 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal74a3c73fa2014a1304a7d68280593565 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-view-counter','data' => ['content' => $photo,'type' => 'photo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-view-counter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($photo),'type' => 'photo']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal74a3c73fa2014a1304a7d68280593565)): ?>
<?php $attributes = $__attributesOriginal74a3c73fa2014a1304a7d68280593565; ?>
<?php unset($__attributesOriginal74a3c73fa2014a1304a7d68280593565); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal74a3c73fa2014a1304a7d68280593565)): ?>
<?php $component = $__componentOriginal74a3c73fa2014a1304a7d68280593565; ?>
<?php unset($__componentOriginal74a3c73fa2014a1304a7d68280593565); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal6f504f396e2242cb757c367dd734f8bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6f504f396e2242cb757c367dd734f8bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-comment-button','data' => ['content' => $photo,'type' => 'photo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-comment-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($photo),'type' => 'photo']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6f504f396e2242cb757c367dd734f8bb)): ?>
<?php $attributes = $__attributesOriginal6f504f396e2242cb757c367dd734f8bb; ?>
<?php unset($__attributesOriginal6f504f396e2242cb757c367dd734f8bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6f504f396e2242cb757c367dd734f8bb)): ?>
<?php $component = $__componentOriginal6f504f396e2242cb757c367dd734f8bb; ?>
<?php unset($__componentOriginal6f504f396e2242cb757c367dd734f8bb); ?>
<?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Comments Section (Sistema Unificato) -->
            <?php if (isset($component)) { $__componentOriginal3a0426d3cc93dd4143162417cb66a587 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3a0426d3cc93dd4143162417cb66a587 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-comments-section','data' => ['content' => $photo,'type' => 'photo']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-comments-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($photo),'type' => 'photo']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3a0426d3cc93dd4143162417cb66a587)): ?>
<?php $attributes = $__attributesOriginal3a0426d3cc93dd4143162417cb66a587; ?>
<?php unset($__attributesOriginal3a0426d3cc93dd4143162417cb66a587); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3a0426d3cc93dd4143162417cb66a587)): ?>
<?php $component = $__componentOriginal3a0426d3cc93dd4143162417cb66a587; ?>
<?php unset($__componentOriginal3a0426d3cc93dd4143162417cb66a587); ?>
<?php endif; ?>

        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Author Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-user me-2"></i><?php echo e(__('media.author')); ?>

                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($photo->user)); ?>"
                             alt="<?php echo e($photo->user->getDisplayName()); ?>"
                             class="rounded-circle me-3"
                             style="width: 60px; height: 60px; object-fit: cover;">
                        <div>
                            <h6 class="mb-1">
                                <a href="<?php echo e(route('user.show', $photo->user)); ?>" class="text-decoration-none hover-effect">
                                    <?php echo e($photo->user->getDisplayName()); ?>

                                </a>
                            </h6>
                            <small class="text-muted">
                                <?php echo e($photo->user->photos_count); ?> <?php echo e(__('media.photos')); ?>

                            </small>
                        </div>
                    </div>
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->id() !== $photo->user_id): ?>
                            <button type="button" class="btn btn-primary w-100" onclick="followUser(<?php echo e($photo->user->id); ?>)" id="followButton<?php echo e($photo->user->id); ?>">
                                <i class="ph ph-user me-1"></i>
                                <?php echo e($photo->user->is_followed_by_current_user ?? false ? __('profile.following') : __('profile.follow')); ?>

                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Photo Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-info me-2"></i><?php echo e(__('media.photo_info')); ?>

                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <small class="text-muted d-block"><?php echo e(__('media.uploaded')); ?></small>
                            <strong><?php echo e($photo->created_at->format('d/m/Y')); ?></strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block"><?php echo e(__('media.dimensions')); ?></small>
                            <strong><?php echo e($photo->width ?? 'N/A'); ?>x<?php echo e($photo->height ?? 'N/A'); ?></strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block"><?php echo e(__('media.file_size')); ?></small>
                            <strong><?php echo e($photo->file_size ?? 'N/A'); ?></strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block"><?php echo e(__('media.format')); ?></small>
                            <strong><?php echo e(strtoupper(pathinfo($photo->image_path, PATHINFO_EXTENSION))); ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Photos -->
            <?php if($relatedPhotos->count() > 0): ?>
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-images me-2"></i><?php echo e(__('media.related_photos')); ?>

                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <?php $__currentLoopData = $relatedPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedPhoto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-6">
                                <a href="<?php echo e(route('photos.show', $relatedPhoto)); ?>" class="text-decoration-none">
                                    <div class="position-relative rounded overflow-hidden" style="height: 120px;">
                                        <img src="<?php echo e($relatedPhoto->thumbnail_url); ?>"
                                             alt="<?php echo e($relatedPhoto->title ?: 'Foto di ' . $relatedPhoto->user->getDisplayName()); ?>"
                                             class="w-100 h-100"
                                             style="object-fit: cover;">
                                        <div class="position-absolute bottom-0 start-0 end-0 p-2" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                            <small class="text-white"><?php echo e($relatedPhoto->user->getDisplayName()); ?></small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>

// Follow functionality
function followUser(userId) {
    if (!isAuthenticated) {
        window.location.href = '<?php echo e(route("login")); ?>';
        return;
    }

    fetch('/api/follow', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const followBtn = document.getElementById(`followButton${userId}`);
            if (data.following) {
                followBtn.innerHTML = '<i class="ph ph-user me-1"></i><?php echo e(__("profile.following")); ?>';
                followBtn.classList.remove('btn-primary');
                followBtn.classList.add('btn-success');
            } else {
                followBtn.innerHTML = '<i class="ph ph-user me-1"></i><?php echo e(__("profile.follow")); ?>';
                followBtn.classList.remove('btn-success');
                followBtn.classList.add('btn-primary');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Errore durante l\'operazione', 'error');
    });
}


// Check if user is authenticated
const isAuthenticated = <?php echo e(auth()->check() ? 'true' : 'false'); ?>;
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/photos/show.blade.php ENDPATH**/ ?>