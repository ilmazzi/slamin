@extends('layout.master')

@section('title', 'La Mia Wishlist - Slam in')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-heart f-s-20 me-2 text-danger"></i>
                        La Mia Wishlist
                    </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Wishlist</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wishlist Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="ph-duotone ph-calendar f-s-16 me-2"></i>
                                Eventi nella Wishlist
                            </h5>
                            <span class="badge bg-primary f-s-12">
                                {{ $wishlistedEvents->total() }} eventi
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($wishlistedEvents->count() > 0)
                            <div class="row">
                                @foreach($wishlistedEvents as $event)
                                <div class="col-lg-6 col-xl-4 mb-4">
                                    <div class="card hover-effect h-100">
                                        @if($event->image_url)
                                            <img src="{{ $event->image_url }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                                        @else
                                            @php
                                                $fallbackImages = [
                                                    'assets/images/background/default-event-1.webp',
                                                    'assets/images/background/default-event-2.webp',
                                                    'assets/images/background/default-event-3.webp',
                                                    'assets/images/background/default-event-4.webp'
                                                ];
                                                $randomImage = $fallbackImages[array_rand($fallbackImages)];
                                            @endphp
                                            <img src="{{ asset($randomImage) }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                                        @endif

                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title f-w-600 mb-0">{{ $event->title }}</h5>
                                                <button class="btn btn-sm btn-outline-danger remove-wishlist" data-event-id="{{ $event->id }}" title="Rimuovi dalla wishlist">
                                                    <i class="ph-duotone ph-heart-fill"></i>
                                                </button>
                                            </div>

                                            <p class="card-text text-muted f-s-14 mb-2">
                                                <i class="ph-duotone ph-map-pin f-s-12 me-1"></i>
                                                {{ $event->venue_name }}
                                            </p>

                                            @if($event->description)
                                                <p class="card-text flex-grow-1">{{ Str::limit($event->description, 100) }}</p>
                                            @endif

                                            <div class="mt-auto">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge {{ $event->category_color_class }}">
                                                        {{ $event->getCategoryDisplayName() }}
                                                    </span>
                                                    <small class="text-body-secondary">
                                                        <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                                        {{ $event->start_datetime->format('d/m/Y H:i') }}
                                                    </small>
                                                </div>

                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-primary flex-fill">
                                                        <i class="ph-duotone ph-info f-s-14 me-1"></i>Dettagli
                                                    </a>
                                                    @if($event->acceptsRequests())
                                                        <a href="{{ route('events.apply', $event) }}" class="btn btn-sm btn-success">
                                                            <i class="ph-duotone ph-user-plus f-s-14"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            @if($wishlistedEvents->hasPages())
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $wishlistedEvents->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="ph-duotone ph-heart f-s-64 text-muted"></i>
                                </div>
                                <h5 class="text-muted mb-3">La tua wishlist è vuota</h5>
                                <p class="text-muted mb-4">
                                    Non hai ancora aggiunto nessun evento alla tua wishlist.<br>
                                    Esplora gli eventi disponibili e aggiungi quelli che ti interessano!
                                </p>
                                <a href="{{ route('events.index') }}" class="btn btn-primary">
                                    <i class="ph-duotone ph-calendar f-s-16 me-2"></i>
                                    Esplora Eventi
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Rimuovi dalla wishlist
    $('.remove-wishlist').on('click', function() {
        const eventId = $(this).data('event-id');
        const card = $(this).closest('.col-lg-6');

        $.ajax({
            url: `/wishlist/${eventId}/remove`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Animazione di rimozione
                    card.fadeOut(300, function() {
                        $(this).remove();

                        // Aggiorna il contatore
                        const currentCount = parseInt($('.badge.bg-primary').text().match(/\d+/)[0]);
                        $('.badge.bg-primary').text(`${currentCount - 1} eventi`);

                        // Se non ci sono più eventi, mostra il messaggio vuoto
                        if ($('.col-lg-6').length === 0) {
                            location.reload();
                        }
                    });

                    // Notifica di successo
                    Swal.fire({
                        icon: 'success',
                        title: 'Rimosso dalla wishlist',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore',
                    text: 'Impossibile rimuovere l\'evento dalla wishlist'
                });
            }
        });
    });
});
</script>
@endpush
