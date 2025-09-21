@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ __('articles.edit_article') }}</h4>
                        <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left"></i> {{ __('articles.back_to_dashboard') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" id="articleForm">
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

                                <!-- Tags -->
                                <div class="mb-3">
                                    <label for="tag_ids" class="form-label">{{ __('articles.tags') }}</label>
                                    <select class="form-select @error('tag_ids') is-invalid @enderror"
                                            id="tag_ids" name="tag_ids[]" multiple>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}"
                                                    {{ in_array($tag->id, old('tag_ids', $article->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">{{ __('articles.tags_help') }}</div>
                                    @error('tag_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Immagine in evidenza -->
                                <div class="mb-3">
                                    <label for="featured_image" class="form-label">{{ __('articles.featured_image') }}</label>
                                    <input type="file" class="form-control @error('featured_image') is-invalid @enderror"
                                           id="featured_image" name="featured_image" accept="image/*">
                                    @error('featured_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($article->featured_image)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($article->featured_image) }}" alt="Current image" class="img-fluid rounded" style="max-height: 150px;">
                                        </div>
                                    @endif
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
                                                <option value="archived" {{ old('status', $article->status) == 'archived' ? 'selected' : '' }}>
                                                    {{ __('articles.archived') }}
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Data di pubblicazione -->
                                        <div class="mb-3">
                                            <label for="published_at" class="form-label">{{ __('articles.publish_date') }}</label>
                                            <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror"
                                                   id="published_at" name="published_at" value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}">
                                            @error('published_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Opzioni avanzate -->
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="featured"
                                                   name="featured" value="1" {{ old('featured', $article->featured) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="featured">
                                                {{ __('articles.mark_as_featured') }}
                                            </label>
                                        </div>

                                        @if(auth()->user()->can('articles.manage_news'))
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="is_news"
                                                   name="is_news" value="1" {{ old('is_news', $article->is_news) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_news">
                                                📰 {{ __('articles.mark_as_news') }}
                                                <small class="text-muted d-block">{{ __('articles.news_description') }}</small>
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                <i class="ti ti-arrow-left"></i> {{ __('articles.cancel') }}
                            </button>
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

<script>
function previewArticle() {
    // Implement preview functionality
    alert('Preview functionality will be implemented');
}
</script>
@endsection
