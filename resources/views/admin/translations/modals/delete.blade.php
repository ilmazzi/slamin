<!-- Modal Elimina Lingua -->
<div class="modal fade" id="deleteLanguageModal" tabindex="-1" aria-labelledby="deleteLanguageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLanguageModalLabel">
                    <i class="ph-duotone ph-warning text-danger me-2"></i>
                    {{ __('admin.confirm_delete') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="ph-duotone ph-warning me-2"></i>
                    {{ __('admin.delete_language_warning') }}
                </div>
                <p>{{ __('admin.delete_language_confirm_text') }}</p>
                <ul class="list-unstyled">
                    <li><i class="ph-duotone ph-check text-danger me-2"></i>{{ __('admin.delete_language_consequence1') }}</li>
                    <li><i class="ph-duotone ph-check text-danger me-2"></i>{{ __('admin.delete_language_consequence2') }}</li>
                    <li><i class="ph-duotone ph-check text-danger me-2"></i>{{ __('admin.delete_language_consequence3') }}</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    {{ __('admin.cancel') }}
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteLanguage">
                    <i class="ph-duotone ph-trash me-1"></i>
                    {{ __('admin.delete_language') }}
                </button>
            </div>
        </div>
    </div>
</div>
