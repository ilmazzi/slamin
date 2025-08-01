@extends('layout.master')

@section('title', __('poems.create.title'))

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb start -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">{{ __('poems.create.title') }}</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('home') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> {{ __('common.home') }}
                        </span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('poems.index') }}" class="f-s-14 f-w-500">
                        <span>{{ __('poems.title') }}</span>
                    </a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">{{ __('poems.create.title') }}</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Breadcrumb end -->

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="ph ph-pen-nib text-primary me-2"></i>
                        {{ __('poems.create.subtitle') }}
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('poems.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Titolo -->
                            <div class="col-12 mb-3">
                                <label for="title" class="form-label">{{ __('poems.fields.title') }}</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title') }}"
                                       placeholder="{{ __('poems.create.title_placeholder') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Testo (Contenuto) - OBBLIGATORIO -->
                            <div class="col-12 mb-3">
                                <label for="content" class="form-label">{{ __('poems.fields.content') }} <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content') is-invalid @enderror"
                                          id="content" name="content" rows="12"
                                          placeholder="{{ __('poems.create.content_placeholder') }}" required>{{ old('content') }}</textarea>
                                <small class="form-text text-muted">{{ __('poems.create.content_help') }}</small>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Immagine (Thumbnail) -->
                            <div class="col-12 mb-3">
                                <label for="thumbnail" class="form-label">{{ __('poems.fields.thumbnail') }}</label>
                                <input type="file" class="form-control @error('thumbnail') is-invalid @enderror"
                                       id="thumbnail" name="thumbnail" accept="image/*">
                                <small class="form-text text-muted">{{ __('poems.create.thumbnail_help') }}</small>
                                @error('thumbnail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Categoria e Tipologia -->
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">{{ __('poems.fields.category') }}</label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category">
                                    <option value="">{{ __('common.select') }}</option>
                                    @foreach($categories as $key => $category)
                                        <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="poem_type" class="form-label">{{ __('poems.fields.poem_type') }}</label>
                                <select class="form-select @error('poem_type') is-invalid @enderror" id="poem_type" name="poem_type">
                                    <option value="">{{ __('common.select') }}</option>
                                    @foreach($poemTypes as $key => $type)
                                        <option value="{{ $key }}" {{ old('poem_type') == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('poem_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lingua e Tags -->
                            <div class="col-md-6 mb-3">
                                <label for="language" class="form-label">{{ __('poems.fields.language') }}</label>
                                <select class="form-select @error('language') is-invalid @enderror" id="language" name="language">
                                    <option value="">{{ __('common.select') }}</option>
                                    @foreach($languages as $key => $language)
                                        <option value="{{ $key }}" {{ old('language') == $key ? 'selected' : '' }}>
                                            {{ $language }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tags" class="form-label">{{ __('poems.fields.tags') }}</label>
                                <input type="text" class="form-control @error('tags') is-invalid @enderror"
                                       id="tags" name="tags" value="{{ old('tags') }}"
                                       placeholder="{{ __('poems.create.tags_placeholder') }}">
                                <small class="form-text text-muted">{{ __('poems.create.tags_help') }}</small>
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Descrizione -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">{{ __('poems.fields.description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3"
                                          placeholder="{{ __('poems.create.description_placeholder') }}">{{ old('description') }}</textarea>
                                <small class="form-text text-muted">{{ __('poems.create.description_help') }}</small>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Opzioni di pubblicazione -->
                            <div class="col-12">
                                <div class="card card-light">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="ph ph-gear text-info me-2"></i>
                                            {{ __('poems.create.publication_options') }}
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1"
                                                           {{ old('is_public', true) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_public">
                                                        {{ __('poems.fields.is_public') }}
                                                    </label>
                                                </div>
                                                <small class="form-text text-muted">{{ __('poems.create.public_help') }}</small>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_draft" name="is_draft" value="1"
                                                           {{ old('is_draft') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_draft">
                                                        {{ __('poems.fields.is_draft') }}
                                                    </label>
                                                </div>
                                                <small class="form-text text-muted">{{ __('poems.create.draft_help') }}</small>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="translation_available" name="translation_available" value="1"
                                                           {{ old('translation_available') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="translation_available">
                                                        {{ __('poems.fields.translation_available') }}
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="translation_price" class="form-label">{{ __('poems.fields.translation_price') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">€</span>
                                                    <input type="number" class="form-control @error('translation_price') is-invalid @enderror"
                                                           id="translation_price" name="translation_price"
                                                           value="{{ old('translation_price') }}" min="0" step="0.01">
                                                </div>
                                                @error('translation_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pulsanti -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('poems.index') }}" class="btn btn-light">
                                        <i class="ph ph-arrow-left me-2"></i>
                                        {{ __('common.cancel') }}
                                    </a>

                                    <div>
                                        <button type="submit" name="action" value="draft" class="btn btn-outline-primary me-2">
                                            <i class="ph ph-floppy-disk me-2"></i>
                                            {{ __('poems.create.save_draft') }}
                                        </button>

                                        <button type="submit" name="action" value="publish" class="btn btn-primary">
                                            <i class="ph ph-paper-plane me-2"></i>
                                            {{ __('poems.create.publish') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestione del draft
    const draftCheckbox = document.getElementById('is_draft');
    const publicCheckbox = document.getElementById('is_public');

    if (draftCheckbox) {
        draftCheckbox.addEventListener('change', function() {
            if (this.checked) {
                publicCheckbox.checked = false;
            }
        });
    }

    if (publicCheckbox) {
        publicCheckbox.addEventListener('change', function() {
            if (this.checked) {
                draftCheckbox.checked = false;
            }
        });
    }

    // Preview del thumbnail
    const thumbnailInput = document.getElementById('thumbnail');
    if (thumbnailInput) {
        thumbnailInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Qui puoi aggiungere una preview del thumbnail se necessario
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush
@endsection
