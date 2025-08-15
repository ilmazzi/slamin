<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- Main Content - Mobile-First -->
        <div class="col-12 col-lg-8">
            <?php $__env->startSection('breadcrumb-title'); ?>
            <h3 class="f-s-18 f-w-600"><?php echo e($article->title); ?></h3>
            <?php $__env->stopSection(); ?>

            <?php $__env->startSection('breadcrumb-items'); ?>
            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('articles.home')); ?></a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('articles.index')); ?>"><?php echo e(__('articles.news')); ?></a></li>
            <li class="breadcrumb-item active"><?php echo e(Str::limit($article->title, 40)); ?></li>
            <?php $__env->stopSection(); ?>

            <!-- Mobile-First Article Card -->
            <div class="card mb-4 hover-effect">
                <?php if($article->featured_image): ?>
                    <img src="<?php echo e(Storage::url($article->featured_image)); ?>"
                         class="card-img-top" style="height: 250px; object-fit: cover;"
                         alt="<?php echo e($article->title); ?>">
                <?php else: ?>
                    <div class="card-img-top d-flex align-items-center justify-content-center"
                         style="height: 250px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="text-center text-white">
                            <i class="ph ph-newspaper f-s-48 mb-2"></i>
                            <div class="f-s-16 f-w-600"><?php echo e(__('articles.article')); ?></div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <!-- Mobile-First Article Header -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            <?php if($article->category): ?>
                                <span class="badge f-s-12" style="background-color: <?php echo e($article->category->color); ?>">
                                    <?php echo e($article->category->name); ?>

                                </span>
                            <?php endif; ?>
                            <?php if($article->featured): ?>
                                <span class="badge bg-warning f-s-12"><?php echo e(__('articles.featured')); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical f-s-14"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if(auth()->check() && auth()->user()->can('edit', $article)): ?>
                                    <li><a class="dropdown-item" href="<?php echo e(route('articles.edit', $article)); ?>">
                                        <i class="ti ti-edit me-2"></i> <?php echo e(__('articles.edit_article')); ?>

                                    </a></li>
                                <?php endif; ?>
                                <?php if(auth()->check() && auth()->user()->hasPermissionTo('articles.publish')): ?>
                                    <?php if($article->isPublished): ?>
                                        <li><a class="dropdown-item" href="#" onclick="unpublishArticle(<?php echo e($article->id); ?>)">
                                            <i class="ti ti-eye-off me-2"></i> <?php echo e(__('articles.unpublish')); ?>

                                        </a></li>
                                    <?php else: ?>
                                        <li><a class="dropdown-item" href="#" onclick="publishArticle(<?php echo e($article->id); ?>)">
                                            <i class="ti ti-eye me-2"></i> <?php echo e(__('articles.publish')); ?>

                                        </a></li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if(auth()->check() && auth()->user()->hasPermissionTo('articles.feature')): ?>
                                    <li><a class="dropdown-item" href="#" onclick="toggleFeatured(<?php echo e($article->id); ?>)">
                                        <i class="ti ti-star me-2"></i> <?php echo e($article->featured ? __('articles.unfeature') : __('articles.feature')); ?>

                                    </a></li>
                                <?php endif; ?>
                                <?php if(auth()->check() && auth()->user()->can('delete', $article)): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteArticle(<?php echo e($article->id); ?>)">
                                        <i class="ti ti-trash me-2"></i> <?php echo e(__('articles.delete')); ?>

                                    </a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Mobile-First Title and Meta -->
                    <h1 class="card-title mb-3 f-s-20 f-w-600"><?php echo e($article->title); ?></h1>

                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center text-muted mb-4 gap-2">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($article->user)); ?>"
                                 class="rounded-circle me-2" style="width: 28px; height: 28px;"
                                 alt="<?php echo e($article->user->name); ?>">
                            <span class="f-s-14"><?php echo e(__('articles.by')); ?>

                                <a href="<?php echo e(route('user.show', $article->user)); ?>" class="text-decoration-none">
                                    <?php echo e(Str::limit($article->user->name, 20)); ?>

                                </a>
                            </span>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2 f-s-12">
                            <span><?php echo e($article->published_at->format('d/m/Y H:i')); ?></span>
                            <span>•</span>
                            <span><?php echo e(__('articles.read_time', ['minutes' => $article->read_time])); ?></span>
                            <span>•</span>
                            <span><?php echo e($article->views_count); ?> <?php echo e(__('articles.views')); ?></span>
                        </div>
                    </div>

                    <!-- Mobile-First Tags -->
                    <?php if($article->tags->count() > 0): ?>
                        <div class="mb-4">
                            <div class="d-flex flex-wrap gap-2">
                                <?php $__currentLoopData = $article->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-light text-dark f-s-12"><?php echo e($tag->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Mobile-First Social Actions -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
                        <div class="d-flex flex-wrap gap-2">
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

                            <!-- View Counter (Sistema Unificato) -->
                            <?php if (isset($component)) { $__componentOriginal74a3c73fa2014a1304a7d68280593565 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal74a3c73fa2014a1304a7d68280593565 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-view-counter','data' => ['content' => $article,'type' => 'article']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-view-counter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($article),'type' => 'article']); ?>
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

                            <!-- Condividi -->
                            <button class="btn btn-outline-info btn-sm" onclick="shareArticle()">
                                <i class="ti ti-share f-s-14 me-1"></i> <?php echo e(__('articles.share_article')); ?>

                            </button>

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

                        <!-- Stampa -->
                        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                            <i class="ti ti-printer f-s-14 me-1"></i> <?php echo e(__('articles.print_article')); ?>

                        </button>
                    </div>

                    <!-- Mobile-First Article Content -->
                    <div class="article-content mb-4">
                        <div class="f-s-14 lh-base">
                            <?php echo $article->content; ?>

                        </div>
                    </div>

                    <!-- Mobile-First Author Section -->
                    <div class="card bg-light-success">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3">
                                <a href="<?php echo e(route('user.show', $article->user)); ?>" class="text-decoration-none">
                                    <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($article->user)); ?>"
                                         class="rounded-circle" style="width: 56px; height: 56px;"
                                         alt="<?php echo e($article->user->name); ?>">
                                </a>
                                <div class="flex-grow-1">
                                    <h6 class="mb-2 f-s-16 f-w-600"><?php echo e(__('articles.by')); ?>

                                        <a href="<?php echo e(route('user.show', $article->user)); ?>" class="text-decoration-none hover-effect">
                                            <?php echo e($article->user->name); ?>

                                        </a>
                                    </h6>
                                    <p class="text-muted mb-0 f-s-14"><?php echo e($article->user->profile->bio ?? __('articles.no_bio')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section (Sistema Unificato) -->
            <?php if (isset($component)) { $__componentOriginal3a0426d3cc93dd4143162417cb66a587 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3a0426d3cc93dd4143162417cb66a587 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-comments-section','data' => ['content' => $article,'type' => 'article']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-comments-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($article),'type' => 'article']); ?>
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

        <!-- Mobile-First Sidebar -->
        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <!-- Mobile-First Related Articles -->
            <?php if($relatedArticles->count() > 0): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 f-s-16 f-w-600"><?php echo e(__('articles.related_articles')); ?></h5>
                    </div>
                    <div class="card-body p-0">
                        <?php $__currentLoopData = $relatedArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedArticle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border-bottom p-3">
                                <h6 class="mb-2 f-s-14 f-w-600">
                                    <a href="<?php echo e(route('articles.show', $relatedArticle)); ?>" class="text-decoration-none">
                                        <?php echo e(Str::limit($relatedArticle->title, 50)); ?>

                                    </a>
                                </h6>
                                <div class="d-flex flex-wrap align-items-center text-muted f-s-12 gap-2">
                                    <span><?php echo e($relatedArticle->published_at->format('d/m/Y')); ?></span>
                                    <span>•</span>
                                    <span><?php echo e($relatedArticle->views_count); ?> <?php echo e(__('articles.views')); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Mobile-First Article Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 f-s-16 f-w-600"><?php echo e(__('articles.article_stats')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-4">
                            <div class="h5 mb-1 f-w-600"><?php echo e($article->views_count); ?></div>
                            <small class="text-muted f-s-12"><?php echo e(__('articles.views')); ?></small>
                        </div>
                        <div class="col-4">
                            <div class="h5 mb-1 f-w-600"><?php echo e($article->likes_count); ?></div>
                            <small class="text-muted f-s-12"><?php echo e(__('articles.likes')); ?></small>
                        </div>
                        <div class="col-4">
                            <div class="h5 mb-1 f-w-600"><?php echo e($article->comments_count); ?></div>
                            <small class="text-muted f-s-12"><?php echo e(__('articles.comments')); ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile-First Social Sharing -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 f-s-16 f-w-600"><?php echo e(__('articles.share_article')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(request()->url())); ?>"
                           target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="ti ti-brand-facebook me-2"></i> <?php echo e(__('articles.share_on_facebook')); ?>

                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo e(urlencode(request()->url())); ?>&text=<?php echo e(urlencode($article->title)); ?>"
                           target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="ti ti-brand-twitter me-2"></i> <?php echo e(__('articles.share_on_twitter')); ?>

                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo e(urlencode(request()->url())); ?>"
                           target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="ti ti-brand-linkedin me-2"></i> <?php echo e(__('articles.share_on_linkedin')); ?>

                        </a>
                        <button class="btn btn-outline-success btn-sm" onclick="copyLink()">
                            <i class="ti ti-copy me-2"></i> <?php echo e(__('articles.copy_link')); ?>

                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile-First Enhancements
    const isMobile = window.innerWidth < 768;

    if (isMobile) {
        // Mobile-specific adjustments
        const articleContent = document.querySelector('.article-content');
        if (articleContent) {
            // Ensure content is readable on mobile
            articleContent.style.fontSize = '16px';
            articleContent.style.lineHeight = '1.6';
        }

        // Mobile-friendly image handling
        const articleImages = document.querySelectorAll('.article-content img');
        articleImages.forEach(img => {
            img.style.maxWidth = '100%';
            img.style.height = 'auto';
            img.classList.add('img-fluid');
        });
    }

    // Responsive adjustments
    function adjustMobileLayout() {
        const isMobile = window.innerWidth < 768;
        const articleCard = document.querySelector('.card.hover-effect');

        if (isMobile && articleCard) {
            articleCard.classList.add('mb-3');
        } else if (articleCard) {
            articleCard.classList.remove('mb-3');
        }
    }

    // Initial adjustment
    adjustMobileLayout();

    // Adjust on resize
    window.addEventListener('resize', adjustMobileLayout);
});

function shareArticle() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo e($article->title); ?>',
            text: '<?php echo e($article->excerpt); ?>',
            url: window.location.href
        });
    } else {
        copyLink();
    }
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        showNotification('<?php echo e(__('articles.link_copied')); ?>', 'success');
    });
}

function publishArticle(articleId) {
    if (confirm('<?php echo e(__('articles.confirm_publish_article')); ?>')) {
        fetch(`/articles/${articleId}/publish`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

function unpublishArticle(articleId) {
    if (confirm('<?php echo e(__('articles.confirm_unpublish_article')); ?>')) {
        fetch(`/articles/${articleId}/unpublish`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

function toggleFeatured(articleId) {
    fetch(`/articles/${articleId}/toggle-featured`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function deleteArticle(articleId) {
    if (confirm('<?php echo e(__('articles.confirm_delete_article')); ?>')) {
        fetch(`/articles/${articleId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '<?php echo e(route('articles.index')); ?>';
            }
        });
    }
}

function showNotification(message, type) {
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

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/articles/show.blade.php ENDPATH**/ ?>