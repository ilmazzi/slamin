import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_REVERB_APP_KEY || 'slamin',
    wsHost: process.env.MIX_REVERB_HOST || window.location.hostname,
    wsPort: process.env.MIX_REVERB_PORT || 8080,
    wssPort: process.env.MIX_REVERB_PORT || 8080,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }
});
