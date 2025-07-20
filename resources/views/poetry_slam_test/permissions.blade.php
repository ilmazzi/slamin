@extends('layout.master')
@section('title', 'Poetry Slam - Test Permissions')
@section('css')
    <style>
        .permission-badge {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .permission-badge.has-access {
            background-color: #28a745 !important;
            color: white;
        }
        .permission-badge.no-access {
            background-color: #dc3545 !important;
            color: white;
        }
        .permission-test-result {
            display: none;
        }
    </style>
@endsection

@section('main-content')
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">🔐 Permission Testing System</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('poetry.test.dashboard') }}" class="f-s-14 f-w-500">
                            <span><i class="ph-duotone ph-test-tube f-s-16"></i> Test System</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">Permissions</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->

        <!-- User Info Bar -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="text-white mb-1">Testing as: {{ $user->getDisplayName() ?? 'Guest' }}</h5>
                                <p class="text-white-50 mb-0">
                                    Roles:
                                    @if($user)
                                        @forelse($user->getDisplayRoles() as $role)
                                            <span class="badge bg-light text-dark me-1">{{ ucfirst($role) }}</span>
                                        @empty
                                            <span class="badge bg-warning">No roles</span>
                                        @endforelse
                                    @else
                                        <span class="badge bg-danger">Not logged in</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('poetry.test.dashboard') }}" class="btn btn-light btn-sm">
                                    ← Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permission Test Results (AJAX) -->
        <div class="row permission-test-result">
            <div class="col-12">
                <div class="alert alert-info" id="test-result">
                    <strong>Test Result:</strong> <span id="test-permission-name"></span><br>
                    <strong>User:</strong> <span id="test-user-name"></span><br>
                    <strong>Has Access:</strong> <span id="test-has-access"></span><br>
                    <strong>User Roles:</strong> <span id="test-user-roles"></span>
                </div>
            </div>
        </div>

        <!-- Permissions by Module -->
        @if($user)
            @foreach($allPermissions as $module => $permissions)
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    @switch($module)
                                        @case('profile')
                                            👤 Profile & Content Management
                                            @break
                                        @case('events')
                                            🎪 Events Management
                                            @break
                                        @case('votes')
                                            🗳️ Voting & Social
                                            @break
                                        @case('gigs')
                                            💼 Gigs Management
                                            @break
                                        @case('venues')
                                            🏛️ Venues Management
                                            @break
                                        @case('stats')
                                            📊 Statistics & Analytics
                                            @break
                                        @case('admin')
                                            👑 Administration
                                            @break
                                        @case('users')
                                            👥 User Management
                                            @break
                                        @case('system')
                                            ⚙️ System Settings
                                            @break
                                        @case('comments')
                                            💬 Comments Management
                                            @break
                                        @case('content')
                                            📝 Content Management
                                            @break
                                        @case('follows')
                                            🤝 Social Following
                                            @break
                                        @default
                                            🔧 {{ ucfirst($module) }} Module
                                    @endswitch
                                </h5>
                                <p class="text-muted mb-0">{{ $permissions->count() }} permissions in this module</p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($permissions as $permission)
                                        @php
                                            $hasAccess = $user->can($permission->name);
                                            $badgeClass = $hasAccess ? 'bg-success has-access' : 'bg-danger no-access';
                                            $icon = $hasAccess ? '✅' : '❌';
                                        @endphp
                                        <div class="col-md-6 col-lg-4 mb-2">
                                            <span class="badge {{ $badgeClass }} permission-badge w-100 p-2 text-start"
                                                  data-permission="{{ $permission->name }}"
                                                  data-bs-toggle="tooltip"
                                                  title="Click to test this permission">
                                                {{ $icon }} {{ $permission->name }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning">
                        <h5>⚠️ Not Logged In</h5>
                        <p>You need to be logged in to test permissions.</p>
                        <a href="{{ route('poetry.test.login') }}" class="btn btn-primary">
                            Go to Login Page
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Permission Summary -->
        @if($user)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>📋 Permission Summary</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $totalPermissions = $allPermissions->flatten()->count();
                                $userPermissions = $user->getAllPermissions();
                                $hasPermissions = $userPermissions->count();
                                $percentage = $totalPermissions > 0 ? round(($hasPermissions / $totalPermissions) * 100, 1) : 0;
                            @endphp

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h3 class="text-success">{{ $hasPermissions }}</h3>
                                        <p class="text-muted">Permissions You Have</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h3 class="text-primary">{{ $totalPermissions }}</h3>
                                        <p class="text-muted">Total Permissions</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h3 class="text-info">{{ $percentage }}%</h3>
                                        <p class="text-muted">Access Level</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h3 class="text-warning">{{ $user->roles->count() }}</h3>
                                        <p class="text-muted">Active Roles</p>
                                    </div>
                                </div>
                            </div>

                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Permission testing AJAX
            document.querySelectorAll('.permission-badge').forEach(function(badge) {
                badge.addEventListener('click', function() {
                    var permission = this.getAttribute('data-permission');
                    testPermission(permission);
                });
            });

            function testPermission(permission) {
                fetch('{{ route("poetry.test.test-permission") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        permission: permission
                    })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('test-permission-name').textContent = data.permission;
                    document.getElementById('test-user-name').textContent = data.user;
                    document.getElementById('test-has-access').innerHTML = data.has_access ?
                        '<span class="badge bg-success">✅ Yes</span>' :
                        '<span class="badge bg-danger">❌ No</span>';
                    document.getElementById('test-user-roles').textContent = data.roles.join(', ');

                    // Show result
                    document.querySelector('.permission-test-result').style.display = 'block';

                    // Scroll to result
                    document.querySelector('.permission-test-result').scrollIntoView({
                        behavior: 'smooth'
                    });
                })
                .catch(error => {
                    console.error('Error testing permission:', error);
                });
            }
        });
    </script>
@endsection
