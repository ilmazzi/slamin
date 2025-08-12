@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ __('articles.layout_management') }}</h4>
                        <div>
                            <a href="{{ route('articles.layout.preview') }}" class="btn btn-outline-primary me-2" target="_blank">
                                <i class="ti ti-eye"></i> {{ __('articles.preview') }}
                            </a>
                            <button class="btn btn-success" onclick="saveAllLayout()">
                                <i class="ti ti-device-floppy"></i> {{ __('articles.save_all') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Posizioni layout -->
                        <div class="col-md-8">
                            <div class="row">
                                <!-- Banner principale -->
                                <div class="col-12 mb-4">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">{{ __('articles.banner') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="banner-position" class="layout-position" data-position="banner">
                                                @if(isset($layoutData['banner']['article']))
                                                    @include('articles.layout.article-preview', ['article' => $layoutData['banner']['article']])
                                                @else
                                                    <div class="text-center text-muted py-4">
                                                        <i class="ti ti-plus-circle h1"></i>
                                                        <p>{{ __('articles.drag_article_here') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Prima riga: 2 colonne -->
                                <div class="col-md-6 mb-4">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">{{ __('articles.column1') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="column1-position" class="layout-position" data-position="column1">
                                                @if(isset($layoutData['column1']['article']))
                                                    @include('articles.layout.article-preview', ['article' => $layoutData['column1']['article']])
                                                @else
                                                    <div class="text-center text-muted py-3">
                                                        <i class="ti ti-plus-circle"></i>
                                                        <p class="small">{{ __('articles.drag_article_here') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">{{ __('articles.column2') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="column2-position" class="layout-position" data-position="column2">
                                                @if(isset($layoutData['column2']['article']))
                                                    @include('articles.layout.article-preview', ['article' => $layoutData['column2']['article']])
                                                @else
                                                    <div class="text-center text-muted py-3">
                                                        <i class="ti ti-plus-circle"></i>
                                                        <p class="small">{{ __('articles.drag_article_here') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Articolo orizzontale 1 -->
                                <div class="col-12 mb-4">
                                    <div class="card border-warning">
                                        <div class="card-header bg-warning text-dark">
                                            <h6 class="mb-0">{{ __('articles.horizontal1') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="horizontal1-position" class="layout-position" data-position="horizontal1">
                                                @if(isset($layoutData['horizontal1']['article']))
                                                    @include('articles.layout.article-preview', ['article' => $layoutData['horizontal1']['article']])
                                                @else
                                                    <div class="text-center text-muted py-3">
                                                        <i class="ti ti-plus-circle"></i>
                                                        <p class="small">{{ __('articles.drag_article_here') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Articolo orizzontale 2 -->
                                <div class="col-12 mb-4">
                                    <div class="card border-warning">
                                        <div class="card-header bg-warning text-dark">
                                            <h6 class="mb-0">{{ __('articles.horizontal2') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="horizontal2-position" class="layout-position" data-position="horizontal2">
                                                @if(isset($layoutData['horizontal2']['article']))
                                                    @include('articles.layout.article-preview', ['article' => $layoutData['horizontal2']['article']])
                                                @else
                                                    <div class="text-center text-muted py-3">
                                                        <i class="ti ti-plus-circle"></i>
                                                        <p class="small">{{ __('articles.drag_article_here') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar con articoli disponibili -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('articles.available_articles') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <input type="text" id="articleSearch" class="form-control" 
                                               placeholder="{{ __('articles.search_articles') }}">
                                    </div>
                                    <div id="articlesList" class="articles-list">
                                        @foreach($articles as $article)
                                            <div class="article-item mb-2" data-article-id="{{ $article->id }}" draggable="true">
                                                <div class="card">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex align-items-center">
                                                            @if($article->featured_image)
                                                                <img src="{{ Storage::url($article->featured_image) }}" 
                                                                     class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;" 
                                                                     alt="{{ $article->title }}">
                                                            @endif
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-0 small">{{ Str::limit($article->title, 40) }}</h6>
                                                                <small class="text-muted">{{ $article->user->name }}</small>
                                                            </div>
                                                            <button class="btn btn-sm btn-outline-primary" 
                                                                    onclick="selectArticleForPosition('{{ $article->id }}')">
                                                                <i class="ti ti-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal per selezione posizione -->
<div class="modal fade" id="positionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('articles.select_position') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="assignArticleToPosition('banner')">
                        {{ __('articles.banner') }}
                    </button>
                    <button class="btn btn-outline-info" onclick="assignArticleToPosition('column1')">
                        {{ __('articles.column1') }}
                    </button>
                    <button class="btn btn-outline-info" onclick="assignArticleToPosition('column2')">
                        {{ __('articles.column2') }}
                    </button>
                    <button class="btn btn-outline-warning" onclick="assignArticleToPosition('horizontal1')">
                        {{ __('articles.horizontal1') }}
                    </button>
                    <button class="btn btn-outline-warning" onclick="assignArticleToPosition('horizontal2')">
                        {{ __('articles.horizontal2') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let selectedArticleId = null;
let layoutChanges = {};

document.addEventListener('DOMContentLoaded', function() {
    // Initialize drag and drop
    initializeDragAndDrop();
    
    // Search functionality
    const searchInput = document.getElementById('articleSearch');
    searchInput.addEventListener('input', function() {
        filterArticles(this.value);
    });
});

function initializeDragAndDrop() {
    // Make article items draggable
    document.querySelectorAll('.article-item').forEach(item => {
        item.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', this.dataset.articleId);
            this.classList.add('dragging');
        });
        
        item.addEventListener('dragend', function() {
            this.classList.remove('dragging');
        });
    });

    // Make layout positions droppable
    document.querySelectorAll('.layout-position').forEach(position => {
        position.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });
        
        position.addEventListener('dragleave', function() {
            this.classList.remove('drag-over');
        });
        
        position.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            
            const articleId = e.dataTransfer.getData('text/plain');
            const positionName = this.dataset.position;
            
            assignArticleToPosition(positionName, articleId);
        });
    });
}

function assignArticleToPosition(position, articleId = null) {
    const articleIdToUse = articleId || selectedArticleId;
    if (!articleIdToUse) return;

    // Find the article data
    const articleItem = document.querySelector(`[data-article-id="${articleIdToUse}"]`);
    if (!articleItem) return;

    const articleTitle = articleItem.querySelector('h6').textContent;
    const articleAuthor = articleItem.querySelector('small').textContent;
    const articleImage = articleItem.querySelector('img')?.src;

    // Update the position
    const positionElement = document.getElementById(`${position}-position`);
    positionElement.innerHTML = `
        <div class="article-preview" data-article-id="${articleIdToUse}">
            <div class="d-flex align-items-center">
                ${articleImage ? `<img src="${articleImage}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">` : ''}
                <div class="flex-grow-1">
                    <h6 class="mb-0 small">${articleTitle}</h6>
                    <small class="text-muted">${articleAuthor}</small>
                </div>
                <button class="btn btn-sm btn-outline-danger" onclick="removeArticleFromPosition('${position}')">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        </div>
    `;

    // Track changes
    layoutChanges[position] = articleIdToUse;

    // Close modal if open
    const modal = bootstrap.Modal.getInstance(document.getElementById('positionModal'));
    if (modal) {
        modal.hide();
    }

    selectedArticleId = null;
}

function removeArticleFromPosition(position) {
    const positionElement = document.getElementById(`${position}-position`);
    positionElement.innerHTML = `
        <div class="text-center text-muted py-3">
            <i class="ti ti-plus-circle"></i>
            <p class="small">{{ __('articles.drag_article_here') }}</p>
        </div>
    `;

    // Track changes
    layoutChanges[position] = null;
}

function selectArticleForPosition(articleId) {
    selectedArticleId = articleId;
    const modal = new bootstrap.Modal(document.getElementById('positionModal'));
    modal.show();
}

function filterArticles(searchTerm) {
    const articles = document.querySelectorAll('.article-item');
    articles.forEach(article => {
        const title = article.querySelector('h6').textContent.toLowerCase();
        const author = article.querySelector('small').textContent.toLowerCase();
        const search = searchTerm.toLowerCase();
        
        if (title.includes(search) || author.includes(search)) {
            article.style.display = 'block';
        } else {
            article.style.display = 'none';
        }
    });
}

function saveAllLayout() {
    const changes = Object.keys(layoutChanges).map(position => ({
        position: position,
        article_id: layoutChanges[position]
    }));

    fetch('{{ route('articles.layout.bulk-update') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            layout: changes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            layoutChanges = {};
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('{{ __('articles.error_saving_layout') }}', 'error');
    });
}

function showNotification(message, type) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            text: message,
            icon: type,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    } else {
        alert(message);
    }
}
</script>

<style>
.layout-position {
    min-height: 80px;
    border: 2px dashed #dee2e6;
    border-radius: 0.375rem;
    transition: all 0.3s ease;
}

.layout-position.drag-over {
    border-color: #007bff;
    background-color: rgba(0, 123, 255, 0.1);
}

.article-item {
    cursor: grab;
    transition: all 0.3s ease;
}

.article-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.article-item.dragging {
    opacity: 0.5;
    cursor: grabbing;
}

.articles-list {
    max-height: 600px;
    overflow-y: auto;
}
</style>
@endpush
