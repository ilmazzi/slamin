@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="ph ph-search f-s-48 text-warning mb-3"></i>
                        <h1 class="display-4 text-warning fw-bold">404</h1>
                        <h2 class="h3 text-dark mb-3">Pagina Non Trovata</h2>
                        <p class="text-muted mb-4">
                            La pagina che stai cercando non esiste o è stata spostata. 
                            Controlla l'URL e riprova.
                        </p>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="ph ph-house me-2"></i>Torna alla Home
                        </a>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="ph ph-arrow-left me-2"></i>Torna Indietro
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
