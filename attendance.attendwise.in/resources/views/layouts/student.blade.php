<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AttendWise Student Portal')</title>

    <!-- PWA Web App Meta Tags & App Icons -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta name="theme-color" content="#0f172a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="AttendWise">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/icon-192.png') }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- HTML5 QR Code Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <style>
        :root {
            --bg: #ffffff;
            --subtle-bg: #f5f5f5;
            --border: #e0e0e0;
            --bg-dark: #ffffff;
            --card-bg: #ffffff;
            --card-border: #e0e0e0;
            --accent-primary: #000000;
            --accent-glow: rgba(0, 0, 0, 0.1);
            --accent-gradient: #000000;
            --success-gradient: #333333;
            --danger-gradient: #666666;
            --warning-gradient: #999999;
            --text-main: #000000;
            --text-muted: #666666;
            --nav-height: 72px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(0, 0, 0, 0.03) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(0, 0, 0, 0.04) 0%, transparent 40%);
            background-attachment: fixed;
            padding-bottom: calc(var(--nav-height) + 20px);
        }

        /* Top Bar Header */
        .app-topbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #0f172a;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--accent-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 4px 12px var(--accent-glow);
        }

        .brand-title {
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -0.5px;
            color: #000000;
            background: none;
            -webkit-text-fill-color: initial;
        }

        .offline-badge {
            display: none;
            background: #f5f5f5;
            color: #333333;
            border: 1px solid #cccccc;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            align-items: center;
            gap: 6px;
        }

        .container {
            max-width: 540px;
            width: 100%;
            margin: 0 auto;
            padding: 16px;
            flex: 1;
        }

        /* Light Cards */
        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 18px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        /* PWA Install Banner */
        #pwaInstallBanner {
            display: none;
            background: #fafafa;
            border: 1px solid #dddddd;
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 16px;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .btn-install-pwa {
            background: var(--accent-gradient);
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            box-shadow: 0 4px 14px var(--accent-glow);
            white-space: nowrap;
        }

        /* Bottom PWA Navigation Bar */
        .pwa-navbar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--nav-height);
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-around;
            z-index: 1000;
            max-width: 540px;
            margin: 0 auto;
            border-top-left-radius: 24px;
            border-top-right-radius: 24px;
            box-shadow: 0 -8px 25px rgba(0, 0, 0, 0.06);
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.72rem;
            font-weight: 600;
            transition: all 0.2s ease;
            flex: 1;
            height: 100%;
        }

        .nav-item i {
            font-size: 1.25rem;
            transition: transform 0.2s ease;
        }

        .nav-item.active {
            color: #000000;
        }

        .nav-item.active i {
            transform: translateY(-2px);
            color: #000000;
        }

        /* Floating Scanner Nav Action Button */
        .nav-scan-btn {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--accent-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
            box-shadow: 0 8px 24px var(--accent-glow);
            margin-top: -30px;
            border: 4px solid #ffffff;
            text-decoration: none;
            transition: transform 0.2s ease;
            animation: pulse-glow 2s infinite;
        }

        .nav-scan-btn:active {
            transform: scale(0.92);
        }

        @keyframes pulse-glow {
            0% { box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.2); }
            70% { box-shadow: 0 0 0 14px rgba(0, 0, 0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 0, 0, 0); }
        }

        /* Buttons & Controls */
        .btn-primary {
            background: var(--accent-gradient);
            color: #fff;
            border: none;
            border-radius: 14px;
            padding: 14px 20px;
            font-weight: 700;
            font-size: 0.95rem;
            width: 100%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 6px 20px var(--accent-glow);
            transition: all 0.2s ease;
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2000;
            width: 90%;
            max-width: 440px;
            pointer-events: none;
        }

        .toast-msg {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            color: #fff;
            padding: 14px 18px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: auto;
        }

        .toast-msg.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast-success { border-left: 4px solid #333333; }
        .toast-error { border-left: 4px solid #000000; }
    </style>
    @yield('styles')
    @vite(['resources/js/app.js'])
</head>
<body>

    <!-- Top Bar -->
    <header class="app-topbar">
        <a href="{{ route('student.dashboard') }}" class="brand-logo" style="display: flex; align-items: center; gap: 10px;">
            <img src="{{ asset('assets/images/logo.png') }}" alt="AttendWise Logo" style="height: 32px; width: auto; max-width: 140px; object-fit: contain;">
        </a>
        <div class="offline-badge" id="offlineBadge">
            <i class="fa-solid fa-wifi-slash"></i> Offline Mode
        </div>
        @auth('student')
        <div style="display: flex; align-items: center; gap: 8px;">
            <a href="{{ route('student.profile') }}" style="color: var(--text-muted); font-size: 1.1rem; text-decoration: none;">
                <i class="fa-regular fa-circle-user"></i>
            </a>
        </div>
        @endauth
    </header>

    <!-- Toast Notification Anchor -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Main Content Container -->
    <main class="container">
        <!-- PWA Installation Banner -->
        <div id="pwaInstallBanner">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: #ffffff; display: flex; align-items: center; justify-content: center; padding: 4px; box-shadow: 0 4px 10px rgba(0,0,0,0.2); flex-shrink: 0;">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="AttendWise Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 0.88rem; color: var(--text-main);">Install AttendWise PWA</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Add to home screen for instant access</div>
                </div>
            </div>
            <button class="btn-install-pwa" id="installPwaBtn" onclick="installPWA()">Install</button>
        </div>

        @yield('content')
    </main>

    <!-- Bottom Mobile Navigation Bar -->
    @auth('student')
    <nav class="pwa-navbar">
        <a href="{{ route('student.dashboard') }}" class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('student.timetable') }}" class="nav-item {{ request()->routeIs('student.timetable') ? 'active' : '' }}">
            <i class="fa-regular fa-calendar-check"></i>
            <span>Schedule</span>
        </a>
        
        <!-- Center Floating Camera Scanner -->
        <a href="{{ route('student.scanner') }}" class="nav-scan-btn" title="Scan QR Code">
            <i class="fa-solid fa-qrcode"></i>
        </a>

        <a href="{{ route('student.history') }}" class="nav-item {{ request()->routeIs('student.history') ? 'active' : '' }}">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>History</span>
        </a>
        <a href="{{ route('student.profile') }}" class="nav-item {{ request()->routeIs('student.profile') ? 'active' : '' }}">
            <i class="fa-solid fa-id-card"></i>
            <span>Profile</span>
        </a>
    </nav>
    @endauth

    <script>
        // --- 1. Service Worker Registration ---
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register("{{ asset('sw.js') }}")
                    .then(reg => console.log('[PWA] Service Worker registered:', reg.scope))
                    .catch(err => console.error('[PWA] Service Worker registration failed:', err));
            });
        }

        // --- 2. Offline / Online Detector ---
        function updateOnlineStatus() {
            const badge = document.getElementById('offlineBadge');
            if (badge) {
                badge.style.display = navigator.onLine ? 'none' : 'flex';
            }
        }
        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
        updateOnlineStatus();

        // --- 3. PWA Install Prompt Handler ---
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            const banner = document.getElementById('pwaInstallBanner');
            if (banner && !window.matchMedia('(display-mode: standalone)').matches) {
                banner.style.display = 'flex';
            }
        });

        function installPWA() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('[PWA] User accepted installation');
                    }
                    deferredPrompt = null;
                    const banner = document.getElementById('pwaInstallBanner');
                    if (banner) banner.style.display = 'none';
                });
            }
        }

        // --- 4. Device Identification Persistence ---
        function getOrGenerateDeviceId() {
            let deviceId = localStorage.getItem('attendwise_device_id');
            if (!deviceId) {
                deviceId = 'pwa_' + Math.random().toString(36).substring(2, 15) + '_' + Date.now();
                localStorage.setItem('attendwise_device_id', deviceId);
            }
            return deviceId;
        }

        // --- 5. Global Toast Message Helper ---
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast-msg toast-${type}`;
            const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
            toast.innerHTML = `<i class="fa-solid ${icon}" style="font-size: 1.2rem;"></i> <span>${message}</span>`;
            container.appendChild(toast);

            setTimeout(() => toast.classList.add('show'), 50);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        // --- 6. Global Session Expiration & Fetch Interceptor ---
        const originalFetch = window.fetch;
        window.fetch = async function(...args) {
            const response = await originalFetch(...args);
            if (response.status === 401 || response.status === 419) {
                showToast('Session expired. Redirecting to login...', 'error');
                setTimeout(() => {
                    window.location.href = "{{ route('student.login') }}";
                }, 1000);
            }
            return response;
        };
    </script>
    @yield('scripts')
</body>
</html>
