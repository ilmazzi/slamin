<?php $__env->startSection('title', $poem->title); ?>

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
                                <a href="<?php echo e(route('poems.index')); ?>" class="text-decoration-none">
                                    <i class="ph ph-book-open me-1"></i><?php echo e(__('poems.title')); ?>

                                </a>
                            </li>
                            <li class="breadcrumb-item active text-truncate" style="max-width: 200px;">
                                <?php echo e($poem->title); ?>

                            </li>
                        </ol>
                    </div>
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
                                <span class="badge bg-light-primary"><?php echo e(__('poems.categories.' . $poem->category)); ?></span>
                                <span class="badge bg-light-primary"><?php echo e(__('poems.poem_types.' . $poem->poem_type)); ?></span>
                                <span class="badge bg-light-primary"><?php echo e(__('poems.languages.' . $poem->language)); ?></span>
                                <?php if($poem->is_featured): ?>
                                    <span class="badge bg-light-warning">
                                        <i class="ph ph-star me-1"></i><?php echo e(__('poems.status.featured')); ?>

                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Selettore Lingue Disponibili -->
                            <?php if($poem->available_languages->count() > 1): ?>
                            <div class="mb-3">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="text-muted small">
                                        <i class="ph ph-translate me-1"></i><?php echo e(__('poems.available_languages')); ?>:
                                    </span>
                                    <div class="btn-group" role="group" id="language-selector">
                                        <?php $__currentLoopData = $poem->available_languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <button type="button"
                                                    class="btn btn-sm <?php echo e($lang['is_original'] ? 'btn-primary' : 'btn-outline-primary'); ?> language-btn"
                                                    data-language="<?php echo e($lang['code']); ?>"
                                                    data-original="<?php echo e($lang['is_original'] ? 'true' : 'false'); ?>">
                                                <?php if($lang['is_original']): ?>
                                                    <i class="ph ph-flag me-1"></i>
                                                <?php else: ?>
                                                    <i class="ph ph-translate me-1"></i>
                                                <?php endif; ?>
                                                <?php echo e($lang['name']); ?>

                                                <?php if(!$lang['is_original'] && $lang['is_official']): ?>
                                                    <i class="ph ph-check-circle ms-1 text-success"></i>
                                                <?php endif; ?>
                                            </button>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="ph ph-user me-1"></i>
                                <a href="<?php echo e(route('user.show', $poem->user)); ?>" class="text-decoration-none hover-effect"><?php echo e($poem->user->getDisplayName()); ?></a>
                                <span class="mx-2">•</span>
                                <i class="ph ph-calendar me-1"></i>
                                <?php echo e($poem->published_at ? $poem->published_at->format('d/m/Y') : $poem->created_at->format('d/m/Y')); ?>

                                <span class="mx-2">•</span>
                                <i class="ph ph-eye me-1"></i>
                                <?php echo e(number_format($poem->views_count)); ?> <?php echo e(__('poems.stats.views')); ?>

                            </div>
                        </div>

                                                <?php if(auth()->guard()->check()): ?>
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="dropdown">
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
                                        <a class="dropdown-item" href="<?php echo e(route('poems.edit', $poem->slug)); ?>">
                                            <i class="ph ph-pencil me-2"></i><?php echo e(__('common.edit')); ?>

                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if($poem->canBeDeletedBy(auth()->user())): ?>
                                    <li>
                                        <form action="<?php echo e(route('poems.destroy', $poem->slug)); ?>" method="POST" class="d-inline">
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
                                <h5 class="text-primary mb-1"><?php echo e(number_format($poem->likes_count)); ?></h5>
                                <small class="text-muted"><?php echo e(__('poems.stats.likes')); ?></small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <h5 class="text-info mb-1"><?php echo e(number_format($poem->comments_count)); ?></h5>
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
                        <?php if (isset($component)) { $__componentOriginal723641259025d9a0842581325b5584a2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal723641259025d9a0842581325b5584a2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-like-button','data' => ['content' => $poem]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-like-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($poem)]); ?>
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
                        <?php if (isset($component)) { $__componentOriginal6f504f396e2242cb757c367dd734f8bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6f504f396e2242cb757c367dd734f8bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-comment-button','data' => ['content' => $poem,'type' => 'poem']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-comment-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($poem),'type' => 'poem']); ?>
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

                        <button class="btn btn-warning icon-btn" onclick="toggleBookmark()" id="bookmarkBtn" title="<?php echo e(__('poems.actions.bookmark')); ?>">
                            <i class="ph <?php echo e($poem->is_bookmarked_by_current_user ? 'ph-bookmark-fill text-warning' : 'ph-bookmark'); ?>"></i>
                        </button>

                        <button class="btn btn-info icon-btn" onclick="sharePoem()" title="<?php echo e(__('common.share')); ?>">
                            <i class="ph ph-share"></i>
                        </button>


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
                    <!-- Contatori social per utenti non autenticati -->
                    <div class="d-flex justify-content-center gap-4 mb-4">
                        <div class="text-center">
                            <div class="social-counter" style="display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 8px; border-radius: 8px;">
                                <i class="ph ph-heart f-s-24 text-muted" style="opacity: 0.6;"></i>
                                <span class="text-secondary f-s-12"><?php echo e(number_format($poem->likes_count)); ?></span>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="social-counter" style="display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 8px; border-radius: 8px;">
                                <i class="ph ph-bookmark f-s-24 text-muted" style="opacity: 0.6;"></i>
                                <span class="text-secondary f-s-12"><?php echo e(number_format($poem->bookmark_count)); ?></span>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="social-counter" style="display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 8px; border-radius: 8px;">
                                <i class="ph ph-share f-s-24 text-muted" style="opacity: 0.6;"></i>
                                <span class="text-secondary f-s-12">Condividi</span>
                            </div>
                        </div>
                    </div>

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

            <!-- Sezione commenti unificata -->
            <?php if (isset($component)) { $__componentOriginal3a0426d3cc93dd4143162417cb66a587 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3a0426d3cc93dd4143162417cb66a587 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-comments-section','data' => ['content' => $poem]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-comments-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($poem)]); ?>
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
            <!-- Informazioni autore -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph ph-user text-primary me-2"></i>
                        <?php echo e(__('poems.about_author')); ?>

                    </h5>
                </div>
                <div class="card-body text-center">
                   <img src="<?php echo e($poem->user->getProfilePhotoUrlAttribute()); ?>"
                             class="rounded-circle mb-3" width="80" height="80" alt="<?php echo e($poem->user->name); ?>">
                    <h6>
                        <a href="<?php echo e(route('user.show', $poem->user)); ?>" class="text-decoration-none hover-effect">
                            <?php echo e($poem->user->getDisplayName()); ?>

                        </a>
                    </h6>
                    <p class="text-muted small mb-3"><?php echo e($poem->user->bio ?? __('poems.no_bio')); ?></p>

                    <div class="row text-center">
                        <div class="col-4">
                            <h6 class="text-primary"><?php echo e($poem->user->poems()->published()->count()); ?></h6>
                            <small class="text-muted"><?php echo e(__('poems.poems')); ?></small>
                        </div>
                        <div class="col-4">
                            <h6 class="text-info"><?php echo e($poem->user->poems()->published()->get()->sum('likes_count')); ?></h6>
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
                            <?php if($relatedPoem->thumbnail): ?>
                                <img src="<?php echo e($relatedPoem->thumbnail); ?>" class="rounded me-3"
                                     width="60" height="60" alt="<?php echo e($relatedPoem->title); ?>">
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <?php if($relatedPoem->slug): ?>
                                        <a href="<?php echo e(route('poems.show', $relatedPoem->slug)); ?>" class="text-decoration-none">
                                            <?php echo e($relatedPoem->title); ?>

                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted"><?php echo e($relatedPoem->title); ?></span>
                                    <?php endif; ?>
                                </h6>
                                <small class="text-muted">
                                    <a href="<?php echo e(route('user.show', $relatedPoem->user)); ?>" class="text-decoration-none hover-effect">
                                        <?php echo e($relatedPoem->user->getDisplayName()); ?>

                                    </a>
                                </small>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <?php if (isset($component)) { $__componentOriginal723641259025d9a0842581325b5584a2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal723641259025d9a0842581325b5584a2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-like-button','data' => ['content' => $relatedPoem,'type' => 'poem','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-like-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($relatedPoem),'type' => 'poem','size' => 'sm']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-view-counter','data' => ['content' => $relatedPoem,'type' => 'poem','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-view-counter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($relatedPoem),'type' => 'poem','size' => 'sm']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-comment-button','data' => ['content' => $relatedPoem,'type' => 'poem','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-comment-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($relatedPoem),'type' => 'poem','size' => 'sm']); ?>
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
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-warning"><?php echo e($poem->comments_count); ?></h6>
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
// Funzioni rimosse - ora usiamo i componenti unificati

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


// Gestione cambio lingua dinamico
document.addEventListener('DOMContentLoaded', function() {
    const languageButtons = document.querySelectorAll('.language-btn');
    const poemTitle = document.querySelector('.card-title');
    const poemContent = document.querySelector('.poem-content');
    const poemDescription = document.querySelector('.poem-description');

    // Dati originali della poesia
    const originalData = {
        title: <?php echo json_encode($poem->title); ?>,
        content: <?php echo json_encode($poem->content); ?>,
        description: <?php echo json_encode($poem->description ?? ''); ?>

    };

    languageButtons.forEach(button => {
        button.addEventListener('click', function() {
            const language = this.dataset.language;
            const isOriginal = this.dataset.original === 'true';

            // Aggiorna lo stato dei pulsanti
            languageButtons.forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            });
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary');

            if (isOriginal) {
                // Mostra il contenuto originale
                poemTitle.textContent = originalData.title;
                poemContent.innerHTML = originalData.content;
                if (poemDescription) {
                    poemDescription.textContent = originalData.description;
                }
            } else {
                // Carica il contenuto tradotto
                const url = `/poems/<?php echo e($poem->slug); ?>/translations/${language}`;
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        poemTitle.textContent = data.title;
                        poemContent.innerHTML = data.content;
                        if (poemDescription) {
                            poemDescription.textContent = data.description || '';
                        }
                    })
                    .catch(error => {
                        console.error('Errore nel caricamento della traduzione:', error);
                        // Fallback al contenuto originale
                        poemTitle.textContent = originalData.title;
                        poemContent.innerHTML = originalData.content;
                        if (poemDescription) {
                            poemDescription.textContent = originalData.description;
                        }
                    });
            }
        });
    });
});
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