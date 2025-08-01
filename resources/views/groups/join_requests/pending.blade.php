@extends('layout.master')

@section('title', 'Richieste di Partecipazione Pendenti')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-hand-waving me-2 text-primary"></i>
                        Richieste di Partecipazione Pendenti
                    </h4>
                    <div>
                        <a href="{{ route('groups.show', $group) }}" class="btn btn-light">
                            <i class="ph-duotone ph-arrow-left me-2"></i>
                            Torna al Gruppo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche -->
    <div class="row mb-4">
        <div class="col-12 col-md-3">
            <div class="card card-light-primary">
                <div class="card-body text-center">
                    <i class="ph-duotone ph-clock text-primary f-s-32 mb-2"></i>
                    <h4 class="text-primary mb-1">{{ $requests->where('status', 'pending')->count() }}</h4>
                    <p class="text-muted mb-0">In Attesa</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card card-light-success">
                <div class="card-body text-center">
                    <i class="ph-duotone ph-check-circle text-success f-s-32 mb-2"></i>
                    <h4 class="text-success mb-1">{{ $requests->where('status', 'accepted')->count() }}</h4>
                    <p class="text-muted mb-0">Accettate</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card card-light-danger">
                <div class="card-body text-center">
                    <i class="ph-duotone ph-x-circle text-danger f-s-32 mb-2"></i>
                    <h4 class="text-danger mb-1">{{ $requests->where('status', 'declined')->count() }}</h4>
                    <p class="text-muted mb-0">Rifiutate</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card card-light-warning">
                <div class="card-body text-center">
                    <i class="ph-duotone ph-users text-warning f-s-32 mb-2"></i>
                    <h4 class="text-warning mb-1">{{ $requests->count() }}</h4>
                    <p class="text-muted mb-0">Totali</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista richieste -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-list me-2 text-primary"></i>
                        Richieste di Partecipazione
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($requests as $request)
                    <div class="d-flex align-items-center mb-3 p-3 border rounded">
                        <!-- Avatar utente -->
                        <div class="flex-shrink-0 me-3">
                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($request->user) }}"
                                 alt="{{ $request->user->name }}"
                                 class="rounded-circle"
                                 style="width: 50px; height: 50px; object-fit: cover;"
                                 onerror="this.src='{{ asset('assets/images/avatar/default-avatar.webp') }}'">
                        </div>

                        <!-- Informazioni richiesta -->
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $request->user->name }}</h6>
                            <p class="text-muted mb-1">{{ $request->user->getPrivacySafeIdentifier() }}</p>
                            @if($request->message)
                                <p class="text-muted mb-1"><small>"{{ $request->message }}"</small></p>
                            @endif
                            <small class="text-muted">
                                <i class="ph-duotone ph-calendar me-1"></i>
                                Richiesta il {{ $request->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>

                        <!-- Status e azioni -->
                        <div class="flex-shrink-0">
                            @if($request->status === 'pending')
                                <span class="badge bg-warning me-2">In Attesa</span>
                                <div class="btn-group" role="group">
                                    <form action="{{ route('group-requests.accept', $request) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="ph-duotone ph-check me-1"></i>
                                            Accetta
                                        </button>
                                    </form>
                                    <form action="{{ route('group-requests.decline', $request) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="ph-duotone ph-x me-1"></i>
                                            Rifiuta
                                        </button>
                                    </form>
                                </div>
                            @elseif($request->status === 'accepted')
                                <span class="badge bg-success me-2">Accettata</span>
                                <small class="text-muted">
                                    {{ $request->updated_at->format('d/m/Y H:i') }}
                                </small>
                            @elseif($request->status === 'declined')
                                <span class="badge bg-danger me-2">Rifiutata</span>
                                <small class="text-muted">
                                    {{ $request->updated_at->format('d/m/Y H:i') }}
                                </small>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="ph-duotone ph-hand-waving text-muted f-s-64 mb-3"></i>
                        <h5 class="text-muted">Nessuna richiesta di partecipazione</h5>
                        <p class="text-muted">Non ci sono richieste di partecipazione per questo gruppo.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Paginazione -->
    @if($requests->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ $requests->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
