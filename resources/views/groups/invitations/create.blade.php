@extends('layout.master')

@section('title', __('groups.invite_members') . ' - ' . $group->name)

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-envelope me-2 text-success"></i>
                        {{ __('groups.invite_members') }} - {{ $group->name }}
                    </h4>
                    <a href="{{ route('groups.members.index', $group) }}" class="btn btn-light">
                        <i class="ph-duotone ph-arrow-left me-2"></i>
                        {{ __('common.back') }}
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('groups.invitations.store', $group) }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_search" class="form-label">Cerca Utente *</label>
                                    <div class="position-relative">
                                                                                                                        <input type="text"
                                               class="form-control @error('user_search') is-invalid @enderror"
                                               id="user_search"
                                               placeholder="Cerca per nome, nickname o email..."
                                               autocomplete="off">
                                        <input type="hidden" id="selected_user_id" name="user_id" value="{{ old('user_id') }}">
                                        <div id="search_results" class="position-absolute w-100 bg-white border rounded shadow-sm" style="top: 100%; left: 0; z-index: 1000; display: none; max-height: 200px; overflow-y: auto;"></div>
                                    </div>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Inizia a digitare per cercare utenti. L'utente deve essere già registrato sulla piattaforma.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="message" class="form-label">{{ __('groups.invite_message') }}</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror"
                                              id="message"
                                              name="message"
                                              rows="4"
                                              placeholder="{{ __('groups.invite_message_placeholder') }}">{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('groups.invite_message_help') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="ph-duotone ph-paper-plane me-2"></i>
                                        {{ __('groups.send_invitation') }}
                                    </button>
                                    <a href="{{ route('groups.members.index', $group) }}" class="btn btn-light">
                                        {{ __('common.cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Informazioni aggiuntive -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-light-info">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph-duotone ph-info me-2 text-info"></i>
                        {{ __('groups.invitation_info') }}
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>{{ __('groups.invitation_info_1') }}</li>
                        <li>{{ __('groups.invitation_info_2') }}</li>
                        <li>{{ __('groups.invitation_info_3') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('user_search');
    const searchResults = document.getElementById('search_results');
    const selectedUserId = document.getElementById('selected_user_id');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        clearTimeout(searchTimeout);

        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`/groups/{{ $group->id }}/members/search?search=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data);
                })
                .catch(error => {
                    console.error('Errore nella ricerca:', error);
                });
        }, 300);
    });

    function displaySearchResults(users) {
        if (users.length === 0) {
            searchResults.innerHTML = '<div class="p-3 text-muted">Nessun utente trovato</div>';
        } else {
                        searchResults.innerHTML = users.map(user => `
                <div class="search-result-item p-2 border-bottom cursor-pointer hover-bg-light"
                     data-user-id="${user.id}"
                     data-user-name="${user.name}">
                    <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                            <img src="${user.avatar_url}"
                                 class="rounded-circle"
                                 width="32"
                                 height="32"
                                 alt="${user.name}"
                                 onerror="this.src='{{ asset('assets/images/avatar/default-avatar.webp') }}'">
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-medium">${user.name}</div>
                            <small class="text-muted">
                                ${user.nickname ? `@${user.nickname} • ` : ''}${user.email}
                            </small>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        searchResults.style.display = 'block';
    }

    // Gestione click sui risultati
    searchResults.addEventListener('click', function(e) {
        const resultItem = e.target.closest('.search-result-item');
        if (resultItem) {
            const userId = resultItem.dataset.userId;
            const userName = resultItem.dataset.userName;

            selectedUserId.value = userId;
            searchInput.value = userName;
            searchResults.style.display = 'none';
        }
    });

    // Nascondi risultati quando si clicca fuori
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    // Validazione form
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!selectedUserId.value) {
            e.preventDefault();
            Swal.fire('{{ __("common.attention") }}', '{{ __("groups.select_user_from_search") }}', 'warning');
            return false;
        }
    });
});
</script>

<style>
.search-result-item:hover {
    background-color: #f8f9fa;
}

.cursor-pointer {
    cursor: pointer;
}
</style>
@endsection
