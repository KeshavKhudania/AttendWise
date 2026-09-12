import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Determine configuration from window.__ECHO_CONFIG__ (Blade runtime) or Vite import.meta.env
const runtimeConfig = window.__ECHO_CONFIG__ || {};
const broadcaster = (
    runtimeConfig.broadcaster ||
    import.meta.env.VITE_BROADCAST_CONNECTION ||
    import.meta.env.VITE_BROADCAST_DRIVER ||
    'reverb'
).toLowerCase();

// Robust fallback endpoint detection for subpaths (e.g. /attendwise/.../public)
const getFallbackAuthEndpoint = () => {
    if (typeof window !== 'undefined' && window.location?.pathname) {
        const path = window.location.pathname;
        const publicIndex = path.indexOf('/public');
        if (publicIndex !== -1) {
            return path.substring(0, publicIndex + 7) + '/broadcasting/auth';
        }
    }
    return '/broadcasting/auth';
};

const authEndpoint = runtimeConfig.authEndpoint ||
    document.querySelector('meta[name="broadcasting-auth"]')?.getAttribute('content') ||
    getFallbackAuthEndpoint();

const csrfToken = runtimeConfig.csrfToken ||
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
    (typeof window !== 'undefined' && window.Laravel?.csrfToken ? window.Laravel.csrfToken : null);

const authHeaders = {
    ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
    'X-Requested-With': 'XMLHttpRequest',
};

let echoOptions = {
    csrfToken: csrfToken,
    authEndpoint: authEndpoint,
    channelAuthorization: {
        endpoint: authEndpoint,
        transport: 'ajax',
        headers: authHeaders,
    },
    userAuthentication: {
        endpoint: authEndpoint.replace(/\/auth$/, '/user-auth'),
        transport: 'ajax',
        headers: authHeaders,
    },
    auth: {
        headers: authHeaders,
    },
};

if (broadcaster === 'pusher') {
    const pusherConfig = runtimeConfig.pusher || {};
    const key = pusherConfig.key || import.meta.env.VITE_PUSHER_APP_KEY;
    const cluster = pusherConfig.cluster || import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1';
    const host = pusherConfig.host || import.meta.env.VITE_PUSHER_HOST;
    const port = pusherConfig.port ?? (import.meta.env.VITE_PUSHER_PORT ? parseInt(import.meta.env.VITE_PUSHER_PORT) : 443);
    const forceTLS = pusherConfig.forceTLS !== undefined
        ? Boolean(pusherConfig.forceTLS)
        : ((import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https');

    echoOptions = {
        ...echoOptions,
        broadcaster: 'pusher',
        key: key,
        cluster: cluster,
        forceTLS: forceTLS,
    };

    // If a custom host is configured (e.g. self-hosted Soketi/Pusher proxy)
    if (host) {
        echoOptions.wsHost = host;
        echoOptions.wsPort = port;
        echoOptions.wssPort = port;
        echoOptions.enabledTransports = ['ws', 'wss'];
    }
} else if (broadcaster === 'reverb') {
    const reverbConfig = runtimeConfig.reverb || {};
    const key = reverbConfig.key || import.meta.env.VITE_REVERB_APP_KEY;
    const host = reverbConfig.host || import.meta.env.VITE_REVERB_HOST || 'localhost';
    const port = reverbConfig.port ?? (import.meta.env.VITE_REVERB_PORT ? parseInt(import.meta.env.VITE_REVERB_PORT) : 8080);
    const forceTLS = reverbConfig.forceTLS !== undefined
        ? Boolean(reverbConfig.forceTLS)
        : ((import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https');

    echoOptions = {
        ...echoOptions,
        broadcaster: 'reverb',
        key: key,
        wsHost: host,
        wsPort: port,
        wssPort: port,
        forceTLS: forceTLS,
        enabledTransports: ['ws', 'wss'],
    };
} else {
    echoOptions = {
        ...echoOptions,
        broadcaster: broadcaster,
    };
}

if (echoOptions.key) {
    window.Echo = new Echo(echoOptions);
} else {
    console.warn(`[AttendWise] WebSocket broadcaster is set to "${broadcaster}", but no valid key is provided in the configuration.`);
}
