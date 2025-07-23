@extends('layout.master')

@section('title', 'Gestione Permessi - Slamin')

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
            <h4 class="main-title">{{ __('permissions.permissions') }}</h4>
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
                    <a href="#" class="f-s-14 f-w-500">{{ __('permissions.permissions') }}</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Header Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Permessi del Sistema</h4>
                <button class="btn btn-primary" onclick="showCreatePermissionModal()">
                    <i class="ph ph-plus me-2"></i>
                    Nuovo Permesso
                </button>
            </div>
        </div>
    </div>

    <!-- Permissions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-shield-check me-2 text-success"></i>
                        Permessi del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="permissionsTable">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Nome Visualizzato</th>
                                    <th>Gruppo</th>
                                    <th>Descrizione</th>
                                    <th>Ruoli</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $permission)
                                <tr data-permission-id="{{ $permission->id }}">
                                    <td>
                                        <code>{{ $permission->name }}</code>
                                    </td>
                                    <td>
                                        <strong>{{ $permission->display_name ?? $permission->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $permission->group ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $permission->description ?? 'Nessuna descrizione' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $permission->roles->count() }}</span>
                                        @if($permission->roles->count() > 0)
                                        <button class="btn btn-sm btn-outline-info ms-2" onclick="showPermissionRoles({{ $permission->id }})" title="Visualizza Ruoli">
                                            <i class="ph ph-eye"></i>
                                        </button>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="editPermission({{ $permission->id }})" title="Modifica">
                                                <i class="ph ph-pencil"></i>
                                            </button>
                                            @if($permission->roles->count() == 0)
                                            <button class="btn btn-outline-danger" onclick="deletePermission({{ $permission->id }})" title="Elimina">
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

<!-- Create/Edit Permission Modal -->
<div class="modal fade" id="permissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permissionModalTitle">
                    <i class="ph ph-plus me-2"></i>
                    Crea Nuovo Permesso
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="permissionForm">
                <input type="hidden" name="permission_id" id="permissionId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome Permesso *</label>
                        <input type="text" class="form-control" name="name" id="permissionName" required>
                        <small class="text-muted">Nome tecnico (es: events.create, users.manage)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nome Visualizzato *</label>
                        <input type="text" class="form-control" name="display_name" id="permissionDisplayName" required>
                        <small class="text-muted">Nome per l'interfaccia (es: Crea Eventi)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gruppo</label>
                        <select class="form-select" name="group" id="permissionGroup">
                            <option value="">Nessun gruppo</option>
                            <option value="events">Eventi</option>
                            <option value="users">Utenti</option>
                            <option value="content">Contenuti</option>
                            <option value="analytics">Analytics</option>
                            <option value="system">Sistema</option>
                            <option value="invitations">Inviti</option>
                            <option value="requests">Richieste</option>
                            <option value="notifications">Notifiche</option>
                        </select>
                        <small class="text-muted">Raggruppa i permessi per categoria</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrizione</label>
                        <textarea class="form-control" name="description" id="permissionDescription" rows="3"></textarea>
                        <small class="text-muted">Descrizione dettagliata del permesso</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary" id="permissionSubmitBtn">
                        <i class="ph ph-plus me-2"></i>
                        Crea Permesso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Permission Roles Modal -->
<div class="modal fade" id="permissionRolesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-users me-2"></i>
                    Ruoli con questo Permesso
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="permissionRolesContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
let currentPermissionId = null;

function showCreatePermissionModal() {
    $('#permissionModalTitle').html('<i class="ph ph-plus me-2"></i>Crea Nuovo Permesso');
    $('#permissionSubmitBtn').html('<i class="ph ph-plus me-2"></i>Crea Permesso');
    $('#permissionForm')[0].reset();
    $('#permissionId').val('');
    $('#permissionName').prop('readonly', false);
    $('#permissionModal').modal('show');
}

function editPermission(permissionId) {
    currentPermissionId = permissionId;
    fetch(`{{ route('permissions.permissions.index') }}?permission_id=${permissionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.permission) {
                $('#permissionModalTitle').html('<i class="ph ph-pencil me-2"></i>Modifica Permesso');
                $('#permissionSubmitBtn').html('<i class="ph ph-check me-2"></i>Aggiorna Permesso');
                $('#permissionId').val(data.permission.id);
                $('#permissionName').val(data.permission.name).prop('readonly', true);
                $('#permissionDisplayName').val(data.permission.display_name);
                $('#permissionGroup').val(data.permission.group);
                $('#permissionDescription').val(data.permission.description);
                $('#permissionModal').modal('show');
            }
        });
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
            fetch(`{{ route('permissions.permissions.delete', ['permission' => ':permissionId']) }}`.replace(':permissionId', permissionId), {
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

function showPermissionRoles(permissionId) {
    fetch(`{{ route('permissions.permissions.index') }}?permission_id=${permissionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.permission) {
                let content = '<div class="table-responsive"><table class="table">';
                content += '<thead><tr><th>Ruolo</th><th>Nome Visualizzato</th><th>Descrizione</th></tr></thead><tbody>';
                data.permission.roles.forEach(role => {
                    content += `<tr>
                        <td><code>${role.name}</code></td>
                        <td><strong>${role.display_name || role.name}</strong></td>
                        <td>${role.description || 'Nessuna descrizione'}</td>
                    </tr>`;
                });
                content += '</tbody></table></div>';
                if (data.permission.roles.length === 0) {
                    content = '<p class="text-muted">Nessun ruolo con questo permesso.</p>';
                }
                $('#permissionRolesContent').html(content);
                $('#permissionRolesModal').modal('show');
            }
        });
}

$('#permissionForm').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const permissionId = $('#permissionId').val();
            const url = permissionId ? `{{ route('permissions.permissions.update', ['permission' => ':permissionId']) }}`.replace(':permissionId', permissionId) : '{{ route("permissions.permissions.store") }}';
    const method = permissionId ? 'PUT' : 'POST';

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
    $('#permissionsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/it-IT.json'
        },
        order: [[2, 'asc'], [1, 'asc']],
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
