@extends('layout.master')

@section('title', __('profile.following') . ' - ' . $user->getDisplayName())

@section('main-content')
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">{{ __('profile.following') }} - {{ $user->getDisplayName() }}</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('home') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> {{ __('dashboard.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('user.show', $user) }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-user f-s-16"></i> {{ $user->getDisplayName() }}
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">{{ __('profile.following') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Statistiche -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h4 class="text-primary">{{ $following->total() }}</h4>
                                <p class="text-muted">{{ __('profile.following') }}</p>
                            </div>
                            <div class="col-md-4">
                                <h4 class="text-success">{{ $user->followers_count }}</h4>
                                <p class="text-muted">{{ __('profile.followers') }}</p>
                            </div>
                            <div class="col-md-4">
                                <h4 class="text-info">{{ $user->videos_count + $user->photos_count + $user->poems_count }}</h4>
                                <p class="text-muted">{{ __('profile.content') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista Following -->
        <div class="row">
            @forelse($following as $followedUser)
            <div class="col-12 col-sm-6 col-lg-4 mb-4">
                <div class="card hover-effect h-100">
                    <div class="card-body text-center">
                        <!-- Avatar -->
                        <div class="mb-3">
                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($followedUser) }}"
                                 alt="{{ $followedUser->getDisplayName() }}"
                                 class="rounded-circle"
                                 width="80"
                                 height="80"
                                 style="object-fit: cover;">
                        </div>

                        <!-- Nome e Info -->
                        <h5 class="card-title f-w-600 mb-2">
                            <a href="{{ route('user.show', $followedUser) }}" class="text-decoration-none">
                                {{ $followedUser->getDisplayName() }}
                            </a>
                        </h5>

                        @if($followedUser->nickname && $followedUser->nickname !== $followedUser->name)
                        <p class="text-muted f-s-14 mb-2">{{ $followedUser->nickname }}</p>
                        @endif

                        <!-- Statistiche -->
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="f-s-12 text-muted">{{ __('profile.videos_label') }}</div>
                                <div class="fw-bold">{{ $followedUser->videos_count }}</div>
                            </div>
                            <div class="col-4">
                                <div class="f-s-12 text-muted">{{ __('profile.photos_label') }}</div>
                                <div class="fw-bold">{{ $followedUser->photos_count }}</div>
                            </div>
                            <div class="col-4">
                                <div class="f-s-12 text-muted">{{ __('profile.poems_label') }}</div>
                                <div class="fw-bold">{{ $followedUser->poems_count }}</div>
                            </div>
                        </div>

                        <!-- Azioni -->
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('user.show', $followedUser) }}" class="btn btn-primary btn-sm">
                                <i class="ph-duotone ph-user me-1"></i>
                                Profilo
                            </a>
                            @auth
                            <button type="button"
                                    class="btn {{ $followedUser->is_followed_by_current_user ?? false ? 'btn-success' : 'btn-outline-primary' }} btn-sm"
                                    onclick="followUser({{ $followedUser->id }})"
                                    id="followBtn{{ $followedUser->id }}">
                                <i class="ti {{ $followedUser->is_followed_by_current_user ?? false ? 'ti-user-check' : 'ti-user' }} me-1"></i>
                                <span id="followText{{ $followedUser->id }}">
                                    {{ $followedUser->is_followed_by_current_user ?? false ? __('profile.following_label') : __('profile.follow_label') }}
                                </span>
                            </button>
                            @else
                            <div class="btn btn-outline-secondary btn-sm" style="opacity: 0.6;">
                                <i class="ti ti-user me-1"></i>
                                Follow
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph-duotone ph-users f-s-48 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('profile.no_following') }}</h5>
                        <p class="text-muted">{{ __('profile.no_following_message') }}</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="ph-duotone ph-house me-2"></i>
                            {{ __('profile.explore_platform') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Paginazione -->
        @if($following->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $following->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function followUser(userId) {
    // Verifica se l'utente è autenticato
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    if (!isAuthenticated) {
        window.location.href = '{{ route("login") }}';
        return;
    }

    const button = document.getElementById('followBtn' + userId);
    const text = document.getElementById('followText' + userId);

    // Disabilita il pulsante durante la richiesta
    button.disabled = true;

    fetch('/api/follow/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            user_id: userId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Aggiorna il pulsante
            if (data.following) {
                button.innerHTML = '<i class="ti ti-user-check me-1"></i><span id="followText' + userId + '">{{ __("profile.following_label") }}</span>';
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-success');
            } else {
                button.innerHTML = '<i class="ti ti-user me-1"></i><span id="followText' + userId + '">{{ __("profile.follow_label") }}</span>';
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-primary');
            }

            // Mostra notifica
            Swal.fire({
                icon: 'success',
                title: '{{ __("profile.success") }}',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            Swal.fire('{{ __("profile.error") }}', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Errore connessione follow:', error);
        Swal.fire('{{ __("profile.error") }}', '{{ __("profile.operation_error") }}', 'error');
    })
    .finally(() => {
        button.disabled = false;
    });
}
</script>
@endpush
@endsection
