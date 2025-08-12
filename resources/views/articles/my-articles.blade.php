@extends('layout.master')
@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ __('articles.my_articles') }}</h4>
                        <a href="{{ route('articles.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i> {{ __('articles.create_new_article') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtri -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">{{ __('articles.all_statuses') }}</option>
                                <option value="draft">{{ __('articles.draft') }}</option>
                                <option value="published">{{ __('articles.published') }}</option>
                                <option value="pending">{{ __('articles.pending_review') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="categoryFilter">
                                <option value="">{{ __('articles.all_categories') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="searchFilter" placeholder="{{ __('articles.search_articles') }}">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" onclick="applyFilters()">
                                <i class="ti ti-filter"></i> {{ __('articles.apply_filters') }}
                            </button>
                        </div>
                    </div>

                    @if($articles->count() > 0)
                        <div class="row">
                            @foreach($articles as $article)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100">
                                        @if($article->featured_image)
                                            <img src="{{ Storage::url($article->featured_image) }}" 
                                                 class="card-img-top" style="height: 200px; object-fit: cover;" 
                                                 alt="{{ $article->title }}">
                                        @endif
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                @if($article->category)
                                                    <span class="badge bg-primary">{{ $article->category->name }}</span>
                                                @endif
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('articles.show', $article) }}" target="_blank">
                                                                <i class="ti ti-eye"></i> {{ __('articles.view') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('articles.edit', $article) }}">
                                                                <i class="ti ti-edit"></i> {{ __('articles.edit') }}
                                                            </a>
                                                        </li>
                                                        @if($article->status === 'published')
                                                            <li>
                                                                <button class="dropdown-item" onclick="unpublishArticle({{ $article->id }})">
                                                                    <i class="ti ti-eye-off"></i> {{ __('articles.unpublish') }}
                                                                </button>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <button class="dropdown-item" onclick="publishArticle({{ $article->id }})">
                                                                    <i class="ti ti-eye"></i> {{ __('articles.publish') }}
                                                                </button>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <button class="dropdown-item" onclick="toggleFeatured({{ $article->id }})">
                                                                <i class="ti ti-star"></i> 
                                                                {{ $article->featured ? __('articles.unfeature') : __('articles.feature') }}
                                                            </button>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <button class="dropdown-item text-danger" onclick="deleteArticle({{ $article->id }}, '{{ $article->title }}')">
                                                                <i class="ti ti-trash"></i> {{ __('articles.delete') }}
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                            <h5 class="card-title">
                                                <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                                                    {{ Str::limit($article->title, 60) }}
                                                </a>
                                            </h5>
                                            
                                            @if($article->excerpt)
                                                <p class="card-text text-muted">{{ Str::limit($article->excerpt, 100) }}</p>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <small class="text-muted">
                                                    <i class="ti ti-calendar"></i> {{ $article->created_at->format('d/m/Y') }}
                                                </small>
                                                <div class="d-flex gap-2">
                                                    <span class="badge bg-{{ $article->status === 'published' ? 'success' : ($article->status === 'draft' ? 'secondary' : 'warning') }}">
                                                        {{ __('articles.' . $article->status) }}
                                                    </span>
                                                    @if($article->featured)
                                                        <span class="badge bg-warning">{{ __('articles.featured') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-3 text-muted">
                                                    <small><i class="ti ti-eye"></i> {{ $article->views_count }}</small>
                                                    <small><i class="ti ti-heart"></i> {{ $article->likes_count }}</small>
                                                    <small><i class="ti ti-message-circle"></i> {{ $article->comments_count }}</small>
                                                </div>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($articles->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $articles->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-article display-1 text-muted"></i>
                            <h5 class="mt-3">{{ __('articles.no_articles_yet') }}</h5>
                            <p class="text-muted">{{ __('articles.no_articles_description') }}</p>
                            <a href="{{ route('articles.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus"></i> {{ __('articles.create_first_article') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const category = document.getElementById('categoryFilter').value;
    const search = document.getElementById('searchFilter').value;
    
    let url = new URL(window.location);
    if (status) url.searchParams.set('status', status);
    if (category) url.searchParams.set('category', category);
    if (search) url.searchParams.set('search', search);
    
    window.location.href = url.toString();
}

function publishArticle(articleId) {
    if (confirm('{{ __("articles.confirm_publish") }}')) {
        fetch(`/articles/${articleId}/publish`, {
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

function unpublishArticle(articleId) {
    if (confirm('{{ __("articles.confirm_unpublish") }}')) {
        fetch(`/articles/${articleId}/unpublish`, {
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

function toggleFeatured(articleId) {
    fetch(`/articles/${articleId}/toggle-featured`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showNotification(data.message || '{{ __("articles.feature_error") }}', 'error');
        }
    })
    .catch(error => {
        showNotification('{{ __("articles.feature_error") }}', 'error');
    });
}

function deleteArticle(articleId, title) {
    Swal.fire({
        title: '{{ __("articles.confirm_delete") }}',
        text: `"${title}"`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __("articles.delete") }}',
        cancelButtonText: '{{ __("articles.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/articles/${articleId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('{{ __("articles.deleted_successfully") }}', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showNotification(data.message || '{{ __("articles.delete_error") }}', 'error');
                }
            })
            .catch(error => {
                showNotification('{{ __("articles.delete_error") }}', 'error');
            });
        }
    });
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

// Applica filtri all'avvio se presenti nell'URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.has('status')) {
        document.getElementById('statusFilter').value = urlParams.get('status');
    }
    if (urlParams.has('category')) {
        document.getElementById('categoryFilter').value = urlParams.get('category');
    }
    if (urlParams.has('search')) {
        document.getElementById('searchFilter').value = urlParams.get('search');
    }
});

// Ricerca in tempo reale
document.getElementById('searchFilter').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});
</script>
@endpush
