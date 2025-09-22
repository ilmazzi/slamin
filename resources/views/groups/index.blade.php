@extends('layout.master')

@section('title', __('groups.title'))

@section('main-content')
<div class="container-fluid">
    <!-- Header con titolo e pulsante crea -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-users me-2 text-primary"></i>
                        {{ __('groups.title') }}
                    </h4>
                    @can('groups.create')
                    <a href="{{ route('groups.create') }}" class="btn btn-primary">
                        <i class="ph-duotone ph-plus me-2"></i>
                        {{ __('groups.create_group') }}
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Messaggi Flash -->
    @if(session('success'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-x-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Filtri e ricerca -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('groups.index') }}" class="row g-3">
                        <!-- Ricerca -->
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph-duotone ph-magnifying-glass"></i>
                                </span>
                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       placeholder="{{ __('groups.search_placeholder') }}"
                                       value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- Filtro -->
                        <div class="col-md-3">
                            <select name="filter" class="form-select">
                                <option value="">{{ __('groups.filter_all') }}</option>
                                <!-- Filtri per gruppi -->
                                <optgroup label="{{ __('groups.groups') }}">
                                    <option value="my_groups" {{ request('filter') == 'my_groups' ? 'selected' : '' }}>
                                        {{ __('groups.filter_my_groups') }}
                                    </option>
                                    <option value="public" {{ request('filter') == 'public' ? 'selected' : '' }}>
                                        {{ __('groups.filter_public') }}
                                    </option>
                                    <option value="private" {{ request('filter') == 'private' ? 'selected' : '' }}>
                                        {{ __('groups.filter_private') }}
                                    </option>
                                </optgroup>
                                <!-- Filtri per utenti -->
                                <optgroup label="{{ __('groups.users') }}">
                                    <option value="poets" {{ request('filter') == 'poets' ? 'selected' : '' }}>
                                        {{ __('groups.filter_poets') }}
                                    </option>
                                    <option value="organizers" {{ request('filter') == 'organizers' ? 'selected' : '' }}>
                                        {{ __('groups.filter_organizers') }}
                                    </option>
                                    <option value="active" {{ request('filter') == 'active' ? 'selected' : '' }}>
                                        {{ __('groups.filter_active_users') }}
                                    </option>
                                </optgroup>
                                @if(auth()->user()->hasRole('admin'))
                                <optgroup label="{{ __('groups.admin') }}">
                                    <option value="admin" {{ request('filter') == 'admin' ? 'selected' : '' }}>
                                        {{ __('groups.filter_admin') }}
                                    </option>
                                </optgroup>
                                @endif
                            </select>
                        </div>

                        <!-- Pulsanti -->
                        <div class="col-md-5">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ph-duotone ph-funnel me-1"></i>
                                {{ __('common.filter') }}
                            </button>
                            <a href="{{ route('groups.index') }}" class="btn btn-light">
                                <i class="ph-duotone ph-arrow-clockwise me-1"></i>
                                {{ __('common.reset') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================== -->
    <!-- SEZIONE UTENTI (SUPERIORE) -->
    <!-- ======================================== -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 f-w-600">
                        <i class="ph-duotone ph-user me-2 text-info"></i>{{ __('groups.users') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($users as $user)
                            <x-user-card
                                :user="$user"
                                card-class="col-12 col-md-6 col-lg-4 mb-3"
                                :show-follow-button="true"
                                :show-message-button="true" />
                        @empty
                        <div class="col-12">
                            <div class="text-center py-4">
                                <i class="ph-duotone ph-user text-muted f-s-48 mb-3"></i>
                                <h6 class="text-muted">{{ __('groups.no_users') }}</h6>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Paginazione utenti -->
                    @if($users->hasPages())
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="app-pagination-link">
                                        {{ $users->appends(request()->query())->links('pagination.bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================== -->
    <!-- SEZIONE GRUPPI (INFERIORE) -->
    <!-- ======================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 f-w-600">
                        <i class="ph-duotone ph-users me-2 text-primary"></i>{{ __('groups.group_title') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($groups as $group)
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            <div class="card hover-effect equal-card">
                                <div class="card-body">
                                    <!-- Header del gruppo -->
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="flex-shrink-0">
                                            @if($group->image)
                                                <img src="{{ asset('storage/' . $group->image) }}"
                                                     alt="{{ $group->name }}"
                                                     class="rounded-circle"
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center"
                                                     style="width: 60px; height: 60px;">
                                                    <i class="ph-duotone ph-users text-primary f-s-24"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="card-title mb-1">{{ $group->name }}</h5>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-{{ $group->visibility == 'public' ? 'success' : 'warning' }}">
                                                    {{ __('groups.visibility_' . $group->visibility) }}
                                                </span>
                                                @if($group->hasMember(auth()->user()))
                                                    @php $role = $group->getUserRole(auth()->user()); @endphp
                                                    <span class="badge bg-info">
                                                        {{ __('groups.role_' . $role) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Descrizione -->
                                    @if($group->description)
                                    <p class="card-text text-muted mb-3">
                                        {{ Str::limit($group->description, 100) }}
                                    </p>
                                    @endif

                                    <!-- Statistiche -->
                                    <div class="row text-center mb-3">
                                        <div class="col-4">
                                            <div class="text-primary fw-bold">{{ $group->getMembersCount() }}</div>
                                            <small class="text-muted">{{ __('groups.members_count_label') }}</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-success fw-bold">{{ $group->getAdmins()->count() }}</div>
                                            <small class="text-muted">{{ __('groups.admins_count') }}</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-info fw-bold">{{ $group->getModerators()->count() }}</div>
                                            <small class="text-muted">{{ __('groups.moderators_count') }}</small>
                                        </div>
                                    </div>

                                    <!-- Azioni -->
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('groups.show', $group) }}" class="btn btn-primary btn-sm flex-fill">
                                            <i class="ph-duotone ph-eye me-1"></i>
                                            {{ __('common.view') }}
                                        </a>

                                        @if($group->hasMember(auth()->user()))
                                            @if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin'))
                                            <a href="{{ route('groups.edit', $group) }}" class="btn btn-light-primary btn-sm">
                                                <i class="ph-duotone ph-pencil"></i>
                                            </a>
                                            @endif
                                        @else
                                            @if($group->visibility == 'public')
                                                <form action="{{ route('groups.join', $group) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="message" value="">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="ph-duotone ph-plus me-1"></i>
                                                        {{ __('groups.join') }}
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('groups.requests.store', $group) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-info btn-sm">
                                                        <i class="ph-duotone ph-hand-waving me-1"></i>
                                                        {{ __('groups.send_request') }}
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <!-- Footer con info aggiuntive -->
                                <div class="card-footer bg-light-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="ph-duotone ph-user me-1"></i>
                                            <a href="{{ route('user.show', $group->creator) }}" class="text-decoration-none hover-effect">
                                                {{ $group->creator->getDisplayName() }}
                                            </a>
                                        </small>
                                        <small class="text-muted">
                                            <i class="ph-duotone ph-calendar me-1"></i>
                                            {{ $group->created_at->format('d/m/Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-4">
                                <i class="ph-duotone ph-users text-muted f-s-48 mb-3"></i>
                                <h6 class="text-muted">{{ __('groups.no_groups') }}</h6>
                                @can('groups.create')
                                <p class="text-muted mb-3">{{ __('groups.tips.create_group') }}</p>
                                <a href="{{ route('groups.create') }}" class="btn btn-primary">
                                    <i class="ph-duotone ph-plus me-2"></i>
                                    {{ __('groups.create_group') }}
                                </a>
                                @endcan
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Paginazione gruppi -->
                    @if($groups->hasPages())
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="app-pagination-link">
                                        {{ $groups->appends(request()->query())->links('pagination.bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<x-user-card-scripts />

@endsection
