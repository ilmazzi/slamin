@extends('layout.app')

@section('title', 'Impostazioni Social - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Impostazioni Social</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="ph-duotone ph-gear f-s-24 me-2 text-primary"></i>
                    Impostazioni Social
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-users f-s-18 me-2 text-success"></i>
                        Configurazione Sistema Social
                    </h5>
                </div>
                <div class="card-body">
                    <form id="socialSettingsForm" method="POST" action="{{ route('admin.social-settings.update') }}">
                        @csrf
                        
                        <!-- Sezione Like -->
                        <div class="card card-light-primary mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="ph-duotone ph-heart f-s-16 me-2 text-danger"></i>
                                    Sistema Like
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="social_enable_likes" name="social_enable_likes" value="1" checked>
                                            <label class="form-check-label" for="social_enable_likes">
                                                Abilita sistema like
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Contenuti che possono essere likati</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_likeable_content[]" value="video" checked>
                                            <label class="form-check-label">Video</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_likeable_content[]" value="photo" checked>
                                            <label class="form-check-label">Foto</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_likeable_content[]" value="poem" checked>
                                            <label class="form-check-label">Poesie</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_likeable_content[]" value="article" checked>
                                            <label class="form-check-label">Articoli</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_likeable_content[]" value="event" checked>
                                            <label class="form-check-label">Eventi</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_likeable_content[]" value="comment" checked>
                                            <label class="form-check-label">Commenti</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sezione Commenti -->
                        <div class="card card-light-info mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="ph-duotone ph-chat-circle f-s-16 me-2 text-info"></i>
                                    Sistema Commenti
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="social_enable_comments" name="social_enable_comments" value="1" checked>
                                            <label class="form-check-label" for="social_enable_comments">
                                                Abilita sistema commenti
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mt-3">
                                            <input class="form-check-input" type="checkbox" id="social_auto_approve_comments" name="social_auto_approve_comments" value="1" checked>
                                            <label class="form-check-label" for="social_auto_approve_comments">
                                                Approvazione automatica commenti
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Contenuti che possono essere commentati</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_commentable_content[]" value="video" checked>
                                            <label class="form-check-label">Video</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_commentable_content[]" value="photo" checked>
                                            <label class="form-check-label">Foto</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_commentable_content[]" value="poem" checked>
                                            <label class="form-check-label">Poesie</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_commentable_content[]" value="article" checked>
                                            <label class="form-check-label">Articoli</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_commentable_content[]" value="event" checked>
                                            <label class="form-check-label">Eventi</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sezione Visualizzazioni -->
                        <div class="card card-light-warning mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="ph-duotone ph-eye f-s-16 me-2 text-warning"></i>
                                    Sistema Visualizzazioni
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="social_enable_views" name="social_enable_views" value="1" checked>
                                            <label class="form-check-label" for="social_enable_views">
                                                Abilita sistema visualizzazioni
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Contenuti tracciabili</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_viewable_content[]" value="video" checked>
                                            <label class="form-check-label">Video</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_viewable_content[]" value="photo" checked>
                                            <label class="form-check-label">Foto</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_viewable_content[]" value="poem" checked>
                                            <label class="form-check-label">Poesie</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_viewable_content[]" value="article" checked>
                                            <label class="form-check-label">Articoli</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_viewable_content[]" value="event" checked>
                                            <label class="form-check-label">Eventi</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sezione Notifiche -->
                        <div class="card card-light-success mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="ph-duotone ph-bell f-s-16 me-2 text-success"></i>
                                    Sistema Notifiche
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="social_enable_notifications" name="social_enable_notifications" value="1" checked>
                                            <label class="form-check-label" for="social_enable_notifications">
                                                Abilita notifiche social
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tipi di notifica</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_notification_types[]" value="content_liked" checked>
                                            <label class="form-check-label">Like sui contenuti</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_notification_types[]" value="content_commented" checked>
                                            <label class="form-check-label">Commenti sui contenuti</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="social_notification_types[]" value="comment_liked" checked>
                                            <label class="form-check-label">Like sui commenti</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pulsanti Azione -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary hover-effect">
                                <i class="ph-duotone ph-floppy-disk f-s-14 me-1"></i>
                                Salva Impostazioni
                            </button>
                            <button type="button" class="btn btn-outline-secondary hover-effect" onclick="resetSettings()">
                                <i class="ph-duotone ph-arrow-clockwise f-s-14 me-1"></i>
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadSettings();
    
    // Gestione form
    document.getElementById('socialSettingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        
        fetch('{{ route("admin.social-settings.update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Impostazioni salvate con successo!', 'success');
            } else {
                showToast(data.message || 'Errore durante il salvataggio', 'error');
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            showToast('Errore di connessione', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
        });
    });
});

function loadSettings() {
    fetch('{{ route("admin.social-settings.api.settings") }}')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const settings = data.settings;
            
            // Like settings
            document.getElementById('social_enable_likes').checked = settings.social_enable_likes;
            updateCheckboxes('social_likeable_content', settings.social_likeable_content);
            
            // Comment settings
            document.getElementById('social_enable_comments').checked = settings.social_enable_comments;
            document.getElementById('social_auto_approve_comments').checked = settings.social_auto_approve_comments;
            updateCheckboxes('social_commentable_content', settings.social_commentable_content);
            
            // View settings
            document.getElementById('social_enable_views').checked = settings.social_enable_views;
            updateCheckboxes('social_viewable_content', settings.social_viewable_content);
            
            // Notification settings
            document.getElementById('social_enable_notifications').checked = settings.social_enable_notifications;
            updateCheckboxes('social_notification_types', settings.social_notification_types);
        }
    })
    .catch(error => {
        console.error('Errore caricamento impostazioni:', error);
    });
}

function updateCheckboxes(name, values) {
    const checkboxes = document.querySelectorAll(`input[name="${name}[]"]`);
    checkboxes.forEach(checkbox => {
        checkbox.checked = values.includes(checkbox.value);
    });
}

function resetSettings() {
    if (confirm('Sei sicuro di voler resettare tutte le impostazioni ai valori di default?')) {
        fetch('{{ route("admin.social-settings.reset") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Impostazioni resettate con successo!', 'success');
                loadSettings();
            } else {
                showToast(data.message || 'Errore durante il reset', 'error');
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            showToast('Errore di connessione', 'error');
        });
    }
}

function showToast(message, type = 'info') {
    if (typeof toastr !== 'undefined') {
        toastr[type](message);
    } else if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: type === 'success' ? 'Successo!' : 'Errore',
            text: message,
            icon: type,
            timer: 2000,
            showConfirmButton: false
        });
    } else {
        alert(message);
    }
}
</script>
@endsection 