<?php $__env->startSection('title', __('groups.edit_group') . ' - ' . $group->name); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-pencil me-2 text-warning"></i>
                        <?php echo e(__('groups.edit_group')); ?>: <?php echo e($group->name); ?>

                    </h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('groups.update', $group)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Nome del gruppo -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="ph-duotone ph-tag me-1"></i>
                                <?php echo e(__('groups.name')); ?> <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="name"
                                   name="name"
                                   value="<?php echo e(old('name', $group->name)); ?>"
                                   placeholder="<?php echo e(__('groups.group_name_placeholder')); ?>"
                                   required>
                            <?php $__errorArgs = ['name'];
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
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="ph-duotone ph-text-aa me-1"></i>
                                <?php echo e(__('groups.description')); ?>

                            </label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="<?php echo e(__('groups.group_description_placeholder')); ?>"><?php echo e(old('description', $group->description)); ?></textarea>
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

                        <!-- Immagine del gruppo -->
                        <div class="mb-3">
                            <label for="image" class="form-label">
                                <i class="ph-duotone ph-image me-1"></i>
                                <?php echo e(__('groups.image')); ?>

                            </label>

                            <!-- Immagine attuale -->
                            <?php if($group->image): ?>
                            <div class="mb-3">
                                <label class="form-label"><?php echo e(__('common.current_image')); ?>:</label>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo e(asset('storage/' . $group->image)); ?>"
                                         alt="<?php echo e($group->name); ?>"
                                         class="rounded me-3"
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                    <div>
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="document.getElementById('remove_image').value = '1'; this.parentElement.parentElement.style.display = 'none';">
                                            <i class="ph-duotone ph-trash me-1"></i>
                                            <?php echo e(__('common.remove_image')); ?>

                                        </button>
                                        <input type="hidden" id="remove_image" name="remove_image" value="0">
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <input type="file"
                                   class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="image"
                                   name="image"
                                   accept="image/*"
                                   onchange="previewImage(this)">
                            <div class="form-text">
                                <?php echo e(__('common.image_help_text')); ?>

                            </div>
                            <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                            <!-- Anteprima immagine -->
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="ph-duotone ph-eye me-1"></i>
                                            <?php echo e(__('groups.new_image_preview')); ?>

                                        </h6>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                            <i class="ph-duotone ph-x"></i>
                                        </button>
                                    </div>
                                    <div class="card-body text-center">
                                        <img id="previewImg" src="" alt="Anteprima" class="img-fluid rounded" style="max-height: 200px; max-width: 100%;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Visibilità -->
                        <div class="mb-4">
                            <label for="visibility" class="form-label">
                                <i class="ph-duotone ph-eye me-1"></i>
                                <?php echo e(__('groups.visibility')); ?> <span class="text-danger">*</span>
                            </label>
                            <select class="form-select <?php $__errorArgs = ['visibility'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="visibility"
                                    name="visibility"
                                    required>
                                <option value=""><?php echo e(__('common.select_option')); ?></option>
                                <option value="public" <?php echo e(old('visibility', $group->visibility) == 'public' ? 'selected' : ''); ?>>
                                    <?php echo e(__('groups.visibility_public')); ?>

                                </option>
                                <option value="private" <?php echo e(old('visibility', $group->visibility) == 'private' ? 'selected' : ''); ?>>
                                    <?php echo e(__('groups.visibility_private')); ?>

                                </option>
                            </select>
                            <div class="form-text">
                                <strong><?php echo e(__('groups.visibility_public')); ?>:</strong>
                                <?php echo e(__('groups.tips.public_visibility')); ?>

                            </div>
                            <div class="form-text">
                                <strong><?php echo e(__('groups.visibility_private')); ?>:</strong>
                                <?php echo e(__('groups.tips.private_visibility')); ?>

                            </div>
                            <?php $__errorArgs = ['visibility'];
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

                        <!-- Social Links -->
                        <div class="mb-4">
                            <h6 class="mb-3">
                                <i class="ph-duotone ph-share-network me-2"></i>
                                Social Links
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="website" class="form-label">
                                        <i class="ph-duotone ph-globe me-1"></i>
                                        Sito Web
                                    </label>
                                    <input type="url"
                                           class="form-control <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="website"
                                           name="website"
                                           value="<?php echo e(old('website', $group->website)); ?>"
                                           placeholder="https://esempio.com">
                                    <?php $__errorArgs = ['website'];
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
                                    <label for="social_facebook" class="form-label">
                                        <i class="ph-duotone ph-facebook-logo me-1"></i>
                                        Facebook
                                    </label>
                                    <input type="url"
                                           class="form-control <?php $__errorArgs = ['social_facebook'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="social_facebook"
                                           name="social_facebook"
                                           value="<?php echo e(old('social_facebook', $group->social_facebook)); ?>"
                                           placeholder="https://facebook.com/pagina">
                                    <?php $__errorArgs = ['social_facebook'];
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
                                    <label for="social_instagram" class="form-label">
                                        <i class="ph-duotone ph-instagram-logo me-1"></i>
                                        Instagram
                                    </label>
                                    <input type="url"
                                           class="form-control <?php $__errorArgs = ['social_instagram'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="social_instagram"
                                           name="social_instagram"
                                           value="<?php echo e(old('social_instagram', $group->social_instagram)); ?>"
                                           placeholder="https://instagram.com/profilo">
                                    <?php $__errorArgs = ['social_instagram'];
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
                                    <label for="social_youtube" class="form-label">
                                        <i class="ph-duotone ph-youtube-logo me-1"></i>
                                        YouTube
                                    </label>
                                    <input type="url"
                                           class="form-control <?php $__errorArgs = ['social_youtube'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="social_youtube"
                                           name="social_youtube"
                                           value="<?php echo e(old('social_youtube', $group->social_youtube)); ?>"
                                           placeholder="https://youtube.com/c/canale">
                                    <?php $__errorArgs = ['social_youtube'];
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
                                    <label for="social_twitter" class="form-label">
                                        <i class="ph-duotone ph-twitter-logo me-1"></i>
                                        Twitter
                                    </label>
                                    <input type="url"
                                           class="form-control <?php $__errorArgs = ['social_twitter'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="social_twitter"
                                           name="social_twitter"
                                           value="<?php echo e(old('social_twitter', $group->social_twitter)); ?>"
                                           placeholder="https://twitter.com/profilo">
                                    <?php $__errorArgs = ['social_twitter'];
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
                                    <label for="social_tiktok" class="form-label">
                                        <i class="ph-duotone ph-tiktok-logo me-1"></i>
                                        TikTok
                                    </label>
                                    <input type="url"
                                           class="form-control <?php $__errorArgs = ['social_tiktok'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="social_tiktok"
                                           name="social_tiktok"
                                           value="<?php echo e(old('social_tiktok', $group->social_tiktok)); ?>"
                                           placeholder="https://tiktok.com/@profilo">
                                    <?php $__errorArgs = ['social_tiktok'];
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
                                    <label for="social_linkedin" class="form-label">
                                        <i class="ph-duotone ph-linkedin-logo me-1"></i>
                                        LinkedIn
                                    </label>
                                    <input type="url"
                                           class="form-control <?php $__errorArgs = ['social_linkedin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="social_linkedin"
                                           name="social_linkedin"
                                           value="<?php echo e(old('social_linkedin', $group->social_linkedin)); ?>"
                                           placeholder="https://linkedin.com/company/azienda">
                                    <?php $__errorArgs = ['social_linkedin'];
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
                            
                            <div class="form-text">
                                <i class="ph-duotone ph-info me-1"></i>
                                I social links sono opzionali e permettono ai membri di seguire il gruppo sui social media.
                            </div>
                        </div>

                        <!-- Pulsanti -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="ph-duotone ph-check me-2"></i>
                                <?php echo e(__('groups.edit')); ?>

                            </button>
                            <a href="<?php echo e(route('groups.show', $group)); ?>" class="btn btn-light">
                                <i class="ph-duotone ph-arrow-left me-2"></i>
                                <?php echo e(__('common.cancel')); ?>

                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Azioni pericolose -->
            <?php if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin')): ?>
            <div class="card mt-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">
                        <i class="ph-duotone ph-warning me-2"></i>
                        <?php echo e(__('common.danger_zone')); ?>

                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-danger mb-1"><?php echo e(__('groups.delete')); ?></h6>
                            <p class="text-muted mb-0">
                                <?php echo e(__('groups.delete_warning')); ?>

                            </p>
                        </div>
                        <button type="button"
                                class="btn btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteGroupModal">
                            <i class="ph-duotone ph-trash me-2"></i>
                            <?php echo e(__('groups.delete')); ?>

                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal di conferma eliminazione -->
<?php if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin')): ?>
<div class="modal fade" id="deleteGroupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="ph-duotone ph-warning me-2"></i>
                    <?php echo e(__('groups.confirm_delete')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(__('groups.delete_confirmation_text')); ?></p>
                <ul class="text-muted">
                    <li><?php echo e(__('groups.delete_confirmation_members')); ?></li>
                    <li><?php echo e(__('groups.delete_confirmation_events')); ?></li>
                    <li><?php echo e(__('groups.delete_confirmation_invitations')); ?></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <?php echo e(__('common.cancel')); ?>

                </button>
                <form action="<?php echo e(route('groups.destroy', $group)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="ph-duotone ph-trash me-2"></i>
                        <?php echo e(__('groups.delete')); ?>

                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Funzioni per l'anteprima dell'immagine
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const file = input.files[0];

    if (file) {
        // Verifica che sia un'immagine
        if (!file.type.startsWith('image/')) {
            Swal.fire('Errore', 'Seleziona un file immagine valido', 'error');
            input.value = '';
            return;
        }

        // Verifica la dimensione del file (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire('Errore', 'L\'immagine deve essere inferiore a 2MB', 'error');
            input.value = '';
            return;
        }

        // Crea l'URL per l'anteprima
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}

function removeImage() {
    const input = document.getElementById('image');
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    // Pulisci l'input file
    input.value = '';

    // Nascondi l'anteprima
    preview.style.display = 'none';
    previewImg.src = '';

    // Mostra conferma
            Swal.fire({
            icon: 'success',
            title: '<?php echo e(__("groups.image_removed")); ?>',
            text: '<?php echo e(__("groups.image_removed_message")); ?>',
            timer: 1500,
            showConfirmButton: false
        });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/groups/edit.blade.php ENDPATH**/ ?>