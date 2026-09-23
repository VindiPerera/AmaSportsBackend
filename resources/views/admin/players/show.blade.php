@extends('admin.layouts.app')

@section('title', $player->full_name . ' — Administrative Analysis')

@section('content')

    <div class="mb-4">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-brand-red transition-colors">
            ← Back to Dashboard
        </a>
    </div>

    {{-- Player Header Card --}}
    <x-card class="mb-6 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <x-avatar :src="$photoUrl" :name="$player->full_name" size="xl" ring />
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-black text-brand-charcoal">{{ $player->full_name }}</h1>
                        <x-badge variant="gold" size="sm">Scout Verified</x-badge>
                    </div>
                    <p class="text-xs font-semibold text-slate-500 mt-1">
                        {{ $team ?? 'Independent Athlete' }}
                        @if ($player->country) • 📍 {{ $player->country }} @endif
                        • Cricket Analysis Log
                    </p>
                </div>
            </div>

            <x-button href="{{ route('public.players.show', $player) }}" target="_blank" variant="secondary" size="sm">
                View Public Profile ↗
            </x-button>
        </div>
    </x-card>

    @if (! $analysis['has_any_stats'])
        <x-empty-state
            title="No stats recorded for this player yet"
            message="Career metrics and averages will populate as soon as this player participates in scored matches."
        />
    @else
        {{-- Overview Stat Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            <x-stat-card label="Matches" value="{{ $analysis['overview']['matches'] }}" />
            <x-stat-card label="Runs" value="{{ $analysis['overview']['runs'] }}" accent="red" />
            <x-stat-card label="Wickets" value="{{ $analysis['overview']['wickets'] }}" accent="gold" />
            <x-stat-card label="Bat Avg" value="{{ $analysis['overview']['batting_average'] ?? '—' }}" />
            <x-stat-card label="Bowl Avg" value="{{ $analysis['overview']['bowling_average'] ?? '—' }}" />
            <x-stat-card label="Win %" value="{{ $analysis['overview']['win_percentage'] !== null ? $analysis['overview']['win_percentage'].'%' : '—' }}" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Batting Career --}}
            <x-card>
                <x-slot:header>
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-900">Batting — Career Metrics</h2>
                </x-slot:header>

                <dl class="divide-y divide-slate-100 text-xs">
                    @foreach ([
                        'Innings' => $analysis['batting']['career']['innings'],
                        'Not Outs' => $analysis['batting']['career']['not_outs'],
                        'Highest Score' => $analysis['batting']['career']['highest_score'] ?? '—',
                        'Strike Rate' => $analysis['batting']['career']['strike_rate'] ?? '—',
                        '100s / 50s' => $analysis['batting']['career']['hundreds'].' / '.$analysis['batting']['career']['fifties'],
                        '4s / 6s' => $analysis['boundaries']['fours'].' / '.$analysis['boundaries']['sixes'],
                    ] as $label => $value)
                        <div class="py-2.5 flex justify-between">
                            <dt class="text-slate-500 font-semibold">{{ $label }}</dt>
                            <dd class="font-extrabold text-slate-900">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </x-card>

            {{-- Bowling Career --}}
            <x-card>
                <x-slot:header>
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-900">Bowling — Career Metrics</h2>
                </x-slot:header>

                <dl class="divide-y divide-slate-100 text-xs">
                    @foreach ([
                        'Balls Bowled' => $analysis['bowling']['career']['balls'],
                        'Runs Conceded' => $analysis['bowling']['career']['runs_conceded'],
                        'Economy Rate' => $analysis['bowling']['career']['economy'] ?? '—',
                        'Best (Innings)' => $analysis['bowling']['career']['best_bowling_innings'] ?? '—',
                        'Best (Match)' => $analysis['bowling']['career']['best_bowling_match'] ?? '—',
                        '4w / 5w / 10w' => $analysis['bowling']['career']['four_w'].' / '.$analysis['bowling']['career']['five_w'].' / '.$analysis['bowling']['career']['ten_w'],
                    ] as $label => $value)
                        <div class="py-2.5 flex justify-between">
                            <dt class="text-slate-500 font-semibold">{{ $label }}</dt>
                            <dd class="font-extrabold text-slate-900">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </x-card>
        </div>

        {{-- Recent Form Table --}}
        @if (! empty($analysis['recent_form']))
            <x-card class="mt-6 p-0 overflow-hidden">
                <x-slot:header>
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-900">Recent Form Log</h2>
                </x-slot:header>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] font-extrabold uppercase text-slate-400">
                                <th class="py-3 px-4">Date</th>
                                <th class="py-3 px-4">Opponent</th>
                                <th class="py-3 px-3 text-right">Runs</th>
                                <th class="py-3 px-3 text-right">Balls</th>
                                <th class="py-3 px-3 text-right">Wickets</th>
                                <th class="py-3 px-3 text-right">Overs</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach ($analysis['recent_form'] as $match)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-3 px-4 font-bold text-slate-900">{{ $match['match_date'] ?? '—' }}</td>
                                    <td class="py-3 px-4 font-semibold text-slate-800">vs {{ $match['opponent'] ?? 'Opponent' }}</td>
                                    <td class="py-3 px-3 text-right font-extrabold text-brand-red">{{ $match['runs_scored'] ?? '—' }}</td>
                                    <td class="py-3 px-3 text-right text-slate-500">{{ $match['balls_faced'] ?? '—' }}</td>
                                    <td class="py-3 px-3 text-right font-extrabold text-slate-900">{{ $match['wickets_taken'] ?? '—' }}</td>
                                    <td class="py-3 px-3 text-right text-slate-500">{{ $match['overs_bowled'] ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>
        @endif
    @endif

@endsection
