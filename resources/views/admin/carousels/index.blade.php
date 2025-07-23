@extends('layout.master')

@section('title', __('carousel.management'))

@section('main-content')
<div class="page-content">
    <div class="container-fluid">

        <!-- Header -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">{{ __('carousel.management') }}</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> {{ __('carousel.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">{{ __('carousel.breadcrumb') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Carousel List -->
        <div class="row">
            <div class="col-12">
                <div class="card hover-effect">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">
                                    <i class="ph-duotone ph-images f-s-16 me-2"></i>
                                    {{ __('carousel.slides') }}
                                </h5>
                                <p class="mt-1 f-m-light mb-0">{{ __('carousel.slides_description') }}</p>
                            </div>
                            <a href="{{ route('admin.carousels.create') }}" class="btn btn-success hover-effect">
                                <i class="ph-duotone ph-plus f-s-16 me-2"></i>
                                {{ __('carousel.new_slide') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($carousels->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bottom-border table-box-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 60px;">
                                                <i class="ti ti-arrows-move fs-4 text-secondary"></i>
                                            </th>
                                            <th style="width: 100px;">{{ __('carousel.image_header') }}</th>
                                            <th>{{ __('carousel.title_header') }}</th>
                                            <th style="width: 120px;">{{ __('carousel.status_header') }}</th>
                                            <th style="width: 140px;">{{ __('carousel.dates_header') }}</th>
                                            <th class="text-center" style="width: 150px;">{{ __('carousel.actions_header') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sortableCarousel">
                                        @foreach($carousels as $carousel)
                                        <tr data-id="{{ $carousel->id }}">
                                            <td class="text-center">
                                                <span class="badge bg-secondary f-s-12">{{ $carousel->order }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="position-relative">
                                                        <img src="{{ $carousel->imageUrl }}" alt="{{ $carousel->display_title }}"
                                                             class="rounded" style="width: 50px; height: 35px; object-fit: cover;">
                                                        @if($carousel->video_path)
                                                            <div class="position-absolute top-0 end-0">
                                                                <i class="ph-duotone ph-video-camera f-s-10 text-primary bg-white rounded-circle p-1"></i>
                                                            </div>
                                                        @endif
                                                        @if($carousel->isContentReference())
                                                            <div class="position-absolute bottom-0 start-0">
                                                                <span class="badge bg-info f-s-8">{{ ucfirst($carousel->content_type) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-1 f-s-14 f-w-600">{{ $carousel->display_title }}</h6>
                                                    @if($carousel->display_description)
                                                        <p class="text-muted f-s-12 mb-0">{{ Str::limit($carousel->display_description, 40) }}</p>
                                                    @endif
                                                    @if($carousel->isContentReference())
                                                        <small class="text-info f-s-10">
                                                            <i class="ph-duotone ph-link f-s-10 me-1"></i>
                                                            Contenuto referenziato
                                                        </small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($carousel->is_active)
                                                    <span class="badge bg-success f-s-11">
                                                        <i class="ph-duotone ph-check-circle f-s-11 me-1"></i>{{ __('carousel.active') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger f-s-11">
                                                        <i class="ph-duotone ph-x-circle f-s-11 me-1"></i>{{ __('carousel.inactive') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="f-s-11">
                                                    @if($carousel->start_date)
                                                        <div class="text-muted">{{ __('carousel.from') }}: {{ $carousel->start_date->format('d/m/Y') }}</div>
                                                    @endif
                                                    @if($carousel->end_date)
                                                        <div class="text-muted">{{ __('carousel.to') }}: {{ $carousel->end_date->format('d/m/Y') }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="app-btn-list">
                                                    <a href="{{ route('admin.carousels.edit', $carousel) }}"
                                                       class="btn btn-primary icon-btn b-r-4 hover-effect" title="{{ __('carousel.edit') }}">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <a href="{{ route('admin.carousels.show', $carousel) }}"
                                                       class="btn btn-secondary icon-btn b-r-4 hover-effect" title="{{ __('carousel.view') }}">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger icon-btn b-r-4 hover-effect"
                                                            onclick="deleteCarousel({{ $carousel->id }})" title="{{ __('carousel.delete') }}">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center pa-40">
                                <div class="empty-state">
                                    <i class="ph-duotone ph-images f-s-64 text-muted mb-3"></i>
                                    <h5 class="mb-3">{{ __('carousel.no_slides') }}</h5>
                                    <p class="text-muted mb-4">{{ __('carousel.create_first_slide') }}</p>
                                    <a href="{{ route('admin.carousels.create') }}" class="btn btn-primary hover-effect">
                                        <i class="ph-duotone ph-plus f-s-16 me-2"></i>
                                        {{ __('carousel.create_first_slide_button') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCarouselModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('carousel.delete_confirmation_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('carousel.delete_confirmation_message') }}</p>
                <p class="text-muted small">{{ __('carousel.delete_confirmation_warning') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('carousel.cancel') }}</button>
                <form id="deleteCarouselForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('carousel.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
// Sortable functionality
new Sortable(document.getElementById('sortableCarousel'), {
    handle: '.ti-arrows-move',
    animation: 150,
    onEnd: function(evt) {
        const items = Array.from(evt.to.children).map((tr, index) => ({
            id: tr.dataset.id,
            order: index
        }));

        fetch('{{ route("admin.carousels.order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ items: items })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update order numbers
                items.forEach((item, index) => {
                    const tr = document.querySelector(`tr[data-id="${item.id}"]`);
                    const badge = tr.querySelector('.badge');
                    badge.textContent = index;
                });
            }
        })
        .catch(error => {
            console.error('Error updating order:', error);
        });
    }
});

function deleteCarousel(carouselId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteCarouselModal'));
    const form = document.getElementById('deleteCarouselForm');
    form.action = `/admin/carousels/${carouselId}`;
    modal.show();
}
</script>
@endpush
