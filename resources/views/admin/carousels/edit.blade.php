@extends('layout.master')

@section('title', __('permissions.modify') . ' Slide Carosello')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">

        <!-- Header -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">{{ __('permissions.modify') }} Slide Carosello</h4>
                
            </div>
        </div>

        <!-- Form -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-pencil-circle f-s-16 me-2"></i>
                            {{ __('permissions.modify') }} Slide: {{ $carousel->title }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.carousels.update', $carousel) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-8">
                                    <!-- Basic Info -->
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Titolo *</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                               value="{{ old('title', $carousel->title) }}" required maxlength="255">
                                        <div class="form-text">Titolo della slide che apparirà nel carosello</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Descrizione</label>
                                        <textarea class="form-control" id="description" name="description"
                                                  rows="3" maxlength="1000">{{ old('description', $carousel->description) }}</textarea>
                                        <div class="form-text">Descrizione opzionale che apparirà sotto il titolo</div>
                                    </div>

                                    <!-- {{ __('common.media_section') }} Upload -->
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Immagine</label>
                                        
                                        @if($carousel->isContentReference() && $carousel->content_image_url)
                                            <!-- Toggle per scegliere tra foto originale e caricata -->
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="use_original_image" name="use_original_image" value="1" checked>
                                                    <label class="form-check-label" for="use_original_image">
                                                        Usa immagine originale del contenuto
                                                    </label>
                                                </div>
                                                <div class="form-text">Se attivo, usa l'immagine del contenuto originale. Se disattivo, puoi caricare una nuova immagine.</div>
                                            </div>
                                            
                                            <!-- Mostra immagine originale -->
                                            <div class="mb-3" id="original-image-preview">
                                                <small class="text-muted">Immagine originale del contenuto:</small>
                                                <img src="{{ $carousel->content_image_url }}" alt="Original" class="img-thumbnail ms-2" style="height: 80px;">
                                            </div>
                                            
                                            <!-- Campo per caricare nuova immagine (nascosto di default) -->
                                            <div id="custom-image-upload" style="display: none;">
                                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                                <div class="form-text">Carica una nuova immagine personalizzata</div>
                                            </div>
                                        @else
                                            <!-- Upload normale per contenuti non referenziati -->
                                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                            <div class="form-text">Lascia vuoto per mantenere l'immagine attuale</div>
                                        @endif
                                        
                                        @if($carousel->image_path)
                                            <div class="mt-2">
                                                <small class="text-muted">Immagine attuale:</small>
                                                <img src="{{ $carousel->imageUrl }}" alt="Current" class="img-thumbnail ms-2" style="height: 50px;">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="video" class="form-label">{{ __('common.video') }} (Opzionale)</label>
                                        <input type="file" class="form-control" id="video" name="video" accept="video/*">
                                        <div class="form-text">Lascia vuoto per mantenere il video attuale</div>
                                        @if($carousel->video_path)
                                            <div class="mt-2">
                                                <small class="text-muted">{{ __('common.video') }} attuale:</small>
                                                <video class="ms-2" style="height: 50px;" controls>
                                                    <source src="{{ $carousel->videoUrl }}" type="video/mp4">
                                                </video>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Link -->
                                    <div class="mb-3">
                                        <label for="link_url" class="form-label">URL Link</label>
                                        <input type="url" class="form-control" id="link_url" name="link_url"
                                               value="{{ old('link_url', $carousel->link_url) }}" placeholder="https://...">
                                        <div class="form-text">URL opzionale per il link del pulsante</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="link_text" class="form-label">Testo Link</label>
                                        <input type="text" class="form-control" id="link_text" name="link_text"
                                               value="{{ old('link_text', $carousel->link_text) }}" maxlength="100" placeholder="Scopri di più">
                                        <div class="form-text">Testo del pulsante (richiede URL)</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Settings -->
                                    <div class="card card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="ph-duotone ph-gear f-s-16 me-2"></i>
                                                Impostazioni
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="order" class="form-label">Ordine</label>
                                                <input type="number" class="form-control" id="order" name="order"
                                                       value="{{ old('order', $carousel->order) }}" min="0">
                                                <div class="form-text">Ordine di visualizzazione (0 = primo)</div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_active"
                                                           name="is_active" value="1" {{ old('is_active', $carousel->is_active) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_active">
                                                        Slide Attiva
                                                    </label>
                                                </div>
                                                <div class="form-text">Mostra questa slide nel carosello</div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="start_date" class="form-label">Data Inizio</label>
                                                <input type="datetime-local" class="form-control" id="start_date"
                                                       name="start_date" value="{{ old('start_date', $carousel->start_date ? $carousel->start_date->format('Y-m-d\TH:i') : '') }}">
                                                <div class="form-text">Quando iniziare a mostrare la slide</div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="end_date" class="form-label">Data Fine</label>
                                                <input type="datetime-local" class="form-control" id="end_date"
                                                       name="end_date" value="{{ old('end_date', $carousel->end_date ? $carousel->end_date->format('Y-m-d\TH:i') : '') }}">
                                                <div class="form-text">Quando smettere di mostrare la slide</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Current Status -->
                                    <div class="card card mt-3">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="ph-duotone ph-info-circle f-s-16 me-2"></i>
                                                {{ __('invitations.status') }} Attuale
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <strong>{{ __('invitations.status') }}:</strong>
                                                @if($carousel->isCurrentlyActive())
                                                    <span class="badge bg-success ms-2">Attivo</span>
                                                @else
                                                    <span class="badge bg-danger ms-2">Inattivo</span>
                                                @endif
                                            </div>
                                            <div class="mb-2">
                                                <strong>Ordine:</strong> {{ $carousel->order }}
                                            </div>
                                            <div class="mb-2">
                                                <strong>Creata:</strong> {{ $carousel->created_at->format('d/m/Y H:i') }}
                                            </div>
                                            <div class="mb-0">
                                                <strong>Aggiornata:</strong> {{ $carousel->updated_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('admin.carousels.index') }}" class="btn btn-secondary hover-effect">
                                            <i class="ph-duotone ph-arrow-left f-s-16 me-2"></i>
                                            Annulla
                                        </a>
                                        <button type="submit" class="btn btn-success hover-effect">
                                            <i class="ph-duotone ph-check-circle f-s-16 me-2"></i>
                                            Aggiorna Slide
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

@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    
    // Gestione toggle per immagine originale
    const useOriginalImageToggle = document.getElementById('use_original_image');
    const originalImagePreview = document.getElementById('original-image-preview');
    const customImageUpload = document.getElementById('custom-image-upload');
    
    if (useOriginalImageToggle && originalImagePreview && customImageUpload) {
        useOriginalImageToggle.addEventListener('change', function() {
            if (this.checked) {
                // Usa immagine originale
                originalImagePreview.style.display = 'block';
                customImageUpload.style.display = 'none';
                // Disabilita il campo file
                const fileInput = customImageUpload.querySelector('input[type="file"]');
                if (fileInput) {
                    fileInput.disabled = true;
                    fileInput.value = '';
                }
            } else {
                // Usa immagine personalizzata
                originalImagePreview.style.display = 'none';
                customImageUpload.style.display = 'block';
                // Abilita il campo file
                const fileInput = customImageUpload.querySelector('input[type="file"]');
                if (fileInput) {
                    fileInput.disabled = false;
                }
            }
        });
        
        // Inizializza lo stato
        useOriginalImageToggle.dispatchEvent(new Event('change'));
    }
    
})();
</script>
@endpush
