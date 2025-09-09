<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Header Compatto -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-translate f-s-18 me-2"></i>
                        <?php echo __('admin.language_' . $language) ?: ucfirst($language); ?> - <?php echo e(ucfirst($file)); ?>

                    </h4>
                    <small class="text-muted">
                        <?php echo e($stats['translated_keys']); ?>/<?php echo e($stats['total_keys']); ?> tradotte
                        (<?php echo e(round(($stats['translated_keys'] / $stats['total_keys']) * 100, 1)); ?>%)
                    </small>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('admin.translations.index')); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="ph-duotone ph-arrow-left me-1"></i> <?php echo e(__('admin.back')); ?>

                    </a>
                    <button type="button" class="btn btn-primary btn-sm" onclick="addNewKey()">
                        <i class="ph-duotone ph-plus f-s-12 me-1"></i> <?php echo e(__('admin.add_key')); ?>

                    </button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="copyFromItalian()">
                        <i class="ph-duotone ph-copy f-s-12 me-1"></i> <?php echo e(__('admin.copy_from_it')); ?>

                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="clearAllTranslations()">
                        <i class="ph-duotone ph-trash f-s-12 me-1"></i> <?php echo e(__('admin.clear_all')); ?>

                    </button>
                    <button type="button" class="btn btn-success btn-sm" onclick="saveTranslations()">
                        <i class="ph-duotone ph-floppy-disk f-s-12 me-1"></i> <?php echo e(__('admin.save')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtri e Ricerca Compatti -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-2">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="ph-duotone ph-magnifying-glass f-s-12"></i></span>
                                <input type="text" id="searchInput" class="form-control" placeholder="<?php echo e(__('admin.search_key_or_text')); ?>" onkeyup="filterTranslations()">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select id="statusFilter" class="form-select form-select-sm" onchange="filterTranslations()">
                                <option value=""><?php echo e(__('admin.all_statuses')); ?></option>
                                <option value="translated"><?php echo e(__('admin.translated_status')); ?></option>
                                <option value="missing"><?php echo e(__('admin.missing_status')); ?></option>
                                <option value="empty"><?php echo e(__('admin.empty_status')); ?></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="sortBy" class="form-select form-select-sm" onchange="sortTranslations()">
                                <option value="key"><?php echo e(__('admin.sort_by_key')); ?></option>
                                <option value="reference"><?php echo e(__('admin.sort_by_reference')); ?></option>
                                <option value="status"><?php echo e(__('admin.sort_by_status')); ?></option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="resetFilters()">
                                <i class="ph-duotone ph-arrow-clockwise f-s-12"></i> <?php echo e(__('admin.reset_filters')); ?>

                            </button>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted d-flex align-items-center h-100">
                                <span id="resultsCount">0</span> <?php echo e(__('admin.keys_found')); ?> |
                                <span class="text-success" id="translatedCount">0</span><?php echo e(__('admin.translated_short')); ?> |
                                <span class="text-warning" id="missingCount">0</span><?php echo e(__('admin.missing_short')); ?> |
                                <span class="text-danger" id="emptyCount">0</span><?php echo e(__('admin.empty_short')); ?>

                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Traduzioni in Tabella Compatta -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 25%;"><?php echo e(__('admin.key_column')); ?></th>
                                    <th style="width: 30%;"><?php echo e(__('admin.reference_column')); ?></th>
                                    <th style="width: 40%;"><?php echo e(__('admin.translation_column')); ?></th>
                                    <th style="width: 5%;"><?php echo e(__('admin.status_column')); ?></th>
                                </tr>
                            </thead>
                            <tbody id="translationsTable">
                                <?php $__currentLoopData = $translationData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="translation-row" data-key="<?php echo e($key); ?>" data-status="<?php echo e($data['is_translated'] ? 'translated' : ($data['is_missing'] ? 'missing' : 'empty')); ?>">
                                    <td>
                                        <code class="text-primary f-s-12"><?php echo e($key); ?></code>
                                    </td>
                                    <td>
                                        <div class="text-muted f-s-12" style="max-height: 60px; overflow-y: auto;">
                                            <?php echo e($data['reference']); ?>

                                        </div>
                                    </td>
                                    <td>
                                        <textarea
                                            name="translations[<?php echo e($key); ?>]"
                                            class="form-control form-control-sm translation-input"
                                            rows="2"
                                            style="resize: vertical; min-height: 40px;"
                                            data-key="<?php echo e($key); ?>"
                                        ><?php echo e($data['translation']); ?></textarea>
                                    </td>
                                    <td class="text-center">
                                        <?php if($data['is_translated']): ?>
                                            <i class="ph-duotone ph-check-circle text-success f-s-16" title="<?php echo e(__('admin.translated_tooltip')); ?>"></i>
                                        <?php elseif($data['is_missing']): ?>
                                            <i class="ph-duotone ph-warning-circle text-warning f-s-16" title="<?php echo e(__('admin.missing_tooltip')); ?>"></i>
                                        <?php else: ?>
                                            <i class="ph-duotone ph-x-circle text-danger f-s-16" title="<?php echo e(__('admin.empty_tooltip')); ?>"></i>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let allTranslations = <?php echo json_encode($translationData, 15, 512) ?>;
let currentFilter = '';
let currentSort = 'key';

// Filtra le traduzioni
function filterTranslations() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.translation-row');
    let visibleCount = 0;
    let translatedCount = 0;
    let missingCount = 0;
    let emptyCount = 0;

    rows.forEach(row => {
        const key = row.dataset.key.toLowerCase();
        const reference = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const translation = row.querySelector('textarea').value.toLowerCase();
        const status = row.dataset.status;

        const matchesSearch = key.includes(searchTerm) || reference.includes(searchTerm) || translation.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;

        if (matchesSearch && matchesStatus) {
            row.style.display = '';
            visibleCount++;

            if (status === 'translated') translatedCount++;
            else if (status === 'missing') missingCount++;
            else if (status === 'empty') emptyCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Aggiorna contatori
    document.getElementById('resultsCount').textContent = visibleCount;
    document.getElementById('translatedCount').textContent = translatedCount;
    document.getElementById('missingCount').textContent = missingCount;
    document.getElementById('emptyCount').textContent = emptyCount;
}

// Ordina le traduzioni
function sortTranslations() {
    const sortBy = document.getElementById('sortBy').value;
    const tbody = document.getElementById('translationsTable');
    const rows = Array.from(tbody.querySelectorAll('.translation-row'));

    rows.sort((a, b) => {
        let aVal, bVal;

        switch(sortBy) {
            case 'key':
                aVal = a.dataset.key;
                bVal = b.dataset.key;
                break;
            case 'reference':
                aVal = a.querySelector('td:nth-child(2)').textContent.trim();
                bVal = b.querySelector('td:nth-child(2)').textContent.trim();
                break;
            case 'status':
                aVal = a.dataset.status;
                bVal = b.dataset.status;
                break;
        }

        return aVal.localeCompare(bVal);
    });

    // Riordina le righe
    rows.forEach(row => tbody.appendChild(row));
}

// Reset filtri
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('sortBy').value = 'key';
    filterTranslations();
}

// Copia da italiano
function copyFromItalian() {
    Swal.fire({
        title: 'Copia da Italiano',
        text: '<?php echo e(__('admin.copy_confirm')); ?>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sì, copia',
        cancelButtonText: 'Annulla',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            const rows = document.querySelectorAll('.translation-row');
            rows.forEach(row => {
                const reference = row.querySelector('td:nth-child(2)').textContent.trim();
                const textarea = row.querySelector('textarea');
                textarea.value = reference;
                updateRowStatus(row);
            });
            updateCounters();

            Swal.fire({
                icon: 'success',
                title: 'Copiato!',
                text: 'Le traduzioni sono state copiate dall\'italiano',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

// Svuota tutte le traduzioni
function clearAllTranslations() {
    Swal.fire({
        title: 'Svuota Tutte le Traduzioni',
        text: '<?php echo e(__('admin.clear_confirm')); ?>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sì, svuota',
        cancelButtonText: 'Annulla',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            const textareas = document.querySelectorAll('.translation-input');
            textareas.forEach(textarea => {
                textarea.value = '';
                const row = textarea.closest('tr');
                updateRowStatus(row);
            });
            updateCounters();

            Swal.fire({
                icon: 'success',
                title: 'Svuotato!',
                text: 'Tutte le traduzioni sono state svuotate',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

// Salva traduzioni
function saveTranslations() {
    const formData = new FormData();
    const textareas = document.querySelectorAll('.translation-input');

    textareas.forEach(textarea => {
        formData.append('translations[' + textarea.dataset.key + ']', textarea.value);
    });

    fetch('<?php echo e(route("admin.translations.update", $language)); ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Salvataggio Completato',
                text: '<?php echo e(__('admin.save_success')); ?>',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Errore nel Salvataggio',
                text: '<?php echo e(__('admin.save_error')); ?>: ' + (data.message || 'Errore sconosciuto'),
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Errore nel Salvataggio',
            text: '<?php echo e(__('admin.save_error')); ?>',
            confirmButtonText: 'OK'
        });
    });
}

// Aggiorna stato della riga
function updateRowStatus(row) {
    const textarea = row.querySelector('textarea');
    const statusIcon = row.querySelector('td:last-child i');
    const value = textarea.value.trim();

    if (value) {
        row.dataset.status = 'translated';
        statusIcon.className = 'ph-duotone ph-check-circle text-success f-s-16';
        statusIcon.title = '<?php echo e(__('admin.translated_tooltip')); ?>';
    } else {
        const reference = row.querySelector('td:nth-child(2)').textContent.trim();
        if (reference) {
            row.dataset.status = 'missing';
            statusIcon.className = 'ph-duotone ph-warning-circle text-warning f-s-16';
            statusIcon.title = '<?php echo e(__('admin.missing_tooltip')); ?>';
        } else {
            row.dataset.status = 'empty';
            statusIcon.className = 'ph-duotone ph-x-circle text-danger f-s-16';
            statusIcon.title = '<?php echo e(__('admin.empty_tooltip')); ?>';
        }
    }
}

// Aggiorna contatori
function updateCounters() {
    const rows = document.querySelectorAll('.translation-row');
    let translatedCount = 0;
    let missingCount = 0;
    let emptyCount = 0;

    rows.forEach(row => {
        const status = row.dataset.status;
        if (status === 'translated') translatedCount++;
        else if (status === 'missing') missingCount++;
        else if (status === 'empty') emptyCount++;
    });

    document.getElementById('translatedCount').textContent = translatedCount;
    document.getElementById('missingCount').textContent = missingCount;
    document.getElementById('emptyCount').textContent = emptyCount;
}

// Event listeners per aggiornamento automatico dello stato
document.addEventListener('DOMContentLoaded', function() {
    const textareas = document.querySelectorAll('.translation-input');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            const row = this.closest('tr');
            updateRowStatus(row);
            updateCounters();
        });
    });

    // Inizializza contatori
    updateCounters();
    filterTranslations();
});

// Tasti di scelta rapida
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey || e.metaKey) {
        switch(e.key) {
            case 's':
                e.preventDefault();
                saveTranslations();
                break;
            case 'f':
                e.preventDefault();
                document.getElementById('searchInput').focus();
                break;
        }
    }
});

// Funzione per aggiungere una nuova chiave
function addNewKey() {
    Swal.fire({
        title: 'Aggiungi Nuova Chiave',
        input: 'text',
        inputLabel: 'Nome della chiave',
        inputPlaceholder: 'Inserisci il nome della chiave...',
        showCancelButton: true,
        confirmButtonText: 'Aggiungi',
        cancelButtonText: 'Annulla',
        inputValidator: (value) => {
            if (!value || value.trim() === '') {
                return 'Il nome della chiave è obbligatorio!';
            }
            return null;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const key = result.value.trim();

            // Verifica se la chiave esiste già
            const existingRow = document.querySelector(`tr[data-key="${key}"]`);
            if (existingRow) {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore',
                    text: 'Questa chiave esiste già!',
                    confirmButtonText: 'OK'
                });
                return;
            }

            addKeyToTable(key);
        }
    });
}

// Funzione per aggiungere la chiave alla tabella
function addKeyToTable(key) {
    // Crea una nuova riga
    const tbody = document.querySelector('tbody');
    const newRow = document.createElement('tr');
    newRow.className = 'translation-row';
    newRow.dataset.key = key;
    newRow.dataset.status = 'empty';

    newRow.innerHTML = `
        <td>
            <code class="text-primary">${key}</code>
        </td>
        <td>
            <span class="text-muted">-</span>
        </td>
        <td>
            <textarea class="form-control form-control-sm translation-input"
                      name="translations[${key}]"
                      rows="1"
                      placeholder="Inserisci traduzione..."></textarea>
        </td>
        <td class="text-center">
            <span class="status-icon" title="<?php echo e(__('admin.empty_tooltip')); ?>">✗</span>
        </td>
    `;

    // Aggiungi la riga alla tabella
    tbody.appendChild(newRow);

    // Aggiungi event listener per la nuova textarea
    const textarea = newRow.querySelector('.translation-input');
    textarea.addEventListener('input', function() {
        const row = this.closest('tr');
        updateRowStatus(row);
        updateCounters();
    });

    // Aggiorna contatori
    updateCounters();

    // Evidenzia la nuova riga
    newRow.style.backgroundColor = '#fff3cd';
    setTimeout(() => {
        newRow.style.backgroundColor = '';
    }, 2000);

    // Focus sulla textarea
    textarea.focus();

    // Mostra messaggio di successo
    Swal.fire({
        icon: 'success',
        title: 'Chiave Aggiunta',
        text: `La chiave "${key}" è stata aggiunta con successo!`,
        timer: 2000,
        showConfirmButton: false
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/translations/show.blade.php ENDPATH**/ ?>