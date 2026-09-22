@extends('admin.layouts.app')

@section('title', 'Admin Dashboard — Live Control')

@section('content')

    {{-- Top Overview Metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-stat-card
            label="Live Matches"
            value="{{ $liveMatches->count() }}"
            subtext="In Progress"
            accent="red"
        />
        <x-stat-card
            label="Upcoming Scheduled"
            value="{{ $upcomingMatches->count() }}"
            subtext="Ready to Score"
            accent="gold"
        />
        <x-stat-card
            label="Database Sync"
            value="Active"
            subtext="Firebase Realtime"
        />
        <x-stat-card
            label="System Status"
            value="Healthy"
            subtext="All Services Operational"
        />
    </div>

    {{-- Player Search Box --}}
    <x-card class="mb-8 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-brand-red">SCOUT &amp; ANALYSIS DESK</span>
                <h2 class="text-base font-black text-brand-charcoal mt-0.5">Find Athlete Profile &amp; Analysis</h2>
            </div>
            <span class="text-xs text-slate-400 font-semibold">Direct lookup by athlete name</span>
        </div>

        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <x-input
                    name="q"
                    value="{{ $query }}"
                    placeholder="Search by athlete name (e.g. Dasun, Kusal, Warner)..."
                />
            </div>
            <x-button type="submit" variant="primary" size="md">
                Search Player
            </x-button>
            @if($query)
                <x-button href="{{ route('admin.dashboard') }}" variant="secondary" size="md">
                    Clear
                </x-button>
            @endif
        </form>

        @if ($searchResults !== null)
            <div class="mt-6 pt-5 border-t border-slate-100 space-y-2">
                @if ($searchResults->isEmpty())
                    <p class="text-xs font-semibold text-slate-500 py-2">No athletes found matching "{{ $query }}".</p>
                @else
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block mb-2">Search Results</span>
                    <div class="divide-y divide-slate-100">
                        @foreach ($searchResults as $result)
                            <a href="{{ route('admin.players.show', $result['player']) }}"
                               class="flex items-center justify-between py-3 px-2 rounded-xl hover:bg-red-50/40 transition-colors">
                                <div class="flex items-center gap-3">
                                    <x-avatar :name="$result['player']->full_name" size="sm" />
                                    <div>
                                        <p class="text-xs font-black text-slate-900">{{ $result['player']->full_name }}</p>
                                        <p class="text-[11px] text-slate-500 font-medium">
                                            {{ $result['team'] ?? 'Independent' }} • Cricket
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 text-xs font-bold text-slate-600">
                                    <span>{{ $result['overview']['matches'] }} matches</span>
                                    <span class="text-brand-red">{{ $result['overview']['runs'] }} runs</span>
                                    <span>{{ $result['overview']['wickets'] }} wkts</span>
                                    <span class="text-brand-red font-extrabold">Open Analysis →</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </x-card>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- Main Column (8 Cols) --}}
        <div class="lg:col-span-8 space-y-8">

            {{-- Live Matches Section --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-brand-red live-dot-pulse"></span>
                        <span>Matches In Progress</span>
                    </h2>
                    @if($liveMatches->isNotEmpty())
                        <x-badge variant="live" size="sm">{{ $liveMatches->count() }} ACTIVE</x-badge>
                    @endif
                </div>

                @if ($liveMatches->isEmpty())
                    <x-empty-state
                        title="No matches are live right now"
                        message="Start an upcoming fixture from the schedule below to launch real-time scoring console."
                    />
                @else
                    <div class="space-y-4">
                        @foreach ($liveMatches as $match)
                            <x-card class="hover:border-red-200 transition-colors">
                                <div class="flex items-center justify-between text-xs font-bold text-slate-500 pb-3 mb-3 border-b border-slate-100">
                                    <span class="text-brand-red font-extrabold flex items-center gap-1.5 uppercase">
                                        <span class="h-2 w-2 rounded-full bg-brand-red live-dot-pulse"></span>
                                        {{ $match->sport->name }} • LIVE SCORING
                                    </span>
                                    <span class="text-slate-400 font-medium">{{ $match->venue ?? 'Main Arena' }}</span>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <h3 class="text-base font-extrabold text-brand-charcoal">
                                            {{ $match->homeTeam->name }} <span class="text-slate-400 font-normal text-xs mx-1">vs</span> {{ $match->awayTeam->name }}
                                        </h3>
                                        <p class="text-xs text-slate-500 mt-1">Real-time ball-by-ball score tracking enabled</p>
                                    </div>

                                    <x-button href="{{ route('admin.live-score.show', $match) }}" variant="primary" size="sm">
                                        Open Scorer Console →
                                    </x-button>
                                </div>
                            </x-card>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Upcoming Fixtures Section --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-black text-slate-900">Scheduled Upcoming Fixtures</h2>
                    <a href="{{ route('admin.matches.index') }}" class="text-xs font-bold text-brand-red hover:underline">View All Fixtures →</a>
                </div>

                @if ($upcomingMatches->isEmpty())
                    <x-card class="text-center py-8">
                        <p class="text-xs text-slate-400 font-semibold">No upcoming matches scheduled.</p>
                    </x-card>
                @else
                    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden divide-y divide-slate-100 shadow-xs">
                        @foreach ($upcomingMatches as $match)
                            <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50/70 transition-colors">
                                <div class="flex items-center gap-3">
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-[10px] font-black uppercase">
                                        {{ $match->sport->name }}
                                    </span>
                                    <div>
                                        <h4 class="text-xs font-extrabold text-slate-900">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</h4>
                                        <p class="text-[11px] text-slate-500 font-medium">{{ $match->scheduled_at?->format('D, M j • g:i A') ?? 'TBA' }} • {{ $match->venue ?? 'Venue TBA' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 self-end sm:self-center">
                                    <x-button href="{{ route('admin.matches.players.index', $match) }}" variant="secondary" size="sm">
                                        Roster
                                    </x-button>
                                    <x-button href="{{ route('admin.live-score.show', $match) }}" variant="quiet" size="sm">
                                        Score
                                    </x-button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- Sidebar (4 Cols) --}}
        <div class="lg:col-span-4 space-y-6">
            <x-card class="space-y-4">
                <x-slot:header>
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-900">
                        Quick Administration Tools
                    </h3>
                </x-slot:header>

                <div class="space-y-3 text-xs font-bold">
                    <a href="{{ route('admin.matches.create') }}" class="flex items-center gap-3 p-2 rounded-xl text-slate-700 hover:bg-slate-50 hover:text-brand-red transition-all group">
                        <div class="w-9 h-9 rounded-xl bg-red-50 text-brand-red flex items-center justify-center text-sm font-black group-hover:scale-105 transition-transform border border-red-100">
                            ➕
                        </div>
                        <div>
                            <span class="block text-slate-900 font-bold">Create New Match</span>
                            <span class="text-[10px] text-slate-400 font-normal">Add teams, format, and stream</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.matches.index') }}" class="flex items-center gap-3 p-2 rounded-xl text-slate-700 hover:bg-slate-50 hover:text-brand-red transition-all group">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-brand-gold flex items-center justify-center text-sm font-black group-hover:scale-105 transition-transform border border-amber-200">
                            📅
                        </div>
                        <div>
                            <span class="block text-slate-900 font-bold">Matches &amp; Schedule</span>
                            <span class="text-[10px] text-slate-400 font-normal">View &amp; manage fixtures</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 p-2 rounded-xl text-slate-700 hover:bg-slate-50 hover:text-brand-red transition-all group">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-sm font-black group-hover:scale-105 transition-transform border border-slate-200">
                            👥
                        </div>
                        <div>
                            <span class="block text-slate-900 font-bold">User Directory</span>
                            <span class="text-[10px] text-slate-400 font-normal">Manage accounts &amp; athletes</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.achievements.index') }}" class="flex items-center gap-3 p-2 rounded-xl text-slate-700 hover:bg-slate-50 hover:text-brand-red transition-all group">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center text-sm font-black group-hover:scale-105 transition-transform border border-amber-200">
                            🏆
                        </div>
                        <div>
                            <span class="block text-slate-900 font-bold">Achievement Templates</span>
                            <span class="text-[10px] text-slate-400 font-normal">Medals, badges &amp; milestones</span>
                        </div>
                    </a>
                </div>
            </x-card>

            <div class="bg-gradient-to-br from-amber-50/60 to-red-50/40 rounded-2xl border border-amber-200/80 p-5 text-xs text-slate-700 space-y-2">
                <span class="text-[10px] font-black uppercase text-amber-900 tracking-wider block">REALTIME CLOUD SYNC</span>
                <p class="font-semibold text-slate-800 leading-relaxed">
                    Scoreboard changes updated on this control panel broadcast live to web viewers and mobile app users instantly.
                </p>
            </div>
        </div>

    </div>

@endsection
