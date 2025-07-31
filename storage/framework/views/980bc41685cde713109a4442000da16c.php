<?php $__env->startSection('title', $poem->title); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0"><?php echo e($poem->title); ?></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('home')); ?>"><?php echo e(__('common.home')); ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('poems.index')); ?>"><?php echo e(__('poems.title')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?php echo e($poem->title); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Contenuto principale -->
        <div class="col-lg-8">
            <div class="card">
                <!-- Header della poesia -->
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h2 class="card-title mb-2"><?php echo e($poem->title); ?></h2>
                            <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                <span class="badge bg-primary"><?php echo e(__('poems.categories.' . $poem->category)); ?></span>
                                <span class="badge bg-info"><?php echo e(__('poems.poem_types.' . $poem->poem_type)); ?></span>
                                <span class="badge bg-secondary"><?php echo e(__('poems.languages.' . $poem->language)); ?></span>
                                <?php if($poem->is_featured): ?>
                                    <span class="badge bg-warning">
                                        <i class="ph ph-star me-1"></i><?php echo e(__('poems.status.featured')); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="ph ph-user me-1"></i>
                                <a href="#" class="text-decoration-none"><?php echo e($poem->user->name); ?></a>
                                <span class="mx-2">•</span>
                                <i class="ph ph-calendar me-1"></i>
                                <?php echo e($poem->published_at ? $poem->published_at->format('d/m/Y') : $poem->created_at->format('d/m/Y')); ?>

                                <span class="mx-2">•</span>
                                <i class="ph ph-eye me-1"></i>
                                <?php echo e(number_format($poem->view_count)); ?> <?php echo e(__('poems.stats.views')); ?>

                            </div>
                        </div>

                                                <?php if(auth()->guard()->check()): ?>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="ph ph-dots-three-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('poems.create')); ?>">
                                        <i class="ph ph-plus me-2"></i><?php echo e(__('poems.create.title')); ?>

                                    </a>
                                </li>
                                <?php if($poem->canBeEditedBy(auth()->user())): ?>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo e(route('poems.edit', $poem)); ?>">
                                            <i class="ph ph-pencil me-2"></i><?php echo e(__('common.edit')); ?>

                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if($poem->canBeDeletedBy(auth()->user())): ?>
                                    <li>
                                        <form action="<?php echo e(route('poems.destroy', $poem)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('<?php echo e(__('poems.delete_confirm')); ?>')">
                                                <i class="ph ph-trash me-2"></i><?php echo e(__('common.delete')); ?>

                                            </button>
                                        </form>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="sharePoem()">
                                        <i class="ph ph-share me-2"></i><?php echo e(__('common.share')); ?>

                                    </a>
                                </li>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- <?php echo e(__('common.thumbnail')); ?> -->
                <?php if($poem->thumbnail_path): ?>
                <div class="card-img-top">
                    <img src="<?php echo e($poem->thumbnail_url); ?>" class="img-fluid w-100" alt="<?php echo e($poem->title); ?>">
                </div>
                <?php endif; ?>

                <!-- Contenuto della poesia -->
                <div class="card-body">
                    <?php if($poem->description): ?>
                        <div class="mb-4">
                            <p class="text-muted"><?php echo e($poem->description); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="poem-content mb-4">
                        <?php echo nl2br(e($poem->content)); ?>

                    </div>

                    <!-- Tags -->
                    <?php if($poem->tags && count($poem->tags) > 0): ?>
                        <div class="mb-4">
                            <h6 class="text-muted mb-2"><?php echo e(__('poems.tags')); ?>:</h6>
                            <div class="d-flex flex-wrap gap-1">
                                <?php $__currentLoopData = $poem->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-light text-dark"><?php echo e($tag); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Statistiche -->
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <div class="border-end">
                                <h5 class="text-primary mb-1"><?php echo e(number_format($poem->like_count)); ?></h5>
                                <small class="text-muted"><?php echo e(__('poems.stats.likes')); ?></small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <h5 class="text-info mb-1"><?php echo e(number_format($poem->comment_count)); ?></h5>
                                <small class="text-muted"><?php echo e(__('poems.stats.comments')); ?></small>
                            </div>
                        </div>
                        <div class="col-4">
                            <h5 class="text-success mb-1"><?php echo e(number_format($poem->bookmark_count)); ?></h5>
                            <small class="text-muted"><?php echo e(__('poems.stats.bookmarks')); ?></small>
                        </div>
                    </div>

                    <!-- <?php echo e(__('invitations.actions')); ?> social -->
                    <?php if(auth()->guard()->check()): ?>
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <button class="btn btn-outline-primary" onclick="toggleLike()" id="likeBtn">
                            <i class="ph <?php echo e($poem->is_liked_by_current_user ? 'ph-heart-fill text-danger' : 'ph-heart'); ?> me-2"></i>
                            <?php echo e(__('poems.actions.like')); ?>

                        </button>

                        <button class="btn btn-outline-secondary" onclick="toggleBookmark()" id="bookmarkBtn">
                            <i class="ph <?php echo e($poem->is_bookmarked_by_current_user ? 'ph-bookmark-fill text-warning' : 'ph-bookmark'); ?> me-2"></i>
                            <?php echo e(__('poems.actions.bookmark')); ?>

                        </button>

                        <button class="btn btn-outline-info" onclick="sharePoem()">
                            <i class="ph ph-share me-2"></i>
                            <?php echo e(__('common.share')); ?>

                        </button>

                        <?php if($poem->translation_available): ?>
                            <button class="btn btn-outline-success" onclick="requestTranslation()">
                                <i class="ph ph-translate me-2"></i>
                                <?php echo e(__('poems.actions.request_translation')); ?>

                            </button>
                        <?php endif; ?>

                        <?php if (isset($component)) { $__componentOriginalcab7032bfdfb17b0d85d7225950dd852 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcab7032bfdfb17b0d85d7225950dd852 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.report-button','data' => ['content' => $poem,'type' => 'poem']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('report-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($poem),'type' => 'poem']); ?>
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
                    <div class="text-center mb-4">
                        <p class="text-muted"><?php echo e(__('poems.login_to_interact')); ?></p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">
                                <i class="ph ph-sign-in me-2"></i>
                                <?php echo e(__('auth.login')); ?>

                            </a>
                            <a href="<?php echo e(route('poems.create')); ?>" class="btn btn-outline-primary">
                                <i class="ph ph-plus me-2"></i>
                                <?php echo e(__('poems.create.title')); ?>

                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- <?php echo e(__('common.comments_section')); ?> -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph ph-chats-circle text-primary me-2"></i>
                        <?php echo e(__('poems.stats.comments')); ?> (<?php echo e($poem->comments->count()); ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <?php if(auth()->guard()->check()): ?>
                    <!-- Form per nuovo commento -->
                    <form action="<?php echo e(route('poems.comments.store', $poem)); ?>" method="POST" class="mb-4">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <textarea class="form-control" name="content" rows="3"
                                      placeholder="<?php echo e(__('poems.tooltips.comment_placeholder')); ?>" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph ph-paper-plane me-2"></i>
                            <?php echo e(__('poems.tooltips.post_comment')); ?>

                        </button>
                    </form>
                    <?php endif; ?>

                    <!-- Lista commenti -->
                    <div id="commentsList">
                        <?php $__empty_1 = true; $__currentLoopData = $poem->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <img src="<?php echo e($comment->user->avatar_url ?? asset('assets/images/avatar/default.png')); ?>"
                                         class="rounded-circle" width="40" height="40" alt="<?php echo e($comment->user->name); ?>">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?php echo e($comment->user->name); ?></h6>
                                            <small class="text-muted"><?php echo e($comment->created_at->diffForHumans()); ?></small>
                                        </div>
                                        <?php if($comment->canBeEditedBy(auth()->user())): ?>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                                    <i class="ph ph-dots-three-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="editComment(<?php echo e($comment->id); ?>)">
                                                            <i class="ph ph-pencil me-2"></i><?php echo e(__('common.edit')); ?>

                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="<?php echo e(route('poems.comments.destroy', $comment)); ?>" method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ph ph-trash me-2"></i><?php echo e(__('common.delete')); ?>

                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <p class="mb-1"><?php echo e($comment->content); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center text-muted py-4">
                                <i class="ph ph-chats-circle display-4"></i>
                                <p class="mt-2"><?php echo e(__('poems.no_comments')); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Informazioni autore -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph ph-user text-primary me-2"></i>
                        <?php echo e(__('poems.about_author')); ?>

                    </h5>
                </div>
                <div class="card-body text-center">
                    <img src="<?php echo e($poem->user->avatar_url ?? asset('assets/images/avatar/default.png')); ?>"
                         class="rounded-circle mb-3" width="80" height="80" alt="<?php echo e($poem->user->name); ?>">
                    <h6><?php echo e($poem->user->name); ?></h6>
                    <p class="text-muted small mb-3"><?php echo e($poem->user->bio ?? __('poems.no_bio')); ?></p>

                    <div class="row text-center">
                        <div class="col-4">
                            <h6 class="text-primary"><?php echo e($poem->user->poems()->published()->count()); ?></h6>
                            <small class="text-muted"><?php echo e(__('poems.poems')); ?></small>
                        </div>
                        <div class="col-4">
                            <h6 class="text-info"><?php echo e($poem->user->poems()->published()->sum('like_count')); ?></h6>
                            <small class="text-muted"><?php echo e(__('poems.total_likes')); ?></small>
                        </div>
                        <div class="col-4">
                            <h6 class="text-success"><?php echo e($poem->user->created_at->diffForHumans()); ?></h6>
                            <small class="text-muted"><?php echo e(__('poems.member_since')); ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Poesie correlate -->
            <?php if($relatedPoems->count() > 0): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph ph-link text-primary me-2"></i>
                        <?php echo e(__('poems.related_poems')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $relatedPoems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedPoem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex mb-3">
                            <?php if($relatedPoem->thumbnail_path): ?>
                                <img src="<?php echo e($relatedPoem->thumbnail_url); ?>" class="rounded me-3"
                                     width="60" height="60" alt="<?php echo e($relatedPoem->title); ?>">
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="<?php echo e(route('poems.show', $relatedPoem)); ?>" class="text-decoration-none">
                                        <?php echo e($relatedPoem->title); ?>

                                    </a>
                                </h6>
                                <small class="text-muted"><?php echo e($relatedPoem->user->name); ?></small>
                                <div class="d-flex align-items-center mt-1">
                                    <small class="text-muted me-3">
                                        <i class="ph ph-heart me-1"></i><?php echo e($relatedPoem->like_count); ?>

                                    </small>
                                    <small class="text-muted">
                                        <i class="ph ph-eye me-1"></i><?php echo e($relatedPoem->view_count); ?>

                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Statistiche dettagliate -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph ph-chart-line text-primary me-2"></i>
                        <?php echo e(__('poems.statistics')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h6 class="text-primary"><?php echo e($poem->word_count); ?></h6>
                                <small class="text-muted"><?php echo e(__('poems.words')); ?></small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h6 class="text-info"><?php echo e($poem->share_count); ?></h6>
                            <small class="text-muted"><?php echo e(__('poems.shares')); ?></small>
                        </div>
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-success"><?php echo e($poem->translation_request_count); ?></h6>
                                <small class="text-muted"><?php echo e(__('poems.translation_requests')); ?></small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-warning"><?php echo e($poem->comments->count()); ?></h6>
                            <small class="text-muted"><?php echo e(__('poems.comments')); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Toggle like
function toggleLike() {
    fetch('<?php echo e(route("poems.like", $poem)); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeBtn = document.getElementById('likeBtn');
            const icon = likeBtn.querySelector('i');

            if (data.liked) {
                icon.className = 'ph ph-heart-fill text-danger me-2';
            } else {
                icon.className = 'ph ph-heart me-2';
            }

            // Aggiorna il contatore
            location.reload();
        }
    });
}

// Toggle bookmark
function toggleBookmark() {
    fetch('<?php echo e(route("poems.bookmark", $poem)); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const bookmarkBtn = document.getElementById('bookmarkBtn');
            const icon = bookmarkBtn.querySelector('i');

            if (data.bookmarked) {
                icon.className = 'ph ph-bookmark-fill text-warning me-2';
            } else {
                icon.className = 'ph ph-bookmark me-2';
            }

            // Aggiorna il contatore
            location.reload();
        }
    });
}

// Share poem
function sharePoem() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo e($poem->title); ?>',
            text: '<?php echo e($poem->description ?? $poem->title); ?>',
            url: window.location.href,
        });
    } else {
        // Fallback: copia l'URL negli appunti
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('<?php echo e(__("poems.url_copied")); ?>');
        });
    }
}

// Request translation
function requestTranslation() {
    if (confirm('<?php echo e(__("poems.translation_confirm")); ?>')) {
        fetch('<?php echo e(route("poems.request-translation", $poem)); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('<?php echo e(__("poems.translation_requested")); ?>');
            } else {
                alert(data.message || '<?php echo e(__("poems.translation_error")); ?>');
            }
        });
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.poem-content {
    font-size: 1.1rem;
    line-height: 1.8;
    white-space: pre-line;
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/poems/show.blade.php ENDPATH**/ ?>