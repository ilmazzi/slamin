<!DOCTYPE html>
<html lang="en">

@section('title', 'Poetry Slam - Test Login')
@include('layout.head')
@include('layout.css')

<body class="sign-in-bg">
<div class="app-wrapper d-block">
    <div class="">
        <!-- Body main section starts -->
        <div class="container main-container">
            <div class="row main-content-box">
                <div class="col-lg-7 image-content-box d-none d-lg-block">
                    <div class="form-container">
                        <div class="signup-content mt-4">
                            <span>
                              @include('poetry_slam_test.logo_helper', ['type' => 'main', 'class' => 'w-200'])
                            </span>
                            <h3 class="text-white mt-3">🎭 Poetry Slam Social Network</h3>
                            <p class="text-white-50">Test System - Roles & Permissions</p>
                        </div>

                        <div class="signup-bg-img">
                            <img alt="" class="img-fluid" src="{{ asset('assets/images/login/01.png') }}">
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 form-content-box">
                    <div class="form-container">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="login-card">
                                        <div class="text-center">
                                            <h3>🧪 Test Users</h3>
                                            <h6 class="f-w-400 text-secondary f-s-16">Click to login as different users</h6>
                                        </div>

                                        @if(session('success'))
                                            <div class="alert alert-success">{{ session('success') }}</div>
                                        @endif

                                        @if(session('error'))
                                            <div class="alert alert-danger">{{ session('error') }}</div>
                                        @endif

                                        <!-- Test Users Grid -->
                                        <div class="row mt-4">
                                            @foreach($testUsers as $testUser)
                                                <div class="col-md-6 mb-3">
                                                    <form method="POST" action="{{ route('poetry.test.quick-login') }}">
                                                        @csrf
                                                        <input type="hidden" name="email" value="{{ $testUser['email'] }}">

                                                        <button type="submit" class="btn btn-outline-primary w-100 p-3">
                                                            <div class="text-start">
                                                                <strong>{{ ucfirst($testUser['role']) }}</strong><br>
                                                                <small class="text-muted">{{ $testUser['email'] }}</small><br>
                                                                <small class="badge bg-light-primary">{{ $testUser['password'] }}</small>
                                                            </div>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- System Info -->
                                        <div class="mt-4">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h6>🎯 Test Features:</h6>
                                                    <ul class="list-unstyled mb-0">
                                                        <li>• <strong>Multi-role system</strong> - Users can have multiple roles</li>
                                                        <li>• <strong>Granular permissions</strong> - 34 different permissions</li>
                                                        <li>• <strong>Real-time testing</strong> - See permissions in action</li>
                                                        <li>• <strong>Role hierarchy</strong> - Admin > Moderator > Organizer</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Quick Links -->
                                        <div class="mt-3">
                                            <div class="text-center">
                                                <a href="{{ route('poetry.test.dashboard') }}" class="btn btn-success me-2">
                                                    🏠 Dashboard
                                                </a>
                                                <a href="{{ route('poetry.test.signup') }}" class="btn btn-primary me-2">
                                                    🚀 Registrati
                                                </a>
                                                <a href="{{ url('/') }}" class="btn btn-secondary">
                                                    ← Back to Main Site
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Sign Up Invitation -->
                                        <div class="mt-4">
                                            <div class="border-top pt-3">
                                                <div class="text-center">
                                                    <p class="text-muted">Non hai un account?</p>
                                                    <a href="{{ route('poetry.test.signup') }}" class="btn btn-outline-primary">
                                                        ✨ Crea un account con multi-ruolo
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layout.script')
</body>
</html>
