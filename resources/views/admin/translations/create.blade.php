@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="ph-duotone ph-plus text-primary me-2"></i>
                        {{ __('admin_general.add_language') }}
                    </h1>
                    <p class="text-muted mb-0">{{ __('admin_general.add_language_description') }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.translations.index') }}" class="btn btn-outline-secondary">
                        <i class="ph-duotone ph-arrow-left me-1"></i>
                        {{ __('admin_general.back_to_languages') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-globe me-2"></i>
                        {{ __('admin_general.language_details') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.translations.store') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="language_code" class="form-label f-s-14">
                                    {{ __('admin_general.language_code') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('language_code') is-invalid @enderror"
                                       id="language_code"
                                       name="language_code"
                                       value="{{ old('language_code') }}"
                                       placeholder="es. en, fr, de, es"
                                       maxlength="2"
                                       required>
                                @error('language_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('admin_general.language_code_help') }}</div>
                            </div>

                            <div class="col-md-6">
                                <label for="language_name" class="form-label f-s-14">
                                    {{ __('admin_general.language_name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('language_name') is-invalid @enderror"
                                       id="language_name"
                                       name="language_name"
                                       value="{{ old('language_name') }}"
                                       placeholder="es. English, Français, Deutsch, Español"
                                       maxlength="50"
                                       required>
                                @error('language_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('admin_general.language_name_help') }}</div>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="alert alert-info mt-4">
                            <div class="d-flex">
                                <i class="ph-duotone ph-info me-3 f-s-20 text-info"></i>
                                <div>
                                    <h6 class="alert-heading mb-2">{{ __('admin_general.what_happens_next') }}</h6>
                                    <ul class="mb-0 f-s-14">
                                        <li>{{ __('admin_general.language_creation_step1') }}</li>
                                        <li>{{ __('admin_general.language_creation_step2') }}</li>
                                        <li>{{ __('admin_general.language_creation_step3') }}</li>
                                        <li>{{ __('admin_general.language_creation_step4') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Available Languages -->
                        <div class="mt-4">
                            <h6 class="mb-3">{{ __('admin_general.available_language_codes') }}</h6>
                            <div class="row g-2">
                                @php
                                    $commonLanguages = [
                                        'en' => 'English',
                                        'fr' => 'Français',
                                        'de' => 'Deutsch',
                                        'es' => 'Español',
                                        'pt' => 'Português',
                                        'ru' => 'Русский',
                                        'zh' => '中文',
                                        'ja' => '日本語',
                                        'ko' => '한국어',
                                        'ar' => 'العربية'
                                    ];
                                @endphp
                                @foreach($commonLanguages as $code => $name)
                                    <div class="col-md-3 col-sm-4 col-6">
                                        <button type="button"
                                                class="btn btn-outline-primary btn-sm w-100 language-suggestion"
                                                data-code="{{ $code }}"
                                                data-name="{{ $name }}">
                                            <strong>{{ strtoupper($code) }}</strong><br>
                                            <small>{{ $name }}</small>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.translations.index') }}" class="btn btn-outline-secondary">
                                {{ __('admin_general.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ph-duotone ph-plus me-1"></i>
                                {{ __('admin_general.create_language') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-fill form when clicking on language suggestions
document.querySelectorAll('.language-suggestion').forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('language_code').value = this.dataset.code;
        document.getElementById('language_name').value = this.dataset.name;

        // Visual feedback
        this.classList.remove('btn-outline-primary');
        this.classList.add('btn-primary');

        // Reset other buttons
        document.querySelectorAll('.language-suggestion').forEach(btn => {
            if (btn !== this) {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            }
        });
    });
});

// Auto-format language code
document.getElementById('language_code').addEventListener('input', function() {
    this.value = this.value.toLowerCase().replace(/[^a-z]/g, '');
});

// Auto-format language name
document.getElementById('language_name').addEventListener('input', function() {
    this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);
});
</script>
@endsection
