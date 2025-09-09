@extends('layout.master')

@section('title', __('poems.edit.title'))

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ __('poems.edit.title') }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">{{ __('common.home') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('poems.index') }}">{{ __('poems.title') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('poems.show', $poem->slug) }}">{{ $poem->title }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('common.edit') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="ph ph-pencil text-primary me-2"></i>
                        {{ __('poems.edit.subtitle') }}
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('poems.update', $poem) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Titolo -->
                            <div class="col-12 mb-3">
                                <label for="title" class="form-label">{{ __('poems.fields.title') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title', $poem->title) }}"
                                       placeholder="{{ __('poems.create.title_placeholder') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Categoria e Tipo -->
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">{{ __('poems.fields.category') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                    <option value="">{{ __('common.select') }}</option>
                                    @foreach($categories as $key => $category)
                                        <option value="{{ $key }}" {{ old('category', $poem->category) == $key ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="poem_type" class="form-label">{{ __('poems.fields.poem_type') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('poem_type') is-invalid @enderror" id="poem_type" name="poem_type" required>
                                    <option value="">{{ __('common.select') }}</option>
                                    @foreach($poemTypes as $key => $type)
                                        <option value="{{ $key }}" {{ old('poem_type', $poem->poem_type) == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('poem_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- {{ __('common.language_selector') }} e Tags -->
                            <div class="col-md-6 mb-3">
                                <label for="language" class="form-label">{{ __('poems.fields.language') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('language') is-invalid @enderror" id="language" name="language" required>
                                    <option value="">{{ __('common.select') }}</option>
                                    @foreach($languages as $key => $language)
                                        <option value="{{ $key }}" {{ old('language', $poem->language) == $key ? 'selected' : '' }}>
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
                                       id="tags" name="tags" value="{{ old('tags', is_array($poem->tags) ? implode(', ', $poem->tags) : $poem->tags) }}"
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
                                          placeholder="{{ __('poems.create.description_placeholder') }}">{{ old('description', $poem->description) }}</textarea>
                                <small class="form-text text-muted">{{ __('poems.create.description_help') }}</small>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contenuto -->
                            <div class="col-12 mb-3">
                                <label for="content" class="form-label">{{ __('poems.fields.content') }} <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content') is-invalid @enderror"
                                          id="content" name="content" rows="12"
                                          placeholder="{{ __('poems.create.content_placeholder') }}" required>{{ old('content', $poem->content) }}</textarea>
                                <small class="form-text text-muted">{{ __('poems.create.content_help') }}</small>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- {{ __('common.thumbnail') }} -->
                            <div class="col-12 mb-3">
                                <label for="thumbnail" class="form-label">{{ __('poems.fields.thumbnail') }}</label>

                                @if($poem->thumbnail)
                                    <div class="mb-3">
                                        <img src="{{ $poem->thumbnail }}" class="img-thumbnail" width="200" alt="{{ __('common.current_thumbnail') }}">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="remove_thumbnail" name="remove_thumbnail" value="1">
                                            <label class="form-check-label" for="remove_thumbnail">
                                                {{ __('poems.edit.remove_thumbnail') }}
                                            </label>
                                        </div>
                                    </div>
                                @endif

                                <input type="file" class="form-control @error('thumbnail') is-invalid @enderror"
                                       id="thumbnail" name="thumbnail" accept="image/*">
                                <small class="form-text text-muted">{{ __('poems.create.thumbnail_help') }}</small>
                                @error('thumbnail')
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
                                                           {{ old('is_public', $poem->is_public) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_public">
                                                        {{ __('poems.fields.is_public') }}
                                                    </label>
                                                </div>
                                                <small class="form-text text-muted">{{ __('poems.create.public_help') }}</small>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_draft" name="is_draft" value="1"
                                                           {{ old('is_draft', $poem->is_draft) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_draft">
                                                        {{ __('poems.fields.is_draft') }}
                                                    </label>
                                                </div>
                                                <small class="form-text text-muted">{{ __('poems.create.draft_help') }}</small>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="translation_available" name="translation_available" value="1"
                                                           {{ old('translation_available', $poem->translation_available) ? 'checked' : '' }}>
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
                                                           value="{{ old('translation_price', $poem->translation_price) }}" min="0" step="0.01">
                                                </div>
                                                @error('translation_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informazioni di stato -->
                            <div class="col-12">
                                <div class="card card-light">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="ph ph-info text-warning me-2"></i>
                                            {{ __('poems.edit.status_info') }}
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1">
                                                    <strong>{{ __('poems.fields.created_at') }}:</strong>
                                                    {{ $poem->created_at->format('d/m/Y H:i') }}
                                                </p>
                                                @if($poem->published_at)
                                                    <p class="mb-1">
                                                        <strong>{{ __('poems.fields.published_at') }}:</strong>
                                                        {{ $poem->published_at->format('d/m/Y H:i') }}
                                                    </p>
                                                @endif
                                                @if($poem->draft_saved_at)
                                                    <p class="mb-1">
                                                        <strong>{{ __('poems.fields.draft_saved_at') }}:</strong>
                                                        {{ $poem->draft_saved_at->format('d/m/Y H:i') }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1">
                                                    <strong>{{ __('poems.fields.view_count') }}:</strong>
                                                    {{ number_format($poem->views_count) }}
                                                </p>
                                                <p class="mb-1">
                                                    <strong>{{ __('poems.fields.like_count') }}:</strong>
                                                    {{ number_format($poem->likes_count) }}
                                                </p>
                                                <p class="mb-1">
                                                    <strong>{{ __('poems.fields.comment_count') }}:</strong>
                                                    {{ number_format($poem->comments_count) }}
                                                </p>
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
                                    <a href="{{ route('poems.show', $poem) }}" class="btn btn-light">
                                        <i class="ph ph-arrow-left me-2"></i>
                                        {{ __('common.cancel') }}
                                    </a>

                                    <div>
                                        <button type="submit" name="action" value="draft" class="btn btn-outline-primary me-2">
                                            <i class="ph ph-floppy-disk me-2"></i>
                                            {{ __('poems.edit.save_draft') }}
                                        </button>

                                        <button type="submit" name="action" value="publish" class="btn btn-primary">
                                            <i class="ph ph-check me-2"></i>
                                            {{ __('poems.edit.update') }}
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

    // Gestione rimozione thumbnail
    const removeThumbnailCheckbox = document.getElementById('remove_thumbnail');
    const thumbnailInput = document.getElementById('thumbnail');

    if (removeThumbnailCheckbox && thumbnailInput) {
        removeThumbnailCheckbox.addEventListener('change', function() {
            if (this.checked) {
                thumbnailInput.disabled = true;
            } else {
                thumbnailInput.disabled = false;
            }
        });
    }

    // Preview del nuovo thumbnail
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
