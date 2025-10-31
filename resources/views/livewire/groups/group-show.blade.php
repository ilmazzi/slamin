<div class="container-fluid">
    <!-- Header del gruppo -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h2 class="mb-2">{{ $group->name }}</h2>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="badge bg-{{ $group->visibility == 'public' ? 'success' : 'warning' }} fs-6">
                                            {{ __('groups.visibility_' . $group->visibility) }}
                                        </span>
                                        @if($isMember)
                                            @if($userRole)
                                            <span class="badge bg-info fs-6">
                                                {{ __('groups.role_' . $userRole) }}
                                            </span>
                                            @endif
                                        @endif
                                    </div>
                                    @if($group->description)
                                        <p class="text-muted mb-3">{{ $group->description }}</p>
                                    @endif
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="ph-duotone ph-dots-three-outline"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($isMember)
                                                @if($isAdmin || auth()->user()->hasRole('admin'))
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('groups.edit', $group) }}">
                                                        <i class="ph-duotone ph-pencil me-2"></i>
                                                        {{ __('groups.edit') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('groups.members.index', $group) }}">
                                                        <i class="ph-duotone ph-users me-2"></i>
                                                        {{ __('groups.group_members') }}
                                                    </a>
                                                </li>
                                                @endif
                                                @if(!$isAdmin)
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger" wire:click="leaveGroup" wire:confirm="{{ __('groups.confirm_leave') }}">
                                                        <i class="ph-duotone ph-sign-out me-2"></i>
                                                        {{ __('groups.leave') }}
                                                    </button>
                                                </li>
                                                @endif
                                            @else
                                                <li>
                                                    <button type="button" class="dropdown-item" wire:click="joinGroup">
                                                        <i class="ph-duotone ph-plus me-2"></i>
                                                        {{ __('groups.join') }}
                                                    </button>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner del gruppo -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    {!! group_banner_with_dimensions($group, '100%', '300px', 'w-100') !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Eventi del gruppo -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-calendar me-2 text-primary"></i>
                        {{ __('groups.group_events') }}
                    </h5>
                    @if($isMember)
                    <a href="{{ route('events.create', ['group_id' => $group->id]) }}" class="btn btn-primary btn-sm">
                        <i class="ph-duotone ph-plus me-1"></i>
                        {{ __('groups.create_group_event') }}
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    @php $groupEvents = $group->linkedEvents()->latest()->take(10)->get(); @endphp
                    @if($groupEvents->count() > 0)
                        <div class="row">
                            @foreach($groupEvents as $event)
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card overflow-hidden hover-effect h-100">
                                    {!! event_cover_with_dimensions($event, '100%', '200px', 'card-img-top') !!}
                                    <div class="card-body">
                                        <h5 class="card-title f-w-600">{{ $event->title }}</h5>
                                        <p class="card-text text-muted f-s-14">
                                            <i class="ph-duotone ph-map-pin f-s-12 me-1"></i>
                                            {{ $event->venue_name ?: $event->city ?: __('groups.location_tbd') }}
                                        </p>
                                        @if($event->description)
                                            <p class="card-text">{{ Str::limit($event->description, 80) }}</p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="card-text">
                                                <small class="text-body-secondary">
                                                    <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                                    {{ $event->start_datetime->format('d/m/Y H:i') }}
                                                </small>
                                            </p>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-warning">
                                                    <i class="ph-duotone ph-info f-s-14 me-1"></i>{{ __('groups.details') }}
                                                </a>
                                                <x-report-button :content="$event" type="event" size="sm" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ph-duotone ph-calendar text-muted f-s-48 mb-3"></i>
                            <p class="text-muted">{{ __('groups.no_group_events') }}</p>
                            @if($isMember)
                            <a href="{{ route('events.create', ['group_id' => $group->id]) }}" class="btn btn-primary">
                                <i class="ph-duotone ph-plus me-2"></i>
                                {{ __('groups.create_group_event') }}
                            </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="d-flex flex-column align-items-center">
                                <i class="ph-duotone ph-users text-primary f-s-24 mb-1"></i>
                                <h6 class="text-primary mb-0">{{ $stats['total_members'] }}</h6>
                                <small class="text-muted">{{ __('groups.total_members') }}</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex flex-column align-items-center">
                                <i class="ph-duotone ph-crown text-success f-s-24 mb-1"></i>
                                <h6 class="text-success mb-0">{{ $group->getAdmins()->count() }}</h6>
                                <small class="text-muted">{{ __('groups.admins_count') }}</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex flex-column align-items-center">
                                <i class="ph-duotone ph-shield-check text-info f-s-24 mb-1"></i>
                                <h6 class="text-info mb-0">{{ $group->getModerators()->count() }}</h6>
                                <small class="text-muted">{{ __('groups.moderators_count') }}</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex flex-column align-items-center">
                                <i class="ph-duotone ph-calendar text-warning f-s-24 mb-1"></i>
                                <h6 class="text-warning mb-0">{{ $stats['total_events'] }}</h6>
                                <small class="text-muted">{{ __('groups.group_events') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenuto principale -->
    <div class="row">
        <!-- Informazioni del gruppo -->
        <div class="col-12 col-lg-8">
            <!-- Membri recenti -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-users me-2 text-success"></i>
                        {{ __('groups.group_members') }}
                    </h5>
                    <a href="{{ route('groups.members.index', $group) }}" class="btn btn-primary btn-sm">
                        {{ __('groups.view_all') }}
                    </a>
                </div>
                <div class="card-body">
                    @forelse($group->members()->with('user')->latest()->take(6)->get() as $member)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($member->user) }}"
                                 alt="{{ $member->user->getDisplayName() }}"
                                 class="rounded-circle avatar-md">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">
                                <a href="{{ route('user.show', $member->user) }}" class="text-decoration-none hover-effect">
                                    {{ $member->user->getDisplayName() }}
                                </a>
                            </h6>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-{{ $member->role == 'admin' ? 'success' : ($member->role == 'moderator' ? 'info' : 'secondary') }}">
                                    {{ __('groups.role_' . $member->role) }}
                                </span>
                                <small class="text-muted">
                                    {{ __('groups.member_since') }} {{ $member->joined_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="ph-duotone ph-users text-muted f-s-48 mb-3"></i>
                        <p class="text-muted">{{ __('groups.no_members') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Bacheca annunci -->
            @if($isMember)
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-news me-2 text-primary"></i>
                        {{ __('groups.announcements') }}
                    </h5>
                    <a href="{{ route('groups.announcements.index', $group) }}" class="btn btn-primary btn-sm">
                        {{ __('groups.view_all') }}
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $recentAnnouncements = $group->announcements()
                            ->active()
                            ->with(['author'])
                            ->orderBy('is_pinned', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->limit(3)
                            ->get();
                    @endphp

                    @forelse($recentAnnouncements as $announcement)
                        <div class="announcement-item mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0">
                                    @if($announcement->is_pinned)
                                        <i class="ph-duotone ph-pin text-warning me-1" title="{{ __('groups.pinned_announcement') }}"></i>
                                    @endif
                                    {{ $announcement->title }}
                                </h6>
                                <small class="text-muted">{{ $announcement->created_at->format('d/m') }}</small>
                            </div>
                            <p class="text-muted small mb-2">
                                {{ Str::limit($announcement->content, 100) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="ph-duotone ph-user me-1"></i>
                                    {{ $announcement->author->name }}
                                </small>
                                <a href="{{ route('groups.announcements.show', [$group, $announcement]) }}"
                                   class="btn btn-sm btn-primary">
                                    {{ __('groups.read') }}
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3">
                            <i class="ph-duotone ph-news f-s-32 text-muted mb-2"></i>
                            <p class="text-muted mb-0">{{ __('groups.no_announcements') }}</p>
                            @if($isAdmin || $isModerator)
                            <a href="{{ route('groups.announcements.create', $group) }}" class="btn btn-sm btn-primary mt-2">
                                {{ __('groups.create_announcement') }}
                            </a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar con informazioni aggiuntive -->
        <div class="col-12 col-lg-4">
            <!-- Informazioni del gruppo -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph-duotone ph-info me-2 text-info"></i>
                        {{ __('groups.group_info') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>{{ __('groups.created_by') }}:</strong>
                        <div class="d-flex align-items-center mt-1">
                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($group->creator) }}"
                                 alt="{{ $group->creator->getDisplayName() }}"
                                 class="rounded-circle me-2 avatar-sm">
                            <a href="{{ route('user.show', $group->creator) }}" class="text-decoration-none hover-effect">
                                {{ $group->creator->getDisplayName() }}
                            </a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('groups.created_at') }}:</strong>
                        <div class="text-muted">{{ $group->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    @if($isMember)
                    <div>
                        <strong>{{ __('groups.member_since') }}:</strong>
                        <div class="text-muted">
                            {{ $group->members()->where('user_id', auth()->id())->first()->joined_at->format('d/m/Y') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Azioni rapide -->
            @if($isMember)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph-duotone ph-lightning me-2 text-warning"></i>
                        {{ __('groups.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('groups.members.index', $group) }}" class="btn btn-primary btn-sm">
                            <i class="ph-duotone ph-users me-2"></i>
                            {{ __('groups.view_members') }}
                        </a>
                        @if($isAdmin || $isModerator)
                        <a href="{{ route('groups.announcements.create', $group) }}" class="btn btn-success btn-sm">
                            <i class="ph-duotone ph-plus me-2"></i>
                            {{ __('groups.new_announcement') }}
                        </a>
                        @endif
                        @if($isAdmin || auth()->user()->hasRole('admin'))
                        <a href="{{ route('groups.edit', $group) }}" class="btn btn-warning btn-sm">
                            <i class="ph-duotone ph-pencil me-2"></i>
                            {{ __('groups.edit_group') }}
                        </a>
                        <a href="{{ route('groups.invitations.pending', $group) }}" class="btn btn-info btn-sm">
                            <i class="ph-duotone ph-envelope me-2"></i>
                            {{ __('groups.manage_invitations') }}
                        </a>
                        <a href="{{ route('groups.requests.pending', $group) }}" class="btn btn-secondary btn-sm">
                            <i class="ph-duotone ph-hand-waving me-2"></i>
                            {{ __('groups.manage_requests') }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Social Links -->
            <x-group-social-links :group="$group" />

            <!-- Statistiche avanzate -->
            @if($isAdmin || auth()->user()->hasRole('admin'))
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph-duotone ph-chart-line me-2 text-success"></i>
                        {{ __('groups.stats') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>{{ __('groups.pending_invitations') }}:</span>
                            <span class="badge bg-warning">{{ $stats['pending_invitations'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>{{ __('groups.pending_requests') }}:</span>
                            <span class="badge bg-info">{{ $stats['pending_requests'] }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between">
                            <span>{{ __('groups.group_events') }}:</span>
                            <span class="badge bg-primary">{{ $stats['total_events'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
