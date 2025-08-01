<?php $__env->startSection('title', __('poems.liked.title')); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0"><?php echo e(__('poems.liked.title')); ?></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('home')); ?>"><?php echo e(__('common.home')); ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('poems.index')); ?>"><?php echo e(__('poems.title')); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?php echo e(__('poems.liked.title')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche like -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card card-light-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1"><?php echo e($likedPoems->total()); ?></h4>
                            <p class="text-muted mb-0"><?php echo e(__('poems.liked.total_liked')); ?></p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph ph-heart display-4 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-light-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1"><?php echo e($likedPoems->where('pivot.created_at', '>=', now()->subDays(7))->count()); ?></h4>
                            <p class="text-muted mb-0"><?php echo e(__('poems.liked.recent_likes')); ?></p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph ph-clock display-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-light-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1"><?php echo e($likedPoems->unique('user_id')->count()); ?></h4>
                            <p class="text-muted mb-0"><?php echo e(__('poems.liked.authors')); ?></p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph ph-users display-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-light-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1"><?php echo e($likedPoems->unique('category')->count()); ?></h4>
                            <p class="text-muted mb-0"><?php echo e(__('poems.liked.categories')); ?></p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph ph-tag display-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header con azioni -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="ph ph-heart text-danger me-2"></i>
                                <?php echo e(__('poems.liked.your_liked_poems')); ?>

                            </h5>
                            <p class="text-muted mb-0"><?php echo e(__('poems.liked.description')); ?></p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('poems.create')); ?>" class="btn btn-primary">
                                <i class="ph ph-plus me-2"></i>
                                <?php echo e(__('poems.create.title')); ?>

                            </a>
                            <a href="<?php echo e(route('poems.bookmarks')); ?>" class="btn btn-outline-warning">
                                <i class="ph ph-bookmark me-2"></i>
                                <?php echo e(__('poems.liked.view_bookmarks')); ?>

                            </a>
                            <a href="<?php echo e(route('poems.index')); ?>" class="btn btn-outline-primary">
                                <i class="ph ph-magnifying-glass me-2"></i>
                                <?php echo e(__('poems.liked.explore_poems')); ?>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtri -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="category" class="form-label"><?php echo e(__('poems.filters.category')); ?></label>
                            <select class="form-select" id="category" name="category">
                                <option value=""><?php echo e(__('common.all')); ?></option>
                                <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e(request('category') == $key ? 'selected' : ''); ?>>
                                        <?php echo e($category); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="sort" class="form-label"><?php echo e(__('poems.filters.sort')); ?></label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="recent" <?php echo e(request('sort') == 'recent' ? 'selected' : ''); ?>>
                                    <?php echo e(__('poems.filters.recent')); ?>

                                </option>
                                <option value="oldest" <?php echo e(request('sort') == 'oldest' ? 'selected' : ''); ?>>
                                    <?php echo e(__('poems.filters.oldest')); ?>

                                </option>
                                <option value="popular" <?php echo e(request('sort') == 'popular' ? 'selected' : ''); ?>>
                                    <?php echo e(__('poems.filters.popular')); ?>

                                </option>
                                <option value="alphabetical" <?php echo e(request('sort') == 'alphabetical' ? 'selected' : ''); ?>>
                                    <?php echo e(__('poems.filters.alphabetical')); ?>

                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ph ph-funnel me-2"></i>
                                    <?php echo e(__('common.filter')); ?>

                                </button>
                                <a href="<?php echo e(route('poems.liked')); ?>" class="btn btn-outline-secondary">
                                    <i class="ph ph-arrow-clockwise me-2"></i>
                                    <?php echo e(__('common.reset')); ?>

                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista poesie piaciute -->
    <div class="row">
        <?php $__empty_1 = true; $__currentLoopData = $likedPoems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $poem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card hover-effect">
                    <!-- <?php echo e(__('common.thumbnail')); ?> -->
                    <?php if($poem->thumbnail_path): ?>
                        <div class="card-img-top">
                            <img src="<?php echo e($poem->thumbnail_url); ?>" class="img-fluid" alt="<?php echo e($poem->title); ?>">
                        </div>
                    <?php endif; ?>

                    <div class="card-body">
                        <!-- Status badge -->
                        <div class="mb-2">
                            <span class="badge bg-danger">
                                <i class="ph ph-heart me-1"></i><?php echo e(__('poems.status.liked')); ?>

                            </span>

                            <?php if($poem->is_featured): ?>
                                <span class="badge bg-primary">
                                    <i class="ph ph-star me-1"></i><?php echo e(__('poems.status.featured')); ?>

                                </span>
                            <?php endif; ?>

                            <span class="badge bg-secondary"><?php echo e($poem->category); ?></span>
                        </div>

                        <!-- Titolo -->
                        <h5 class="card-title">
                            <a href="<?php echo e(route('poems.show', $poem)); ?>" class="text-decoration-none">
                                <?php echo e($poem->title); ?>

                            </a>
                        </h5>

                        <!-- Autore -->
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="ph ph-user me-1"></i>
                                <a href="#" class="text-decoration-none"><?php echo e($poem->user->name); ?></a>
                            </small>
                        </div>

                        <!-- Anteprima contenuto -->
                        <p class="card-text text-muted small">
                            <?php echo e(Str::limit($poem->content, 150)); ?>

                        </p>

                        <!-- Statistiche -->
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="ph ph-eye me-1"></i><?php echo e(number_format($poem->view_count)); ?>

                                </small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="ph ph-heart me-1"></i><?php echo e(number_format($poem->like_count)); ?>

                                </small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="ph ph-chat-circle me-1"></i><?php echo e(number_format($poem->comment_count)); ?>

                                </small>
                            </div>
                        </div>

                        <!-- Data like -->
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="ph ph-heart me-1"></i>
                                <?php echo e(__('poems.liked.liked_on')); ?> <?php echo e($poem->pivot->created_at->format('d/m/Y')); ?>

                            </small>
                        </div>

                        <!-- <?php echo e(__('invitations.actions')); ?> -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo e(route('poems.show', $poem)); ?>" class="btn btn-outline-primary">
                                    <i class="ph ph-eye"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" onclick="unlikePoem(<?php echo e($poem->id); ?>)">
                                    <i class="ph ph-heart-fill"></i>
                                </button>
                                <button type="button" class="btn btn-outline-warning" onclick="toggleBookmark(<?php echo e($poem->id); ?>)">
                                    <i class="ph ph-bookmark<?php echo e($poem->is_bookmarked_by_current_user ? '-fill' : ''); ?>"></i>
                                </button>
                                <button type="button" class="btn btn-outline-info" onclick="sharePoem('<?php echo e(route('poems.show', $poem)); ?>')">
                                    <i class="ph ph-share"></i>
                                </button>
                            </div>

                            <div class="dropdown">
                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                    <i class="ph ph-dots-three-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="<?php echo e(route('poems.show', $poem)); ?>">
                                            <i class="ph ph-eye me-2"></i><?php echo e(__('common.view')); ?>

                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="sharePoem('<?php echo e(route('poems.show', $poem)); ?>')">
                                            <i class="ph ph-share me-2"></i><?php echo e(__('common.share')); ?>

                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="toggleBookmark(<?php echo e($poem->id); ?>)">
                                            <i class="ph ph-bookmark<?php echo e($poem->is_bookmarked_by_current_user ? '-fill' : ''); ?> me-2"></i>
                                            <?php echo e($poem->is_bookmarked_by_current_user ? __('poems.bookmarks.remove_bookmark') : __('poems.bookmarks.add_bookmark')); ?>

                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" onclick="unlikePoem(<?php echo e($poem->id); ?>)">
                                            <i class="ph ph-heart-fill me-2"></i><?php echo e(__('poems.liked.unlike')); ?>

                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph ph-heart display-1 text-muted mb-3"></i>
                        <h4 class="text-muted mb-3"><?php echo e(__('poems.liked.no_liked_poems')); ?></h4>
                        <p class="text-muted mb-4"><?php echo e(__('poems.liked.no_liked_poems_description')); ?></p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="<?php echo e(route('poems.index')); ?>" class="btn btn-primary">
                                <i class="ph ph-magnifying-glass me-2"></i>
                                <?php echo e(__('poems.liked.explore_poems')); ?>

                            </a>
                            <a href="<?php echo e(route('poems.bookmarks')); ?>" class="btn btn-outline-warning">
                                <i class="ph ph-bookmark me-2"></i>
                                <?php echo e(__('poems.liked.view_bookmarks')); ?>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Paginazione -->
    <?php if($likedPoems->hasPages()): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <?php echo e($likedPoems->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function unlikePoem(poemId) {
    if (confirm('<?php echo e(__("poems.liked.unlike_confirm")); ?>')) {
        fetch(`/poems/${poemId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Rimuovi la card dalla pagina
                const card = document.querySelector(`[data-poem-id="${poemId}"]`);
                if (card) {
                    card.remove();
                }
                // Ricarica la pagina per aggiornare le statistiche
                window.location.reload();
            } else {
                alert(data.message || '<?php echo e(__("poems.liked.unlike_error")); ?>');
            }
        });
    }
}

function toggleBookmark(poemId) {
    fetch(`/poems/${poemId}/bookmark`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Aggiorna l'icona del bookmark
            const bookmarkBtn = document.querySelector(`[data-poem-id="${poemId}"] .btn-outline-warning i`);
            if (bookmarkBtn) {
                bookmarkBtn.className = data.bookmarked ? 'ph ph-bookmark-fill' : 'ph ph-bookmark';
            }
        } else {
            alert(data.message || '<?php echo e(__("poems.bookmarks.toggle_error")); ?>');
        }
    });
}

function sharePoem(url) {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo e(__("poems.share_title")); ?>',
            text: '<?php echo e(__("poems.share_text")); ?>',
            url: url,
        });
    } else {
        // Fallback: copia l'URL negli appunti
        navigator.clipboard.writeText(url).then(() => {
            alert('<?php echo e(__("poems.url_copied")); ?>');
        });
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/poems/liked.blade.php ENDPATH**/ ?>