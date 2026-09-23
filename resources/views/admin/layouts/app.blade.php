<!DOCTYPE html>
<html lang="en" class="h-full bg-[#F8F9FB]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Portal') — AmaX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F8F9FB] text-slate-800 antialiased flex flex-col font-sans">
    {{-- 1. Modern Light Executive Header --}}
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-40 shadow-2xs">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Left: Brand & Navigation --}}
                <div class="flex items-center gap-6 lg:gap-8">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 group">
                        <img src="{{ asset('images/logo.png') }}" alt="AmaX" class="h-8 w-auto object-contain transition-transform group-hover:scale-105" />
                        <span class="hidden sm:inline-block text-[10px] font-extrabold uppercase tracking-widest px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 border border-slate-200">Admin</span>
                    </a>

                    <nav class="hidden md:flex items-center space-x-1 text-sm font-semibold">
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-red-50 text-brand-red font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-red-50 text-brand-red font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Users
                        </a>
                        <a href="{{ route('admin.payments.index') }}"
                           class="px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.payments.*') || request()->routeIs('admin.purchases.*') ? 'bg-red-50 text-brand-red font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Payments
                        </a>
                        <a href="{{ route('admin.matches.index') }}"
                           class="px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.matches.*') && !request()->routeIs('admin.matches.create') ? 'bg-red-50 text-brand-red font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Matches
                        </a>
                        <a href="{{ route('admin.matches.create') }}"
                           class="px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.matches.create') ? 'bg-red-50 text-brand-red font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            + New Match
                        </a>
                        <a href="{{ route('admin.subscription-prices.index') }}"
                           class="px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.subscription-prices.*') ? 'bg-red-50 text-brand-red font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Pricing
                        </a>
                        <a href="{{ route('admin.achievements.index') }}"
                           class="px-3 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.achievements.*') ? 'bg-red-50 text-brand-red font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Achievements
                        </a>
                    </nav>
                </div>

                {{-- Right: Status & Actions --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('public.home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                        <span>Live Site</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>

                    @auth
                        <div class="flex items-center gap-3 pl-3 border-l border-slate-200">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-bold text-slate-900 leading-none">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Administrator</p>
                            </div>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <x-button type="submit" variant="ghost" size="sm" class="text-xs text-rose-600 hover:bg-rose-50 hover:text-rose-700">
                                    Log Out
                                </x-button>
                            </form>
                        </div>
                    @else
                        <x-button href="{{ route('admin.login') }}" variant="primary" size="sm">
                            Log In
                        </x-button>
                    @endauth

                    {{-- Mobile menu button --}}
                    <button type="button" data-mobile-menu-toggle="admin-mobile-nav" class="md:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100" aria-label="Toggle Navigation">
                        <svg class="w-6 h-6 icon-menu-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg class="w-6 h-6 icon-menu-close hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Drawer --}}
            <div id="admin-mobile-nav" class="hidden md:hidden border-t border-slate-100 py-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-red-50 text-brand-red' : 'text-slate-600' }}">Dashboard</a>
                <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('admin.users.*') ? 'bg-red-50 text-brand-red' : 'text-slate-600' }}">Users</a>
                <a href="{{ route('admin.payments.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('admin.payments.*') ? 'bg-red-50 text-brand-red' : 'text-slate-600' }}">Payments</a>
                <a href="{{ route('admin.matches.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('admin.matches.*') ? 'bg-red-50 text-brand-red' : 'text-slate-600' }}">Matches</a>
                <a href="{{ route('admin.matches.create') }}" class="block px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('admin.matches.create') ? 'bg-red-50 text-brand-red' : 'text-slate-600' }}">+ New Match</a>
                <a href="{{ route('admin.subscription-prices.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('admin.subscription-prices.*') ? 'bg-red-50 text-brand-red' : 'text-slate-600' }}">Subscription Pricing</a>
                <a href="{{ route('admin.achievements.index') }}" class="block px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('admin.achievements.*') ? 'bg-red-50 text-brand-red' : 'text-slate-600' }}">Achievements</a>
            </div>
        </div>
    </header>

    {{-- 2. Light Match Ticker Ribbon --}}
    <div class="bg-white border-b border-slate-200/80 py-2.5 px-4 shadow-2xs overflow-x-auto">
        <div class="mx-auto max-w-7xl flex items-center gap-4">
            <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 shrink-0 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-brand-red"></span>
                LIVE PLATFORM
            </span>
            <div class="flex items-center gap-3 overflow-x-auto py-0.5 scrollbar-none text-xs">
                <div class="bg-slate-50 rounded-xl px-3 py-1.5 border border-slate-200/70 shrink-0 flex items-center gap-3">
                    <x-badge variant="live" size="sm">LIVE</x-badge>
                    <span class="font-bold text-slate-900">Colombo vs Galle</span>
                    <span class="font-extrabold text-brand-red">139/8</span>
                    <span class="text-slate-400">• Main Arena</span>
                </div>
                <div class="bg-slate-50 rounded-xl px-3 py-1.5 border border-slate-200/70 shrink-0 flex items-center gap-3">
                    <x-badge variant="info" size="sm">SCHEDULED</x-badge>
                    <span class="font-bold text-slate-900">Maristella vs Ananda</span>
                    <span class="text-slate-500">Today 3:15 PM</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Main Content Container --}}
    <main class="flex-1 mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8 py-8">
        @include('admin.partials.flash-messages')
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-slate-200/80 bg-white py-6 mt-auto text-xs text-slate-500">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>&copy; {{ date('Y') }} AmaX Sports Platform. All rights reserved.</p>
            <div class="flex items-center gap-6">
                <a href="{{ route('pricing') }}" class="font-semibold hover:text-brand-red transition-colors">Pricing</a>
                <a href="{{ route('terms') }}" class="font-semibold hover:text-brand-red transition-colors">Terms &amp; Conditions</a>
                <a href="{{ route('privacy-policy') }}" class="font-semibold hover:text-brand-red transition-colors">Privacy Policy</a>
                <a href="{{ route('refund-policy') }}" class="font-semibold hover:text-brand-red transition-colors">Refund Policy</a>
            </div>
        </div>
    </footer>
</body>
</html>
