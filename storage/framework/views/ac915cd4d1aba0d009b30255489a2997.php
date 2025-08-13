<div class="card h-100 hover-effect">
    <?php if($article->featured_image): ?>
        <img src="<?php echo e(Storage::url($article->featured_image)); ?>"
             class="card-img-top" style="height: 180px; object-fit: cover;"
             alt="<?php echo e($article->title); ?>">
    <?php else: ?>
        <div class="card-img-top d-flex align-items-center justify-content-center"
             style="height: 180px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="text-center text-white">
                <i class="ph ph-newspaper f-s-32 mb-2"></i>
                <div class="f-s-14 f-w-600"><?php echo e(__('articles.article')); ?></div>
            </div>
        </div>
    <?php endif; ?>
    <div class="card-body d-flex flex-column">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="d-flex flex-wrap gap-1">
                <?php if($article->category): ?>
                    <span class="badge f-s-11" style="background-color: <?php echo e($article->category->color); ?>">
                        <?php echo e($article->category->name); ?>

                    </span>
                <?php endif; ?>
                <?php if($article->featured): ?>
                    <span class="badge bg-warning f-s-11"><?php echo e(__('articles.featured')); ?></span>
                <?php endif; ?>
            </div>
            <?php if(isset($position) && auth()->check() && auth()->user()->hasPermissionTo('articles.manage_layout')): ?>
                <button class="btn btn-sm btn-outline-secondary" onclick="editLayoutPosition('<?php echo e($position); ?>', <?php echo e($article->id); ?>)">
                    <i class="ti ti-edit f-s-12"></i>
                </button>
            <?php endif; ?>
        </div>

        <h6 class="card-title f-s-16 f-w-600 mb-2">
            <a href="<?php echo e(route('articles.show', $article)); ?>" class="text-decoration-none">
                <?php echo e(Str::limit($article->title, 60)); ?>

            </a>
        </h6>

        <?php if($article->excerpt): ?>
            <p class="card-text text-muted f-s-13 mb-3 flex-grow-1"><?php echo e(Str::limit($article->excerpt, 100)); ?></p>
        <?php endif; ?>

        <div class="d-flex flex-wrap align-items-center text-muted mb-3 f-s-12">
            <span class="me-2"><?php echo e(__('articles.by')); ?>

                <a href="<?php echo e(route('user.show', $article->user)); ?>" class="text-decoration-none">
                    <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($article->user)); ?>"
                         class="rounded-circle me-1" style="width: 14px; height: 14px;"
                         alt="<?php echo e($article->user->name); ?>">
                    <?php echo e(Str::limit($article->user->name, 15)); ?>

                </a>
            </span>
            <span class="mx-1">•</span>
            <span><?php echo e($article->published_at->format('d/m/Y')); ?></span>
            <span class="mx-1">•</span>
            <span><?php echo e(__('articles.read_time', ['minutes' => $article->read_time])); ?></span>
        </div>

        <!-- Tag - Mobile-First -->
        <?php if($article->tags->count() > 0): ?>
            <div class="mb-3">
                <div class="d-flex flex-wrap gap-1">
                    <?php $__currentLoopData = $article->tags->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge bg-light text-dark f-s-11"><?php echo e($tag->name); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($article->tags->count() > 2): ?>
                        <small class="text-muted f-s-11">+<?php echo e($article->tags->count() - 2); ?></small>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Statistiche - Mobile-First -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center text-muted f-s-12">
                <i class="ti ti-eye me-1"></i>
                <span><?php echo e($article->views_count); ?></span>
                <span class="mx-2">•</span>
                <i class="ti ti-message-circle me-1"></i>
                <span><?php echo e($article->comments_count); ?></span>
            </div>
        </div>

        <!-- Azioni social - Mobile-First -->
        <div class="d-flex justify-content-between align-items-center mt-auto">
            <div class="d-flex gap-1">
                <!-- Like Button (Sistema Unificato) -->
                <?php if (isset($component)) { $__componentOriginal723641259025d9a0842581325b5584a2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal723641259025d9a0842581325b5584a2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-like-button','data' => ['content' => $article,'type' => 'article']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-like-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($article),'type' => 'article']); ?>
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

                <!-- Commenti -->
                <a href="<?php echo e(route('articles.show', $article)); ?>#comments" class="btn btn-sm btn-outline-secondary">
                    <i class="ti ti-message-circle f-s-12"></i>
                    <span class="d-none d-sm-inline"><?php echo e($article->comments_count); ?></span>
                </a>

                <!-- Report Button (Sistema Unificato) -->
                <?php if (isset($component)) { $__componentOriginalcab7032bfdfb17b0d85d7225950dd852 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcab7032bfdfb17b0d85d7225950dd852 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.report-button','data' => ['content' => $article,'type' => 'article']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('report-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($article),'type' => 'article']); ?>
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

            <a href="<?php echo e(route('articles.show', $article)); ?>" class="btn btn-primary btn-sm">
                <?php echo e(__('articles.read_more')); ?>

            </a>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile-First Card Interactions
    const articleCards = document.querySelectorAll('.card.hover-effect');

    articleCards.forEach(card => {
        // Add touch-friendly interactions for mobile
        if ('ontouchstart' in window) {
            card.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            });

            card.addEventListener('touchend', function() {
                this.style.transform = 'scale(1)';
            });
        }
    });

    // Like functionality
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!<?php echo e(auth()->check() ? 'true' : 'false'); ?>) {
                window.location.href = '<?php echo e(route('login')); ?>';
                return;
            }

            const articleId = this.dataset.articleId;
            const isLiked = this.dataset.liked === 'true';

            fetch(`/articles/${articleId}/likes/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button state
                    this.dataset.liked = data.liked;
                    const icon = this.querySelector('i');
                    const count = this.querySelector('.likes-count');

                    if (data.liked) {
                        icon.classList.add('text-danger');
                    } else {
                        icon.classList.remove('text-danger');
                    }

                    count.textContent = data.likes_count;

                    // Show notification
                    showNotification(data.message, 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('<?php echo e(__('articles.error_processing_request')); ?>', 'error');
            });
        });
    });
});

function showNotification(message, type) {
    // Use SweetAlert or similar notification system
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            text: message,
            icon: type,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    } else {
        alert(message);
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/articles/partials/article-card.blade.php ENDPATH**/ ?>