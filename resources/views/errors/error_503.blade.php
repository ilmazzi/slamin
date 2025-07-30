@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="ph ph-gear f-s-48 text-info mb-3"></i>
                        <h1 class="display-4 text-info fw-bold">503</h1>
                        <h2 class="h3 text-dark mb-3">Servizio Non Disponibile</h2>
                        <p class="text-muted mb-4">
                            Il servizio è temporaneamente non disponibile per manutenzione. 
                            Riprova tra qualche minuto.
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
