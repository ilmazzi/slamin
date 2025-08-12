<?php if($articles->count() > 0): ?>
<div class="row">
    <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-6 mb-3">
        <div class="card hover-effect">
            <div class="position-relative">
                <?php if($article->featured_image): ?>
                    <img src="<?php echo e(Storage::url($article->featured_image)); ?>"
                         alt="<?php echo e($article->title); ?>" class="card-img-top"
                         style="height: 200px; object-fit: cover;">
                <?php else: ?>
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                         style="height: 200px;">
                        <div class="text-center">
                            <i class="ph ph-newspaper f-s-48 text-muted mb-2"></i>
                            <div class="f-s-16 f-w-600 text-muted"><?php echo e(__('articles.article')); ?></div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- Status badge -->
                <div class="position-absolute top-0 start-0 m-2">
                    <?php if($article->featured): ?>
                        <span class="badge bg-warning f-s-11"><?php echo e(__('articles.featured')); ?></span>
                    <?php elseif($article->status === 'published'): ?>
                        <span class="badge bg-success f-s-11"><?php echo e(__('articles.published')); ?></span>
                    <?php else: ?>
                        <span class="badge bg-secondary f-s-11"><?php echo e(__('articles.draft')); ?></span>
                    <?php endif; ?>
                </div>
                <!-- Views badge -->
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-dark f-s-11"><?php echo e($article->views_count ?? 0); ?> <?php echo e(__('profile.views')); ?></span>
                </div>
            </div>
            <div class="card-body pa-15">
                <h6 class="card-title f-w-600 f-s-14 mb-1">
                    <a href="<?php echo e(route('articles.show', $article)); ?>" class="text-decoration-none">
                        <?php echo e($article->title); ?>

                    </a>
                </h6>
                <?php if($article->excerpt): ?>
                <p class="text-muted f-s-12 mb-2"><?php echo e(Str::limit($article->excerpt, 80)); ?></p>
                <?php endif; ?>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted f-s-11"><?php echo e($article->created_at->diffForHumans()); ?></small>
                    <div class="d-flex gap-1">
                        <small class="text-muted f-s-11">
                            <i class="ph ph-heart me-1"></i><?php echo e($article->likes_count); ?>

                        </small>
                        <small class="text-muted f-s-11">
                            <i class="ph ph-chat-circle me-1"></i><?php echo e($article->comments_count); ?>

                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<!-- Paginazione -->
<?php if($articles->hasPages()): ?>
<div class="d-flex justify-center mt-4">
    <ul class="pagination app-pagination" id="articles-pagination">
        <?php if($articles->onFirstPage()): ?>
            <li class="page-item disabled">
                <span class="page-link b-r-left">Previous</span>
            </li>
        <?php else: ?>
            <li class="page-item">
                <a class="page-link b-r-left" href="javascript:void(0)" data-page="<?php echo e($articles->currentPage() - 1); ?>">Previous</a>
            </li>
        <?php endif; ?>

        <?php $__currentLoopData = $articles->getUrlRange(1, $articles->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="page-item <?php echo e($page == $articles->currentPage() ? 'active' : ''); ?>">
                <a class="page-link" href="javascript:void(0)" data-page="<?php echo e($page); ?>"><?php echo e($page); ?></a>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if($articles->hasMorePages()): ?>
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" data-page="<?php echo e($articles->currentPage() + 1); ?>">Next</a>
            </li>
        <?php else: ?>
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
        <?php endif; ?>
    </ul>
</div>
<?php endif; ?>
<?php else: ?>
<div class="text-center py-4">
    <div class="bg-light-info h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
        <i class="ph ph-newspaper f-s-24 text-muted"></i>
    </div>
    <p class="text-muted f-s-14 mb-0"><?php echo e(__('profile.no_articles_written')); ?></p>
    <?php if(auth()->check() && auth()->id() == $user->id): ?>
    <a href="<?php echo e(route('articles.create')); ?>" class="btn btn-sm btn-primary mt-2">
        <i class="ph ph-plus me-1"></i><?php echo e(__('articles.create_first_article')); ?>

    </a>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/profile/partials/articles-list.blade.php ENDPATH**/ ?>