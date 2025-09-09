@extends('layout-master')

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                            <i class="ph-duotone ph-house me-1"></i>
                            {{ __('admin.dashboard') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.translations.index') }}" class="text-decoration-none">
                            <i class="ph-duotone ph-translate me-1"></i>
                            {{ __('admin.translations') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="ph-duotone ph-plus me-1"></i>
                        {{ __('admin.add_language') }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="ph-duotone ph-plus text-primary me-2"></i>
                        {{ __('admin.add_language') }}
                    </h1>
                    <p class="text-muted mb-0">{{ __('admin.new_language_note') }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.translations.index') }}" class="btn btn-outline-secondary">
                        <i class="ph-duotone ph-arrow-left me-1"></i>
                        {{ __('admin.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-globe me-2"></i>
                        {{ __('admin.create_language') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.translations.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="language_code" class="form-label">
                                {{ __('admin.language_code') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('language_code') is-invalid @enderror"
                                   id="language_code"
                                   name="language_code"
                                   value="{{ old('language_code') }}"
                                   placeholder="es, fr, de, en"
                                   maxlength="2"
                                   required>
                            <div class="form-text">{{ __('admin.language_code_help') }}</div>
                            @error('language_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="language_name" class="form-label">
                                {{ __('admin.language_name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('language_name') is-invalid @enderror"
                                   id="language_name"
                                   name="language_name"
                                   value="{{ old('language_name') }}"
                                   placeholder="Español, Français, Deutsch, English"
                                   maxlength="50"
                                   required>
                            <div class="form-text">{{ __('admin.language_name_help') }}</div>
                            @error('language_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Information Card -->
                        <div class="card card-light-info mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="h-40 w-40 d-flex-center rounded-circle bg-light-info">
                                            <i class="ph-duotone ph-info f-s-16 text-info"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-2">{{ __('admin.information') }}</h6>
                                        <p class="text-muted f-s-14 mb-0">{{ __('admin.new_language_note') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Language Examples -->
                        <div class="card card-light-secondary mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="ph-duotone ph-list-bullets me-2"></i>
                                    Esempi di Codici Lingua
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled mb-0">
                                            <li><strong>es</strong> - Español</li>
                                            <li><strong>fr</strong> - Français</li>
                                            <li><strong>de</strong> - Deutsch</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled mb-0">
                                            <li><strong>en</strong> - English</li>
                                            <li><strong>pt</strong> - Português</li>
                                            <li><strong>ru</strong> - Русский</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph-duotone ph-check me-1"></i>
                                {{ __('admin.create_language') }}
                            </button>
                            <a href="{{ route('admin.translations.index') }}" class="btn btn-outline-secondary">
                                <i class="ph-duotone ph-x me-1"></i>
                                {{ __('admin.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4 col-lg-6">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-lightbulb me-2"></i>
                        {{ __('admin.tips') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="mb-2">
                            <i class="ph-duotone ph-check-circle text-success me-1"></i>
                            {{ __('admin.tip_use_reference') }}
                        </h6>
                        <p class="text-muted f-s-14 mb-0">{{ __('admin.tip_use_reference') }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="mb-2">
                            <i class="ph-duotone ph-check-circle text-success me-1"></i>
                            {{ __('admin.tip_maintain_length') }}
                        </h6>
                        <p class="text-muted f-s-14 mb-0">{{ __('admin.tip_maintain_length') }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="mb-2">
                            <i class="ph-duotone ph-check-circle text-success me-1"></i>
                            {{ __('admin.tip_check_grammar') }}
                        </h6>
                        <p class="text-muted f-s-14 mb-0">{{ __('admin.tip_check_grammar') }}</p>
                    </div>

                    <div class="mb-0">
                        <h6 class="mb-2">
                            <i class="ph-duotone ph-check-circle text-success me-1"></i>
                            {{ __('admin.tip_save_often') }}
                        </h6>
                        <p class="text-muted f-s-14 mb-0">{{ __('admin.tip_save_often') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-format language code
document.getElementById('language_code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toLowerCase().replace(/[^a-z]/g, '');
});

// Auto-suggest language name based on code
const languageNames = {
    'es': 'Español',
    'fr': 'Français',
    'de': 'Deutsch',
    'en': 'English',
    'pt': 'Português',
    'ru': 'Русский',
    'it': 'Italiano',
    'nl': 'Nederlands',
    'pl': 'Polski',
    'sv': 'Svenska',
    'da': 'Dansk',
    'no': 'Norsk',
    'fi': 'Suomi',
    'cs': 'Čeština',
    'sk': 'Slovenčina',
    'hu': 'Magyar',
    'ro': 'Română',
    'bg': 'Български',
    'hr': 'Hrvatski',
    'sl': 'Slovenščina',
    'et': 'Eesti',
    'lv': 'Latviešu',
    'lt': 'Lietuvių',
    'el': 'Ελληνικά',
    'tr': 'Türkçe',
    'ar': 'العربية',
    'he': 'עברית',
    'ja': '日本語',
    'ko': '한국어',
    'zh': '中文',
    'hi': 'हिन्दी',
    'th': 'ไทย',
    'vi': 'Tiếng Việt',
    'id': 'Bahasa Indonesia',
    'ms': 'Bahasa Melayu',
    'tl': 'Filipino'
};

document.getElementById('language_code').addEventListener('blur', function(e) {
    const code = e.target.value;
    const nameField = document.getElementById('language_name');

    if (code && languageNames[code] && !nameField.value) {
        nameField.value = languageNames[code];
    }
});
</script>
@endsection
