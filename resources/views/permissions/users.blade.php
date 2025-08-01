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
            <div class="card hover-effect">
                <div class="card-body bg-light-primary">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-0 f-w-600 text-primary">
                                <i class="ph ph-users me-2"></i>{{ __('permissions.users') ?? 'Gestione Utenti' }}
                                <span class="badge bg-primary ms-2">{{ $users->total() }} totali</span>
                            </h5>
                            <p class="text-muted mb-0 f-s-14">
                                {{ __('permissions.users_description') ?? 'Gestisci utenti e permessi' }} -
                                Mostrando {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} di {{ $users->total() }} utenti
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column flex-md-row gap-2">
                                <button class="btn btn-success" onclick="exportUsers()">
                                    <i class="ph ph-download me-2"></i>{{ __('permissions.export') }}
                                </button>
                                <button class="btn btn-info" onclick="exportAllUsers()">
                                    <i class="ph ph-download-simple me-2"></i>Esporta Tutti
                                </button>
                                <button class="btn btn-primary" onclick="showBulkAssignModal()">
                                    <i class="ph ph-users-plus me-2"></i>{{ __('permissions.bulk_assign') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label f-s-14 f-w-500">{{ __('permissions.search_users') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light-primary">
                                        <i class="ph ph-magnifying-glass"></i>
                                    </span>
                                    <input type="text" class="form-control" id="searchUsers" placeholder="{{ __('permissions.search_placeholder') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label f-s-14 f-w-500">{{ __('permissions.filter_by_role') }}</label>
                                <select class="form-select" id="filterRole">
                                    <option value="">{{ __('permissions.all_roles') }}</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->display_name ?? $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label f-s-14 f-w-500">{{ __('permissions.filter_by_status') }}</label>
                                <select class="form-select" id="filterStatus">
                                    <option value="">{{ __('permissions.all_status') }}</option>
                                    <option value="active">{{ __('permissions.status_active') }}</option>
                                    <option value="inactive">{{ __('permissions.status_inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label f-s-14 f-w-500">{{ __('permissions.items_per_page') }}</label>
                                <select class="form-select" id="itemsPerPage">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50" selected>50</option>
                                    <option value="100">100</option>
                                    <option value="all">Tutti</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table - Desktop -->
    <div class="row d-none d-lg-block">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-users me-2 text-primary"></i>
                        {{ __('permissions.system_users') }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="usersTable">
                            <thead class="bg-light-primary">
                                <tr>
                                    <th class="border-0">
                                        <div class="d-flex align-items-center">
                                            <i class="ph ph-user me-2"></i>{{ __('permissions.name') }}
                                        </div>
                                    </th>
                                    <th class="border-0">
                                        <i class="ph ph-envelope me-2"></i>{{ __('permissions.email') }}
                                    </th>
                                    <th class="border-0">
                                        <i class="ph ph-at me-2"></i>{{ __('permissions.nickname') }}
                                    </th>
                                    <th class="border-0">
                                        <i class="ph ph-users-three me-2"></i>{{ __('permissions.roles') }}
                                    </th>
                                    <th class="border-0">
                                        <i class="ph ph-shield me-2"></i>{{ __('permissions.permissions') }}
                                    </th>
                                    <th class="border-0">
                                        <i class="ph ph-circle me-2"></i>{{ __('permissions.status') }}
                                    </th>
                                    <th class="border-0 text-center">
                                        <i class="ph ph-gear me-2"></i>{{ __('permissions.actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr data-user-id="{{ $user->id }}" class="user-row">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light-primary h-40 w-40 d-flex-center b-r-50 position-relative overflow-hidden me-3">
                                                <img src="{{ asset('assets/images/avatar/' . ($user->id % 16 + 1) . '.png') }}" alt="{{ __('common.avatar') }}" class="img-fluid b-r-50">
                                            </div>
                                            <div>
                                                <h6 class="mb-0 f-w-600">{{ $user->name }}</h6>
                                                <small class="text-muted f-s-12">ID: {{ $user->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $user->email }}" class="text-decoration-none">{{ $user->email }}</a>
                                    </td>
                                    <td>
                                        @if($user->nickname)
                                            <span class="badge bg-light-danger text-danger">{{ $user->nickname }}</span>
                                        @else
                                            <span class="text-muted f-s-12">{{ __('permissions.no_nickname') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @forelse($user->roles as $role)
                                                <span class="f-s-12 text-primary">{{ $role->display_name ?? $role->name }}</span>
                                                @if(!$loop->last), @endif
                                            @empty
                                                <span class="text-muted f-s-12">{{ __('permissions.no_roles') }}</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @forelse($user->permissions as $permission)
                                                <span class="f-s-12 text-success">{{ $permission->display_name ?? $permission->name }}</span>
                                                @if(!$loop->last), @endif
                                            @empty
                                                <span class="text-muted f-s-12">{{ __('permissions.no_direct_permissions') }}</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }} f-s-12">
                                            {{ __('permissions.status_' . $user->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-light-primary icon-btn b-r-4" onclick="viewUserDetails({{ $user->id }})" title="{{ __('permissions.view_details') }}">
                                                <i class="ph ph-eye"></i>
                                            </button>
                                            <button class="btn btn-light-secondary icon-btn b-r-4" onclick="editUserRoles({{ $user->id }})" title="{{ __('permissions.edit_roles') }}">
                                                <i class="ph ph-user-gear"></i>
                                            </button>
                                            <button class="btn btn-light-info icon-btn b-r-4" onclick="editUserPermissions({{ $user->id }})" title="{{ __('permissions.edit_permissions') }}">
                                                <i class="ph ph-shield-check"></i>
                                            </button>
                                            @php
                                                $currentUser = auth()->user();
                                                $isSuperAdmin = $currentUser->hasRole('super-admin');
                                                $isOwnAccount = $currentUser->id === $user->id;
                                                $canDelete = (!$user->hasRole('admin') || $isSuperAdmin) && !$isOwnAccount;
                                            @endphp
                                            @if($canDelete)
                                            <button class="btn btn-light-danger icon-btn b-r-4" onclick="deleteUser({{ $user->id }})" title="{{ __('permissions.delete_user') }}">
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

    <!-- Users Cards - Mobile Only -->
    <div class="row d-lg-none">
        <div class="col-12">
            <h5 class="mb-3 f-w-600 text-primary">
                <i class="ph ph-user me-2"></i>{{ __('permissions.system_users') }}
            </h5>
        </div>
        @foreach($users as $user)
        <div class="col-12 col-md-6 mb-3">
            <div class="card hover-effect">
                <div class="card-header position-relative overflow-hidden bg-light-primary">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-light-primary h-40 w-40 d-flex-center b-r-50 position-relative overflow-hidden me-3">
                                <img src="{{ asset('assets/images/avatar/' . ($user->id % 16 + 1) . '.png') }}" alt="{{ __('common.avatar') }}" class="img-fluid b-r-50">
                            </div>
                            <div>
                                <h6 class="mb-0 f-w-600 text-primary">{{ $user->name }}</h6>
                                <small class="text-muted f-s-12">ID: {{ $user->id }}</small>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-light-primary btn-sm icon-btn b-r-4" type="button" data-bs-toggle="dropdown">
                                <i class="ph ph-dots-three-outline"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="viewUserDetails({{ $user->id }})">
                                    <i class="ph ph-eye me-2"></i>{{ __('permissions.view_details') }}
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="editUserRoles({{ $user->id }})">
                                    <i class="ph ph-user-gear me-2"></i>{{ __('permissions.edit_roles') }}
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="editUserPermissions({{ $user->id }})">
                                    <i class="ph ph-shield-check me-2"></i>{{ __('permissions.edit_permissions') }}
                                </a></li>
                                @php
                                    $currentUser = auth()->user();
                                    $isSuperAdmin = $currentUser->hasRole('super-admin');
                                    $isOwnAccount = $currentUser->id === $user->id;
                                    $canDelete = (!$user->hasRole('admin') || $isSuperAdmin) && !$isOwnAccount;
                                @endphp
                                @if($canDelete)
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteUser({{ $user->id }})">
                                    <i class="ph ph-trash me-2"></i>{{ __('permissions.delete_user') }}
                                </a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <i class="ph ph-envelope me-2 text-muted"></i>
                        <span class="f-s-14">{{ $user->email }}</span>
                    </div>
                    <div class="mb-2">
                        <i class="ph ph-at me-2 text-muted"></i>
                        @if($user->nickname)
                            <span class="badge bg-light-danger text-danger f-s-12">{{ $user->nickname }}</span>
                        @else
                            <span class="text-muted f-s-12">{{ __('permissions.no_nickname') }}</span>
                        @endif
                    </div>
                    <div class="mb-2">
                        <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }} f-s-12">
                            {{ __('permissions.status_' . $user->status) }}
                        </span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light-primary btn-sm flex-fill" onclick="viewUserDetails({{ $user->id }})">
                            <i class="ph ph-eye me-1"></i>{{ __('permissions.view') }}
                        </button>
                        <button class="btn btn-light-secondary btn-sm flex-fill" onclick="editUserRoles({{ $user->id }})">
                            <i class="ph ph-user-gear me-1"></i>{{ __('permissions.roles') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center mt-4">
                {{ $users->links() }}
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
                    Gestione {{ __('permissions.roles') }} {{ __('permissions.user') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="userRolesForm">
                @csrf
                <input type="hidden" name="user_id" id="userRolesUserId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('permissions.user') }}</label>
                        <input type="text" class="form-control" id="userRolesUserName" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('permissions.roles') }} Disponibili</label>
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
                        Salva {{ __('permissions.roles') }}
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
                    Gestione Permessi Diretti {{ __('permissions.user') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="userPermissionsForm">
                @csrf
                <input type="hidden" name="user_id" id="userPermissionsUserId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('permissions.user') }}</label>
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
                    Dettagli {{ __('permissions.user') }}
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
                    Assegnazione Massiva {{ __('permissions.roles') }}
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
                        <label class="form-label">{{ __('permissions.roles') }} da Assegnare</label>
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
                        Assegna {{ __('permissions.roles') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function editUserRoles(userId) {
    fetch(`{{ route('permissions.users.show', ['user' => ':userId']) }}`.replace(':userId', userId))
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
    fetch(`{{ route('permissions.users.show', ['user' => ':userId']) }}`.replace(':userId', userId))
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
    fetch(`{{ route('permissions.users.show', ['user' => ':userId']) }}`.replace(':userId', userId))
        .then(response => response.json())
        .then(data => {
            if (data.user) {
                let content = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informazioni {{ __('permissions.user') }}</h6>
                            <table class="table table-sm">
                                <tr><td><strong>Nome:</strong></td><td>${data.user.name}</td></tr>
                                <tr><td><strong>{{ __('permissions.email') }}:</strong></td><td>${data.user.email}</td></tr>
                                <tr><td><strong>Nickname:</strong></td><td>${data.user.nickname || 'N/A'}</td></tr>
                                <tr><td><strong>{{ __('invitations.status') }}:</strong></td><td>${data.user.status || 'N/A'}</td></tr>
                                <tr><td><strong>Registrato:</strong></td><td>${data.user.created_at}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ __('permissions.roles') }} Assegnati</h6>
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

    // Reinizializza Select2 quando il modal è visibile
    setTimeout(function() {
        if (typeof $.fn.select2 !== 'undefined') {
            $('#bulkUserSelect').select2({
                placeholder: 'Seleziona utenti...',
                allowClear: true,
                dropdownParent: $('#bulkAssignModal')
            });
        }
    }, 100);
}

function exportUsers() {
    window.location.href = '{{ route("permissions.users") }}?export=1';
}

function exportAllUsers() {
    window.location.href = '{{ route("permissions.users") }}?export_all=1';
}

$('#userRolesForm').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const userId = $('#userRolesUserId').val();

    // Debug: log dei dati del form
    console.log('Form data:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    console.log('User ID:', userId);

    // Debug: controlla i checkbox selezionati
    console.log('Selected checkboxes:');
    $('input[name="roles[]"]:checked').each(function() {
        console.log('Checked:', $(this).val());
    });

    fetch(`{{ route('permissions.users.roles', ['user' => ':userId']) }}`.replace(':userId', userId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            Swal.fire('Successo!', data.message, 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Errore!', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        Swal.fire('Errore!', 'Errore di connessione', 'error');
    });
});

$('#userPermissionsForm').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const userId = $('#userPermissionsUserId').val();

    fetch(`{{ route('permissions.users.permissions', ['user' => ':userId']) }}`.replace(':userId', userId), {
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

$(document).ready(function() {
    // Verifica che jQuery sia caricato
    if (typeof $ === 'undefined') {
        console.error('jQuery non è caricato!');
        return;
    }

    // Verifica che Bootstrap sia caricato
    if (typeof bootstrap === 'undefined') {
        console.warn('Bootstrap non è caricato. I modals potrebbero non funzionare.');
    }

    // Verifica che SweetAlert sia caricato
    if (typeof Swal === 'undefined') {
        console.warn('SweetAlert non è caricato. Le notifiche potrebbero non funzionare.');
    }

    // Verifica che Select2 sia caricato
    if (typeof $.fn.select2 === 'undefined') {
        console.warn('Select2 non è caricato. I dropdown potrebbero non funzionare correttamente.');
    }

    // Inizializza i filtri
    $('#searchUsers').on('input', filterUsers);
    $('#filterRole').on('change', filterUsers);
    $('#filterStatus').on('change', filterUsers);
    // Gestione cambio items per page
    $('#itemsPerPage').on('change', function() {
        const perPage = $(this).val();
        let url = '{{ route("permissions.users") }}';
        if (perPage === 'all') {
            url += '?per_page=all';
        } else {
            url += '?per_page=' + perPage;
        }
        window.location.href = url;
    });

    // Imposta il valore corretto del selettore basato sui parametri URL
    const urlParams = new URLSearchParams(window.location.search);
    const currentPerPage = urlParams.get('per_page') || '50';
    $('#itemsPerPage').val(currentPerPage);

    function filterUsers() {
        const searchTerm = $('#searchUsers').val().toLowerCase();
        const roleFilter = $('#filterRole').val();
        const statusFilter = $('#filterStatus').val();
        const perPage = $('#itemsPerPage').val();

        $('.user-row').each(function() {
            const $row = $(this);
            const userName = $row.find('h6').text().toLowerCase();
            const userEmail = $row.find('a[href^="mailto:"]').text().toLowerCase();
            const userRoles = $row.find('.badge.bg-primary').map(function() {
                return $(this).text().toLowerCase();
            }).get();
            const userStatus = $row.find('.badge.bg-success, .badge.bg-danger').text().toLowerCase();

            let showRow = true;

            // Search filter
            if (searchTerm && !userName.includes(searchTerm) && !userEmail.includes(searchTerm)) {
                showRow = false;
            }

            // Role filter
            if (roleFilter && !userRoles.includes(roleFilter.toLowerCase())) {
                showRow = false;
            }

            // Status filter
            if (statusFilter && !userStatus.includes(statusFilter.toLowerCase())) {
                showRow = false;
            }

            // Per page filter
            if (perPage === 'all') {
                showRow = true; // Always show all rows if "Tutti" is selected
            } else {
                const currentPage = $row.closest('.pagination').find('.page-item.active .page-link').text();
                const totalPages = $row.closest('.pagination').find('.page-item').length - 2; // Exclude prev/next and active page
                const currentItem = $row.closest('.pagination').find('.page-item.active .page-link').text();
                const currentItemIndex = parseInt(currentItem) - 1;

                if (perPage === '10') {
                    if (currentItemIndex < 10) {
                        showRow = true;
                    } else {
                        showRow = false;
                    }
                } else if (perPage === '25') {
                    if (currentItemIndex < 25) {
                        showRow = true;
                    } else {
                        showRow = false;
                    }
                } else if (perPage === '50') {
                    if (currentItemIndex < 50) {
                        showRow = true;
                    } else {
                        showRow = false;
                    }
                } else if (perPage === '100') {
                    if (currentItemIndex < 100) {
                        showRow = true;
                    } else {
                        showRow = false;
                    }
                }
            }

            $row.toggle(showRow);
        });

        // Update mobile cards visibility
        $('.d-lg-none .card').each(function() {
            const $card = $(this);
            const cardUserName = $card.find('h6').text().toLowerCase();
            const cardUserEmail = $card.find('.f-s-14').text().toLowerCase();
            const cardUserRoles = $card.find('.badge.bg-primary').map(function() {
                return $(this).text().toLowerCase();
            }).get();
            const cardUserStatus = $card.find('.badge.bg-success, .badge.bg-danger').text().toLowerCase();

            let showCard = true;

            if (searchTerm && !cardUserName.includes(searchTerm) && !cardUserEmail.includes(searchTerm)) {
                showCard = false;
            }

            if (roleFilter && !cardUserRoles.includes(roleFilter.toLowerCase())) {
                showCard = false;
            }

            if (statusFilter && !cardUserStatus.includes(statusFilter.toLowerCase())) {
                showCard = false;
            }

            // Per page filter for mobile cards
            if (perPage === 'all') {
                showCard = true; // Always show all cards if "Tutti" is selected
            } else {
                const currentPage = $card.closest('.pagination').find('.page-item.active .page-link').text();
                const totalPages = $card.closest('.pagination').find('.page-item').length - 2; // Exclude prev/next and active page
                const currentItem = $card.closest('.pagination').find('.page-item.active .page-link').text();
                const currentItemIndex = parseInt(currentItem) - 1;

                if (perPage === '10') {
                    if (currentItemIndex < 10) {
                        showCard = true;
                    } else {
                        showCard = false;
                    }
                } else if (perPage === '25') {
                    if (currentItemIndex < 25) {
                        showCard = true;
                    } else {
                        showCard = false;
                    }
                } else if (perPage === '50') {
                    if (currentItemIndex < 50) {
                        showCard = true;
                    } else {
                        showCard = false;
                    }
                } else if (perPage === '100') {
                    if (currentItemIndex < 100) {
                        showCard = true;
                    } else {
                        showCard = false;
                    }
                }
            }

            $card.closest('.col-12, .col-md-6').toggle(showCard);
        });
    }

    // Inizializza Select2 solo se disponibile
    if (typeof $.fn.select2 !== 'undefined') {
        $('#bulkUserSelect').select2({
            placeholder: 'Seleziona utenti...',
            allowClear: true
        });
    } else {
        console.warn('Select2 non è caricato. I dropdown potrebbero non funzionare correttamente.');
    }
});

/**
 * Elimina un utente
 */
function deleteUser(userId) {
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Questa operazione eliminerà definitivamente l'utente e tutti i suoi dati associati (video, foto, commenti, like, etc.)!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sì, elimina!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra loader
            Swal.fire({
                title: 'Eliminazione in corso...',
                text: 'Sto eliminando l\'utente e tutti i suoi dati...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Chiamata AJAX per eliminare l'utente
            fetch(`{{ route('permissions.users.delete', ['user' => ':userId']) }}`.replace(':userId', userId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
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
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Errore!', 'Si è verificato un errore durante l\'eliminazione.', 'error');
            });
        }
    });
}

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
@endpush
