<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><?php echo e(__('articles.layout_preview')); ?></h4>
                        <div>
                            <a href="<?php echo e(route('articles.layout.index')); ?>" class="btn btn-outline-primary">
                                <i class="ti ti-arrow-left"></i> <?php echo e(__('articles.back_to_layout')); ?>

                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Layout Preview -->
                        <div class="col-12">
                            <div class="layout-preview">
                                <!-- Banner principale -->
                                <?php if(isset($layoutArticles['banner'])): ?>
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="mb-0"><?php echo e(__('articles.banner')); ?></h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-center">
                                                        <?php if($layoutArticles['banner']->featured_image): ?>
                                                            <img src="<?php echo e(Storage::url($layoutArticles['banner']->featured_image)); ?>"
                                                                 class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;"
                                                                 alt="<?php echo e($layoutArticles['banner']->title); ?>">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px;">
                                                                <i class="ph ph-newspaper text-muted f-s-32"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="flex-grow-1">
                                                            <h4 class="mb-1"><?php echo e($layoutArticles['banner']->title); ?></h4>
                                                            <p class="text-muted mb-2"><?php echo e(Str::limit($layoutArticles['banner']->excerpt, 150)); ?></p>
                                                            <small class="text-muted">
                                                                <i class="ti ti-user me-1"></i><?php echo e($layoutArticles['banner']->user->name); ?>

                                                                <i class="ti ti-calendar ms-3 me-1"></i><?php echo e($layoutArticles['banner']->created_at->format('d/m/Y')); ?>

                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Prima riga: 2 colonne -->
                                <div class="row mb-4">
                                    <?php if(isset($layoutArticles['column1'])): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0"><?php echo e(__('articles.column1')); ?></h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-start">
                                                        <?php if($layoutArticles['column1']->featured_image): ?>
                                                            <img src="<?php echo e(Storage::url($layoutArticles['column1']->featured_image)); ?>"
                                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;"
                                                                 alt="<?php echo e($layoutArticles['column1']->title); ?>">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                                <i class="ph ph-newspaper text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1"><?php echo e($layoutArticles['column1']->title); ?></h6>
                                                            <p class="text-muted small mb-2"><?php echo e(Str::limit($layoutArticles['column1']->excerpt, 100)); ?></p>
                                                            <small class="text-muted"><?php echo e($layoutArticles['column1']->user->name); ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if(isset($layoutArticles['column2'])): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0"><?php echo e(__('articles.column2')); ?></h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-start">
                                                        <?php if($layoutArticles['column2']->featured_image): ?>
                                                            <img src="<?php echo e(Storage::url($layoutArticles['column2']->featured_image)); ?>"
                                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;"
                                                                 alt="<?php echo e($layoutArticles['column2']->title); ?>">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                                <i class="ph ph-newspaper text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1"><?php echo e($layoutArticles['column2']->title); ?></h6>
                                                            <p class="text-muted small mb-2"><?php echo e(Str::limit($layoutArticles['column2']->excerpt, 100)); ?></p>
                                                            <small class="text-muted"><?php echo e($layoutArticles['column2']->user->name); ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Articolo orizzontale 1 -->
                                <?php if(isset($layoutArticles['horizontal1'])): ?>
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-warning">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="mb-0"><?php echo e(__('articles.horizontal1')); ?></h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-center">
                                                        <?php if($layoutArticles['horizontal1']->featured_image): ?>
                                                            <img src="<?php echo e(Storage::url($layoutArticles['horizontal1']->featured_image)); ?>"
                                                                 class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;"
                                                                 alt="<?php echo e($layoutArticles['horizontal1']->title); ?>">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px;">
                                                                <i class="ph ph-newspaper text-muted f-s-32"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="flex-grow-1">
                                                            <h5 class="mb-1"><?php echo e($layoutArticles['horizontal1']->title); ?></h5>
                                                            <p class="text-muted mb-2"><?php echo e(Str::limit($layoutArticles['horizontal1']->excerpt, 200)); ?></p>
                                                            <small class="text-muted">
                                                                <i class="ti ti-user me-1"></i><?php echo e($layoutArticles['horizontal1']->user->name); ?>

                                                                <i class="ti ti-calendar ms-3 me-1"></i><?php echo e($layoutArticles['horizontal1']->created_at->format('d/m/Y')); ?>

                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Articolo orizzontale 2 -->
                                <?php if(isset($layoutArticles['horizontal2'])): ?>
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-warning">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="mb-0"><?php echo e(__('articles.horizontal2')); ?></h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-center">
                                                        <?php if($layoutArticles['horizontal2']->featured_image): ?>
                                                            <img src="<?php echo e(Storage::url($layoutArticles['horizontal2']->featured_image)); ?>"
                                                                 class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;"
                                                                 alt="<?php echo e($layoutArticles['horizontal2']->title); ?>">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px;">
                                                                <i class="ph ph-newspaper text-muted f-s-32"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="flex-grow-1">
                                                            <h5 class="mb-1"><?php echo e($layoutArticles['horizontal2']->title); ?></h5>
                                                            <p class="text-muted mb-2"><?php echo e(Str::limit($layoutArticles['horizontal2']->excerpt, 200)); ?></p>
                                                            <small class="text-muted">
                                                                <i class="ti ti-user me-1"></i><?php echo e($layoutArticles['horizontal2']->user->name); ?>

                                                                <i class="ti ti-calendar ms-3 me-1"></i><?php echo e($layoutArticles['horizontal2']->created_at->format('d/m/Y')); ?>

                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Seconda riga: 2 colonne -->
                                <div class="row mb-4">
                                    <?php if(isset($layoutArticles['column3'])): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0"><?php echo e(__('articles.column3')); ?></h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-start">
                                                        <?php if($layoutArticles['column3']->featured_image): ?>
                                                            <img src="<?php echo e(Storage::url($layoutArticles['column3']->featured_image)); ?>"
                                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;"
                                                                 alt="<?php echo e($layoutArticles['column3']->title); ?>">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                                <i class="ph ph-newspaper text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1"><?php echo e($layoutArticles['column3']->title); ?></h6>
                                                            <p class="text-muted small mb-2"><?php echo e(Str::limit($layoutArticles['column3']->excerpt, 100)); ?></p>
                                                            <small class="text-muted"><?php echo e($layoutArticles['column3']->user->name); ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if(isset($layoutArticles['column4'])): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0"><?php echo e(__('articles.column4')); ?></h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-start">
                                                        <?php if($layoutArticles['column4']->featured_image): ?>
                                                            <img src="<?php echo e(Storage::url($layoutArticles['column4']->featured_image)); ?>"
                                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;"
                                                                 alt="<?php echo e($layoutArticles['column4']->title); ?>">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                                <i class="ph ph-newspaper text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1"><?php echo e($layoutArticles['column4']->title); ?></h6>
                                                            <p class="text-muted small mb-2"><?php echo e(Str::limit($layoutArticles['column4']->excerpt, 100)); ?></p>
                                                            <small class="text-muted"><?php echo e($layoutArticles['column4']->user->name); ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Articolo orizzontale 3 -->
                                <?php if(isset($layoutArticles['horizontal3'])): ?>
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card border-warning">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="mb-0"><?php echo e(__('articles.horizontal3')); ?></h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-center">
                                                        <?php if($layoutArticles['horizontal3']->featured_image): ?>
                                                            <img src="<?php echo e(Storage::url($layoutArticles['horizontal3']->featured_image)); ?>"
                                                                 class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;"
                                                                 alt="<?php echo e($layoutArticles['horizontal3']->title); ?>">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px;">
                                                                <i class="ph ph-newspaper text-muted f-s-32"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="flex-grow-1">
                                                            <h5 class="mb-1"><?php echo e($layoutArticles['horizontal3']->title); ?></h5>
                                                            <p class="text-muted mb-2"><?php echo e(Str::limit($layoutArticles['horizontal3']->excerpt, 200)); ?></p>
                                                            <small class="text-muted">
                                                                <i class="ti ti-user me-1"></i><?php echo e($layoutArticles['horizontal3']->user->name); ?>

                                                                <i class="ti ti-calendar ms-3 me-1"></i><?php echo e($layoutArticles['horizontal3']->created_at->format('d/m/Y')); ?>

                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Terza riga: 2 colonne -->
                                <div class="row mb-4">
                                    <?php if(isset($layoutArticles['column5'])): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0"><?php echo e(__('articles.column5')); ?></h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-start">
                                                        <?php if($layoutArticles['column5']->featured_image): ?>
                                                            <img src="<?php echo e(Storage::url($layoutArticles['column5']->featured_image)); ?>"
                                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;"
                                                                 alt="<?php echo e($layoutArticles['column5']->title); ?>">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                                <i class="ph ph-newspaper text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1"><?php echo e($layoutArticles['column5']->title); ?></h6>
                                                            <p class="text-muted small mb-2"><?php echo e(Str::limit($layoutArticles['column5']->excerpt, 100)); ?></p>
                                                            <small class="text-muted"><?php echo e($layoutArticles['column5']->user->name); ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if(isset($layoutArticles['column6'])): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0"><?php echo e(__('articles.column6')); ?></h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="article-preview-card">
                                                    <div class="d-flex align-items-start">
                                                        <?php if($layoutArticles['column6']->featured_image): ?>
                                                            <img src="<?php echo e(Storage::url($layoutArticles['column6']->featured_image)); ?>"
                                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;"
                                                                 alt="<?php echo e($layoutArticles['column6']->title); ?>">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                                <i class="ph ph-newspaper text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1"><?php echo e($layoutArticles['column6']->title); ?></h6>
                                                            <p class="text-muted small mb-2"><?php echo e(Str::limit($layoutArticles['column6']->excerpt, 100)); ?></p>
                                                            <small class="text-muted"><?php echo e($layoutArticles['column6']->user->name); ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.layout-preview {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 0.5rem;
    border: 2px dashed #dee2e6;
}

.article-preview-card {
    transition: all 0.3s ease;
}

.article-preview-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .layout-preview {
        padding: 1rem;
    }

    .article-preview-card .d-flex {
        flex-direction: column;
        text-align: center;
    }

    .article-preview-card img,
    .article-preview-card .bg-light {
        margin: 0 auto 1rem auto;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/articles/layout/preview.blade.php ENDPATH**/ ?>