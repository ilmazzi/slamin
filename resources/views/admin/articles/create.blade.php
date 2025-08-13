@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ __('articles.create_new_article') }}</h4>
                        <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left"></i> {{ __('articles.back_to_dashboard') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" id="articleForm">
                        @csrf

                        <div class="row">
                            <div class="col-lg-8">
                                <!-- Titolo -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">{{ __('articles.title') }} *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Excerpt -->
                                <div class="mb-3">
                                    <label for="excerpt" class="form-label">{{ __('articles.excerpt') }}</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror"
                                              id="excerpt" name="excerpt" rows="3">{{ old('excerpt') }}</textarea>
                                    <div class="form-text">{{ __('articles.excerpt_help') }}</div>
                                    @error('excerpt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Contenuto -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">{{ __('articles.content') }} *</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                              id="content" name="content" rows="15">{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <!-- Categoria -->
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">{{ __('articles.category') }}</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror"
                                            id="category_id" name="category_id">
                                        <option value="">{{ __('articles.select_category') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tag -->
                                <div class="mb-3">
                                    <label for="tag_ids" class="form-label">{{ __('articles.tags') }}</label>
                                    <select class="form-select @error('tag_ids') is-invalid @enderror"
                                            id="tag_ids" name="tag_ids[]" multiple>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tag_ids', [])) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tag_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Immagine in evidenza -->
                                <div class="mb-3">
                                    <label for="featured_image" class="form-label">{{ __('articles.featured_image') }}</label>
                                                <input type="file" class="form-control @error('featured_image') is-invalid @enderror"
                id="featured_image" name="featured_image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    <div class="form-text">
                                        {{ __('articles.image_help') }}<br>
                                        {{ __('articles.max_size') }}: 2MB<br>
                                        {{ __('articles.formats') }}: JPG, PNG, GIF, WebP
                                    </div>
                                    @error('featured_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Anteprima immagine -->
                                <div class="mb-3" id="imagePreview" style="display: none;">
                                    <img id="previewImg" src="" alt="Preview" class="img-fluid rounded">
                                </div>

                                <!-- Stato articolo -->
                                <div class="mb-3">
                                    <label for="status" class="form-label">{{ __('articles.article_status') }}</label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status" name="status">
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>{{ __('articles.draft') }}</option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>{{ __('articles.published') }}</option>
                                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>{{ __('articles.archived') }}</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- In evidenza -->
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="featured">
                                            {{ __('articles.featured') }}
                                        </label>
                                    </div>
                                </div>

                                <!-- SEO -->
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">{{ __('articles.seo_settings') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="meta_title" class="form-label">{{ __('articles.meta_title') }}</label>
                                            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                                            <div class="form-text">{{ __('articles.meta_title_help') }}</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="meta_description" class="form-label">{{ __('articles.meta_description') }}</label>
                                            <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ old('meta_description') }}</textarea>
                                            <div class="form-text">{{ __('articles.meta_description_help') }}</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="meta_keywords" class="form-label">{{ __('articles.meta_keywords') }}</label>
                                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}">
                                            <div class="form-text">{{ __('articles.meta_keywords_help') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="submit" name="action" value="draft" class="btn btn-outline-secondary">
                                        <i class="ti ti-save"></i> {{ __('articles.save_draft') }}
                                    </button>
                                    <button type="submit" name="action" value="publish" class="btn btn-primary">
                                        <i class="ti ti-check"></i> {{ __('articles.publish_now') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview immagine
    const featuredImage = document.getElementById('featured_image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    featuredImage.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
        }
    });

    // Gestione form submit
    const form = document.getElementById('articleForm');
    form.addEventListener('submit', function(e) {
        const action = e.submitter.value;
        if (action === 'draft') {
            document.getElementById('status').value = 'draft';
        } else if (action === 'publish') {
            document.getElementById('status').value = 'published';
        }
    });
});
</script>
@endpush
