<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Login - AttendWise</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root[data-theme="light"] {
            --bg: #ffffff;
            --text-main: #111827;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --subtle-bg: #f9fafb;
            --primary: #6366f1;
            --primary-hover: #4f46e5;
        }

        :root[data-theme="dark"] {
            --bg: #000000;
            --text-main: #f9fafb;
            --text-muted: #888888;
            --border: #222222;
            --subtle-bg: #0a0a0a;
            --primary: #6366f1;
            --primary-hover: #4f46e5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--bg);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            transition: background 0.3s, color 0.3s;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 2rem;
        }

        .login-card {
            background: var(--bg);
            border: 1px solid var(--border);
            padding: 3rem 2.5rem;
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo {
            width: 56px;
            height: 56px;
            background: var(--primary);
            color: var(--bg);
            border-radius: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        h1 { font-size: 1.5rem; font-weight: 800; letter-spacing: -0.04em; margin-bottom: 0.5rem; }
        p { color: var(--text-muted); font-size: 0.9rem; font-weight: 500; }

        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em; }
        
        input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: var(--subtle-bg);
            border: 1px solid var(--border);
            border-radius: 0.6rem;
            color: var(--text-main);
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        input:focus {
            outline: none;
            border-color: var(--text-main);
            background: var(--bg);
        }

        .btn-login {
            width: 100%;
            padding: 0.8rem;
            background: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 0.6rem;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 1rem;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-login:hover { background: var(--primary-hover); }

        .error-msg {
            color: var(--text-main);
            font-size: 0.75rem;
            margin-top: 0.5rem;
            font-weight: 700;
        }

        /* Float Theme Toggle */
        .theme-float {
            position: fixed;
            top: 2rem;
            right: 2rem;
            border: 1px solid var(--border);
            padding: 0.5rem;
            border-radius: 0.75rem;
            background: var(--subtle-bg);
            cursor: pointer;
            color: var(--text-main);
        }
    </style>

    <button class="theme-float" id="theme-toggle">
        <svg class="sun-icon" style="display:none" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
        <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
    </button>

    <div class="login-container">
        <div class="login-card">
            <div class="header">
                <div class="logo" style="background: transparent; box-shadow: none;">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="AttendWise" style="height: 64px; width: auto;">
                </div>
                <h1>Faculty Access</h1>
                <p>Welcome to AttendWise Institution Portal</p>
            </div>

            <form action="{{ route('faculty.login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Professional Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@college.edu">
                    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <label style="margin: 0;">Secret Password</label>
                        <a href="#" style="font-size: 0.75rem; color: var(--text-muted); text-decoration: none; font-weight: 500;">Forgot Code?</a>
                    </div>
                    <input type="password" name="password" required placeholder="••••••••">
                    @error('password') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                
                <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; margin: 0; cursor: pointer; text-transform: none; color: var(--text-muted); font-size: 0.75rem;">
                        <input type="checkbox" name="remember" style="width: auto;">
                        <span>Keep me logged in</span>
                    </label>
                </div>

                <button type="submit" class="btn-login">Open Portal</button>
            </form>
        </div>
        <div style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.75rem; font-weight: 500;">
            Institutional Authentication System • AttendWise Corp
        </div>
    </div>

    <script>

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
