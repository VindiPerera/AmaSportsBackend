<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Portal') — AmaX</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', sans-serif; }

        .nav-link {
            display: inline-flex; align-items: center; gap: 0.375rem;
            padding: 0.5rem 0.875rem; border-radius: 0.5rem;
            font-size: 0.875rem; font-weight: 700;
            color: rgba(255,255,255,0.75); text-decoration: none;
            transition: all 0.15s ease;
        }
        .nav-link:hover { color: #fff; background: rgba(255,255,255,0.1); }
        .nav-link.active { color: #fff; background: rgba(245,158,11,0.2); border: 1px solid rgba(245,158,11,0.3); }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 0.375rem;
            padding: 0.5rem 1.25rem;
            background: linear-gradient(135deg, #f59e0b, #eab308);
            color: #111827; font-weight: 800; font-size: 0.8125rem;
            border-radius: 0.625rem; border: none; cursor: pointer;
            transition: all 0.2s ease; text-decoration: none; white-space: nowrap;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(245,158,11,0.4); }

        .flash-success {
            background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.3);
            color: #10b981; border-radius: 0.75rem; padding: 0.875rem 1.25rem;
            font-weight: 600; font-size: 0.875rem; margin-bottom: 1.25rem;
        }
        .flash-error {
            background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25);
            color: #f87171; border-radius: 0.75rem; padding: 0.875rem 1.25rem;
            font-weight: 600; font-size: 0.875rem; margin-bottom: 1.25rem;
        }

        .form-input {
            width: 100%; background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12); border-radius: 0.75rem;
            padding: 0.75rem 1rem; color: #f1f5f9; font-size: 0.9375rem;
            font-family: inherit; outline: none; transition: border-color 0.15s;
        }
        .form-input:focus { border-color: rgba(245,158,11,0.6); }
    </style>
</head>
<body style="background: #0a0f1e; color: #e2e8f0; min-height: 100vh; display: flex; flex-direction: column;">

    {{-- ─── AUTHENTICATED USER NAVIGATION ─── --}}
    <header style="background: rgba(10,15,30,0.95); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,0.08); sticky top: 0; z-index: 50;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 1.5rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; height: 4.25rem;">

                {{-- Brand --}}
                <a href="{{ route('user.matches.index') }}" style="display: flex; align-items: center; text-decoration: none;">
                    <img src="{{ asset('images/logo.png') }}" alt="AmaX" style="height: 2.25rem; width: auto; object-fit: contain;" />
                </a>

                {{-- User Portal Options: ONLY Matches & Schedule AND Match Creation --}}
                <nav style="display: flex; align-items: center; gap: 0.75rem;">
                    <a href="{{ route('user.matches.index') }}"
                       class="nav-link {{ request()->routeIs('user.matches.index') || request()->routeIs('dashboard') ? 'active' : '' }}">
                        📋 Matches &amp; Schedule
                    </a>
                    <a href="{{ route('user.matches.create') }}"
                       class="nav-link {{ request()->routeIs('user.matches.create') ? 'active' : '' }}">
                        ➕ Match Creation
                    </a>
                </nav>

                {{-- User Info & Logout --}}
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <span style="font-size: 0.8125rem; font-weight: 700; color: rgba(255,255,255,0.9);">
                        {{ Auth::user()->name }}
                    </span>
                    <form method="POST" action="{{ route('user.logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #f87171; font-weight: 700; font-size: 0.8125rem; padding: 0.4rem 0.875rem; border-radius: 0.5rem; cursor: pointer; transition: all 0.15s;">
                            Log Out
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </header>

    {{-- ─── MAIN CONTENT ─── --}}
    <main style="flex: 1; max-width: 1280px; width: 100%; margin: 0 auto; padding: 2rem 1.5rem;">
        @if(session('success'))
            <div class="flash-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash-error">⚠ {{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    {{-- ─── FOOTER ─── --}}
    <footer style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.06); padding: 1.5rem;">
        <div style="max-width: 1280px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
            <p style="color: rgba(100,116,139,0.7); font-size: 0.75rem;">&copy; {{ date('Y') }} AmaX Platform. All rights reserved.</p>
            <p style="color: rgba(100,116,139,0.5); font-size: 0.75rem;">User Portal — Matches &amp; Schedule</p>
        </div>
    </footer>

</body>
</html>
