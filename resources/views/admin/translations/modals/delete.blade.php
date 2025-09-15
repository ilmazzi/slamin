<!-- Modale per Eliminare Traduzione -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph-duotone ph-trash me-2"></i>
                    {{ __('admin.delete_translation') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('admin.delete_translation_confirm') }}</p>

                <div class="alert alert-warning">
                    <i class="ph-duotone ph-warning-circle me-2"></i>
                    {{ __('admin.delete_translation_warning') }}
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('admin.group_name') }}</label>
                        <div class="form-control-plaintext">
                            <code id="deleteGroupName" class="text-primary"></code>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('admin.locale') }}</label>
                        <div class="form-control-plaintext">
                            <span id="deleteLocale" class="badge bg-primary"></span>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">{{ __('admin.key_name') }}</label>
                        <div class="form-control-plaintext">
                            <code id="deleteKeyName" class="text-secondary"></code>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">{{ __('admin.translation_value') }}</label>
                        <div class="form-control-plaintext">
                            <div id="deleteValue" class="p-2 bg-light rounded border" style="max-height: 100px; overflow-y: auto;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('admin.cancel') }}
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="ph-duotone ph-trash me-1"></i>
                    {{ __('admin.delete') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    const translationId = document.getElementById('deleteTranslationId').value;

    fetch(`/admin/translations/${translationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '{{ __('admin.success') }}',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: '{{ __('admin.error') }}',
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: '{{ __('admin.error') }}',
            text: '{{ __('admin.unknown_error') }}'
        });
    });
}

// Carica i dati della traduzione quando si apre il modale
document.getElementById('deleteModal').addEventListener('show.bs.modal', function() {
    const translationId = document.getElementById('deleteTranslationId').value;

    // Recupera i dati della traduzione (questo dovrebbe essere fatto via AJAX)
    // Per ora lasciamo vuoto, sarà implementato quando necessario
});
</script>

