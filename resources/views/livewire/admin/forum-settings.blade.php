<div>
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="ph ph-gear text-primary me-2"></i>Configurazioni Forum
                            </h4>
                            <p class="text-muted mb-0">Gestisci le impostazioni globali del forum</p>
                        </div>
                        <a href="{{ route('admin.forum.dashboard') }}" class="btn btn-light-secondary">
                            <i class="ph ph-arrow-left me-2"></i>Torna alla Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Settings List --}}
    <div class="row">
        <div class="col-lg-8 mx-auto">
            @foreach($settings as $key => $setting)
                <div class="card mb-3 hover-effect">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="mb-1">{{ ucwords(str_replace('_', ' ', $key)) }}</h6>
                                <p class="text-muted small mb-0">{{ $setting['description'] }}</p>
                            </div>
                            <div class="col-md-4">
                                @if($editMode[$key])
                                    <div class="d-flex gap-2">
                                        @if($setting['type'] === 'boolean')
                                            <select wire:model="settings.{{ $key }}.value" class="form-select form-select-sm">
                                                <option value="true">Abilitato</option>
                                                <option value="false">Disabilitato</option>
                                            </select>
                                        @elseif($setting['type'] === 'integer')
                                            <input type="number" wire:model="settings.{{ $key }}.value" class="form-control form-control-sm">
                                        @elseif($setting['type'] === 'json')
                                            <input type="text" wire:model="settings.{{ $key }}.value" class="form-control form-control-sm">
                                        @else
                                            <input type="text" wire:model="settings.{{ $key }}.value" class="form-control form-control-sm">
                                        @endif
                                        <button wire:click="saveSetting('{{ $key }}')" class="btn btn-sm btn-success">
                                            <i class="ph ph-check"></i>
                                        </button>
                                        <button wire:click="toggleEdit('{{ $key }}')" class="btn btn-sm btn-light-secondary">
                                            <i class="ph ph-x"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            @if($setting['type'] === 'boolean')
                                                @if($setting['value'] === 'true' || $setting['value'] === true)
                                                    <span class="badge bg-light-success text-success">
                                                        <i class="ph ph-check"></i> Abilitato
                                                    </span>
                                                @else
                                                    <span class="badge bg-light-danger text-danger">
                                                        <i class="ph ph-x"></i> Disabilitato
                                                    </span>
                                                @endif
                                            @elseif($setting['type'] === 'integer')
                                                <span class="badge bg-light-primary text-primary">
                                                    {{ number_format($setting['value']) }}
                                                </span>
                                            @else
                                                <code class="small">{{ $setting['value'] }}</code>
                                            @endif
                                        </div>
                                        <button wire:click="toggleEdit('{{ $key }}')" class="btn btn-sm btn-light-primary ms-2">
                                            <i class="ph ph-pencil"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Help Card --}}
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card bg-light-info">
                <div class="card-body">
                    <h6 class="text-info mb-2">
                        <i class="ph ph-info me-2"></i>Informazioni
                    </h6>
                    <ul class="mb-0 small">
                        <li><strong>comment_depth_limit:</strong> Massimo livello di annidamento dei commenti (consigliato: 3)</li>
                        <li><strong>max_image_size:</strong> Dimensione massima immagini in bytes (5MB = 5242880)</li>
                        <li><strong>allowed_post_types:</strong> Tipi di post permessi (JSON array)</li>
                        <li><strong>require_approval_new_subreddit:</strong> Richiedi approvazione admin per nuovi subreddit</li>
                        <li><strong>enable_notifications:</strong> Abilita notifiche forum</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
