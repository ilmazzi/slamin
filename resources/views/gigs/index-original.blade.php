@extends('layout.master')

@section('title', __('gigs.title'))

@section('styles')
<style>
.f-s-10 { font-size: 10px !important; }
.f-s-12 { font-size: 12px !important; }
.f-s-14 { font-size: 14px !important; }

@media (max-width: 768px) {
    .f-s-10 { font-size: 9px !important; }
    .f-s-12 { font-size: 11px !important; }
    .f-s-14 { font-size: 13px !important; }

    .btn-sm {
        padding: 4px 8px !important;
        font-size: 12px !important;
        min-width: 32px !important;
        min-height: 32px !important;
    }

    .card-body {
        padding: 16px !important;
    }

    .card-title {
        font-size: 16px !important;
        line-height: 1.4 !important;
        word-wrap: break-word !important;
    }

    .badge {
        font-size: 9px !important;
        padding: 4px 6px !important;
    }

    .form-control-sm, .form-select-sm {
        font-size: 14px !important;
        padding: 8px 12px !important;
    }
}
</style>
@endsection

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="page-title-box">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                <div class="page-title-right">
                    
                </div>
            </div>
        </div>

        <!-- Mobile-First Statistiche -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card card-light-primary hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-primary rounded">
                                        <i class="ph ph-briefcase text-white f-s-14"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-1 f-s-12 f-w-500">{{ __('gigs.stats.total_gigs') }}</h6>
                                <h4 class="mb-0 f-s-18 f-w-600">{{ number_format($stats['total_gigs']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-light-success hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-success rounded">
                                        <i class="ph ph-check-circle text-white f-s-14"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-1 f-s-12 f-w-500">{{ __('gigs.stats.open_gigs_count') }}</h6>
                                <h4 class="mb-0 f-s-18 f-w-600">{{ number_format($stats['open_gigs_count']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-light-warning hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-warning rounded">
                                        <i class="ph ph-warning text-white f-s-14"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-1 f-s-12 f-w-500">{{ __('gigs.stats.urgent_gigs_count') }}</h6>
                                <h4 class="mb-0 f-s-18 f-w-600">{{ number_format($stats['urgent_gigs_count']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-light-info hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-info rounded">
                                        <i class="ph ph-users text-white f-s-14"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-1 f-s-12 f-w-500">{{ __('gigs.stats.total_applications') }}</h6>
                                <h4 class="mb-0 f-s-18 f-w-600">{{ number_format((int)$gigs->sum('application_count')) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile-First Filtri e Ricerca -->
        <div class="card hover-effect">
            <div class="card-header">
                <h5 class="card-title mb-0 f-s-16 f-w-600">
                    <i class="ph ph-funnel me-2"></i>{{ __('gigs.filters.title') }}
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('gigs.index') }}" class="row g-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="search" class="form-label f-s-14 f-w-500">{{ __('gigs.filters.search') }}</label>
                        <input type="text" class="form-control form-control-sm" id="search" name="search"
                               value="{{ request('search') }}"
                               placeholder="{{ __('gigs.filters.search') }}">
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="category" class="form-label f-s-14 f-w-500">{{ __('gigs.filters.filter_by_category') }}</label>
                        <select class="form-select form-select-sm" id="category" name="category">
                            <option value="">{{ __('common.all') }}</option>
                            @foreach($categories as $key => $category)
                                <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="type" class="form-label f-s-14 f-w-500">{{ __('gigs.filters.filter_by_type') }}</label>
                        <select class="form-select form-select-sm" id="type" name="type">
                            <option value="">{{ __('common.all') }}</option>
                            @foreach($types as $key => $type)
                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="sort" class="form-label f-s-14 f-w-500">{{ __('gigs.filters.sort_by') }}</label>
                        <select class="form-select form-select-sm" id="sort" name="sort">
                            @foreach($sortOptions as $key => $option)
                                <option value="{{ $key }}" {{ request('sort', 'recent') == $key ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label class="form-label f-s-14 f-w-500">&nbsp;</label>
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="ph ph-magnifying-glass me-1"></i>{{ __('common.search') }}
                            </button>
                            <a href="{{ route('gigs.index') }}" class="btn btn-light btn-sm">
                                <i class="ph ph-arrows-clockwise me-1"></i>{{ __('common.reset') }}
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Mobile-First Filtri rapidi -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remote" name="remote"
                                       value="1" {{ request('remote') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label f-s-14" for="remote">
                                    {{ __('gigs.filters.show_remote') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="urgent" name="urgent"
                                       value="1" {{ request('urgent') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label f-s-14" for="urgent">
                                    {{ __('gigs.filters.show_urgent') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="featured" name="featured"
                                       value="1" {{ request('featured') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label f-s-14" for="featured">
                                    {{ __('gigs.filters.show_featured') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile-First Sezione Organizzatore -->
        @if($showOrganizerSection && $userEvents->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card hover-effect">
                    <div class="card-header">
                        <h5 class="card-title mb-0 f-s-16 f-w-600">
                            <i class="ph ph-calendar me-2"></i>{{ __('gigs.organizer_section.title') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($userEvents as $event)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card hover-effect h-100">
                                    <div class="card-body d-flex flex-column p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="flex-grow-1 me-2">
                                                <h6 class="card-title mb-2 fw-bold f-s-14" style="word-wrap: break-word; line-height: 1.4;">
                                                    <a href="{{ route('events.show', $event) }}" class="text-decoration-none text-dark">
                                                        {{ Str::limit(is_array($event->title) ? implode(', ', $event->title) : $event->title, 50) }}
                                                    </a>
                                                </h6>
                                                @if($event->subtitle)
                                                    <h6 class="text-muted mb-2 f-s-12" style="word-wrap: break-word;">{{ Str::limit(is_array($event->subtitle) ? implode(', ', $event->subtitle) : $event->subtitle, 40) }}</h6>
                                                @endif
                                                <p class="text-muted mb-2 f-s-12" style="word-wrap: break-word; line-height: 1.4;">
                                                    {{ Str::limit(is_array($event->description) ? implode(', ', $event->description) : $event->description, 60) }}
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="bg-light-primary text-center d-flex flex-column align-items-center justify-content-center" style="min-width: 40px; min-height: 40px; font-size: 10px; border-radius: 6px;">
                                                    <div class="fw-bold f-s-12">{{ $event->start_datetime ? $event->start_datetime->format('d') : 'N/A' }}</div>
                                                    <div class="f-s-10">{{ $event->start_datetime ? $event->start_datetime->format('M') : 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex align-items-center text-muted mb-1">
                                                <i class="ph ph-clock me-1 f-s-12"></i>
                                                <span class="f-s-12">
                                                    {{ $event->start_datetime ? $event->start_datetime->format('H:i') : 'N/A' }} -
                                                    {{ $event->end_datetime ? $event->end_datetime->format('H:i') : 'N/A' }}
                                                </span>
                                            </div>
                                            <div class="d-flex align-items-center text-muted mb-1">
                                                <i class="ph ph-user me-1 f-s-12"></i>
                                                <span class="f-s-12">
                                                    @if($event->organizer)
                                                        <a href="{{ route('user.show', $event->organizer) }}" class="text-decoration-none hover-effect">
                                                            {{ $event->organizer->getDisplayName() }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </span>
                                            </div>
                                            @if($event->is_online)
                                                <div class="d-flex align-items-center text-muted mb-1">
                                                    <i class="ph ph-globe me-1 f-s-12"></i>
                                                    <span class="f-s-12">{{ $event->timezone ?? 'N/A' }}</span>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center text-muted mb-1">
                                                    <i class="ph ph-map-pin me-1 f-s-12"></i>
                                                    <span class="f-s-12">{{ $event->city ?? 'N/A' }}, {{ $event->country ?? 'N/A' }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-auto">
                                            <!-- Event Info -->
                                            <div class="d-flex flex-wrap align-items-center gap-1 mb-2">
                                                @if($event->entry_fee > 0)
                                                    <span class="badge bg-warning f-s-10">{{ __('events.entry_fee') }}: €{{ $event->entry_fee }}</span>
                                                @else
                                                    <span class="badge bg-success f-s-10">{{ __('events.free') }}</span>
                                                @endif
                                                @if($event->max_participants)
                                                    <small class="text-muted f-s-10">{{ __('events.max_participants') }}: {{ $event->max_participants }}</small>
                                                @endif
                                                <span class="badge bg-primary f-s-10">
                                                    {{ (int)$event->gigs()->count() }} {{ __('gigs.organizer_section.gigs') }}
                                                </span>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="d-flex flex-column flex-sm-row gap-2">
                                                <a href="{{ route('events.show', $event) }}" class="btn btn-primary btn-sm">
                                                    <i class="ph ph-eye me-1"></i>{{ __('common.view') }}
                                                </a>
                                                <a href="{{ route('gigs.create') }}?event={{ $event->id }}" class="btn btn-light btn-sm">
                                                    <i class="ph ph-plus me-1"></i>{{ __('gigs.organizer_section.add_gig') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Mobile-First Azioni principali -->
        @auth
            @unless(auth()->user()->hasRole('audience'))
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <a href="{{ route('gigs.create') }}" class="btn btn-primary btn-sm">
                                <i class="ph ph-plus me-2"></i>{{ __('gigs.create_gig') }}
                            </a>
                            <a href="{{ route('gigs.my-gigs') }}" class="btn btn-light btn-sm">
                                <i class="ph ph-briefcase me-2"></i>{{ __('gigs.my_gigs') }}
                            </a>
                                            <a href="{{ route('gigs.my-applications') }}" class="btn btn-light btn-sm">
                    <i class="ph ph-user-plus me-2"></i>{{ __('gigs.applications.my_applications') }}
                </a>
                        </div>
                    </div>
                </div>
            @endunless
        @endauth

        <!-- Mobile-First Lista Gigs -->
        <div class="row">
            @forelse($gigs as $gig)
                <div class="col-12">
                    <div class="card hover-effect mb-3">
                        <div class="card-body p-3">
                            <!-- Header con badge di stato -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1 me-3">
                                    <h6 class="card-title mb-1 f-s-16 f-w-600" style="word-wrap: break-word; line-height: 1.4;">
                                        <a href="{{ route('gigs.show', $gig) }}" class="text-decoration-none hover-effect">
                                            @php
                                                $gigTitle = is_array($gig->title) ? implode(', ', $gig->title) : $gig->title;
                                            @endphp
                                            {{ $gigTitle }}
                                        </a>
                                    </h6>
                                    <p class="text-muted f-s-12 mb-0">
                                        <i class="ph ph-user me-1"></i>
                                        @if($gig->user)
                                            <a href="{{ route('user.show', $gig->user) }}" class="text-decoration-none hover-effect">
                                                {{ $gig->user->getDisplayName() }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    @if($gig->is_urgent)
                                        <span class="badge bg-warning f-s-10">
                                            <i class="ph ph-warning me-1"></i>{{ __('gigs.status.urgent') }}
                                        </span>
                                    @elseif($gig->is_featured)
                                        <span class="badge bg-info f-s-10">
                                            <i class="ph ph-star me-1"></i>{{ __('gigs.status.featured') }}
                                        </span>
                                    @elseif($gig->is_closed)
                                        <span class="badge bg-secondary f-s-10">
                                            <i class="ph ph-lock me-1"></i>{{ __('gigs.status.closed') }}
                                        </span>
                                    @elseif($gig->is_expired)
                                        <span class="badge bg-danger f-s-10">
                                            <i class="ph ph-clock me-1"></i>{{ __('gigs.status.expired') }}
                                        </span>
                                    @else
                                        <span class="badge bg-success f-s-10">
                                            <i class="ph ph-check-circle me-1"></i>{{ __('gigs.status.open') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Descrizione -->
                            <p class="card-text text-muted f-s-12 mb-3" style="word-wrap: break-word; line-height: 1.4;">
                                {{ Str::limit(is_array($gig->description) ? implode(', ', $gig->description) : $gig->description, 100) }}
                            </p>

                            <!-- Categorie e tipo -->
                            <div class="mb-3">
                                <span class="badge bg-light-primary me-1 f-s-10">
                                    @php
                                        $categoryKey = is_array($gig->category) ? implode(', ', $gig->category) : $gig->category;
                                        $categoryTranslation = __('gigs.categories.' . $categoryKey);
                                        if ($categoryTranslation === 'gigs.categories.' . $categoryKey) {
                                            $categoryTranslation = $categoryKey; // Fallback se la traduzione non esiste
                                        }
                                    @endphp
                                    {{ $categoryTranslation }}
                                </span>
                                <span class="badge bg-light-primary me-1 f-s-10">
                                    @php
                                        $typeKey = is_array($gig->type) ? implode(', ', $gig->type) : $gig->type;
                                        $typeTranslation = __('gigs.types.' . $typeKey);
                                        if ($typeTranslation === 'gigs.types.' . $typeKey) {
                                            $typeTranslation = $typeKey; // Fallback se la traduzione non esiste
                                        }
                                    @endphp
                                    {{ $typeTranslation }}
                                </span>
                                @if($gig->is_remote)
                                    <span class="badge bg-light-success f-s-10">
                                        <i class="ph ph-globe me-1"></i>{{ __('gigs.fields.is_remote') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Informazioni aggiuntive -->
                            <div class="row text-center mb-3 g-2">
                                <div class="col-4">
                                    <small class="text-muted d-block f-s-10">{{ __('gigs.stats.applications') }}</small>
                                    @if((int)$gig->application_count > 0)
                                        <a href="{{ route('gigs.manage-applications', $gig) }}" class="text-decoration-none">
                                            <strong class="text-primary f-s-12">{{ (int)$gig->application_count }}</strong>
                                            <i class="ph ph-arrow-right ms-1 f-s-10"></i>
                                        </a>
                                    @else
                                        <strong class="f-s-12">{{ (int)$gig->application_count }}</strong>
                                    @endif
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block f-s-10">{{ __('gigs.stats.accepted_applications_count') }}</small>
                                    <strong class="text-success f-s-12">{{ (int)$gig->accepted_applications_count }}</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block f-s-10">{{ __('gigs.fields.deadline') }}</small>
                                    <strong class="f-s-12">{{ $gig->deadline ? $gig->deadline->format('d/m/Y') : 'N/A' }}</strong>
                                </div>
                            </div>

                            <!-- Compenso e località -->
                            <div class="mb-3">
                                @if($gig->compensation)
                                    <div class="text-success f-s-12">
                                        <i class="ph ph-currency-eur me-1"></i>
                                        @php
                                            $gigCompensation = is_array($gig->compensation) ? implode(', ', $gig->compensation) : $gig->compensation;
                                        @endphp
                                        {{ $gigCompensation }}
                                    </div>
                                @endif
                                @if($gig->location)
                                    <div class="text-muted f-s-12">
                                        <i class="ph ph-map-pin me-1"></i>
                                        @php
                                            $gigLocation = is_array($gig->location) ? implode(', ', $gig->location) : $gig->location;
                                        @endphp
                                        {{ $gigLocation }}
                                    </div>
                                @endif
                            </div>

                            <!-- Azioni -->
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <a href="{{ route('gigs.show', $gig) }}" class="btn btn-primary btn-sm">
                                    <i class="ph ph-eye me-1"></i>{{ __('gigs.actions.read') }}
                                </a>
                                @auth
                                    @unless(auth()->user()->hasRole('audience'))
                                        @if($gig->can_apply)
                                            <button class="btn btn-success btn-sm" onclick="applyToGig({{ $gig->id }})">
                                                <i class="ph ph-user-plus me-1"></i>{{ __('gigs.apply_gig') }}
                                            </button>
                                        @else
                                            <button class="btn btn-light btn-sm" disabled>
                                                <i class="ph ph-lock me-1"></i>{{ __('gigs.status.closed') }}
                                            </button>
                                        @endif
                                    @endunless
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-light btn-sm">
                                        <i class="ph ph-sign-in me-1"></i>{{ __('gigs.messages.login_to_interact') }}
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ph ph-briefcase text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">{{ __('gigs.messages.no_gigs_found') }}</h5>
                            <p class="text-muted">{{ __('gigs.messages.no_gigs_description') }}</p>
                            @auth
                                @unless(auth()->user()->hasRole('audience'))
                                    <a href="{{ route('gigs.create') }}" class="btn btn-primary">
                                        <i class="ph ph-plus me-2"></i>{{ __('gigs.create_gig') }}
                                    </a>
                                @endunless
                            @endauth
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Paginazione -->
        @if($gigs->hasPages())
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $gigs->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Sezione Lavori di Traduzione -->
        @if($translationJobs->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <div class="d-flex align-items-center mb-4">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">
                            <i class="ph ph-translate text-primary me-2"></i>
                            {{ __('gigs.translation_jobs') }}
                        </h4>
                        <p class="text-muted mb-0">{{ __('gigs.translation_jobs_description') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($translationJobs as $job)
                <div class="col-12">
                    <div class="card hover-effect mb-3">
                        <div class="card-body p-3">
                            <!-- Header con badge di stato -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1 me-3">
                                    <h6 class="card-title mb-1 f-s-16 f-w-600" style="word-wrap: break-word; line-height: 1.4;">
                                        @php
                                            $jobTitle = is_array($job->title) ? implode(', ', $job->title) : $job->title;
                                        @endphp
                                        @if($job->poem)
                                            <a href="{{ route('poems.show', $job->poem->slug) }}" class="text-decoration-none hover-effect">
                                                {{ $jobTitle }}
                                            </a>
                                        @else
                                            {{ $jobTitle }}
                                        @endif
                                    </h6>
                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                        <span class="badge bg-light-primary f-s-10">{{ __('gigs.categories.traduzione') }}</span>
                                        <span class="badge bg-light-info f-s-10">{{ $job->type }}</span>
                                        <span class="badge bg-light-success f-s-10">{{ $job->language }}</span>
                                        @if($job->is_featured)
                                            <span class="badge bg-light-warning f-s-10">
                                                <i class="ph ph-star me-1"></i>{{ __('gigs.status.featured') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    @php
                                        $jobCompensation = is_array($job->compensation) ? implode(', ', $job->compensation) : $job->compensation;
                                    @endphp
                                    <div class="f-s-14 f-w-600 text-primary mb-1">{{ $jobCompensation }}</div>
                                    <div class="f-s-12 text-muted">{{ __('gigs.remote') }}</div>
                                </div>
                            </div>

                            <!-- Descrizione -->
                            <p class="card-text f-s-14 text-muted mb-3" style="line-height: 1.5;">
                                {{ Str::limit(is_array($job->description) ? implode(', ', $job->description) : $job->description, 150) }}
                            </p>

                            <!-- Footer con azioni -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center text-muted f-s-12">
                                        <i class="ph ph-user me-1"></i>
                                        @if($job->user)
                                            <a href="{{ route('user.show', $job->user) }}" class="text-decoration-none hover-effect">
                                                {{ $job->user->getDisplayName() }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center text-muted f-s-12">
                                        <i class="ph ph-calendar me-1"></i>
                                        {{ $job->created_at ? $job->created_at->format('d/m/Y') : 'N/A' }}
                                    </div>
                                    <div class="d-flex align-items-center text-muted f-s-12">
                                        <i class="ph ph-users me-1"></i>
                                        {{ (int)$job->application_count }} {{ __('gigs.applications') }}
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    @if($job->poem)
                                        <a href="{{ route('poems.show', $job->poem) }}" class="btn btn-primary btn-sm">
                                            <i class="ph ph-eye me-1"></i>{{ __('gigs.actions.view') }}
                                        </a>
                                        @auth
                                            @if(true)
                                                <a href="{{ route('translations.create', $job->poem) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="ph ph-translate me-1"></i>{{ __('gigs.actions.apply') }}
                                                </a>
                                            @endif
                                        @endauth
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<!-- Modal per candidatura -->
<div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applyModalLabel">{{ __('gigs.applications.apply') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
            </div>
            <form id="applyForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="message" class="form-label">{{ __('gigs.applications.message') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="message" name="message" rows="4"
                                  placeholder="{{ __('gigs.applications.message_placeholder') }}" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="experience" class="form-label">{{ __('gigs.applications.experience') }}</label>
                        <textarea class="form-control" id="experience" name="experience" rows="3"
                                  placeholder="{{ __('gigs.applications.experience_placeholder') }}"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="portfolio" class="form-label">{{ __('gigs.applications.portfolio') }}</label>
                        <input type="text" class="form-control" id="portfolio" name="portfolio"
                               placeholder="{{ __('gigs.applications.portfolio_placeholder') }}">
                    </div>
                    <div class="mb-3">
                        <label for="availability" class="form-label">{{ __('gigs.applications.availability') }}</label>
                        <textarea class="form-control" id="availability" name="availability" rows="2"
                                  placeholder="{{ __('gigs.applications.availability_placeholder') }}"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="compensation_expectation" class="form-label">{{ __('gigs.applications.compensation_expectation') }}</label>
                        <input type="text" class="form-control" id="compensation_expectation" name="compensation_expectation"
                               placeholder="{{ __('gigs.applications.compensation_expectation_placeholder') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('gigs.applications.submit_application') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Fallback per toastr se viene caricato da qualche parte
if (typeof toastr === 'undefined') {
    window.toastr = {
        success: function(message) {
            Swal.fire('Successo!', message, 'success');
        },
        error: function(message) {
            Swal.fire('Errore!', message, 'error');
        },
        warning: function(message) {
            Swal.fire('Attenzione!', message, 'warning');
        },
        info: function(message) {
            Swal.fire('Info', message, 'info');
        }
    };
}

let currentGigId = null;

function applyToGig(gigId) {
    currentGigId = gigId;
    $('#applyModal').modal('show');
}

$('#applyForm').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(`/gigs/${currentGigId}/apply`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire(
                'Candidatura Inviata!',
                data.message,
                'success'
            ).then(() => {
                $('#applyModal').modal('hide');
                $('#applyForm')[0].reset();
                location.reload();
            });
        } else {
            Swal.fire(
                'Errore!',
                data.error || 'Errore durante l\'invio della candidatura',
                'error'
            );
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire(
            'Errore!',
            'Errore di connessione o server non disponibile',
            'error'
        );
    });
});
</script>
@endpush
