<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    <nav class="bg-gray-900 text-white">
        <div class="mx-auto max-w-6xl px-4 flex items-center justify-between h-14">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.dashboard') }}" class="font-semibold">{{ config('app.name') }} Admin</a>
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-300 hover:text-white">Dashboard</a>
                <a href="{{ route('admin.matches.index') }}" class="text-sm text-gray-300 hover:text-white">Matches</a>
                <a href="{{ route('admin.matches.create') }}" class="text-sm text-gray-300 hover:text-white">+ New Match</a>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-300 hover:text-white">Logout</button>
            </form>
        </div>
    </nav>

    <main class="mx-auto max-w-6xl px-4 py-8">
        @include('admin.partials.flash-messages')

        @yield('content')
    </main>
</body>
</html>
