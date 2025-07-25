@extends('layout.master')

@section('title', 'Dettagli Slide Carosello')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">

        <!-- Header -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Dettagli Slide Carosello</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('admin.carousels.index') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-images f-s-16"></i> Carosello
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">{{ $carousel->title }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-eye f-s-16 me-2"></i>
                        {{ $carousel->title }}
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('admin.carousels.edit', $carousel) }}" class="btn btn-primary hover-effect">
                            <i class="ph-duotone ph-pencil f-s-16 me-2"></i>
                            Modifica
                        </a>
                        <a href="{{ route('admin.carousels.index') }}" class="btn btn-secondary hover-effect">
                            <i class="ph-duotone ph-arrow-left f-s-16 me-2"></i>
                            Torna alla Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Media Preview -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-image f-s-16 me-2"></i>
                            Anteprima Media
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($carousel->video_path)
                            <div class="text-center">
                                <video class="img-fluid rounded" controls>
                                    <source src="{{ $carousel->videoUrl }}" type="video/mp4">
                                    Il tuo browser non supporta la riproduzione video.
                                </video>
                                <p class="text-muted mt-2">
                                    <i class="ph-duotone ph-video-camera f-s-14 me-1"></i>
                                    Video attivo
                                </p>
                            </div>
                        @else
                            <div class="text-center">
                                <img src="{{ $carousel->imageUrl }}" alt="{{ $carousel->title }}" class="img-fluid rounded">
                                <p class="text-muted mt-2">
                                    <i class="ph-duotone ph-image f-s-14 me-1"></i>
                                    Immagine
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Carousel Preview -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-presentation f-s-16 me-2"></i>
                            Anteprima Carosello
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    @if($carousel->video_path)
                                        <video class="d-block w-100" autoplay muted loop>
                                            <source src="{{ $carousel->videoUrl }}" type="video/mp4">
                                        </video>
                                    @else
                                        <img src="{{ $carousel->imageUrl }}" class="d-block w-100" alt="{{ $carousel->title }}">
                                    @endif
                                    <div class="bg-light-success carousel-caption d-none d-md-block ">
                                        <h5 class="f-w-600">{{ $carousel->title }}</h5>
                                        @if($carousel->description)
                                            <p class="mb-3">{{ $carousel->description }}</p>
                                        @endif
                                        @if($carousel->link_url && $carousel->link_text)
                                            <a href="{{ $carousel->link_url }}" class="btn btn-primary hover-effect" target="_blank">
                                                {{ $carousel->link_text }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="col-lg-4">
                <!-- Basic Info -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-info-circle f-s-16 me-2"></i>
                            Informazioni
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Titolo:</strong>
                            <p class="mb-0">{{ $carousel->title }}</p>
                        </div>

                        @if($carousel->description)
                        <div class="mb-3">
                            <strong>Descrizione:</strong>
                            <p class="mb-0">{{ $carousel->description }}</p>
                        </div>
                        @endif

                        @if($carousel->link_url)
                        <div class="mb-3">
                            <strong>Link:</strong>
                            <p class="mb-0">
                                <a href="{{ $carousel->link_url }}" target="_blank">{{ $carousel->link_url }}</a>
                            </p>
                        </div>
                        @endif

                        @if($carousel->link_text)
                        <div class="mb-3">
                            <strong>Testo Link:</strong>
                            <p class="mb-0">{{ $carousel->link_text }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Status -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-gear f-s-16 me-2"></i>
                            Stato e Impostazioni
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Stato:</strong>
                            @if($carousel->is_active)
                                <span class="badge bg-success ms-2">
                                    <i class="ph-duotone ph-check-circle f-s-12 me-1"></i>Attivo
                                </span>
                            @else
                                <span class="badge bg-danger ms-2">
                                    <i class="ph-duotone ph-x-circle f-s-12 me-1"></i>Inattivo
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <strong>Stato Attuale:</strong>
                            @if($carousel->isCurrentlyActive())
                                <span class="badge bg-success ms-2">
                                    <i class="ph-duotone ph-check-circle f-s-12 me-1"></i>Visibile
                                </span>
                            @else
                                <span class="badge bg-warning ms-2">
                                    <i class="ph-duotone ph-clock f-s-12 me-1"></i>Non Visibile
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <strong>Ordine:</strong>
                            <span class="badge bg-secondary ms-2">{{ $carousel->order }}</span>
                        </div>

                        @if($carousel->start_date)
                        <div class="mb-3">
                            <strong>Data Inizio:</strong>
                            <p class="mb-0">{{ $carousel->start_date->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif

                        @if($carousel->end_date)
                        <div class="mb-3">
                            <strong>Data Fine:</strong>
                            <p class="mb-0">{{ $carousel->end_date->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-clock f-s-16 me-2"></i>
                            Timestamps
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Creata:</strong>
                            <p class="mb-0">{{ $carousel->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="mb-0">
                            <strong>Aggiornata:</strong>
                            <p class="mb-0">{{ $carousel->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
