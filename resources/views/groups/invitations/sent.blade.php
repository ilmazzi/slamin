@extends('layout.master')

@section('title', 'Inviti Inviati ai Gruppi')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-paper-plane me-2 text-primary"></i>
                        Inviti Inviati ai Gruppi
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('group-invitations.index') }}" class="btn btn-outline-primary">
                            <i class="ph-duotone ph-envelope me-2"></i>
                            Inviti Ricevuti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($invitations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Gruppo</th>
                                        <th>Invitato</th>
                                        <th>Messaggio</th>
                                        <th>Data Invito</th>
                                        <th>Scade il</th>
                                        <th>Stato</th>
                                        <th>Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invitations as $invitation)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        @if($invitation->group->avatar)
                                                            <img src="{{ asset('storage/' . $invitation->group->avatar) }}"
                                                                 class="rounded-circle"
                                                                 width="32"
                                                                 height="32"
                                                                 alt="{{ $invitation->group->name }}">
                                                        @else
                                                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                                                 style="width: 32px; height: 32px;">
                                                                <i class="ph-duotone ph-users text-white" style="font-size: 16px;"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-medium">{{ $invitation->group->name }}</div>
                                                        <small class="text-muted">{{ $invitation->group->description ? Str::limit($invitation->group->description, 30) : 'Nessuna descrizione' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($invitation->user)
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($invitation->user) }}"
                                                                 class="rounded-circle"
                                                                 width="24"
                                                                 height="24"
                                                                 alt="{{ $invitation->user->name }}">
                                                        </div>
                                                        <span>{{ $invitation->user->name }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Utente non trovato</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($invitation->message)
                                                    <span class="text-muted">{{ Str::limit($invitation->message, 50) }}</span>
                                                @else
                                                    <span class="text-muted">Nessun messaggio</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $invitation->created_at->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                @if($invitation->expires_at)
                                                    <small class="text-muted">{{ $invitation->expires_at->format('d/m/Y H:i') }}</small>
                                                @else
                                                    <small class="text-muted">Non scade</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($invitation->isPending())
                                                    @if($invitation->isExpired())
                                                        <span class="badge bg-danger">Scaduto</span>
                                                    @else
                                                        <span class="badge bg-warning">In Attesa</span>
                                                    @endif
                                                @elseif($invitation->isAccepted())
                                                    <span class="badge bg-success">Accettato</span>
                                                @elseif($invitation->isDeclined())
                                                    <span class="badge bg-secondary">Rifiutato</span>
                                                @else
                                                    <span class="badge bg-light text-dark">{{ ucfirst($invitation->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if($invitation->isPending())
                                                        @if($invitation->isExpired())
                                                            <form action="{{ route('group-invitations.resend', $invitation) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Rinvia Invito">
                                                                    <i class="ph-duotone ph-arrow-clockwise"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <form action="{{ route('group-invitations.cancel', $invitation) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancella Invito" onclick="return confirm('Sei sicuro di voler cancellare questo invito?')">
                                                                <i class="ph-duotone ph-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <a href="{{ route('group-invitations.show', $invitation) }}" class="btn btn-sm btn-outline-primary" title="Visualizza Dettagli">
                                                        <i class="ph-duotone ph-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $invitations->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="ph-duotone ph-paper-plane text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="text-muted">Nessun invito inviato</h5>
                            <p class="text-muted">Non hai ancora inviato nessun invito ai gruppi.</p>
                            <a href="{{ route('groups.index') }}" class="btn btn-primary">
                                <i class="ph-duotone ph-users me-2"></i>
                                Esplora i Gruppi
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
