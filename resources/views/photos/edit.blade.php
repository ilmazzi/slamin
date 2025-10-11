@extends('layout.master')

@section('title', __('photos.edit_photo'))

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="page-title-box">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="page-title mb-0">
                <i class="ph ph-pencil-simple me-2"></i>{{ __('photos.edit_photo') }}
            </h4>
            <a href="{{ route('photos.show', $photo) }}" class="btn btn-light">
                <i class="ph ph-arrow-left me-1"></i>{{ __('common.back') }}
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('photos.update', $photo) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Current Photo -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="ph ph-image me-2"></i>{{ __('photos.current_photo') }}
                        </h5>
                        <div class="text-center">
                            <img src="{{ $photo->image_url }}" 
                                 alt="{{ $photo->alt_text }}" 
                                 class="img-fluid rounded"
                                 style="max-height: 400px; object-fit: contain;">
                        </div>
                        <small class="text-secondary d-block mt-2">
                            <i class="ph ph-info me-1"></i>{{ __('photos.edit_note') }}
                        </small>
                    </div>
                </div>

                <!-- Photo Details -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="ph ph-info me-2"></i>{{ __('photos.photo_details') }}
                        </h5>

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                {{ __('photos.title') }} <span class="text-secondary">({{ __('common.optional') }})</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title"
                                   value="{{ old('title', $photo->title) }}"
                                   maxlength="255"
                                   placeholder="{{ __('photos.title_placeholder') }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                {{ __('photos.description') }} <span class="text-secondary">({{ __('common.optional') }})</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description"
                                      rows="3"
                                      maxlength="1000"
                                      placeholder="{{ __('photos.description_placeholder') }}">{{ old('description', $photo->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alt Text -->
                        <div class="mb-3">
                            <label for="alt_text" class="form-label">
                                {{ __('photos.alt_text') }} <span class="text-secondary">({{ __('common.optional') }})</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('alt_text') is-invalid @enderror" 
                                   id="alt_text" 
                                   name="alt_text"
                                   value="{{ old('alt_text', $photo->alt_text) }}"
                                   maxlength="255"
                                   placeholder="{{ __('photos.alt_text_placeholder') }}">
                            @error('alt_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-secondary">{{ __('photos.alt_text_help') }}</small>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="ph ph-check me-2"></i>{{ __('photos.save_changes') }}
                            </button>
                            <button type="button" 
                                    class="btn btn-danger" 
                                    onclick="deletePhoto()">
                                <i class="ph ph-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deletePhoto() {
    Swal.fire({
        title: 'Sei sicuro?',
        text: "{{ __('photos.confirm_delete') }}",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sì, elimina!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("photos.destroy", $photo) }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Eliminata!', data.message, 'success').then(() => {
                        window.location.href = '{{ route("profile.photos") }}';
                    });
                } else {
                    Swal.fire('Errore!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Errore eliminazione foto:', error);
                Swal.fire('Errore!', '{{ __("photos.delete_error") }}', 'error');
            });
        }
    });
}
</script>
@endpush

