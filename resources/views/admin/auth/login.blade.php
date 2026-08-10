<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="w-full max-w-sm bg-white p-8 rounded-lg shadow">
        <h1 class="text-xl font-semibold mb-6 text-center">{{ config('app.name') }} Admin</h1>

        @include('admin.partials.flash-messages')

        <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 w-full rounded border-gray-300 focus:border-gray-500 focus:ring-gray-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required
                    class="mt-1 w-full rounded border-gray-300 focus:border-gray-500 focus:ring-gray-500">
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember">
                Remember me
            </label>

            <button type="submit" class="w-full rounded bg-gray-900 text-white py-2 text-sm font-medium hover:bg-gray-800">
                Log in
            </button>
        </form>
    </div>
</body>
</html>
