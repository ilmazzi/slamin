<!-- Modale per Visualizzare Traduzione -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph-duotone ph-eye me-2"></i>
                    {{ __('admin.view_translation') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('admin.group_name') }}</label>
                        <div class="form-control-plaintext">
                            <code id="viewGroupName" class="text-primary"></code>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('admin.locale') }}</label>
                        <div class="form-control-plaintext">
                            <span id="viewLocale" class="badge bg-primary"></span>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">{{ __('admin.key_name') }}</label>
                        <div class="form-control-plaintext">
                            <code id="viewKeyName" class="text-secondary"></code>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">{{ __('admin.translation_value') }}</label>
                        <div class="form-control-plaintext">
                            <div id="viewValue" class="p-3 bg-light rounded border" style="min-height: 100px; white-space: pre-wrap;"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('admin.created_at') }}</label>
                        <div class="form-control-plaintext">
                            <span id="viewCreatedAt" class="text-muted"></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('admin.updated_at') }}</label>
                        <div class="form-control-plaintext">
                            <span id="viewUpdatedAt" class="text-muted"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('admin.close') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="editFromView()">
                    <i class="ph-duotone ph-pencil me-1"></i>
                    {{ __('admin.edit') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function editFromView() {
    // Chiudi il modale di visualizzazione
    const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewModal'));
    viewModal.hide();

    // Apri il modale di modifica
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();

    // Copia i dati dal modale di visualizzazione a quello di modifica
    document.getElementById('editTranslationId').value = document.getElementById('viewTranslationId').value;
    document.getElementById('editGroupName').value = document.getElementById('viewGroupName').textContent;
    document.getElementById('editLocale').value = document.getElementById('viewLocale').textContent.toLowerCase();
    document.getElementById('editKeyName').value = document.getElementById('viewKeyName').textContent;
    document.getElementById('editValue').value = document.getElementById('viewValue').textContent;
}

// Carica i dati della traduzione quando si apre il modale
document.getElementById('viewModal').addEventListener('show.bs.modal', function() {
    const translationId = document.getElementById('viewTranslationId').value;

    // Recupera i dati della traduzione (questo dovrebbe essere fatto via AJAX)
    // Per ora lasciamo vuoto, sarà implementato quando necessario
});
</script>

