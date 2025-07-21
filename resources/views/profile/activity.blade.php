@extends('layout.master')

@section('title', 'Le Mie Attività - Slamin')

@section('css')
@endsection

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">{{ __('profile.my_activities') }}</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> {{ __('dashboard.dashboard') }}
                        </span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('profile.show') }}" class="f-s-14 f-w-500">{{ __('profile.breadcrumb_profile') }}</a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">{{ __('profile.activities') }}</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 f-w-600">Timeline delle Attività</h4>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="activityFilter">
                        <option value="">Tutte le attività</option>
                        <option value="event_organized">Eventi organizzati</option>
                        <option value="event_participation">Partecipazioni</option>
                        <option value="video_upload">Video caricati</option>
                    </select>
                    <button class="btn btn-outline-primary hover-effect" onclick="exportActivity()">
                        <i class="ph ph-download me-2"></i>Esporta
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Timeline -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0 f-w-600 text-dark">
                        <i class="ph ph-activity me-2"></i>
                        Cronologia Attività
                    </h5>
                </div>
                <div class="card-body pa-30">
                    @if($activities->count() > 0)
                    <div class="timeline">
                        @foreach($activities as $activity)
                        <div class="timeline-item activity-item" data-type="{{ $activity['type'] }}">
                            <div class="timeline-marker bg-{{ $activity['color'] }}">
                                <i class="ph {{ $activity['icon'] }} text-white f-s-14"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="card hover-effect">
                                    <div class="card-body pa-20">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0 f-w-600 f-s-16">{{ $activity['title'] }}</h6>
                                            <span class="badge bg-{{ $activity['color'] }} f-s-11">
                                                {{ ucfirst(str_replace('_', ' ', $activity['type'])) }}
                                            </span>
                                        </div>

                                        @if($activity['description'])
                                        <p class="text-muted f-s-14 mb-2">{{ Str::limit($activity['description'], 150) }}</p>
                                        @endif

                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted f-s-12">
                                                <i class="ph ph-clock me-1"></i>
                                                {{ $activity['date']->format('d/m/Y H:i') }}
                                                <span class="ms-2">({{ $activity['date']->diffForHumans() }})</span>
                                            </small>

                                            @if(isset($activity['url']))
                                            <a href="{{ $activity['url'] }}" class="btn btn-sm btn-outline-{{ $activity['color'] }} hover-effect">
                                                <i class="ph ph-arrow-right me-1"></i>Vedi Dettagli
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="bg-light-info h-80 w-80 d-flex-center rounded-circle m-auto mb-3">
                            <i class="ph ph-activity-slash f-s-48 text-info"></i>
                        </div>
                        <h4 class="text-info f-w-600 mb-2">Nessuna Attività</h4>
                        <p class="text-muted f-s-16 mb-4">Non hai ancora registrato nessuna attività. Inizia a partecipare agli eventi!</p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('events.index') }}" class="btn btn-primary hover-effect">
                                <i class="ph ph-calendar me-2"></i>Vedi Eventi
                            </a>
                            <a href="{{ route('videos.upload') }}" class="btn btn-success hover-effect">
                                <i class="ph ph-video-camera me-2"></i>Carica Video
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Statistics -->
    @if($activities->count() > 0)
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card hover-effect equal-card b-t-4-primary">
                <div class="card-body eshop-cards text-center pa-20">
                    <div class="bg-light-primary h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                        <i class="ph ph-calendar-plus f-s-20 text-primary"></i>
                    </div>
                    <span class="ripple-effect"></span>
                    <div class="overflow-hidden">
                        <h3 class="text-primary mb-1 f-w-600">{{ $activities->where('type', 'event_organized')->count() }}</h3>
                        <p class="f-w-500 text-dark f-s-13 mb-1">{{ __('profile.organized_events') }}</p>
                        <span class="badge bg-light-primary f-s-11">{{ __('profile.role_organizer') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card hover-effect equal-card b-t-4-success">
                <div class="card-body eshop-cards text-center pa-20">
                    <div class="bg-light-success h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                        <i class="ph ph-users f-s-20 text-success"></i>
                    </div>
                    <span class="ripple-effect"></span>
                    <div class="overflow-hidden">
                        <h3 class="text-success mb-1 f-w-600">{{ $activities->where('type', 'event_participation')->count() }}</h3>
                        <p class="f-w-500 text-dark f-s-13 mb-1">{{ __('profile.participated_events') }}</p>
                        <span class="badge bg-light-success f-s-11">{{ __('profile.role_participant') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card hover-effect equal-card b-t-4-warning">
                <div class="card-body eshop-cards text-center pa-20">
                    <div class="bg-light-warning h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                        <i class="ph ph-video-camera f-s-20 text-warning"></i>
                    </div>
                    <span class="ripple-effect"></span>
                    <div class="overflow-hidden">
                        <h3 class="text-warning mb-1 f-w-600">{{ $activities->where('type', 'video_upload')->count() }}</h3>
                        <p class="f-w-500 text-dark f-s-13 mb-1">{{ __('profile.uploaded_videos') }}</p>
                        <span class="badge bg-light-warning f-s-11">{{ __('profile.role_video') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@section('script')
<script>
// Activity Filter
document.getElementById('activityFilter').addEventListener('change', function() {
    const filterValue = this.value;
    const activityItems = document.querySelectorAll('.activity-item');

    activityItems.forEach(item => {
        if (filterValue === '' || item.dataset.type === filterValue) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

function exportActivity() {
    Swal.fire({
        title: 'Esporta Attività',
        text: 'Vuoi esportare le tue attività in formato CSV?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sì, esporta!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            // Implementazione esportazione
            Swal.fire('Info', 'Funzionalità esportazione in sviluppo', 'info');
        }
    });
}

// Timeline Animation
document.addEventListener('DOMContentLoaded', function() {
    const timelineItems = document.querySelectorAll('.timeline-item');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateX(0)';
            }
        });
    });

    timelineItems.forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        item.style.transition = 'all 0.3s ease';
        observer.observe(item);
    });
});

// Hide loader as fallback
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const loader = document.querySelector('.loader-wrapper');
        if (loader) {
            loader.style.display = 'none';
        }
    }, 1000);
});
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #e9ecef, #dee2e6);
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 20px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-content {
    margin-left: 20px;
}
</style>
@endsection
