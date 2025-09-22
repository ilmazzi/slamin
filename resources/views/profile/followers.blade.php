@extends('layout.master')

@section('title', __('profile.followers') . ' - ' . $user->getDisplayName())

@section('main-content')
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">{{ __('profile.followers') }} - {{ $user->getDisplayName() }}</h4>

            </div>
        </div>

        <!-- Statistiche -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h4 class="text-success">{{ $followers->total() }}</h4>
                                <p class="text-muted">{{ __('profile.followers') }}</p>
                            </div>
                            <div class="col-md-4">
                                <h4 class="text-primary">{{ $user->following_count }}</h4>
                                <p class="text-muted">{{ __('profile.following') }}</p>
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

        <!-- Lista Followers -->
        <div class="row">
            @forelse($followers as $follower)
                <x-user-card
                    :user="$follower"
                    card-class="col-12 col-sm-6 col-lg-4 mb-4"
                    :show-follow-button="true"
                    :show-message-button="true" />
            @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph-duotone ph-users f-s-48 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('profile.no_followers') }}</h5>
                        <p class="text-muted">{{ __('profile.no_followers_message') }}</p>
                        @auth
                        @if(!$isOwnProfile)
                        <button type="button" class="btn btn-primary" onclick="followUser({{ $user->id }})">
                            <i class="ph-duotone ph-user-plus me-2"></i>
                            Segui {{ $user->getDisplayName() }}
                        </button>
                        @endif
                        @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="ph-duotone ph-sign-in me-2"></i>
                            Accedi per seguire
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Paginazione -->
        @if($followers->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $followers->appends(request()->query())->links() }}
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

function startChat(userId) {
    // Verifica se l'utente è autenticato
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    if (!isAuthenticated) {
        window.location.href = '{{ route("login") }}';
        return;
    }

    // Disabilita i pulsanti durante la richiesta
    const messageButton = document.getElementById('messageBtn' + userId);

    if (messageButton) messageButton.disabled = true;

    // Mostra loading
    Swal.fire({
        title: 'Creazione chat...',
        text: 'Sto creando la chat con questo utente',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Crea o accede alla chat privata
    fetch('{{ route("chat.create-private", ":userId") }}'.replace(':userId', userId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reindirizza alla chat
            window.location.href = '{{ route("chat.index") }}';
        } else {
            Swal.fire('Errore', data.message || 'Errore durante la creazione della chat', 'error');
        }
    })
    .catch(error => {
        console.error('Errore creazione chat:', error);
        Swal.fire('Errore', 'Errore durante la creazione della chat', 'error');
    })
    .finally(() => {
        // Riabilita i pulsanti
        if (messageButton) messageButton.disabled = false;
    });
}
</script>
@endpush
@endsection

<x-user-card-scripts />
