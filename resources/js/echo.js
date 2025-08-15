import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: process.env.MIX_REVERB_APP_KEY || 'tfpylhqswaob1atg660b',
    wsHost: process.env.MIX_REVERB_HOST || 'localhost',
    wsPort: process.env.MIX_REVERB_PORT || 8080,
    wssPort: process.env.MIX_REVERB_PORT || 8080,
    forceTLS: (process.env.MIX_REVERB_SCHEME || 'http') === 'https',
    enabledTransports: ['ws', 'wss'],
});
