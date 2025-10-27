@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card card-light-primary">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="ph ph-envelope-simple display-1 text-primary"></i>
                    </div>

                    <h4 class="mb-3">📧 Verifica la tua email</h4>

                    <p class="text-muted mb-4">
                        Ti abbiamo inviato un'email di verifica all'indirizzo <strong>{{ auth()->user()->email }}</strong>
                    </p>

                    <p class="text-muted mb-4">
                        Clicca sul link nell'email per attivare il tuo account e accedere a tutte le funzionalità di Slamin.
                    </p>

                    @if (session('success'))
                        <div class="alert alert-success mb-4">
                            <i class="ph ph-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('message'))
                        <div class="alert alert-info mb-4">
                            <i class="ph ph-info me-2"></i>
                            {{ session('message') }}
                        </div>
                    @endif

                    <div class="card card-light-secondary mb-4">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="ph ph-question me-2"></i>
                                Non hai ricevuto l'email?
                            </h6>
                            <p class="card-text small text-muted mb-3">
                                Controlla la cartella spam o promozioni. Se non la trovi, puoi richiedere un nuovo invio.
                            </p>

                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="ph ph-paper-plane-tilt me-2"></i>
                                    Invia di nuovo l'email
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-light">
                            <i class="ph ph-arrow-left me-2"></i>
                            Torna alla Dashboard
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-secondary">
                                <i class="ph ph-sign-out me-2"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="ph ph-info me-1"></i>
                    Hai bisogno di aiuto? <a href="mailto:support@slamin.it" class="text-decoration-none">Contattaci</a>
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
