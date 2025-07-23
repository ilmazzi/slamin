@extends('layout.master')

@section('title', 'Configurazione PeerTube - Admin')

@section('css')
<style>
    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
    .status-success { background-color: #28a745; }
    .status-error { background-color: #dc3545; }
    .status-warning { background-color: #ffc107; }
    .config-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .test-result {
        padding: 10px;
        border-radius: 5px;
        margin-top: 10px;
    }
    .test-success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
    .test-error { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
</style>
@endsection

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Configurazione PeerTube</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                        </span>
                    </a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">Configurazione PeerTube</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0 f-w-600">
                        <i class="ph ph-video-camera me-2 text-primary"></i>
                        Stato Configurazione PeerTube
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <span class="status-indicator {{ $isConfigured ? 'status-success' : 'status-error' }}"></span>
                                <div>
                                    <h6 class="mb-0 f-w-600">Configurazione</h6>
                                    <small class="text-muted">{{ $isConfigured ? 'Completa' : 'Incompleta' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <span class="status-indicator {{ $connectionTest ? 'status-success' : 'status-error' }}"></span>
                                <div>
                                    <h6 class="mb-0 f-w-600">Connessione</h6>
                                    <small class="text-muted">{{ $connectionTest ? 'Attiva' : 'Non disponibile' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <span class="status-indicator {{ $authTest ? 'status-success' : 'status-error' }}"></span>
                                <div>
                                    <h6 class="mb-0 f-w-600">Autenticazione</h6>
                                    <small class="text-muted">{{ $authTest ? 'Valida' : 'Fallita' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <span class="status-indicator {{ $authTest && $channelInfo ? 'status-success' : 'status-warning' }}"></span>
                                <div>
                                    <h6 class="mb-0 f-w-600">Canale</h6>
                                    <small class="text-muted">{{ $authTest && $channelInfo ? 'Configurato' : 'Non configurato' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0 f-w-600">
                        <i class="ph ph-gear me-2 text-primary"></i>
                        Configurazioni PeerTube
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.peertube.config.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Server Configuration -->
                        <div class="config-section">
                            <h6 class="f-w-600 mb-3">Configurazione Server</h6>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label f-w-600">URL Server PeerTube *</label>
                                    <input type="url" name="peertube_url" class="form-control @error('peertube_url') is-invalid @enderror"
                                           value="{{ old('peertube_url', $configs['peertube_url'] ?? '') }}" 
                                           placeholder="https://peertube.example.com" required>
                                    @error('peertube_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">URL completo del server PeerTube (es. https://peertube.example.com)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Authentication -->
                        <div class="config-section">
                            <h6 class="f-w-600 mb-3">Autenticazione Admin</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label f-w-600">Username Admin *</label>
                                    <input type="text" name="peertube_admin_username" class="form-control @error('peertube_admin_username') is-invalid @enderror"
                                           value="{{ old('peertube_admin_username', $configs['peertube_admin_username'] ?? '') }}" required>
                                    @error('peertube_admin_username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label f-w-600">Password Admin</label>
                                    <input type="password" name="peertube_admin_password" class="form-control @error('peertube_admin_password') is-invalid @enderror"
                                           placeholder="Lascia vuoto per non modificare">
                                    @error('peertube_admin_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Lascia vuoto per mantenere la password attuale</small>
                                </div>
                            </div>
                        </div>

                        <!-- Channel Configuration -->
                        <div class="config-section">
                            <h6 class="f-w-600 mb-3">Configurazione Canale</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label f-w-600">ID Canale</label>
                                    <input type="text" name="peertube_channel_id" class="form-control @error('peertube_channel_id') is-invalid @enderror"
                                           value="{{ old('peertube_channel_id', $configs['peertube_channel_id'] ?? '') }}" 
                                           placeholder="ID del canale PeerTube">
                                    @error('peertube_channel_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label f-w-600">ID Account</label>
                                    <input type="text" name="peertube_account_id" class="form-control @error('peertube_account_id') is-invalid @enderror"
                                           value="{{ old('peertube_account_id', $configs['peertube_account_id'] ?? '') }}" 
                                           placeholder="ID dell'account PeerTube">
                                    @error('peertube_account_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Upload Settings -->
                        <div class="config-section">
                            <h6 class="f-w-600 mb-3">Impostazioni Upload</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label f-w-600">Privacy di Default</label>
                                    <select name="peertube_default_privacy" class="form-select @error('peertube_default_privacy') is-invalid @enderror">
                                        <option value="public" {{ (old('peertube_default_privacy', $configs['peertube_default_privacy'] ?? '') == 'public') ? 'selected' : '' }}>Pubblico</option>
                                        <option value="unlisted" {{ (old('peertube_default_privacy', $configs['peertube_default_privacy'] ?? '') == 'unlisted') ? 'selected' : '' }}>Non in elenco</option>
                                        <option value="private" {{ (old('peertube_default_privacy', $configs['peertube_default_privacy'] ?? '') == 'private') ? 'selected' : '' }}>Privato</option>
                                    </select>
                                    @error('peertube_default_privacy')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label f-w-600">Dimensione Massima File (bytes)</label>
                                    <input type="number" name="peertube_max_file_size" class="form-control @error('peertube_max_file_size') is-invalid @enderror"
                                           value="{{ old('peertube_max_file_size', $configs['peertube_max_file_size'] ?? 1073741824) }}" 
                                           min="1048576" step="1048576">
                                    @error('peertube_max_file_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">1GB = 1073741824 bytes</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label f-w-600">Tags di Default</label>
                                    <input type="text" name="peertube_default_tags" class="form-control @error('peertube_default_tags') is-invalid @enderror"
                                           value="{{ old('peertube_default_tags', is_array($configs['peertube_default_tags'] ?? []) ? implode(',', $configs['peertube_default_tags']) : 'poetry,slam,poetry-slam') }}" 
                                           placeholder="poetry,slam,poetry-slam">
                                    @error('peertube_default_tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Separati da virgola</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label f-w-600">Estensioni Permesse</label>
                                    <input type="text" name="peertube_allowed_extensions" class="form-control @error('peertube_allowed_extensions') is-invalid @enderror"
                                           value="{{ old('peertube_allowed_extensions', is_array($configs['peertube_allowed_extensions'] ?? []) ? implode(',', $configs['peertube_allowed_extensions']) : 'mp4,avi,mov,mkv,webm') }}" 
                                           placeholder="mp4,avi,mov,mkv,webm">
                                    @error('peertube_allowed_extensions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Separate da virgola</small>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="button" class="btn btn-info me-2" onclick="testConnection()">
                                    <i class="ph ph-wifi me-2"></i>Test Connessione
                                </button>
                                <button type="button" class="btn btn-warning me-2" onclick="testAuthentication()">
                                    <i class="ph ph-key me-2"></i>Test Autenticazione
                                </button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-danger me-2" onclick="resetConfig()">
                                    <i class="ph ph-trash me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ph ph-floppy-disk me-2"></i>Salva Configurazioni
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Channel Info -->
        <div class="col-lg-4">
            @if($authTest && $channelInfo)
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0 f-w-600">
                        <i class="ph ph-video-camera me-2 text-success"></i>
                        Informazioni Canale
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nome:</strong> {{ $channelInfo['displayName'] ?? 'N/A' }}
                    </div>
                    <div class="mb-3">
                        <strong>Descrizione:</strong> 
                        <p class="text-muted">{{ Str::limit($channelInfo['description'] ?? 'Nessuna descrizione', 100) }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>URL:</strong> 
                        <a href="{{ $channelInfo['url'] ?? '#' }}" target="_blank" class="text-primary">
                            {{ $channelInfo['url'] ?? 'N/A' }}
                        </a>
                    </div>
                    <div class="mb-3">
                        <strong>Follower:</strong> {{ $channelInfo['followersCount'] ?? 0 }}
                    </div>
                    <div class="mb-3">
                        <strong>Video:</strong> {{ $channelInfo['videosCount'] ?? 0 }}
                    </div>
                </div>
            </div>
            @endif

            @if($authTest && $accountInfo)
            <div class="card hover-effect mt-3">
                <div class="card-header">
                    <h5 class="mb-0 f-w-600">
                        <i class="ph ph-user me-2 text-info"></i>
                        Informazioni Account
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nome:</strong> {{ $accountInfo['displayName'] ?? 'N/A' }}
                    </div>
                    <div class="mb-3">
                        <strong>Username:</strong> {{ $accountInfo['name'] ?? 'N/A' }}
                    </div>
                    <div class="mb-3">
                        <strong>URL:</strong> 
                        <a href="{{ $accountInfo['url'] ?? '#' }}" target="_blank" class="text-primary">
                            {{ $accountInfo['url'] ?? 'N/A' }}
                        </a>
                    </div>
                    <div class="mb-3">
                        <strong>Follower:</strong> {{ $accountInfo['followersCount'] ?? 0 }}
                    </div>
                    <div class="mb-3">
                        <strong>Following:</strong> {{ $accountInfo['followingCount'] ?? 0 }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function testConnection() {
    fetch('{{ route("admin.peertube.config.test-connection") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        const resultDiv = document.createElement('div');
        resultDiv.className = `test-result ${data.success ? 'test-success' : 'test-error'}`;
        resultDiv.innerHTML = `<i class="ph ${data.success ? 'ph-check-circle' : 'ph-x-circle'} me-2"></i>${data.message}`;
        
        // Rimuovi risultati precedenti
        document.querySelectorAll('.test-result').forEach(el => el.remove());
        
        // Aggiungi nuovo risultato
        document.querySelector('.card-body').appendChild(resultDiv);
        
        // Rimuovi dopo 5 secondi
        setTimeout(() => resultDiv.remove(), 5000);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function testAuthentication() {
    fetch('{{ route("admin.peertube.config.test-auth") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        const resultDiv = document.createElement('div');
        resultDiv.className = `test-result ${data.success ? 'test-success' : 'test-error'}`;
        resultDiv.innerHTML = `<i class="ph ${data.success ? 'ph-check-circle' : 'ph-x-circle'} me-2"></i>${data.message}`;
        
        // Rimuovi risultati precedenti
        document.querySelectorAll('.test-result').forEach(el => el.remove());
        
        // Aggiungi nuovo risultato
        document.querySelector('.card-body').appendChild(resultDiv);
        
        // Rimuovi dopo 5 secondi
        setTimeout(() => resultDiv.remove(), 5000);
        
        // Ricarica la pagina se l'autenticazione è riuscita per aggiornare le info
        if (data.success) {
            setTimeout(() => location.reload(), 2000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function resetConfig() {
    if (confirm('Sei sicuro di voler resettare tutte le configurazioni PeerTube? Questa azione non può essere annullata.')) {
        fetch('{{ route("admin.peertube.config.reset") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}
</script>
@endsection 