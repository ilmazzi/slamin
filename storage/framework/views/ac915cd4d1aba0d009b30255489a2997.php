<div class="card h-100">
    <?php if($article->featured_image): ?>
        <img src="<?php echo e(Storage::url($article->featured_image)); ?>"
             class="card-img-top" style="height: 200px; object-fit: cover;"
             alt="<?php echo e($article->title); ?>">
    <?php endif; ?>
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <?php if($article->category): ?>
                    <span class="badge" style="background-color: <?php echo e($article->category->color); ?>">
                        <?php echo e($article->category->name); ?>

                    </span>
                <?php endif; ?>
                <?php if($article->featured): ?>
                    <span class="badge bg-warning ms-1"><?php echo e(__('articles.featured')); ?></span>
                <?php endif; ?>
            </div>
            <?php if(isset($position) && auth()->check() && auth()->user()->hasPermissionTo('articles.manage_layout')): ?>
                <button class="btn btn-sm btn-outline-secondary" onclick="editLayoutPosition('<?php echo e($position); ?>', <?php echo e($article->id); ?>)">
                    <i class="ti ti-edit"></i>
                </button>
            <?php endif; ?>
        </div>

        <h5 class="card-title">
            <a href="<?php echo e(route('articles.show', $article)); ?>" class="text-decoration-none">
                <?php echo e($article->title); ?>

            </a>
        </h5>

        <?php if($article->excerpt): ?>
            <p class="card-text text-muted"><?php echo e(Str::limit($article->excerpt, 120)); ?></p>
        <?php endif; ?>

        <div class="d-flex align-items-center text-muted mb-3">
            <small><?php echo e(__('articles.by')); ?>

                <a href="<?php echo e(route('user.show', $article->user)); ?>" class="text-decoration-none">
                    <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($article->user)); ?>"
                         class="rounded-circle me-1" style="width: 16px; height: 16px;"
                         alt="<?php echo e($article->user->name); ?>">
                    <?php echo e($article->user->name); ?>

                </a>
            </small>
            <span class="mx-2">•</span>
            <small><?php echo e($article->published_at->format('d/m/Y')); ?></small>
            <span class="mx-2">•</span>
            <small><?php echo e(__('articles.read_time', ['minutes' => $article->read_time])); ?></small>
        </div>

        <!-- Tag -->
        <?php if($article->tags->count() > 0): ?>
            <div class="mb-3">
                <?php $__currentLoopData = $article->tags->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="badge bg-light text-dark me-1"><?php echo e($tag->name); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($article->tags->count() > 3): ?>
                    <small class="text-muted">+<?php echo e($article->tags->count() - 3); ?></small>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Statistiche -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center text-muted">
                <i class="ti ti-eye me-1"></i>
                <small><?php echo e($article->views_count); ?> <?php echo e(__('articles.views')); ?></small>
                <span class="mx-2">•</span>
                <i class="ti ti-message-circle me-1"></i>
                <small><?php echo e($article->comments_count); ?> <?php echo e(__('articles.comments')); ?></small>
            </div>
        </div>

        <!-- Azioni social -->
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex gap-2">
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
                    <i class="ti ti-message-circle"></i>
                    <?php echo e($article->comments_count); ?>

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

    // Report functionality rimossa perché ora gestita dal componente report-button
});

// Funzione showReportModal rimossa perché ora gestita dal componente report-button

// Funzione submitReport rimossa perché ora gestita dal componente report-button

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