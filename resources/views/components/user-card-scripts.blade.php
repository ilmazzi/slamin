@push('scripts')
<script>
// Funzione per seguire un utente
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
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-success');
                } else {
                    button.innerHTML = '<i class="ti ti-user me-1"></i><span id="followText' + userId + '">{{ __("profile.follow_label") }}</span>';
                    button.classList.remove('btn-success');
                    button.classList.add('btn-primary');
                }

                // Mostra notifica
                Swal.fire({
                    icon: 'success',
                    title: 'Successo!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Errore', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Errore connessione follow:', error);
            Swal.fire('Errore', 'Errore durante l\'operazione', 'error');
        })
        .finally(() => {
            // Riabilita il pulsante
            button.disabled = false;
        });
}

// Funzione per iniziare una chat
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
