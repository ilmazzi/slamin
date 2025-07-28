@extends('layout.master')

@section('title', 'Impostazioni Moderazione')

@section('main-content')
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Impostazioni Moderazione</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-gauge f-s-16"></i> Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('admin.moderation.index') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-shield-check f-s-16"></i> Moderazione
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <span class="f-s-14 f-w-500">
                            <i class="ph-duotone ph-gear f-s-16"></i> Impostazioni
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 f-w-600">
                        <i class="ph-duotone ph-gear me-2"></i>
                        Impostazioni Moderazione
                    </h4>
                    <p class="text-muted mb-0">Configura le impostazioni di moderazione per i contenuti</p>
                </div>
                <div>
                    <a href="{{ route('admin.moderation.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="ph-duotone ph-arrow-left me-2"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.moderation.pending') }}" class="btn btn-outline-primary">
                        <i class="ph-duotone ph-list-checks me-2"></i>
                        Contenuti in Attesa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Impostazioni -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-shield-check me-2"></i>
                        Configurazione Auto-Approval
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.moderation.settings.update') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="videos_auto_approve" name="videos_auto_approve" value="1" {{ ($formSettings['videos_auto_approve']['value'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="videos_auto_approve">
                                        <i class="ph-duotone ph-video-camera me-2"></i>
                                        Video - Auto Approval
                                    </label>
                                </div>
                                <small class="text-muted">I video verranno automaticamente approvati</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="poems_auto_approve" name="poems_auto_approve" value="1" {{ ($formSettings['poems_auto_approve']['value'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="poems_auto_approve">
                                        <i class="ph-duotone ph-book-open me-2"></i>
                                        Poesie - Auto Approval
                                    </label>
                                </div>
                                <small class="text-muted">Le poesie verranno automaticamente approvate</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="events_auto_approve" name="events_auto_approve" value="1" {{ ($formSettings['events_auto_approve']['value'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="events_auto_approve">
                                        <i class="ph-duotone ph-calendar me-2"></i>
                                        Eventi - Auto Approval
                                    </label>
                                </div>
                                <small class="text-muted">Gli eventi verranno automaticamente approvati</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="photos_auto_approve" name="photos_auto_approve" value="1" {{ ($formSettings['photos_auto_approve']['value'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="photos_auto_approve">
                                        <i class="ph-duotone ph-image me-2"></i>
                                        Foto - Auto Approval
                                    </label>
                                </div>
                                <small class="text-muted">Le foto verranno automaticamente approvate</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="carousels_auto_approve" name="carousels_auto_approve" value="1" {{ ($formSettings['carousels_auto_approve']['value'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="carousels_auto_approve">
                                        <i class="ph-duotone ph-slideshow me-2"></i>
                                        Caroselli - Auto Approval
                                    </label>
                                </div>
                                <small class="text-muted">I caroselli verranno automaticamente approvati</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="comments_auto_approve" name="comments_auto_approve" value="1" {{ ($formSettings['comments_auto_approve']['value'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="comments_auto_approve">
                                        <i class="ph-duotone ph-chat-circle me-2"></i>
                                        Commenti - Auto Approval
                                    </label>
                                </div>
                                <small class="text-muted">I commenti verranno automaticamente approvati</small>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Notifica Email per Contenuti in Attesa</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" value="1" {{ ($formSettings['email_notifications']['value'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_notifications">
                                        Abilita notifiche email
                                    </label>
                                </div>
                                <small class="text-muted">Invia email agli admin quando ci sono contenuti in attesa</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Limite Contenuti per Pagina</label>
                                <input type="number" class="form-control" name="items_per_page" value="{{ $formSettings['items_per_page']['value'] ?? 20 }}" min="5" max="100">
                                <small class="text-muted">Numero di contenuti mostrati per pagina nella moderazione</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Retention Segnalazioni (giorni)</label>
                                <input type="number" class="form-control" name="reports_retention_days" value="{{ $formSettings['reports_retention_days']['value'] ?? 30 }}" min="1" max="365">
                                <small class="text-muted">Dopo quanti giorni le segnalazioni risolte vengono archiviate</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Auto-Archiviazione Contenuti Rifiutati</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="auto_archive_rejected" name="auto_archive_rejected" value="1" {{ ($formSettings['auto_archive_rejected']['value'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_archive_rejected">
                                        Archivia automaticamente
                                    </label>
                                </div>
                                <small class="text-muted">Archivia automaticamente i contenuti rifiutati dopo un periodo</small>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph-duotone ph-floppy-disk me-2"></i>
                                Salva Impostazioni
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Statistiche Impostazioni -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-chart-bar me-2"></i>
                        Statistiche Attuali
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Auto-Approval Attivo</h6>
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between">
                                <span>Video:</span>
                                <span class="badge bg-{{ ($formSettings['videos_auto_approve']['value'] ?? false) ? 'success' : 'secondary' }}">
                                    {{ ($formSettings['videos_auto_approve']['value'] ?? false) ? 'Sì' : 'No' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Poesie:</span>
                                <span class="badge bg-{{ ($formSettings['poems_auto_approve']['value'] ?? false) ? 'success' : 'secondary' }}">
                                    {{ ($formSettings['poems_auto_approve']['value'] ?? false) ? 'Sì' : 'No' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Eventi:</span>
                                <span class="badge bg-{{ ($formSettings['events_auto_approve']['value'] ?? false) ? 'success' : 'secondary' }}">
                                    {{ ($formSettings['events_auto_approve']['value'] ?? false) ? 'Sì' : 'No' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Foto:</span>
                                <span class="badge bg-{{ ($formSettings['photos_auto_approve']['value'] ?? false) ? 'success' : 'secondary' }}">
                                    {{ ($formSettings['photos_auto_approve']['value'] ?? false) ? 'Sì' : 'No' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Caroselli:</span>
                                <span class="badge bg-{{ ($formSettings['carousels_auto_approve']['value'] ?? false) ? 'success' : 'secondary' }}">
                                    {{ ($formSettings['carousels_auto_approve']['value'] ?? false) ? 'Sì' : 'No' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Commenti:</span>
                                <span class="badge bg-{{ ($formSettings['comments_auto_approve']['value'] ?? false) ? 'success' : 'secondary' }}">
                                    {{ ($formSettings['comments_auto_approve']['value'] ?? false) ? 'Sì' : 'No' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6>Notifiche</h6>
                        <div class="d-flex justify-content-between">
                            <span>Email:</span>
                                                            <span class="badge bg-{{ ($formSettings['email_notifications']['value'] ?? false) ? 'success' : 'secondary' }}">
                                    {{ ($formSettings['email_notifications']['value'] ?? false) ? 'Attive' : 'Disattive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informazioni -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-info me-2"></i>
                        Informazioni
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6>Come Funziona</h6>
                        <ul class="mb-0">
                            <li><strong>Auto-Approval:</strong> I contenuti vengono automaticamente approvati senza intervento manuale</li>
                            <li><strong>Moderazione Manuale:</strong> I contenuti richiedono l'approvazione di un admin/moderatore</li>
                            <li><strong>Notifiche:</strong> Ricevi email quando ci sono contenuti in attesa di moderazione</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Mostra notifiche di successo se ci sono messaggi flash
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Successo!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Errore!',
        text: '{{ session('error') }}'
    });
@endif
</script>
@endpush
