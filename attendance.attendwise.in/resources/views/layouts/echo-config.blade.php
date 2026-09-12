<meta name="broadcasting-auth" content="{{ url('/broadcasting/auth') }}">
<script>
    window.__ECHO_CONFIG__ = {
        broadcaster: @json(config('broadcasting.default', env('BROADCAST_CONNECTION', 'reverb'))),
        authEndpoint: @json(url('/broadcasting/auth')),
        csrfToken: @json(csrf_token()),
        reverb: {
            key: @json(config('broadcasting.connections.reverb.key')),
            host: @json(config('broadcasting.connections.reverb.options.host', 'localhost')),
            port: @json((int) (config('broadcasting.connections.reverb.options.port') ?: 8080)),
            scheme: @json(config('broadcasting.connections.reverb.options.scheme') ?: 'http'),
            forceTLS: @json(config('broadcasting.connections.reverb.options.useTLS') ?? false),
        },
        pusher: {
            key: @json(config('broadcasting.connections.pusher.key')),
            cluster: @json(config('broadcasting.connections.pusher.options.cluster') ?: 'mt1'),
            host: @json(env('PUSHER_HOST') ?: null),
            port: @json((int) (config('broadcasting.connections.pusher.options.port') ?: 443)),
            scheme: @json(config('broadcasting.connections.pusher.options.scheme') ?: 'https'),
            forceTLS: @json(config('broadcasting.connections.pusher.options.useTLS') ?? true),
        }
    };
</script>

