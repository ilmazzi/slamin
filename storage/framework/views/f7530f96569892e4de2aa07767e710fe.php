<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['content', 'type', 'size' => 'sm']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['content', 'type', 'size' => 'sm']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $isReported = $content->isReportedByUser();
    $reportCount = $content->active_reports_count;
?>

<div class="report-button-container">
    <?php if($isReported): ?>
        <!-- Pulsante per rimuovere la segnalazione -->
        <div class="report-remove-btn"
             data-type="<?php echo e($type); ?>"
             data-id="<?php echo e($content->id); ?>"
             title="Rimuovi segnalazione"
             style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; transition: all 0.2s;"
             onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
             onmouseout="this.style.backgroundColor='transparent'">
            <i class="ti ti-flag f-s-24 text-warning"></i>
            <span class="text-secondary f-s-12">Segnalato</span>
        </div>
    <?php else: ?>
        <!-- Pulsante per segnalare -->
        <div class="report-btn"
             data-type="<?php echo e($type); ?>"
             data-id="<?php echo e($content->id); ?>"
             title="Segnala contenuto"
             style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; transition: all 0.2s;"
             onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
             onmouseout="this.style.backgroundColor='transparent'">
            <i class="ti ti-flag f-s-24 text-muted"></i>
            <span class="text-secondary f-s-12">Segnala</span>
        </div>
    <?php endif; ?>

    <?php if($reportCount > 0): ?>
        <span class="badge bg-warning ms-1" title="<?php echo e($reportCount); ?> segnalazioni attive">
            <?php echo e($reportCount); ?>

        </span>
    <?php endif; ?>
</div>

<?php if (! $__env->hasRenderedOnce('54239d91-b5a3-4bea-a297-5e9ba0286dce')): $__env->markAsRenderedOnce('54239d91-b5a3-4bea-a297-5e9ba0286dce'); ?>
<?php $__env->startPush('scripts'); ?>
<script>
// Variabile globale per tracciare se il modal è già aperto
window.reportModalOpen = false;

document.addEventListener('DOMContentLoaded', function() {
    // Gestione segnalazione con modal
    document.querySelectorAll('.report-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (window.reportModalOpen) return; // Se il modal è già aperto, non fare nulla
            
            const type = this.dataset.type;
            const id = this.dataset.id;
            
            showReportModal(type, id);
        });
    });

    // Gestione rimozione segnalazione
    document.querySelectorAll('.report-remove-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const type = this.dataset.type;
            const id = this.dataset.id;
            
            if (confirm('Sei sicuro di voler rimuovere la tua segnalazione?')) {
                removeReport(type, id);
            }
        });
    });
});

function showReportModal(type, id) {
    // Se il modal è già aperto, non fare nulla
    if (window.reportModalOpen) return;
    
    // Rimuovi qualsiasi modal esistente
    const existingModal = document.getElementById('reportModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Crea il modal HTML
    const modalHtml = `
        <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reportModalLabel">Segnala Contenuto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="reportForm">
                            <input type="hidden" name="type" value="${type}">
                            <input type="hidden" name="id" value="${id}">
                            
                            <div class="mb-3">
                                <label for="reason" class="form-label">Motivo della segnalazione *</label>
                                <select class="form-select" name="reason" id="reason" required>
                                    <option value="">Seleziona un motivo</option>
                                    <option value="spam">Spam</option>
                                    <option value="inappropriate">Contenuto inappropriato</option>
                                    <option value="violence">Violenza</option>
                                    <option value="harassment">Molestie</option>
                                    <option value="copyright">Violazione copyright</option>
                                    <option value="misinformation">Disinformazione</option>
                                    <option value="other">Altro</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Descrizione (opzionale)</label>
                                <textarea class="form-control" name="description" id="description" rows="3" maxlength="1000" placeholder="Fornisci ulteriori dettagli..."></textarea>
                                <div class="form-text">
                                    <span class="char-count">0</span>/1000 caratteri
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-danger" id="submitReportBtn">
                            <span class="btn-text">Segnala</span>
                            <span class="btn-loader d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Elaborando...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Aggiungi il modal al body
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Mostra il modal
    const modal = new bootstrap.Modal(document.getElementById('reportModal'));
    modal.show();
    
    // Marca il modal come aperto
    window.reportModalOpen = true;
    
    // Aggiungi event listener al pulsante Segnala
    const submitBtn = document.querySelector('#reportModal .btn-danger');
    if (submitBtn) {
        submitBtn.addEventListener('click', submitReport);
    }
    
    // Contatore caratteri
    const textarea = document.getElementById('description');
    const charCount = document.querySelector('.char-count');
    if (textarea && charCount) {
        textarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }
    
    // Rimuovi il modal quando viene chiuso
    const modalElement = document.getElementById('reportModal');
    if (modalElement) {
        modalElement.addEventListener('hidden.bs.modal', function() {
            this.remove();
            window.reportModalOpen = false;
        });
    }
}

function submitReport() {
    const form = document.getElementById('reportForm');
    if (!form) {
        console.error('Form non trovato');
        return;
    }
    
    const formData = new FormData(form);
    
    // Debug
    console.log('Form data:', {
        type: formData.get('type'),
        id: formData.get('id'),
        reason: formData.get('reason'),
        description: formData.get('description')
    });
    
    // Verifica che i campi obbligatori siano presenti
    if (!formData.get('type') || !formData.get('id') || !formData.get('reason')) {
        showNotification('Compila tutti i campi obbligatori', 'error');
        return;
    }
    
    // Attiva il loader
    const submitBtn = document.getElementById('submitReportBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoader = submitBtn.querySelector('.btn-loader');
    
    submitBtn.disabled = true;
    btnText.classList.add('d-none');
    btnLoader.classList.remove('d-none');
    
    // Ottieni il token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '<?php echo e(csrf_token()); ?>';
    
    fetch('/reports/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            type: formData.get('type'),
            id: formData.get('id'),
            reason: formData.get('reason'),
            description: formData.get('description')
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Chiudi il modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
            if (modal) {
                modal.hide();
            }
            window.reportModalOpen = false;
            
            // Mostra messaggio di successo
            showNotification('Segnalazione inviata con successo!', 'success');
            
            // Aggiorna il pulsante
            updateReportButton(data.type, data.id, true);
        } else {
            showNotification(data.message || 'Errore durante l\'invio della segnalazione', 'error');
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        showNotification('Errore di connessione', 'error');
    })
    .finally(() => {
        // Disattiva il loader
        submitBtn.disabled = false;
        btnText.classList.remove('d-none');
        btnLoader.classList.add('d-none');
    });
}

function removeReport(type, id) {
    // Ottieni il token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '<?php echo e(csrf_token()); ?>';
    
    fetch('/reports/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            type: type,
            id: id
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Segnalazione rimossa con successo!', 'success');
            
            // Aggiorna il pulsante
            updateReportButton(type, id, false);
        } else {
            showNotification(data.message || 'Errore durante la rimozione della segnalazione', 'error');
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        showNotification('Errore di connessione', 'error');
    });
}

function updateReportButton(type, id, isReported) {
    const reportButton = document.querySelector(`[data-type="${type}"][data-id="${id}"]`);
    if (!reportButton) {
        console.error('Pulsante non trovato:', type, id);
        return;
    }
    const reportContainer = reportButton.closest('.report-button-container');
    
    if (isReported) {
        reportContainer.innerHTML = `
            <div class="report-remove-btn"
                 data-type="${type}"
                 data-id="${id}"
                 title="Rimuovi segnalazione"
                 style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; transition: all 0.2s;"
                 onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
                 onmouseout="this.style.backgroundColor='transparent'">
                <i class="ti ti-flag f-s-24 text-warning"></i>
                <span class="text-secondary f-s-12">Segnalato</span>
            </div>
        `;
        
        // Aggiungi event listener al nuovo pulsante
        reportContainer.querySelector('.report-remove-btn').addEventListener('click', function() {
            if (confirm('Sei sicuro di voler rimuovere la tua segnalazione?')) {
                removeReport(type, id);
            }
        });
    } else {
        reportContainer.innerHTML = `
            <div class="report-btn"
                 data-type="${type}"
                 data-id="${id}"
                 title="Segnala contenuto"
                 style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; transition: all 0.2s;"
                 onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
                 onmouseout="this.style.backgroundColor='transparent'">
                <i class="ti ti-flag f-s-24 text-muted"></i>
                <span class="text-secondary f-s-12">Segnala</span>
            </div>
        `;
        
        // Aggiungi event listener al nuovo pulsante
        reportContainer.querySelector('.report-btn').addEventListener('click', function() {
            showReportModal(type, id);
        });
    }
}

function showNotification(message, type) {
    // Use SweetAlert or similar notification system
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            text: message,
            icon: type,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    } else {
        alert(message);
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/components/report-button.blade.php ENDPATH**/ ?>