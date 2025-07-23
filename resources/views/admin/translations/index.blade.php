@extends('layout.master')

@section('title', __('admin.translation_management'))

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-translate me-2"></i>
                {{ __('admin.translation_management') }}
            </h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                        </span>
                    </a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">{{ __('admin.translation_management') }}</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-2">
                            <i class="ph ph-translate me-2"></i>{{ __('admin.translation_management') }}
                        </h2>
                        <p class="text-muted mb-0">{{ __('admin.translation_management_description') }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.translations.sync') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Sincronizzare tutte le lingue con le chiavi italiane?')">
                                <i class="ph ph-arrows-clockwise me-2"></i>Sincronizza Lingue
                            </button>
                        </form>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLanguageModal">
                            <i class="ph ph-plus me-2"></i>{{ __('admin.add_language') }}
                        </button>
                    </div>
                </div>
        </div>
    </div>

    <!-- Languages Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-globe me-2"></i>Lingue Disponibili
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($languages as $code => $name)
                        <div class="col-md-6 col-lg-4">
                            <div class="card card-light-primary border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $name }}</h6>
                                            <small class="text-muted">{{ strtoupper($code) }}</small>
                                            @if($code !== 'it' && isset($missingKeys[$code]))
                                                @php
                                                    $totalMissing = 0;
                                                    foreach($missingKeys[$code] as $file => $keys) {
                                                        $totalMissing += count($keys);
                                                    }
                                                @endphp
                                                @if($totalMissing > 0)
                                                    <div class="mt-1">
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="ph ph-warning me-1"></i>{{ $totalMissing }} chiavi mancanti
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="mt-1">
                                                        <span class="badge bg-success">
                                                            <i class="ph ph-check me-1"></i>Completa
                                                        </span>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-light-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                                                <i class="ph ph-dots-three-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('admin.translations.show', [$code, 'common']) }}">
                                                    <i class="ph ph-pencil me-2"></i>Modifica Traduzioni
                                                </a></li>
                                                @if($code !== 'it' && isset($missingKeys[$code]))
                                                    @if($totalMissing > 0)
                                                        <li><a class="dropdown-item text-warning" href="#" onclick="showMissingKeys('{{ $code }}', {{ json_encode($missingKeys[$code]) }})">
                                                            <i class="ph ph-warning me-2"></i>Vedi Chiavi Mancanti ({{ $totalMissing }})
                                                        </a></li>
                                                    @endif
                                                @endif
                                                @if($code !== 'it')
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('admin.translations.delete-language', $code) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Sei sicuro di voler eliminare questa lingua?')">
                                                            <i class="ph ph-trash me-2"></i>Elimina Lingua
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Translation Files -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-files me-2"></i>File di Traduzione
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>File</th>
                                    <th>Descrizione</th>
                                    <th>Lingue</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($translationFiles as $file => $displayName)
                                <tr>
                                    <td>
                                        <strong>{{ $file }}.php</strong>
                                    </td>
                                    <td>{{ $displayName }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @foreach($languages as $code => $name)
                                            <a href="{{ route('admin.translations.show', [$code, $file]) }}"
                                               class="badge bg-primary text-decoration-none">
                                                {{ strtoupper($code) }}
                                            </a>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @foreach($languages as $code => $name)
                                            <a href="{{ route('admin.translations.show', [$code, $file]) }}"
                                               class="btn btn-outline-primary">
                                                {{ strtoupper($code) }}
                                            </a>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Language Modal -->
<div class="modal fade" id="addLanguageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-plus-circle me-2"></i>Aggiungi Nuova Lingua
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.translations.create-language') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="language_code" class="form-label">Codice Lingua *</label>
                        <input type="text" name="language_code" id="language_code" class="form-control"
                               placeholder="es. en, fr, de" maxlength="2" required>
                        <small class="text-muted">Codice ISO 639-1 (2 caratteri)</small>
                    </div>
                    <div class="mb-3">
                        <label for="language_name" class="form-label">Nome Lingua *</label>
                        <input type="text" name="language_name" id="language_name" class="form-control"
                               placeholder="es. English, Français, Deutsch" required>
                        <small class="text-muted">Nome completo della lingua</small>
                    </div>
                    <div class="alert alert-info">
                        <i class="ph ph-info me-2"></i>
                        <strong>Nota:</strong> La nuova lingua sarà creata copiando tutte le traduzioni dall'italiano.
                        Potrai poi modificarle individualmente.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-plus me-2"></i>Crea Lingua
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Missing Keys Modal -->
<div class="modal fade" id="missingKeysModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-warning me-2"></i>Chiavi Mancanti
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="missingKeysContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                <a href="#" id="syncLanguageBtn" class="btn btn-warning">
                    <i class="ph ph-arrows-clockwise me-2"></i>Sincronizza Questa Lingua
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-uppercase language code
    const languageCodeInput = document.getElementById('language_code');
    if (languageCodeInput) {
        languageCodeInput.addEventListener('input', function() {
            this.value = this.value.toLowerCase();
        });
    }
});

function showMissingKeys(languageCode, missingKeys) {
    let content = `<h6>Lingua: <strong>${languageCode.toUpperCase()}</strong></h6>`;
    content += '<div class="mt-3">';

    for (const [file, keys] of Object.entries(missingKeys)) {
        content += `<div class="mb-3">`;
        content += `<h6 class="text-primary">${file}.php (${keys.length} chiavi)</h6>`;
        content += `<ul class="list-group list-group-flush">`;
        keys.forEach(key => {
            content += `<li class="list-group-item d-flex justify-content-between align-items-center">`;
            content += `<code>${key}</code>`;
            content += `<a href="{{ route('admin.translations.show', ['LANG', 'FILE']) }}".replace('LANG', languageCode).replace('FILE', file) class="btn btn-sm btn-outline-primary">`;
            content += `<i class="ph ph-pencil me-1"></i>Traduci</a>`;
            content += `</li>`;
        });
        content += `</ul></div>`;
    }

    content += '</div>';

    document.getElementById('missingKeysContent').innerHTML = content;
    document.getElementById('syncLanguageBtn').href = "{{ route('admin.translations.sync') }}";

    new bootstrap.Modal(document.getElementById('missingKeysModal')).show();
}
</script>
@endsection
