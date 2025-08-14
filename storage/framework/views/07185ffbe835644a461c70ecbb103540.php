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
                <!-- Mobile-First Featured + Recent Articles Layout -->
                <?php if($featuredArticles->count() > 0): ?>
                    <div class="mb-4">
                        <h4 class="mb-3 f-s-18 f-w-600">
                            <i class="ph ph-star me-2"></i>
                            <?php echo e(__('articles.featured_articles')); ?>

                        </h4>
                        
                        <!-- Featured Article 1 + 2 Recent -->
                        <?php if($featuredArticles->count() >= 1): ?>
                            <div class="row g-3 mb-4">
                                <!-- Featured Article -->
                                <div class="col-12">
                                    <?php $featured1 = $featuredArticles->get(0); ?>
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            <?php if($featured1->featured_image): ?>
                                                <img src="<?php echo e(Storage::url($featured1->featured_image)); ?>"
                                                     class="card-img-top" style="height: 250px; object-fit: cover;"
                                                     alt="<?php echo e($featured1->title); ?>">
                                            <?php else: ?>
                                                <div class="card-img-top d-flex align-items-center justify-content-center"
                                                     style="height: 250px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                    <div class="text-center text-white">
                                                        <i class="ph ph-newspaper f-s-32 mb-2"></i>
                                                        <div class="f-s-14 f-w-600"><?php echo e(__('articles.featured_article')); ?></div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <span class="badge bg-warning">
                                                    <i class="ph ph-star me-1"></i><?php echo e(__('articles.featured')); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title f-s-18 f-w-600 mb-3"><?php echo e($featured1->title); ?></h5>
                                            <p class="card-text f-s-14 text-muted mb-3"><?php echo e(Str::limit($featured1->excerpt, 150)); ?></p>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-3 text-muted f-s-12">
                                                    <span><i class="ph ph-user me-1"></i><?php echo e($featured1->user->name ?? 'N/A'); ?></span>
                                                    <span><i class="ph ph-calendar me-1"></i><?php echo e($featured1->published_at->format('d/m/Y')); ?></span>
                                                    <span><i class="ph ph-eye me-1"></i><?php echo e($featured1->views_count ?? 0); ?></span>
                                                    <span><i class="ph ph-heart me-1"></i><?php echo e($featured1->likes_count); ?></span>
                                                    <span><i class="ph ph-chat-circle me-1"></i><?php echo e($featured1->comments_count); ?></span>
                                                </div>
                                                <a href="<?php echo e(route('articles.show', $featured1->slug)); ?>" class="btn btn-primary">
                                                    <?php echo e(__('articles.read_more')); ?>

                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- 2 Recent Articles -->
                                <?php if($recentArticles->count() >= 2): ?>
                                    <div class="col-12 col-sm-6">
                                        <?php $recent1 = $recentArticles->get(0); ?>
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                <?php if($recent1->featured_image): ?>
                                                    <img src="<?php echo e(Storage::url($recent1->featured_image)); ?>"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="<?php echo e($recent1->title); ?>">
                                                <?php else: ?>
                                                    <div class="card-img-top d-flex align-items-center justify-content-center"
                                                         style="height: 140px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                        <div class="text-center text-white">
                                                            <i class="ph ph-newspaper f-s-20 mb-1"></i>
                                                            <div class="f-s-10 f-w-600"><?php echo e(__('articles.article')); ?></div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($recent1->category): ?>
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-primary"><?php echo e($recent1->category->name); ?></span>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2"><?php echo e(Str::limit($recent1->title, 50)); ?></h6>
                                                <p class="card-text f-s-13 text-muted mb-3"><?php echo e(Str::limit($recent1->excerpt, 70)); ?></p>
                                                
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center text-muted f-s-11 mb-2">
                                                        <span><i class="ph ph-user me-1"></i><?php echo e($recent1->user->name ?? 'N/A'); ?></span>
                                                        <span><i class="ph ph-calendar me-1"></i><?php echo e($recent1->published_at->format('d/m/Y')); ?></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <span><i class="ph ph-eye me-1"></i><?php echo e($recent1->views_count ?? 0); ?></span>
                                                            <span><i class="ph ph-heart me-1"></i><?php echo e($recent1->likes_count); ?></span>
                                                            <span><i class="ph ph-chat-circle me-1"></i><?php echo e($recent1->comments_count); ?></span>
                                                        </div>
                                                        <a href="<?php echo e(route('articles.show', $recent1->slug)); ?>" class="btn btn-outline-primary btn-sm">
                                                            <?php echo e(__('articles.read_more')); ?>

                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-sm-6">
                                        <?php $recent2 = $recentArticles->get(1); ?>
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                <?php if($recent2->featured_image): ?>
                                                    <img src="<?php echo e(Storage::url($recent2->featured_image)); ?>"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="<?php echo e($recent2->title); ?>">
                                                <?php else: ?>
                                                    <div class="card-img-top d-flex align-items-center justify-content-center"
                                                         style="height: 140px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                        <div class="text-center text-white">
                                                            <i class="ph ph-newspaper f-s-20 mb-1"></i>
                                                            <div class="f-s-10 f-w-600"><?php echo e(__('articles.article')); ?></div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($recent2->category): ?>
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-primary"><?php echo e($recent2->category->name); ?></span>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2"><?php echo e(Str::limit($recent2->title, 50)); ?></h6>
                                                <p class="card-text f-s-13 text-muted mb-3"><?php echo e(Str::limit($recent2->excerpt, 70)); ?></p>
                                                
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center text-muted f-s-11 mb-2">
                                                        <span><i class="ph ph-user me-1"></i><?php echo e($recent2->user->name ?? 'N/A'); ?></span>
                                                        <span><i class="ph ph-calendar me-1"></i><?php echo e($recent2->published_at->format('d/m/Y')); ?></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <span><i class="ph ph-eye me-1"></i><?php echo e($recent2->views_count ?? 0); ?></span>
                                                            <span><i class="ph ph-heart me-1"></i><?php echo e($recent2->likes_count); ?></span>
                                                            <span><i class="ph ph-chat-circle me-1"></i><?php echo e($recent2->comments_count); ?></span>
                                                        </div>
                                                        <a href="<?php echo e(route('articles.show', $recent2->slug)); ?>" class="btn btn-outline-primary btn-sm">
                                                            <?php echo e(__('articles.read_more')); ?>

                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Featured Article 2 + 2 Recent -->
                        <?php if($featuredArticles->count() >= 2): ?>
                            <div class="row g-3 mb-4">
                                <!-- Featured Article -->
                                <div class="col-12">
                                    <?php $featured2 = $featuredArticles->get(1); ?>
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            <?php if($featured2->featured_image): ?>
                                                <img src="<?php echo e(Storage::url($featured2->featured_image)); ?>"
                                                     class="card-img-top" style="height: 250px; object-fit: cover;"
                                                     alt="<?php echo e($featured2->title); ?>">
                                            <?php else: ?>
                                                <div class="card-img-top d-flex align-items-center justify-content-center"
                                                     style="height: 250px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                    <div class="text-center text-white">
                                                        <i class="ph ph-newspaper f-s-32 mb-2"></i>
                                                        <div class="f-s-14 f-w-600"><?php echo e(__('articles.featured_article')); ?></div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <span class="badge bg-warning">
                                                    <i class="ph ph-star me-1"></i><?php echo e(__('articles.featured')); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title f-s-18 f-w-600 mb-3"><?php echo e($featured2->title); ?></h5>
                                            <p class="card-text f-s-14 text-muted mb-3"><?php echo e(Str::limit($featured2->excerpt, 150)); ?></p>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-3 text-muted f-s-12">
                                                    <span><i class="ph ph-user me-1"></i><?php echo e($featured2->user->name ?? 'N/A'); ?></span>
                                                    <span><i class="ph ph-calendar me-1"></i><?php echo e($featured2->published_at->format('d/m/Y')); ?></span>
                                                    <span><i class="ph ph-eye me-1"></i><?php echo e($featured2->views_count ?? 0); ?></span>
                                                    <span><i class="ph ph-heart me-1"></i><?php echo e($featured2->likes_count); ?></span>
                                                    <span><i class="ph ph-chat-circle me-1"></i><?php echo e($featured2->comments_count); ?></span>
                                                </div>
                                                <a href="<?php echo e(route('articles.show', $featured2->slug)); ?>" class="btn btn-primary">
                                                    <?php echo e(__('articles.read_more')); ?>

                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- 2 Recent Articles -->
                                <?php if($recentArticles->count() >= 4): ?>
                                    <div class="col-12 col-sm-6">
                                        <?php $recent3 = $recentArticles->get(2); ?>
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                <?php if($recent3->featured_image): ?>
                                                    <img src="<?php echo e(Storage::url($recent3->featured_image)); ?>"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="<?php echo e($recent3->title); ?>">
                                                <?php else: ?>
                                                    <div class="card-img-top d-flex align-items-center justify-content-center"
                                                         style="height: 140px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                        <div class="text-center text-white">
                                                            <i class="ph ph-newspaper f-s-20 mb-1"></i>
                                                            <div class="f-s-10 f-w-600"><?php echo e(__('articles.article')); ?></div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($recent3->category): ?>
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-primary"><?php echo e($recent3->category->name); ?></span>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2"><?php echo e(Str::limit($recent3->title, 50)); ?></h6>
                                                <p class="card-text f-s-13 text-muted mb-3"><?php echo e(Str::limit($recent3->excerpt, 70)); ?></p>
                                                
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center text-muted f-s-11 mb-2">
                                                        <span><i class="ph ph-user me-1"></i><?php echo e($recent3->user->name ?? 'N/A'); ?></span>
                                                        <span><i class="ph ph-calendar me-1"></i><?php echo e($recent3->published_at->format('d/m/Y')); ?></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <span><i class="ph ph-eye me-1"></i><?php echo e($recent3->views_count ?? 0); ?></span>
                                                            <span><i class="ph ph-heart me-1"></i><?php echo e($recent3->likes_count); ?></span>
                                                            <span><i class="ph ph-chat-circle me-1"></i><?php echo e($recent3->comments_count); ?></span>
                                                        </div>
                                                        <a href="<?php echo e(route('articles.show', $recent3->slug)); ?>" class="btn btn-outline-primary btn-sm">
                                                            <?php echo e(__('articles.read_more')); ?>

                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-sm-6">
                                        <?php $recent4 = $recentArticles->get(3); ?>
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                <?php if($recent4->featured_image): ?>
                                                    <img src="<?php echo e(Storage::url($recent4->featured_image)); ?>"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="<?php echo e($recent4->title); ?>">
                                                <?php else: ?>
                                                    <div class="card-img-top d-flex align-items-center justify-content-center"
                                                         style="height: 140px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                        <div class="text-center text-white">
                                                            <i class="ph ph-newspaper f-s-20 mb-1"></i>
                                                            <div class="f-s-10 f-w-600"><?php echo e(__('articles.article')); ?></div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($recent4->category): ?>
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-primary"><?php echo e($recent4->category->name); ?></span>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2"><?php echo e(Str::limit($recent4->title, 50)); ?></h6>
                                                <p class="card-text f-s-13 text-muted mb-3"><?php echo e(Str::limit($recent4->excerpt, 70)); ?></p>
                                                
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center text-muted f-s-11 mb-2">
                                                        <span><i class="ph ph-user me-1"></i><?php echo e($recent4->user->name ?? 'N/A'); ?></span>
                                                        <span><i class="ph ph-calendar me-1"></i><?php echo e($recent4->published_at->format('d/m/Y')); ?></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <span><i class="ph ph-eye me-1"></i><?php echo e($recent4->views_count ?? 0); ?></span>
                                                            <span><i class="ph ph-heart me-1"></i><?php echo e($recent4->likes_count); ?></span>
                                                            <span><i class="ph ph-chat-circle me-1"></i><?php echo e($recent4->comments_count); ?></span>
                                                        </div>
                                                        <a href="<?php echo e(route('articles.show', $recent4->slug)); ?>" class="btn btn-outline-primary btn-sm">
                                                            <?php echo e(__('articles.read_more')); ?>

                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Featured Article 3 + 2 Recent -->
                        <?php if($featuredArticles->count() >= 3): ?>
                            <div class="row g-3 mb-4">
                                <!-- Featured Article -->
                                <div class="col-12">
                                    <?php $featured3 = $featuredArticles->get(2); ?>
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            <?php if($featured3->featured_image): ?>
                                                <img src="<?php echo e(Storage::url($featured3->featured_image)); ?>"
                                                     class="card-img-top" style="height: 250px; object-fit: cover;"
                                                     alt="<?php echo e($featured3->title); ?>">
                                            <?php else: ?>
                                                <div class="card-img-top d-flex align-items-center justify-content-center"
                                                     style="height: 250px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                    <div class="text-center text-white">
                                                        <i class="ph ph-newspaper f-s-32 mb-2"></i>
                                                        <div class="f-s-14 f-w-600"><?php echo e(__('articles.featured_article')); ?></div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <span class="badge bg-warning">
                                                    <i class="ph ph-star me-1"></i><?php echo e(__('articles.featured')); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title f-s-18 f-w-600 mb-3"><?php echo e($featured3->title); ?></h5>
                                            <p class="card-text f-s-14 text-muted mb-3"><?php echo e(Str::limit($featured3->excerpt, 150)); ?></p>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-3 text-muted f-s-12">
                                                    <span><i class="ph ph-user me-1"></i><?php echo e($featured3->user->name ?? 'N/A'); ?></span>
                                                    <span><i class="ph ph-calendar me-1"></i><?php echo e($featured3->published_at->format('d/m/Y')); ?></span>
                                                    <span><i class="ph ph-eye me-1"></i><?php echo e($featured3->views_count ?? 0); ?></span>
                                                    <span><i class="ph ph-heart me-1"></i><?php echo e($featured3->likes_count); ?></span>
                                                    <span><i class="ph ph-chat-circle me-1"></i><?php echo e($featured3->comments_count); ?></span>
                                                </div>
                                                <a href="<?php echo e(route('articles.show', $featured3->slug)); ?>" class="btn btn-primary">
                                                    <?php echo e(__('articles.read_more')); ?>

                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- 2 Recent Articles -->
                                <?php if($recentArticles->count() >= 6): ?>
                                    <div class="col-12 col-sm-6">
                                        <?php $recent5 = $recentArticles->get(4); ?>
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                <?php if($recent5->featured_image): ?>
                                                    <img src="<?php echo e(Storage::url($recent5->featured_image)); ?>"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="<?php echo e($recent5->title); ?>">
                                                <?php else: ?>
                                                    <div class="card-img-top d-flex align-items-center justify-content-center"
                                                         style="height: 140px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                        <div class="text-center text-white">
                                                            <i class="ph ph-newspaper f-s-20 mb-1"></i>
                                                            <div class="f-s-10 f-w-600"><?php echo e(__('articles.article')); ?></div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($recent5->category): ?>
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-primary"><?php echo e($recent5->category->name); ?></span>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2"><?php echo e(Str::limit($recent5->title, 50)); ?></h6>
                                                <p class="card-text f-s-13 text-muted mb-3"><?php echo e(Str::limit($recent5->excerpt, 70)); ?></p>
                                                
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center text-muted f-s-11 mb-2">
                                                        <span><i class="ph ph-user me-1"></i><?php echo e($recent5->user->name ?? 'N/A'); ?></span>
                                                        <span><i class="ph ph-calendar me-1"></i><?php echo e($recent5->published_at->format('d/m/Y')); ?></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <span><i class="ph ph-eye me-1"></i><?php echo e($recent5->views_count ?? 0); ?></span>
                                                            <span><i class="ph ph-heart me-1"></i><?php echo e($recent5->likes_count); ?></span>
                                                            <span><i class="ph ph-chat-circle me-1"></i><?php echo e($recent5->comments_count); ?></span>
                                                        </div>
                                                        <a href="<?php echo e(route('articles.show', $recent5->slug)); ?>" class="btn btn-outline-primary btn-sm">
                                                            <?php echo e(__('articles.read_more')); ?>

                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-sm-6">
                                        <?php $recent6 = $recentArticles->get(5); ?>
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                <?php if($recent6->featured_image): ?>
                                                    <img src="<?php echo e(Storage::url($recent6->featured_image)); ?>"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="<?php echo e($recent6->title); ?>">
                                                <?php else: ?>
                                                    <div class="card-img-top d-flex align-items-center justify-content-center"
                                                         style="height: 140px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                        <div class="text-center text-white">
                                                            <i class="ph ph-newspaper f-s-20 mb-1"></i>
                                                            <div class="f-s-10 f-w-600"><?php echo e(__('articles.article')); ?></div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if($recent6->category): ?>
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-primary"><?php echo e($recent6->category->name); ?></span>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2"><?php echo e(Str::limit($recent6->title, 50)); ?></h6>
                                                <p class="card-text f-s-13 text-muted mb-3"><?php echo e(Str::limit($recent6->excerpt, 70)); ?></p>
                                                
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center text-muted f-s-11 mb-2">
                                                        <span><i class="ph ph-user me-1"></i><?php echo e($recent6->user->name ?? 'N/A'); ?></span>
                                                        <span><i class="ph ph-calendar me-1"></i><?php echo e($recent6->published_at->format('d/m/Y')); ?></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <span><i class="ph ph-eye me-1"></i><?php echo e($recent6->views_count ?? 0); ?></span>
                                                            <span><i class="ph ph-heart me-1"></i><?php echo e($recent6->likes_count); ?></span>
                                                            <span><i class="ph ph-chat-circle me-1"></i><?php echo e($recent6->comments_count); ?></span>
                                                        </div>
                                                        <a href="<?php echo e(route('articles.show', $recent6->slug)); ?>" class="btn btn-outline-primary btn-sm">
                                                            <?php echo e(__('articles.read_more')); ?>

                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
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