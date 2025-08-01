<?php $__env->startSection('title', 'Gestione Permessi - Slamin'); ?>

<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title"><?php echo e(__('permissions.permissions')); ?></h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="<?php echo e(route('dashboard')); ?>" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> <?php echo e(__('dashboard.dashboard')); ?>

                        </span>
                    </a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500"><?php echo e(__('permissions.permissions')); ?></a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Quick Navigation Links -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-navigation-arrow me-2"></i>
                        <?php echo e(__('permissions.quick_navigation')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('permissions.index')); ?>" class="card card-light-primary hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-gauge f-s-30 text-primary mb-2"></i>
                                    <h6 class="mb-1"><?php echo e(__('permissions.dashboard')); ?></h6>
                                    <small class="text-muted"><?php echo e(__('permissions.overview')); ?></small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('permissions.roles')); ?>" class="card card-light-info hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-users f-s-30 text-info mb-2"></i>
                                    <h6 class="mb-1"><?php echo e(__('permissions.roles')); ?></h6>
                                    <small class="text-muted"><?php echo e(__('permissions.manage_roles')); ?></small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('permissions.permissions')); ?>" class="card card-light-success hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-shield-check f-s-30 text-success mb-2"></i>
                                    <h6 class="mb-1"><?php echo e(__('permissions.permissions')); ?></h6>
                                    <small class="text-muted"><?php echo e(__('permissions.manage_permissions')); ?></small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('permissions.users')); ?>" class="card card-light-warning hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-user-circle f-s-30 text-warning mb-2"></i>
                                    <h6 class="mb-1"><?php echo e(__('permissions.users')); ?></h6>
                                    <small class="text-muted"><?php echo e(__('permissions.manage_users')); ?></small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 f-w-600"><?php echo e(__('permissions.permissions_panel')); ?></h4>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('permissions.roles')); ?>" class="btn btn-primary hover-effect">
                        <i class="ph ph-users me-2"></i>
                        Gestione <?php echo e(__('permissions.roles')); ?>

                    </a>
                    <a href="<?php echo e(route('permissions.permissions')); ?>" class="btn btn-success hover-effect">
                        <i class="ph ph-shield-check me-2"></i>
                        Gestione Permessi
                    </a>
                    <a href="<?php echo e(route('permissions.users')); ?>" class="btn btn-warning hover-effect">
                        <i class="ph ph-user me-2"></i>
                        Gestione Utenti
                    </a>
                    <button class="btn btn-info hover-effect" onclick="showCreateRoleModal()">
                        <i class="ph ph-plus me-2"></i>
                        Nuovo <?php echo e(__('invitations.role')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-6 col-md-3">
            <div class="card hover-effect equal-card b-t-4-primary">
                <div class="card-body eshop-cards text-center pa-20">
                    <div class="bg-light-primary h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                        <i class="ph ph-users f-s-20 text-primary"></i>
                    </div>
                    <span class="ripple-effect"></span>
                    <div class="overflow-hidden">
                        <h3 class="text-primary mb-1 f-w-600"><?php echo e($stats['total_roles']); ?></h3>
                        <p class="f-w-500 text-dark f-s-13 mb-1"><?php echo e(__('permissions.total_roles')); ?></p>
                        <span class="badge bg-light-primary f-s-11"><?php echo e(__('permissions.role_management')); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card hover-effect equal-card b-t-4-success">
                <div class="card-body eshop-cards text-center pa-20">
                    <div class="bg-light-success h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                        <i class="ph ph-shield-check f-s-20 text-success"></i>
                    </div>
                    <span class="ripple-effect"></span>
                    <div class="overflow-hidden">
                        <h3 class="text-success mb-1 f-w-600"><?php echo e($stats['total_permissions']); ?></h3>
                        <p class="f-w-500 text-dark f-s-13 mb-1"><?php echo e(__('permissions.total_permissions')); ?></p>
                        <span class="badge bg-light-success f-s-11"><?php echo e(__('permissions.role_security')); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card hover-effect equal-card b-t-4-warning">
                <div class="card-body eshop-cards text-center pa-20">
                    <div class="bg-light-warning h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                        <i class="ph ph-user f-s-20 text-warning"></i>
                    </div>
                    <span class="ripple-effect"></span>
                    <div class="overflow-hidden">
                        <h3 class="text-warning mb-1 f-w-600"><?php echo e($stats['total_users']); ?></h3>
                        <p class="f-w-500 text-dark f-s-13 mb-1"><?php echo e(__('permissions.total_users')); ?></p>
                        <span class="badge bg-light-warning f-s-11"><?php echo e(__('permissions.role_users')); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card hover-effect equal-card b-t-4-info">
                <div class="card-body eshop-cards text-center pa-20">
                    <div class="bg-light-info h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                        <i class="ph ph-chart-line f-s-20 text-info"></i>
                    </div>
                    <span class="ripple-effect"></span>
                    <div class="overflow-hidden">
                        <h3 class="text-info mb-1 f-w-600"><?php echo e($stats['roles_with_permissions']); ?></h3>
                        <p class="f-w-500 text-dark f-s-13 mb-1"><?php echo e(__('permissions.roles_with_permissions')); ?></p>
                        <span class="badge bg-light-info f-s-11"><?php echo e(__('permissions.role_statistics')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Recent Roles -->
        <div class="col-lg-6">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 f-w-600 text-dark">
                            <i class="ph ph-users me-2 text-primary"></i>
                            <?php echo e(__('permissions.roles')); ?> Recenti
                        </h5>
                        <a href="<?php echo e(route('permissions.roles')); ?>" class="btn btn-sm btn-primary hover-effect">
                            Vedi Tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="f-w-600"><?php echo e(__('invitations.role')); ?></th>
                                    <th class="f-w-600 text-center"><?php echo e(__('permissions.permissions_count')); ?></th>
                                    <th class="f-w-600 text-center"><?php echo e(__('permissions.users_count')); ?></th>
                                    <th class="f-w-600 text-center"><?php echo e(__('invitations.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $roles->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0 f-w-600"><?php echo e($role->display_name ?? $role->name); ?></h6>
                                            <small class="text-muted f-s-12"><?php echo e($role->description ?? 'Nessuna descrizione'); ?></small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary f-s-12"><?php echo e($role->permissions->count()); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary f-s-12"><?php echo e($role->users->count()); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary hover-effect" onclick="editRole(<?php echo e($role->id); ?>)" title="<?php echo e(__('permissions.modify')); ?>">
                                                <i class="ph ph-pencil f-s-14"></i>
                                            </button>
                                            <?php if($role->users->count() == 0): ?>
                                            <button class="btn btn-outline-danger hover-effect" onclick="deleteRole(<?php echo e($role->id); ?>)" title="Elimina">
                                                <i class="ph ph-trash f-s-14"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Permissions -->
        <div class="col-lg-6">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 f-w-600 text-dark">
                            <i class="ph ph-shield-check me-2 text-success"></i>
                            Permessi Recenti
                        </h5>
                        <a href="<?php echo e(route('permissions.permissions')); ?>" class="btn btn-sm btn-success hover-effect">
                            Vedi Tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="f-w-600"><?php echo e(__('permissions.permission')); ?></th>
                                    <th class="f-w-600 text-center"><?php echo e(__('permissions.group')); ?></th>
                                    <th class="f-w-600 text-center"><?php echo e(__('permissions.roles')); ?></th>
                                    <th class="f-w-600 text-center"><?php echo e(__('invitations.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $permissions->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0 f-w-600"><?php echo e($permission->display_name ?? $permission->name); ?></h6>
                                            <small class="text-muted f-s-12"><?php echo e($permission->description ?? 'Nessuna descrizione'); ?></small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info f-s-12"><?php echo e($permission->group ?? 'N/A'); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary f-s-12"><?php echo e($permission->roles->count()); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary hover-effect" onclick="editPermission(<?php echo e($permission->id); ?>)" title="<?php echo e(__('permissions.modify')); ?>">
                                                <i class="ph ph-pencil f-s-14"></i>
                                            </button>
                                            <?php if($permission->roles->count() == 0): ?>
                                            <button class="btn btn-outline-danger hover-effect" onclick="deletePermission(<?php echo e($permission->id); ?>)" title="Elimina">
                                                <i class="ph ph-trash f-s-14"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 f-w-600 text-dark">
                            <i class="ph ph-user me-2 text-warning"></i>
                            Utenti Recenti
                        </h5>
                        <a href="<?php echo e(route('permissions.users')); ?>" class="btn btn-sm btn-warning hover-effect">
                            Vedi Tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="f-w-600"><?php echo e(__('permissions.user')); ?></th>
                                    <th class="f-w-600"><?php echo e(__('permissions.email')); ?></th>
                                    <th class="f-w-600 text-center"><?php echo e(__('permissions.roles')); ?></th>
                                    <th class="f-w-600 text-center"><?php echo e(__('invitations.status')); ?></th>
                                    <th class="f-w-600 text-center"><?php echo e(__('invitations.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $users->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="bg-light-primary h-40 w-40 d-flex-center b-r-50 position-relative overflow-hidden me-3">
                                                    <img src="<?php echo e(asset('assets/images/avatar/' . ($user->id % 16 + 1) . '.png')); ?>" alt="<?php echo e(__('common.avatar')); ?>" class="img-fluid b-r-50">
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 f-w-600"><?php echo e($user->name); ?></h6>
                                                <small class="text-muted f-s-12">ID: <?php echo e($user->id); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:<?php echo e($user->email); ?>" class="text-primary"><?php echo e($user->email); ?></a>
                                    </td>
                                    <td class="text-center">
                                        <?php $__currentLoopData = $user->roles->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-primary me-1 f-s-11"><?php echo e($role->display_name ?? $role->name); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($user->roles->count() > 2): ?>
                                        <span class="badge bg-secondary f-s-11">+<?php echo e($user->roles->count() - 2); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($user->status === 'active'): ?>
                                        <span class="badge bg-success f-s-12">Attivo</span>
                                        <?php elseif($user->status === 'inactive'): ?>
                                        <span class="badge bg-warning f-s-12">Inattivo</span>
                                        <?php else: ?>
                                        <span class="badge bg-secondary f-s-12"><?php echo e($user->status ?? 'N/A'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary hover-effect" onclick="editUserRoles(<?php echo e($user->id); ?>)" title="Gestisci <?php echo e(__('permissions.roles')); ?>">
                                                <i class="ph ph-users f-s-14"></i>
                                            </button>
                                            <button class="btn btn-outline-success hover-effect" onclick="editUserPermissions(<?php echo e($user->id); ?>)" title="<?php echo e(__('permissions.manage_permissions')); ?>">
                                                <i class="ph ph-shield-check f-s-14"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-light-primary">
                <h5 class="modal-title f-w-600">
                    <i class="ph ph-plus me-2"></i>
                    Crea Nuovo <?php echo e(__('invitations.role')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createRoleForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label f-w-600">Nome <?php echo e(__('invitations.role')); ?> *</label>
                                <input type="text" class="form-control" name="name" required>
                                <small class="text-muted f-s-12">Nome tecnico (es: admin, moderator)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label f-w-600">Nome Visualizzato *</label>
                                <input type="text" class="form-control" name="display_name" required>
                                <small class="text-muted f-s-12">Nome per l'interfaccia (es: Amministratore)</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label f-w-600">Descrizione</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                        <small class="text-muted f-s-12">Descrizione dettagliata del ruolo</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary hover-effect" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary hover-effect">
                        <i class="ph ph-plus me-2"></i>
                        Crea <?php echo e(__('invitations.role')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function showCreateRoleModal() {
    $('#createRoleModal').modal('show');
}

function editRole(roleId) {
    window.location.href = `<?php echo e(route('permissions.roles')); ?>?edit=${roleId}`;
}

function deleteRole(roleId) {
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Questa azione non può essere annullata!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sì, elimina!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`<?php echo e(route('permissions.roles.delete', ['role' => ':roleId'])); ?>`.replace(':roleId', roleId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Eliminato!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Errore!', data.message, 'error');
                }
            });
        }
    });
}

function editPermission(permissionId) {
    window.location.href = `<?php echo e(route('permissions.permissions')); ?>?edit=${permissionId}`;
}

function deletePermission(permissionId) {
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Questa azione non può essere annullata!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sì, elimina!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`<?php echo e(route('permissions.permissions.delete', ['permission' => ':permissionId'])); ?>`.replace(':permissionId', permissionId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Eliminato!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Errore!', data.message, 'error');
                }
            });
        }
    });
}

function editUserRoles(userId) {
                window.location.href = `<?php echo e(route('permissions.users')); ?>?edit_roles=${userId}`;
}

function editUserPermissions(userId) {
                window.location.href = `<?php echo e(route('permissions.users')); ?>?edit_permissions=${userId}`;
}

// Create Role Form Handler
$('#createRoleForm').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('<?php echo e(route("permissions.roles.store")); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Successo!', data.message, 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Errore!', data.message, 'error');
        }
    });
});

// Hide loader as fallback
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const loader = document.querySelector('.loader-wrapper');
        if (loader) {
            loader.style.display = 'none';
        }
    }, 1000);
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/permissions/index.blade.php ENDPATH**/ ?>