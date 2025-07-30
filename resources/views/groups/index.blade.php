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
                                <option value="my_groups" {{ request('filter') == 'my_groups' ? 'selected' : '' }}>
                                    {{ __('groups.filter_my_groups') }}
                                </option>
                                <option value="public" {{ request('filter') == 'public' ? 'selected' : '' }}>
                                    {{ __('groups.filter_public') }}
                                </option>
                                <option value="private" {{ request('filter') == 'private' ? 'selected' : '' }}>
                                    {{ __('groups.filter_private') }}
                                </option>
                                @if(auth()->user()->hasRole('admin'))
                                <option value="admin" {{ request('filter') == 'admin' ? 'selected' : '' }}>
                                    {{ __('groups.filter_admin') }}
                                </option>
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

    <!-- Lista gruppi -->
    <div class="row">
        @forelse($groups as $group)
        <div class="col-12 col-md-6 col-lg-4 mb-3">
            <div class="card hover-effect h-100">
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
                            {{ $group->creator->getDisplayName() }}
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
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ph-duotone ph-users text-muted f-s-64 mb-3"></i>
                    <h5 class="text-muted">{{ __('groups.no_groups') }}</h5>
                    @can('groups.create')
                    <p class="text-muted mb-3">{{ __('groups.tips.create_group') }}</p>
                    <a href="{{ route('groups.create') }}" class="btn btn-primary">
                        <i class="ph-duotone ph-plus me-2"></i>
                        {{ __('groups.create_group') }}
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Paginazione -->
    @if($groups->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ $groups->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 