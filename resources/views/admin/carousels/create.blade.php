@extends('layout.master')

@section('title', 'Crea ' . __('notifications.new') . ' Slide Carosello')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">

        <!-- Header -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Crea {{ __('notifications.new') }} Slide Carosello</h4>
                
            </div>
        </div>

        <!-- Content Type Selection -->
        <div class="row">
            <div class="col-12">
                <div class="card hover-effect">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-plus-circle f-s-16 me-2"></i>
                            Scegli Tipo di Contenuto
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs" id="contentTypeTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button" role="tab">
                                    <i class="ph-duotone ph-upload f-s-16 me-2"></i>
                                    Carica File
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="existing-tab" data-bs-toggle="tab" data-bs-target="#existing" type="button" role="tab">
                                    <i class="ph-duotone ph-magnifying-glass f-s-16 me-2"></i>
                                    Contenuti Esistenti
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content mt-3" id="contentTypeTabContent">
                            <!-- Upload Tab -->
                            <div class="tab-pane fade show active" id="upload" role="tabpanel">
                                <form action="{{ route('admin.carousels.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-8">
                                            <!-- Basic Info -->
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Titolo *</label>
                                                <input type="text" class="form-control" id="title" name="title"
                                                       value="{{ old('title') }}" required maxlength="255">
                                                <div class="form-text">Titolo della slide che apparirà nel carosello</div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="description" class="form-label">Descrizione</label>
                                                <textarea class="form-control" id="description" name="description"
                                                          rows="3" maxlength="1000">{{ old('description') }}</textarea>
                                                <div class="form-text">Descrizione opzionale che apparirà sotto il titolo</div>
                                            </div>

                                            <!-- {{ __('common.media_section') }} Upload -->
                                            <div class="mb-3">
                                                <label for="image" class="form-label">Immagine *</label>
                                                <input type="file" class="form-control" id="image" name="image"
                                                       accept="image/*" required>
                                                <div class="form-text">Immagine principale della slide (JPEG, PNG, GIF - max 2MB)</div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="video" class="form-label">{{ __('common.video') }} (opzionale)</label>
                                                <input type="file" class="form-control" id="video" name="video"
                                                       accept="video/*">
                                                <div class="form-text">{{ __('common.video') }} opzionale (MP4, AVI, MOV, MKV, WEBM, FLV - max 10MB)</div>
                                            </div>

                                            <!-- Link Settings -->
                                            <div class="mb-3">
                                                <label for="link_url" class="form-label">URL Link</label>
                                                <input type="url" class="form-control" id="link_url" name="link_url"
                                                       value="{{ old('link_url') }}" placeholder="https://example.com">
                                                <div class="form-text">URL opzionale per il link della slide</div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="link_text" class="form-label">Testo Link</label>
                                                <input type="text" class="form-control" id="link_text" name="link_text"
                                                       value="{{ old('link_text') }}" placeholder="Scopri di più">
                                                <div class="form-text">Testo del pulsante link</div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <!-- Settings -->
                                            <div class="card card">
                                                <div class="card-header">
                                                    <h6 class="mb-0">
                                                        <i class="ph-duotone ph-gear f-s-16 me-2"></i>
                                                        Impostazioni
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="order" class="form-label">Ordine</label>
                                                        <input type="number" class="form-control" id="order" name="order"
                                                               value="{{ old('order', 0) }}" min="0">
                                                        <div class="form-text">Ordine di visualizzazione (0 = primo)</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_active">Attivo</label>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="start_date" class="form-label">Data Inizio</label>
                                                        <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                                                               value="{{ old('start_date') }}">
                                                        <div class="form-text">Lascia vuoto per sempre attivo</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="end_date" class="form-label">Data Fine</label>
                                                        <input type="datetime-local" class="form-control" id="end_date" name="end_date"
                                                               value="{{ old('end_date') }}">
                                                        <div class="form-text">Lascia vuoto per sempre attivo</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('admin.carousels.index') }}" class="btn btn-secondary hover-effect">
                                                    <i class="ph-duotone ph-arrow-left f-s-16 me-2"></i>
                                                    Annulla
                                                </a>
                                                <button type="submit" class="btn btn-success hover-effect">
                                                    <i class="ph-duotone ph-check-circle f-s-16 me-2"></i>
                                                    Crea Slide
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Existing Content Tab -->
                            <div class="tab-pane fade" id="existing" role="tabpanel">
                                @if ($errors->any())
                                    <div class="alert alert-danger mb-3">
                                        <h6 class="alert-heading">Errore di Validazione</h6>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger mb-3">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <form action="{{ route('admin.carousels.store') }}" method="POST" id="existingContentForm">
                                    @csrf
                                    <input type="hidden" name="content_type" id="content_type" value="{{ old('content_type') }}">
                                    <input type="hidden" name="content_id" id="content_id" value="{{ old('content_id') }}">

                                    <div class="row">
                                        <div class="col-md-8">
                                            <!-- Content Type Selection -->
                                            <div class="mb-3">
                                                <label for="content_type_select" class="form-label">Tipo di Contenuto *</label>
                                                <select class="form-select" id="content_type_select" required>
                                                    <option value="">Seleziona tipo di contenuto</option>
                                                    @foreach($contentTypes as $type => $label)
                                                        <option value="{{ $type }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Search Content -->
                                            <div class="mb-3" id="searchSection" style="display: none;">
                                                <label for="content_search" class="form-label">Cerca Contenuto</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="content_search"
                                                           placeholder="Inizia a digitare per cercare...">
                                                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                                        <i class="ph-duotone ph-magnifying-glass"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Content Results -->
                                            <div class="mb-3" id="contentResults" style="display: none;">
                                                <label class="form-label">Risultati Ricerca</label>
                                                <div class="content-list" id="contentList">
                                                    <!-- Results will be loaded here -->
                                                </div>
                                            </div>

                                            <!-- Selected Content Preview -->
                                            <div class="mb-3" id="selectedContentPreview" style="display: none;">
                                                <label class="form-label">Contenuto Selezionato</label>
                                                <div class="card card">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <img id="selectedImage" src="" alt="" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                            <div>
                                                                <h6 id="selectedTitle" class="mb-1"></h6>
                                                                <p id="selectedDescription" class="text-muted mb-0 f-s-12"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Custom Override Fields -->
                                            <div class="mb-3" id="overrideFields" style="display: none;">
                                                <div class="card card">
                                                    <div class="card-header">
                                                        <h6 class="mb-0">
                                                            <i class="ph-duotone ph-pencil f-s-16 me-2"></i>
                                                            Personalizza (Opzionale)
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="mb-3">
                                                            <label for="override_title" class="form-label">Titolo Personalizzato</label>
                                                            <input type="text" class="form-control" id="override_title" name="title"
                                                                   value="{{ old('title') }}" placeholder="Lascia vuoto per usare il titolo originale">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="override_description" class="form-label">Descrizione Personalizzata</label>
                                                            <textarea class="form-control" id="override_description" name="description"
                                                                      rows="2" placeholder="Lascia vuoto per usare la descrizione originale">{{ old('description') }}</textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="override_link_url" class="form-label">URL Link Personalizzato</label>
                                                            <input type="url" class="form-control" id="override_link_url" name="link_url"
                                                                   value="{{ old('link_url') }}" placeholder="Lascia vuoto per usare il link originale">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="override_link_text" class="form-label">Testo Link Personalizzato</label>
                                                            <input type="text" class="form-control" id="override_link_text" name="link_text"
                                                                   value="{{ old('link_text', 'Scopri di più') }}" placeholder="Scopri di più">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <!-- Settings -->
                                            <div class="card card">
                                                <div class="card-header">
                                                    <h6 class="mb-0">
                                                        <i class="ph-duotone ph-gear f-s-16 me-2"></i>
                                                        Impostazioni
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="existing_order" class="form-label">Ordine</label>
                                                        <input type="number" class="form-control" id="existing_order" name="order"
                                                               value="{{ old('order', 0) }}" min="0">
                                                        <div class="form-text">Ordine di visualizzazione (0 = primo)</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="existing_is_active" name="is_active" value="1"
                                                                   {{ old('is_active', true) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="existing_is_active">Attivo</label>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="existing_start_date" class="form-label">Data Inizio</label>
                                                        <input type="datetime-local" class="form-control" id="existing_start_date" name="start_date"
                                                               value="{{ old('start_date') }}">
                                                        <div class="form-text">Lascia vuoto per sempre attivo</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="existing_end_date" class="form-label">Data Fine</label>
                                                        <input type="datetime-local" class="form-control" id="existing_end_date" name="end_date"
                                                               value="{{ old('end_date') }}">
                                                        <div class="form-text">Lascia vuoto per sempre attivo</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('admin.carousels.index') }}" class="btn btn-secondary hover-effect">
                                                    <i class="ph-duotone ph-arrow-left f-s-16 me-2"></i>
                                                    Annulla
                                                </a>
                                                <button type="submit" class="btn btn-success hover-effect" id="createExistingBtn" disabled>
                                                    <i class="ph-duotone ph-check-circle f-s-16 me-2"></i>
                                                    Crea Slide
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
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    
    // Variabili locali alla funzione
    var searchTimeout;

// Bootstrap initialization removed

// Ripristina stato del form se ci sono errori di validazione
if (document.getElementById('content_type').value && document.getElementById('content_id').value) {
    document.getElementById('selectedContentPreview').style.display = 'block';
    document.getElementById('overrideFields').style.display = 'block';
    document.getElementById('createExistingBtn').disabled = false;

    // Mostra il tab dei contenuti esistenti
    const existingTab = document.getElementById('existing-tab');
    if (existingTab && typeof bootstrap !== 'undefined') {
        try {
            const tab = new bootstrap.Tab(existingTab);
            tab.show();
        } catch (error) {
            console.error('❌ Error showing tab:', error);
        }
    }
}

// Funzione per mostrare/nascondere sezioni
function toggleSearchSection(show) {
    const searchSection = document.getElementById('searchSection');
    const contentResults = document.getElementById('contentResults');

    if (searchSection) {
        searchSection.style.display = show ? 'block' : 'none';
    }

    if (contentResults) {
        contentResults.style.display = 'none';
    }
}

// Funzione per pulire i risultati
function clearResults() {
    const contentList = document.getElementById('contentList');
    if (contentList) {
        contentList.innerHTML = '';
    }
}

// Funzione per pulire il campo di ricerca
function clearSearchField() {
    const searchField = document.getElementById('content_search');
    if (searchField) {
        searchField.value = '';
    }
}

// Content type selection - Versione semplificata
function handleContentTypeChange() {
    const contentTypeSelect = document.getElementById('content_type_select');
    if (!contentTypeSelect) {
        console.error('❌ Content type select not found!');
        return;
    }

    const selectedValue = contentTypeSelect.value;
    console.log('Content type changed to:', selectedValue);

    if (selectedValue) {
        toggleSearchSection(true);
        clearResults();
        clearSearchField();
        
        // Esegui ricerca automatica
        setTimeout(() => {
            performSearch();
        }, 200);
    } else {
        toggleSearchSection(false);
        clearResults();
    }
}

// Setup event listeners
function setupEventListeners() {
    const contentTypeSelect = document.getElementById('content_type_select');
    if (!contentTypeSelect) {
        console.error('❌ Content type select not found during setup');
        return false;
    }

    // Remove existing listeners to avoid duplicates
    contentTypeSelect.removeEventListener('change', handleContentTypeChange);
    
    // Add new listener
    contentTypeSelect.addEventListener('change', handleContentTypeChange);
    
    console.log('Event listeners setup completed');
    return true;
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up event listeners');
    setupEventListeners();
});

// Debug: mostra tutti gli elementi trovati

// Test functions removed

// Funzione per mostrare i risultati
function displaySearchResults(data, contentType) {
    const contentList = document.getElementById('contentList');
    const contentResults = document.getElementById('contentResults');

    if (data.length === 0) {
        contentList.innerHTML = '<div class="text-center p-3 text-muted">Nessun contenuto trovato</div>';
        return;
    }

    contentResults.style.display = 'block';

    contentList.innerHTML = data.map(item => `
        <div class="content-item card mb-2 hover-effect" data-id="${item.id}" data-title="${item.title}" data-description="${item.description}" data-image="${item.image_url}" data-url="${item.url}">
            <div class="card-body p-2">
                <div class="d-flex align-items-center">
                    <img src="${item.image_url}" alt="${item.title}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 f-s-14">${item.title}</h6>
                        <p class="text-muted mb-0 f-s-12">${item.description}</p>
                        ${getContentTypeSpecificInfo(item, contentType)}
                    </div>
                    <button type="button" class="btn btn-sm btn-primary select-content-btn" onclick="">
                        <i class="ph-duotone ph-check f-s-14"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');

    // Add click handlers
    document.querySelectorAll('.select-content-btn').forEach((btn, index) => {
        
        btn.addEventListener('click', function(e) {
            
            const item = this.closest('.content-item');
            
            if (item) {
                selectContent(item);
            } else {
                console.error('❌ Could not find content-item parent');
            }
        });
    });
}

// Setup test button listener - REMOVED

// Global click handler as backup
document.addEventListener('click', function(e) {
    if (e.target.closest('.select-content-btn')) {
        
        const btn = e.target.closest('.select-content-btn');
        const item = btn.closest('.content-item');
        if (item) {
            selectContent(item);
        }
    }
});

// Event listener per il pulsante "Crea Slide" nel modal
document.addEventListener('DOMContentLoaded', function() {
    const modalCreateBtn = document.getElementById('modalCreateBtn');
    if (modalCreateBtn) {
        modalCreateBtn.addEventListener('click', function() {
            
            submitModalForm();
        });
    }
});

// Search functionality
document.getElementById('searchBtn').addEventListener('click', performSearch);
document.getElementById('content_search').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(performSearch, 500);
});

function performSearch() {
    
    const contentType = document.getElementById('content_type_select').value;
    const query = document.getElementById('content_search').value;
    const contentList = document.getElementById('contentList');
    const contentResults = document.getElementById('contentResults');

    

    if (!contentType) {

        return;
    }

    // Show loading
    contentList.innerHTML = '<div class="text-center p-3"><i class="ph-duotone ph-spinner f-s-24 text-primary"></i><p class="mt-2">Ricerca in corso...</p></div>';
    contentResults.style.display = 'block';

    fetch(`{{ route('admin.carousels.search-content') }}?type=${contentType}&query=${encodeURIComponent(query)}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        credentials: 'same-origin'
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            displaySearchResults(data, contentType);
        })
        .catch(error => {
            console.error('Search error:', error);
            contentList.innerHTML = `
                <div class="text-center p-3">
                    <div class="text-danger mb-2">Errore durante la ricerca</div>
                    <small class="text-muted">Errore: ${error.message}</small>
                </div>
            `;
        });
}

function getContentTypeSpecificInfo(item, contentType) {
    switch (contentType) {
        case 'video':
            return `<small class="text-muted">${item.user} • ${item.views} visualizzazioni</small>`;
        case 'event':
            return `<small class="text-muted">${item.organizer} • ${item.date} • ${item.location}</small>`;
        case 'user':
            return `<small class="text-muted">${item.videos_count} video • ${item.location || 'N/A'}</small>`;
        case 'poem':
            return `<small class="text-muted">${item.user} • ${item.views} visualizzazioni • ${item.likes} like</small>`;
        case 'article':
            return `<small class="text-muted">${item.user} • ${item.views} visualizzazioni • ${item.category}</small>`;
        default:
            return '';
    }
}

function selectContent(item) {

    // Rimuovi l'alert per debug
    // alert('🎯 Contenuto selezionato: ' + item.dataset.title);

    const contentType = document.getElementById('content_type_select').value;
    const contentId = item.dataset.id;
    const contentTitle = item.dataset.title;
    const contentDescription = item.dataset.description;
    const contentImage = item.dataset.image;
    const contentUrl = item.dataset.url;



    // Update hidden fields
    const contentTypeField = document.getElementById('content_type');
    const contentIdField = document.getElementById('content_id');

    if (contentTypeField && contentIdField) {
        contentTypeField.value = contentType;
        contentIdField.value = contentId;
    } else {
        console.error('❌ Hidden fields not found');
    }

    // Update preview
    const selectedImage = document.getElementById('selectedImage');
    const selectedTitle = document.getElementById('selectedTitle');
    const selectedDescription = document.getElementById('selectedDescription');
    const selectedContentPreview = document.getElementById('selectedContentPreview');

    if (selectedImage && selectedTitle && selectedDescription && selectedContentPreview) {
        selectedImage.src = contentImage;
        selectedTitle.textContent = contentTitle;
        selectedDescription.textContent = contentDescription;
                selectedContentPreview.style.display = 'block';
        
    } else {
        console.error('❌ Preview elements not found');
    }

    // Show override fields
    const overrideFields = document.getElementById('overrideFields');
    if (overrideFields) {
                overrideFields.style.display = 'block';

    } else {
        console.error('❌ Override fields not found');
    }

    // Enable create button
    const createExistingBtn = document.getElementById('createExistingBtn');
    if (createExistingBtn) {
        createExistingBtn.disabled = false;

    } else {
        console.error('❌ Create button not found');
    }

    // Update form action
    const existingContentForm = document.getElementById('existingContentForm');
    if (existingContentForm) {
        existingContentForm.action = '{{ route("admin.carousels.store") }}';

    } else {
        console.error('❌ Form not found');
    }

    // Apri il modal invece di aggiornare la pagina
    openContentSelectionModal(item);
}

// Funzione per aprire il modal di selezione contenuto
function openContentSelectionModal(item) {

    const contentType = document.getElementById('content_type_select').value;
    const contentId = item.dataset.id;
    const contentTitle = item.dataset.title;
    const contentDescription = item.dataset.description;
    const contentImage = item.dataset.image;
    const contentUrl = item.dataset.url;

    // Aggiorna il modal con i dati del contenuto
    document.getElementById('modalSelectedImage').src = contentImage;
    document.getElementById('modalSelectedTitle').textContent = contentTitle;
    document.getElementById('modalSelectedDescription').textContent = contentDescription;

    // Aggiungi informazioni specifiche per tipo
    const modalSelectedInfo = document.getElementById('modalSelectedInfo');
    switch (contentType) {
        case 'video':
            modalSelectedInfo.textContent = `{{ __('common.video') }} • ${item.dataset.user || 'N/A'} • ${item.dataset.views || '0'} visualizzazioni`;
            break;
        case 'event':
            modalSelectedInfo.textContent = `{{ __('invitations.event') }} • ${item.dataset.organizer || 'N/A'} • ${item.dataset.date || 'N/A'} • ${item.dataset.location || 'N/A'}`;
            break;
        case 'user':
            modalSelectedInfo.textContent = `{{ __('permissions.user') }} • ${item.dataset.videos_count || '0'} video • ${item.dataset.location || 'N/A'}`;
            break;
        case 'poem':
            modalSelectedInfo.textContent = `Poesia • ${item.dataset.user || 'N/A'} • ${item.dataset.views || '0'} visualizzazioni • ${item.dataset.likes || '0'} like`;
            break;
        case 'article':
            modalSelectedInfo.textContent = `Articolo • ${item.dataset.user || 'N/A'} • ${item.dataset.views || '0'} visualizzazioni • ${item.dataset.category || 'N/A'}`;
            break;
        default:
            modalSelectedInfo.textContent = '';
    }

    // Pre-popola i campi con i valori originali
    document.getElementById('modal_override_title').value = contentTitle;
    document.getElementById('modal_override_description').value = contentDescription;
    document.getElementById('modal_override_link_url').value = contentUrl;

    // Aggiorna i campi nascosti del form
    document.getElementById('content_type').value = contentType;
    document.getElementById('content_id').value = contentId;

    // Apri il modal
    const modal = new bootstrap.Modal(document.getElementById('contentSelectionModal'));
    modal.show();


}

// Funzione per inviare il form dal modal
function submitModalForm() {

    // Raccogli i dati dal modal
    const formData = new FormData();

    // Campi nascosti
    formData.append('content_type', document.getElementById('content_type').value);
    formData.append('content_id', document.getElementById('content_id').value);

    // Campi di personalizzazione
    formData.append('title', document.getElementById('modal_override_title').value);
    formData.append('description', document.getElementById('modal_override_description').value);
    formData.append('link_url', document.getElementById('modal_override_link_url').value);
    formData.append('link_text', document.getElementById('modal_override_link_text').value);
    formData.append('order', document.getElementById('modal_order').value);
    formData.append('is_active', document.getElementById('modal_is_active').checked ? '1' : '0');

    // CSRF token
    formData.append('_token', '{{ csrf_token() }}');



    // Mostra loading state
    const submitBtn = document.getElementById('modalCreateBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="ph-duotone ph-spinner f-s-16 me-2"></i>Creazione...';
    submitBtn.disabled = true;

    // {{ __('videos.send') }} la richiesta
    fetch('{{ route("admin.carousels.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {

        // Controlla se la risposta è JSON
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            // Se non è JSON, leggi il testo per debug
            return response.text().then(text => {
                console.error('❌ Non-JSON response received:', text.substring(0, 500));
                throw new Error('Server returned HTML instead of JSON');
            });
        }
    })
    .then(data => {


        if (data.success) {
            // Chiudi il modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('contentSelectionModal'));
            modal.hide();

            // Mostra successo
            Swal.fire({
                icon: 'success',
                title: 'Slide Creata!',
                text: data.message || 'La slide del carosello è stata creata con successo.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Redirect alla lista caroselli
                window.location.href = '{{ route("admin.carousels.index") }}';
            });
        } else {
            throw new Error(data.message || 'Errore sconosciuto');
        }
    })
    .catch(error => {
        console.error('❌ Error creating carousel:', error);

        // Ripristina il pulsante
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;

        // Mostra errore
        Swal.fire({
            icon: 'error',
            title: 'Errore!',
            text: 'Si è verificato un errore durante la creazione della slide.',
            confirmButtonText: 'OK'
        });
    });
}

// Form submit handling
document.getElementById('existingContentForm').addEventListener('submit', function(e) {

    // Show loading state
    const submitBtn = document.getElementById('createExistingBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="ph-duotone ph-spinner f-s-16 me-2"></i>Creazione...';
    submitBtn.disabled = true;

    // Re-enable after 5 seconds if something goes wrong
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 5000);
});

// Tab switching
document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
    tab.addEventListener('shown.bs.tab', function(e) {
        // Reset form when switching tabs
        if (e.target.id === 'upload-tab') {
            document.getElementById('existingContentForm').reset();
            document.getElementById('selectedContentPreview').style.display = 'none';
            document.getElementById('overrideFields').style.display = 'none';
            document.getElementById('createExistingBtn').disabled = true;
        }
    });
});

})(); // Chiude la funzione IIFE
</script>

<!-- Modal per Selezione Contenuto -->
<div class="modal fade" id="contentSelectionModal" tabindex="-1" aria-labelledby="contentSelectionModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary-800">
                <h5 class="modal-title text-white" id="contentSelectionModalLabel">
                    <i class="ph-duotone ph-check-circle f-s-16 me-2"></i>
                    Contenuto Selezionato
                </h5>
                <button type="button" class="fs-5 border-0 bg-none text-white" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark fs-3"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Preview del contenuto selezionato -->
                <div class="mb-4">
                    <div class="card card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img id="modalSelectedImage" src="" alt="" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <h6 id="modalSelectedTitle" class="mb-1"></h6>
                                    <p id="modalSelectedDescription" class="text-muted mb-0 f-s-12"></p>
                                    <small id="modalSelectedInfo" class="text-muted"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campi di personalizzazione -->
                <div class="card card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="ph-duotone ph-pencil f-s-16 me-2"></i>
                            Personalizza (Opzionale)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="modal_override_title" class="form-label">Titolo Personalizzato</label>
                                    <input type="text" class="form-control" id="modal_override_title" name="title" placeholder="Lascia vuoto per usare il titolo originale">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="modal_override_link_url" class="form-label">URL Link Personalizzato</label>
                                    <input type="url" class="form-control" id="modal_override_link_url" name="link_url" placeholder="Lascia vuoto per usare il link originale">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="modal_override_description" class="form-label">Descrizione Personalizzata</label>
                            <textarea class="form-control" id="modal_override_description" name="description" rows="3" placeholder="Lascia vuoto per usare la descrizione originale"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="modal_override_link_text" class="form-label">Testo Link Personalizzato</label>
                                    <input type="text" class="form-control" id="modal_override_link_text" name="link_text" value="Scopri di più" placeholder="Scopri di più">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="modal_order" class="form-label">Ordine</label>
                                    <input type="number" class="form-control" id="modal_order" name="order" value="0" min="0">
                                    <div class="form-text">Ordine di visualizzazione (0 = primo)</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="modal_is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="modal_is_active">Attivo</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="ph-duotone ph-x f-s-16 me-2"></i>
                    Annulla
                </button>
                <button type="button" class="btn btn-primary" id="modalCreateBtn">
                    <i class="ph-duotone ph-plus f-s-16 me-2"></i>
                    Crea Slide
                </button>
            </div>
        </div>
    </div>
</div>
@endpush
