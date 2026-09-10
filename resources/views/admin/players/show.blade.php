@extends('admin.layouts.app')

@section('title', $player->full_name)

@section('content')
    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-[#0366D6] mb-4">
        ← Back to search
    </a>

    {{-- Player header --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs mb-6 flex items-center gap-5">
        @if ($photoUrl)
            <img src="{{ $photoUrl }}" alt="{{ $player->full_name }}" class="w-16 h-16 rounded-2xl object-cover border border-slate-200">
        @else
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-[#0366D6] flex items-center justify-center text-xl font-black">
                {{ mb_substr($player->full_name ?? '?', 0, 1) }}
            </div>
        @endif

        <div>
            <h1 class="text-lg font-black text-slate-900">{{ $player->full_name }}</h1>
            <p class="text-xs font-semibold text-slate-500 mt-0.5">
                {{ $team ?? 'No team' }}
                @if ($player->country) • {{ $player->country }} @endif
                • Cricket
            </p>
        </div>
    </div>

    @if (! $analysis['has_any_stats'])
        <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-10 text-center">
            <p class="text-sm font-bold text-slate-600">No stats recorded for this player yet.</p>
        </div>
    @else
        {{-- Overview --}}
        <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-6">
            @foreach ([
                'Matches' => $analysis['overview']['matches'],
                'Runs' => $analysis['overview']['runs'],
                'Wickets' => $analysis['overview']['wickets'],
                'Bat Avg' => $analysis['overview']['batting_average'] ?? '—',
                'Bowl Avg' => $analysis['overview']['bowling_average'] ?? '—',
                'Win %' => $analysis['overview']['win_percentage'] !== null ? $analysis['overview']['win_percentage'].'%' : '—',
            ] as $label => $value)
                <div class="bg-white rounded-2xl border border-slate-200 p-4 text-center shadow-xs">
                    <p class="text-lg font-black text-slate-900">{{ $value }}</p>
                    <p class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider mt-1">{{ $label }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Batting --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <h2 class="text-xs font-extrabold text-slate-900 tracking-wider uppercase mb-3">Batting — Career</h2>
                <dl class="grid grid-cols-2 gap-y-2 text-xs">
                    @foreach ([
                        'Innings' => $analysis['batting']['career']['innings'],
                        'Not Outs' => $analysis['batting']['career']['not_outs'],
                        'Highest Score' => $analysis['batting']['career']['highest_score'] ?? '—',
                        'Strike Rate' => $analysis['batting']['career']['strike_rate'] ?? '—',
                        '100s / 50s' => $analysis['batting']['career']['hundreds'].' / '.$analysis['batting']['career']['fifties'],
                        '4s / 6s' => $analysis['boundaries']['fours'].' / '.$analysis['boundaries']['sixes'],
                    ] as $label => $value)
                        <dt class="text-slate-500 font-semibold">{{ $label }}</dt>
                        <dd class="text-right font-extrabold text-slate-900">{{ $value }}</dd>
                    @endforeach
                </dl>
            </div>

            {{-- Bowling --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <h2 class="text-xs font-extrabold text-slate-900 tracking-wider uppercase mb-3">Bowling — Career</h2>
                <dl class="grid grid-cols-2 gap-y-2 text-xs">
                    @foreach ([
                        'Balls' => $analysis['bowling']['career']['balls'],
                        'Runs Conceded' => $analysis['bowling']['career']['runs_conceded'],
                        'Economy' => $analysis['bowling']['career']['economy'] ?? '—',
                        'Best (Innings)' => $analysis['bowling']['career']['best_bowling_innings'] ?? '—',
                        'Best (Match)' => $analysis['bowling']['career']['best_bowling_match'] ?? '—',
                        '4w / 5w / 10w' => $analysis['bowling']['career']['four_w'].' / '.$analysis['bowling']['career']['five_w'].' / '.$analysis['bowling']['career']['ten_w'],
                    ] as $label => $value)
                        <dt class="text-slate-500 font-semibold">{{ $label }}</dt>
                        <dd class="text-right font-extrabold text-slate-900">{{ $value }}</dd>
                    @endforeach
                </dl>
            </div>
        </div>

        {{-- Recent form --}}
        @if (! empty($analysis['recent_form']))
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs mt-6 overflow-x-auto">
                <h2 class="text-xs font-extrabold text-slate-900 tracking-wider uppercase mb-3">Recent Matches</h2>
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-left text-[10px] font-extrabold uppercase text-slate-400 border-b border-slate-100">
                            <th class="py-2 pr-3">Date</th>
                            <th class="py-2 pr-3">Opponent</th>
                            <th class="py-2 pr-3 text-right">Runs</th>
                            <th class="py-2 pr-3 text-right">Balls</th>
                            <th class="py-2 pr-3 text-right">SR</th>
                            <th class="py-2 pr-3 text-right">Wickets</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach (array_reverse($analysis['recent_form']) as $m)
                            <tr>
                                <td class="py-2 pr-3 font-semibold text-slate-700">{{ $m['match_date'] ?? '—' }}</td>
                                <td class="py-2 pr-3 font-semibold text-slate-700">{{ $m['opponent'] ?? '—' }}</td>
                                <td class="py-2 pr-3 text-right font-extrabold text-slate-900">{{ $m['runs'] ?? '—' }}</td>
                                <td class="py-2 pr-3 text-right text-slate-600">{{ $m['balls'] ?? '—' }}</td>
                                <td class="py-2 pr-3 text-right text-slate-600">{{ $m['strike_rate'] ?? '—' }}</td>
                                <td class="py-2 pr-3 text-right font-extrabold text-slate-900">{{ $m['wickets'] ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif
@endsection
