@extends('layout.master')
@section('title', 'Poetry Slam - Users Management')

@section('main-content')
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">👥 Users Management</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('poetry.test.dashboard') }}" class="f-s-14 f-w-500">
                            <span><i class="ph-duotone ph-test-tube f-s-16"></i> Test System</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">Users</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Users Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>👥 All Users ({{ $users->total() }} total)</h5>
                        <p class="text-muted mb-0">Manage user roles and permissions for the Poetry Slam social network</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Roles</th>
                                        <th>Status</th>
                                        <th>Permissions Count</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $user->name }}</strong><br>
                                                    <small class="text-muted">{{ $user->email }}</small><br>
                                                    <small class="text-muted">Joined: {{ $user->created_at->format('M d, Y') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @forelse($user->roles as $role)
                                                    <span class="badge bg-primary me-1 mb-1">{{ $role->name }}</span>
                                                @empty
                                                    <span class="badge bg-secondary">No roles</span>
                                                @endforelse
                                            </td>
                                            <td>
                                                @if($user->isActive())
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $user->getAllPermissions()->count() }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <!-- Quick login as this user -->
                                                    <form method="POST" action="{{ route('poetry.test.quick-login') }}" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="email" value="{{ $user->email }}">
                                                        <button type="submit" class="btn btn-sm btn-outline-primary"
                                                                data-bs-toggle="tooltip" title="Login as this user">
                                                            🔑 Login As
                                                        </button>
                                                    </form>

                                                    <!-- Role assignment dropdown -->
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                            Assign Role
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @foreach($roles as $role)
                                                                <li>
                                                                    <form method="POST" action="{{ route('poetry.test.assign-role', $user) }}" class="d-inline">
                                                                        @csrf
                                                                        <input type="hidden" name="role" value="{{ $role->name }}">
                                                                        <button type="submit" class="dropdown-item">
                                                                            @if($user->hasRole($role->name))
                                                                                ✅ {{ ucfirst($role->name) }}
                                                                            @else
                                                                                {{ ucfirst($role->name) }}
                                                                            @endif
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles Summary -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>🎭 Available Roles</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($roles as $role)
                                @php
                                    $userCount = \App\Models\User::role($role->name)->count();
                                    $permissionCount = $role->permissions->count();
                                @endphp
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">
                                                @switch($role->name)
                                                    @case('admin')
                                                        👑 Admin
                                                        @break
                                                    @case('moderator')
                                                        🛡️ Moderator
                                                        @break
                                                    @case('poet')
                                                        🎤 Poet
                                                        @break
                                                    @case('organizer')
                                                        🎪 Organizer
                                                        @break
                                                    @case('judge')
                                                        👨‍⚖️ Judge
                                                        @break
                                                    @case('venue_owner')
                                                        🏛️ Venue Owner
                                                        @break
                                                    @case('technician')
                                                        🔧 Technician
                                                        @break
                                                    @case('audience')
                                                        👥 Audience
                                                        @break
                                                    @default
                                                        {{ ucfirst($role->name) }}
                                                @endswitch
                                            </h6>
                                            <p class="card-text">
                                                <small class="text-muted">{{ $userCount }} users</small><br>
                                                <small class="text-muted">{{ $permissionCount }} permissions</small>
                                            </p>
                                            <div class="mt-2">
                                                <span class="badge bg-light text-dark">{{ $userCount }} users</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6>⚡ Quick Actions</h6>
                        <div class="btn-group">
                            <a href="{{ route('poetry.test.dashboard') }}" class="btn btn-primary">
                                🏠 Dashboard
                            </a>
                            <a href="{{ route('poetry.test.permissions') }}" class="btn btn-success">
                                🔐 Test Permissions
                            </a>
                            <a href="{{ route('poetry.test.login') }}" class="btn btn-warning">
                                👥 Login Page
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endsection
