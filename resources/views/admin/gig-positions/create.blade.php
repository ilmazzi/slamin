@extends('layout.master')

@section('title', 'Crea Posizione Ingaggio')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="page-title-box">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0 small">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-decoration-none">
                                <i class="ph ph-house me-1"></i>Home
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                                <i class="ph ph-gear me-1"></i>Admin
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.gig-positions.index') }}" class="text-decoration-none">
                                Posizioni Ingaggi
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Crea Posizione
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Crea Nuova Posizione di Ingaggio</h4>

                        <form action="{{ route('admin.gig-positions.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Nome -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nome Posizione <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}"
                                           placeholder="Es: Artista/Poeta" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Nome visualizzato agli utenti</small>
                                </div>

                                <!-- Chiave -->
                                <div class="col-md-6 mb-3">
                                    <label for="key" class="form-label">Chiave Unica <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('key') is-invalid @enderror"
                                           id="key" name="key" value="{{ old('key') }}"
                                           placeholder="Es: artist_poet" required>
                                    @error('key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Chiave unica per identificare la posizione (solo lettere, numeri e underscore)</small>
                                </div>

                                <!-- Descrizione -->
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Descrizione</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="3"
                                              placeholder="Descrivi brevemente questa posizione...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Descrizione opzionale della posizione</small>
                                </div>

                                <!-- Ordine -->
                                <div class="col-md-6 mb-3">
                                    <label for="sort_order" class="form-label">Ordine di Visualizzazione</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}"
                                           min="0" step="1">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Ordine in cui apparirà nelle liste (0 = primo)</small>
                                </div>

                                <!-- Stato -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Posizione Attiva
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Se disattivata, non sarà visibile agli utenti</small>
                                </div>
                            </div>

                            <!-- Azioni -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.gig-positions.index') }}" class="btn btn-secondary">
                                            <i class="ph ph-arrow-left me-2"></i>Annulla
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ph ph-check me-2"></i>Crea Posizione
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
