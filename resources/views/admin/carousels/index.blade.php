@extends('layout.master')

@section('title', 'Gestione Carosello')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">

        <!-- Header -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Gestione Carosello</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">Carosello</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-images f-s-16 me-2"></i>
                        Slide del Carosello
                    </h5>
                    <a href="{{ route('admin.carousels.create') }}" class="btn btn-success hover-effect">
                        <i class="ph-duotone ph-plus f-s-16 me-2"></i>
                        Nuova Slide
                    </a>
                </div>
            </div>
        </div>

        <!-- Carousel List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if($carousels->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Ordine</th>
                                            <th>Immagine</th>
                                            <th>Titolo</th>
                                            <th>Stato</th>
                                            <th>Date</th>
                                            <th>Azioni</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sortableCarousel">
                                        @foreach($carousels as $carousel)
                                        <tr data-id="{{ $carousel->id }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="ph-duotone ph-dots-six-vertical f-s-16 text-muted me-2 cursor-move"></i>
                                                    <span class="badge bg-secondary">{{ $carousel->order }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $carousel->imageUrl }}" alt="{{ $carousel->title }}"
                                                         class="rounded me-3" style="width: 60px; height: 40px; object-fit: cover;">
                                                    @if($carousel->video_path)
                                                        <i class="ph-duotone ph-video-camera f-s-14 text-primary"></i>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-1 f-s-14 f-w-600">{{ $carousel->title }}</h6>
                                                    @if($carousel->description)
                                                        <small class="text-muted">{{ Str::limit($carousel->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($carousel->is_active)
                                                    <span class="badge bg-success">
                                                        <i class="ph-duotone ph-check-circle f-s-12 me-1"></i>Attivo
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="ph-duotone ph-x-circle f-s-12 me-1"></i>Inattivo
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="f-s-12">
                                                    @if($carousel->start_date)
                                                        <div>Da: {{ $carousel->start_date->format('d/m/Y') }}</div>
                                                    @endif
                                                    @if($carousel->end_date)
                                                        <div>A: {{ $carousel->end_date->format('d/m/Y') }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.carousels.edit', $carousel) }}"
                                                       class="btn btn-outline-primary hover-effect" title="Modifica">
                                                        <i class="ph-duotone ph-pencil f-s-14"></i>
                                                    </a>
                                                    <a href="{{ route('admin.carousels.show', $carousel) }}"
                                                       class="btn btn-outline-info hover-effect" title="Visualizza">
                                                        <i class="ph-duotone ph-eye f-s-14"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger hover-effect"
                                                            onclick="deleteCarousel({{ $carousel->id }})" title="Elimina">
                                                        <i class="ph-duotone ph-trash f-s-14"></i>
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
                                <i class="ph-duotone ph-images f-s-64 text-muted mb-3"></i>
                                <h5 class="mb-3">Nessuna slide del carosello</h5>
                                <p class="text-muted mb-4">Crea la tua prima slide per il carosello della home page</p>
                                <a href="{{ route('admin.carousels.create') }}" class="btn btn-primary hover-effect">
                                    <i class="ph-duotone ph-plus f-s-16 me-2"></i>
                                    Crea Prima Slide
                                </a>
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
                <h5 class="modal-title">Conferma Eliminazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Sei sicuro di voler eliminare questa slide del carosello?</p>
                <p class="text-muted small">Questa azione non può essere annullata.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <form id="deleteCarouselForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Elimina</button>
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
    handle: '.cursor-move',
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
