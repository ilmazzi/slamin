<?php $__env->startSection('title', __('poems.edit.title')); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0"><?php echo e(__('poems.edit.title')); ?></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('home')); ?>"><?php echo e(__('common.home')); ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('poems.index')); ?>"><?php echo e(__('poems.title')); ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('poems.show', $poem->slug)); ?>"><?php echo e($poem->title); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?php echo e(__('common.edit')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="ph ph-pencil text-primary me-2"></i>
                        <?php echo e(__('poems.edit.subtitle')); ?>

                    </h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('poems.update', $poem)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <!-- Titolo -->
                            <div class="col-12 mb-3">
                                <label for="title" class="form-label"><?php echo e(__('poems.fields.title')); ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="title" name="title" value="<?php echo e(old('title', $poem->title)); ?>"
                                       placeholder="<?php echo e(__('poems.create.title_placeholder')); ?>" required>
                                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Categoria e Tipo -->
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label"><?php echo e(__('poems.fields.category')); ?> <span class="text-danger">*</span></label>
                                <select class="form-select <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="category" name="category" required>
                                    <option value=""><?php echo e(__('common.select')); ?></option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('category', $poem->category) == $key ? 'selected' : ''); ?>>
                                            <?php echo e($category); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="poem_type" class="form-label"><?php echo e(__('poems.fields.poem_type')); ?> <span class="text-danger">*</span></label>
                                <select class="form-select <?php $__errorArgs = ['poem_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="poem_type" name="poem_type" required>
                                    <option value=""><?php echo e(__('common.select')); ?></option>
                                    <?php $__currentLoopData = $poemTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('poem_type', $poem->poem_type) == $key ? 'selected' : ''); ?>>
                                            <?php echo e($type); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['poem_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- <?php echo e(__('common.language_selector')); ?> e Tags -->
                            <div class="col-md-6 mb-3">
                                <label for="language" class="form-label"><?php echo e(__('poems.fields.language')); ?> <span class="text-danger">*</span></label>
                                <select class="form-select <?php $__errorArgs = ['language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="language" name="language" required>
                                    <option value=""><?php echo e(__('common.select')); ?></option>
                                    <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('language', $poem->language) == $key ? 'selected' : ''); ?>>
                                            <?php echo e($language); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tags" class="form-label"><?php echo e(__('poems.fields.tags')); ?></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['tags'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="tags" name="tags" value="<?php echo e(old('tags', is_array($poem->tags) ? implode(', ', $poem->tags) : $poem->tags)); ?>"
                                       placeholder="<?php echo e(__('poems.create.tags_placeholder')); ?>">
                                <small class="form-text text-muted"><?php echo e(__('poems.create.tags_help')); ?></small>
                                <?php $__errorArgs = ['tags'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Descrizione -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label"><?php echo e(__('poems.fields.description')); ?></label>
                                <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          id="description" name="description" rows="3"
                                          placeholder="<?php echo e(__('poems.create.description_placeholder')); ?>"><?php echo e(old('description', $poem->description)); ?></textarea>
                                <small class="form-text text-muted"><?php echo e(__('poems.create.description_help')); ?></small>
                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Contenuto -->
                            <div class="col-12 mb-3">
                                <label for="content" class="form-label"><?php echo e(__('poems.fields.content')); ?> <span class="text-danger">*</span></label>
                                <textarea class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          id="content" name="content" rows="12"
                                          placeholder="<?php echo e(__('poems.create.content_placeholder')); ?>" required><?php echo e(old('content', $poem->content)); ?></textarea>
                                <small class="form-text text-muted"><?php echo e(__('poems.create.content_help')); ?></small>
                                <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- <?php echo e(__('common.thumbnail')); ?> -->
                            <div class="col-12 mb-3">
                                <label for="thumbnail" class="form-label"><?php echo e(__('poems.fields.thumbnail')); ?></label>

                                <?php if($poem->thumbnail): ?>
                                    <div class="mb-3">
                                        <img src="<?php echo e($poem->thumbnail); ?>" class="img-thumbnail" width="200" alt="<?php echo e(__('common.current_thumbnail')); ?>">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="remove_thumbnail" name="remove_thumbnail" value="1">
                                            <label class="form-check-label" for="remove_thumbnail">
                                                <?php echo e(__('poems.edit.remove_thumbnail')); ?>

                                            </label>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <input type="file" class="form-control <?php $__errorArgs = ['thumbnail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="thumbnail" name="thumbnail" accept="image/*">
                                <small class="form-text text-muted"><?php echo e(__('poems.create.thumbnail_help')); ?></small>
                                <?php $__errorArgs = ['thumbnail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Opzioni di pubblicazione -->
                            <div class="col-12">
                                <div class="card card-light">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="ph ph-gear text-info me-2"></i>
                                            <?php echo e(__('poems.create.publication_options')); ?>

                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1"
                                                           <?php echo e(old('is_public', $poem->is_public) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="is_public">
                                                        <?php echo e(__('poems.fields.is_public')); ?>

                                                    </label>
                                                </div>
                                                <small class="form-text text-muted"><?php echo e(__('poems.create.public_help')); ?></small>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_draft" name="is_draft" value="1"
                                                           <?php echo e(old('is_draft', $poem->is_draft) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="is_draft">
                                                        <?php echo e(__('poems.fields.is_draft')); ?>

                                                    </label>
                                                </div>
                                                <small class="form-text text-muted"><?php echo e(__('poems.create.draft_help')); ?></small>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="translation_available" name="translation_available" value="1"
                                                           <?php echo e(old('translation_available', $poem->translation_available) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="translation_available">
                                                        <?php echo e(__('poems.fields.translation_available')); ?>

                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="translation_price" class="form-label"><?php echo e(__('poems.fields.translation_price')); ?></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">€</span>
                                                    <input type="number" class="form-control <?php $__errorArgs = ['translation_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                           id="translation_price" name="translation_price"
                                                           value="<?php echo e(old('translation_price', $poem->translation_price)); ?>" min="0" step="0.01">
                                                </div>
                                                <?php $__errorArgs = ['translation_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informazioni di stato -->
                            <div class="col-12">
                                <div class="card card-light">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="ph ph-info text-warning me-2"></i>
                                            <?php echo e(__('poems.edit.status_info')); ?>

                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1">
                                                    <strong><?php echo e(__('poems.fields.created_at')); ?>:</strong>
                                                    <?php echo e($poem->created_at->format('d/m/Y H:i')); ?>

                                                </p>
                                                <?php if($poem->published_at): ?>
                                                    <p class="mb-1">
                                                        <strong><?php echo e(__('poems.fields.published_at')); ?>:</strong>
                                                        <?php echo e($poem->published_at->format('d/m/Y H:i')); ?>

                                                    </p>
                                                <?php endif; ?>
                                                <?php if($poem->draft_saved_at): ?>
                                                    <p class="mb-1">
                                                        <strong><?php echo e(__('poems.fields.draft_saved_at')); ?>:</strong>
                                                        <?php echo e($poem->draft_saved_at->format('d/m/Y H:i')); ?>

                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1">
                                                    <strong><?php echo e(__('poems.fields.view_count')); ?>:</strong>
                                                    <?php echo e(number_format($poem->views_count)); ?>

                                                </p>
                                                <p class="mb-1">
                                                    <strong><?php echo e(__('poems.fields.like_count')); ?>:</strong>
                                                    <?php echo e(number_format($poem->likes_count)); ?>

                                                </p>
                                                <p class="mb-1">
                                                    <strong><?php echo e(__('poems.fields.comment_count')); ?>:</strong>
                                                    <?php echo e(number_format($poem->comments_count)); ?>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pulsanti -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="<?php echo e(route('poems.show', $poem)); ?>" class="btn btn-light">
                                        <i class="ph ph-arrow-left me-2"></i>
                                        <?php echo e(__('common.cancel')); ?>

                                    </a>

                                    <div>
                                        <button type="submit" name="action" value="draft" class="btn btn-outline-primary me-2">
                                            <i class="ph ph-floppy-disk me-2"></i>
                                            <?php echo e(__('poems.edit.save_draft')); ?>

                                        </button>

                                        <button type="submit" name="action" value="publish" class="btn btn-primary">
                                            <i class="ph ph-check me-2"></i>
                                            <?php echo e(__('poems.edit.update')); ?>

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestione del draft
    const draftCheckbox = document.getElementById('is_draft');
    const publicCheckbox = document.getElementById('is_public');

    if (draftCheckbox) {
        draftCheckbox.addEventListener('change', function() {
            if (this.checked) {
                publicCheckbox.checked = false;
            }
        });
    }

    if (publicCheckbox) {
        publicCheckbox.addEventListener('change', function() {
            if (this.checked) {
                draftCheckbox.checked = false;
            }
        });
    }

    // Gestione rimozione thumbnail
    const removeThumbnailCheckbox = document.getElementById('remove_thumbnail');
    const thumbnailInput = document.getElementById('thumbnail');

    if (removeThumbnailCheckbox && thumbnailInput) {
        removeThumbnailCheckbox.addEventListener('change', function() {
            if (this.checked) {
                thumbnailInput.disabled = true;
            } else {
                thumbnailInput.disabled = false;
            }
        });
    }

    // Preview del nuovo thumbnail
    if (thumbnailInput) {
        thumbnailInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Qui puoi aggiungere una preview del thumbnail se necessario
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/poems/edit.blade.php ENDPATH**/ ?>