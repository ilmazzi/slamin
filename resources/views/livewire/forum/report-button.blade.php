<div>
    {{-- Report Button --}}
    <button wire:click="openModal" class="btn btn-sm btn-light-warning">
        <i class="ph ph-flag me-1"></i>{{ __('forum.report') }}
    </button>

    @script
    <script>
        Livewire.on('swal:success', (data) => {
            Swal.fire({
                icon: 'success',
                title: data[0].title || 'Successo!',
                text: data[0].text || '',
                confirmButtonText: 'OK',
                confirmButtonClass: 'btn btn-primary'
            });
        });
    </script>
    @endscript

    {{-- Report Modal --}}
    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-light-warning">
                        <h5 class="modal-title text-warning">
                            <i class="ph ph-flag me-2"></i>{{ __('forum.report_content') }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="showModal = false"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="submitReport">
                            <div class="mb-3">
                                <label class="form-label">{{ __('forum.report_reason') }} <span class="text-danger">*</span></label>
                                <select wire:model="reason" class="form-select @error('reason') is-invalid @enderror">
                                    <option value="">{{ __('forum.select_reason') }}</option>
                                    @foreach($reasons as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('forum.report_description') }} <span class="text-danger">*</span></label>
                                <textarea wire:model="description" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          rows="4" 
                                          placeholder="{{ __('forum.report_description_hint') }}"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-border-info" role="alert">
                                <i class="ph ph-info me-2"></i>
                                {{ __('forum.report_info') }}
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="showModal = false" class="btn btn-light-secondary">
                            {{ __('forum.cancel') }}
                        </button>
                        <button wire:click="submitReport" class="btn btn-warning">
                            <i class="ph ph-flag me-2"></i>{{ __('forum.submit_report') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
