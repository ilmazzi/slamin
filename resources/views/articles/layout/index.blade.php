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
                                            <div id="banner-position" class="layout-position {{ !isset($layoutData['banner']['article']) ? 'empty' : '' }}"
                                                 data-position="banner"
                                                 onclick="openArticleSelector('banner')"
                                                 style="cursor: pointer;">
                                                @if(isset($layoutData['banner']['article']))
                                                    @include('articles.layout.article-preview', ['article' => $layoutData['banner']['article']])
                                                @else
                                                    <div class="text-center text-muted py-4">
                                                        <i class="ti ti-plus-circle h1"></i>
                                                        <p>{{ __('articles.drag_article_here') }}</p>
                                                        <small class="text-muted d-block mt-2">Clicca per selezionare un articolo</small>
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
                                            <div id="column1-position" class="layout-position empty" data-position="column1" onclick="openArticleSelector('column1')" style="cursor: pointer;">
                                                @if(isset($layoutData['column1']['article']))
                                                    @include('articles.layout.article-preview', ['article' => $layoutData['column1']['article']])
                                                @else
                                                    <div class="text-center text-muted py-3">
                            <i class="ti ti-plus-circle"></i>
                            <p class="small">{{ __('articles.drag_article_here') }}</p>
                            <small class="text-muted d-block mt-2">Clicca per selezionare un articolo</small>
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
                                            <div id="column2-position" class="layout-position empty" data-position="column2" onclick="openArticleSelector('column2')" style="cursor: pointer;">
                                                @if(isset($layoutData['column2']['article']))
                                                    @include('articles.layout.article-preview', ['article' => $layoutData['column2']['article']])
                                                @else
                                                    <div class="text-center text-muted py-3">
                            <i class="ti ti-plus-circle"></i>
                            <p class="small">{{ __('articles.drag_article_here') }}</p>
                            <small class="text-muted d-block mt-2">Clicca per selezionare un articolo</small>
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
                                            <div id="horizontal1-position" class="layout-position empty" data-position="horizontal1" onclick="openArticleSelector('horizontal1')" style="cursor: pointer;">
                                                @if(isset($layoutData['horizontal1']['article']))
                                                    @include('articles.layout.article-preview', ['article' => $layoutData['horizontal1']['article']])
                                                @else
                                                    <div class="text-center text-muted py-3">
                            <i class="ti ti-plus-circle"></i>
                            <p class="small">{{ __('articles.drag_article_here') }}</p>
                            <small class="text-muted d-block mt-2">Clicca per selezionare un articolo</small>
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
                                            <div id="horizontal2-position" class="layout-position empty" data-position="horizontal2" onclick="openArticleSelector('horizontal2')" style="cursor: pointer;">
                                                @if(isset($layoutData['horizontal2']['article']))
                                                    @include('articles.layout.article-preview', ['article' => $layoutData['horizontal2']['article']])
                                                @else
                                                    <div class="text-center text-muted py-3">
                            <i class="ti ti-plus-circle"></i>
                            <p class="small">{{ __('articles.drag_article_here') }}</p>
                            <small class="text-muted d-block mt-2">Clicca per selezionare un articolo</small>
                        </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Seconda riga: 2 colonne -->
                                <div class="col-md-6 mb-4">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">{{ __('articles.column3') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="column3-position" class="layout-position empty" data-position="column3" onclick="openArticleSelector('column3')" style="cursor: pointer;">
                                                @if(isset($layoutData['column3']['article']))
                                                    @include('articles.layout.article-preview', ['article' => $layoutData['column3']['article']])
                                                @else
                                                    <div class="text-center text-muted py-3">
                            <i class="ti ti-plus-circle"></i>
                            <p class="small">{{ __('articles.drag_article_here') }}</p>
                            <small class="text-muted d-block mt-2">Clicca per selezionare un articolo</small>
                        </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">{{ __('articles.column4') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="column4-position" class="layout-position empty" data-position="column4" onclick="openArticleSelector('column4')" style="cursor: pointer;">
                                                @if(isset($layoutData['column4']['article']))
                                                    @include('articles.layout.article-preview', ['article' => $layoutData['column4']['article']])
                                                @else
                                                    <div class="text-center text-muted py-3">
                            <i class="ti ti-plus-circle"></i>
                            <p class="small">{{ __('articles.drag_article_here') }}</p>
                            <small class="text-muted d-block mt-2">Clicca per selezionare un articolo</small>
                        </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Articolo orizzontale 3 -->
                                <div class="col-12 mb-4">
                                    <div class="card border-warning">
                                        <div class="card-header bg-warning text-dark">
                                            <h6 class="mb-0">{{ __('articles.horizontal3') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="horizontal3-position" class="layout-position empty" data-position="horizontal3" onclick="openArticleSelector('horizontal3')" style="cursor: pointer;">
                                                @if(isset($layoutData['horizontal3']['article']))
                                                    @include('articles.layout.article-preview', ['article' => $layoutData['horizontal3']['article']])
                                                @else
                                                    <div class="text-center text-muted py-3">
                            <i class="ti ti-plus-circle"></i>
                            <p class="small">{{ __('articles.drag_article_here') }}</p>
                            <small class="text-muted d-block mt-2">Clicca per selezionare un articolo</small>
                        </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terza riga: 2 colonne -->
                                <div class="col-md-6 mb-4">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">{{ __('articles.column5') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="column5-position" class="layout-position empty" data-position="column5" onclick="openArticleSelector('column5')" style="cursor: pointer;">
                                                @if(isset($layoutData['column5']['article']))
                                                    @include('articles.layout.article-preview', ['article' => $layoutData['column5']['article']])
                                                @else
                                                    <div class="text-center text-muted py-3">
                            <i class="ti ti-plus-circle"></i>
                            <p class="small">{{ __('articles.drag_article_here') }}</p>
                            <small class="text-muted d-block mt-2">Clicca per selezionare un articolo</small>
                        </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">{{ __('articles.column6') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="column6-position" class="layout-position empty" data-position="column6" onclick="openArticleSelector('column6')" style="cursor: pointer;">
                                                @if(isset($layoutData['column6']['article']))
                                                    @include('articles.layout.article-preview', ['article' => $layoutData['column6']['article']])
                                                @else
                                                    <div class="text-center text-muted py-3">
                            <i class="ti ti-plus-circle"></i>
                            <p class="small">{{ __('articles.drag_article_here') }}</p>
                            <small class="text-muted d-block mt-2">Clicca per selezionare un articolo</small>
                        </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Sidebar con articoli disponibili (solo desktop) -->
                        <div class="col-md-4 d-none d-md-block">
                            <div class="card sidebar-no-scroll" id="articlesSidebar">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">{{ __('articles.available_articles') }}</h5>
                                    <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="closeMobileSidebar()">
                                        <i class="ph ph-x"></i>
                                    </button>
                                </div>
                                <div class="alert alert-info m-3 mb-0" id="positionInfo" style="display: none;">
                                    <small><i class="ti ti-info-circle me-1"></i>Posizione selezionata: <strong id="selectedPositionName"></strong></small>
                                </div>
                                <div class="card-body p-0">
                                    <div class="p-3 border-bottom">
                                        <input type="text" id="articleSearch" class="form-control"
                                               placeholder="{{ __('articles.search_articles') }}">
                                    </div>
                                    <div id="articlesList" class="articles-list-no-scroll p-3">
                                        @foreach($articles as $article)
                                            <div class="article-item mb-2" data-article-id="{{ $article->id }}" draggable="true" onclick="selectArticleForPosition({{ $article->id }})">
                                                <div class="card hover-effect">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex align-items-center">
                                                            @if($article->featured_image)
                                                                <img src="{{ Storage::url($article->featured_image) }}"
                                                                     class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;"
                                                                     alt="{{ $article->title }}">
                                                            @else
                                                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                                    <i class="ph ph-newspaper text-muted"></i>
                                                                </div>
                                                            @endif
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-0 small">{{ Str::limit($article->title, 40) }}</h6>
                                                                <small class="text-muted">{{ $article->user->name }}</small>
                                                            </div>
                                                            <button class="btn btn-sm btn-primary"
                                                                    onclick="event.stopPropagation(); selectArticleForPosition({{ $article->id }})">
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


<!-- Modal per selezione articoli su mobile -->
<div class="modal fade" id="mobileArticleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mobileModalTitle">Seleziona Articolo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" id="mobileArticleSearch" class="form-control"
                           placeholder="{{ __('articles.search_articles') }}">
                </div>
                <div id="mobileArticlesList" class="mobile-articles-list">
                    @foreach($articles as $article)
                        <div class="article-item-mobile mb-2" data-article-id="{{ $article->id }}" onclick="selectArticleForMobile({{ $article->id }})">
                            <div class="card hover-effect">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        @if($article->featured_image)
                                            <img src="{{ Storage::url($article->featured_image) }}"
                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;"
                                                 alt="{{ $article->title }}">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                <i class="ph ph-newspaper text-muted f-s-24"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $article->title }}</h6>
                                            <small class="text-muted">{{ $article->user->name }}</small>
                                            @if($article->category)
                                                <span class="badge bg-secondary ms-2">{{ $article->category->name }}</span>
                                            @endif
                                        </div>
                                        <i class="ti ti-chevron-right text-muted"></i>
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

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let selectedArticleId = null;
let layoutChanges = {};

document.addEventListener('DOMContentLoaded', function() {
    // Initialize drag and drop
    initializeDragAndDrop();

    // Search functionality for desktop
    const searchInput = document.getElementById('articleSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterArticles(this.value);
        });
    }

    // Search functionality for mobile modal
    const mobileSearchInput = document.getElementById('mobileArticleSearch');
    if (mobileSearchInput) {
        mobileSearchInput.addEventListener('input', function() {
            filterMobileArticles(this.value);
        });
    }
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
    if (!articleIdToUse) {
        console.error('No article ID provided');
        return;
    }

    // Find the article data
    const articleItem = document.querySelector(`[data-article-id="${articleIdToUse}"]`);
    if (!articleItem) {
        console.error('Article item not found for ID:', articleIdToUse);
        return;
    }

    const articleTitle = articleItem.querySelector('h6').textContent;
    const articleAuthor = articleItem.querySelector('small').textContent;
    const articleImage = articleItem.querySelector('img')?.src;

    // Update the position
    const positionElement = document.getElementById(`${position}-position`);
    if (!positionElement) {
        console.error('Position element not found:', position);
        return;
    }

    positionElement.innerHTML = `
        <div class="article-preview" data-article-id="${articleIdToUse}">
            <div class="d-flex align-items-center">
                ${articleImage ? `<img src="${articleImage}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">` : '<div class="bg-light rounded d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;"><i class="ph ph-newspaper text-muted"></i></div>'}
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

    // Remove empty class
    positionElement.classList.remove('empty');

    // Track changes
    layoutChanges[position] = articleIdToUse;

    // Position assigned successfully

    selectedArticleId = null;

    // Show success message
    showNotification('Articolo assegnato alla posizione ' + position, 'success');

    // Refresh available articles list
    refreshAvailableArticles();
}

function removeArticleFromPosition(position) {
    const positionElement = document.getElementById(`${position}-position`);
    if (!positionElement) {
        console.error('Position element not found:', position);
        return;
    }

    positionElement.innerHTML = `
        <div class="text-center text-muted py-3">
            <i class="ti ti-plus-circle"></i>
            <p class="small">{{ __('articles.drag_article_here') }}</p>
            <small class="text-muted d-block mt-2">Clicca per selezionare un articolo</small>
        </div>
    `;

    // Add empty class back
    positionElement.classList.add('empty');

    // Track changes
    layoutChanges[position] = null;

    // Show success message
    showNotification('Articolo rimosso dalla posizione ' + position, 'success');

    // Refresh available articles list
    refreshAvailableArticles();
}

function selectArticleForPosition(articleId) {
    console.log('Selecting article:', articleId, 'for position:', window.currentPosition);
    selectedArticleId = articleId;

    // If we have a current position, assign directly
    if (window.currentPosition) {
        assignArticleToPosition(window.currentPosition, articleId);
        if (window.innerWidth <= 768) {
            closeMobileSidebar();
        }

        // Hide position info
        const positionInfo = document.getElementById('positionInfo');
        if (positionInfo) {
            positionInfo.style.display = 'none';
        }

        window.currentPosition = null;
        return;
    }

    // If no current position, show a message to select a position first
    showNotification('Seleziona prima una posizione cliccando su una delle aree del layout', 'info');
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

function filterMobileArticles(searchTerm) {
    const articles = document.querySelectorAll('.article-item-mobile');
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

function refreshAvailableArticles() {
    // Get current search term
    const searchInput = document.getElementById('articleSearch');
    const searchTerm = searchInput ? searchInput.value : '';

    // Fetch updated articles list
    fetch(`{{ route('articles.layout.articles') }}?search=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateArticlesList(data.articles);
            }
        })
        .catch(error => {
            console.error('Error refreshing articles:', error);
        });
}

function updateArticlesList(articles) {
    const articlesContainer = document.getElementById('availableArticles');
    if (!articlesContainer) return;

    if (articles.length === 0) {
        articlesContainer.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="ph ph-newspaper f-s-48 mb-3"></i>
                <h6>Nessun articolo disponibile</h6>
                <p class="small">Tutti gli articoli sono già stati utilizzati nel layout</p>
            </div>
        `;
        return;
    }

    let html = '';
    articles.forEach(article => {
        html += `
            <div class="article-item card mb-2" onclick="selectArticleForPosition(${article.id})">
                <div class="card-body py-2">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            ${article.featured_image_url ?
                                `<img src="${article.featured_image_url}" alt="${article.title}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">` :
                                `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="ph ph-newspaper text-muted"></i></div>`
                            }
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 f-s-14 f-w-600">${article.title}</h6>
                            <small class="text-muted">${article.user ? article.user.name : 'N/A'}</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    articlesContainer.innerHTML = html;
}

// Mobile modal functions
function openMobileArticleModal(position) {
    const modal = new bootstrap.Modal(document.getElementById('mobileArticleModal'));
    const modalTitle = document.getElementById('mobileModalTitle');

    // Update modal title with position name
    const positionNames = {
        'banner': 'Banner Principale',
        'column1': 'Colonna 1',
        'column2': 'Colonna 2',
        'horizontal1': 'Orizzontale 1',
        'horizontal2': 'Orizzontale 2',
        'column3': 'Colonna 3',
        'column4': 'Colonna 4',
        'horizontal3': 'Orizzontale 3',
        'column5': 'Colonna 5',
        'column6': 'Colonna 6'
    };

    if (modalTitle) {
        modalTitle.textContent = `Seleziona Articolo per ${positionNames[position] || position}`;
    }

    modal.show();
}

function selectArticleForMobile(articleId) {
    console.log('Selecting article for mobile:', articleId, 'for position:', window.currentPosition);

    if (window.currentPosition) {
        assignArticleToPosition(window.currentPosition, articleId);

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('mobileArticleModal'));
        if (modal) {
            modal.hide();
        }

        window.currentPosition = null;
    }
}

// Open article selector for a specific position
function openArticleSelector(position) {
    console.log('Opening article selector for position:', position);

    // Store the position for when an article is selected
    window.currentPosition = position;

    // On mobile, open the modal
    if (window.innerWidth <= 768) {
        openMobileArticleModal(position);
    } else {
        // On desktop, scroll to the sidebar and highlight it
        const sidebar = document.getElementById('articlesSidebar');
        if (sidebar) {
            sidebar.scrollIntoView({ behavior: 'smooth', block: 'start' });
            // Add a temporary highlight to show the sidebar
            sidebar.style.boxShadow = '0 0 20px rgba(0, 123, 255, 0.5)';
            setTimeout(() => {
                sidebar.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
            }, 2000);
        }

        // Show position info in sidebar
        const positionInfo = document.getElementById('positionInfo');
        const selectedPositionName = document.getElementById('selectedPositionName');
        if (positionInfo && selectedPositionName) {
            selectedPositionName.textContent = position;
            positionInfo.style.display = 'block';
        }

        // Highlight the selected position
        const positionElement = document.getElementById(`${position}-position`);
        if (positionElement) {
            positionElement.style.borderColor = '#28a745';
            positionElement.style.backgroundColor = 'rgba(40, 167, 69, 0.1)';
            setTimeout(() => {
                positionElement.style.borderColor = '';
                positionElement.style.backgroundColor = '';
            }, 3000);
        }
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

/* Sidebar senza scroll interno - segue lo scroll della pagina */
.sidebar-no-scroll {
    position: sticky;
    top: 20px;
    z-index: 1000;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 0.5rem;
    height: fit-content;
    max-height: calc(100vh - 40px);
}

.articles-list-no-scroll {
    /* Su desktop mantieni lo scroll per vedere tutti gli articoli */
    max-height: calc(100vh - 200px);
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-width: thin;
    scrollbar-color: rgba(0,0,0,0.2) transparent;
}

.articles-list-no-scroll::-webkit-scrollbar {
    width: 6px;
}

.articles-list-no-scroll::-webkit-scrollbar-track {
    background: transparent;
}

.articles-list-no-scroll::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.2);
    border-radius: 3px;
}

.articles-list-no-scroll::-webkit-scrollbar-thumb:hover {
    background-color: rgba(0,0,0,0.3);
}

/* Layout positions clickable on mobile */
.layout-position {
    cursor: pointer;
    transition: all 0.3s ease;
}

.layout-position:hover {
    border-color: #007bff !important;
    background-color: rgba(0, 123, 255, 0.05);
}

.layout-position.empty {
    cursor: pointer;
}

.layout-position.empty:hover {
    border-color: #28a745 !important;
    background-color: rgba(40, 167, 69, 0.05);
}

/* Mobile-specific styles */
@media (max-width: 768px) {
    .layout-position {
        min-height: 100px;
    }

    .mobile-articles-list {
        max-height: 60vh;
        overflow-y: auto;
    }

    .article-item-mobile {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .article-item-mobile:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .article-item-mobile:active {
        transform: translateY(0);
    }
}
</style>
@endpush
