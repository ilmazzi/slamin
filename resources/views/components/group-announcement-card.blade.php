@props(['announcement', 'group'])

@php
    $user = auth()->user();
    $canEdit = $announcement->author_id === $user?->id || $group->hasModerator($user);
    $canDelete = $canEdit;
    $canVote = $announcement->hasPoll() && $announcement->canUserVote($user);
@endphp

<div class="announcement-card card mb-3 {{ $announcement->is_pinned ? 'border-warning' : '' }}">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            @if($announcement->is_pinned)
                <i class="ti ti-pin text-warning me-2" title="{{ __('common.pinned_announcement') }}"></i>
            @endif
            <h6 class="mb-0">{{ $announcement->title }}</h6>
            <span class="badge bg-secondary ms-2">
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
        </div>

        @if($canEdit || $canDelete)
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                <i class="ti ti-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu">
                @if($canEdit)
                    <li>
                        <a class="dropdown-item" href="{{ route('groups.announcements.edit', [$group, $announcement]) }}">
                            <i class="ti ti-edit me-2"></i>Modifica
                        </a>
                    </li>
                @endif
                @if($canDelete)
                    <li>
                        <form action="{{ route('groups.announcements.destroy', [$group, $announcement]) }}"
                              method="POST"
                              onsubmit="return confirm('Sei sicuro di voler eliminare questo annuncio?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="ti ti-trash me-2"></i>Elimina
                            </button>
                        </form>
                    </li>
                @endif
            </ul>
        </div>
        @endif
    </div>

    <div class="card-body">
        <div class="announcement-content">
            {!! nl2br(e($announcement->content)) !!}
        </div>

        @if($announcement->hasPoll())
        <div class="announcement-poll mt-3">
            <h6 class="mb-3">
                <i class="ti ti-chart-bar me-2"></i>
                Sondaggio
            </h6>

            @if($canVote)
            <div class="poll-options">
                @foreach($announcement->poll_options as $index => $option)
                <div class="form-check mb-2">
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
                        class="btn btn-primary btn-sm mt-2"
                        onclick="voteInPoll({{ $announcement->id }})">
                    <i class="ti ti-vote me-1"></i>Vota
                </button>
            </div>
            @else
            <div class="poll-results">
                @php $results = $announcement->getPollResults(); @endphp
                @foreach($results as $index => $result)
                <div class="poll-result mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span>{{ $result['option'] }}</span>
                        <span class="text-muted">{{ $result['votes'] }} voti ({{ $result['percentage'] }}%)</span>
                    </div>
                    <div class="progress" style="height: 8px;">
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
            </div>
            @endif
        </div>
        @endif

        @if($announcement->expires_at)
        <div class="announcement-expiry mt-3">
            <small class="text-muted">
                <i class="ti ti-clock me-1"></i>
                Scade il {{ $announcement->expires_at->format('d/m/Y H:i') }}
            </small>
        </div>
        @endif
    </div>

    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="announcement-meta">
                <small class="text-muted">
                    <i class="ti ti-user me-1"></i>
                    {{ $announcement->author->name }}
                    <span class="mx-2">•</span>
                    <i class="ti ti-calendar me-1"></i>
                    {{ $announcement->created_at->format('d/m/Y H:i') }}
                </small>
            </div>

            <div class="announcement-actions">
                <a href="{{ route('groups.announcements.show', [$group, $announcement]) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-eye me-1"></i>Leggi tutto
                </a>
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
