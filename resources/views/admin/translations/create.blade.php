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
                        {{ __('admin.add_translation') }}
                    </h1>
                    <p class="text-muted mb-0">{{ __('admin.add_translation_description') }}</p>
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
                        {{ __('admin.create_translation') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form id="translationForm">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="group_name" class="form-label">
                                    {{ __('admin.group_name') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="group_name" name="group_name" class="form-select @error('group_name') is-invalid @enderror" required>
                                    <option value="">{{ __('admin.select_group') }}</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group }}" {{ old('group_name') == $group ? 'selected' : '' }}>
                                            {{ $group }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">{{ __('admin.group_name_help') }}</div>
                                @error('group_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="locale" class="form-label">
                                    {{ __('admin.locale') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="locale" name="locale" class="form-select @error('locale') is-invalid @enderror" required>
                                    <option value="">{{ __('admin.select_locale') }}</option>
                                    @foreach($locales as $localeCode)
                                        <option value="{{ $localeCode }}" {{ old('locale') == $localeCode ? 'selected' : '' }}>
                                            {{ strtoupper($localeCode) }} - {{ __('admin.language_' . $localeCode) ?: ucfirst($localeCode) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('locale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="key_name" class="form-label">
                                    {{ __('admin.key_name') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="key_name" name="key_name"
                                       class="form-control @error('key_name') is-invalid @enderror"
                                       value="{{ old('key_name') }}"
                                       placeholder="{{ __('admin.key_name_placeholder') }}"
                                       maxlength="100" required>
                                <div class="form-text">{{ __('admin.key_name_help') }}</div>
                                @error('key_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="value" class="form-label">
                                    {{ __('admin.translation_value') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea id="value" name="value"
                                          class="form-control @error('value') is-invalid @enderror"
                                          rows="4"
                                          placeholder="{{ __('admin.translation_value_placeholder') }}"
                                          required>{{ old('value') }}</textarea>
                                <div class="form-text">{{ __('admin.translation_value_help') }}</div>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph-duotone ph-check me-1"></i>
                                {{ __('admin.create_translation') }}
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
                            {{ __('admin.tip_group_naming') }}
                        </h6>
                        <p class="text-muted f-s-14 mb-0">{{ __('admin.tip_group_naming_description') }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="mb-2">
                            <i class="ph-duotone ph-check-circle text-success me-1"></i>
                            {{ __('admin.tip_key_naming') }}
                        </h6>
                        <p class="text-muted f-s-14 mb-0">{{ __('admin.tip_key_naming_description') }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="mb-2">
                            <i class="ph-duotone ph-check-circle text-success me-1"></i>
                            {{ __('admin.tip_translation_quality') }}
                        </h6>
                        <p class="text-muted f-s-14 mb-0">{{ __('admin.tip_translation_quality_description') }}</p>
                    </div>

                    <div class="mb-0">
                        <h6 class="mb-2">
                            <i class="ph-duotone ph-check-circle text-success me-1"></i>
                            {{ __('admin.tip_consistency') }}
                        </h6>
                        <p class="text-muted f-s-14 mb-0">{{ __('admin.tip_consistency_description') }}</p>
                    </div>
                </div>
            </div>

            <!-- Esempi di Gruppi -->
            <div class="card hover-effect mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="ph-duotone ph-list-bullets me-2"></i>
                        {{ __('admin.group_examples') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0 f-s-14">
                                <li><code>admin</code> - {{ __('admin.admin_interface') }}</li>
                                <li><code>auth</code> - {{ __('admin.authentication') }}</li>
                                <li><code>common</code> - {{ __('admin.common_texts') }}</li>
                                <li><code>dashboard</code> - {{ __('admin.dashboard') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0 f-s-14">
                                <li><code>events</code> - {{ __('admin.events') }}</li>
                                <li><code>profile</code> - {{ __('admin.profile') }}</li>
                                <li><code>videos</code> - {{ __('admin.videos') }}</li>
                                <li><code>chat</code> - {{ __('admin.chat') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('translationForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('{{ route("admin.translations.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '{{ __('admin.success') }}',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '{{ route("admin.translations.index") }}';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: '{{ __('admin.error') }}',
                text: data.message,
                confirmButtonText: '{{ __('admin.ok') }}'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: '{{ __('admin.error') }}',
            text: '{{ __('admin.unknown_error') }}',
            confirmButtonText: '{{ __('admin.ok') }}'
        });
    });
});

// Auto-genera chiave basata su gruppo e valore
document.getElementById('value').addEventListener('input', function() {
    const group = document.getElementById('group_name').value;
    const value = this.value.trim();

    if (group && value && !document.getElementById('key_name').value) {
        // Genera una chiave automatica
        const key = value.toLowerCase()
            .replace(/[^a-z0-9\s]/g, '')
            .replace(/\s+/g, '_')
            .substring(0, 50);

        if (key) {
            document.getElementById('key_name').value = key;
        }
    }
});
</script>
@endsection
