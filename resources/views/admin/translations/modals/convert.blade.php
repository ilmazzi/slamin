<!-- Modale per Convertire in Traduzione -->
<div class="modal fade" id="convertModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph-duotone ph-arrow-right me-2"></i>
                    {{ __('admin_general.convert_to_translation') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="convertForm">
                    @csrf
                    <input type="hidden" id="convertItemId" name="id">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="convertGroup" class="form-label">
                                {{ __('admin_general.group_name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <select id="convertGroup" name="group" class="form-select" required>
                                <option value="">{{ __('admin_general.select_group') }}</option>
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
                            <label for="convertLocale" class="form-label">
                                {{ __('admin_general.locale') }}
                                <span class="text-danger">*</span>
                            </label>
                            <select id="convertLocale" name="locale" class="form-select" required>
                                <option value="">{{ __('admin_general.select_locale') }}</option>
                                <option value="it">IT - Italiano</option>
                                <option value="en">EN - English</option>
                                <option value="es">ES - Español</option>
                                <option value="fr">FR - Français</option>
                                <option value="de">DE - Deutsch</option>
                                <option value="pt">PT - Português</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="convertKey" class="form-label">
                                {{ __('admin_general.key_name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="convertKey" name="key" class="form-control"
                                   placeholder="{{ __('admin_general.key_name_placeholder') }}" required>
                            <div class="form-text">{{ __('admin_general.key_name_help') }}</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('admin_general.original_text') }}</label>
                            <div class="alert alert-light" id="originalTextPreview">
                                <!-- Il testo originale verrà inserito qui via JavaScript -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('admin_general.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="submitConvert()">
                    <i class="ph-duotone ph-check me-1"></i>
                    {{ __('admin_general.convert') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function submitConvert() {
    const formData = new FormData(document.getElementById('convertForm'));

    fetch('{{ route("admin.translations.convert-from-queue") }}', {
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
                title: '{{ __('admin_general.success') }}',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: '{{ __('admin_general.error') }}',
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: '{{ __('admin_general.error') }}',
            text: '{{ __('admin_general.unknown_error') }}'
        });
    });
}

// Auto-genera chiave quando si apre il modale
document.getElementById('convertModal').addEventListener('show.bs.modal', function() {
    const itemId = document.getElementById('convertItemId').value;

    // Recupera i dati dell'elemento (questo dovrebbe essere fatto via AJAX)
    // Per ora generiamo una chiave automatica
    const originalText = document.querySelector(`tr[data-item-id="${itemId}"] .original-text`).textContent;
    const key = originalText.toLowerCase()
        .replace(/[^a-z0-9\s]/g, '')
        .replace(/\s+/g, '_')
        .substring(0, 50);

    document.getElementById('convertKey').value = key;
    document.getElementById('originalTextPreview').textContent = originalText;
});
</script>



