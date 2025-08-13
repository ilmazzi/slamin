<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Mobile-First Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                <h1 class="h3 mb-0"><?php echo e(__('articles.articles')); ?></h1>
                <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->can('articles.create')): ?>
                    <a href="<?php echo e(route('articles.create')); ?>" class="btn btn-primary">
                        <i class="ph-duotone ph-plus me-2"></i>
                        <?php echo e(__('articles.create_article')); ?>

                    </a>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Mobile-First Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Mobile-First Search -->
                    <div class="mb-3">
                        <form action="<?php echo e(route('articles.index')); ?>" method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control flex-grow-1"
                                   placeholder="<?php echo e(__('articles.search_placeholder')); ?>"
                                   value="<?php echo e(request('search')); ?>">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph ph-magnifying-glass"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Mobile-First Sorting -->
                    <div class="mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="<?php echo e(route('articles.index')); ?>?<?php echo e(http_build_query(array_merge(request()->except('sort'), ['sort' => 'newest']))); ?>"
                               class="btn btn-sm <?php echo e(request('sort', 'newest') === 'newest' ? 'btn-primary' : 'btn-light'); ?>">
                                <?php echo e(__('articles.sort_newest')); ?>

                            </a>
                            <a href="<?php echo e(route('articles.index')); ?>?<?php echo e(http_build_query(array_merge(request()->except('sort'), ['sort' => 'oldest']))); ?>"
                               class="btn btn-sm <?php echo e(request('sort') === 'oldest' ? 'btn-primary' : 'btn-light'); ?>">
                                <?php echo e(__('articles.sort_oldest')); ?>

                            </a>
                            <a href="<?php echo e(route('articles.index')); ?>?<?php echo e(http_build_query(array_merge(request()->except('sort'), ['sort' => 'popular']))); ?>"
                               class="btn btn-sm <?php echo e(request('sort') === 'popular' ? 'btn-primary' : 'btn-light'); ?>">
                                <?php echo e(__('articles.sort_popular')); ?>

                            </a>
                            <a href="<?php echo e(route('articles.index')); ?>?<?php echo e(http_build_query(array_merge(request()->except('sort'), ['sort' => 'title']))); ?>"
                               class="btn btn-sm <?php echo e(request('sort') === 'title' ? 'btn-primary' : 'btn-light'); ?>">
                                <?php echo e(__('articles.sort_title')); ?>

                            </a>
                        </div>
                    </div>

                    <!-- Mobile-First Category and Tag Filters -->
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <h6 class="mb-2 f-s-14 f-w-600"><?php echo e(__('articles.categories')); ?></h6>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="<?php echo e(route('articles.index')); ?>"
                                   class="badge <?php echo e(!request('category') ? 'bg-primary' : 'bg-light text-dark'); ?> text-decoration-none">
                                    <?php echo e(__('articles.all_categories')); ?>

                                </a>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('articles.index')); ?>?<?php echo e(http_build_query(array_merge(request()->except('category'), ['category' => $cat->slug]))); ?>"
                                       class="badge text-decoration-none <?php echo e(request('category') === $cat->slug ? 'bg-primary' : 'bg-light text-dark'); ?>">
                                        <?php echo e($cat->name); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <h6 class="mb-2 f-s-14 f-w-600"><?php echo e(__('articles.popular_tags')); ?></h6>
                            <div class="d-flex flex-wrap gap-2">
                                <?php $__currentLoopData = $tags->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('articles.index')); ?>?<?php echo e(http_build_query(array_merge(request()->except('tag'), ['tag' => $tag->slug]))); ?>"
                                       class="badge bg-secondary text-decoration-none">
                                        <?php echo e($tag->name); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile-First Content Layout -->
    <div class="row">
        <!-- Main Content - Mobile-First -->
        <div class="col-12 col-lg-8">
            <?php if(!$showAllArticles): ?>
                <!-- Mobile-First Featured Articles -->
                <?php if($featuredArticles->count() > 0): ?>
                    <div class="mb-4">
                        <h4 class="mb-3 f-s-18 f-w-600"><?php echo e(__('articles.featured_articles')); ?></h4>

                        <!-- Featured Article 1 - Mobile-First -->
                        <?php $featured1 = $featuredArticles->get(0); ?>
                        <div class="card mb-4 hover-effect">
                            <div class="card-body p-0">
                                <div class="position-relative">
                                    <?php if($featured1->featured_image): ?>
                                        <img src="<?php echo e(Storage::url($featured1->featured_image)); ?>"
                                             class="w-100" style="height: 200px; object-fit: cover;"
                                             alt="<?php echo e($featured1->title); ?>">
                                    <?php else: ?>
                                        <div class="w-100 d-flex align-items-center justify-content-center"
                                             style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <div class="text-center text-white">
                                                <i class="ph ph-newspaper f-s-32 mb-2"></i>
                                                <div class="f-s-14 f-w-600"><?php echo e(__('articles.featured_article')); ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="position-absolute bottom-0 start-0 w-100 p-3"
                                         style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                        <div class="text-white">
                                            <span class="badge bg-primary mb-2"><?php echo e(__('articles.featured')); ?></span>
                                            <h5 class="mb-2 f-s-16 f-w-600"><?php echo e($featured1->title); ?></h5>
                                            <p class="mb-2 f-s-14"><?php echo e(Str::limit($featured1->excerpt, 100)); ?></p>
                                            <div class="d-flex flex-wrap align-items-center text-white-50 f-s-12">
                                                <span><?php echo e(__('articles.by')); ?>

                                                    <a href="<?php echo e(route('user.show', $featured1->user)); ?>" class="text-white-50 text-decoration-none">
                                                        <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($featured1->user)); ?>"
                                                             class="rounded-circle me-1" style="width: 16px; height: 16px;"
                                                             alt="<?php echo e($featured1->user->name); ?>">
                                                        <?php echo e($featured1->user->name); ?>

                                                    </a>
                                                </span>
                                                <span class="mx-2">•</span>
                                                <span><?php echo e($featured1->published_at->format('d/m/Y')); ?></span>
                                                <span class="mx-2">•</span>
                                                <span><?php echo e(__('articles.read_time', ['minutes' => $featured1->read_time])); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <a href="<?php echo e(route('articles.show', $featured1)); ?>" class="btn btn-primary btn-sm">
                                        <?php echo e(__('articles.read_more')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Featured Articles - Mobile-First Grid -->
                        <?php if($featuredArticles->count() > 1): ?>
                            <div class="row g-3">
                                <?php $__currentLoopData = $featuredArticles->skip(1)->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $featured): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-12 col-sm-6">
                                        <div class="card h-100 hover-effect">
                                            <div class="card-body p-0">
                                                <div class="position-relative">
                                                    <?php if($featured->featured_image): ?>
                                                        <img src="<?php echo e(Storage::url($featured->featured_image)); ?>"
                                                             class="w-100" style="height: 150px; object-fit: cover;"
                                                             alt="<?php echo e($featured->title); ?>">
                                                    <?php else: ?>
                                                        <div class="w-100 d-flex align-items-center justify-content-center"
                                                             style="height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                            <i class="ph ph-newspaper text-white f-s-24"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="position-absolute bottom-0 start-0 w-100 p-2"
                                                         style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                                        <span class="badge bg-primary"><?php echo e(__('articles.featured')); ?></span>
                                                    </div>
                                                </div>
                                                <div class="p-3">
                                                    <h6 class="f-s-14 f-w-600 mb-2">
                                                        <a href="<?php echo e(route('articles.show', $featured)); ?>" class="text-decoration-none">
                                                            <?php echo e(Str::limit($featured->title, 60)); ?>

                                                        </a>
                                                    </h6>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted f-s-12">
                                                            <?php echo e($featured->published_at->format('d/m/Y')); ?>

                                                        </small>
                                                        <a href="<?php echo e(route('articles.show', $featured)); ?>" class="btn btn-primary btn-sm">
                                                            <?php echo e(__('articles.read_more')); ?>

                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Mobile-First Recent Articles -->
                <div class="mb-4">
                    <h4 class="mb-3 f-s-18 f-w-600"><?php echo e(__('articles.recent_articles')); ?></h4>
                    <div class="row g-3">
                        <?php $__currentLoopData = $recentArticles->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <?php echo $__env->make('articles.partials.article-card', ['article' => $article], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- Mobile-First All Articles Layout -->
                <div class="mb-4">
                    <h4 class="mb-3 f-s-18 f-w-600">
                        <?php if(request('search')): ?>
                            <?php echo e(__('articles.search_results_for')); ?>: "<?php echo e(request('search')); ?>"
                        <?php elseif(request('category')): ?>
                            <?php echo e(__('articles.articles_in_category')); ?>: <?php echo e($categories->firstWhere('slug', request('category'))->name ?? ''); ?>

                        <?php elseif(request('tag')): ?>
                            <?php echo e(__('articles.articles_with_tag')); ?>: <?php echo e($tags->firstWhere('slug', request('tag'))->name ?? ''); ?>

                        <?php endif; ?>
                        <span class="text-muted f-s-14">(<?php echo e($recentArticles->total()); ?> <?php echo e(__('articles.articles_found')); ?>)</span>
                    </h4>
                </div>

                <!-- Mobile-First Articles Grid -->
                <div class="row g-3">
                    <?php $__empty_1 = true; $__currentLoopData = $recentArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <?php echo $__env->make('articles.partials.article-card', ['article' => $article], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="ph ph-newspaper text-muted f-s-48"></i>
                                <h4 class="mt-3 text-muted f-s-18"><?php echo e(__('articles.no_articles_found')); ?></h4>
                                <p class="text-muted f-s-14"><?php echo e(__('articles.try_different_filters')); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Mobile-First Pagination -->
                <?php if($recentArticles->hasPages()): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($recentArticles->appends(request()->query())->links()); ?>

                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Mobile-First Sidebar -->
        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <!-- Mobile-First Search Widget -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 f-s-16 f-w-600"><?php echo e(__('articles.search_articles')); ?></h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('articles.index')); ?>" method="GET" id="searchForm">
                        <div class="mb-3">
                            <input type="text" name="search" class="form-control"
                                   placeholder="<?php echo e(__('articles.search_placeholder')); ?>"
                                   value="<?php echo e(request('search')); ?>">
                        </div>

                        <?php if($categories->count() > 0): ?>
                            <div class="mb-3">
                                <select name="category" class="form-select">
                                    <option value=""><?php echo e(__('articles.filter_by_category')); ?></option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                                            <?php echo e($category->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <select name="sort" class="form-select">
                                <option value="recent" <?php echo e(request('sort') == 'recent' ? 'selected' : ''); ?>>
                                    <?php echo e(__('articles.sort_newest')); ?>

                                </option>
                                <option value="popular" <?php echo e(request('sort') == 'popular' ? 'selected' : ''); ?>>
                                    <?php echo e(__('articles.sort_popular')); ?>

                                </option>
                                <option value="oldest" <?php echo e(request('sort') == 'oldest' ? 'selected' : ''); ?>>
                                    <?php echo e(__('articles.sort_oldest')); ?>

                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-search me-2"></i> <?php echo e(__('articles.search_articles')); ?>

                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile-First Recent Articles Sidebar -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0 f-s-16 f-w-600">
                        <i class="ph ph-clock me-2"></i>
                        <?php echo e(__('articles.recent_articles')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $recentArticles->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidebarArticle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0 me-3">
                                <?php if($sidebarArticle->featured_image): ?>
                                    <img src="<?php echo e(Storage::url($sidebarArticle->featured_image)); ?>"
                                         class="rounded" style="width: 50px; height: 50px; object-fit: cover;"
                                         alt="<?php echo e($sidebarArticle->title); ?>">
                                <?php else: ?>
                                    <div class="rounded d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <i class="ph ph-newspaper text-white f-s-16"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 f-s-14 f-w-600">
                                    <a href="<?php echo e(route('articles.show', $sidebarArticle)); ?>" class="text-decoration-none">
                                        <?php echo e(Str::limit($sidebarArticle->title, 45)); ?>

                                    </a>
                                </h6>
                                <small class="text-muted f-s-12">
                                    <?php echo e($sidebarArticle->published_at->format('d/m/Y')); ?>

                                </small>
                            </div>
                        </div>
                        <?php if(!$loop->last): ?>
                            <hr class="my-3">
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Mobile-First Popular Tags -->
            <?php if($tags->count() > 0): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 f-s-16 f-w-600"><?php echo e(__('articles.popular_tags')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <?php $__currentLoopData = $tags->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('articles.index', ['tag' => $tag->id])); ?>"
                                   class="badge bg-light text-dark text-decoration-none f-s-12">
                                    <?php echo e($tag->name); ?>

                                </a>
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
document.addEventListener('DOMContentLoaded', function() {
    // Mobile-First Search Handling
    const searchForm = document.getElementById('searchForm');
    const searchInput = searchForm.querySelector('input[name="search"]');

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchForm.submit();
        }
    });

    // Mobile-First Responsive Adjustments
    function adjustMobileLayout() {
        const isMobile = window.innerWidth < 768;
        const featuredCards = document.querySelectorAll('.card.hover-effect');

        if (isMobile) {
            featuredCards.forEach(card => {
                card.classList.add('mb-3');
            });
        } else {
            featuredCards.forEach(card => {
                card.classList.remove('mb-3');
            });
        }
    }

    // Initial adjustment
    adjustMobileLayout();

    // Adjust on resize
    window.addEventListener('resize', adjustMobileLayout);
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/articles/index.blade.php ENDPATH**/ ?>