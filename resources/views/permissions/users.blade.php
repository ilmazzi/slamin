@extends('layout.master')

@section('title', 'Gestione Utenti - Slamin')

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
            <h4 class="main-title">{{ __('permissions.users') }}</h4>
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
                    <a href="#" class="f-s-14 f-w-500">{{ __('permissions.users') }}</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Header Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Utenti del Sistema</h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-success" onclick="exportUsers()">
                        <i class="ph ph-download me-2"></i>
                        Esporta
                    </button>
                    <button class="btn btn-primary" onclick="showBulkAssignModal()">
                        <i class="ph ph-users-plus me-2"></i>
                        Assegnazione Massiva
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-user me-2 text-warning"></i>
                        Utenti del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="usersTable">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Nickname</th>
                                    <th>Ruoli</th>
                                    <th>Permessi Diretti</th>
                                    <th>Stato</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr data-user-id="{{ $user->id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="bg-light-primary h-40 w-40 d-flex-center b-r-50 position-relative overflow-hidden me-3">
                                                    <img src="{{ asset('assets/images/avatar/' . ($user->id % 16 + 1) . '.png') }}" alt="Avatar" class="img-fluid b-r-50">
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 f-w-600">{{ $user->name }}</h6>
                                                <small class="text-muted f-s-12">ID: {{ $user->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                    </td>
                                    <td>
                                        <code>{{ $user->nickname ?? 'N/A' }}</code>
                                    </td>
                                    <td>
                                        @foreach($user->roles as $role)
                                        <span class="badge bg-primary me-1">{{ $role->display_name ?? $role->name }}</span>
                                        @endforeach
                                        @if($user->roles->count() == 0)
                                        <span class="text-muted">Nessun ruolo</span>
                                        @endif
                                    </td>
                                    <td>
                                        @foreach($user->permissions as $permission)
                                        <span class="badge bg-success me-1">{{ $permission->display_name ?? $permission->name }}</span>
                                        @endforeach
                                        @if($user->permissions->count() == 0)
                                        <span class="text-muted">Nessun permesso diretto</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->status === 'active')
                                        <span class="badge bg-success">Attivo</span>
                                        @elseif($user->status === 'inactive')
                                        <span class="badge bg-warning">Inattivo</span>
                                        @else
                                        <span class="badge bg-secondary">{{ $user->status ?? 'N/A' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="editUserRoles({{ $user->id }})" title="Gestisci Ruoli">
                                                <i class="ph ph-users"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="editUserPermissions({{ $user->id }})" title="Gestisci Permessi">
                                                <i class="ph ph-shield-check"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="viewUserDetails({{ $user->id }})" title="Dettagli">
                                                <i class="ph ph-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Roles Modal -->
<div class="modal fade" id="userRolesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-users me-2"></i>
                    Gestione Ruoli Utente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="userRolesForm">
                <input type="hidden" name="user_id" id="userRolesUserId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Utente</label>
                        <input type="text" class="form-control" id="userRolesUserName" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ruoli Disponibili</label>
                        <div class="row">
                            @foreach($roles as $role)
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]"
                                           value="{{ $role->name }}" id="role_{{ $role->id }}">
                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                        <strong>{{ $role->display_name ?? $role->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $role->description ?? 'Nessuna descrizione' }}</small>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-check me-2"></i>
                        Salva Ruoli
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Permissions Modal -->
<div class="modal fade" id="userPermissionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-shield-check me-2"></i>
                    Gestione Permessi Diretti Utente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="userPermissionsForm">
                <input type="hidden" name="user_id" id="userPermissionsUserId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Utente</label>
                        <input type="text" class="form-control" id="userPermissionsUserName" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Permessi Diretti</label>
                        <div class="alert alert-info">
                            <i class="ph ph-info me-2"></i>
                            I permessi diretti hanno precedenza sui permessi dei ruoli. Usa con cautela.
                        </div>
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
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-check me-2"></i>
                        Salva Permessi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Details Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-eye me-2"></i>
                    Dettagli Utente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetailsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Bulk Assign Modal -->
<div class="modal fade" id="bulkAssignModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-users-plus me-2"></i>
                    Assegnazione Massiva Ruoli
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkAssignForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Seleziona Utenti</label>
                        <select class="form-select" name="user_ids[]" multiple id="bulkUserSelect">
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ruoli da Assegnare</label>
                        <div class="row">
                            @foreach($roles as $role)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="bulk_roles[]"
                                           value="{{ $role->name }}" id="bulk_role_{{ $role->id }}">
                                    <label class="form-check-label" for="bulk_role_{{ $role->id }}">
                                        <strong>{{ $role->display_name ?? $role->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $role->description ?? 'Nessuna descrizione' }}</small>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-check me-2"></i>
                        Assegna Ruoli
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
function editUserRoles(userId) {
    fetch(`{{ route('permissions.users.index') }}?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.user) {
                $('#userRolesUserId').val(data.user.id);
                $('#userRolesUserName').val(data.user.name + ' (' + data.user.email + ')');
                $('input[name="roles[]"]').prop('checked', false);
                data.user.roles.forEach(role => {
                    $(`input[value="${role.name}"]`).prop('checked', true);
                });
                $('#userRolesModal').modal('show');
            }
        });
}

function editUserPermissions(userId) {
    fetch(`{{ route('permissions.users.index') }}?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.user) {
                $('#userPermissionsUserId').val(data.user.id);
                $('#userPermissionsUserName').val(data.user.name + ' (' + data.user.email + ')');
                $('input[name="permissions[]"]').prop('checked', false);
                data.user.permissions.forEach(permission => {
                    $(`input[value="${permission.name}"]`).prop('checked', true);
                });
                $('#userPermissionsModal').modal('show');
            }
        });
}

function viewUserDetails(userId) {
    fetch(`{{ route('permissions.users.index') }}?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.user) {
                let content = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informazioni Utente</h6>
                            <table class="table table-sm">
                                <tr><td><strong>Nome:</strong></td><td>${data.user.name}</td></tr>
                                <tr><td><strong>Email:</strong></td><td>${data.user.email}</td></tr>
                                <tr><td><strong>Nickname:</strong></td><td>${data.user.nickname || 'N/A'}</td></tr>
                                <tr><td><strong>Stato:</strong></td><td>${data.user.status || 'N/A'}</td></tr>
                                <tr><td><strong>Registrato:</strong></td><td>${data.user.created_at}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Ruoli Assegnati</h6>
                            <div class="mb-3">
                                ${data.user.roles.map(role => `<span class="badge bg-primary me-1">${role.display_name || role.name}</span>`).join('')}
                            </div>
                            <h6>Permessi Diretti</h6>
                            <div class="mb-3">
                                ${data.user.permissions.map(permission => `<span class="badge bg-success me-1">${permission.display_name || permission.name}</span>`).join('')}
                            </div>
                        </div>
                    </div>
                `;
                $('#userDetailsContent').html(content);
                $('#userDetailsModal').modal('show');
            }
        });
}

function showBulkAssignModal() {
    $('#bulkAssignModal').modal('show');
}

function exportUsers() {
    window.location.href = '{{ route("permissions.users.index") }}?export=1';
}

$('#userRolesForm').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const userId = $('#userRolesUserId').val();

    fetch(`{{ route('permissions.users.roles', ['user' => '']) }}/${userId}`, {
        method: 'PUT',
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

$('#userPermissionsForm').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const userId = $('#userPermissionsUserId').val();

    fetch(`{{ route('permissions.users.permissions', ['user' => '']) }}/${userId}`, {
        method: 'PUT',
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
    $('#usersTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/it-IT.json'
        },
        order: [[0, 'asc']],
        pageLength: 25
    });

    $('#bulkUserSelect').select2({
        placeholder: 'Seleziona utenti...',
        allowClear: true
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
