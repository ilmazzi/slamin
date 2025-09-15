<!-- Modale per Confermare Marcatura come Processato -->
<div class="modal fade" id="markProcessedModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph-duotone ph-check me-2"></i>
                    {{ __('admin.mark_as_processed') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('admin.mark_as_processed_confirm') }}</p>
                <div class="alert alert-info">
                    <i class="ph-duotone ph-info me-2"></i>
                    {{ __('admin.mark_as_processed_info') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('admin.cancel') }}
                </button>
                <button type="button" class="btn btn-success" id="confirmMarkProcessed">
                    <i class="ph-duotone ph-check me-1"></i>
                    {{ __('admin.mark_as_processed') }}
                </button>
            </div>
        </div>
    </div>
</div>

