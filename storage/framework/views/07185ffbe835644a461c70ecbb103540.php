<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Header con pulsante Crea Articolo -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
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

    <!-- Sezione Ricerca e Filtri -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Ricerca -->
                        <div class="col-md-6">
                            <form action="<?php echo e(route('articles.index')); ?>" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2"
                                       placeholder="<?php echo e(__('articles.search_placeholder')); ?>"
                                       value="<?php echo e(request('search')); ?>">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ph ph-magnifying-glass"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Ordinamento -->
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('articles.index')); ?>?<?php echo e(http_build_query(array_merge(request()->except('sort'), ['sort' => 'newest']))); ?>"
                                   class="btn btn-sm <?php echo e(request('sort', 'newest') === 'newest' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                    <?php echo e(__('articles.sort_newest')); ?>

                                </a>
                                <a href="<?php echo e(route('articles.index')); ?>?<?php echo e(http_build_query(array_merge(request()->except('sort'), ['sort' => 'oldest']))); ?>"
                                   class="btn btn-sm <?php echo e(request('sort') === 'oldest' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                    <?php echo e(__('articles.sort_oldest')); ?>

                                </a>
                                <a href="<?php echo e(route('articles.index')); ?>?<?php echo e(http_build_query(array_merge(request()->except('sort'), ['sort' => 'popular']))); ?>"
                                   class="btn btn-sm <?php echo e(request('sort') === 'popular' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                    <?php echo e(__('articles.sort_popular')); ?>

                                </a>
                                <a href="<?php echo e(route('articles.index')); ?>?<?php echo e(http_build_query(array_merge(request()->except('sort'), ['sort' => 'title']))); ?>"
                                   class="btn btn-sm <?php echo e(request('sort') === 'title' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                    <?php echo e(__('articles.sort_title')); ?>

                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Filtri Categorie e Tag -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6 class="mb-2"><?php echo e(__('articles.categories')); ?></h6>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="<?php echo e(route('articles.index')); ?>"
                                   class="badge bg-primary text-decoration-none <?php echo e(!request('category') ? 'bg-primary' : 'bg-light text-dark'); ?>">
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
                        <div class="col-md-6">
                            <h6 class="mb-2"><?php echo e(__('articles.popular_tags')); ?></h6>
                            <div class="d-flex flex-wrap gap-2">
                                <?php $__currentLoopData = $tags->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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

    <div class="row">
        <!-- Contenuto principale -->
        <div class="col-lg-8">
            <?php if(!$showAllArticles): ?>
                <!-- Layout normale: Featured + Recent -->
                <!-- Sezione 1: Articolo Featured Orizzontale + 2 Articoli Recenti -->
                <div class="mb-5">
                    <!-- Articolo Featured Orizzontale -->
                    <?php if($featuredArticles->count() > 0): ?>
                        <?php $featured1 = $featuredArticles->get(0); ?>
                        <div class="card mb-4">
                            <div class="card-body p-0">
                                <div class="position-relative">
                                    <?php if($featured1->featured_image): ?>
                                        <img src="<?php echo e(Storage::url($featured1->featured_image)); ?>"
                                             class="w-100" style="height: 300px; object-fit: cover;"
                                             alt="<?php echo e($featured1->title); ?>">
                                    <?php else: ?>
                                        <div class="w-100 d-flex align-items-center justify-content-center"
                                             style="height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <div class="text-center text-white">
                                                <i class="ph ph-newspaper f-s-48 mb-2"></i>
                                                <div class="f-s-16 f-w-600"><?php echo e(__('articles.featured_article')); ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="position-absolute bottom-0 start-0 w-100 p-4"
                                         style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                        <div class="d-flex justify-content-between align-items-end">
                                            <div class="text-white">
                                                <span class="badge bg-primary mb-2"><?php echo e(__('articles.featured')); ?></span>
                                                <h2 class="mb-2"><?php echo e($featured1->title); ?></h2>
                                                <p class="mb-2"><?php echo e(Str::limit($featured1->excerpt, 150)); ?></p>
                                                <div class="d-flex align-items-center text-white-50">
                                                    <small><?php echo e(__('articles.by')); ?>

                                                        <a href="<?php echo e(route('user.show', $featured1->user)); ?>" class="text-white-50 text-decoration-none">
                                                            <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($featured1->user)); ?>"
                                                                 class="rounded-circle me-1" style="width: 16px; height: 16px;"
                                                                 alt="<?php echo e($featured1->user->name); ?>">
                                                            <?php echo e($featured1->user->name); ?>

                                                        </a>
                                                    </small>
                                                    <span class="mx-2">•</span>
                                                    <small><?php echo e($featured1->published_at->format('d/m/Y')); ?></small>
                                                    <span class="mx-2">•</span>
                                                    <small><?php echo e(__('articles.read_time', ['minutes' => $featured1->read_time])); ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <a href="<?php echo e(route('articles.show', $featured1)); ?>" class="btn btn-primary">
                                        <?php echo e(__('articles.read_more')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- 2 Articoli Recenti -->
                    <div class="row">
                        <div class="col-md-6">
                            <?php if($recentArticles->count() > 0): ?>
                                <?php echo $__env->make('articles.partials.article-card', ['article' => $recentArticles->first()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if($recentArticles->count() > 1): ?>
                                <?php echo $__env->make('articles.partials.article-card', ['article' => $recentArticles->get(1)], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sezione 2: Articolo Featured Orizzontale + 2 Articoli Recenti -->
                <div class="mb-5">
                    <!-- Articolo Featured Orizzontale -->
                    <?php if($featuredArticles->count() > 1): ?>
                        <?php $featured2 = $featuredArticles->get(1); ?>
                        <div class="card mb-4">
                            <div class="card-body p-0">
                                <div class="position-relative">
                                    <?php if($featured2->featured_image): ?>
                                        <img src="<?php echo e(Storage::url($featured2->featured_image)); ?>"
                                             class="w-100" style="height: 300px; object-fit: cover;"
                                             alt="<?php echo e($featured2->title); ?>">
                                    <?php else: ?>
                                        <div class="w-100 d-flex align-items-center justify-content-center"
                                             style="height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <div class="text-center text-white">
                                                <i class="ph ph-newspaper f-s-48 mb-2"></i>
                                                <div class="f-s-16 f-w-600"><?php echo e(__('articles.featured_article')); ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="position-absolute bottom-0 start-0 w-100 p-4"
                                         style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                        <div class="d-flex justify-content-between align-items-end">
                                            <div class="text-white">
                                                <span class="badge bg-primary mb-2"><?php echo e(__('articles.featured')); ?></span>
                                                <h2 class="mb-2"><?php echo e($featured2->title); ?></h2>
                                                <p class="mb-2"><?php echo e(Str::limit($featured2->excerpt, 150)); ?></p>
                                                <div class="d-flex align-items-center text-white-50">
                                                    <small><?php echo e(__('articles.by')); ?>

                                                        <a href="<?php echo e(route('user.show', $featured2->user)); ?>" class="text-white-50 text-decoration-none">
                                                            <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($featured2->user)); ?>"
                                                                 class="rounded-circle me-1" style="width: 16px; height: 16px;"
                                                                 alt="<?php echo e($featured2->user->name); ?>">
                                                            <?php echo e($featured2->user->name); ?>

                                                        </a>
                                                    </small>
                                                    <span class="mx-2">•</span>
                                                    <small><?php echo e($featured2->published_at->format('d/m/Y')); ?></small>
                                                    <span class="mx-2">•</span>
                                                    <small><?php echo e(__('articles.read_time', ['minutes' => $featured2->read_time])); ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <a href="<?php echo e(route('articles.show', $featured2)); ?>" class="btn btn-primary">
                                        <?php echo e(__('articles.read_more')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- 2 Articoli Recenti -->
                    <div class="row">
                        <div class="col-md-6">
                            <?php if($recentArticles->count() > 2): ?>
                                <?php echo $__env->make('articles.partials.article-card', ['article' => $recentArticles->get(2)], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if($recentArticles->count() > 3): ?>
                                <?php echo $__env->make('articles.partials.article-card', ['article' => $recentArticles->get(3)], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sezione 3: Articolo Featured Orizzontale + 2 Articoli Recenti -->
                <div class="mb-5">
                    <!-- Articolo Featured Orizzontale -->
                    <?php if($featuredArticles->count() > 2): ?>
                        <?php $featured3 = $featuredArticles->get(2); ?>
                        <div class="card mb-4">
                            <div class="card-body p-0">
                                <div class="position-relative">
                                    <?php if($featured3->featured_image): ?>
                                        <img src="<?php echo e(Storage::url($featured3->featured_image)); ?>"
                                             class="w-100" style="height: 300px; object-fit: cover;"
                                             alt="<?php echo e($featured3->title); ?>">
                                    <?php else: ?>
                                        <div class="w-100 d-flex align-items-center justify-content-center"
                                             style="height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <div class="text-center text-white">
                                                <i class="ph ph-newspaper f-s-48 mb-2"></i>
                                                <div class="f-s-16 f-w-600"><?php echo e(__('articles.featured_article')); ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="position-absolute bottom-0 start-0 w-100 p-4"
                                         style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                        <div class="d-flex justify-content-between align-items-end">
                                            <div class="text-white">
                                                <span class="badge bg-primary mb-2"><?php echo e(__('articles.featured')); ?></span>
                                                <h2 class="mb-2"><?php echo e($featured3->title); ?></h2>
                                                <p class="mb-2"><?php echo e(Str::limit($featured3->excerpt, 150)); ?></p>
                                                <div class="d-flex align-items-center text-white-50">
                                                    <small><?php echo e(__('articles.by')); ?>

                                                        <a href="<?php echo e(route('user.show', $featured3->user)); ?>" class="text-white-50 text-decoration-none">
                                                            <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($featured3->user)); ?>"
                                                                 class="rounded-circle me-1" style="width: 16px; height: 16px;"
                                                                 alt="<?php echo e($featured3->user->name); ?>">
                                                            <?php echo e($featured3->user->name); ?>

                                                        </a>
                                                    </small>
                                                    <span class="mx-2">•</span>
                                                    <small><?php echo e($featured3->published_at->format('d/m/Y')); ?></small>
                                                    <span class="mx-2">•</span>
                                                    <small><?php echo e(__('articles.read_time', ['minutes' => $featured3->read_time])); ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <a href="<?php echo e(route('articles.show', $featured3)); ?>" class="btn btn-primary">
                                        <?php echo e(__('articles.read_more')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- 2 Articoli Recenti -->
                    <div class="row">
                        <div class="col-md-6">
                            <?php if($recentArticles->count() > 4): ?>
                                <?php echo $__env->make('articles.partials.article-card', ['article' => $recentArticles->get(4)], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if($recentArticles->count() > 5): ?>
                                <?php echo $__env->make('articles.partials.article-card', ['article' => $recentArticles->get(5)], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Layout con tutti gli articoli quando vengono applicati filtri -->
                <div class="mb-4">
                    <h4 class="mb-3">
                        <?php if(request('search')): ?>
                            <?php echo e(__('articles.search_results_for')); ?>: "<?php echo e(request('search')); ?>"
                        <?php elseif(request('category')): ?>
                            <?php echo e(__('articles.articles_in_category')); ?>: <?php echo e($categories->firstWhere('slug', request('category'))->name ?? ''); ?>

                        <?php elseif(request('tag')): ?>
                            <?php echo e(__('articles.articles_with_tag')); ?>: <?php echo e($tags->firstWhere('slug', request('tag'))->name ?? ''); ?>

                        <?php endif; ?>
                        (<?php echo e($recentArticles->total()); ?> <?php echo e(__('articles.articles_found')); ?>)
                    </h4>
                </div>

                <!-- Lista di tutti gli articoli -->
                <div class="row">
                    <?php $__empty_1 = true; $__currentLoopData = $recentArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <?php echo $__env->make('articles.partials.article-card', ['article' => $article], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="ph ph-newspaper text-muted" style="font-size: 4rem;"></i>
                                <h4 class="mt-3 text-muted"><?php echo e(__('articles.no_articles_found')); ?></h4>
                                <p class="text-muted"><?php echo e(__('articles.try_different_filters')); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Paginazione -->
                <?php if($recentArticles->hasPages()): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($recentArticles->appends(request()->query())->links()); ?>

                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Sidebar destra -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__('articles.search_articles')); ?></h5>
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
                            <i class="ti ti-search"></i> <?php echo e(__('articles.search_articles')); ?>

                        </button>
                    </form>
                </div>
            </div>

            <!-- Articoli Recenti -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
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
                                         class="rounded" style="width: 60px; height: 60px; object-fit: cover;"
                                         alt="<?php echo e($sidebarArticle->title); ?>">
                                <?php else: ?>
                                    <div class="rounded d-flex align-items-center justify-content-center"
                                         style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <i class="ph ph-newspaper text-white f-s-20"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="<?php echo e(route('articles.show', $sidebarArticle)); ?>" class="text-decoration-none">
                                        <?php echo e(Str::limit($sidebarArticle->title, 50)); ?>

                                    </a>
                                </h6>
                                <small class="text-muted">
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

            <!-- Tag popolari -->
            <?php if($tags->count() > 0): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo e(__('articles.popular_tags')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <?php $__currentLoopData = $tags->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('articles.index', ['tag' => $tag->id])); ?>"
                                   class="badge bg-light text-dark text-decoration-none">
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
    // Gestione ricerca
    const searchForm = document.getElementById('searchForm');
    const searchInput = searchForm.querySelector('input[name="search"]');

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchForm.submit();
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/articles/index.blade.php ENDPATH**/ ?>