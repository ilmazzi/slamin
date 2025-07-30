@extends('layout.master')

@section('title', $group->name)

@section('main-content')
<div class="container-fluid">
    <!-- Header del gruppo -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            @if($group->image)
                                <img src="{{ asset('storage/' . $group->image) }}"
                                     alt="{{ $group->name }}"
                                     class="rounded-circle"
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 100px; height: 100px;">
                                    <i class="ph-duotone ph-users text-primary f-s-48"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h2 class="mb-2">{{ $group->name }}</h2>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="badge bg-{{ $group->visibility == 'public' ? 'success' : 'warning' }} fs-6">
                                            {{ __('groups.visibility_' . $group->visibility) }}
                                        </span>
                                        @if($group->hasMember(auth()->user()))
                                            @php $role = $group->getUserRole(auth()->user()); @endphp
                                            @if($role)
                                            <span class="badge bg-info fs-6">
                                                {{ __('groups.role_' . $role) }}
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
                                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="ph-duotone ph-dots-three-outline"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($group->hasMember(auth()->user()))
                                                @if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin'))
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
                                                @if(!$group->hasAdmin(auth()->user()))
                                                <li>
                                                    <form action="{{ route('groups.leave', $group) }}" method="POST" class="d-inline" id="leaveGroupForm">
                                                        @csrf
                                                        <button type="button" class="dropdown-item text-danger"
                                                                onclick="confirmLeaveGroup()">
                                                            <i class="ph-duotone ph-sign-out me-2"></i>
                                                            {{ __('groups.leave') }}
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                            @else
                                                @if($group->visibility == 'public')
                                                    <li>
                                                        <form action="{{ route('groups.join', $group) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="message" value="">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="ph-duotone ph-plus me-2"></i>
                                                                {{ __('groups.join') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                @else
                                                    <li>
                                                        <form action="{{ route('groups.requests.store', $group) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="ph-duotone ph-hand-waving me-2"></i>
                                                                {{ __('groups.send_request') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
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

    <!-- Statistiche e informazioni -->
    <div class="row mb-4">
        <div class="col-12 col-md-3">
            <div class="card card-light-primary">
                <div class="card-body text-center">
                    <i class="ph-duotone ph-users text-primary f-s-32 mb-2"></i>
                    <h4 class="text-primary mb-1">{{ $group->getMembersCount() }}</h4>
                    <p class="text-muted mb-0">{{ __('groups.total_members') }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card card-light-success">
                <div class="card-body text-center">
                    <i class="ph-duotone ph-crown text-success f-s-32 mb-2"></i>
                    <h4 class="text-success mb-1">{{ $group->getAdmins()->count() }}</h4>
                    <p class="text-muted mb-0">{{ __('groups.admins_count') }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card card-light-info">
                <div class="card-body text-center">
                    <i class="ph-duotone ph-shield-check text-info f-s-32 mb-2"></i>
                    <h4 class="text-info mb-1">{{ $group->getModerators()->count() }}</h4>
                    <p class="text-muted mb-0">{{ __('groups.moderators_count') }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card card-light-warning">
                <div class="card-body text-center">
                    <i class="ph-duotone ph-calendar text-warning f-s-32 mb-2"></i>
                    <h4 class="text-warning mb-1">{{ $group->events()->count() }}</h4>
                    <p class="text-muted mb-0">{{ __('groups.group_events') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenuto principale -->
    <div class="row">
        <!-- Informazioni del gruppo -->
        <div class="col-12 col-lg-8">
            <!-- Eventi del gruppo -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-calendar me-2 text-primary"></i>
                        {{ __('groups.group_events') }}
                    </h5>
                    @if($group->hasMember(auth()->user()))
                    <a href="{{ route('events.create', ['group_id' => $group->id]) }}" class="btn btn-primary btn-sm">
                        <i class="ph-duotone ph-plus me-1"></i>
                        {{ __('groups.create_group_event') }}
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    @forelse($group->events()->latest()->take(5)->get() as $event)
                    <div class="d-flex align-items-center mb-3 p-3 border rounded">
                        <div class="flex-shrink-0">
                            <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 50px; height: 50px;">
                                <i class="ph-duotone ph-calendar text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ $event->title }}</h6>
                            <p class="text-muted mb-1">
                                <i class="ph-duotone ph-calendar me-1"></i>
                                {{ $event->start_datetime->format('d/m/Y H:i') }}
                            </p>
                            <small class="text-muted">
                                <i class="ph-duotone ph-map-pin me-1"></i>
                                {{ $event->location }}
                            </small>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('events.show', $event) }}" class="btn btn-outline-primary btn-sm">
                                {{ __('common.view') }}
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="ph-duotone ph-calendar text-muted f-s-48 mb-3"></i>
                        <p class="text-muted">{{ __('groups.no_group_events') }}</p>
                        @if($group->hasMember(auth()->user()))
                        <a href="{{ route('events.create', ['group_id' => $group->id]) }}" class="btn btn-primary">
                            <i class="ph-duotone ph-plus me-2"></i>
                            {{ __('groups.create_group_event') }}
                        </a>
                        @endif
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Membri recenti -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-users me-2 text-success"></i>
                        {{ __('groups.group_members') }}
                    </h5>
                    <a href="{{ route('groups.members.index', $group) }}" class="btn btn-outline-primary btn-sm">
                        {{ __('common.view_all') }}
                    </a>
                </div>
                <div class="card-body">
                    @forelse($group->members()->with('user')->latest()->take(6)->get() as $member)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            @if($member->user->profile_photo)
                                <img src="{{ $member->user->profile_photo_url }}"
                                     alt="{{ $member->user->getDisplayName() }}"
                                     class="rounded-circle"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px;">
                                    <span class="text-primary fw-bold">
                                        {{ substr($member->user->getDisplayName(), 0, 2) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ $member->user->getDisplayName() }}</h6>
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
                            @if($group->creator->profile_photo)
                                <img src="{{ $group->creator->profile_photo_url }}"
                                     alt="{{ $group->creator->getDisplayName() }}"
                                     class="rounded-circle me-2"
                                     style="width: 30px; height: 30px; object-fit: cover;">
                            @else
                                <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                     style="width: 30px; height: 30px;">
                                    <span class="text-primary fw-bold" style="font-size: 12px;">
                                        {{ substr($group->creator->getDisplayName(), 0, 2) }}
                                    </span>
                                </div>
                            @endif
                            <span>{{ $group->creator->getDisplayName() }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('groups.created_at') }}:</strong>
                        <div class="text-muted">{{ $group->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    @if($group->hasMember(auth()->user()))
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
            @if($group->hasMember(auth()->user()))
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph-duotone ph-lightning me-2 text-warning"></i>
                        {{ __('groups.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('groups.members.index', $group) }}" class="btn btn-outline-primary btn-sm">
                            <i class="ph-duotone ph-users me-2"></i>
                            {{ __('groups.view_members') }}
                        </a>
                        @if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin'))
                        <a href="{{ route('groups.invitations.pending', $group) }}" class="btn btn-outline-success btn-sm">
                            <i class="ph-duotone ph-envelope me-2"></i>
                            {{ __('groups.manage_invitations') }}
                        </a>
                        <a href="{{ route('groups.requests.pending', $group) }}" class="btn btn-outline-info btn-sm">
                            <i class="ph-duotone ph-hand-waving me-2"></i>
                            {{ __('groups.manage_requests') }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Statistiche avanzate -->
            @if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin'))
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
                            <span class="badge bg-warning">{{ $group->getPendingInvitations()->count() }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>{{ __('groups.pending_requests') }}:</span>
                            <span class="badge bg-info">{{ $group->getPendingJoinRequests()->count() }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between">
                            <span>{{ __('groups.group_events') }}:</span>
                            <span class="badge bg-primary">{{ $group->events()->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmLeaveGroup() {
    Swal.fire({
        title: '{{ __("groups.confirm_leave_title") }}',
        text: '{{ __("groups.confirm_leave") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __("groups.leave") }}',
        cancelButtonText: '{{ __("common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('leaveGroupForm').submit();
        }
    });
}
</script>
@endpush
