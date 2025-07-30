@extends('layout.master')

@section('title', 'Inviti Pendenti - ' . $group->name)

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-clock me-2 text-warning"></i>
                        Inviti Pendenti - {{ $group->name }}
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('groups.invitations.create', $group) }}" class="btn btn-success">
                            <i class="ph-duotone ph-plus me-2"></i>
                            Nuovo Invito
                        </a>
                        <a href="{{ route('groups.members.index', $group) }}" class="btn btn-light">
                            <i class="ph-duotone ph-arrow-left me-2"></i>
                            Indietro
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($invitations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Utente</th>
                                        <th>Messaggio</th>
                                        <th>Inviato da</th>
                                        <th>Data Invito</th>
                                        <th>Scade il</th>
                                        <th>Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invitations as $invitation)
                                        <tr>
                                                                                        <td>
                                                @if($invitation->user)
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($invitation->user) }}"
                                                                 class="rounded-circle"
                                                                 width="32"
                                                                 height="32"
                                                                 alt="{{ $invitation->user->name }}">
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="fw-medium">{{ $invitation->user->name }}</div>
                                                            <small class="text-muted">{{ $invitation->user->email }}</small>
                                                        </div>
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
                                                @if($invitation->invitedBy)
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($invitation->invitedBy) }}"
                                                                 class="rounded-circle"
                                                                 width="24"
                                                                 height="24"
                                                                 alt="{{ $invitation->invitedBy->name }}">
                                                        </div>
                                                        <span>{{ $invitation->invitedBy->name }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Utente non trovato</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $invitation->created_at->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                @if($invitation->expires_at)
                                                    <small class="text-muted">{{ $invitation->expires_at->format('d/m/Y H:i') }}</small>
                                                    @if($invitation->isExpired())
                                                        <span class="badge bg-danger ms-1">Scaduto</span>
                                                    @elseif($invitation->expires_at->diffInHours(now()) < 24)
                                                        <span class="badge bg-warning ms-1">Scade presto</span>
                                                    @endif
                                                @else
                                                    <small class="text-muted">Non scade</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if($invitation->isExpired())
                                                        <span class="badge bg-danger">Scaduto</span>
                                                    @else
                                                                                                                                                                        <form action="{{ route('group-invitations.resend', $invitation) }}"
                                                              method="POST"
                                                              class="d-inline"
                                                              onsubmit="return confirm('Rinviare l\'invito?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                                <i class="ph-duotone ph-arrow-clockwise me-1"></i>
                                                                Rinvio
                                                            </button>
                                                        </form>
                                                                                                                                                                        <form action="{{ route('group-invitations.cancel', $invitation) }}"
                                                              method="POST"
                                                              class="d-inline"
                                                              onsubmit="return confirm('Cancellare l\'invito?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="ph-duotone ph-x me-1"></i>
                                                                Cancella
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $invitations->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="ph-duotone ph-clock text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-muted">Nessun invito pendente</h5>
                            <p class="text-muted">Non ci sono inviti in attesa di risposta per questo gruppo.</p>
                            <a href="{{ route('groups.invitations.create', $group) }}" class="btn btn-success">
                                <i class="ph-duotone ph-plus me-2"></i>
                                Invia il primo invito
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card card-light-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-clock text-info" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Inviti Pendenti</h6>
                            <h4 class="mb-0">{{ $invitations->total() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-light-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-warning text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Scadono Presto</h6>
                            <h4 class="mb-0">{{ $invitations->where('expires_at', '>', now())->where('expires_at', '<', now()->addDay())->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-light-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-x-circle text-danger" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Scaduti</h6>
                            <h4 class="mb-0">{{ $invitations->where('expires_at', '<', now())->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
