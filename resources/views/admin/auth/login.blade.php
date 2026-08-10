<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — AmaSports Control Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center bg-slate-950 p-4 text-slate-100 relative overflow-hidden">
    {{-- Background Accent Glows --}}
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-blue-600/20 blur-3xl rounded-full pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-orange-600/10 blur-3xl rounded-full pointer-events-none"></div>

    <div class="w-full max-w-md bg-slate-900/90 border border-slate-800 p-8 rounded-2xl shadow-2xl backdrop-blur-xl relative z-10">
        {{-- Brand Icon Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex w-12 h-12 rounded-2xl bg-blue-600 items-center justify-center text-white text-2xl font-black shadow-lg shadow-blue-600/40 mb-3">
                A
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">AmaSports Admin</h1>
            <p class="text-xs text-slate-400 mt-1">Management Portal & Real-time Live Scorer</p>
        </div>

        @include('admin.partials.flash-messages')

        <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email', 'admin@amasports.app') }}" required autofocus
                    class="w-full rounded-xl bg-slate-800/80 border border-slate-700/80 text-white placeholder-slate-500 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Password</label>
                <input id="password" type="password" name="password" required
                    class="w-full rounded-xl bg-slate-800/80 border border-slate-700/80 text-white placeholder-slate-500 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 text-slate-400 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-700 bg-slate-800 text-blue-600 focus:ring-blue-500/20">
                    Remember session
                </label>
            </div>

            <button type="submit" class="w-full rounded-xl bg-blue-600 hover:bg-blue-500 text-white py-3 text-sm font-bold shadow-lg shadow-blue-600/30 hover:scale-[1.01] active:scale-[0.99] transition-all">
                Sign In to Dashboard
            </button>
        </form>
    </div>
</body>
</html>
