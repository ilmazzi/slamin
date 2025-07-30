@extends('layout.master')

@section('title', 'I Miei Inviti ai Gruppi')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-envelope me-2 text-primary"></i>
                        I Miei Inviti ai Gruppi
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('group-invitations.sent') }}" class="btn btn-outline-primary">
                            <i class="ph-duotone ph-paper-plane me-2"></i>
                            Inviti Inviati
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
                                        <th>Inviato da</th>
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
                                                        <span class="badge bg-warning">In attesa</span>
                                                    @endif
                                                @elseif($invitation->isAccepted())
                                                    <span class="badge bg-success">{{ __('groups.status_accepted') }}</span>
                                                @elseif($invitation->isDeclined())
                                                    <span class="badge bg-secondary">{{ __('groups.status_declined') }}</span>
                                                @else
                                                    <span class="badge bg-light text-dark">{{ ucfirst($invitation->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($invitation->isPending() && !$invitation->isExpired())
                                                    <div class="btn-group" role="group">
                                                                                                                <button type="button"
                                                                class="btn btn-sm btn-success"
                                                                onclick="confirmAcceptInvitation('{{ $invitation->id }}', '{{ $invitation->group->name }}')">
                                                            <i class="ph-duotone ph-check me-1"></i>
                                                            {{ __('groups.accept') }}
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="confirmDeclineInvitation('{{ $invitation->id }}', '{{ $invitation->group->name }}')">
                                                            <i class="ph-duotone ph-x me-1"></i>
                                                            {{ __('groups.decline') }}
                                                        </button>
                                                    </div>
                                                @elseif($invitation->isPending() && $invitation->isExpired())
                                                    <span class="text-muted small">Invito scaduto</span>
                                                @else
                                                    <a href="{{ route('group-invitations.show', $invitation) }}"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="ph-duotone ph-eye me-1"></i>
                                                        Dettagli
                                                    </a>
                                                @endif
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
                                <i class="ph-duotone ph-envelope text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-muted">Nessun invito ricevuto</h5>
                            <p class="text-muted">Non hai ricevuto ancora inviti ai gruppi.</p>
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

    <!-- Statistiche -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card card-light-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-clock text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">In Attesa</h6>
                            <h4 class="mb-0">{{ $invitations->where('status', 'pending')->where('expires_at', '>', now())->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-light-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-check-circle text-success" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ __('groups.invitations_accepted') }}</h6>
                            <h4 class="mb-0">{{ $invitations->where('status', 'accepted')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-light-secondary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-x-circle text-secondary" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ __('groups.invitations_declined') }}</h6>
                            <h4 class="mb-0">{{ $invitations->where('status', 'declined')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-light-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-warning text-danger" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Scaduti</h6>
                            <h4 class="mb-0">{{ $invitations->where('status', 'pending')->where('expires_at', '<', now())->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form nascosti per le azioni -->
<form id="acceptInvitationForm" method="POST" style="display: none;">
    @csrf
</form>
<form id="declineInvitationForm" method="POST" style="display: none;">
    @csrf
</form>

@endsection

@push('scripts')
<script>
function confirmAcceptInvitation(invitationId, groupName) {
    Swal.fire({
        title: '{{ __("groups.accept_invitation") }}',
        text: '{{ __("groups.confirm_accept_invitation") }}'.replace(':group', groupName),
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("groups.accept") }}',
        cancelButtonText: '{{ __("common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('acceptInvitationForm');
            form.action = `/group-invitations/${invitationId}/accept`;
            form.submit();
        }
    });
}

function confirmDeclineInvitation(invitationId, groupName) {
    Swal.fire({
        title: '{{ __("groups.decline_invitation") }}',
        text: '{{ __("groups.confirm_decline_invitation") }}'.replace(':group', groupName),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("groups.decline") }}',
        cancelButtonText: '{{ __("common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('declineInvitationForm');
            form.action = `/group-invitations/${invitationId}/decline`;
            form.submit();
        }
    });
}
</script>
@endpush
