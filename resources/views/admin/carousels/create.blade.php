@extends('layout.master')

@section('title', 'Nuova Slide Carosello')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">

        <!-- Header -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Nuova Slide Carosello</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('admin.carousels.index') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-images f-s-16"></i> Carosello
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">Nuova Slide</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-plus-circle f-s-16 me-2"></i>
                            Crea Nuova Slide
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

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

                                    <!-- Media Upload -->
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Immagine *</label>
                                        <input type="file" class="form-control" id="image" name="image"
                                               accept="image/*" required>
                                        <div class="form-text">Immagine principale della slide (JPEG, PNG, GIF - max 2MB)</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="video" class="form-label">Video (Opzionale)</label>
                                        <input type="file" class="form-control" id="video" name="video"
                                               accept="video/*">
                                        <div class="form-text">Video opzionale che sostituirà l'immagine (MP4, AVI, MOV - max 10MB)</div>
                                    </div>

                                    <!-- Link -->
                                    <div class="mb-3">
                                        <label for="link_url" class="form-label">URL Link</label>
                                        <input type="url" class="form-control" id="link_url" name="link_url"
                                               value="{{ old('link_url') }}" placeholder="https://...">
                                        <div class="form-text">URL opzionale per il link del pulsante</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="link_text" class="form-label">Testo Link</label>
                                        <input type="text" class="form-control" id="link_text" name="link_text"
                                               value="{{ old('link_text') }}" maxlength="100" placeholder="Scopri di più">
                                        <div class="form-text">Testo del pulsante (richiede URL)</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Settings -->
                                    <div class="card card-light-info">
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
                                                       value="{{ old('order', 0) }}" min="0">
                                                <div class="form-text">Ordine di visualizzazione (0 = primo)</div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_active"
                                                           name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_active">
                                                        Slide Attiva
                                                    </label>
                                                </div>
                                                <div class="form-text">Mostra questa slide nel carosello</div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="start_date" class="form-label">Data Inizio</label>
                                                <input type="datetime-local" class="form-control" id="start_date"
                                                       name="start_date" value="{{ old('start_date') }}">
                                                <div class="form-text">Quando iniziare a mostrare la slide</div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="end_date" class="form-label">Data Fine</label>
                                                <input type="datetime-local" class="form-control" id="end_date"
                                                       name="end_date" value="{{ old('end_date') }}">
                                                <div class="form-text">Quando smettere di mostrare la slide</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Preview -->
                                    <div class="card card-light-success mt-3">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="ph-duotone ph-eye f-s-16 me-2"></i>
                                                Anteprima
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="preview" class="text-center">
                                                <i class="ph-duotone ph-image f-s-48 text-muted mb-2"></i>
                                                <p class="text-muted small">L'anteprima apparirà qui dopo aver selezionato un'immagine</p>
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

@endsection

@push('scripts')
<script>
// Preview functionality
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            preview.innerHTML = `
                <img src="${e.target.result}" class="img-fluid rounded mb-2" style="max-height: 200px;">
                <p class="text-muted small">${file.name}</p>
            `;
        };
        reader.readAsDataURL(file);
    }
});

// Link text validation
document.getElementById('link_url').addEventListener('input', function() {
    const linkText = document.getElementById('link_text');
    if (this.value && !linkText.value) {
        linkText.placeholder = 'Scopri di più';
    }
});
</script>
@endpush
