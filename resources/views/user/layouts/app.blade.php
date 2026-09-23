<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Portal') — AmaX</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FB] text-slate-800 min-h-screen flex flex-col font-sans antialiased">

    {{-- ─── AUTHENTICATED USER NAVIGATION ─── --}}
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-40 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Brand --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('user.matches.index') }}" class="flex items-center gap-2 group">
                        <img src="{{ asset('images/logo.png') }}" alt="AmaX" class="h-8 w-auto object-contain transition-transform group-hover:scale-105" />
                        <span class="hidden sm:inline-block text-[10px] font-extrabold uppercase tracking-widest px-2 py-0.5 rounded-md bg-amber-50 text-amber-900 border border-amber-200">User Portal</span>
                    </a>

                    {{-- User Portal Options: Matches & Schedule AND Match Creation --}}
                    <nav class="hidden sm:flex items-center space-x-1 text-sm font-bold">
                        <a href="{{ route('user.matches.index') }}"
                           class="px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('user.matches.index') || request()->routeIs('dashboard') ? 'bg-red-50 text-brand-red' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Matches &amp; Schedule
                        </a>
                        <a href="{{ route('user.matches.create') }}"
                           class="px-3.5 py-2 rounded-xl transition-colors {{ request()->routeIs('user.matches.create') ? 'bg-red-50 text-brand-red' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            + New Match
                        </a>
                    </nav>
                </div>

                {{-- User Info & Logout --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('public.home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                        <span>Public Site</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>

                    <div class="flex items-center gap-3 pl-3 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-900 leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Player / Manager</p>
                        </div>
                        <form method="POST" action="{{ route('user.logout') }}">
                            @csrf
                            <x-button type="submit" variant="ghost" size="sm" class="text-xs text-rose-600 hover:bg-rose-50 hover:text-rose-700">
                                Log Out
                            </x-button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </header>

    {{-- ─── MAIN CONTENT ─── --}}
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <x-alert type="success" title="Success">
                {{ session('success') }}
            </x-alert>
        @endif

        @if (session('error'))
            <x-alert type="error" title="Error">
                {{ session('error') }}
            </x-alert>
        @endif

        @if ($errors->any())
            <x-alert type="error" title="Please resolve the following issues">
                <ul class="list-disc pl-5 space-y-1 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        @yield('content')
    </main>

    {{-- ─── FOOTER ─── --}}
    <footer class="bg-white border-t border-slate-200/80 py-6 mt-auto text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>&copy; {{ date('Y') }} AmaX Sports Platform. All rights reserved.</p>
            <div class="flex items-center gap-6">
                <a href="{{ route('pricing') }}" class="font-semibold hover:text-brand-red transition-colors">Pricing</a>
                <a href="{{ route('terms') }}" class="font-semibold hover:text-brand-red transition-colors">Terms of Service</a>
                <a href="{{ route('privacy-policy') }}" class="font-semibold hover:text-brand-red transition-colors">Privacy Policy</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
