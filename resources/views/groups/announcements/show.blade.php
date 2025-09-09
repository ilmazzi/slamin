@extends('layout.master')

@section('title', $announcement->title . ' - ' . $group->name)

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">
                            @if($announcement->is_pinned)
                                <i class="ti ti-pin text-warning me-2" title="Annuncio pinnato"></i>
                            @endif
                            {{ $announcement->title }}
                        </h1>
                        <p class="page-description">
                            Annuncio di {{ $announcement->author->name }} per {{ $group->name }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('groups.announcements.index', $group) }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>Torna alla bacheca
                        </a>
                        @php
                            $user = auth()->user();
                            $canEdit = $announcement->author_id === $user?->id || $group->hasModerator($user);
                        @endphp
                        @if($canEdit)
                            <a href="{{ route('groups.announcements.edit', [$group, $announcement]) }}" class="btn btn-primary">
                                <i class="ti ti-edit me-1"></i>Modifica
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Annuncio -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary me-2">
                                        @switch($announcement->visibility)
                                            @case('public')
                                                Pubblico
                                                @break
                                            @case('members_only')
                                                Solo membri
                                                @break
                                            @case('admins_only')
                                                Solo admin
                                                @break
                                        @endswitch
                                    </span>
                                    @if($announcement->hasPoll())
                                        <span class="badge bg-info">
                                            <i class="ti ti-chart-bar me-1"></i>Sondaggio
                                        </span>
                                    @endif
                                </div>
                                <div class="announcement-meta">
                                    <small class="text-muted">
                                        <i class="ti ti-calendar me-1"></i>
                                        {{ $announcement->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <!-- Contenuto -->
                            <div class="announcement-content mb-4">
                                {!! nl2br(e($announcement->content)) !!}
                            </div>
                            
                            <!-- Sondaggio -->
                            @if($announcement->hasPoll())
                            <div class="announcement-poll">
                                <h6 class="mb-3">
                                    <i class="ti ti-chart-bar me-2"></i>
                                    Sondaggio
                                </h6>
                                
                                @php
                                    $user = auth()->user();
                                    $canVote = $announcement->canUserVote($user);
                                @endphp
                                
                                @if($canVote)
                                <div class="poll-options">
                                    @foreach($announcement->poll_options as $index => $option)
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="poll_option_{{ $announcement->id }}" 
                                               value="{{ $index }}" 
                                               id="poll_{{ $announcement->id }}_{{ $index }}">
                                        <label class="form-check-label" for="poll_{{ $announcement->id }}_{{ $index }}">
                                            {{ $option }}
                                        </label>
                                    </div>
                                    @endforeach
                                    <button type="button" 
                                            class="btn btn-primary"
                                            onclick="voteInPoll({{ $announcement->id }})">
                                        <i class="ti ti-vote me-1"></i>Vota
                                    </button>
                                </div>
                                @else
                                <div class="poll-results">
                                    @php $results = $announcement->getPollResults(); @endphp
                                    @foreach($results as $index => $result)
                                    <div class="poll-result mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-medium">{{ $result['option'] }}</span>
                                            <span class="text-muted">{{ $result['votes'] }} voti ({{ $result['percentage'] }}%)</span>
                                        </div>
                                        <div class="progress" style="height: 12px;">
                                            <div class="progress-bar" 
                                                 role="progressbar" 
                                                 style="width: {{ $result['percentage'] }}%"
                                                 aria-valuenow="{{ $result['percentage'] }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                    @php
                                        $totalVotes = array_sum(array_column($results, 'votes'));
                                    @endphp
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="ti ti-users me-1"></i>
                                            Totale voti: {{ $totalVotes }}
                                        </small>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Scadenza -->
                            @if($announcement->expires_at)
                            <div class="announcement-expiry mt-4">
                                <div class="alert alert-info">
                                    <i class="ti ti-clock me-2"></i>
                                    <strong>Scadenza:</strong> Questo annuncio scadrà il {{ $announcement->expires_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="announcement-author">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $announcement->author->profile_photo_url }}" 
                                             alt="{{ $announcement->author->name }}" 
                                             class="rounded-circle me-2" 
                                             style="width: 32px; height: 32px;">
                                        <div>
                                            <div class="fw-medium">{{ $announcement->author->name }}</div>
                                            <small class="text-muted">
                                                @if($group->hasAdmin($announcement->author))
                                                    <i class="ti ti-crown me-1"></i>Amministratore
                                                @elseif($group->hasModerator($announcement->author))
                                                    <i class="ti ti-shield me-1"></i>Moderatore
                                                @else
                                                    <i class="ti ti-user me-1"></i>Membro
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="announcement-actions">
                                    @if($canEdit)
                                        <a href="{{ route('groups.announcements.edit', [$group, $announcement]) }}" 
                                           class="btn btn-sm btn-warning me-2">
                                            <i class="ti ti-edit me-1"></i>Modifica
                                        </a>
                                    @endif
                                    
                                    @php
                                        $canDelete = $announcement->author_id === $user?->id || $group->hasModerator($user);
                                    @endphp
                                    @if($canDelete)
                                        <form action="{{ route('groups.announcements.destroy', [$group, $announcement]) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Sei sicuro di voler eliminare questo annuncio?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="ti ti-trash me-1"></i>Elimina
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Info gruppo -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="ti ti-info-circle me-2"></i>
                                Informazioni gruppo
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $group->image ? asset('storage/' . $group->image) : asset('assets/images/groups/default-group.webp') }}" 
                                     alt="{{ $group->name }}" 
                                     class="rounded me-3" 
                                     style="width: 48px; height: 48px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-0">{{ $group->name }}</h6>
                                    <small class="text-muted">{{ $group->getMembersCount() }} membri</small>
                                </div>
                            </div>
                            
                            <a href="{{ route('groups.show', $group) }}" class="btn btn-primary btn-sm w-100">
                                <i class="ti ti-eye me-1"></i>Vedi gruppo
                            </a>
                        </div>
                    </div>

                    <!-- Social links -->
                    <x-group-social-links :group="$group" />
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function voteInPoll(announcementId) {
    const selectedOption = document.querySelector(`input[name="poll_option_${announcementId}"]:checked`);
    
    if (!selectedOption) {
        alert('Seleziona un\'opzione prima di votare');
        return;
    }
    
    const optionIndex = selectedOption.value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    
    fetch(`/groups/{{ $group->id }}/announcements/${announcementId}/vote`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            option_index: parseInt(optionIndex)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Ricarica la pagina per mostrare i risultati
            location.reload();
        } else {
            alert('Errore durante il voto: ' + (data.error || 'Errore sconosciuto'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Errore durante il voto');
    });
}
</script>
@endsection
