@extends('layout.master')

@section('title', 'Test Upload Video')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">🧪 Test Upload Video</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> {{ __('dashboard.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">Test Upload</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Test Info -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h6><i class="ph-duotone ph-info f-s-16 me-2"></i>Modalità Test</h6>
                    <p class="mb-0">
                        Questa è una versione di test che simula l'upload su PeerTube.
                        I video non vengono realmente caricati su video.slamin.it.
                    </p>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card card-light-primary">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-upload f-s-16 me-2"></i>
                            Test Upload Video
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('test.upload-video') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- File Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-medium">File Video (Max 10MB per test)</label>
                                <input type="file" name="video" class="form-control"
                                       accept=".mp4,.avi,.mov,.mkv,.webm,.flv" required>
                                <small class="text-muted">Formati supportati: MP4, AVI, MOV, MKV, WebM, FLV</small>
                            </div>

                            <!-- Video Details -->
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-medium">Titolo Video</label>
                                    <input type="text" class="form-control" name="title"
                                           placeholder="Inserisci il titolo del video" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-medium">Descrizione</label>
                                    <textarea class="form-control" name="description" rows="4"
                                              placeholder="Descrivi il contenuto del video..."></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label fw-medium">Tag</label>
                                    <input type="text" class="form-control" name="tags"
                                           placeholder="poesia, slam, performance, arte">
                                    <small class="text-muted">Separati da virgole</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-medium">Privacy</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_public"
                                               id="is_public" value="1" checked>
                                        <label class="form-check-label" for="is_public">
                                            Video Pubblico
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ph-duotone ph-upload f-s-16 me-2"></i>
                                    Test Upload Video
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Test Info -->
            <div class="col-lg-4">
                <div class="card card-light-info mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-info f-s-16 me-2"></i>
                            Informazioni Test
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="ph-duotone ph-check-circle f-s-16 text-success me-2"></i>
                                Simulazione PeerTube
                            </li>
                            <li class="mb-2">
                                <i class="ph-duotone ph-check-circle f-s-16 text-success me-2"></i>
                                Salvataggio nel database
                            </li>
                            <li class="mb-2">
                                <i class="ph-duotone ph-check-circle f-s-16 text-success me-2"></i>
                                Controllo limiti utente
                            </li>
                            <li class="mb-2">
                                <i class="ph-duotone ph-check-circle f-s-16 text-success me-2"></i>
                                Validazione file
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Current Status -->
                <div class="card card-light-warning">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-user f-s-16 me-2"></i>
                            Stato Utente
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="h4 text-primary mb-1">{{ $user->current_video_count }}</div>
                                <small class="text-muted">Video Attuali</small>
                            </div>
                            <div class="col-6">
                                <div class="h4 text-success mb-1">{{ $user->remaining_video_uploads }}</div>
                                <small class="text-muted">Video Rimanenti</small>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 8px;">
                            @php
                                $percentage = $user->current_video_limit > 0 ?
                                    ($user->current_video_count / $user->current_video_limit) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-{{ $percentage > 80 ? 'danger' : ($percentage > 60 ? 'warning' : 'success') }}"
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Links -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('premium.index') }}" class="btn btn-outline-primary me-2">
                    <i class="ph-duotone ph-crown me-1"></i>
                    Test Pacchetti Premium
                </a>
                <a href="{{ route('profile.videos') }}" class="btn btn-outline-secondary">
                    <i class="ph-duotone ph-video-camera me-1"></i>
                    I Miei Video
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
