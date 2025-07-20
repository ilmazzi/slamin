@extends('layout.master')
@section('title', 'Slamin - Test Dashboard')
@section('css')
    <!-- apexcharts css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/apexcharts/apexcharts.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">🎭 Slamin Test Dashboard</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="#" class="f-s-14 f-w-500">
                            <span><i class="ph-duotone ph-test-tube f-s-16"></i> Test System</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">Dashboard</a>
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

        <!-- Current User Info -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="text-white mb-0">Welcome, {{ auth()->user()->getDisplayName() ?? 'Guest' }}!</h5>
                                <p class="text-white-50 mb-2">{{ auth()->user()->email ?? '' }}</p>
                                @if(auth()->check())
                                    <div class="mb-2">
                                        <strong>Your Roles:</strong>
                                        @forelse($userRoles as $role)
                                            <span class="badge bg-light text-dark me-1">{{ ucfirst($role) }}</span>
                                        @empty
                                            <span class="badge bg-warning">No roles assigned</span>
                                        @endforelse
                                    </div>
                                    <div>
                                        <strong>Status:</strong>
                                        @if(auth()->user()->isActive())
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4 text-end">
                                <!-- Language Switcher -->
                                <div class="dropdown d-inline-block me-2">
                                    <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                        🌍 {{ strtoupper(app()->getLocale()) }}
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ url()->current() }}?lang=it">🇮🇹 Italiano</a>
                                        <a class="dropdown-item" href="{{ url()->current() }}?lang=en">🇬🇧 English</a>
                                        <a class="dropdown-item" href="{{ url()->current() }}?lang=fr">🇫🇷 Français</a>
                                        <a class="dropdown-item" href="{{ url()->current() }}?lang=es">🇪🇸 Español</a>
                                        <a class="dropdown-item" href="{{ url()->current() }}?lang=de">🇩🇪 Deutsch</a>
                                    </div>
                                </div>

                                <div class="btn-group">
                                    <a href="{{ route('poetry.test.permissions') }}" class="btn btn-light btn-sm">
                                        🔐 Test Permissions
                                    </a>
                                    <a href="{{ route('poetry.test.users') }}" class="btn btn-light btn-sm">
                                        👥 Users
                                    </a>
                                    <form method="POST" action="{{ route('poetry.test.logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Statistics -->
        <div class="row">
            <div class="col-6 col-md-3 col-lg-3">
                <div class="card">
                    <span class="bg-primary h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                        <i class="ph ph-users f-s-24"></i>
                    </span>
                    <div class="card-body eshop-cards">
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h3 class="text-primary mb-0">{{ $totalUsers }}</h3>
                            <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">Total Users</p>
                            <span class="badge bg-light-primary">Poetry Slam</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 col-lg-3">
                <div class="card">
                    <span class="bg-success h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                        <i class="ph ph-crown f-s-24"></i>
                    </span>
                    <div class="card-body eshop-cards">
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h3 class="text-success mb-0">{{ $totalRoles }}</h3>
                            <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">Roles</p>
                            <span class="badge bg-light-success">Active</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 col-lg-3">
                <div class="card">
                    <span class="bg-warning h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                        <i class="ph ph-lock f-s-24"></i>
                    </span>
                    <div class="card-body eshop-cards">
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h3 class="text-warning mb-0">{{ $totalPermissions }}</h3>
                            <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">Permissions</p>
                            <span class="badge bg-light-warning">Granular</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 col-lg-3">
                <div class="card">
                    <span class="bg-info h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                        <i class="ph ph-check-circle f-s-24"></i>
                    </span>
                    <div class="card-body eshop-cards">
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h3 class="text-info mb-0">{{ count($userPermissions) }}</h3>
                            <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">Your Permissions</p>
                            <span class="badge bg-light-info">Personal</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Two Columns: Roles & Users -->
        <div class="row">
            <!-- Available Roles -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>🎭 Available Roles</h5>
                    </div>
                    <div class="card-body">
                        @foreach($allRoles as $role)
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                                <div>
                                    <h6 class="mb-1">{{ ucfirst($role->name) }}</h6>
                                    <small class="text-muted">{{ $role->permissions->count() }} permissions</small>
                                </div>
                                <div>
                                    @if(auth()->check() && auth()->user()->hasRole($role->name))
                                        <span class="badge bg-success">You have this</span>
                                    @else
                                        <span class="badge bg-light">{{ $role->permissions->count() }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>👥 Recent Users</h5>
                    </div>
                    <div class="card-body">
                        @foreach($recentUsers as $user)
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                                <div>
                                    <h6 class="mb-1">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                                <div>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                    @endforeach
                                    @if($user->roles->isEmpty())
                                        <span class="badge bg-secondary">No roles</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Test Users -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>🧪 Quick Login Test Users</h5>
                        <p class="text-muted mb-0">Test the system by logging in as different users</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php
                                $testUsers = [
                                    'admin@poetryslam.com' => ['role' => 'Admin', 'color' => 'danger'],
                                    'poet@poetryslam.com' => ['role' => 'Poet', 'color' => 'primary'],
                                    'organizer@poetryslam.com' => ['role' => 'Organizer', 'color' => 'success'],
                                    'judge@poetryslam.com' => ['role' => 'Judge', 'color' => 'warning'],
                                    'venue@poetryslam.com' => ['role' => 'Venue Owner', 'color' => 'info'],
                                    'audience@poetryslam.com' => ['role' => 'Audience', 'color' => 'secondary'],
                                ];
                            @endphp

                            @foreach($testUsers as $email => $info)
                                <div class="col-md-4 col-lg-2 mb-2">
                                    <form method="POST" action="{{ route('poetry.test.quick-login') }}">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ $email }}">
                                        <button type="submit" class="btn btn-{{ $info['color'] }} btn-sm w-100">
                                            {{ $info['role'] }}
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
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
