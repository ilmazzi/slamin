@extends('layout.master')

@section('title', __('languages.add_language'))

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/flag-icons-master/flag-icon.css') }}">
<style>
.custom-language-dropdown {
    position: relative;
}

.custom-language-dropdown .dropdown-toggle {
    display: flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    background-color: #fff;
    cursor: pointer;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.custom-language-dropdown .dropdown-toggle:hover {
    border-color: #86b7fe;
}

.custom-language-dropdown .dropdown-toggle:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.custom-language-dropdown .flag-icon {
    width: 20px;
    height: 15px;
    display: inline-block;
}

.custom-language-dropdown .dropdown-menu {
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
}

.custom-language-dropdown .language-option {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
}

.custom-language-dropdown .language-option:hover {
    background-color: #f8f9fa;
}
</style>
@endsection

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-plus me-2 text-primary"></i>
                        {{ __('languages.add_language') }}
                    </h4>
                    <a href="{{ route('profile.languages.index') }}" class="btn btn-light">
                        <i class="ph-duotone ph-arrow-left me-2"></i>
                        {{ __('common.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('profile.languages.store') }}" method="POST">
                        @csrf

                        <!-- Lingua -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('languages.language') }} <span class="text-danger">*</span></label>
                            <div class="custom-language-dropdown">
                                <div class="dropdown-toggle @error('language_name') is-invalid @enderror" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="flag-icon flag-icon-ita me-2"></i>
                                    <span id="selectedLanguageName">{{ __('languages.select_language') }}</span>
                                    <i class="bi bi-chevron-down ms-auto"></i>
                                </div>
                    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                        @foreach($worldLanguages as $code => $name)
                            <li>
                                <a class="dropdown-item language-option" href="#" data-code="{{ $code }}" data-name="{{ $name }}">
                                    <i class="flag-icon flag-icon-{{ \App\Providers\LanguageServiceProvider::getFlagCode($code) }} me-2"></i>
                                    {{ $name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                                <input type="hidden" name="language_name" id="language_name" value="{{ old('language_name') }}">
                                <input type="hidden" name="language_code" id="language_code" value="{{ old('language_code') }}">
                            </div>
                            @error('language_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('language_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('languages.select_language_help') }}</div>
                        </div>

                        <!-- Tipo di Competenza -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('languages.competence_type') }} <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="type"
                                               id="type_native"
                                               value="native"
                                               {{ old('type') === 'native' ? 'checked' : '' }}
                                               onchange="toggleLevelField()">
                                        <label class="form-check-label" for="type_native">
                                            <i class="ph-duotone ph-house me-1 text-success"></i>
                                            {{ __('languages.native') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="type"
                                               id="type_spoken"
                                               value="spoken"
                                               {{ old('type') === 'spoken' ? 'checked' : '' }}
                                               onchange="toggleLevelField()">
                                        <label class="form-check-label" for="type_spoken">
                                            <i class="ph-duotone ph-microphone me-1 text-info"></i>
                                            {{ __('languages.spoken') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="type"
                                               id="type_written"
                                               value="written"
                                               {{ old('type') === 'written' ? 'checked' : '' }}
                                               onchange="toggleLevelField()">
                                        <label class="form-check-label" for="type_written">
                                            <i class="ph-duotone ph-pencil me-1 text-warning"></i>
                                            {{ __('languages.written') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Livello (solo per parlato/scritto) -->
                        <div class="mb-3" id="level_field" style="display: none;">
                            <label class="form-label">{{ __('languages.level') }} <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="level"
                                               id="level_excellent"
                                               value="excellent"
                                               {{ old('level') === 'excellent' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="level_excellent">
                                            <i class="ph-duotone ph-star me-1 text-success"></i>
                                            {{ __('languages.excellent') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="level"
                                               id="level_good"
                                               value="good"
                                               {{ old('level') === 'good' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="level_good">
                                            <i class="ph-duotone ph-star-half me-1 text-warning"></i>
                                            {{ __('languages.good') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="level"
                                               id="level_poor"
                                               value="poor"
                                               {{ old('level') === 'poor' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="level_poor">
                                            <i class="ph-duotone ph-star me-1 text-danger"></i>
                                            {{ __('languages.poor') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('level')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pulsanti -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph-duotone ph-check me-2"></i>
                                {{ __('languages.add_language') }}
                            </button>
                            <a href="{{ route('profile.languages.index') }}" class="btn btn-light">
                                {{ __('common.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="col-12 col-md-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-info me-2 text-info"></i>
                        {{ __('languages.help_title') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-success">
                            <i class="ph-duotone ph-house me-1"></i>
                            {{ __('languages.native') }}
                        </h6>
                        <p class="text-muted small mb-0">{{ __('languages.native_description') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-info">
                            <i class="ph-duotone ph-microphone me-1"></i>
                            {{ __('languages.spoken') }}
                        </h6>
                        <p class="text-muted small mb-0">{{ __('languages.spoken_description') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-warning">
                            <i class="ph-duotone ph-pencil me-1"></i>
                            {{ __('languages.written') }}
                        </h6>
                        <p class="text-muted small mb-0">{{ __('languages.written_description') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Gestione selezione lingua
$(document).ready(function() {
    $('.language-option').on('click', function(e) {
        e.preventDefault();

        const code = $(this).data('code');
        const name = $(this).data('name');
        const flagIcon = $(this).find('.flag-icon').attr('class');

        // Aggiorna i campi nascosti
        $('#language_code').val(code);
        $('#language_name').val(name);

        // Aggiorna il display del dropdown
        $('#selectedLanguageName').text(name);
        $('#languageDropdown .flag-icon').attr('class', flagIcon);

        // Chiudi il dropdown
        $('.dropdown-menu').removeClass('show');
    });
});

// Toggle del campo livello
function toggleLevelField() {
    const nativeRadio = document.getElementById('type_native');
    const levelField = document.getElementById('level_field');

    if (nativeRadio.checked) {
        levelField.style.display = 'none';
        // Deseleziona tutti i livelli
        document.querySelectorAll('input[name="level"]').forEach(radio => {
            radio.checked = false;
        });
    } else {
        levelField.style.display = 'block';
    }
}

// Inizializza lo stato del campo livello
document.addEventListener('DOMContentLoaded', function() {
    toggleLevelField();
});
</script>
@endpush
@endsection
