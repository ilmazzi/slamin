<?php $__env->startSection('title', __('groups.invite_members') . ' - ' . $group->name); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-envelope me-2 text-success"></i>
                        <?php echo e(__('groups.invite_members')); ?> - <?php echo e($group->name); ?>

                    </h4>
                    <a href="<?php echo e(route('groups.members.index', $group)); ?>" class="btn btn-light">
                        <i class="ph-duotone ph-arrow-left me-2"></i>
                        <?php echo e(__('common.back')); ?>

                    </a>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('groups.invitations.store', $group)); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_search" class="form-label">Cerca Utente *</label>
                                    <div class="position-relative">
                                                                                                                        <input type="text"
                                               class="form-control <?php $__errorArgs = ['user_search'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               id="user_search"
                                               placeholder="Cerca per nome, nickname o email..."
                                               autocomplete="off">
                                        <input type="hidden" id="selected_user_id" name="user_id" value="<?php echo e(old('user_id')); ?>">
                                        <div id="search_results" class="position-absolute w-100 bg-white border rounded shadow-sm" style="top: 100%; left: 0; z-index: 1000; display: none; max-height: 200px; overflow-y: auto;"></div>
                                    </div>
                                    <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">
                                        Inizia a digitare per cercare utenti. L'utente deve essere già registrato sulla piattaforma.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="message" class="form-label"><?php echo e(__('groups.invite_message')); ?></label>
                                    <textarea class="form-control <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                              id="message"
                                              name="message"
                                              rows="4"
                                              placeholder="<?php echo e(__('groups.invite_message_placeholder')); ?>"><?php echo e(old('message')); ?></textarea>
                                    <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">
                                        <?php echo e(__('groups.invite_message_help')); ?>

                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="ph-duotone ph-paper-plane me-2"></i>
                                        <?php echo e(__('groups.send_invitation')); ?>

                                    </button>
                                    <a href="<?php echo e(route('groups.members.index', $group)); ?>" class="btn btn-light">
                                        <?php echo e(__('common.cancel')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Informazioni aggiuntive -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-light-info">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph-duotone ph-info me-2 text-info"></i>
                        <?php echo e(__('groups.invitation_info')); ?>

                    </h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li><?php echo e(__('groups.invitation_info_1')); ?></li>
                        <li><?php echo e(__('groups.invitation_info_2')); ?></li>
                        <li><?php echo e(__('groups.invitation_info_3')); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('user_search');
    const searchResults = document.getElementById('search_results');
    const selectedUserId = document.getElementById('selected_user_id');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        clearTimeout(searchTimeout);

        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`/groups/<?php echo e($group->id); ?>/members/search?search=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data);
                })
                .catch(error => {
                    console.error('Errore nella ricerca:', error);
                });
        }, 300);
    });

    function displaySearchResults(users) {
        if (users.length === 0) {
            searchResults.innerHTML = '<div class="p-3 text-muted">Nessun utente trovato</div>';
        } else {
                        searchResults.innerHTML = users.map(user => `
                <div class="search-result-item p-2 border-bottom cursor-pointer hover-bg-light"
                     data-user-id="${user.id}"
                     data-user-name="${user.name}">
                    <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                            <img src="${user.avatar_url}"
                                 class="rounded-circle"
                                 width="32"
                                 height="32"
                                 alt="${user.name}"
                                 onerror="this.src='<?php echo e(asset('assets/images/avatar/default-avatar.webp')); ?>'">
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-medium">${user.name}</div>
                            <small class="text-muted">
                                ${user.nickname ? `@${user.nickname} • ` : ''}${user.email}
                            </small>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        searchResults.style.display = 'block';
    }

    // Gestione click sui risultati
    searchResults.addEventListener('click', function(e) {
        const resultItem = e.target.closest('.search-result-item');
        if (resultItem) {
            const userId = resultItem.dataset.userId;
            const userName = resultItem.dataset.userName;

            selectedUserId.value = userId;
            searchInput.value = userName;
            searchResults.style.display = 'none';
        }
    });

    // Nascondi risultati quando si clicca fuori
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    // Validazione form
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!selectedUserId.value) {
            e.preventDefault();
            Swal.fire('<?php echo e(__("common.attention")); ?>', '<?php echo e(__("groups.select_user_from_search")); ?>', 'warning');
            return false;
        }
    });
});
</script>

<style>
.search-result-item:hover {
    background-color: #f8f9fa;
}

.cursor-pointer {
    cursor: pointer;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/groups/invitations/create.blade.php ENDPATH**/ ?>