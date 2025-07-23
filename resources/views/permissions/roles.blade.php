@extends('layout.master')

@section('title', 'Gestione Ruoli - Slamin')

@section('css')
<style>
#breadcrumb-nav {
    position: relative !important;
    z-index: 1 !important;
    background: transparent !important;
    width: auto !important;
    height: auto !important;
}
</style>
@endsection

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">{{ __('permissions.roles') }}</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> {{ __('dashboard.dashboard') }}
                        </span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('permissions.index') }}" class="f-s-14 f-w-500">{{ __('permissions.permissions') }}</a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">{{ __('permissions.roles') }}</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Header Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Gestione Ruoli del Sistema</h4>
                <button class="btn btn-primary" onclick="showCreateRoleModal()">
                    <i class="ph ph-plus me-2"></i>
                    Nuovo Ruolo
                </button>
            </div>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-users me-2 text-primary"></i>
                        Ruoli del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="rolesTable">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Nome Visualizzato</th>
                                    <th>Descrizione</th>
                                    <th>Permessi</th>
                                    <th>Utenti</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <tr data-role-id="{{ $role->id }}">
                                    <td>
                                        <code>{{ $role->name }}</code>
                                    </td>
                                    <td>
                                        <strong>{{ $role->display_name ?? $role->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $role->description ?? 'Nessuna descrizione' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $role->permissions->count() }}</span>
                                        @if($role->permissions->count() > 0)
                                        <button class="btn btn-sm btn-outline-info ms-2" onclick="showRolePermissions({{ $role->id }})" title="Visualizza Permessi">
                                            <i class="ph ph-eye"></i>
                                        </button>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $role->users->count() }}</span>
                                        @if($role->users->count() > 0)
                                        <button class="btn btn-sm btn-outline-warning ms-2" onclick="showRoleUsers({{ $role->id }})" title="Visualizza Utenti">
                                            <i class="ph ph-users"></i>
                                        </button>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="editRole({{ $role->id }})" title="Modifica">
                                                <i class="ph ph-pencil"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="manageRolePermissions({{ $role->id }})" title="Gestisci Permessi">
                                                <i class="ph ph-shield-check"></i>
                                            </button>
                                            @if($role->users->count() == 0)
                                            <button class="btn btn-outline-danger" onclick="deleteRole({{ $role->id }})" title="Elimina">
                                                <i class="ph ph-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Role Modal -->
<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModalTitle">
                    <i class="ph ph-plus me-2"></i>
                    Crea Nuovo Ruolo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="roleForm">
                <input type="hidden" name="role_id" id="roleId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nome Ruolo *</label>
                                <input type="text" class="form-control" name="name" id="roleName" required>
                                <small class="text-muted">Nome tecnico (es: admin, moderator)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nome Visualizzato *</label>
                                <input type="text" class="form-control" name="display_name" id="roleDisplayName" required>
                                <small class="text-muted">Nome per l'interfaccia (es: Amministratore)</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrizione</label>
                        <textarea class="form-control" name="description" id="roleDescription" rows="3"></textarea>
                        <small class="text-muted">Descrizione dettagliata del ruolo</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Permessi</label>
                        <div class="row">
                            @foreach($permissions->groupBy('group') as $group => $groupPermissions)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">{{ $group ?? 'Generale' }}</h6>
                                    </div>
                                    <div class="card-body">
                                        @foreach($groupPermissions as $permission)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                   value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                <strong>{{ $permission->display_name ?? $permission->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $permission->description ?? 'Nessuna descrizione' }}</small>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary" id="roleSubmitBtn">
                        <i class="ph ph-plus me-2"></i>
                        Crea Ruolo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Role Permissions Modal -->
<div class="modal fade" id="rolePermissionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-shield-check me-2"></i>
                    Permessi del Ruolo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="rolePermissionsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Role Users Modal -->
<div class="modal fade" id="roleUsersModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-users me-2"></i>
                    Utenti con questo Ruolo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="roleUsersContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
let currentRoleId = null;

function showCreateRoleModal() {
    $('#roleModalTitle').html('<i class="ph ph-plus me-2"></i>Crea Nuovo Ruolo');
    $('#roleSubmitBtn').html('<i class="ph ph-plus me-2"></i>Crea Ruolo');
    $('#roleForm')[0].reset();
    $('#roleId').val('');
    $('#roleName').prop('readonly', false);
    $('input[name="permissions[]"]').prop('checked', false);
    $('#roleModal').modal('show');
}

function editRole(roleId) {
    currentRoleId = roleId;
    fetch(`{{ route('permissions.roles.index') }}?role_id=${roleId}`)
        .then(response => response.json())
        .then(data => {
            if (data.role) {
                $('#roleModalTitle').html('<i class="ph ph-pencil me-2"></i>Modifica Ruolo');
                $('#roleSubmitBtn').html('<i class="ph ph-check me-2"></i>Aggiorna Ruolo');
                $('#roleId').val(data.role.id);
                $('#roleName').val(data.role.name).prop('readonly', true);
                $('#roleDisplayName').val(data.role.display_name);
                $('#roleDescription').val(data.role.description);
                $('input[name="permissions[]"]').prop('checked', false);
                data.permissions.forEach(permission => {
                    $(`input[value="${permission.name}"]`).prop('checked', true);
                });
                $('#roleModal').modal('show');
            }
        });
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
            fetch(`{{ route('permissions.roles.delete', ['role' => ':roleId']) }}`.replace(':roleId', roleId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
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

function showRolePermissions(roleId) {
    fetch(`{{ route('permissions.roles.index') }}?role_id=${roleId}`)
        .then(response => response.json())
        .then(data => {
            if (data.role) {
                let content = '<div class="table-responsive"><table class="table">';
                content += '<thead><tr><th>Permesso</th><th>Gruppo</th><th>Descrizione</th></tr></thead><tbody>';
                data.permissions.forEach(permission => {
                    content += `<tr>
                        <td><strong>${permission.display_name || permission.name}</strong></td>
                        <td><span class="badge bg-info">${permission.group || 'N/A'}</span></td>
                        <td>${permission.description || 'Nessuna descrizione'}</td>
                    </tr>`;
                });
                content += '</tbody></table></div>';
                if (data.permissions.length === 0) {
                    content = '<p class="text-muted">Nessun permesso assegnato a questo ruolo.</p>';
                }
                $('#rolePermissionsContent').html(content);
                $('#rolePermissionsModal').modal('show');
            }
        });
}

function showRoleUsers(roleId) {
    fetch(`{{ route('permissions.roles.index') }}?role_id=${roleId}`)
        .then(response => response.json())
        .then(data => {
            if (data.role) {
                let content = '<div class="table-responsive"><table class="table">';
                content += '<thead><tr><th>Utente</th><th>Email</th><th>Stato</th></tr></thead><tbody>';
                data.role.users.forEach(user => {
                    content += `<tr>
                        <td><strong>${user.name}</strong></td>
                        <td>${user.email}</td>
                        <td><span class="badge bg-success">Attivo</span></td>
                    </tr>`;
                });
                content += '</tbody></table></div>';
                if (data.role.users.length === 0) {
                    content = '<p class="text-muted">Nessun utente con questo ruolo.</p>';
                }
                $('#roleUsersContent').html(content);
                $('#roleUsersModal').modal('show');
            }
        });
}

function manageRolePermissions(roleId) {
    editRole(roleId);
}

$('#roleForm').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const roleId = $('#roleId').val();
            const url = roleId ? `{{ route('permissions.roles.update', ['role' => ':roleId']) }}`.replace(':roleId', roleId) : '{{ route("permissions.roles.store") }}';
    const method = roleId ? 'POST' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
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

$(document).ready(function() {
    $('#rolesTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/it-IT.json'
        },
        order: [[1, 'asc']],
        pageLength: 25
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
@endsection
