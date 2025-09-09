@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8">
            <div class="card">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold">{{ __('auth.forgot_password') }}</h4>
                        <p class="text-muted">{{ __('auth.forgot_password_description') }}</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ph ph-check-circle me-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ph ph-warning-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('auth.email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph ph-envelope"></i>
                                </span>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="{{ __('auth.email_placeholder') }}"
                                       required
                                       autofocus>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph ph-paper-plane-tilt me-2"></i>
                                {{ __('auth.send_reset_link') }}
                            </button>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                <i class="ph ph-arrow-left me-1"></i>
                                {{ __('auth.back_to_login') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
