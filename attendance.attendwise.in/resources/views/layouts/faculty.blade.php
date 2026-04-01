<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Portal - AttendWise</title>
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
            --accent: #2563eb;
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
            --accent: #3b82f6;
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
            color: #ef4444;
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
            background: rgba(239, 68, 68, 0.05);
            border-color: rgba(239, 68, 68, 0.1);
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
            /* max-width: 1200px; */
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

        /* MNC Style Cards */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 1.5rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            border-color: var(--text-muted);
        }

        .btn-primary {
            background: var(--primary);
            color: var(--bg);
            padding: 0.5rem 1rem;
            border-radius: 0.4rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: opacity 0.2s;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .main-content, .top-bar { margin-left: 0; left: 0; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="logo-area">
                <img src="{{ asset('assets/images/logo.png') }}" alt="AttendWise" style="height: 32px; width: auto;">
                AttendWise
            </div>
            <nav class="nav-links">
                <a href="{{ route('faculty.dashboard') }}" class="nav-item {{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">
                    <i data-lucide="home"></i>
                    Overview
                </a>
                <a href="{{ route('faculty.attendance') }}" class="nav-item {{ request()->routeIs('faculty.attendance') ? 'active' : '' }}">
                    <i data-lucide="check-circle"></i>
                    Attendance
                </a>
                <a href="{{ route('faculty.timetable') }}" class="nav-item {{ request()->routeIs('faculty.timetable') ? 'active' : '' }}">
                    <i data-lucide="calendar"></i>
                    Schedules
                </a>
                <a href="{{ route('faculty.events') }}" class="nav-item {{ request()->routeIs('faculty.events') ? 'active' : '' }}">
                    <i data-lucide="zap"></i>
                    Campus Events
                </a>
                <a href="{{ route('faculty.leave') }}" class="nav-item {{ request()->routeIs('faculty.leave') ? 'active' : '' }}">
                    <i data-lucide="message-square"></i>
                    Leave Requests
                </a>
                <div style="margin: 1rem 0 0.5rem 1.5rem; font-size: 0.7rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Account</div>
                <a href="{{ route('faculty.profile') }}" class="nav-item {{ request()->routeIs('faculty.profile') ? 'active' : '' }}">
                    <i data-lucide="user"></i>
                    My Profile
                </a>
            </nav>
            <div class="sidebar-footer">
                <form action="{{ route('faculty.logout') }}" method="POST">
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
                <div class="breadcrumb" style="font-size: 0.85rem; color: var(--text-muted);">
                    Faculty Portal / <span style="color: var(--text-main)">@yield('header-title', 'Overview')</span>
                </div>
                <div class="controls">
                    <button class="theme-toggle" id="theme-toggle" title="Toggle Theme">
                        <i data-lucide="sun" class="sun-icon" style="display:none"></i>
                        <i data-lucide="moon" class="moon-icon"></i>
                    </button>
                    <div class="user-pill">
                        <span>{{ $faculty->name ?? 'Faculty' }}</span>
                        <div class="user-avatar-small">
                            {{ substr($faculty->name ?? 'F', 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-inner">
                @if(session('success'))
                <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="check-circle" style="width: 18px;"></i>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="alert-circle" style="width: 18px;"></i>
                    {{ session('error') }}
                </div>
                @endif

                <div class="header-section">
                    <h1>@yield('header-title', 'Overview')</h1>
                    <p>@yield('header-subtitle', 'Track your performance and manage class schedules.')</p>
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
    </script>
</body>
</html>
