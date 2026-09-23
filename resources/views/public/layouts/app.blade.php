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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vite assets (Tailwind + JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FB] text-slate-800 min-h-screen flex flex-col font-sans antialiased selection:bg-brand-red-light selection:text-brand-red">

    {{-- ─── 1. TOP ANNOUNCEMENT / LIVE RIBBON ─── --}}
    <div class="bg-white border-b border-slate-200/70 py-2 px-4 text-xs font-semibold text-slate-600">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 font-bold text-brand-red">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-red opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-red"></span>
                    </span>
                    MATCHDAY LIVE
                </span>
                <span class="hidden sm:inline-block text-slate-300">|</span>
                <span class="hidden sm:inline-block text-slate-500">Real-time ball-by-ball analysis, player statistics, and live streaming.</span>
            </div>
            <div class="flex items-center gap-4 text-xs font-bold text-slate-500">
                <a href="{{ route('public.matches') }}" class="hover:text-brand-red transition-colors flex items-center gap-1">
                    <span>View Schedule</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- ─── 2. STICKY GLASS HEADER ─── --}}
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-40 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18">

                {{-- Left: Brand Logo --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('public.home') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('images/logo.png') }}" alt="AmaX" class="h-9 w-auto object-contain transition-transform group-hover:scale-105" />
                    </a>

                    {{-- Desktop Nav (PlayerProfile.io style) --}}
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('public.home') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('public.home') ? 'text-brand-red bg-red-50/60' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Home
                        </a>
                        <a href="{{ route('public.matches') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-bold transition-colors flex items-center gap-2 {{ request()->routeIs('public.matches') ? 'text-brand-red bg-red-50/60' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            <span>Matches</span>
                            <span class="w-2 h-2 rounded-full bg-brand-red live-dot-pulse"></span>
                        </a>
                        <a href="{{ route('pricing') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('pricing') ? 'text-brand-red bg-red-50/60' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Pricing
                        </a>
                        <a href="{{ route('public.about') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('public.about') ? 'text-brand-red bg-red-50/60' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            About
                        </a>
                        <a href="{{ route('public.contact') }}"
                           class="px-3.5 py-2 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('public.contact') ? 'text-brand-red bg-red-50/60' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Contact
                        </a>
                    </nav>
                </div>

                {{-- Center/Right: Global Live Search --}}
                <div class="hidden lg:flex items-center flex-1 max-w-xs mx-6 relative">
                    <div class="relative w-full">
                        <input type="text"
                               id="navbar-search-input"
                               placeholder="Search athlete profiles..."
                               autocomplete="off"
                               class="w-full bg-slate-50 border border-slate-200 rounded-full py-2 pl-9 pr-4 text-xs font-semibold text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 focus:outline-none transition-all" />
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <div id="navbar-search-dropdown"
                         class="hidden absolute top-full mt-2 right-0 w-88 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 max-h-96 overflow-y-auto py-2">
                    </div>
                </div>

                {{-- Right Actions --}}
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-100 transition-colors">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Dashboard</span>
                        </a>
                        <form method="POST" action="{{ route('user.logout') }}" class="inline">
                            @csrf
                            <x-button type="submit" variant="ghost" size="sm" class="text-slate-600">Log Out</x-button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-brand-red px-3 py-2 rounded-xl transition-colors">
                            Sign In
                        </a>
                        <x-button href="{{ route('register') }}" variant="primary" size="md">
                            <span>Register</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </x-button>
                    @endauth

                    {{-- Hamburger Mobile Toggle --}}
                    <button type="button" data-mobile-menu-toggle="public-mobile-menu" class="md:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none" aria-label="Toggle navigation">
                        <svg class="w-6 h-6 icon-menu-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg class="w-6 h-6 icon-menu-close hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Menu Dropdown --}}
            <div id="public-mobile-menu" class="hidden md:hidden border-t border-slate-100 py-4 space-y-3">
                {{-- Mobile Search --}}
                <div class="relative">
                    <input type="text"
                           id="mobile-search-input"
                           placeholder="Search athlete profiles..."
                           autocomplete="off"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 pl-10 pr-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-brand-red focus:outline-none" />
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <div id="mobile-search-dropdown" class="hidden absolute top-full mt-2 left-0 right-0 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 max-h-80 overflow-y-auto py-2"></div>
                </div>

                <nav class="flex flex-col space-y-1 font-bold text-sm">
                    <a href="{{ route('public.home') }}" class="px-3 py-2 rounded-xl {{ request()->routeIs('public.home') ? 'bg-red-50 text-brand-red' : 'text-slate-700' }}">Home</a>
                    <a href="{{ route('public.matches') }}" class="px-3 py-2 rounded-xl {{ request()->routeIs('public.matches') ? 'bg-red-50 text-brand-red' : 'text-slate-700' }}">Matches &amp; Schedule</a>
                    <a href="{{ route('pricing') }}" class="px-3 py-2 rounded-xl {{ request()->routeIs('pricing') ? 'bg-red-50 text-brand-red' : 'text-slate-700' }}">Pricing</a>
                    <a href="{{ route('public.about') }}" class="px-3 py-2 rounded-xl {{ request()->routeIs('public.about') ? 'bg-red-50 text-brand-red' : 'text-slate-700' }}">About</a>
                    <a href="{{ route('public.contact') }}" class="px-3 py-2 rounded-xl {{ request()->routeIs('public.contact') ? 'bg-red-50 text-brand-red' : 'text-slate-700' }}">Contact Us</a>
                </nav>

                <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                    @guest
                        <x-button href="{{ route('login') }}" variant="secondary" class="w-full justify-center">Sign In</x-button>
                        <x-button href="{{ route('register') }}" variant="primary" class="w-full justify-center">Get Started</x-button>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    {{-- ─── 3. MAIN CONTENT ─── --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ─── 4. LIGHT FOOTER (PlayerProfile.io Style) ─── --}}
    <footer class="bg-white border-t border-slate-200/80 mt-16 pt-16 pb-12 text-sm text-slate-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-14">

                {{-- Brand Col --}}
                <div class="lg:col-span-2 space-y-4">
                    <img src="{{ asset('images/logo.png') }}" alt="AmaX" class="h-9 w-auto object-contain" />
                    <p class="text-slate-500 leading-relaxed text-sm max-w-sm">
                        The modern digital sports platform for athletes, clubs, and scouts. Real-time scores, verified profiles, and multi-sport career analytics.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-900 border border-amber-200">
                            <span class="w-2 h-2 rounded-full bg-brand-gold"></span>
                            Verified Sports Engine
                        </span>
                    </div>
                </div>

                {{-- Column 1: Platform --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900">Platform</h4>
                    <ul class="space-y-2 text-sm font-medium">
                        <li><a href="{{ route('public.home') }}" class="hover:text-brand-red transition-colors">Home</a></li>
                        <li><a href="{{ route('public.matches') }}" class="hover:text-brand-red transition-colors">Matches &amp; Schedule</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-brand-red transition-colors">Pricing &amp; VIP</a></li>
                        <li><a href="{{ route('public.about') }}" class="hover:text-brand-red transition-colors">About AmaX</a></li>
                    </ul>
                </div>

                {{-- Column 2: Sports --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900">Explore</h4>
                    <ul class="space-y-2 text-sm font-medium">
                        <li><a href="{{ route('public.home') }}#sports" class="hover:text-brand-red transition-colors">Cricket</a></li>
                        <li><a href="{{ route('public.home') }}#sports" class="hover:text-brand-red transition-colors">Badminton &amp; Tennis</a></li>
                        <li><a href="{{ route('public.home') }}#sports" class="hover:text-brand-red transition-colors">Volleyball &amp; Rugby</a></li>
                        <li><a href="{{ route('public.contact') }}" class="hover:text-brand-red transition-colors">Club Inquiries</a></li>
                    </ul>
                </div>

                {{-- Column 3: Legal & Trust --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900">Legal &amp; Trust</h4>
                    <ul class="space-y-2 text-sm font-medium">
                        <li><a href="{{ route('terms') }}" class="hover:text-brand-red transition-colors">Terms of Service</a></li>
                        <li><a href="{{ route('privacy-policy') }}" class="hover:text-brand-red transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ route('refund-policy') }}" class="hover:text-brand-red transition-colors">Refund Policy</a></li>
                        <li><a href="{{ route('public.contact') }}" class="hover:text-brand-red transition-colors">Support &amp; FAQ</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 font-medium">
                <p>&copy; {{ date('Y') }} AmaX Sports Technology. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <span class="text-slate-400">Powered by AmaX Engine</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- Live Player Search Script (Vanilla JS) --}}
    <script>
        function initPlayerAutocomplete(inputId, dropdownId) {
            const input = document.getElementById(inputId);
            const dropdown = document.getElementById(dropdownId);
            if (!input || !dropdown) return;

            let debounceTimer = null;
            let selectedIndex = -1;
            let currentResults = [];

            const escapeHtml = (str) => String(str || '').replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[m]);

            const highlightMatch = (text, query) => {
                if (!query) return escapeHtml(text);
                const safeText = escapeHtml(text);
                const safeQuery = escapeHtml(query);
                const regex = new RegExp('(' + safeQuery.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
                return safeText.replace(regex, '<span class="text-brand-red font-bold underline">$1</span>');
            };

            input.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(debounceTimer);
                selectedIndex = -1;

                if (query.length < 1) {
                    dropdown.classList.add('hidden');
                    dropdown.innerHTML = '';
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`/search/players?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            currentResults = data.results || [];
                            if (currentResults.length === 0) {
                                dropdown.innerHTML = `<div class="p-4 text-center text-xs text-slate-500 font-medium">No athletes found matching "<strong>${escapeHtml(query)}</strong>"</div>`;
                                dropdown.classList.remove('hidden');
                                return;
                            }

                            let html = `<div class="px-4 py-2 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider border-b border-slate-100">Athletes (${currentResults.length})</div>`;
                            currentResults.forEach((player, idx) => {
                                const sportsBadges = (player.sports || []).map(s => `<span class="inline-flex items-center gap-1 text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 px-2 py-0.5 rounded-full">${s.icon || '🏅'} ${s.name}</span>`).join(' ');
                                const teamInfo = player.primary_team ? `<span class="text-xs text-slate-500 font-medium">🛡️ ${escapeHtml(player.primary_team)}</span>` : '';

                                const avatarHtml = player.photo_url
                                    ? `<img src="${player.photo_url}" class="w-9 h-9 rounded-full object-cover shrink-0 border border-slate-200" />`
                                    : `<div class="w-9 h-9 rounded-full bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0 border border-slate-200">${player.name.substring(0, 1).toUpperCase()}</div>`;

                                html += `
                                    <a href="${player.url}"
                                       class="search-item flex items-center gap-3 px-4 py-2.5 hover:bg-red-50/50 transition-colors border-b border-slate-50 last:border-0"
                                       data-index="${idx}">
                                        ${avatarHtml}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <span class="font-bold text-xs text-slate-900 truncate">${highlightMatch(player.name, query)}</span>
                                                <span class="text-[10px] font-extrabold text-brand-red ml-2 shrink-0">View Profile →</span>
                                            </div>
                                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                                ${teamInfo}
                                                ${sportsBadges}
                                            </div>
                                        </div>
                                    </a>
                                `;
                            });

                            dropdown.innerHTML = html;
                            dropdown.classList.remove('hidden');
                        })
                        .catch(() => {
                            dropdown.classList.add('hidden');
                        });
                }, 200);
            });

            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }

        initPlayerAutocomplete('navbar-search-input', 'navbar-search-dropdown');
        initPlayerAutocomplete('mobile-search-input', 'mobile-search-dropdown');
    </script>

    @stack('scripts')
</body>
</html>
