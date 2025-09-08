<?php $__env->startSection('title', 'Gestione Utenti PeerTube - Admin'); ?>

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
                <li class="">
                    <a href="<?php echo e(route('admin.peertube.index')); ?>" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-video-camera f-s-16"></i> PeerTube
                        </span>
                    </a>
                </li>
                <li class="active">
                    <span class="f-s-14 f-w-500">
                        <i class="ph-duotone ph-users f-s-16"></i> Gestione Utenti
                    </span>
                </li>
            </ul>
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
                            <h6 class="mb-1">Totale Utenti</h6>
                            <h4 class="mb-0"><?php echo e($allUsers->count()); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-light-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-check-circle text-success f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Con Account PeerTube</h6>
                            <h4 class="mb-0"><?php echo e($peertubeUsers->count()); ?></h4>
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
                            <i class="ph ph-x-circle text-warning f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Senza Account PeerTube</h6>
                            <h4 class="mb-0"><?php echo e($allUsers->count() - $peertubeUsers->count()); ?></h4>
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
                            <i class="ph ph-chart-line text-primary f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Copertura</h6>
                            <h4 class="mb-0"><?php echo e($allUsers->count() > 0 ? round(($peertubeUsers->count() / $allUsers->count()) * 100, 1) : 0); ?>%</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Selection and Management -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-user-gear f-s-16 me-2"></i>
                        Gestione Utente PeerTube
                    </h4>
                    <p class="mb-0 opacity-75">Seleziona un utente per visualizzare e gestire i suoi dati PeerTube</p>
                </div>
                <div class="card-body">
                    <!-- User Selection -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="userSelect" class="form-label">
                                <i class="ph ph-user me-1"></i>Seleziona Utente
                            </label>
                            <select class="form-select" id="userSelect" onchange="loadUserData()">
                                <option value="">-- Seleziona un utente --</option>
                                <?php $__currentLoopData = $allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>"
                                            data-has-peertube="<?php echo e($user->peertube_user_id ? 'true' : 'false'); ?>"
                                            data-roles="<?php echo e($user->roles->pluck('name')->implode(', ')); ?>">
                                        <?php echo e($user->name); ?> (<?php echo e($user->nickname); ?>) - <?php echo e($user->email); ?>

                                        <?php if($user->peertube_user_id): ?>
                                            <span class="text-success">✓ PeerTube</span>
                                        <?php else: ?>
                                            <span class="text-warning">⚠ No PeerTube</span>
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-primary" onclick="loadUserData()" id="loadUserBtn">
                                    <i class="ph ph-magnifying-glass me-2"></i>Carica Dati
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- User Details Panel -->
                    <div id="userDetailsPanel" style="display: none;">
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-light-info">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="ph ph-user-circle me-2"></i>
                                            Dettagli Utente
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="userDetailsContent">
                                            <!-- Content will be loaded here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PeerTube Data Form -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card card-light-warning">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="ph ph-video-camera me-2"></i>
                                            Dati PeerTube
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="peertubeDataForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="peertube_user_id" class="form-label">PeerTube User ID</label>
                                                        <input type="number" class="form-control" id="peertube_user_id" readonly>
                                                        <small class="form-text text-muted">ID utente su PeerTube (solo lettura)</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="peertube_username" class="form-label">Username PeerTube</label>
                                                        <input type="text" class="form-control" id="peertube_username" name="peertube_username">
                                                        <small class="form-text text-muted">Username dell'utente su PeerTube</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="peertube_channel_id" class="form-label">Channel ID</label>
                                                        <input type="number" class="form-control" id="peertube_channel_id" name="peertube_channel_id">
                                                        <small class="form-text text-muted">ID del canale PeerTube</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="peertube_account_id" class="form-label">Account ID</label>
                                                        <input type="number" class="form-control" id="peertube_account_id" name="peertube_account_id">
                                                        <small class="form-text text-muted">ID dell'account PeerTube</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <button type="button" class="btn btn-primary" onclick="updateUserData()">
                                                            <i class="ph ph-floppy-disk me-2"></i>Aggiorna Dati
                                                        </button>
                                                        <button type="button" class="btn btn-info" onclick="verifyUserExists()">
                                                            <i class="ph ph-magnifying-glass me-2"></i>Verifica Esistenza
                                                        </button>
                                                        <button type="button" class="btn btn-secondary" onclick="syncUserData()">
                                                            <i class="ph ph-arrows-clockwise me-2"></i>Sincronizza
                                                        </button>
                                                        <button type="button" class="btn btn-success" onclick="createUserAccount(false)">
                                                            <i class="ph ph-plus-circle me-2"></i>Crea Account
                                                        </button>
                                                        <button type="button" class="btn btn-warning" onclick="createUserAccount(true)">
                                                            <i class="ph ph-arrow-clockwise me-2"></i>Ricrea Account
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary" onclick="linkExistingAccount()">
                                                            <i class="ph ph-link me-2"></i>Collega Esistente
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger" onclick="deletePeerTubeUser()">
                                                            <i class="ph ph-trash me-2"></i>Elimina da PeerTube
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logs Panel -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-info text-white">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-bug f-s-16 me-2"></i>
                        Log Debug PeerTube
                    </h4>
                    <p class="mb-0 opacity-75">Log recenti per il debug</p>
                </div>
                <div class="card-body">
                    <div class="log-container" style="max-height: 600px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 8px; font-family: monospace; font-size: 12px;">
                        <?php if(count($recentLogs) > 0): ?>
                            <?php $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="log-entry mb-2 p-2 rounded" style="background: white; border-left: 4px solid #007bff;">
                                    <div class="text-muted small"><?php echo e($log); ?></div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="text-muted text-center">
                                <i class="ph ph-info-circle f-s-24 mb-2"></i>
                                <p>Nessun log PeerTube recente trovato</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="refreshLogs()">
                            <i class="ph ph-arrow-clockwise me-2"></i>Aggiorna Log
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let currentUserId = null;

function loadUserData() {
    const userId = document.getElementById('userSelect').value;
    if (!userId) {
        alert('Seleziona un utente');
        return;
    }

    currentUserId = userId;
    const button = document.getElementById('loadUserBtn');
    const originalText = button.innerHTML;

    button.innerHTML = '<i class="ph ph-spinner ph-spin me-2"></i>Caricamento...';
    button.disabled = true;

    fetch('<?php echo e(route("admin.peertube.show-user")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        displayUserData(data);
        button.innerHTML = originalText;
        button.disabled = false;
    })
    .catch(error => {
        console.error('Errore:', error);
        alert('Errore durante il caricamento dei dati utente');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function displayUserData(data) {
    const panel = document.getElementById('userDetailsPanel');
    const content = document.getElementById('userDetailsContent');

    // Mostra il pannello
    panel.style.display = 'block';

    // Popola i dettagli utente
    content.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <p><strong>Nome:</strong> ${data.user.name}</p>
                <p><strong>Nickname:</strong> ${data.user.nickname}</p>
                <p><strong>Email:</strong> ${data.user.email}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Ruoli:</strong> ${data.user.roles.map(role => role.name).join(', ')}</p>
                <p><strong>Account PeerTube:</strong>
                    <span class="badge bg-${data.has_peertube_account ? 'success' : 'warning'}">
                        ${data.has_peertube_account ? 'Presente' : 'Assente'}
                    </span>
                </p>
                ${data.peertube_created_at ? `<p><strong>Creato:</strong> ${data.peertube_created_at}</p>` : ''}
            </div>
        </div>
    `;

    // Popola il form PeerTube
    document.getElementById('peertube_user_id').value = data.user.peertube_user_id || '';
    document.getElementById('peertube_username').value = data.peertube_username || '';
    document.getElementById('peertube_channel_id').value = data.peertube_channel_id || '';
    document.getElementById('peertube_account_id').value = data.peertube_account_id || '';
}

function updateUserData() {
    if (!currentUserId) {
        alert('Seleziona prima un utente');
        return;
    }

    const formData = {
        user_id: currentUserId,
        peertube_username: document.getElementById('peertube_username').value,
        peertube_channel_id: document.getElementById('peertube_channel_id').value,
        peertube_account_id: document.getElementById('peertube_account_id').value
    };

    fetch('<?php echo e(route("admin.peertube.update-user-data")); ?>', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        showAlert('danger', 'Errore durante l\'aggiornamento dei dati');
    });
}

function createUserAccount(forceRecreate = false) {
    if (!currentUserId) {
        alert('Seleziona prima un utente');
        return;
    }

    const action = forceRecreate ? 'ricreare' : 'creare';
    if (!confirm(`Sei sicuro di voler ${action} l'account PeerTube per questo utente?`)) {
        return;
    }

    const formData = {
        user_id: currentUserId,
        force_recreate: forceRecreate
    };

    fetch('<?php echo e(route("admin.peertube.create-user-account")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            // Aggiorna i dati del form se disponibili
            if (data.user_data) {
                document.getElementById('peertube_user_id').value = data.user_data.peertube_user_id || '';
                document.getElementById('peertube_username').value = data.user_data.peertube_username || '';
                document.getElementById('peertube_channel_id').value = data.user_data.peertube_channel_id || '';
                document.getElementById('peertube_account_id').value = data.user_data.peertube_account_id || '';
            }
            // Ricarica i dati utente
            setTimeout(() => loadUserData(), 1000);
        } else {
            // Se c'è un conflitto di email, mostra le opzioni
            if (data.existing_user) {
                showEmailConflictDialog(data);
            } else {
                showAlert('danger', data.message);
            }
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        showAlert('danger', 'Errore durante la creazione dell\'account PeerTube');
    });
}

function linkExistingAccount() {
    if (!currentUserId) {
        alert('Seleziona prima un utente');
        return;
    }

    if (!confirm('Sei sicuro di voler collegare l\'account PeerTube esistente per questo utente?')) {
        return;
    }

    const formData = {
        user_id: currentUserId,
        link_existing: true
    };

    fetch('<?php echo e(route("admin.peertube.create-user-account")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            // Aggiorna i dati del form se disponibili
            if (data.user_data) {
                document.getElementById('peertube_user_id').value = data.user_data.peertube_user_id || '';
                document.getElementById('peertube_username').value = data.user_data.peertube_username || '';
                document.getElementById('peertube_channel_id').value = data.user_data.peertube_channel_id || '';
                document.getElementById('peertube_account_id').value = data.user_data.peertube_account_id || '';
            }
            // Ricarica i dati utente
            setTimeout(() => loadUserData(), 1000);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        showAlert('danger', 'Errore durante il collegamento dell\'account PeerTube');
    });
}

function showEmailConflictDialog(data) {
    const existingUser = data.existing_user;
    const suggestions = data.suggestions;

    const modalHtml = `
        <div class="modal fade" id="emailConflictModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="ph ph-warning-circle me-2"></i>
                            Conflitto Email PeerTube
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <strong>⚠️ Attenzione!</strong> Esiste già un utente PeerTube con questa email.
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Dettagli Utente PeerTube Esistente</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>ID:</strong> ${existingUser.peertube_user_id}</p>
                                <p><strong>Username:</strong> ${existingUser.username}</p>
                                <p><strong>Email:</strong> ${existingUser.email}</p>
                                ${existingUser.created_at ? `<p><strong>Creato:</strong> ${existingUser.created_at}</p>` : ''}
                            </div>
                        </div>

                        <div class="mt-3">
                            <h6>Opzioni disponibili:</h6>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary" onclick="linkExistingAccount()">
                                    <i class="ph ph-link me-2"></i>${suggestions.link_existing}
                                </button>
                                <button type="button" class="btn btn-warning" onclick="forceRecreateAccount()">
                                    <i class="ph ph-arrow-clockwise me-2"></i>${suggestions.force_recreate}
                                </button>
                                <button type="button" class="btn btn-danger" onclick="deleteExistingUser()">
                                    <i class="ph ph-trash me-2"></i>Elimina Utente Esistente
                                </button>
                                <button type="button" class="btn btn-info" onclick="showChangeEmailForm()">
                                    <i class="ph ph-envelope me-2"></i>${suggestions.change_email}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Rimuovi modal esistente se presente
    const existingModal = document.getElementById('emailConflictModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Aggiungi il nuovo modal
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Mostra il modal
    const modal = new bootstrap.Modal(document.getElementById('emailConflictModal'));
    modal.show();
}

function forceRecreateAccount() {
    // Chiudi il modal di conflitto
    const modal = bootstrap.Modal.getInstance(document.getElementById('emailConflictModal'));
    if (modal) {
        modal.hide();
    }

    // Conferma l'azione
    if (confirm('⚠️ ATTENZIONE: Questa operazione eliminerà l\'account PeerTube esistente e ne creerà uno nuovo. Sei sicuro?')) {
        createUserAccount(true);
    }
}

function deleteExistingUser() {
    // Chiudi il modal di conflitto
    const modal = bootstrap.Modal.getInstance(document.getElementById('emailConflictModal'));
    if (modal) {
        modal.hide();
    }

    // Conferma l'azione
    if (confirm('⚠️ ATTENZIONE: Questa operazione eliminerà PERMANENTEMENTE l\'utente PeerTube esistente. Sei sicuro?')) {
        deletePeerTubeUser();
    }
}

function deletePeerTubeUser() {
    if (!currentUserId) {
        alert('Seleziona prima un utente');
        return;
    }

    if (!confirm('⚠️ ATTENZIONE: Questa operazione eliminerà PERMANENTEMENTE l\'utente PeerTube. Sei sicuro?')) {
        return;
    }

    const formData = {
        user_id: currentUserId,
        delete_by_email: true
    };

    fetch('<?php echo e(route("admin.peertube.delete-user")); ?>', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            // Resetta i campi del form
            document.getElementById('peertube_user_id').value = '';
            document.getElementById('peertube_username').value = '';
            document.getElementById('peertube_channel_id').value = '';
            document.getElementById('peertube_account_id').value = '';
            // Ricarica i dati utente
            setTimeout(() => loadUserData(), 1000);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        showAlert('danger', 'Errore durante l\'eliminazione dell\'utente PeerTube');
    });
}

function showChangeEmailForm() {
    // Chiudi il modal di conflitto
    const modal = bootstrap.Modal.getInstance(document.getElementById('emailConflictModal'));
    if (modal) {
        modal.hide();
    }

    // Mostra form per cambio email
    const newEmail = prompt('Inserisci la nuova email per questo utente:');
    if (newEmail && newEmail.trim() !== '') {
        changeUserEmail(newEmail.trim());
    }
}

function changeUserEmail(newEmail) {
    if (!currentUserId) {
        alert('Seleziona prima un utente');
        return;
    }

    const formData = {
        user_id: currentUserId,
        new_email: newEmail
    };

    fetch('<?php echo e(route("admin.peertube.change-user-email")); ?>', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            // Ricarica i dati utente per mostrare la nuova email
            setTimeout(() => loadUserData(), 1000);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        showAlert('danger', 'Errore durante il cambio email');
    });
}

function verifyUserExists() {
    if (!currentUserId) {
        alert('Seleziona prima un utente');
        return;
    }

    const formData = {
        user_id: currentUserId
    };

    fetch('<?php echo e(route("admin.peertube.verify-user-exists")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayVerificationResults(data);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        showAlert('danger', 'Errore durante la verifica dell\'esistenza utente');
    });
}

function syncUserData() {
    if (!currentUserId) {
        alert('Seleziona prima un utente');
        return;
    }

    if (!confirm('Sei sicuro di voler sincronizzare i dati PeerTube per questo utente?')) {
        return;
    }

    const formData = {
        user_id: currentUserId
    };

    fetch('<?php echo e(route("admin.peertube.sync-user-data")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            // Aggiorna i dati del form se disponibili
            if (data.updated_data) {
                if (data.updated_data.peertube_user_id) {
                    document.getElementById('peertube_user_id').value = data.updated_data.peertube_user_id;
                }
                if (data.updated_data.peertube_username) {
                    document.getElementById('peertube_username').value = data.updated_data.peertube_username;
                }
                if (data.updated_data.peertube_channel_id) {
                    document.getElementById('peertube_channel_id').value = data.updated_data.peertube_channel_id;
                }
                if (data.updated_data.peertube_account_id) {
                    document.getElementById('peertube_account_id').value = data.updated_data.peertube_account_id;
                }
            }
            // Ricarica i dati utente
            setTimeout(() => loadUserData(), 1000);
        } else {
            showAlert('danger', data.message);
            // Mostra i risultati della verifica se disponibili
            if (data.verification_results) {
                displayVerificationResults({ success: true, verification_results: data.verification_results });
            }
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        showAlert('danger', 'Errore durante la sincronizzazione dei dati PeerTube');
    });
}

function displayVerificationResults(data) {
    const results = data.verification_results;
    const summary = data.summary;

    let html = `
        <div class="card card-light-info">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ph ph-shield-check me-2"></i>
                    Risultati Verifica Esistenza PeerTube
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="alert alert-${summary.any_success ? 'success' : 'danger'}">
                            <strong>Stato Generale:</strong>
                            ${summary.any_success ? '✅ Utente trovato su PeerTube' : '❌ Utente non trovato su PeerTube'}
                            <br>
                            <strong>Metodi riusciti:</strong> ${summary.successful_methods.length}/${summary.total_methods}
                        </div>
                    </div>
                </div>
    `;

    // Mostra i risultati per ogni metodo
    Object.keys(results).forEach(method => {
        const result = results[method];
        const methodName = result.method;
        const isSuccess = result.success;

        html += `
            <div class="row mb-2">
                <div class="col-12">
                    <div class="card ${isSuccess ? 'border-success' : 'border-danger'}">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="ph ph-${isSuccess ? 'check-circle text-success' : 'x-circle text-danger'} f-s-20"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${methodName}</h6>
                                    ${isSuccess ?
                                        `<p class="mb-0 text-success">✅ ${methodName} trovato</p>` :
                                        `<p class="mb-0 text-danger">❌ ${result.error}</p>`
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    html += `
            </div>
        </div>
    `;

    // Crea un modal per mostrare i risultati
    const modalHtml = `
        <div class="modal fade" id="verificationModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ph ph-shield-check me-2"></i>
                            Verifica Esistenza PeerTube
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${html}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Rimuovi modal esistente se presente
    const existingModal = document.getElementById('verificationModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Aggiungi il nuovo modal
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Mostra il modal
    const modal = new bootstrap.Modal(document.getElementById('verificationModal'));
    modal.show();
}

function refreshLogs() {
    location.reload();
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);

    // Rimuovi l'alert dopo 5 secondi
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/peertube/manage-users.blade.php ENDPATH**/ ?>