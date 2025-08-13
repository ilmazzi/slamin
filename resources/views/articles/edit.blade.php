@extends('layout.master')
@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ __('articles.edit_article') }}: {{ $article->title }}</h4>
                        <div>
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-info me-2" target="_blank">
                                <i class="ti ti-eye"></i> {{ __('articles.view_article') }}
                            </a>
                            <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left"></i> {{ __('articles.back_to_articles') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data" id="articleForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-8">
                                <!-- Titolo -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">{{ __('articles.title') }} *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title', $article->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Excerpt -->
                                <div class="mb-3">
                                    <label for="excerpt" class="form-label">{{ __('articles.excerpt') }}</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror"
                                              id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $article->excerpt) }}</textarea>
                                    <div class="form-text">{{ __('articles.excerpt_help') }}</div>
                                    @error('excerpt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Contenuto -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">{{ __('articles.content') }} *</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                              id="content" name="content" rows="15">{{ old('content', $article->content) }}</textarea>
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
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
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
                                    <label for="tags" class="form-label">{{ __('articles.tags') }}</label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror"
                                           id="tags" name="tags"
                                           value="{{ old('tags', $article->tags->pluck('name')->implode(', ')) }}"
                                           placeholder="{{ __('articles.tags_placeholder') }}">
                                    <div class="form-text">{{ __('articles.tags_help') }}</div>
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Immagine in evidenza -->
                                <div class="mb-3">
                                    <label for="featured_image" class="form-label">{{ __('articles.featured_image') }}</label>
                                    @if($article->featured_image)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($article->featured_image) }}"
                                                 alt="{{ $article->title }}" class="img-fluid rounded" style="max-height: 150px;">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                                                <label class="form-check-label" for="remove_image">
                                                    {{ __('articles.remove_image') }}
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                                <input type="file" class="form-control @error('featured_image') is-invalid @enderror"
                id="featured_image" name="featured_image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    <div class="form-text">
                                        {{ __('articles.image_help') }}<br>
                                        {{ __('articles.max_size') }}: {{ config('app.max_upload_size', '2MB') }}<br>
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

                                <!-- Statistiche articolo -->
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0">{{ __('articles.article_stats') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="h5 mb-0">{{ $article->views_count }}</div>
                                                <small class="text-muted">{{ __('articles.views') }}</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="h5 mb-0">{{ $article->likes_count }}</div>
                                                <small class="text-muted">{{ __('articles.likes') }}</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="h5 mb-0">{{ $article->comments_count }}</div>
                                                <small class="text-muted">{{ __('articles.comments') }}</small>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                {{ __('articles.created') }}: {{ $article->created_at->format('d/m/Y H:i') }}<br>
                                                {{ __('articles.updated') }}: {{ $article->updated_at->format('d/m/Y H:i') }}
                                                @if($article->published_at)
                                                    <br>{{ __('articles.published') }}: {{ $article->published_at->format('d/m/Y H:i') }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Opzioni di pubblicazione -->
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">{{ __('articles.publishing_options') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Stato -->
                                        <div class="mb-3">
                                            <label for="status" class="form-label">{{ __('articles.status') }}</label>
                                            <select class="form-select @error('status') is-invalid @enderror"
                                                    id="status" name="status">
                                                <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>
                                                    {{ __('articles.draft') }}
                                                </option>
                                                <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>
                                                    {{ __('articles.published') }}
                                                </option>
                                                @if(auth()->user()->hasPermissionTo('articles.publish'))
                                                    <option value="pending" {{ old('status', $article->status) == 'pending' ? 'selected' : '' }}>
                                                        {{ __('articles.pending_review') }}
                                                    </option>
                                                @endif
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Data di pubblicazione -->
                                        <div class="mb-3">
                                            <label for="published_at" class="form-label">{{ __('articles.publish_date') }}</label>
                                            <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror"
                                                   id="published_at" name="published_at"
                                                   value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}">
                                            @error('published_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- SEO -->
                                        <div class="mb-3">
                                            <label for="meta_title" class="form-label">{{ __('articles.meta_title') }}</label>
                                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                                   id="meta_title" name="meta_title"
                                                   value="{{ old('meta_title', $article->meta_title) }}">
                                            @error('meta_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="meta_description" class="form-label">{{ __('articles.meta_description') }}</label>
                                            <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                                      id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $article->meta_description) }}</textarea>
                                            @error('meta_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Opzioni avanzate -->
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="allow_comments"
                                                   name="allow_comments" value="1"
                                                   {{ old('allow_comments', $article->allow_comments) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="allow_comments">
                                                {{ __('articles.allow_comments') }}
                                            </label>
                                        </div>

                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="featured"
                                                   name="featured" value="1"
                                                   {{ old('featured', $article->featured) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="featured">
                                                {{ __('articles.mark_as_featured') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <button type="button" class="btn btn-outline-secondary me-2" onclick="saveDraft()">
                                    <i class="ti ti-device-floppy"></i> {{ __('articles.save_draft') }}
                                </button>
                                @if($article->status === 'published')
                                    <button type="button" class="btn btn-outline-warning" onclick="unpublishArticle()">
                                        <i class="ti ti-eye-off"></i> {{ __('articles.unpublish') }}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-outline-success" onclick="publishArticle()">
                                        <i class="ti ti-eye"></i> {{ __('articles.publish') }}
                                    </button>
                                @endif
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" onclick="previewArticle()">
                                    <i class="ti ti-eye"></i> {{ __('articles.preview') }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy"></i> {{ __('articles.update_article') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal di anteprima -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('articles.preview') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Contenuto dell'anteprima -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- TinyMCE (versione gratuita senza API key) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.7.2/tinymce.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inizializza TinyMCE (versione gratuita)
    tinymce.init({
        selector: '#content',
        height: 500,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; }',
        // Configurazioni per versione gratuita
        branding: false,
        promotion: false,
        menubar: false,
        statusbar: false,
        // Rimuove warning e usa solo funzionalità gratuite
        elementpath: false,
        resize: false,
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        }
    });

    // Anteprima immagine
    document.getElementById('featured_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('imagePreview').style.display = 'none';
        }
    });

    // Auto-genera meta title e description
    document.getElementById('title').addEventListener('input', function() {
        const title = this.value;
        if (!document.getElementById('meta_title').value) {
            document.getElementById('meta_title').value = title;
        }
    });

    document.getElementById('excerpt').addEventListener('input', function() {
        const excerpt = this.value;
        if (!document.getElementById('meta_description').value && excerpt) {
            document.getElementById('meta_description').value = excerpt.substring(0, 160);
        }
    });
});

function saveDraft() {
    document.getElementById('status').value = 'draft';
    document.getElementById('articleForm').submit();
}

function publishArticle() {
    if (confirm('{{ __("articles.confirm_publish") }}')) {
        fetch('{{ route("articles.publish", $article) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('{{ __("articles.published_successfully") }}', 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showNotification(data.message || '{{ __("articles.publish_error") }}', 'error');
            }
        })
        .catch(error => {
            showNotification('{{ __("articles.publish_error") }}', 'error');
        });
    }
}

function unpublishArticle() {
    if (confirm('{{ __("articles.confirm_unpublish") }}')) {
        fetch('{{ route("articles.unpublish", $article) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('{{ __("articles.unpublished_successfully") }}', 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showNotification(data.message || '{{ __("articles.unpublish_error") }}', 'error');
            }
        })
        .catch(error => {
            showNotification('{{ __("articles.unpublish_error") }}', 'error');
        });
    }
}

function previewArticle() {
    const title = document.getElementById('title').value;
    const content = tinymce.get('content').getContent();
    const excerpt = document.getElementById('excerpt').value;

    if (!title || !content) {
        showNotification('{{ __("articles.fill_required_fields") }}', 'warning');
        return;
    }

    const previewContent = `
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">${title}</h1>
                ${excerpt ? `<p class="lead text-muted">${excerpt}</p>` : ''}
                <div class="article-content">${content}</div>
            </div>
        </div>
    `;

    document.getElementById('previewContent').innerHTML = previewContent;
    new bootstrap.Modal(document.getElementById('previewModal')).show();
}

function showNotification(message, type = 'info') {
    Swal.fire({
        title: message,
        icon: type,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
}
</script>
@endpush
