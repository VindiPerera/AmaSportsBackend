<!DOCTYPE html>
<html lang="en" class="h-full bg-[#F8F9FB]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Sign In — AmaX Control Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center bg-[#F8F9FB] p-4 text-slate-800 font-sans antialiased relative">
    {{-- Soft Ambient Glows --}}
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-brand-red/5 blur-3xl rounded-full pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-brand-gold/10 blur-3xl rounded-full pointer-events-none"></div>

    <div class="w-full max-w-md bg-white border border-slate-200/80 p-8 sm:p-10 rounded-3xl shadow-xl relative z-10">
        {{-- Brand Header --}}
        <div class="text-center mb-8">
            <a href="{{ route('public.home') }}" class="inline-block">
                <img src="{{ asset('images/logo.png') }}" alt="AmaX" class="h-10 w-auto mx-auto object-contain mb-4" />
            </a>
            <h1 class="text-2xl font-black text-brand-charcoal tracking-tight">Admin Sign In</h1>
            <p class="text-xs text-slate-500 font-semibold mt-1">Management Portal &amp; Live Scoring Console</p>
        </div>

        @include('admin.partials.flash-messages')

        <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-4">
            @csrf

            <x-input
                type="email"
                name="email"
                label="Email Address"
                value="admin@amasports.app"
                required
                autofocus
                placeholder="admin@amasports.app"
            />

            <x-input
                type="password"
                name="password"
                label="Password"
                required
                placeholder="••••••••"
            />

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 text-slate-600 font-semibold cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-red focus:ring-brand-red/30">
                    <span>Keep me logged in</span>
                </label>
            </div>

            <div class="pt-2">
                <x-button type="submit" variant="primary" size="lg" class="w-full justify-center">
                    Sign In to Dashboard
                </x-button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <a href="{{ route('public.home') }}" class="font-semibold hover:text-brand-red transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to public site</span>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('terms') }}" class="hover:text-brand-red transition-colors">Terms</a>
                <span>•</span>
                <a href="{{ route('privacy-policy') }}" class="hover:text-brand-red transition-colors">Privacy</a>
            </div>
        </div>
    </div>
</body>
</html>
