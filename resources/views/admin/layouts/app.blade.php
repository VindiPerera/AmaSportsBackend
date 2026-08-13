<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — AmaX Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased flex flex-col">
    {{-- 1. ESPNcricinfo Style Top Royal Blue Header --}}
    <header class="bg-[#0366D6] text-white sticky top-0 z-50 shadow-md">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                {{-- Left: Brand & Navigation --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        <img src="{{ asset('images/logo.png') }}" alt="AmaX" class="h-8 w-auto object-contain bg-white/90 px-2 py-1 rounded-lg" />
                    </a>

                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('admin.users.index') }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            Users
                        </a>
                        <a href="{{ route('admin.payments.index') }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.payments.*') || request()->routeIs('admin.purchases.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            Payments
                        </a>
                    </nav>
                </div>

                {{-- Right: Edition & Actions --}}
                <div class="flex items-center gap-4 text-xs font-bold">
                    <div class="hidden sm:flex items-center gap-1 text-blue-100 hover:text-white cursor-pointer">
                        <span>Edition SL</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>

                    @auth
                        <span class="hidden lg:inline text-blue-100 font-semibold normal-case">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-white/10 hover:bg-red-500 text-white transition-all text-xs font-extrabold">
                                Log Out
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.login') }}" class="px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white transition-all text-xs font-extrabold">
                            Log In
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- 2. ESPNcricinfo Style Top Live Match Ticker Ribbon --}}
    <div class="bg-white border-b border-slate-200 py-2.5 px-4 shadow-xs overflow-x-auto">
        <div class="mx-auto max-w-7xl flex items-center gap-3">
            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 shrink-0">LATEST MATCHES</span>
            <div class="flex items-center gap-3 overflow-x-auto scrollbar-none py-0.5">
                {{-- Match Ticker Item 1 --}}
                <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-200 shrink-0 min-w-[200px] text-xs">
                    <div class="flex justify-between text-[10px] font-bold text-red-600 uppercase mb-1">
                        <span>🔴 Live</span>
                        <span>Main Arena</span>
                    </div>
                    <div class="flex justify-between font-bold text-slate-800">
                        <span>Galle</span>
                        <span class="text-blue-600">139/8</span>
                    </div>
                    <div class="flex justify-between font-bold text-slate-800">
                        <span>Colombo</span>
                        <span>48/2</span>
                    </div>
                </div>

                {{-- Match Ticker Item 2 --}}
                <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-200 shrink-0 min-w-[200px] text-xs">
                    <div class="flex justify-between text-[10px] font-bold text-slate-500 uppercase mb-1">
                        <span>Upcoming</span>
                        <span>Today 3:15 PM</span>
                    </div>
                    <div class="font-bold text-slate-800">Maristella vs Ananda</div>
                    <div class="text-[10px] text-slate-500 mt-0.5">Venue TBA</div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Main Page Content Container --}}
    <main class="flex-1 mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8 py-6">
        @include('admin.partials.flash-messages')

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-slate-200 bg-white py-4 mt-auto text-xs text-slate-500">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <p>&copy; {{ date('Y') }} AmaX Platform. All rights reserved.</p>
            <p class="font-semibold text-slate-400">Minimalist Sports UI Engine</p>
        </div>
    </footer>
</body>
</html>
