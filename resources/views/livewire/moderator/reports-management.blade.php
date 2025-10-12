<div>
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="ph ph-flag text-warning me-2"></i>Gestione Segnalazioni
                            </h4>
                            <p class="text-muted mb-0">Revisiona e gestisci le segnalazioni degli utenti</p>
                        </div>
                        <a href="{{ route('forum.moderate.queue') }}" class="btn btn-light-primary">
                            <i class="ph ph-shield-check me-2"></i>Coda Moderazione
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body">
            <label class="form-label">Stato</label>
            <div class="btn-group w-100">
                <button wire:click="setFilter('statusFilter', 'pending')" 
                        class="btn {{ $statusFilter === 'pending' ? 'btn-warning' : 'btn-light-warning' }}">
                    In Attesa
                </button>
                <button wire:click="setFilter('statusFilter', 'resolved')" 
                        class="btn {{ $statusFilter === 'resolved' ? 'btn-success' : 'btn-light-success' }}">
                    Risolte
                </button>
                <button wire:click="setFilter('statusFilter', 'dismissed')" 
                        class="btn {{ $statusFilter === 'dismissed' ? 'btn-danger' : 'btn-light-danger' }}">
                    Respinte
                </button>
            </div>
        </div>
    </div>

    {{-- Reports List --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Contenuto</th>
                            <th>Motivo</th>
                            <th>Segnalato da</th>
                            <th>Data</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td>
                                    <div>
                                        <div class="small text-muted mt-1">
                                            {{ $report->target_type === 'App\Models\ForumPost' ? 'Post' : 'Commento' }}
                                            @if($report->target)
                                                @if($report->target instanceof \App\Models\ForumPost)
                                                    <span class="badge bg-light-primary text-primary">
                                                        r/{{ $report->target->subreddit->name }}
                                                    </span>
                                                    : {{ Str::limit($report->target->title, 40) }}
                                                @else
                                                    : {{ Str::limit($report->target->content, 40) }}
                                                @endif
                                            @else
                                                <span class="text-danger">(eliminato)</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light-warning text-warning">
                                        {{ ucfirst(str_replace('_', ' ', $report->reason)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('profile.show', $report->reporter->id) }}" target="_blank">
                                        {{ $report->reporter->name }}
                                    </a>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $report->created_at->diffForHumans() }}
                                    </small>
                                </td>
                                <td>
                                    @if($statusFilter === 'pending')
                                        <button wire:click="viewReport({{ $report->id }})" 
                                                class="btn btn-sm btn-light-primary">
                                            <i class="ph ph-eye"></i> Revisiona
                                        </button>
                                    @else
                                        <span class="badge bg-light-{{ $report->status === 'resolved' ? 'success' : 'danger' }}">
                                            {{ $report->status === 'resolved' ? 'Risolta' : 'Respinta' }}
                                        </span>
                                        @if($report->handled_by)
                                            <div class="small text-muted">
                                                da {{ $report->handledBy->name }}
                                            </div>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="ph ph-check-circle f-s-48 mb-2"></i>
                                    <p>Nessuna segnalazione {{ $statusFilter }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($reports->hasPages())
            <div class="card-footer">
                {{ $reports->links() }}
            </div>
        @endif
    </div>

    {{-- Resolve Report Modal --}}
    @if($showResolveModal && $selectedReport)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-light-warning">
                        <h5 class="modal-title text-warning">
                            <i class="ph ph-flag me-2"></i>Revisiona Segnalazione
                        </h5>
                        <button type="button" class="btn-close" wire:click="showResolveModal = false"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Report Details --}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">Dettagli Segnalazione</h6>
                                <div class="row mb-2">
                                    <div class="col-4 text-muted">Motivo:</div>
                                    <div class="col-8">
                                        <span class="badge bg-light-warning text-warning">
                                            {{ ucfirst(str_replace('_', ' ', $selectedReport->reason)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 text-muted">Descrizione:</div>
                                    <div class="col-8">{{ $selectedReport->description }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 text-muted">Segnalato da:</div>
                                    <div class="col-8">
                                        <a href="{{ route('profile.show', $selectedReport->reporter->id) }}" target="_blank">
                                            {{ $selectedReport->reporter->name }}
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 text-muted">Data:</div>
                                    <div class="col-8">{{ $selectedReport->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Reported Content --}}
                        @if($selectedReport->target)
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Contenuto Segnalato</h6>
                                </div>
                                <div class="card-body">
                                    @if($selectedReport->target instanceof \App\Models\ForumPost)
                                        <h6>{{ $selectedReport->target->title }}</h6>
                                        <p>{{ Str::limit($selectedReport->target->content, 300) }}</p>
                                        <a href="{{ route('forum.post.show', ['subreddit' => $selectedReport->target->subreddit->slug, 'post' => $selectedReport->target->id]) }}" 
                                           target="_blank" class="btn btn-sm btn-light-primary">
                                            <i class="ph ph-arrow-square-out me-1"></i>Visualizza Post
                                        </a>
                                    @else
                                        <p>{{ $selectedReport->target->content }}</p>
                                        <small class="text-muted">
                                            Autore: {{ $selectedReport->target->user->name }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="alert alert-border-danger" role="alert">
                                <i class="ph ph-warning me-2"></i>
                                Il contenuto segnalato è stato già eliminato
                            </div>
                        @endif

                        {{-- Moderator Notes --}}
                        <div class="mt-3">
                            <label class="form-label">Note Moderatore (opzionale)</label>
                            <textarea wire:model="moderatorNotes" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Aggiungi note sulla decisione..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="showResolveModal = false" class="btn btn-light-secondary">
                            Chiudi
                        </button>
                        <button wire:click="resolveReport('rejected')" 
                                class="btn btn-danger">
                            <i class="ph ph-x me-2"></i>Respingi Segnalazione
                        </button>
                        <button wire:click="resolveReport('approved')" 
                                wire:confirm="Eliminare il contenuto segnalato?"
                                class="btn btn-success">
                            <i class="ph ph-check me-2"></i>Approva ed Elimina Contenuto
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
