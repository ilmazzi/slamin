<?php $__env->startSection('title', __('languages.add_language')); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/vendor/flag-icons-master/flag-icon.css')); ?>">
<style>
.custom-language-dropdown {
    position: relative;
}

.custom-language-dropdown .dropdown-toggle {
    display: flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    background-color: #fff;
    cursor: pointer;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.custom-language-dropdown .dropdown-toggle:hover {
    border-color: #86b7fe;
}

.custom-language-dropdown .dropdown-toggle:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.custom-language-dropdown .flag-icon {
    width: 20px;
    height: 15px;
    display: inline-block;
}

.custom-language-dropdown .dropdown-menu {
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
}

.custom-language-dropdown .language-option {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
}

.custom-language-dropdown .language-option:hover {
    background-color: #f8f9fa;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-plus me-2 text-primary"></i>
                        <?php echo e(__('languages.add_language')); ?>

                    </h4>
                    <a href="<?php echo e(route('profile.languages.index')); ?>" class="btn btn-light">
                        <i class="ph-duotone ph-arrow-left me-2"></i>
                        <?php echo e(__('common.back')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('profile.languages.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <!-- Lingua -->
                        <div class="mb-3">
                            <label class="form-label"><?php echo e(__('languages.language')); ?> <span class="text-danger">*</span></label>
                            <div class="custom-language-dropdown">
                                <div class="dropdown-toggle <?php $__errorArgs = ['language_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="flag-icon flag-icon-ita me-2"></i>
                                    <span id="selectedLanguageName"><?php echo e(__('languages.select_language')); ?></span>
                                    <i class="bi bi-chevron-down ms-auto"></i>
                                </div>
                    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                        <?php $__currentLoopData = $worldLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a class="dropdown-item language-option" href="#" data-code="<?php echo e($code); ?>" data-name="<?php echo e($name); ?>">
                                    <i class="flag-icon flag-icon-<?php echo e(\App\Providers\LanguageServiceProvider::getFlagCode($code)); ?> me-2"></i>
                                    <?php echo e($name); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                                <input type="hidden" name="language_name" id="language_name" value="<?php echo e(old('language_name')); ?>">
                                <input type="hidden" name="language_code" id="language_code" value="<?php echo e(old('language_code')); ?>">
                            </div>
                            <?php $__errorArgs = ['language_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php $__errorArgs = ['language_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text"><?php echo e(__('languages.select_language_help')); ?></div>
                        </div>

                        <!-- Tipo di Competenza -->
                        <div class="mb-3">
                            <label class="form-label"><?php echo e(__('languages.competence_type')); ?> <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="type"
                                               id="type_native"
                                               value="native"
                                               <?php echo e(old('type') === 'native' ? 'checked' : ''); ?>

                                               onchange="toggleLevelField()">
                                        <label class="form-check-label" for="type_native">
                                            <i class="ph-duotone ph-house me-1 text-success"></i>
                                            <?php echo e(__('languages.native')); ?>

                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="type"
                                               id="type_spoken"
                                               value="spoken"
                                               <?php echo e(old('type') === 'spoken' ? 'checked' : ''); ?>

                                               onchange="toggleLevelField()">
                                        <label class="form-check-label" for="type_spoken">
                                            <i class="ph-duotone ph-microphone me-1 text-info"></i>
                                            <?php echo e(__('languages.spoken')); ?>

                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="type"
                                               id="type_written"
                                               value="written"
                                               <?php echo e(old('type') === 'written' ? 'checked' : ''); ?>

                                               onchange="toggleLevelField()">
                                        <label class="form-check-label" for="type_written">
                                            <i class="ph-duotone ph-pencil me-1 text-warning"></i>
                                            <?php echo e(__('languages.written')); ?>

                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Livello (solo per parlato/scritto) -->
                        <div class="mb-3" id="level_field" style="display: none;">
                            <label class="form-label"><?php echo e(__('languages.level')); ?> <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="level"
                                               id="level_excellent"
                                               value="excellent"
                                               <?php echo e(old('level') === 'excellent' ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="level_excellent">
                                            <i class="ph-duotone ph-star me-1 text-success"></i>
                                            <?php echo e(__('languages.excellent')); ?>

                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="level"
                                               id="level_good"
                                               value="good"
                                               <?php echo e(old('level') === 'good' ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="level_good">
                                            <i class="ph-duotone ph-star-half me-1 text-warning"></i>
                                            <?php echo e(__('languages.good')); ?>

                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="level"
                                               id="level_poor"
                                               value="poor"
                                               <?php echo e(old('level') === 'poor' ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="level_poor">
                                            <i class="ph-duotone ph-star me-1 text-danger"></i>
                                            <?php echo e(__('languages.poor')); ?>

                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php $__errorArgs = ['level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Pulsanti -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph-duotone ph-check me-2"></i>
                                <?php echo e(__('languages.add_language')); ?>

                            </button>
                            <a href="<?php echo e(route('profile.languages.index')); ?>" class="btn btn-light">
                                <?php echo e(__('common.cancel')); ?>

                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="col-12 col-md-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-info me-2 text-info"></i>
                        <?php echo e(__('languages.help_title')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-success">
                            <i class="ph-duotone ph-house me-1"></i>
                            <?php echo e(__('languages.native')); ?>

                        </h6>
                        <p class="text-muted small mb-0"><?php echo e(__('languages.native_description')); ?></p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-info">
                            <i class="ph-duotone ph-microphone me-1"></i>
                            <?php echo e(__('languages.spoken')); ?>

                        </h6>
                        <p class="text-muted small mb-0"><?php echo e(__('languages.spoken_description')); ?></p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-warning">
                            <i class="ph-duotone ph-pencil me-1"></i>
                            <?php echo e(__('languages.written')); ?>

                        </h6>
                        <p class="text-muted small mb-0"><?php echo e(__('languages.written_description')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Gestione selezione lingua
$(document).ready(function() {
    $('.language-option').on('click', function(e) {
        e.preventDefault();

        const code = $(this).data('code');
        const name = $(this).data('name');
        const flagIcon = $(this).find('.flag-icon').attr('class');

        // Aggiorna i campi nascosti
        $('#language_code').val(code);
        $('#language_name').val(name);

        // Aggiorna il display del dropdown
        $('#selectedLanguageName').text(name);
        $('#languageDropdown .flag-icon').attr('class', flagIcon);

        // Chiudi il dropdown
        $('.dropdown-menu').removeClass('show');
    });
});

// Toggle del campo livello
function toggleLevelField() {
    const nativeRadio = document.getElementById('type_native');
    const levelField = document.getElementById('level_field');

    if (nativeRadio.checked) {
        levelField.style.display = 'none';
        // Deseleziona tutti i livelli
        document.querySelectorAll('input[name="level"]').forEach(radio => {
            radio.checked = false;
        });
    } else {
        levelField.style.display = 'block';
    }
}

// Inizializza lo stato del campo livello
document.addEventListener('DOMContentLoaded', function() {
    toggleLevelField();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/profile/languages/create.blade.php ENDPATH**/ ?>