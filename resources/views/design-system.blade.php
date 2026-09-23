<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AmaX Design System Playground</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FB] min-h-screen text-slate-800 p-8">
    <div class="max-w-6xl mx-auto space-y-12">
        <header class="border-b border-slate-200 pb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto" />
                <div>
                    <h1 class="text-2xl font-black text-brand-charcoal">Design System & Component Library</h1>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">PlayerProfile.io Aesthetic + AmaX Brand Colors</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block w-4 h-4 rounded-full bg-brand-red"></span>
                <span class="inline-block w-4 h-4 rounded-full bg-brand-gold"></span>
                <span class="inline-block w-4 h-4 rounded-full bg-brand-charcoal"></span>
            </div>
        </header>

        {{-- 1. Buttons --}}
        <section class="space-y-4">
            <h2 class="text-lg font-bold text-slate-900 border-b border-slate-200 pb-2">Buttons</h2>
            <div class="flex flex-wrap gap-3 items-center">
                <x-button variant="primary">Brand Red (Primary)</x-button>
                <x-button variant="gold">Brand Gold (Accent)</x-button>
                <x-button variant="dark">Dark Charcoal</x-button>
                <x-button variant="secondary">Secondary Outline</x-button>
                <x-button variant="ghost">Ghost Button</x-button>
                <x-button variant="danger">Danger</x-button>
                <x-button variant="quiet">Quiet Text</x-button>
                <x-button variant="primary" size="sm">Small Red</x-button>
                <x-button variant="gold" size="lg">Large Gold</x-button>
            </div>
        </section>

        {{-- 2. Badges --}}
        <section class="space-y-4">
            <h2 class="text-lg font-bold text-slate-900 border-b border-slate-200 pb-2">Badges</h2>
            <div class="flex flex-wrap gap-3 items-center">
                <x-badge variant="live">LIVE</x-badge>
                <x-badge variant="gold" dot>VIP Member</x-badge>
                <x-badge variant="red" dot>Featured</x-badge>
                <x-badge variant="success">Completed</x-badge>
                <x-badge variant="warning">Pending Review</x-badge>
                <x-badge variant="info">Upcoming</x-badge>
                <x-badge variant="neutral">Draft</x-badge>
                <x-badge variant="dark">Official</x-badge>
            </div>
        </section>

        {{-- 3. Stat Cards --}}
        <section class="space-y-4">
            <h2 class="text-lg font-bold text-slate-900 border-b border-slate-200 pb-2">Stat Cards</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card label="Total Matches" value="1,248" subtext="Across 12 Sports" accent="red" />
                <x-stat-card label="Active Players" value="4,890" subtext="+12% this month" accent="gold" />
                <x-stat-card label="Live Viewers" value="340" subtext="Currently online" />
                <x-stat-card label="Win Rate" value="78.5%" subtext="Rank #3 Overall" trend="+4.2% vs last season" />
            </div>
        </section>

        {{-- 4. Alerts --}}
        <section class="space-y-4">
            <h2 class="text-lg font-bold text-slate-900 border-b border-slate-200 pb-2">Alerts</h2>
            <x-alert type="success" title="Profile Updated">Your athlete profile has been successfully saved.</x-alert>
            <x-alert type="error" title="Validation Failed">Please review the required fields highlighted in red below.</x-alert>
            <x-alert type="warning" title="Subscription Expiring">Your pass will renew automatically in 3 days.</x-alert>
            <x-alert type="info" title="Match Announcement">Rain delay has pushed match start to 14:30.</x-alert>
        </section>

        {{-- 5. Card, Table & Avatars --}}
        <section class="space-y-4">
            <h2 class="text-lg font-bold text-slate-900 border-b border-slate-200 pb-2">Cards, Table & Avatars</h2>
            <x-card hover>
                <x-slot:header>
                    <div class="flex items-center gap-3">
                        <x-avatar name="Liam Vance" ring />
                        <div>
                            <h3 class="font-bold text-slate-900">Featured Athlete Card</h3>
                            <p class="text-xs text-slate-500">Cricket • Top-Order Batter</p>
                        </div>
                    </div>
                    <x-badge variant="gold">Pro Member</x-badge>
                </x-slot:header>

                <p class="text-slate-600 text-sm mb-4">
                    High-performance modern card container designed with airy 16px radius, subtle border, and crisp typography.
                </p>

                <x-table :headers="['Player', 'Sport', 'Matches', 'Status', 'Action']">
                    <tr>
                        <td class="px-5 py-3 font-semibold text-slate-900 flex items-center gap-2">
                            <x-avatar name="David Warner" size="sm" />
                            <span>David Warner</span>
                        </td>
                        <td class="px-5 py-3">Cricket</td>
                        <td class="px-5 py-3 font-bold">142</td>
                        <td class="px-5 py-3"><x-badge variant="success">Active</x-badge></td>
                        <td class="px-5 py-3"><x-button size="sm" variant="secondary">View</x-button></td>
                    </tr>
                    <tr>
                        <td class="px-5 py-3 font-semibold text-slate-900 flex items-center gap-2">
                            <x-avatar name="Sarah Chen" size="sm" />
                            <span>Sarah Chen</span>
                        </td>
                        <td class="px-5 py-3">Badminton</td>
                        <td class="px-5 py-3 font-bold">88</td>
                        <td class="px-5 py-3"><x-badge variant="live">Playing</x-badge></td>
                        <td class="px-5 py-3"><x-button size="sm" variant="secondary">View</x-button></td>
                    </tr>
                </x-table>
            </x-card>
        </section>
    </div>
</body>
</html>
