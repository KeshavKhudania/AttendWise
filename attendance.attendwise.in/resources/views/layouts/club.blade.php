<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#000000">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Club Portal - AttendWise</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root[data-theme="light"] {
            --bg: #ffffff;
            --sidebar-bg: #f9fafb;
            --text-main: #111827;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --card-bg: #ffffff;
            --primary: #000000;
            --primary-glow: rgba(0, 0, 0, 0.05);
            --accent: #333333;
            --hover-bg: #f3f4f6;
            --sidebar-active: #ffffff;
            --subtle-bg: #f9fafb;
        }

        :root[data-theme="dark"] {
            --bg: #000000;
            --sidebar-bg: #000000;
            --text-main: #f9fafb;
            --text-muted: #888888;
            --border: #222222;
            --card-bg: #000000;
            --primary: #ffffff;
            --primary-glow: rgba(255, 255, 255, 0.1);
            --accent: #cccccc;
            --hover-bg: #111111;
            --sidebar-active: #111111;
            --subtle-bg: #0a0a0a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, sans-serif;
        }

        body {
            background: var(--bg);
            color: var(--text-main);
            overflow-x: hidden;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 50;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .logo-area {
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-main);
        }

        .nav-links {
            flex: 1;
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.75rem;
            text-decoration: none;
            color: var(--text-muted);
            border-radius: 0.4rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item:hover {
            background: var(--hover-bg);
            color: var(--text-main);
        }

        .nav-item.active {
            background: var(--sidebar-active);
            color: var(--text-main);
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            border: 1px solid var(--border);
        }

        .nav-item i {
            width: 18px;
            height: 18px;
            stroke-width: 2px;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid var(--border);
        }

        .logout-btn {
            width: 100%;
            padding: 0.6rem;
            background: transparent;
            color: var(--text-main);
            border: 1px solid transparent;
            border-radius: 0.4rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background: var(--hover-bg);
            border-color: var(--border);
        }

        /* Top Bar */
        .top-bar {
            position: sticky;
            top: 0;
            right: 0;
            left: 260px;
            height: 64px;
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            z-index: 40;
            backdrop-filter: blur(8px);
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            min-height: 100vh;
            background: var(--bg);
            position: relative;
        }

        .content-inner {
            padding: 2.5rem;
            margin: 0 auto;
        }

        .header-section {
            margin-bottom: 3rem;
        }

        .header-section h1 {
            font-size: 1.875rem;
            font-weight: 700;
            letter-spacing: -0.04em;
            margin-bottom: 0.5rem;
        }

        .header-section p {
            color: var(--text-muted);
            font-size: 1rem;
        }

        /* Controls */
        .controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .theme-toggle {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-main);
            width: 36px;
            height: 36px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .theme-toggle:hover {
            background: var(--hover-bg);
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.35rem 0.5rem 0.35rem 1rem;
            background: var(--subtle-bg);
            border: 1px solid var(--border);
            border-radius: 2rem;
            color: var(--text-main);
            font-size: 0.85rem;
            font-weight: 500;
        }

        .user-avatar-small {
            width: 28px;
            height: 28px;
            background: var(--text-main);
            color: var(--bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.75rem;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 45;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .menu-toggle {
            display: none;
            background: transparent;
            border: none;
            color: var(--text-main);
            cursor: pointer;
            padding: 0.5rem;
            margin-right: 1rem;
        }

        @media (max-width: 1024px) {
            .sidebar { 
                transform: translateX(-100%);
                box-shadow: 4px 0 24px rgba(0,0,0,0.1); 
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content, .top-bar { margin-left: 0; left: 0; }
            .menu-toggle { display: block; }
            .breadcrumb { display: none; }
            .top-bar { padding: 0 1rem; }
            .content-inner { padding: 1.5rem 1rem; }
        }
    </style>
    @yield('styles')
    @vite(['resources/js/app.js'])
</head>
<body>
    <div class="sidebar-overlay" id="sidebar-overlay"></div>
    <div class="layout">
        <aside class="sidebar" id="sidebar">
            <div class="logo-area">
                <img src="{{ asset('assets/images/logo.png') }}" alt="AttendWise" style="height: 32px; width: auto;">
                Club Panel
            </div>
            <nav class="nav-links">
                <a href="{{ route('club.dashboard') }}" class="nav-item {{ request()->routeIs('club.dashboard') ? 'active' : '' }}">
                    <i data-lucide="home"></i>
                    Dashboard
                </a>
                <a href="{{ route('club.members') }}" class="nav-item {{ request()->routeIs('club.members') ? 'active' : '' }}">
                    <i data-lucide="users"></i>
                    Members
                </a>
                <a href="{{ route('club.groups') }}" class="nav-item {{ request()->routeIs('club.groups') ? 'active' : '' }}">
                    <i data-lucide="shield"></i>
                    User Groups
                </a>
                <a href="{{ route('club.events') }}" class="nav-item {{ request()->routeIs('club.events') ? 'active' : '' }}">
                    <i data-lucide="calendar"></i>
                    Events
                </a>
                
                @if(Auth::guard('club')->user()->role === 'admin' || Auth::guard('club')->user()->hasPermission('attendance.view') || Auth::guard('club')->user()->hasPermission('attendance.take'))
                <a href="{{ route('club.attendance') }}" class="nav-item {{ request()->routeIs('club.attendance*') ? 'active' : '' }}">
                    <i data-lucide="clipboard-check"></i>
                    Attendance
                </a>
                @endif
                @if(Auth::guard('club')->user()->role === 'admin')
                <div style="margin: 1rem 0 0.5rem 1.5rem; font-size: 0.7rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Settings</div>
                <a href="#" class="nav-item">
                    <i data-lucide="settings"></i>
                    Club Profile
                </a>
                @endif
            </nav>
            <div class="sidebar-footer">
                <form action="{{ route('club.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i data-lucide="log-out"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <div style="display: flex; align-items: center;">
                    <button class="menu-toggle" id="menu-toggle">
                        <i data-lucide="menu"></i>
                    </button>
                    <div class="breadcrumb" style="font-size: 0.85rem; color: var(--text-muted); display: flex; align-items: center; gap: 1rem;">
                        <div>
                            Club Portal / <span style="color: var(--text-main)">@yield('header-title', 'Overview')</span>
                        </div>
                        <div>
                            @yield('header-actions')
                        </div>
                    </div>
                </div>
                <div class="controls">
                    <button class="theme-toggle" id="theme-toggle" title="Toggle Theme">
                        <i data-lucide="sun" class="sun-icon" style="display:none"></i>
                        <i data-lucide="moon" class="moon-icon"></i>
                    </button>
                    <div class="user-pill" style="display: flex; flex-direction: column; align-items: flex-end; padding: 4px 12px; gap: 0;">
                        <span style="font-size: 0.85rem; font-weight: 600;">{{ Auth::guard('club')->user()->name ?? 'Manager' }}</span>
                        <span style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase;">{{ str_replace('_', ' ', Auth::guard('club')->user()->role ?? 'admin') }} - {{ Auth::guard('club')->user()->club->name ?? 'Club' }}</span>
                    </div>
                </div>
            </div>

            <div class="content-inner">
                @if(session('success'))
                <div style="background: var(--subtle-bg); border: 1px solid var(--border); color: var(--text-main); padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="check-circle" style="width: 18px;"></i>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div style="background: var(--subtle-bg); border: 1px solid var(--border); color: var(--text-main); padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="alert-circle" style="width: 18px;"></i>
                    {{ session('error') }}
                </div>
                @endif

                <div class="header-section">
                    <h1>@yield('header-title', 'Overview')</h1>
                    <p>@yield('header-subtitle', 'Manage your club.')</p>
                </div>
                
                <div class="content">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();

        // Theme Toggle Logic
        const themeToggle = document.getElementById('theme-toggle');
        const sunIcon = document.querySelector('.sun-icon');
        const moonIcon = document.querySelector('.moon-icon');
        const root = document.documentElement;

        function setTheme(theme) {
            root.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            if (theme === 'light') {
                sunIcon.style.display = 'block';
                moonIcon.style.display = 'none';
            } else {
                sunIcon.style.display = 'none';
                moonIcon.style.display = 'block';
            }
        }

        const savedTheme = localStorage.getItem('theme') || 'dark';
        setTheme(savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = root.getAttribute('data-theme');
            setTheme(currentTheme === 'dark' ? 'light' : 'dark');
        });

        // Mobile Sidebar Toggle
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
        }

        menuToggle.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking a link on mobile
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', () => {
                if(window.innerWidth <= 1024) {
                    toggleSidebar();
                }
            });
        });

        const originalFetch = window.fetch;
        window.fetch = async function(...args) {
            const response = await originalFetch(...args);
            if (response.status === 401 || response.status === 419) {
                window.location.href = "{{ route('club.login') }}";
            }
            return response;
        };
    </script>
    @yield('scripts')
</body>
</html>
