<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="ph-duotone ph-translate text-primary me-2"></i>
                        <?php echo e(__('admin.translation_management')); ?>

                    </h1>
                    <p class="text-muted mb-0"><?php echo e(__('admin.translation_management_description')); ?></p>
                </div>
                <div>
                    <a href="<?php echo e(route('admin.translations.create')); ?>" class="btn btn-primary">
                        <i class="ph-duotone ph-plus me-1"></i>
                        <?php echo e(__('admin.add_language')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-primary"><?php echo e(count($languages)); ?></h4>
                                <p class="text-muted mb-0 f-s-14"><?php echo e(__('admin.available_languages')); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-success"><?php echo e($languageStats['total_translated']); ?></h4>
                                <p class="text-muted mb-0 f-s-14"><?php echo e(__('admin.total_translated')); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-warning"><?php echo e($languageStats['total_missing']); ?></h4>
                                <p class="text-muted mb-0 f-s-14"><?php echo e(__('admin.total_missing')); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4 class="mb-1 text-info"><?php echo e($languageStats['total_keys']); ?></h4>
                            <p class="text-muted mb-0 f-s-14"><?php echo e(__('admin.total_keys')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Languages List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-list-bullets me-2"></i>
                        <?php echo e(__('admin.available_languages')); ?>

                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if(count($languages) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;"><?php echo e(__('admin.language')); ?></th>
                                        <th style="width: 15%;"><?php echo e(__('admin.code')); ?></th>
                                        <th style="width: 15%;"><?php echo e(__('admin.translated')); ?></th>
                                        <th style="width: 15%;"><?php echo e(__('admin.missing')); ?></th>
                                        <th style="width: 15%;"><?php echo e(__('admin.progress')); ?></th>
                                        <th style="width: 20%;"><?php echo e(__('admin.actions')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2"><?php echo e(__('admin.language_' . $lang) ?: ucfirst($lang)); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="text-primary"><?php echo e(strtoupper($lang)); ?></code>
                                        </td>
                                        <td>
                                            <span class="badge bg-success"><?php echo e($languageStats[$lang]['translated_keys'] ?? 0); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning"><?php echo e($languageStats[$lang]['missing_keys'] ?? 0); ?></span>
                                        </td>
                                        <td>
                                            <?php
                                                $percentage = $languageStats[$lang]['progress_percentage'] ?? 0;
                                            ?>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                    <div class="progress-bar
                                                        <?php if($percentage >= 80): ?> bg-success
                                                        <?php elseif($percentage >= 50): ?> bg-warning
                                                        <?php else: ?> bg-danger
                                                        <?php endif; ?>"
                                                        style="width: <?php echo e($percentage); ?>%"></div>
                                                </div>
                                                <small class="text-muted"><?php echo e($percentage); ?>%</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo e(route('admin.translations.show', $lang)); ?>" class="btn btn-outline-primary" title="<?php echo e(__('admin.edit_translations')); ?>">
                                                    <i class="ph-duotone ph-pencil f-s-14"></i>
                                                </a>
                                                <?php if($lang !== 'it'): ?>
                                                <button type="button" class="btn btn-outline-danger" onclick="deleteLanguage('<?php echo e($lang); ?>')" title="<?php echo e(__('admin.delete_language')); ?>">
                                                    <i class="ph-duotone ph-trash f-s-14"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="ph-duotone ph-translate f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted"><?php echo e(__('admin.no_languages_found')); ?></h5>
                            <p class="text-muted"><?php echo e(__('admin.no_languages_description')); ?></p>
                            <a href="<?php echo e(route('admin.translations.create')); ?>" class="btn btn-primary">
                                <i class="ph-duotone ph-plus me-1"></i>
                                <?php echo e(__('admin.add_first_language')); ?>

                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sync Actions -->
    <?php if(count($languages) > 1): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-arrows-clockwise me-2"></i>
                        <?php echo e(__('admin.sync_actions')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="ph-duotone ph-arrows-clockwise f-s-24 text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?php echo e(__('admin.sync_all_languages')); ?></h6>
                                    <p class="text-muted mb-2 f-s-14"><?php echo e(__('admin.sync_all_languages_description')); ?></p>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="syncAllLanguages()">
                                        <i class="ph-duotone ph-arrows-clockwise me-1"></i>
                                        <?php echo e(__('admin.sync_now')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="ph-duotone ph-info f-s-24 text-info"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?php echo e(__('admin.translation_info')); ?></h6>
                                    <p class="text-muted mb-2 f-s-14"><?php echo e(__('admin.translation_info_description')); ?></p>
                                    <small class="text-muted">
                                        <i class="ph-duotone ph-lightbulb me-1"></i>
                                        <?php echo e(__('admin.translation_tip')); ?>

                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Delete Language Modal -->
<div class="modal fade" id="deleteLanguageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('admin.delete_language')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(__('admin.delete_language_warning')); ?></p>
                <div class="alert alert-warning">
                    <i class="ph-duotone ph-warning-circle me-2"></i>
                    <?php echo e(__('admin.delete_language_irreversible')); ?>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('admin.cancel')); ?></button>
                <button type="button" class="btn btn-danger" id="confirmDeleteLanguage"><?php echo e(__('admin.delete')); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
let languageToDelete = null;

function deleteLanguage(language) {
    languageToDelete = language;
    const modal = new bootstrap.Modal(document.getElementById('deleteLanguageModal'));
    modal.show();
}

document.getElementById('confirmDeleteLanguage').addEventListener('click', function() {
    if (languageToDelete) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo e(route("admin.translations.destroy", ":language")); ?>'.replace(':language', languageToDelete);

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '<?php echo e(csrf_token()); ?>';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
});

function syncAllLanguages() {
    if (confirm('<?php echo e(__('admin.sync_confirm')); ?>')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo e(route("admin.translations.sync")); ?>';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '<?php echo e(csrf_token()); ?>';

        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/translations/index.blade.php ENDPATH**/ ?>