console.log('[DEBUG] Inizio Echo');

import Echo from 'laravel-echo';
console.log('[DEBUG] Echo importato');

import Pusher from 'pusher-js';
console.log('[DEBUG] Pusher importato');

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: import.meta.env.VITE_PUSHER_FORCE_TLS === 'true',
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
});


console.log('[DEBUG] Echo istanziato:', window.Echo);
console.log('[DEBUG] Configurazione:', {
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: import.meta.env.VITE_PUSHER_FORCE_TLS
});


