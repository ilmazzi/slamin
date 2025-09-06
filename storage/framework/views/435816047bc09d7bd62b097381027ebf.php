<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slam In - A Home for poetry</title>

    <!-- Solo Bootstrap CSS essenziale -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/flag-icons-master/flag-icon.css')); ?>">

    <style>
        /* CSS con colori del template */
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: linear-gradient(135deg, rgb(15, 98, 106) 0%, rgb(12, 78, 85) 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .left-section {
            background: linear-gradient(135deg, rgb(15, 98, 106) 0%, rgb(12, 78, 85) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 3rem 2rem;
        }

        .brand-container {
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
        }

        .right-section {
            background: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 1.5rem;
        }

        .form-container {
            width: 100%;
            max-width: 100%;
        }

        /* Custom Language Dropdown */
        .custom-language-dropdown {
            position: relative;
        }

        .custom-language-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            background: white;
            border: 1px solid #ced4da;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 48px;
        }

        .custom-language-dropdown .dropdown-toggle:hover {
            border-color: #0f626a;
            box-shadow: 0 0 0 0.2rem rgba(15, 98, 106, 0.25);
        }

        .custom-language-dropdown .dropdown-toggle:focus {
            outline: none;
            border-color: #0f626a;
            box-shadow: 0 0 0 0.2rem rgba(15, 98, 106, 0.25);
        }

        .custom-language-dropdown .dropdown-menu {
            width: 100%;
            border: 1px solid #ced4da;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 4px;
        }

        .custom-language-dropdown .dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .custom-language-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #0f626a;
        }

        .custom-language-dropdown .flag-icon {
            width: 20px;
            height: 15px;
            background-size: cover;
            display: inline-block;
        }


        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .right-section {
                padding: 2rem 1rem;
                justify-content: center;
            }

            .form-container {
                max-width: 600px;
                margin: 0 auto;
            }
        }

        @media (min-width: 992px) {
            .right-section {
                padding: 2rem 3rem;
            }

            .form-container {
                max-width: 100%;
            }
        }

        .logo {
            max-width: 180px;
            margin-bottom: 2rem;
        }

        .feature-icon {
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .feature-icon:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        .btn-primary {
            background: rgb(15, 98, 106);
            border: 1px solid rgb(15, 98, 106);
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: rgb(12, 78, 85);
            border-color: rgb(12, 78, 85);
            transform: translateY(-1px);
        }

        .role-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .role-card:hover {
            border-color: rgb(15, 98, 106);
            background-color: rgba(15, 98, 106, 0.05);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(15, 98, 106, 0.1);
        }

        .role-card input:checked + label {
            color: rgb(15, 98, 106);
            font-weight: 600;
        }

        .role-card:has(input:checked) {
            border-color: rgb(15, 98, 106);
            background-color: rgba(15, 98, 106, 0.08);
            box-shadow: 0 2px 8px rgba(15, 98, 106, 0.15);
        }

        .form-control:focus {
            border-color: rgb(15, 98, 106);
            box-shadow: 0 0 0 0.2rem rgba(15, 98, 106, 0.25);
        }

        .form-label {
            font-weight: 500;
            color: #495057;
        }

        .form-label strong {
            color: rgb(15, 98, 106);
        }

        .btn-outline-secondary:hover,
        .btn-outline-info:hover {
            color: white;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <div class="row g-0" style="min-height: 100vh;">

            <!-- Colonna sinistra - Brand -->
            <div class="col-lg-7 d-none d-lg-block left-section">
                <div class="brand-container">
                    <img src="<?php echo e(asset('assets/images/logo.png')); ?>"
                         alt="<?php echo e(__('register.home_for_poetry')); ?>"
                         class="img-fluid logo">

                    <h1 class="mb-4">🎭 Slam In</h1>
                    <p class="lead mb-5"><?php echo e(__('register.home_for_poetry')); ?></p>

                    <div class="row text-center justify-content-center">
                        <div class="col-3">
                            <div class="feature-icon">
                                <h3>🎤</h3>
                                <small><?php echo e(__('register.poets')); ?></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="feature-icon">
                                <h3>🎪</h3>
                                <small><?php echo e(__('register.events')); ?></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="feature-icon">
                                <h3>🏛️</h3>
                                <small><?php echo e(__('register.venues')); ?></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="feature-icon">
                                <h3>👥</h3>
                                <small><?php echo e(__('register.community')); ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="small opacity-75">
                            <?php echo e(__('register.platform_description')); ?>

                        </p>
                    </div>
                </div>
            </div>

            <!-- Colonna destra - Form -->
            <div class="col-lg-5 col-12 right-section">
                <div class="form-container">

                    <div class="text-center mb-4">
                        <h2>🚀 <?php echo e(__('register.register')); ?></h2>
                        <p class="text-muted"><?php echo e(__('register.create_account')); ?></p>
                        <p class="small text-muted"><?php echo e(__('register.complete_profile')); ?></p>
                    </div>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('register.process')); ?>">
                        <?php echo csrf_field(); ?>

                        <!-- Selezione Lingua - Prima cosa da scegliere -->
                        <div class="mb-4">
                            <label class="form-label">
                                <strong>🌍 <?php echo e(__('register.preferred_language')); ?></strong>
                            </label>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-info-circle"></i> <?php echo e(__('register.language_tip')); ?>

                            </p>
                            <!-- Dropdown personalizzato con bandiere -->
                            <div class="custom-language-dropdown">
                                <div class="dropdown-toggle" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="flag-icon flag-icon-<?php echo e(\App\Helpers\LanguageHelper::getLanguageFlagCode(app()->getLocale())); ?> me-2"></i>
                                    <span id="selectedLanguageName"><?php echo e(\App\Helpers\LanguageHelper::getLanguageName(app()->getLocale())); ?></span>
                                    <i class="bi bi-chevron-down ms-auto"></i>
                                </div>
                                <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                                    <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <a class="dropdown-item language-option" href="#" data-code="<?php echo e($code); ?>">
                                                <i class="flag-icon flag-icon-<?php echo e(\App\Helpers\LanguageHelper::getLanguageFlagCode($code)); ?> me-2"></i>
                                                <?php echo e($name); ?>

                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                                <input type="hidden" name="language" id="languageInput" value="<?php echo e(old('language', session('locale', 'it'))); ?>">
                            </div>
                        </div>

                        <!-- Dati Base - Layout migliorato -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?php echo e(__('register.full_name')); ?> *</label>
                                <input type="text" name="name" class="form-control"
                                       value="<?php echo e(old('name')); ?>" required
                                       placeholder="<?php echo e(__('register.full_name_placeholder')); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?php echo e(__('register.nickname')); ?></label>
                                <input type="text" name="nickname" class="form-control"
                                       value="<?php echo e(old('nickname')); ?>"
                                       placeholder="<?php echo e(__('register.nickname_placeholder')); ?>">
                                <small class="text-muted"><?php echo e(__('register.optional')); ?></small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label"><?php echo e(__('register.email')); ?> *</label>
                                <input type="email" name="email" class="form-control"
                                       value="<?php echo e(old('email')); ?>" required
                                       placeholder="<?php echo e(__('register.email_placeholder')); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                            <?php echo e(__('register.password')); ?> *
                                    <small class="text-muted"><?php echo e(__('register.password_min_characters')); ?></small>
                                </label>
                                <input type="password" name="password" class="form-control"
                                       required placeholder="••••••••">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><?php echo e(__('register.confirm_password')); ?> *</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                       required placeholder="••••••••">
                            </div>
                        </div>

                        <!-- Selezione Multi-<?php echo e(__('invitations.role')); ?> -->
                        <div class="mb-4">
                            <label class="form-label">
                                <strong>🎭 <?php echo e(__('register.choose_role')); ?></strong>
                            </label>
                            <p class="text-muted small mb-3">
                                <?php echo e(__('register.choose_role_description')); ?>

                                <br><strong>💡 <?php echo e(__('register.four_main_roles')); ?>:</strong> <?php echo e(__('register.poet')); ?>, <?php echo e(__('events.organizer')); ?>, <?php echo e(__('register.venue_owner')); ?>, <?php echo e(__('register.audience')); ?>

                            </p>



                            <div class="row">
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="role-card">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       name="roles[]"
                                                       value="<?php echo e($role['name']); ?>"
                                                       id="role_<?php echo e($role['name']); ?>"
                                                       <?php echo e(in_array($role['name'], old('roles', [])) ? 'checked' : ''); ?>>

                                                <label class="form-check-label w-100" for="role_<?php echo e($role['name']); ?>">
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2 fs-5"><?php echo e($role['icon']); ?></span>
                                                        <div class="flex-grow-1">
                                                            <strong><?php echo e($role['display_name']); ?></strong><br>
                                                            <small class="text-muted"><?php echo e($role['description']); ?></small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <!-- Pulsante Registrazione -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                🚀 <?php echo e(__('register.join_slam_in')); ?>

                            </button>
                        </div>
                    </form>

                    <!-- Link Alternativi -->
                    <div class="text-center border-top pt-3 mt-4">
                        <p class="text-muted mb-3"><?php echo e(__('register.already_have_account')); ?></p>
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-box-arrow-in-right"></i> <?php echo e(__('register.login')); ?>

                            </a>

                        </div>
                    </div>

                    <!-- Info Footer -->
                    <div class="mt-3">
                        <div class="alert alert-info">
                            <h6>🌟 <?php echo e(__('register.why_join_slam_in')); ?></h6>
                            <ul class="list-unstyled mb-0 small">
                                <li>• <strong><?php echo e(__('register.fast_registration')); ?>:</strong> <?php echo e(__('register.only_essential_data')); ?></li>
                                <li>• <strong><?php echo e(__('register.flexible_roles')); ?>:</strong> <?php echo e(__('register.poet')); ?>, <?php echo e(__('events.organizer')); ?>, <?php echo e(__('register.venue_owner')); ?>, <?php echo e(__('register.audience')); ?></li>
                                <li>• <strong><?php echo e(__('register.complete_ecosystem')); ?>:</strong> <?php echo e(__('register.artists')); ?>, <?php echo e(__('events.organizers')); ?>, <?php echo e(__('register.venues')); ?> <?php echo e(__('register.and')); ?> <?php echo e(__('register.audience')); ?></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Solo JavaScript essenziale -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Gestione dropdown lingua personalizzato
        document.addEventListener('DOMContentLoaded', function() {
            const languageOptions = document.querySelectorAll('.language-option');
            const selectedLanguageName = document.getElementById('selectedLanguageName');
            const languageInput = document.getElementById('languageInput');
            const currentLanguage = '<?php echo e(app()->getLocale()); ?>';

            languageOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();

                    const selectedCode = this.getAttribute('data-code');
                    const selectedName = this.textContent.trim();

                    // Aggiorna il display
                    selectedLanguageName.textContent = selectedName;
                    languageInput.value = selectedCode;

                    // Aggiorna la bandiera
                    const flagIcon = this.querySelector('.flag-icon');
                    const newFlagClass = flagIcon.className;
                    const currentFlagIcon = document.querySelector('#languageDropdown .flag-icon');
                    currentFlagIcon.className = newFlagClass;

                    // Chiudi il dropdown
                    const dropdown = bootstrap.Dropdown.getInstance(document.getElementById('languageDropdown'));
                    if (dropdown) {
                        dropdown.hide();
                    }

                    // Se la lingua è diversa, ricarica la pagina
                    if (selectedCode !== currentLanguage) {
                        const submitButton = document.querySelector('button[type="submit"]');
                        const originalText = submitButton.innerHTML;
                        submitButton.innerHTML = '🔄 <?php echo e(__("register.changing_language")); ?>...';
                        submitButton.disabled = true;

                        const currentUrl = new URL(window.location);
                        currentUrl.searchParams.set('lang', selectedCode);
                        window.location.href = currentUrl.toString();
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/auth/signup.blade.php ENDPATH**/ ?>