<?php $__env->startSection('title', __('languages.title')); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/vendor/flag-icons-master/flag-icon.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-translate me-2 text-primary"></i>
                        <?php echo e(__('languages.title')); ?>

                    </h4>
                    <a href="<?php echo e(route('profile.languages.create')); ?>" class="btn btn-primary">
                        <i class="ph-duotone ph-plus me-2"></i>
                        <?php echo e(__('languages.add_language')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Messaggi Flash -->
    <?php if(session('success')): ?>
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-check-circle me-2"></i>
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-x-circle me-2"></i>
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Lista Lingue -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php if($languages->count() > 0): ?>
                        <div class="row">
                            <?php $__currentLoopData = $languages->groupBy('language_code'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $languageCode => $languageGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-12 col-md-6 col-lg-4 mb-3">
                                    <div class="card hover-effect equal-card">
                                        <div class="card-body">
                                            <!-- Nome Lingua -->
                                            <h5 class="card-title mb-3">
                                                <?php echo \App\Helpers\FlagHelper::getFlagIconWithName($languageGroup->first()->language_code, $languageGroup->first()->language_name); ?>

                                            </h5>

                                            <!-- Competenze -->
                                            <div class="mb-3">
                                                <?php $__currentLoopData = $languageGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-<?php echo e($language->type === 'native' ? 'success' : ($language->type === 'spoken' ? 'info' : 'warning')); ?>">
                                                            <?php echo e($language->competence_description); ?>

                                                        </span>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="<?php echo e(route('profile.languages.edit', $language)); ?>"
                                                               class="btn btn-outline-primary btn-sm"
                                                               title="<?php echo e(__('common.edit')); ?>">
                                                                <i class="ph-duotone ph-pencil"></i>
                                                            </a>
                                                            <form action="<?php echo e(route('profile.languages.destroy', $language)); ?>"
                                                                  method="POST"
                                                                  class="d-inline"
                                                                  onsubmit="return confirm('<?php echo e(__('languages.delete_confirm')); ?>')">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <button type="submit"
                                                                        class="btn btn-outline-danger btn-sm"
                                                                        title="<?php echo e(__('common.delete')); ?>">
                                                                    <i class="ph-duotone ph-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="ph-duotone ph-translate text-muted f-s-64 mb-3"></i>
                            <h5 class="text-muted"><?php echo e(__('languages.no_languages')); ?></h5>
                            <p class="text-muted mb-4"><?php echo e(__('languages.no_languages_description')); ?></p>
                            <a href="<?php echo e(route('profile.languages.create')); ?>" class="btn btn-primary">
                                <i class="ph-duotone ph-plus me-2"></i>
                                <?php echo e(__('languages.add_first_language')); ?>

                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/profile/languages/index.blade.php ENDPATH**/ ?>