@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Dashboard</h1>
        <a href="{{ route('admin.matches.create') }}" class="rounded bg-gray-900 text-white px-4 py-2 text-sm font-medium hover:bg-gray-800">
            + New Match
        </a>
    </div>

    <section class="mb-10">
        <h2 class="text-lg font-medium mb-3 flex items-center gap-2">
            <span class="inline-block h-2 w-2 rounded-full bg-red-500"></span> Live now
        </h2>

        @if ($liveMatches->isEmpty())
            <p class="text-sm text-gray-500">No matches are live right now.</p>
        @else
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($liveMatches as $match)
                    <a href="{{ route('admin.live-score.show', $match) }}" class="block rounded-lg border border-red-200 bg-red-50 p-4 hover:border-red-300">
                        <p class="text-xs uppercase tracking-wide text-red-600 font-medium mb-1">{{ $match->sport->name }} · Live</p>
                        <p class="font-medium">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</p>
                        <p class="text-sm text-gray-500">{{ $match->venue }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    <section>
        <h2 class="text-lg font-medium mb-3">Upcoming</h2>

        @if ($upcomingMatches->isEmpty())
            <p class="text-sm text-gray-500">No upcoming matches scheduled.</p>
        @else
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($upcomingMatches as $match)
                    <a href="{{ route('admin.matches.players.index', $match) }}" class="block rounded-lg border border-gray-200 bg-white p-4 hover:border-gray-300">
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-medium mb-1">{{ $match->sport->name }}</p>
                        <p class="font-medium">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</p>
                        <p class="text-sm text-gray-500">{{ $match->scheduled_at?->format('D, M j g:ia') }} · {{ $match->venue }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
@endsection
