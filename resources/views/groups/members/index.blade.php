@extends('layout.master')

@section('title', __('groups.group_members') . ' - ' . $group->name)

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="ph-duotone ph-users me-2 text-primary"></i>
                            {{ __('groups.group_members') }}
                        </h4>
                        <p class="text-muted mb-0">{{ $group->name }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        @if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin'))
                        <a href="{{ route('groups.invitations.create', $group) }}" class="btn btn-success">
                            <i class="ph-duotone ph-plus me-2"></i>
                            {{ __('groups.invite') }}
                        </a>
                        @endif
                        <a href="{{ route('groups.show', $group) }}" class="btn btn-light">
                            <i class="ph-duotone ph-arrow-left me-2"></i>
                            {{ __('common.back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche membri -->
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
            <div class="card card-light-secondary">
                <div class="card-body text-center">
                    <i class="ph-duotone ph-user text-secondary f-s-32 mb-2"></i>
                    <h4 class="text-secondary mb-1">{{ $group->members()->where('role', 'member')->count() }}</h4>
                    <p class="text-muted mb-0">{{ __('groups.members_count_label') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista membri -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('groups.group_members') }}</h5>
                </div>
                <div class="card-body">
                    @forelse($members as $member)
                    <div class="d-flex align-items-center mb-3 p-3 border rounded">
                        <div class="flex-shrink-0">
                            @if($member->user->profile_photo)
                                <img src="{{ $member->user->profile_photo_url }}" 
                                     alt="{{ $member->user->getDisplayName() }}" 
                                     class="rounded-circle" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px;">
                                    <span class="text-primary fw-bold">
                                        {{ substr($member->user->getDisplayName(), 0, 2) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $member->user->getDisplayName() }}</h6>
                                    <p class="text-muted mb-1">{{ $member->user->getPrivacySafeIdentifier() }}</p>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-{{ $member->role == 'admin' ? 'success' : ($member->role == 'moderator' ? 'info' : 'secondary') }}">
                                            {{ __('groups.role_' . $member->role) }}
                                        </span>
                                        <small class="text-muted">
                                            {{ __('groups.member_since') }} {{ $member->joined_at->format('d/m/Y') }}
                                        </small>
                                        @if($member->invited_by)
                                            <small class="text-muted">
                                                {{ __('groups.invited_by') }} {{ $member->invitedBy->getDisplayName() }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    @if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin'))
                                        @if($member->user_id !== auth()->id())
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="ph-duotone ph-dots-three-outline"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if($member->role !== 'admin')
                                                <li>
                                                    <form action="{{ route('groups.members.promote', [$group, $member]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="ph-duotone ph-crown me-2"></i>
                                                            {{ __('groups.promote') }} {{ __('groups.role_admin') }}
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                                @if($member->role === 'member')
                                                <li>
                                                    <form action="{{ route('groups.members.promote-moderator', [$group, $member]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="ph-duotone ph-shield-check me-2"></i>
                                                            {{ __('groups.promote') }} {{ __('groups.role_moderator') }}
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                                @if($member->role === 'admin')
                                                <li>
                                                    <form action="{{ route('groups.members.demote', [$group, $member]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="ph-duotone ph-arrow-down me-2"></i>
                                                            {{ __('groups.demote') }} {{ __('groups.role_moderator') }}
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                                @if($member->role === 'moderator')
                                                <li>
                                                    <form action="{{ route('groups.members.demote-member', [$group, $member]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="ph-duotone ph-arrow-down me-2"></i>
                                                            {{ __('groups.demote') }} {{ __('groups.role_member') }}
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('groups.members.remove', [$group, $member]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-danger" 
                                                                onclick="return confirm('{{ __('groups.confirm_remove_member') }}')">
                                                            <i class="ph-duotone ph-trash me-2"></i>
                                                            {{ __('groups.remove') }}
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                        @else
                                        <span class="badge bg-primary">{{ __('groups.you') }}</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="ph-duotone ph-users text-muted f-s-64 mb-3"></i>
                        <h5 class="text-muted">{{ __('groups.no_members') }}</h5>
                        @if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin'))
                        <p class="text-muted mb-3">{{ __('groups.invite_first_member') }}</p>
                        <a href="{{ route('groups.invitations.create', $group) }}" class="btn btn-primary">
                            <i class="ph-duotone ph-plus me-2"></i>
                            {{ __('groups.invite') }}
                        </a>
                        @endif
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Paginazione -->
    @if($members->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ $members->links() }}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 