@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8">
            <div class="card">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold">{{ __('auth.reset_password') }}</h4>
                        <p class="text-muted">{{ __('auth.reset_password_description') }}</p>
                    </div>

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

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('auth.email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph ph-envelope"></i>
                                </span>
                                <input type="email"
                                       class="form-control"
                                       id="email"
                                       value="{{ $email }}"
                                       disabled>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('auth.new_password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph ph-lock"></i>
                                </span>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder="{{ __('auth.new_password_placeholder') }}"
                                       required
                                       autofocus>
                                <button class="btn btn-light-secondary" type="button" id="togglePassword">
                                    <i class="ph ph-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted">{{ __('auth.password_requirements') }}</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('auth.confirm_password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph ph-lock"></i>
                                </span>
                                <input type="password"
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder="{{ __('auth.confirm_password_placeholder') }}"
                                       required>
                                <button class="btn btn-light-secondary" type="button" id="togglePasswordConfirmation">
                                    <i class="ph ph-eye" id="togglePasswordConfirmationIcon"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph ph-check-circle me-2"></i>
                                {{ __('auth.reset_password') }}
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    const togglePasswordIcon = document.getElementById('togglePasswordIcon');
    const togglePasswordConfirmationIcon = document.getElementById('togglePasswordConfirmationIcon');

    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        togglePasswordIcon.className = type === 'password' ? 'ph ph-eye' : 'ph ph-eye-slash';
    });

    togglePasswordConfirmation.addEventListener('click', function() {
        const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmation.setAttribute('type', type);
        togglePasswordConfirmationIcon.className = type === 'password' ? 'ph ph-eye' : 'ph ph-eye-slash';
    });
});
</script>
@endsection
