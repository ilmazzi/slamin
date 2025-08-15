<?php $__env->startSection('title', 'Contenuti in Attesa - Moderazione'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Mobile-First Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title f-s-18 f-w-600">Contenuti in Attesa</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="<?php echo e(route('dashboard')); ?>" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-gauge f-s-16"></i> <?php echo e(__('dashboard.dashboard')); ?>

                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="<?php echo e(route('admin.moderation.index')); ?>" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-shield-check f-s-16"></i> Moderazione
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <span class="f-s-14 f-w-500">
                            <i class="ph-duotone ph-list-checks f-s-16"></i> Contenuti in Attesa
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Mobile-First Header Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                    <div>
                        <h5 class="mb-1 f-w-600 f-s-16">
                            <i class="ph-duotone ph-list-checks me-2"></i>
                            Gestisci i contenuti in attesa di moderazione
                        </h5>
                        <p class="text-muted mb-0 f-s-14">Approva o rifiuta i contenuti degli utenti</p>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="<?php echo e(route('admin.moderation.index')); ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="ph-duotone ph-arrow-left me-2"></i>
                            <?php echo e(__('dashboard.dashboard')); ?>

                        </a>
                        <a href="<?php echo e(route('admin.moderation.settings')); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="ph-duotone ph-gear me-2"></i>
                            Impostazioni
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile-First Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 f-s-16 f-w-600">
                            <i class="ph-duotone ph-funnel me-2"></i>
                            Filtri
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label f-s-14 f-w-500">Tipo di Contenuto</label>
                                <select name="type" class="form-select">
                                    <option value="all" <?php echo e($type == 'all' ? 'selected' : ''); ?>>Tutti i contenuti</option>
                                    <option value="videos" <?php echo e($type == 'videos' ? 'selected' : ''); ?>><?php echo e(__('common.video')); ?></option>
                                    <option value="poems" <?php echo e($type == 'poems' ? 'selected' : ''); ?>>Poesie</option>
                                    <option value="events" <?php echo e($type == 'events' ? 'selected' : ''); ?>>Eventi</option>
                                    <option value="photos" <?php echo e($type == 'photos' ? 'selected' : ''); ?>><?php echo e(__('common.photo')); ?></option>
                                    <option value="carousels" <?php echo e($type == 'carousels' ? 'selected' : ''); ?>>Caroselli</option>
                                    <option value="video_comments" <?php echo e($type == 'video_comments' ? 'selected' : ''); ?>><?php echo e(__('common.comments_section')); ?> <?php echo e(__('common.video')); ?></option>
                                    <option value="poem_comments" <?php echo e($type == 'poem_comments' ? 'selected' : ''); ?>><?php echo e(__('common.comments_section')); ?> Poesie</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label f-s-14 f-w-500"><?php echo e(__('invitations.status')); ?></label>
                                <select name="status" class="form-select">
                                    <option value="pending" <?php echo e($status == 'pending' ? 'selected' : ''); ?>><?php echo e(__('invitations.pending_invitations')); ?></option>
                                    <option value="approved" <?php echo e($status == 'approved' ? 'selected' : ''); ?>>Approvati</option>
                                    <option value="rejected" <?php echo e($status == 'rejected' ? 'selected' : ''); ?>><?php echo e(__('invitations.rejected_invitations')); ?></option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label f-s-14 f-w-500">Filtro</label>
                                <select name="filter" class="form-select">
                                    <option value="all" <?php echo e($filter == 'all' ? 'selected' : ''); ?>>Tutti</option>
                                    <option value="pending" <?php echo e($filter == 'pending' ? 'selected' : ''); ?>>Da Approvare</option>
                                    <option value="reports" <?php echo e($filter == 'reports' ? 'selected' : ''); ?>>Segnalazioni</option>
                                    <option value="flagged" <?php echo e($filter == 'flagged' ? 'selected' : ''); ?>>Contrassegnati</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label f-s-14 f-w-500">Ordina per</label>
                                <select name="sort" class="form-select">
                                    <option value="newest" <?php echo e($sort == 'newest' ? 'selected' : ''); ?>>Più recenti</option>
                                    <option value="oldest" <?php echo e($sort == 'oldest' ? 'selected' : ''); ?>>Più vecchi</option>
                                    <option value="priority" <?php echo e($sort == 'priority' ? 'selected' : ''); ?>>Priorità</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="d-flex flex-column flex-sm-row gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ph-duotone ph-magnifying-glass me-2"></i>
                                        Applica Filtri
                                    </button>
                                    <a href="<?php echo e(route('admin.moderation.pending')); ?>" class="btn btn-outline-secondary btn-sm">
                                        <i class="ph-duotone ph-arrow-clockwise me-2"></i>
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile-First Content List -->
        <div class="row g-3">
            <?php $__empty_1 = true; $__currentLoopData = $contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-12 col-lg-6">
                <div class="card hover-effect">
                    <div class="card-header">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ph-duotone <?php echo e(getContentIcon($content->type)); ?> f-s-16"></i>
                                <h6 class="mb-0 f-s-16 f-w-600"><?php echo e(getContentTypeName($content->type)); ?></h6>
                            </div>
                            <span class="badge <?php echo e(getStatusBadgeClass($content->status)); ?> f-s-12">
                                <?php echo e(getStatusText($content->status)); ?>

                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Mobile-First Content Preview -->
                        <div class="d-flex flex-column gap-3">
                            <!-- Content Header -->
                            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 f-s-16 f-w-600"><?php echo e(Str::limit($content->title ?? $content->content, 50)); ?></h6>
                                    <small class="text-muted f-s-12">
                                        <i class="ph-duotone ph-user me-1"></i>
                                        <?php echo e($content->user->name ?? 'N/A'); ?>

                                    </small>
                                </div>
                                                <div class="flex-shrink-0">
                                    <small class="text-muted f-s-12">
                                        <i class="ph-duotone ph-calendar me-1"></i>
                                        <?php echo e($content->created_at->diffForHumans()); ?>

                                    </small>
                                                    </div>
                                                </div>

                            <!-- Content Preview -->
                            <?php if($content->type === 'videos' && $content->thumbnail_url): ?>
                            <div class="text-center">
                                <img src="<?php echo e($content->thumbnail_url); ?>" alt="<?php echo e($content->title); ?>"
                                     class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                            <?php elseif($content->type === 'photos' && $content->image_url): ?>
                            <div class="text-center">
                                <img src="<?php echo e($content->image_url); ?>" alt="<?php echo e($content->title); ?>"
                                     class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                            <?php elseif($content->type === 'poems' && $content->content): ?>
                            <div class="border rounded p-3 bg-light">
                                <pre class="mb-0 f-s-13" style="white-space: pre-wrap;"><?php echo e(Str::limit($content->content, 200)); ?></pre>
                            </div>
                            <?php elseif($content->type === 'events' && $content->description): ?>
                            <div class="border rounded p-3 bg-light">
                                <p class="mb-0 f-s-13"><?php echo e(Str::limit($content->description, 200)); ?></p>
                            </div>
                                                    <?php endif; ?>

                            <!-- Mobile-First Action Buttons -->
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <div class="d-flex gap-1 flex-grow-1">
                                    <button class="btn btn-success btn-sm flex-fill"
                                            onclick="approveContent('<?php echo e($content->type); ?>', <?php echo e($content->id); ?>)"
                                            title="Approva">
                                        <i class="ph-duotone ph-check f-s-14 me-1"></i>
                                        <span class="d-none d-sm-inline">Approva</span>
                                                        </button>
                                    <button class="btn btn-danger btn-sm flex-fill"
                                            onclick="rejectContent('<?php echo e($content->type); ?>', <?php echo e($content->id); ?>)"
                                            title="Rifiuta">
                                        <i class="ph-duotone ph-x f-s-14 me-1"></i>
                                        <span class="d-none d-sm-inline">Rifiuta</span>
                                                        </button>
                                                    </div>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-outline-primary btn-sm"
                                            onclick="viewContent('<?php echo e($content->type); ?>', <?php echo e($content->id); ?>)"
                                            title="Visualizza">
                                        <i class="ph-duotone ph-eye f-s-14"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-sm"
                                            onclick="editContent('<?php echo e($content->type); ?>', <?php echo e($content->id); ?>)"
                                            title="Modifica">
                                        <i class="ph-duotone ph-pencil f-s-14"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <?php if($content->reports_count > 0): ?>
                            <div class="alert alert-warning py-2 mb-0">
                                <small class="f-s-12">
                                    <i class="ph-duotone ph-flag me-1"></i>
                                    <?php echo e($content->reports_count); ?> segnalazione<?php echo e($content->reports_count > 1 ? 'i' : ''); ?>

                                </small>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph-duotone ph-check-circle f-s-48 text-success mb-3"></i>
                        <h5 class="f-s-18 f-w-600">Nessun contenuto in attesa</h5>
                        <p class="text-muted f-s-14">Tutti i contenuti sono stati moderati!</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Segnalazioni Section -->
        <?php if($filter === 'reports' || $filter === 'all'): ?>
        <div class="row g-3 mt-4">
            <div class="col-12">
                <h5 class="f-s-18 f-w-600 mb-3">
                    <i class="ph-duotone ph-flag me-2"></i>
                    Segnalazioni Recenti
                </h5>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-12 col-lg-6">
                <div class="card hover-effect">
                    <div class="card-header">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ph-duotone ph-flag f-s-16 text-warning"></i>
                                <h6 class="mb-0 f-s-16 f-w-600"><?php echo e(ucfirst($report->reason)); ?></h6>
                            </div>
                            <span class="badge <?php echo e(getReportStatusBadgeClass($report->status)); ?> f-s-12">
                                <?php echo e(getReportStatusText($report->status)); ?>

                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            <!-- Report Content Info -->
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ph-duotone ph-file-text f-s-14 text-muted"></i>
                                    <span class="f-s-14 f-w-500"><?php echo e(class_basename($report->reportable_type)); ?></span>
                                    <span class="text-muted">•</span>
                                    <span class="f-s-14"><?php echo e($report->user ? $report->user->name : 'Anonimo'); ?></span>
                                </div>
                                <?php if($report->content_title): ?>
                                <h6 class="mb-1 f-s-16 f-w-600"><?php echo e($report->content_title); ?></h6>
                                <?php endif; ?>
                                <?php if($report->description): ?>
                                <p class="mb-0 f-s-13 text-muted"><?php echo e(Str::limit($report->description, 150)); ?></p>
                                <?php endif; ?>
                                <small class="text-muted f-s-12">
                                    <i class="ph-duotone ph-calendar me-1"></i>
                                    <?php echo e($report->created_at->diffForHumans()); ?>

                                </small>
                            </div>

                            <!-- Report Action Buttons -->
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <div class="d-flex gap-1 flex-grow-1">
                                    <button class="btn btn-warning btn-sm flex-fill"
                                            onclick="investigateReport(<?php echo e($report->id); ?>)"
                                            title="Metti in Investigazione">
                                        <i class="ph-duotone ph-magnifying-glass f-s-14 me-1"></i>
                                        <span class="d-none d-sm-inline">Investiga</span>
                                    </button>
                                    <button class="btn btn-success btn-sm flex-fill"
                                            onclick="resolveReport(<?php echo e($report->id); ?>)"
                                            title="Risolve e Rifiuta Contenuto">
                                        <i class="ph-duotone ph-check-circle f-s-14 me-1"></i>
                                        <span class="d-none d-sm-inline">Risolve</span>
                                    </button>
                                </div>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-outline-primary btn-sm"
                                            onclick="viewReportedContent(<?php echo e($report->id); ?>)"
                                            title="Visualizza Contenuto">
                                        <i class="ph-duotone ph-eye f-s-14"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm"
                                            onclick="dismissReport(<?php echo e($report->id); ?>)"
                                            title="Respingi Segnalazione">
                                        <i class="ph-duotone ph-x-circle f-s-14"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph-duotone ph-check-circle f-s-48 text-success mb-3"></i>
                        <h5 class="mb-0 f-s-18 f-w-600">Nessuna segnalazione</h5>
                        <p class="text-muted f-s-14">Tutte le segnalazioni sono state gestite!</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Mobile-First Pagination -->
        <!-- Pagination temporarily disabled - will be re-enabled when proper pagination is implemented -->
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Mobile-First Moderation Functions
document.addEventListener('DOMContentLoaded', function() {
    // Mobile-specific adjustments
    const isMobile = window.innerWidth < 768;

    if (isMobile) {
        // Make buttons more touch-friendly on mobile
        const buttons = document.querySelectorAll('.btn-sm');
        buttons.forEach(btn => {
            btn.style.minHeight = '44px';
            btn.style.minWidth = '44px';
        });

        // Adjust card spacing for mobile
        const cards = document.querySelectorAll('.card.hover-effect');
        cards.forEach(card => {
            card.classList.add('mb-3');
        });
    }

    // Responsive adjustments
    function adjustMobileLayout() {
        const isMobile = window.innerWidth < 768;
        const contentCards = document.querySelectorAll('.card.hover-effect');

        if (isMobile) {
            contentCards.forEach(card => {
                card.classList.add('mb-3');
                });
            } else {
            contentCards.forEach(card => {
                card.classList.remove('mb-3');
            });
        }
    }

    // Initial adjustment
    adjustMobileLayout();

    // Adjust on resize
    window.addEventListener('resize', adjustMobileLayout);
});

function approveContent(type, id) {
    Swal.fire({
        title: 'Conferma Approvazione',
        text: 'Sei sicuro di voler approvare questo contenuto?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sì, Approva',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra loading
            Swal.fire({
                title: 'Approvazione in corso...',
                text: 'Attendi mentre approvo il contenuto',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/moderation/approve/${type}/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Approvato!',
                        text: data.message || 'Contenuto approvato con successo',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore!',
                        text: data.message || 'Errore durante l\'approvazione'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Errore di Connessione',
                    text: 'Impossibile connettersi al server. Riprova più tardi.'
                });
            });
        }
    });
}

function rejectContent(type, id) {
    Swal.fire({
        title: 'Motivo del Rifiuto',
        input: 'textarea',
        inputLabel: 'Inserisci il motivo del rifiuto',
        inputPlaceholder: 'Spiega perché questo contenuto è stato rifiutato...',
        inputAttributes: {
            'aria-label': 'Motivo del rifiuto'
        },
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Rifiuta',
        cancelButtonText: 'Annulla',
        inputValidator: (value) => {
            if (!value || value.trim().length < 10) {
                return 'Il motivo deve essere di almeno 10 caratteri';
            }
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            // Mostra loading
            Swal.fire({
                title: 'Rifiuto in corso...',
                text: 'Attendi mentre rifiuto il contenuto',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/moderation/reject/${type}/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({ reason: result.value.trim() })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Rifiutato!',
                        text: data.message || 'Contenuto rifiutato con successo',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore!',
                        text: data.message || 'Errore durante il rifiuto'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Errore di Connessione',
                    text: 'Impossibile connettersi al server. Riprova più tardi.'
                });
            });
        }
    });
}

function viewContent(type, id) {
    Swal.fire({
        title: 'Visualizza Contenuto',
        text: `Funzionalità per visualizzare ${type} con ID ${id} in sviluppo.`,
        icon: 'info',
        confirmButtonColor: '#007bff'
    });
}

function editContent(type, id) {
    Swal.fire({
        title: 'Modifica Contenuto',
        text: `Funzionalità per modificare ${type} con ID ${id} in sviluppo.`,
        icon: 'info',
        confirmButtonColor: '#007bff'
    });
}

// Funzioni per gestire le segnalazioni
function investigateReport(reportId) {
    Swal.fire({
        title: 'Metti in Investigazione',
        text: 'Vuoi mettere questa segnalazione in investigazione?',
        icon: 'question',
        input: 'textarea',
        inputLabel: 'Note per l\'investigazione',
        inputPlaceholder: 'Inserisci note o osservazioni...',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Investiga',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            handleReport(reportId, 'investigate', result.value);
        }
    });
}

function resolveReport(reportId) {
    Swal.fire({
        title: 'Risolve Segnalazione',
        text: 'Questa azione risolverà la segnalazione e rifiuterà il contenuto. Continuare?',
        icon: 'warning',
        input: 'textarea',
        inputLabel: 'Note per la risoluzione',
        inputPlaceholder: 'Inserisci note o motivazioni...',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Risolve',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            handleReport(reportId, 'resolve', result.value);
        }
    });
}

function dismissReport(reportId) {
    Swal.fire({
        title: 'Respingi Segnalazione',
        text: 'Vuoi respingere questa segnalazione?',
        icon: 'question',
        input: 'textarea',
        inputLabel: 'Motivo del respingimento',
        inputPlaceholder: 'Inserisci il motivo...',
        showCancelButton: true,
        confirmButtonColor: '#6c757d',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Respingi',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            handleReport(reportId, 'dismiss', result.value);
        }
    });
}

function viewReportedContent(reportId) {
    // Mostra i dettagli del contenuto segnalato
    fetch(`/admin/moderation/reports/${reportId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Dettagli Contenuto',
                    html: `
                        <div class="text-left">
                            <p><strong>Tipo:</strong> ${data.data.type || 'N/A'}</p>
                            <p><strong>Titolo:</strong> ${data.data.title || 'N/A'}</p>
                            <p><strong>Autore:</strong> ${data.data.author || 'N/A'}</p>
                            ${data.data.excerpt ? `<p><strong>Anteprima:</strong> ${data.data.excerpt}</p>` : ''}
                            ${data.data.start_date ? `<p><strong>Data inizio:</strong> ${data.data.start_date}</p>` : ''}
                            ${data.data.location ? `<p><strong>Luogo:</strong> ${data.data.location}</p>` : ''}
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonColor: '#007bff'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: 'Impossibile caricare i dettagli del contenuto'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: 'Errore di connessione. Riprova.'
            });
        });
}

function handleReport(reportId, action, notes) {
    // Mostra loading
    Swal.fire({
        title: 'Elaborazione in corso...',
        text: 'Attendi mentre elaboro la segnalazione',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/admin/moderation/reports/${reportId}/handle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ action: action, notes: notes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Completato!',
                text: data.message || 'Segnalazione gestita con successo',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: data.message || 'Errore durante la gestione della segnalazione'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Errore di Connessione',
            text: 'Impossibile connettersi al server. Riprova più tardi.'
        });
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php
function getContentIcon($type) {
    $icons = [
        'videos' => 'ph-video-camera',
        'photos' => 'ph-image',
        'poems' => 'ph-book-open',
        'events' => 'ph-calendar',
        'articles' => 'ph-newspaper',
        'carousels' => 'ph-images',
        'video_comments' => 'ph-chat-circle',
        'poem_comments' => 'ph-chat-circle'
    ];
    return $icons[$type] ?? 'ph-file-text';
}

function getContentTypeName($type) {
    $names = [
        'videos' => 'Video',
        'photos' => 'Foto',
        'poems' => 'Poesia',
        'events' => 'Evento',
        'articles' => 'Articolo',
        'carousels' => 'Carosello',
        'video_comments' => 'Commento Video',
        'poem_comments' => 'Commento Poesia'
    ];
    return $names[$type] ?? 'Contenuto';
}

function getStatusBadgeClass($status) {
    $classes = [
        'pending' => 'bg-warning',
        'approved' => 'bg-success',
        'rejected' => 'bg-danger',
        'flagged' => 'bg-info'
    ];
    return $classes[$status] ?? 'bg-secondary';
}

function getStatusText($status) {
    $texts = [
        'pending' => 'In Attesa',
        'approved' => 'Approvato',
        'rejected' => 'Rifiutato',
        'flagged' => 'Contrassegnato'
    ];
    return $texts[$status] ?? 'Sconosciuto';
}

function getReportStatusBadgeClass($status) {
    $classes = [
        'pending' => 'bg-warning',
        'investigating' => 'bg-info',
        'resolved' => 'bg-success',
        'dismissed' => 'bg-secondary'
    ];
    return $classes[$status] ?? 'bg-secondary';
}

function getReportStatusText($status) {
    $texts = [
        'pending' => 'In Attesa',
        'investigating' => 'In Investigazione',
        'resolved' => 'Risolta',
        'dismissed' => 'Respinta'
    ];
    return $texts[$status] ?? 'Sconosciuto';
}
?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/moderation/pending.blade.php ENDPATH**/ ?>