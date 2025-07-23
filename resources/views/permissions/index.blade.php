@extends('layout.master')

@section('title', 'Gestione Permessi - Slamin')

@section('css')
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
                <h4 class="mb-0 f-w-600">Pannello Gestione Permessi</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('permissions.roles') }}" class="btn btn-primary hover-effect">
                        <i class="ph ph-users me-2"></i>
                        Gestione Ruoli
                    </a>
                    <a href="{{ route('permissions.permissions') }}" class="btn btn-success hover-effect">
                        <i class="ph ph-shield-check me-2"></i>
                        Gestione Permessi
                    </a>
                    <a href="{{ route('permissions.users') }}" class="btn btn-warning hover-effect">
                        <i class="ph ph-user me-2"></i>
                        Gestione Utenti
                    </a>
                    <button class="btn btn-info hover-effect" onclick="showCreateRoleModal()">
                        <i class="ph ph-plus me-2"></i>
                        Nuovo Ruolo
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
                        <h3 class="text-primary mb-1 f-w-600">{{ $stats['total_roles'] }}</h3>
                        <p class="f-w-500 text-dark f-s-13 mb-1">{{ __('permissions.total_roles') }}</p>
                        <span class="badge bg-light-primary f-s-11">{{ __('permissions.role_management') }}</span>
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
                        <h3 class="text-success mb-1 f-w-600">{{ $stats['total_permissions'] }}</h3>
                        <p class="f-w-500 text-dark f-s-13 mb-1">{{ __('permissions.total_permissions') }}</p>
                        <span class="badge bg-light-success f-s-11">{{ __('permissions.role_security') }}</span>
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
                        <h3 class="text-warning mb-1 f-w-600">{{ $stats['total_users'] }}</h3>
                        <p class="f-w-500 text-dark f-s-13 mb-1">{{ __('permissions.total_users') }}</p>
                        <span class="badge bg-light-warning f-s-11">{{ __('permissions.role_users') }}</span>
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
                        <h3 class="text-info mb-1 f-w-600">{{ $stats['roles_with_permissions'] }}</h3>
                        <p class="f-w-500 text-dark f-s-13 mb-1">{{ __('permissions.roles_with_permissions') }}</p>
                        <span class="badge bg-light-info f-s-11">{{ __('permissions.role_statistics') }}</span>
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
                            Ruoli Recenti
                        </h5>
                        <a href="{{ route('permissions.roles') }}" class="btn btn-sm btn-primary hover-effect">
                            Vedi Tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="f-w-600">Ruolo</th>
                                    <th class="f-w-600 text-center">N° Permessi</th>
                                    <th class="f-w-600 text-center">N° Utenti</th>
                                    <th class="f-w-600 text-center">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles->take(5) as $role)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0 f-w-600">{{ $role->display_name ?? $role->name }}</h6>
                                            <small class="text-muted f-s-12">{{ $role->description ?? 'Nessuna descrizione' }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary f-s-12">{{ $role->permissions->count() }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary f-s-12">{{ $role->users->count() }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary hover-effect" onclick="editRole({{ $role->id }})" title="Modifica">
                                                <i class="ph ph-pencil f-s-14"></i>
                                            </button>
                                            @if($role->users->count() == 0)
                                            <button class="btn btn-outline-danger hover-effect" onclick="deleteRole({{ $role->id }})" title="Elimina">
                                                <i class="ph ph-trash f-s-14"></i>
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

        <!-- Recent Permissions -->
        <div class="col-lg-6">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 f-w-600 text-dark">
                            <i class="ph ph-shield-check me-2 text-success"></i>
                            Permessi Recenti
                        </h5>
                        <a href="{{ route('permissions.permissions') }}" class="btn btn-sm btn-success hover-effect">
                            Vedi Tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="f-w-600">Permesso</th>
                                    <th class="f-w-600 text-center">Gruppo</th>
                                    <th class="f-w-600 text-center">Ruoli</th>
                                    <th class="f-w-600 text-center">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions->take(5) as $permission)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0 f-w-600">{{ $permission->display_name ?? $permission->name }}</h6>
                                            <small class="text-muted f-s-12">{{ $permission->description ?? 'Nessuna descrizione' }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info f-s-12">{{ $permission->group ?? 'N/A' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary f-s-12">{{ $permission->roles->count() }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary hover-effect" onclick="editPermission({{ $permission->id }})" title="Modifica">
                                                <i class="ph ph-pencil f-s-14"></i>
                                            </button>
                                            @if($permission->roles->count() == 0)
                                            <button class="btn btn-outline-danger hover-effect" onclick="deletePermission({{ $permission->id }})" title="Elimina">
                                                <i class="ph ph-trash f-s-14"></i>
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
                        <a href="{{ route('permissions.users') }}" class="btn btn-sm btn-warning hover-effect">
                            Vedi Tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="f-w-600">Utente</th>
                                    <th class="f-w-600">Email</th>
                                    <th class="f-w-600 text-center">Ruoli</th>
                                    <th class="f-w-600 text-center">Stato</th>
                                    <th class="f-w-600 text-center">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users->take(5) as $user)
                                <tr>
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
                                        <a href="mailto:{{ $user->email }}" class="text-primary">{{ $user->email }}</a>
                                    </td>
                                    <td class="text-center">
                                        @foreach($user->roles->take(2) as $role)
                                        <span class="badge bg-primary me-1 f-s-11">{{ $role->display_name ?? $role->name }}</span>
                                        @endforeach
                                        @if($user->roles->count() > 2)
                                        <span class="badge bg-secondary f-s-11">+{{ $user->roles->count() - 2 }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($user->status === 'active')
                                        <span class="badge bg-success f-s-12">Attivo</span>
                                        @elseif($user->status === 'inactive')
                                        <span class="badge bg-warning f-s-12">Inattivo</span>
                                        @else
                                        <span class="badge bg-secondary f-s-12">{{ $user->status ?? 'N/A' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary hover-effect" onclick="editUserRoles({{ $user->id }})" title="Gestisci Ruoli">
                                                <i class="ph ph-users f-s-14"></i>
                                            </button>
                                            <button class="btn btn-outline-success hover-effect" onclick="editUserPermissions({{ $user->id }})" title="Gestisci Permessi">
                                                <i class="ph ph-shield-check f-s-14"></i>
                                            </button>
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

<!-- Create Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-light-primary">
                <h5 class="modal-title f-w-600">
                    <i class="ph ph-plus me-2"></i>
                    Crea Nuovo Ruolo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createRoleForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label f-w-600">Nome Ruolo *</label>
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
                        Crea Ruolo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
function showCreateRoleModal() {
    $('#createRoleModal').modal('show');
}

function editRole(roleId) {
    window.location.href = `{{ route('permissions.roles') }}?edit=${roleId}`;
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

function editPermission(permissionId) {
    window.location.href = `{{ route('permissions.permissions') }}?edit=${permissionId}`;
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

function editUserRoles(userId) {
                window.location.href = `{{ route('permissions.users') }}?edit_roles=${userId}`;
}

function editUserPermissions(userId) {
                window.location.href = `{{ route('permissions.users') }}?edit_permissions=${userId}`;
}

// Create Role Form Handler
$('#createRoleForm').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('{{ route("permissions.roles.store") }}', {
        method: 'POST',
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
