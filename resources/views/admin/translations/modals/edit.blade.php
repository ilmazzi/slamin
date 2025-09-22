<!-- Modale per Modificare Traduzione -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph-duotone ph-pencil me-2"></i>
                    {{ __('admin.edit_translation') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editTranslationId" name="id">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="editGroupName" class="form-label">
                                {{ __('admin.group_name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <select id="editGroupName" name="group_name" class="form-select" required>
                                <option value="">{{ __('admin.select_group') }}</option>
                                <option value="admin">admin</option>
                                <option value="common">common</option>
                                <option value="auth">auth</option>
                                <option value="dashboard">dashboard</option>
                                <option value="events">events</option>
                                <option value="profile">profile</option>
                                <option value="videos">videos</option>
                                <option value="chat">chat</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="editLocale" class="form-label">
                                {{ __('admin.locale') }}
                                <span class="text-danger">*</span>
                            </label>
                            <select id="editLocale" name="locale" class="form-select" required>
                                <option value="">{{ __('admin.select_locale') }}</option>
                                <option value="it">IT - Italiano</option>
                                <option value="en">EN - English</option>
                                <option value="es">ES - Español</option>
                                <option value="fr">FR - Français</option>
                                <option value="de">DE - Deutsch</option>
                                <option value="pt">PT - Português</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="editKeyName" class="form-label">
                                {{ __('admin.key_name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="editKeyName" name="key_name" class="form-control"
                                   placeholder="{{ __('admin.key_name_placeholder') }}" required>
                        </div>

                        <div class="col-12">
                            <label for="editValue" class="form-label">
                                {{ __('admin.translation_value') }}
                                <span class="text-danger">*</span>
                            </label>
                            <textarea id="editValue" name="value" class="form-control"
                                      rows="4" placeholder="{{ __('admin.translation_value_placeholder') }}" required></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('admin.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="submitEdit()">
                    <i class="ph-duotone ph-check me-1"></i>
                    {{ __('admin.update') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function submitEdit() {
    const formData = new FormData(document.getElementById('editForm'));
    const translationId = document.getElementById('editTranslationId').value;

    fetch(`/admin/translations/${translationId}`, {
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
document.getElementById('editModal').addEventListener('show.bs.modal', function() {
    const translationId = document.getElementById('editTranslationId').value;

    // Recupera i dati della traduzione (questo dovrebbe essere fatto via AJAX)
    // Per ora lasciamo vuoto, sarà implementato quando necessario
});
</script>



