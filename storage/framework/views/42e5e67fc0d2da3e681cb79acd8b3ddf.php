

<?php $__env->startSection('title', 'Configurazione PeerTube - Admin'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Breadcrumbs -->
    <div class="row">
        <div class="col-12">
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="<?php echo e(route('dashboard')); ?>" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-gauge f-s-16"></i> Admin
                        </span>
                    </a>
                </li>
                <li class="active">
                    <span class="f-s-14 f-w-500">
                        <i class="ph-duotone ph-video-camera f-s-16"></i> Configurazione PeerTube
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card card-light-<?php echo e($isConfigured ? 'success' : 'danger'); ?>">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-<?php echo e($isConfigured ? 'check-circle' : 'x-circle'); ?> text-<?php echo e($isConfigured ? 'success' : 'danger'); ?> f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Configurazione</h6>
                            <h4 class="mb-0"><?php echo e($isConfigured ? 'Completa' : 'Incompleta'); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-light-<?php echo e(isset($connectionTest) && $connectionTest['success'] ? 'success' : 'danger'); ?>">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-<?php echo e(isset($connectionTest) && $connectionTest['success'] ? 'check-circle' : 'x-circle'); ?> text-<?php echo e(isset($connectionTest) && $connectionTest['success'] ? 'success' : 'danger'); ?> f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Connessione</h6>
                            <h4 class="mb-0"><?php echo e(isset($connectionTest) && $connectionTest['success'] ? 'Attiva' : 'Fallita'); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-light-<?php echo e(isset($connectionTest) && $connectionTest['success'] ? 'success' : 'warning'); ?>">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-<?php echo e(isset($connectionTest) && $connectionTest['success'] ? 'shield-check' : 'shield-warning'); ?> text-<?php echo e(isset($connectionTest) && $connectionTest['success'] ? 'success' : 'warning'); ?> f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Autenticazione</h6>
                            <h4 class="mb-0"><?php echo e(isset($connectionTest) && $connectionTest['success'] ? 'Riuscita' : 'Fallita'); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-light-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-users text-info f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Utenti PeerTube</h6>
                            <h4 class="mb-0"><?php echo e(\App\Models\User::whereNotNull('peertube_user_id')->count()); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-light-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-video-camera text-warning f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Canali Creati</h6>
                            <h4 class="mb-0"><?php echo e(\App\Models\User::whereNotNull('peertube_channel_id')->count()); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-light-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-calendar text-primary f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1"><?php echo e(__('notifications.last_7_days')); ?></h6>
                            <h4 class="mb-0"><?php echo e(\App\Models\User::whereNotNull('peertube_user_id')->where('peertube_created_at', '>=', now()->subDays(7))->count()); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-light-secondary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-gear text-secondary f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Server URL</h6>
                            <h4 class="mb-0"><?php echo e($settings['peertube_url'] ?? 'Non configurato'); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration Form -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-gear f-s-16 me-2"></i>
                        Configurazione Server PeerTube
                    </h4>
                    <p class="mb-0 opacity-75">Configura le credenziali per la connessione al server PeerTube</p>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.peertube.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="peertube_url" class="form-label">
                                        <i class="ph ph-globe me-1"></i>URL Server PeerTube *
                                    </label>
                                    <input type="url"
                                           class="form-control <?php $__errorArgs = ['peertube_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="peertube_url"
                                           name="peertube_url"
                                           value="<?php echo e($settings['peertube_url'] ?? 'https://video.slamin.it'); ?>"
                                           placeholder="https://video.slamin.it"
                                           required>
                                    <?php $__errorArgs = ['peertube_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">URL completo del server PeerTube (es. https://video.slamin.it)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="peertube_admin_username" class="form-label">
                                        <i class="ph ph-user me-1"></i>Username Admin *
                                    </label>
                                    <input type="text"
                                           class="form-control <?php $__errorArgs = ['peertube_admin_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="peertube_admin_username"
                                           name="peertube_admin_username"
                                           value="<?php echo e($settings['peertube_admin_username'] ?? ''); ?>"
                                           placeholder="admin"
                                           required>
                                    <?php $__errorArgs = ['peertube_admin_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">Username dell'account admin PeerTube</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="peertube_admin_password" class="form-label">
                                        <i class="ph ph-lock me-1"></i>Password Admin *
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control <?php $__errorArgs = ['peertube_admin_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               id="peertube_admin_password"
                                               name="peertube_admin_password"
                                               value="<?php echo e($settings['peertube_admin_password'] ?? ''); ?>"
                                               placeholder="••••••••"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                            <i class="ph ph-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                    <?php $__errorArgs = ['peertube_admin_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">Password dell'account admin PeerTube</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="button" class="btn btn-outline-info" onclick="testConnection()" id="testConnectionBtn">
                                            <i class="ph ph-wifi me-2"></i>Testa Connessione
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">Testa la connessione con le credenziali inserite</small>
                                </div>
                            </div>
                        </div>

                        <!-- Connection Test Result -->
                        <?php if(isset($connectionTest)): ?>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="alert alert-<?php echo e($connectionTest['success'] ? 'success' : 'danger'); ?> d-flex align-items-start">
                                    <i class="ph ph-<?php echo e($connectionTest['success'] ? 'check-circle' : 'x-circle'); ?> me-2 mt-1"></i>
                                    <div class="flex-grow-1">
                                        <strong><?php echo e($connectionTest['success'] ? 'Test Connessione Riuscito!' : 'Test Connessione Fallito'); ?></strong>
                                        <br>
                                        <?php echo e($connectionTest['message']); ?>

                                        <?php if($connectionTest['success'] && isset($connectionTest['token'])): ?>
                                            <br><small class="text-muted">Token di accesso ottenuto: <?php echo e($connectionTest['token']); ?></small>
                                        <?php endif; ?>
                                        <?php if(!$connectionTest['success']): ?>
                                            <br><small class="text-muted mt-2">
                                                <strong>Suggerimenti:</strong>
                                                <ul class="mb-0 mt-1">
                                                    <li>Verifica che l'URL del server sia corretto</li>
                                                    <li>Controlla che le credenziali admin siano valide</li>
                                                    <li>Assicurati che il server PeerTube sia raggiungibile</li>
                                                    <li>Verifica che l'account admin abbia i permessi necessari</li>
                                                </ul>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="<?php echo e(route('admin.peertube.statistics')); ?>" class="btn btn-outline-secondary me-2">
                                            <i class="ph ph-chart-line me-2"></i>Statistiche
                                        </a>
                                        <a href="<?php echo e(route('admin.peertube.manage-users')); ?>" class="btn btn-outline-info">
                                            <i class="ph ph-users me-2"></i>Gestione Utenti
                                        </a>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ph ph-floppy-disk me-2"></i>Salva Configurazione
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function togglePassword() {
    const passwordInput = document.getElementById('peertube_admin_password');
    const passwordIcon = document.getElementById('password-icon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.className = 'ph ph-eye-slash';
    } else {
        passwordInput.type = 'password';
        passwordIcon.className = 'ph ph-eye';
    }
}

function testConnection() {
    const button = document.getElementById('testConnectionBtn');
    const originalText = button.innerHTML;

    button.innerHTML = '<i class="ph ph-spinner ph-spin me-2"></i>Testando...';
    button.disabled = true;

    // Mostra un messaggio di caricamento
    const loadingAlert = document.createElement('div');
    loadingAlert.className = 'alert alert-info d-flex align-items-center';
    loadingAlert.innerHTML = `
        <i class="ph ph-spinner ph-spin me-2"></i>
        <div>Testando la connessione a PeerTube...</div>
    `;

    const form = document.querySelector('form');
    form.insertBefore(loadingAlert, form.firstChild);

    fetch('<?php echo e(route("admin.peertube.test-connection")); ?>')
        .then(response => response.json())
        .then(data => {
            // Rimuovi l'alert di caricamento
            loadingAlert.remove();

            // Mostra il risultato
            const resultAlert = document.createElement('div');
            resultAlert.className = `alert alert-${data.success ? 'success' : 'danger'} d-flex align-items-start`;
            resultAlert.innerHTML = `
                <i class="ph ph-${data.success ? 'check-circle' : 'x-circle'} me-2 mt-1"></i>
                <div class="flex-grow-1">
                    <strong>${data.success ? 'Test Connessione Riuscito!' : 'Test Connessione Fallito'}</strong>
                    <br>
                    ${data.message}
                    ${data.success && data.token ? `<br><small class="text-muted">Token di accesso ottenuto: ${data.token}</small>` : ''}
                    ${!data.success ? `
                        <br><small class="text-muted mt-2">
                            <strong>Suggerimenti:</strong>
                            <ul class="mb-0 mt-1">
                                <li>Verifica che l'URL del server sia corretto</li>
                                <li>Controlla che le credenziali admin siano valide</li>
                                <li>Assicurati che il server PeerTube sia raggiungibile</li>
                                <li>Verifica che l'account admin abbia i permessi necessari</li>
                            </ul>
                        </small>
                    ` : ''}
                </div>
            `;

            form.insertBefore(resultAlert, form.firstChild);

            // Ricarica la pagina dopo 3 secondi per aggiornare le card di stato
            setTimeout(() => {
                location.reload();
            }, 3000);
        })
        .catch(error => {
            console.error('Errore test connessione:', error);
            loadingAlert.remove();

            const errorAlert = document.createElement('div');
            errorAlert.className = 'alert alert-danger d-flex align-items-center';
            errorAlert.innerHTML = `
                <i class="ph ph-x-circle me-2"></i>
                <div>Errore durante il test di connessione. Riprova.</div>
            `;

            form.insertBefore(errorAlert, form.firstChild);

            button.innerHTML = originalText;
            button.disabled = false;
        });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/peertube/index.blade.php ENDPATH**/ ?>