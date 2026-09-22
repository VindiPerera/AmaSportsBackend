@extends('public.layouts.app')

@section('title', 'AmaX — The Professional Sports Platform')
@section('meta_description', 'AmaX — Live sports scores, athlete profiles, match schedules, and performance analytics. Track cricket, badminton, football, and 20+ sports.')

@section('content')

{{-- ═══ 1. HERO SECTION (Dynamic Split Hero with Sports Imagery) ═══════════════ --}}
<section class="relative overflow-hidden pt-8 pb-16 sm:pt-14 sm:pb-20 bg-gradient-to-b from-white via-[#F8F9FB] to-[#F8F9FB] border-b border-slate-200/60">
    {{-- Decorative Ambient Glows --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-24 left-1/4 w-[600px] h-[320px] bg-red-500/8 blur-[120px] rounded-full"></div>
        <div class="absolute top-1/3 right-10 w-96 h-96 bg-amber-400/10 blur-[120px] rounded-full"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:24px_24px] opacity-35"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">

            {{-- Left Column: Hero Copy & Actions --}}
            <div class="lg:col-span-7 text-center lg:text-left">
                {{-- Live Matchday Pill --}}
                @if($liveMatches->isNotEmpty())
                    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-red-50 border border-red-200 text-brand-red text-xs font-bold uppercase tracking-wider mb-6 shadow-xs animate-pulse-glow">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-red opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-brand-red"></span>
                        </span>
                        <span>{{ $liveMatches->count() }} Live {{ Str::plural('Match', $liveMatches->count()) }} In Progress</span>
                    </div>
                @else
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider mb-6 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-brand-gold"></span>
                        <span>The Digital Arena For Every Athlete</span>
                    </div>
                @endif

                {{-- Hero Headline with Fire Gradient --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-brand-charcoal leading-[1.1]">
                    Make your sporting story <br class="hidden sm:inline" />
                    <span class="text-gradient-fire relative inline-block">
                        ready for opportunity.
                    </span>
                </h1>

                <p class="mt-6 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-normal">
                    One clear, credible platform for selection, recruitment, and live matchday tracking across cricket, football, racket sports, and 20+ disciplines.
                </p>

                {{-- CTA Buttons --}}
                <div class="mt-8 flex flex-wrap items-center justify-center lg:justify-start gap-4">
                    <x-button href="{{ route('register') }}" variant="primary" size="lg" class="shadow-md">
                        <span>Build Athlete Passport</span>
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </x-button>
                    <x-button href="{{ route('public.matches') }}" variant="secondary" size="lg">
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-red"></span>
                            <span>Live Matchday Center</span>
                        </span>
                    </x-button>
                </div>

                {{-- Trust Metrics Strip --}}
                <div class="mt-10 pt-6 border-t border-slate-200/70 grid grid-cols-3 gap-4 text-center lg:text-left max-w-lg mx-auto lg:mx-0">
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-brand-charcoal">24+</div>
                        <div class="text-xs font-semibold text-slate-500 mt-0.5">Sports Disciplines</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-brand-red">Live</div>
                        <div class="text-xs font-semibold text-slate-500 mt-0.5">Ball-by-Ball Tracking</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900">100%</div>
                        <div class="text-xs font-semibold text-slate-500 mt-0.5">Verified Passports</div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Dynamic Action Banner with Floating HUD Cards --}}
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-md lg:max-w-none">
                    {{-- Glowing Backing --}}
                    <div class="absolute -inset-1.5 bg-gradient-to-tr from-brand-red/25 to-brand-gold/25 rounded-3xl blur-lg opacity-70"></div>

                    {{-- Main Hero Image Card --}}
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-white/60 aspect-[4/3] sm:aspect-[16/11] bg-slate-900">
                        <img
                            src="{{ asset('images/hero-sports.jpg') }}"
                            alt="Athletes in Action on AmaX"
                            class="w-full h-full object-cover object-center transform transition-transform duration-700 hover:scale-105"
                            loading="eager"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/20"></div>

                        {{-- Floating HUD 1 (Top Right): Live Match Preview --}}
                        <div class="absolute top-4 right-4 animate-float-slow">
                            <div class="glass-dark-hud text-white rounded-2xl px-4 py-2.5 shadow-xl flex items-center gap-3">
                                <span class="relative flex h-2.5 w-2.5 shrink-0">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-red opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-brand-red"></span>
                                </span>
                                <div>
                                    <div class="text-[10px] font-black uppercase tracking-wider text-brand-gold">MATCHDAY LIVE</div>
                                    <div class="text-xs font-bold text-white leading-tight">Ball-by-Ball Engine</div>
                                </div>
                            </div>
                        </div>

                        {{-- Floating HUD 2 (Bottom Left): Verified Athlete Badge --}}
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="glass-dark-hud text-white rounded-2xl p-3.5 shadow-xl flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-red to-amber-500 text-white font-black flex items-center justify-center text-sm shadow-md shrink-0">
                                        ⚡
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-extrabold text-white truncate flex items-center gap-1.5">
                                            <span>Athlete Talent Passport</span>
                                            <span class="inline-block w-3.5 h-3.5 text-brand-gold">✓</span>
                                        </div>
                                        <div class="text-[11px] text-slate-300 font-medium">Scouted • Ranked • Verified</div>
                                    </div>
                                </div>
                                <a href="{{ route('public.matches') }}" class="shrink-0 px-2.5 py-1 rounded-lg bg-white/15 hover:bg-white/25 text-white text-[10px] font-bold transition-colors">
                                    View →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ═══ 2. PROOF STRIP (Modernized Feature Highlights) ════════════════════════ --}}
<section class="border-b border-slate-200/80 bg-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
            <div class="card-modern p-5 card-modern-hover flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-red-50 text-brand-red font-black text-sm flex items-center justify-center shrink-0 border border-red-200/60">
                    01
                </div>
                <div>
                    <h4 class="text-sm font-black text-brand-charcoal">One Sporting Passport</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Centralize lifetime statistics, verified match logs, affiliated clubs, and honors in a single credible link.</p>
                </div>
            </div>
            <div class="card-modern p-5 card-modern-hover flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-800 font-black text-sm flex items-center justify-center shrink-0 border border-amber-200/60">
                    02
                </div>
                <div>
                    <h4 class="text-sm font-black text-brand-charcoal">Live Matchday Scoring</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Ball-by-ball, set-by-set digital scoreboard with automated career aggregations updated in real time.</p>
                </div>
            </div>
            <div class="card-modern p-5 card-modern-hover flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-800 font-black text-sm flex items-center justify-center shrink-0 border border-slate-200">
                    03
                </div>
                <div>
                    <h4 class="text-sm font-black text-brand-charcoal">Talent Discovery & Scouting</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Filter athletes by discipline, strike rates, batting averages, and tournament milestones ready for recruiters.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ 3. GLOBAL ATHLETE SEARCH & CAREER ANALYTICS ══════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-xs">
        <div class="max-w-3xl mb-8">
            <span class="text-xs font-extrabold uppercase tracking-widest text-brand-red">TALENT DIRECTORY</span>
            <h2 class="text-2xl sm:text-3xl font-black text-brand-charcoal tracking-tight mt-1">
                Explore Verified Athletes &amp; Career Records
            </h2>
            <p class="text-sm text-slate-500 mt-2">
                Look up any athlete to evaluate verified match history, batting &amp; bowling averages, tournament medals, and career progression.
            </p>
        </div>

        {{-- Search Input Form with Autocomplete --}}
        <form method="GET" action="{{ route('public.home') }}" class="flex flex-col sm:flex-row gap-3 relative">
            <div class="flex-1 relative">
                <input
                    type="text"
                    id="hero-player-search"
                    name="q"
                    value="{{ $query }}"
                    placeholder="Search athlete by name (e.g. Dasun, Kusal, Warner)..."
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50/70 py-3.5 pl-12 pr-4 text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-brand-red focus:ring-2 focus:ring-brand-red/20 focus:outline-none transition-all shadow-2xs"
                    autocomplete="off"
                >
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                {{-- Live Floating Suggestions Dropdown --}}
                <div id="hero-search-dropdown"
                     class="hidden absolute top-full mt-2 left-0 right-0 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 max-h-96 overflow-y-auto py-2">
                </div>
            </div>
            <x-button type="submit" variant="primary" size="lg" class="shadow-sm">
                Search Directory
            </x-button>
            @if($query)
                <x-button href="{{ route('public.home') }}" variant="secondary" size="lg">
                    Clear Search
                </x-button>
            @endif
        </form>

        {{-- Search Results Display --}}
        @if ($searchResults !== null)
            @php
                $statLabels = [
                    'matches' => 'Matches',
                    'games' => 'Games',
                    'win_percentage' => 'Win %',
                    'goal_accuracy' => 'Goal Accuracy',
                    'points_per_match' => 'Pts / Match',
                    'champion' => 'Championships',
                    'second_place' => 'Runner-up',
                    'third_place' => 'Third Place',
                ];
                $percentKeys = ['win_percentage', 'goal_accuracy'];
                $statLabel = fn ($key) => $statLabels[$key] ?? \Illuminate\Support\Str::headline($key);
                $statValue = function ($key, $value) use ($percentKeys) {
                    if ($value === null) return '—';
                    return is_numeric($value) && in_array($key, $percentKeys, true)
                        ? number_format((float) $value, 1).'%'
                        : $value;
                };
                $sportIcons = [
                    'cricket' => '🏏', 'hockey' => '🏑', 'football' => '⚽', 'basketball' => '🏀', 'netball' => '🥅',
                    'rugby' => '🏉', 'boxing' => '🥊', 'karate' => '🥋', 'judo' => '🥋',
                    'chess' => '♟️', 'athletics' => '🏃', 'swimming' => '🏊', 'volleyball' => '🏐',
                    'beach-volleyball' => '🏖️', 'elle' => '🏏', 'base-ball' => '⚾',
                    'badminton' => '🏸', 'tennis' => '🎾', 'table-tennis' => '🏓',
                ];
            @endphp

            <div class="mt-10 pt-8 border-t border-slate-100">
                @if ($searchResults->isEmpty())
                    <x-empty-state
                        title="No athletes found matching '{{ $query }}'"
                        message="Try searching by another first or last name, or check your spelling."
                    />
                @else
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400">
                            Search Results ({{ $searchResults->count() }})
                        </span>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        @foreach ($searchResults as $result)
                            @php
                                $player = $result['player'];
                                $displayName = $result['display_name'];
                                $sports = $result['sports'];
                            @endphp

                            <x-card class="border-slate-200">
                                {{-- Player Card Header --}}
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                                    <div class="flex items-center gap-4">
                                        <x-avatar :name="$displayName" size="xl" ring />
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-lg font-black text-brand-charcoal">{{ $displayName }}</h3>
                                                <x-badge variant="gold" size="sm">Verified</x-badge>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                                @forelse($sports as $s)
                                                    <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded-full">
                                                        {{ $sportIcons[$s['sport']->slug] ?? '🏅' }} {{ $s['sport']->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-slate-400">No sport profiles attached</span>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                    <x-button href="{{ route('public.players.show', ['player' => $player->id]) }}" variant="primary" size="sm">
                                        View Full Profile →
                                    </x-button>
                                </div>

                                {{-- Sports Breakdown --}}
                                @if($sports->isNotEmpty())
                                    <div class="pt-5 space-y-6">
                                        @foreach($sports as $sportResult)
                                            @php
                                                $sport = $sportResult['sport'];
                                                $team = $sportResult['team'];
                                                $analysis = $sportResult['analysis'];
                                            @endphp

                                            <div class="{{ !$loop->last ? 'border-b border-slate-100 pb-5' : '' }}">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-base">{{ $sportIcons[$sport->slug] ?? '🏅' }}</span>
                                                        <span class="font-bold text-sm text-slate-900">{{ $sport->name }}</span>
                                                        @if($team)
                                                            <span class="text-xs text-slate-400 font-medium">🛡️ {{ $team }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if($sportResult['type'] === 'unsupported')
                                                    <p class="text-xs text-slate-400 italic">Stats tracking for {{ $sport->name }} is coming soon.</p>
                                                @elseif($sportResult['type'] === 'cricket')
                                                    @php
                                                        $batting = $analysis['overview']['career_batting'] ?? null;
                                                        $bowling = $analysis['overview']['career_bowling'] ?? null;
                                                    @endphp

                                                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2.5 text-center">
                                                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                                            <div class="text-[10px] font-bold text-slate-400 uppercase">Matches</div>
                                                            <div class="text-sm font-extrabold text-slate-800 mt-0.5">{{ $batting['matches'] ?? 0 }}</div>
                                                        </div>
                                                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                                            <div class="text-[10px] font-bold text-slate-400 uppercase">Runs</div>
                                                            <div class="text-sm font-extrabold text-brand-red mt-0.5">{{ $batting['runs'] ?? 0 }}</div>
                                                        </div>
                                                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                                            <div class="text-[10px] font-bold text-slate-400 uppercase">Bat Avg</div>
                                                            <div class="text-sm font-extrabold text-slate-800 mt-0.5">{{ $batting['average'] !== null ? number_format($batting['average'], 2) : '—' }}</div>
                                                        </div>
                                                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                                            <div class="text-[10px] font-bold text-slate-400 uppercase">High Score</div>
                                                            <div class="text-sm font-extrabold text-slate-800 mt-0.5">{{ $batting['highest_score'] ?? '—' }}</div>
                                                        </div>
                                                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                                            <div class="text-[10px] font-bold text-slate-400 uppercase">Wickets</div>
                                                            <div class="text-sm font-extrabold text-slate-800 mt-0.5">{{ $bowling['wickets'] ?? 0 }}</div>
                                                        </div>
                                                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                                            <div class="text-[10px] font-bold text-slate-400 uppercase">Economy</div>
                                                            <div class="text-sm font-extrabold text-slate-800 mt-0.5">{{ $bowling['economy'] !== null ? number_format($bowling['economy'], 2) : '—' }}</div>
                                                        </div>
                                                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                                            <div class="text-[10px] font-bold text-slate-400 uppercase">Best Bowl</div>
                                                            <div class="text-sm font-extrabold text-slate-800 mt-0.5">{{ $bowling['best_bowling'] ?? '—' }}</div>
                                                        </div>
                                                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                                            <div class="text-[10px] font-bold text-slate-400 uppercase">Strike Rate</div>
                                                            <div class="text-sm font-extrabold text-slate-800 mt-0.5">{{ $batting['strike_rate'] ? number_format($batting['strike_rate'], 1) : '—' }}</div>
                                                        </div>
                                                    </div>
                                                @else
                                                    @if(($analysis['has_any_stats'] ?? false) && !empty($analysis['overview']))
                                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-center">
                                                            @foreach($analysis['overview'] as $key => $value)
                                                                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                                                    <div class="text-[10px] font-bold text-slate-400 uppercase">{{ $statLabel($key) }}</div>
                                                                    <div class="text-sm font-extrabold text-slate-800 mt-0.5">{{ $statValue($key, $value) }}</div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <p class="text-xs text-slate-400 italic">No {{ $sport->name }} stats recorded yet.</p>
                                                    @endif
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </x-card>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>

{{-- ═══ 4. LIVE MATCHES SECTION ═════════════════════════════════════════════ --}}
@if($liveMatches->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <span class="text-xs font-extrabold uppercase tracking-widest text-brand-red flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-brand-red live-dot-pulse"></span>
                ACTION IN PROGRESS
            </span>
            <h2 class="text-2xl font-black text-brand-charcoal tracking-tight mt-1">Live Matchday Center</h2>
        </div>
        <x-button href="{{ route('public.matches', ['status' => 'live']) }}" variant="secondary" size="sm">
            View All Live ({{ $liveMatches->count() }}) →
        </x-button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($liveMatches as $match)
            <x-card hover class="flex flex-col justify-between">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-xs font-bold text-slate-500 uppercase">{{ $match->sport->name }}</span>
                    <x-badge variant="live" size="sm">LIVE</x-badge>
                </div>

                <div class="py-6 text-center space-y-2">
                    <div class="text-base font-extrabold text-slate-900">{{ $match->homeTeam->name }}</div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">vs</div>
                    <div class="text-base font-extrabold text-slate-900">{{ $match->awayTeam->name }}</div>
                    @if($match->venue)
                        <div class="text-xs text-slate-400 font-medium pt-2">📍 {{ $match->venue }}</div>
                    @endif
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500 font-medium">Real-time tracker</span>
                    <a href="{{ route('public.matches') }}" class="text-xs font-extrabold text-brand-red hover:underline">
                        Open Scoreboard →
                    </a>
                </div>
            </x-card>
        @endforeach
    </div>
</section>
@endif

{{-- ═══ 5. UPCOMING FIXTURES ═════════════════════════════════════════════════ --}}
@if($upcomingMatches->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <span class="text-xs font-extrabold uppercase tracking-widest text-slate-400">SCHEDULED FIXTURES</span>
            <h2 class="text-2xl font-black text-brand-charcoal tracking-tight mt-1">Upcoming Matches</h2>
        </div>
        <x-button href="{{ route('public.matches', ['status' => 'upcoming']) }}" variant="secondary" size="sm">
            Full Calendar →
        </x-button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 divide-y divide-slate-100 overflow-hidden shadow-xs">
        @foreach($upcomingMatches as $match)
            <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/70 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-800 border border-amber-200/80 flex flex-col items-center justify-center shrink-0">
                        <span class="text-[10px] font-bold uppercase leading-none">{{ $match->scheduled_at?->format('M') ?? 'TBA' }}</span>
                        <span class="text-base font-black leading-none mt-0.5">{{ $match->scheduled_at?->format('d') ?? '—' }}</span>
                    </div>
                    <div>
                        <div class="text-sm sm:text-base font-bold text-brand-charcoal">
                            {{ $match->homeTeam->name }} <span class="text-slate-400 font-normal">vs</span> {{ $match->awayTeam->name }}
                        </div>
                        <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-2">
                            <span>{{ $match->scheduled_at?->format('g:i A') ?? 'Time TBA' }}</span>
                            @if($match->venue)
                                <span>•</span>
                                <span>{{ $match->venue }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 self-end sm:self-center">
                    <x-badge variant="neutral">{{ $match->sport->name }}</x-badge>
                    <x-button href="{{ route('public.matches') }}" variant="secondary" size="sm">Details</x-button>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- ═══ 6. HOW IT WORKS (PlayerProfile.io 3-step Journey) ════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center max-w-2xl mx-auto mb-12">
        <span class="text-xs font-extrabold uppercase tracking-widest text-brand-red">HOW IT WORKS</span>
        <h2 class="text-3xl font-black text-brand-charcoal tracking-tight mt-1">
            Your sporting story, structured for the moment it matters.
        </h2>
        <p class="text-sm text-slate-500 mt-2">
            Turn scattered matches and scores into a verified digital profile that scouts, coaches, and recruiters can trust.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-card class="relative">
            <span class="text-3xl font-black text-brand-red/20 mb-3 block">01</span>
            <h3 class="text-base font-bold text-slate-900 mb-1.5">Build your profile</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Add career milestones, team affiliations, match performance points, and media in one guided workflow.
            </p>
        </x-card>

        <x-card class="relative">
            <span class="text-3xl font-black text-brand-gold/30 mb-3 block">02</span>
            <h3 class="text-base font-bold text-slate-900 mb-1.5">Track live performance</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Matches are scored digitally with automated averages, run rates, bowling figures, and win records calculated on the fly.
            </p>
        </x-card>

        <x-card class="relative">
            <span class="text-3xl font-black text-slate-200 mb-3 block">03</span>
            <h3 class="text-base font-bold text-slate-900 mb-1.5">Share with confidence</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Publish a clean public link, downloadable sports CV, or private link ready for scouts and clubs anywhere in the world.
            </p>
        </x-card>
    </div>
</section>

{{-- ═══ 7. SUPPORTED SPORTS DIRECTORY ═════════════════════════════════════════ --}}
<section id="sports" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="card-modern p-8 sm:p-12 text-center bg-gradient-to-b from-white to-slate-50/50">
        <span class="text-xs font-black uppercase tracking-widest text-brand-red">DISCIPLINE DIRECTORY</span>
        <h3 class="text-2xl sm:text-3xl font-black text-brand-charcoal mt-1 mb-3">Multi-Sport Engine Built for Athletes</h3>
        <p class="text-sm text-slate-500 max-w-xl mx-auto mb-8 leading-relaxed">
            Engineered with customized point tables, ball-by-ball score tracking, and automated career aggregations for over 20 global sports.
        </p>
        <div class="flex flex-wrap gap-2.5 justify-center max-w-4xl mx-auto">
            @php
                $allDisciplines = [
                    ['Cricket', '🏏'], ['Football', '⚽'], ['Basketball', '🏀'], ['Badminton', '🏸'],
                    ['Tennis', '🎾'], ['Table Tennis', '🏓'], ['Volleyball', '🏐'], ['Rugby', '🏉'],
                    ['Athletics', '🏃'], ['Swimming', '🏊'], ['Boxing', '🥊'], ['Karate', '🥋'],
                    ['Judo', '🥋'], ['Hockey', '🏑'], ['Netball', '🥅'], ['Baseball', '⚾'],
                    ['Chess', '♟️'], ['Beach Volleyball', '🏖️'], ['Elle', '🏏'], ['Kabaddi', '🤼']
                ];
            @endphp
            @foreach($allDisciplines as [$sportName, $icon])
                <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-extrabold bg-white text-slate-800 border border-slate-200/90 shadow-2xs hover:border-brand-red hover:text-brand-red hover:-translate-y-0.5 transition-all cursor-default">
                    <span>{{ $icon }}</span>
                    <span>{{ $sportName }}</span>
                </span>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ 8. FINAL CALL TO ACTION (Cinematic Stadium Night Arena) ════════════════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-20">
    <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-800 text-center text-white">
        {{-- High Resolution Cinematic Stadium Background --}}
        <img
            src="{{ asset('images/stadium-cta.jpg') }}"
            alt="Stadium Atmosphere"
            class="absolute inset-0 w-full h-full object-cover object-center"
        />
        {{-- Dark Gradient & Atmospheric Overlay --}}
        <div class="absolute inset-0 hero-cinematic-overlay"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-brand-red/20 via-transparent to-brand-gold/15 pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl mx-auto p-8 sm:p-16">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-gold/20 text-brand-gold border border-brand-gold/40 text-xs font-black uppercase tracking-widest mb-4 badge-glow-gold">
                <span>⚡ JOIN THE PLATFORM</span>
            </span>
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight mt-2 mb-5 leading-tight">
                Take Your Sporting Journey <br class="hidden sm:inline" />
                <span class="text-gradient-gold">Beyond the Boundary.</span>
            </h2>
            <p class="text-sm sm:text-base text-slate-200 max-w-xl mx-auto mb-8 leading-relaxed font-normal">
                Join thousands of athletes, team managers, and sports clubs tracking live ball-by-ball matches and verified talent passports on AmaX.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <x-button href="{{ route('register') }}" variant="primary" size="lg" class="shadow-lg shadow-brand-red/30">
                    <span>Create Athlete Profile</span>
                    <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </x-button>
                <x-button href="{{ route('public.matches') }}" variant="secondary" size="lg" class="bg-white/10 hover:bg-white/20 text-white border-white/20 backdrop-blur-md">
                    <span>Explore Live Matches →</span>
                </x-button>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initPlayerAutocomplete('hero-player-search', 'hero-search-dropdown');
    });
</script>
@endpush

@endsection
