@extends('layout.master')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">?? I Miei Inviti</h4>
                    <div class="page-title-right">
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium text-truncate d-block">Inviti Totali</span>
                                <h4 class="fs-4 fw-semibold mb-3">{{ $invitations->total() }}</h4>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="ph ph-envelope-simple text-primary fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium text-truncate d-block">In Attesa</span>
                                <h4 class="fs-4 fw-semibold mb-3 text-warning">{{ $invitations->where('status', 'pending')->count() }}</h4>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="ph ph-clock text-warning fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium text-truncate d-block">Accettati</span>
                                <h4 class="fs-4 fw-semibold mb-3 text-success">{{ $invitations->where('status', 'accepted')->count() }}</h4>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="ph ph-check-circle text-success fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium text-truncate d-block">Rifiutati</span>
                                <h4 class="fs-4 fw-semibold mb-3 text-danger">{{ $invitations->where('status', 'declined')->count() }}</h4>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="ph ph-x-circle text-danger fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invitations List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0">?? Inviti Ricevuti</h4>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                                    <i class="ph ph-arrows-clockwise"></i> Aggiorna
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($invitations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Evento</th>
                                            <th>Ruolo</th>
                                            <th>Organizzatore</th>
                                            <th>Data Evento</th>
                                            <th>Stato</th>
                                            <th>Azioni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invitations as $invitation)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($invitation->event->image && !empty($invitation->event->image))
                                                            <img src="{{ $invitation->event->image }}" alt="Event Image" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; display: none;">
                                                                <i class="ph ph-calendar text-muted"></i>
                                                            </div>
                                                        @else
                                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                                <i class="ph ph-calendar text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">{{ $invitation->event->title }}</h6>
                                                            <small class="text-muted">{{ $invitation->event->city }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ ucfirst($invitation->role) }}</span>
                                                    @if($invitation->compensation)
