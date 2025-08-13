<?php $__env->startSection('title', 'Contenuti in Attesa - Moderazione'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Contenuti in Attesa</h4>
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
        <!-- Breadcrumb end -->

        <!-- Header Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1 f-w-600">
                            <i class="ph-duotone ph-list-checks me-2"></i>
                            Gestisci i contenuti in attesa di moderazione
                        </h5>
                        <p class="text-muted mb-0">Approva o rifiuta i contenuti degli utenti</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('admin.moderation.index')); ?>" class="btn btn-outline-secondary">
                            <i class="ph-duotone ph-arrow-left me-2"></i>
                            <?php echo e(__('dashboard.dashboard')); ?>

                        </a>
                        <a href="<?php echo e(route('admin.moderation.settings')); ?>" class="btn btn-outline-primary">
                            <i class="ph-duotone ph-gear me-2"></i>
                            Impostazioni
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtri -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-funnel me-2"></i>
                            Filtri
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label">Tipo di Contenuto</label>
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
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label"><?php echo e(__('invitations.status')); ?></label>
                                <select name="status" class="form-select">
                                    <option value="pending" <?php echo e($status == 'pending' ? 'selected' : ''); ?>><?php echo e(__('invitations.pending_invitations')); ?></option>
                                    <option value="approved" <?php echo e($status == 'approved' ? 'selected' : ''); ?>>Approvati</option>
                                    <option value="rejected" <?php echo e($status == 'rejected' ? 'selected' : ''); ?>><?php echo e(__('invitations.rejected_invitations')); ?></option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label">Filtro</label>
                                <select name="filter" class="form-select">
                                    <option value="all" <?php echo e($filter == 'all' ? 'selected' : ''); ?>>Tutti</option>
                                    <option value="pending" <?php echo e($filter == 'pending' ? 'selected' : ''); ?>>Da Approvare</option>
                                    <option value="reports" <?php echo e($filter == 'reports' ? 'selected' : ''); ?>>Segnalazioni</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="ph-duotone ph-magnifying-glass me-2"></i>
                                        Filtra
                                    </button>
                                    <a href="<?php echo e(route('admin.moderation.pending')); ?>" class="btn btn-outline-secondary">
                                        <i class="ph-duotone ph-arrow-clockwise"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenuti -->
        <div class="row">
            <?php if($filter == 'reports' || $filter == 'all'): ?>
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="ph-duotone ph-flag me-2"></i>
                                Segnalazioni Recenti
                            </h5>
                            <span class="badge bg-warning"><?php echo e($reports->count()); ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if($reports->count() > 0): ?>
                            <div class="row">
                                <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-lg-6 col-md-12 mb-3">
                                    <div class="card hover-effect">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar avatar-md bg-warning">
                                                        <i class="ph-duotone ph-flag text-white"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1 f-w-600"><?php echo e(Str::limit($report->reportable_title, 50)); ?></h6>
                                                    <p class="text-muted mb-2 f-s-14">
                                                        <i class="ph-duotone ph-user me-1"></i>
                                                        <?php echo e($report->user->name ?? 'N/A'); ?>

                                                    </p>
                                                    <p class="text-muted mb-2 f-s-14">
                                                        <i class="ph-duotone ph-warning me-1"></i>
                                                        <?php echo e($report->reason_text); ?>

                                                    </p>
                                                    <?php if($report->description): ?>
                                                    <p class="text-muted mb-2 f-s-14">
                                                        <i class="ph-duotone ph-chat-circle me-1"></i>
                                                        <?php echo e(Str::limit($report->description, 100)); ?>

                                                    </p>
                                                    <?php endif; ?>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            <i class="ph-duotone ph-calendar me-1"></i>
                                                            <?php echo e($report->created_at->diffForHumans()); ?>

                                                        </small>
                                                        <span class="badge bg-<?php echo e($report->status_class); ?>"><?php echo e($report->status_text); ?></span>
                                                    </div>
                                                    
                                                    <!-- Pulsanti di azione -->
                                                    <div class="d-flex gap-2 mt-3">
                                                        <button class="btn btn-sm btn-success" onclick="approveReportedContent('<?php echo e($report->reportable_type); ?>', <?php echo e($report->reportable_id); ?>)" title="Approva contenuto">
                                                            <i class="ph-duotone ph-check me-1"></i>
                                                            Approva
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" onclick="rejectReportedContent('<?php echo e($report->reportable_type); ?>', <?php echo e($report->reportable_id); ?>)" title="Rifiuta contenuto">
                                                            <i class="ph-duotone ph-x me-1"></i>
                                                            Rifiuta
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-primary" onclick="viewReportDetails(<?php echo e($report->id); ?>)" title="Visualizza dettagli">
                                                            <i class="ph-duotone ph-magnifying-glass"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-warning" onclick="handleReport(<?php echo e($report->id); ?>, 'investigate')" title="Metti in investigazione">
                                                            <i class="ph-duotone ph-search"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="ph-duotone ph-flag f-s-48 text-muted mb-3"></i>
                                <h5 class="text-muted">Nessuna segnalazione attiva</h5>
                                <p class="text-muted">Non ci sono segnalazioni da gestire al momento.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($filter == 'pending' || $filter == 'all'): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="ph-duotone ph-list-checks me-2"></i>
                                Contenuti <?php echo e(ucfirst($status)); ?>

                            </h5>
                            <?php
                                $totalContent = 0;
                                foreach($content as $type => $items) {
                                    $totalContent += $items->count();
                                }
                            ?>
                            <span class="badge bg-primary"><?php echo e($totalContent); ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                            $hasContent = false;
                            foreach($content as $type => $items) {
                                if($items->count() > 0) {
                                    $hasContent = true;
                                    break;
                                }
                            }
                        ?>

                        <?php if($hasContent): ?>
                            <?php $__currentLoopData = $content; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($items->count() > 0): ?>
                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted mb-3 f-w-600">
                                        <i class="ph-duotone ph-<?php echo e($type == 'videos' ? 'video-camera' : ($type == 'poems' ? 'book-open' : ($type == 'events' ? 'calendar' : ($type == 'photos' ? 'image' : ($type == 'carousels' ? 'slideshow' : 'chat-circle'))))); ?> me-2"></i>
                                        <?php echo e(ucfirst(str_replace('_', ' ', $type))); ?> (<?php echo e($items->count()); ?>)
                                    </h6>
                                    <div class="row">
                                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-lg-6 col-md-12 mb-3">
                                            <div class="card hover-effect">
                                                <?php if($type == 'videos' && $item->thumbnail_path): ?>
                                                <img src="<?php echo e($item->thumbnail_url); ?>" class="card-img-top" alt="<?php echo e($item->title); ?>" style="height: 150px; object-fit: cover;">
                                                <?php endif; ?>
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="card-title f-w-600 mb-0">
                                                            <?php echo e(Str::limit($item->title ?? $item->content ?? 'N/A', 50)); ?>

                                                        </h6>
                                                        <span class="badge bg-<?php echo e($status == 'pending' ? 'warning' : ($status == 'approved' ? 'success' : 'danger')); ?>">
                                                            <?php echo e(ucfirst($status)); ?>

                                                        </span>
                                                    </div>

                                                    <p class="text-muted f-s-14 mb-2">
                                                        <i class="ph-duotone ph-user f-s-12 me-1"></i>
                                                        <?php echo e($item->user->name ?? $item->organizer->name ?? 'N/A'); ?>

                                                    </p>

                                                    <?php if($item->description): ?>
                                                    <p class="card-text f-s-14 mb-2"><?php echo e(Str::limit($item->description, 100)); ?></p>
                                                    <?php endif; ?>

                                                    <?php if($type == 'poems' && $item->content): ?>
                                                    <div class="mb-2">
                                                        <small class="text-muted f-s-12">Anteprima contenuto:</small>
                                                        <p class="f-s-14 mb-0"><?php echo e(Str::limit(strip_tags($item->content), 150)); ?></p>
                                                    </div>
                                                    <?php endif; ?>

                                                    <?php if($type == 'events'): ?>
                                                    <div class="mb-2">
                                                        <small class="text-muted f-s-12">Dettagli evento:</small>
                                                        <p class="f-s-14 mb-0">
                                                            <i class="ph-duotone ph-calendar me-1"></i>
                                                            <?php echo e($item->start_datetime ? $item->start_datetime->format('d/m/Y H:i') : 'N/A'); ?>

                                                            <?php if($item->city): ?>
                                                            <br><i class="ph-duotone ph-map-pin me-1"></i>
                                                            <?php echo e($item->city); ?>

                                                            <?php endif; ?>
                                                        </p>
                                                    </div>
                                                    <?php endif; ?>

                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                                            <?php echo e($item->created_at->diffForHumans()); ?>

                                                        </small>
                                                        
                                                        <?php if($status == 'pending'): ?>
                                                        <div class="d-flex gap-2">
                                                            <button class="btn btn-sm btn-success" onclick="approveContent('<?php echo e($type); ?>', <?php echo e($item->id); ?>)" title="Approva contenuto">
                                                                <i class="ph-duotone ph-check me-1"></i>
                                                                Approva
                                                            </button>
                                                            <button class="btn btn-sm btn-danger" onclick="rejectContent('<?php echo e($type); ?>', <?php echo e($item->id); ?>)" title="Rifiuta contenuto">
                                                                <i class="ph-duotone ph-x me-1"></i>
                                                                Rifiuta
                                                            </button>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="ph-duotone ph-list-checks f-s-48 text-muted mb-3"></i>
                                <h5 class="text-muted">Nessun contenuto trovato</h5>
                                <p class="text-muted">Non ci sono contenuti con i filtri selezionati.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal per note di moderazione -->
<div class="modal fade" id="moderationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Note di Moderazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <textarea id="moderationNotes" class="form-control" rows="3" placeholder="Inserisci note opzionali..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="confirmModeration">Conferma</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal per dettagli segnalazione -->
<div class="modal fade" id="reportDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dettagli Segnalazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="reportDetailsContent">
                <!-- Contenuto dinamico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal per gestione segnalazione -->
<div class="modal fade" id="reportActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestione Segnalazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Azione da eseguire:</label>
                    <select id="reportAction" class="form-select">
                        <option value="investigate">Metti in investigazione</option>
                        <option value="resolve">Risolta</option>
                        <option value="dismiss">Archiviata</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Note (opzionali):</label>
                    <textarea id="reportActionNotes" class="form-control" rows="3" placeholder="Inserisci note..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="confirmReportAction">Conferma</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let currentAction = null;
let currentType = null;
let currentId = null;
let currentReportId = null;

// Funzioni per contenuti normali
function approveContent(type, id) {
    currentAction = 'approve';
    currentType = type;
    currentId = id;
    $('#moderationNotes').val('');
    $('#moderationModal').modal('show');
}

function rejectContent(type, id) {
    currentAction = 'reject';
    currentType = type;
    currentId = id;
    $('#moderationNotes').val('');
    $('#moderationModal').modal('show');
}

// Funzioni per contenuti segnalati
function approveReportedContent(type, id) {
    currentAction = 'approve';
    currentType = type;
    currentId = id;
    $('#moderationNotes').val('');
    $('#moderationModal').modal('show');
}

function rejectReportedContent(type, id) {
    currentAction = 'reject';
    currentType = type;
    currentId = id;
    $('#moderationNotes').val('');
    $('#moderationModal').modal('show');
}

// Visualizza dettagli segnalazione
function viewReportDetails(reportId) {
    // Per ora mostra un modal semplice, in futuro può caricare i dettagli via AJAX
    $('#reportDetailsContent').html(`
        <div class="text-center">
            <i class="ph-duotone ph-flag f-s-48 text-warning mb-3"></i>
            <h5>Dettagli Segnalazione</h5>
            <p class="text-muted">Funzionalità in sviluppo. I dettagli completi saranno disponibili presto.</p>
        </div>
    `);
    $('#reportDetailsModal').modal('show');
}

// Gestione segnalazione con modal elegante
function handleReport(reportId, action) {
    currentReportId = reportId;
    $('#reportAction').val(action);
    $('#reportActionNotes').val('');
    $('#reportActionModal').modal('show');
}

// Conferma azione segnalazione
$('#confirmReportAction').click(function() {
    const action = $('#reportAction').val();
    const notes = $('#reportActionNotes').val();

    $.ajax({
        url: '<?php echo e(route("admin.moderation.reports.handle", ["report" => ":report"])); ?>'.replace(':report', currentReportId),
        method: 'POST',
        data: {
            action: action,
            notes: notes,
            _token: '<?php echo e(csrf_token()); ?>'
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Successo!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: response.message
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: 'Errore durante l\'operazione'
            });
        }
    });

    $('#reportActionModal').modal('hide');
});

// Conferma moderazione contenuto
$('#confirmModeration').click(function() {
    const notes = $('#moderationNotes').val();
    let url;

    if (currentAction === 'approve') {
        url = '<?php echo e(route("admin.moderation.approve", ["type" => ":type", "id" => ":id"])); ?>'
            .replace(':type', currentType)
            .replace(':id', currentId);
    } else if (currentAction === 'reject') {
        url = '<?php echo e(route("admin.moderation.reject", ["type" => ":type", "id" => ":id"])); ?>'
            .replace(':type', currentType)
            .replace(':id', currentId);
    }

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            notes: notes,
            _token: '<?php echo e(csrf_token()); ?>'
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Successo!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: response.message
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: 'Errore durante l\'operazione'
            });
        }
    });

    $('#moderationModal').modal('hide');
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/moderation/pending.blade.php ENDPATH**/ ?>