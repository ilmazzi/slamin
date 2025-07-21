@extends('layout.master')

@section('title', 'Impostazioni Sistema - Admin')

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-gear me-2"></i>
                Impostazioni Sistema
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
                    <a href="#" class="f-s-14 f-w-500">Impostazioni Sistema</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Settings Form -->
    <form id="settingsForm" action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            @foreach($groups as $groupKey => $groupName)
            <div class="col-lg-6 mb-4">
                <div class="card hover-effect">
                    <div class="card-header">
                        <h5 class="mb-0 f-w-600 text-dark">
                            <i class="ph ph-{{ $groupKey === 'upload' ? 'upload' : ($groupKey === 'video' ? 'video-camera' : 'gear') }} me-2"></i>
                            {{ $groupName }}
                        </h5>
                    </div>
                    <div class="card-body pa-30">
                        @if(isset($settings[$groupKey]))
                            @foreach($settings[$groupKey] as $key => $setting)
                                <div class="mb-4">
                                    <label class="form-label f-w-600">{{ $setting['display_name'] }}</label>

                                    @if($setting['type'] === 'boolean')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="settings[{{ $key }}]" value="1"
                                                   id="setting_{{ $key }}"
                                                   {{ $setting['value'] ? 'checked' : '' }}>
                                            <label class="form-check-label" for="setting_{{ $key }}">
                                                Abilitato
                                            </label>
                                        </div>
                                    @elseif($setting['type'] === 'json')
                                        <textarea class="form-control" name="settings[{{ $key }}]"
                                                  rows="3" placeholder="Inserisci JSON valido">{{ is_array($setting['value']) ? json_encode($setting['value'], JSON_PRETTY_PRINT) : $setting['value'] }}</textarea>
                                    @elseif($setting['type'] === 'integer')
                                        <input type="number" class="form-control"
                                               name="settings[{{ $key }}]"
                                               value="{{ $setting['value'] }}"
                                               min="0">
                                    @else
                                        <input type="text" class="form-control"
                                               name="settings[{{ $key }}]"
                                               value="{{ $setting['value'] }}">
                                    @endif

                                    @if($setting['description'])
                                        <small class="text-muted f-s-12">{{ $setting['description'] }}</small>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted">
                                <i class="ph ph-info f-s-24 mb-2"></i>
                                <p>Nessuna impostazione disponibile per questo gruppo</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-12">
                <div class="card hover-effect">
                    <div class="card-body pa-30">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button type="button" class="btn btn-warning hover-effect me-2" onclick="resetSettings()">
                                    <i class="ph ph-arrow-clockwise me-2"></i>
                                    Reset ai Default
                                </button>
                                <button type="button" class="btn btn-info hover-effect" onclick="refreshSettings()">
                                    <i class="ph ph-arrows-clockwise me-2"></i>
                                    Ricarica
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary hover-effect me-2">
                                    <i class="ph ph-x me-2"></i>
                                    Annulla
                                </a>
                                <button type="submit" class="btn btn-primary hover-effect">
                                    <i class="ph ph-check me-2"></i>
                                    Salva Impostazioni
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
// Submit form con AJAX
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Show loading
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="ph ph-spinner ph-spin me-2"></i>Salvando...';
    submitBtn.disabled = true;

    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Success message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Successo!',
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                alert(data.message);
            }
        } else {
            // Error message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: data.message,
                    footer: data.errors ? data.errors.join('<br>') : ''
                });
            } else {
                alert('Errore: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: 'Errore durante il salvataggio: ' + error.message
            });
        } else {
            alert('Errore durante il salvataggio: ' + error.message);
        }
    })
    .finally(() => {
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Reset settings
function resetSettings() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Conferma Reset',
            text: 'Sei sicuro di voler reimpostare tutte le impostazioni ai valori di default? Questa azione non può essere annullata.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sì, resetta!',
            cancelButtonText: 'Annulla'
        }).then((result) => {
            if (result.isConfirmed) {
                performReset();
            }
        });
    } else {
        if (confirm('Sei sicuro di voler reimpostare tutte le impostazioni ai valori di default?')) {
            performReset();
        }
    }
}

function performReset() {
    fetch('{{ route("admin.settings.reset") }}', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Reset Completato!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                alert(data.message);
                location.reload();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: data.message
                });
            } else {
                alert('Errore: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: 'Errore durante il reset: ' + error.message
            });
        } else {
            alert('Errore durante il reset: ' + error.message);
        }
    });
}

// Refresh settings
function refreshSettings() {
    location.reload();
}
</script>
@endsection
