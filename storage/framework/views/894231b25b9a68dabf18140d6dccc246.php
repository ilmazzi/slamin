<?php $__env->startSection('title', __('groups.create_group')); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-plus-circle me-2 text-primary"></i>
                        <?php echo e(__('groups.create_group')); ?>

                    </h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('groups.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

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
                                   value="<?php echo e(old('name')); ?>"
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
                                      placeholder="<?php echo e(__('groups.group_description_placeholder')); ?>"><?php echo e(old('description')); ?></textarea>
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
                                            <?php echo e(__('groups.image_preview')); ?>

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
                                <option value="public" <?php echo e(old('visibility') == 'public' ? 'selected' : ''); ?>>
                                    <?php echo e(__('groups.visibility_public')); ?>

                                </option>
                                <option value="private" <?php echo e(old('visibility') == 'private' ? 'selected' : ''); ?>>
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

                        <!-- Sezione Inviti Membri -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label mb-0">
                                    <i class="ph-duotone ph-users me-1"></i>
                                    <?php echo e(__('groups.invite_members')); ?>

                                </label>
                                <span class="badge bg-primary" id="invitedUsersCount">0</span>
                            </div>

                            <!-- Ricerca utenti -->
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control"
                                           id="userSearch"
                                           placeholder="<?php echo e(__('groups.search_users_placeholder')); ?>"
                                           onkeydown="handleUserSearchKeydown(event)">
                                    <button class="btn btn-outline-primary"
                                            type="button"
                                            onclick="searchUsersForGroup()">
                                        <i class="ph-duotone ph-magnifying-glass"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Risultati ricerca -->
                            <div id="searchResults" class="mb-3" style="display: none;">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><?php echo e(__('groups.search_results')); ?></h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="searchResultsList" class="list-group list-group-flush">
                                            <!-- Risultati dinamici qui -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lista utenti invitati -->
                            <div id="invitedUsersList" class="mb-3" style="display: none;">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><?php echo e(__('groups.invited_users')); ?></h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="invitedUsersContainer" class="list-group list-group-flush">
                                            <!-- Utenti invitati dinamici qui -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Input nascosto per i dati degli utenti invitati -->
                            <input type="hidden" id="invitedUsersData" name="invited_users" value="[]">
                        </div>

                        <!-- Pulsanti -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph-duotone ph-check me-2"></i>
                                <?php echo e(__('groups.create')); ?>

                            </button>
                            <a href="<?php echo e(route('groups.index')); ?>" class="btn btn-light">
                                <i class="ph-duotone ph-arrow-left me-2"></i>
                                <?php echo e(__('common.cancel')); ?>

                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Suggerimenti -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph-duotone ph-lightbulb me-2 text-warning"></i>
                        <?php echo e(__('common.tips')); ?>

                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="ph-duotone ph-check-circle text-success me-2"></i>
                            <?php echo e(__('groups.tips.create_group')); ?>

                        </li>
                        <li class="mb-2">
                            <i class="ph-duotone ph-check-circle text-success me-2"></i>
                            <?php echo e(__('groups.tips.invite_members')); ?>

                        </li>
                        <li class="mb-2">
                            <i class="ph-duotone ph-check-circle text-success me-2"></i>
                            <?php echo e(__('groups.tips.manage_permissions')); ?>

                        </li>
                        <li>
                            <i class="ph-duotone ph-check-circle text-success me-2"></i>
                            <?php echo e(__('groups.tips.group_events')); ?>

                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Array per memorizzare gli utenti invitati
let invitedUsers = [];

// Aggiorna il contatore degli utenti invitati
function updateInvitedUsersCount() {
    const countElement = document.getElementById('invitedUsersCount');
    countElement.textContent = invitedUsers.length;
}

// Aggiorna la visualizzazione della lista utenti invitati
function updateInvitedUsersDisplay() {
    const container = document.getElementById('invitedUsersContainer');
    const listDiv = document.getElementById('invitedUsersList');

    if (invitedUsers.length === 0) {
        listDiv.style.display = 'none';
        return;
    }

    listDiv.style.display = 'block';
    container.innerHTML = invitedUsers.map(user => `
        <div class="list-group-item d-flex justify-content-between align-items-center" data-user-id="${user.id}">
            <div class="d-flex align-items-center">
                <img src="${user.avatar_url || '<?php echo e(asset('assets/images/avatar/default-avatar.webp')); ?>'}"
                     alt="Avatar"
                     class="rounded-circle me-3"
                     width="40"
                     height="40"
                     onerror="this.src='<?php echo e(asset('assets/images/avatar/default-avatar.webp')); ?>'">
                <div>
                    <div class="fw-bold">${user.name}</div>
                    <small class="text-muted">${user.email}</small>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeInvitedUser(${user.id})">
                <i class="ph-duotone ph-x"></i>
            </button>
        </div>
    `).join('');
}

// Aggiorna i dati nascosti per gli utenti invitati
function updateInvitedUsersData() {
    const hiddenInput = document.getElementById('invitedUsersData');
    if (hiddenInput) {
        hiddenInput.value = JSON.stringify(invitedUsers);
    }
}

// Gestisce il tasto Invio nella ricerca utenti
function handleUserSearchKeydown(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        searchUsersForGroup();
    }
}

// Cerca utenti per inviti al gruppo
function searchUsersForGroup() {
    const searchTerm = document.getElementById('userSearch').value.trim();
    const resultsDiv = document.getElementById('searchResults');
    const resultsList = document.getElementById('searchResultsList');

    if (!searchTerm) {
        Swal.fire('Attenzione', 'Inserisci un termine di ricerca', 'warning');
        return;
    }

    // Mostra loading
    resultsList.innerHTML = '<div class="list-group-item text-center"><div class="spinner-border spinner-border-sm" role="status"></div> Ricerca in corso...</div>';
    resultsDiv.style.display = 'block';

    // Chiamata API per la ricerca
    fetch(`/api/users/search?q=${encodeURIComponent(searchTerm)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.users && data.users.length > 0) {
            resultsList.innerHTML = data.users.map(user => `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="${user.avatar_url || '<?php echo e(asset('assets/images/avatar/default-avatar.webp')); ?>'}"
                             alt="Avatar"
                             class="rounded-circle me-2"
                             width="32"
                             height="32"
                             onerror="this.src='<?php echo e(asset('assets/images/avatar/default-avatar.webp')); ?>'">
                        <div>
                            <div class="fw-bold">${user.name}</div>
                            <small class="text-muted">${user.email}</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary"
                            onclick="addUserToInviteList(${user.id}, '${user.name}', '${user.email}', '${user.avatar_url || ''}')">
                        <i class="ph-duotone ph-plus me-1"></i>Invita
                    </button>
                </div>
            `).join('');
        } else {
            resultsList.innerHTML = '<div class="list-group-item text-center text-muted">Nessun utente trovato</div>';
        }
    })
    .catch(error => {
        console.error('Search error:', error);
        resultsList.innerHTML = '<div class="list-group-item text-center text-danger">Errore durante la ricerca</div>';
    });
}

// Aggiunge un utente alla lista degli inviti
function addUserToInviteList(userId, userName, userEmail, userAvatar) {
    // Controlla se l'utente è già stato invitato
    if (invitedUsers.some(user => user.id === userId)) {
        Swal.fire('Attenzione', 'Questo utente è già stato invitato', 'warning');
        return;
    }

    // Aggiungi l'utente alla lista
    invitedUsers.push({
        id: userId,
        name: userName,
        email: userEmail,
        avatar_url: userAvatar
    });

    // Aggiorna la visualizzazione
    updateInvitedUsersDisplay();
    updateInvitedUsersCount();
    updateInvitedUsersData();

    // Nascondi i risultati della ricerca
    document.getElementById('searchResults').style.display = 'none';
    document.getElementById('userSearch').value = '';

    // Mostra conferma
    Swal.fire({
        icon: 'success',
        title: 'Utente aggiunto',
        text: `${userName} è stato aggiunto alla lista degli inviti`,
        timer: 1500,
        showConfirmButton: false
    });
}

// Rimuove un utente dalla lista degli inviti
function removeInvitedUser(userId) {
    invitedUsers = invitedUsers.filter(user => user.id !== userId);
    updateInvitedUsersDisplay();
    updateInvitedUsersCount();
    updateInvitedUsersData();
}

// Validazione del form prima dell'invio
document.querySelector('form').addEventListener('submit', function(e) {
    // La validazione degli inviti è opzionale, quindi non blocchiamo l'invio
    // ma aggiorniamo i dati nascosti
    updateInvitedUsersData();
});

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

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/groups/create.blade.php ENDPATH**/ ?>