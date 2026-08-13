<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'AmaX — Live sports scores, matches, schedules and player analytics for every sport.')">
    <title>@yield('title', 'AmaX') — Every Sport, Live</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vite assets (Tailwind + JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', sans-serif; }

        /* ── Custom Utility Overrides ── */
        .nav-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: rgba(255,255,255,0.75);
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .nav-link:hover { color: #fff; background: rgba(255,255,255,0.1); }
        .nav-link.active { color: #fff; background: rgba(255,255,255,0.15); }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 0.375rem;
            padding: 0.5rem 1.25rem;
            background: linear-gradient(135deg, #f59e0b, #eab308);
            color: #111827;
            font-weight: 700; font-size: 0.8125rem;
            border-radius: 0.625rem;
            border: none; cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none; white-space: nowrap;
            box-shadow: 0 4px 12px rgba(245,158,11,0.35);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(245,158,11,0.45);
            color: #111827;
        }

        .live-dot {
            display: inline-block; width: 0.5rem; height: 0.5rem;
            background: #ef4444; border-radius: 50%;
            animation: livepulse 1.2s ease-in-out infinite;
        }
        @keyframes livepulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.85); }
        }

        /* Ticker ribbon */
        .ticker-track {
            display: flex;
            animation: ticker-scroll 30s linear infinite;
            white-space: nowrap;
        }
        @keyframes ticker-scroll {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }
        .ticker-track:hover { animation-play-state: paused; }

        /* Glass card */
        .glass-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 1rem;
        }

        /* Mobile menu */
        #mobile-menu { display: none; }
        #mobile-menu.open { display: block; }

        /* Flash */
        .flash-success {
            background: rgba(16,185,129,0.12);
            border: 1px solid rgba(16,185,129,0.3);
            color: #10b981; border-radius: 0.75rem;
            padding: 0.875rem 1.25rem;
            font-weight: 600; font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }
        .flash-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.25);
            color: #f87171; border-radius: 0.75rem;
            padding: 0.875rem 1.25rem;
            font-weight: 600; font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        /* Form inputs */
        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            color: #f1f5f9;
            font-size: 0.9375rem;
            font-family: inherit;
            transition: border-color 0.15s, box-shadow 0.15s;
            outline: none;
        }
        .form-input::placeholder { color: rgba(148,163,184,0.6); }
        .form-input:focus {
            border-color: rgba(245,158,11,0.6);
            box-shadow: 0 0 0 3px rgba(245,158,11,0.12);
        }
    </style>
</head>
<body style="background: #0a0f1e; color: #e2e8f0; min-height: 100vh; display: flex; flex-direction: column;">

    {{-- ─── NAVIGATION ─── --}}
    <header style="background: rgba(10,15,30,0.92); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,0.07); position: sticky; top: 0; z-index: 50;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 1.5rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; height: 4rem;">

                {{-- Brand --}}
                <a href="{{ route('public.home') }}" style="display: flex; align-items: center; text-decoration: none;">
                    <img src="{{ asset('images/logo.png') }}" alt="AmaX" style="height: 2.25rem; width: auto; object-fit: contain;" />
                </a>

                {{-- Desktop Nav --}}
                <nav style="display: none; align-items: center; gap: 0.25rem;" class="desktop-nav">
                    <a href="{{ route('public.home') }}"
                       class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('public.about') }}"
                       class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}">
                        About
                    </a>
                    <a href="{{ route('public.contact') }}"
                       class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}">
                        Contact Us
                    </a>
                    <a href="{{ route('public.matches') }}"
                       class="nav-link {{ request()->routeIs('public.matches') ? 'active' : '' }}">
                        <span class="live-dot" style="margin-right: 0.125rem;"></span>
                        Matches &amp; Schedule
                    </a>
                    <a href="{{ route('public.app') }}"
                       class="nav-link {{ request()->routeIs('public.app') ? 'active' : '' }}" style="color: #fbbf24;">
                        📱 Mobile Web App
                    </a>
                </nav>

                {{-- Login CTA (desktop) --}}
                <div style="display: none; align-items: center; gap: 0.75rem;" class="desktop-actions">
                    <a href="{{ route('login') }}" class="nav-link">Log In</a>
                    <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
                </div>

                {{-- Hamburger --}}
                <button id="hamburger" aria-label="Open menu"
                        style="display: flex; align-items: center; padding: 0.5rem; color: rgba(255,255,255,0.7); cursor: pointer; background: none; border: none;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" style="background: rgba(10,15,30,0.98); border-top: 1px solid rgba(255,255,255,0.07); padding: 1rem 1.5rem 1.5rem;">
            <nav style="display: flex; flex-direction: column; gap: 0.25rem; margin-bottom: 1rem;">
                <a href="{{ route('public.home') }}" class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }}" style="color: #fff;">Home</a>
                <a href="{{ route('public.about') }}" class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}" style="color: rgba(255,255,255,0.75);">About</a>
                <a href="{{ route('public.contact') }}" class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}" style="color: rgba(255,255,255,0.75);">Contact Us</a>
                <a href="{{ route('public.matches') }}" class="nav-link {{ request()->routeIs('public.matches') ? 'active' : '' }}" style="color: rgba(255,255,255,0.75); display: flex; align-items: center; gap: 0.375rem;">
                    <span class="live-dot"></span> Matches &amp; Schedule
                </a>
                <a href="{{ route('public.app') }}" class="nav-link {{ request()->routeIs('public.app') ? 'active' : '' }}" style="color: #fbbf24; display: flex; align-items: center; gap: 0.375rem;">
                    📱 Mobile Web App
                </a>
            </nav>
            <div style="display: flex; flex-direction: column; gap: 0.625rem;">
                <a href="{{ route('login') }}" style="display: block; text-align: center; padding: 0.625rem; border-radius: 0.625rem; border: 1px solid rgba(255,255,255,0.15); color: #fff; font-weight: 600; font-size: 0.875rem; text-decoration: none;">Log In</a>
                <a href="{{ route('register') }}" class="btn-primary" style="text-align: center; justify-content: center;">Get Started</a>
            </div>
        </div>
    </header>

    {{-- ─── MAIN CONTENT ─── --}}
    <main style="flex: 1;">
        @yield('content')
    </main>

    {{-- ─── FOOTER ─── --}}
    <footer style="background: rgba(255,255,255,0.03); border-top: 1px solid rgba(255,255,255,0.07); padding: 2.5rem 1.5rem;">
        <div style="max-width: 1280px; margin: 0 auto;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-bottom: 2rem;">

                <div>
                    <div style="display: flex; align-items: center; margin-bottom: 0.875rem;">
                        <img src="{{ asset('images/logo.png') }}" alt="AmaX" style="height: 1.75rem; width: auto; object-fit: contain;" />
                    </div>
                    <p style="color: rgba(148,163,184,0.75); font-size: 0.8125rem; line-height: 1.6;">Every Sport, Live. Track scores, players, and schedules across all your favourite sports.</p>
                </div>

                <div>
                    <p style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(148,163,184,0.6); margin-bottom: 0.875rem;">Platform</p>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <a href="{{ route('public.home') }}" style="color: rgba(203,213,225,0.8); font-size: 0.875rem; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(203,213,225,0.8)'">Home</a>
                        <a href="{{ route('public.matches') }}" style="color: rgba(203,213,225,0.8); font-size: 0.875rem; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(203,213,225,0.8)'">Matches &amp; Schedule</a>
                        <a href="{{ route('public.about') }}" style="color: rgba(203,213,225,0.8); font-size: 0.875rem; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(203,213,225,0.8)'">About</a>
                        <a href="{{ route('public.contact') }}" style="color: rgba(203,213,225,0.8); font-size: 0.875rem; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(203,213,225,0.8)'">Contact Us</a>
                    </div>
                </div>

                <div>
                    <p style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(148,163,184,0.6); margin-bottom: 0.875rem;">Account</p>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <a href="{{ route('login') }}" style="color: rgba(203,213,225,0.8); font-size: 0.875rem; text-decoration: none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(203,213,225,0.8)'">Log In</a>
                        <a href="{{ route('register') }}" style="color: rgba(203,213,225,0.8); font-size: 0.875rem; text-decoration: none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(203,213,225,0.8)'">Create Account</a>
                    </div>
                </div>

            </div>

            <div style="border-top: 1px solid rgba(255,255,255,0.07); padding-top: 1.5rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 0.75rem;">
                <p style="color: rgba(100,116,139,0.8); font-size: 0.75rem;">&copy; {{ date('Y') }} AmaX Platform. All rights reserved.</p>
                <p style="color: rgba(100,116,139,0.6); font-size: 0.75rem; font-weight: 600;">Every Sport, Live.</p>
            </div>
        </div>
    </footer>

    <script>
        // Desktop nav & actions responsive show
        function setResponsive() {
            const isWide = window.innerWidth >= 768;
            document.querySelectorAll('.desktop-nav, .desktop-actions').forEach(el => {
                el.style.display = isWide ? 'flex' : 'none';
            });
            document.getElementById('hamburger').style.display = isWide ? 'none' : 'flex';
        }
        setResponsive();
        window.addEventListener('resize', setResponsive);

        // Hamburger toggle
        document.getElementById('hamburger').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('open');
        });
    </script>

    @stack('scripts')
</body>
</html>
