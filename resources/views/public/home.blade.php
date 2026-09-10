@extends('public.layouts.app')

@section('title', 'AmaX')
@section('meta_description', 'AmaX — Live sports scores, match schedules and player analytics. Track cricket, football, basketball and more in real time.')

@section('content')

{{-- ═══ HERO SECTION ═══════════════════════════════════════════════════════ --}}
<section style="position: relative; overflow: hidden; padding: 5rem 1.5rem 4rem; text-align: center;">
    {{-- Radial gradient background --}}
    <div style="position: absolute; inset: 0; background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(245,158,11,0.15) 0%, transparent 70%); pointer-events: none;"></div>
    <div style="position: absolute; top: -6rem; left: 50%; transform: translateX(-50%); width: 700px; height: 700px; background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, transparent 70%); pointer-events: none;"></div>

    <div style="position: relative; max-width: 800px; margin: 0 auto;">
        {{-- Live badge --}}
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25); border-radius: 2rem; padding: 0.375rem 1rem; font-size: 0.75rem; font-weight: 700; color: #f87171; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1.5rem;">
            <span style="width: 0.5rem; height: 0.5rem; background: #ef4444; border-radius: 50%; animation: livepulse 1.2s ease-in-out infinite;"></span>
            Live Now — {{ $liveMatches->count() }} {{ Str::plural('Match', $liveMatches->count()) }} in Progress
        </div>

        <h1 style="font-size: clamp(2.25rem, 6vw, 4rem); font-weight: 900; line-height: 1.1; letter-spacing: -0.03em; color: #fff; margin-bottom: 1.25rem;">
            Every Sport.<br>
            <span style="background: linear-gradient(135deg, #f59e0b, #fcd34d); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Live.</span>
        </h1>

        <p style="font-size: 1.125rem; color: rgba(148,163,184,0.85); line-height: 1.7; max-width: 560px; margin: 0 auto 2.5rem;">
            Track real-time scores, upcoming fixtures and player performance across cricket, football, basketball and 20+ more sports — on web and mobile.
        </p>

        <div style="display: flex; flex-wrap: wrap; gap: 0.875rem; justify-content: center;">
            <a href="{{ route('register') }}" class="btn-primary" style="padding: 0.75rem 1.75rem; font-size: 0.9375rem;">
                Get Started Free
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <a href="{{ route('public.matches') }}"
               style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.75rem; font-size: 0.9375rem; font-weight: 700; color: rgba(255,255,255,0.8); border: 1px solid rgba(255,255,255,0.15); border-radius: 0.625rem; text-decoration: none; transition: all 0.2s;"
               onmouseover="this.style.borderColor='rgba(255,255,255,0.35)'; this.style.color='#fff';"
               onmouseout="this.style.borderColor='rgba(255,255,255,0.15)'; this.style.color='rgba(255,255,255,0.8)';">
                View Matches
            </a>
        </div>
    </div>
</section>

{{-- ═══ GLOBAL PLAYER SEARCH & STATS ════════════════════════════════════════ --}}
<section style="max-width: 1280px; margin: 0 auto; padding: 0 1.5rem 2.5rem;">
    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 1.25rem; padding: 2rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;">
            <div>
                <h2 style="font-weight: 800; font-size: 1.25rem; color: #fff; display: flex; align-items: center; gap: 0.5rem;">
                    🔍 Global Player Search &amp; Career Stats
                </h2>
                <p style="font-size: 0.8125rem; color: rgba(148,163,184,0.75); margin-top: 0.25rem;">Search any registered player to inspect career analytics, batting/bowling averages, and recent form.</p>
            </div>
        </div>

        {{-- Search Input Form --}}
        <form method="GET" action="{{ route('public.home') }}" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 260px; position: relative;">
                <input
                    type="text"
                    name="q"
                    value="{{ $query }}"
                    placeholder="Search player by name (e.g. Dasun, Kusal, Wanindu)..."
                    class="form-input"
                    style="padding-left: 2.75rem; background: rgba(255,255,255,0.06);"
                >
                <div style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: rgba(148,163,184,0.6); pointer-events: none;">
                    🔍
                </div>
            </div>
            <button type="submit" class="btn-primary" style="padding: 0.75rem 1.5rem;">
                Search Players
            </button>
            @if($query)
                <a href="{{ route('public.home') }}" style="display: inline-flex; align-items: center; padding: 0.75rem 1.25rem; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); border-radius: 0.625rem; color: rgba(255,255,255,0.8); font-size: 0.8125rem; font-weight: 700; text-decoration: none;">
                    Clear Search
                </a>
            @endif
        </form>

        {{-- Search Results Display --}}
        @if ($searchResults !== null)
            <div style="margin-top: 2rem;">
                @if ($searchResults->isEmpty())
                    <div style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1); border-radius: 1rem; padding: 2.5rem; text-align: center; color: rgba(148,163,184,0.7);">
                        <p style="font-weight: 700; font-size: 0.9375rem;">No players found matching "{{ $query }}"</p>
                        <p style="font-size: 0.8125rem; margin-top: 0.375rem; color: rgba(100,116,139,0.7);">Try searching by another first or last name.</p>
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        @foreach ($searchResults as $result)
                            @php
                                $player = $result['player'];
                                $team = $result['team'];
                                $analysis = $result['analysis'];
                                $batting = $analysis['overview']['career_batting'] ?? null;
                                $bowling = $analysis['overview']['career_bowling'] ?? null;
                                $recentForm = $analysis['recent_form'] ?? collect();
                            @endphp

                            <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(245,158,11,0.25); border-radius: 1.25rem; padding: 1.5rem; transition: all 0.2s;">

                                {{-- Player Profile Header --}}
                                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem;">
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <div style="width: 3.25rem; height: 3.25rem; background: linear-gradient(135deg, #f59e0b, #eab308); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1.25rem; color: #111827;">
                                            {{ strtoupper(substr($player->full_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h3 style="font-weight: 800; font-size: 1.25rem; color: #fff; margin-bottom: 0.15rem;">{{ $player->full_name }}</h3>
                                            <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.8125rem; color: rgba(148,163,184,0.8);">
                                                <span>🛡️ {{ $team }}</span>
                                                <span>•</span>
                                                <span style="color: #fbbf24; font-weight: 700;">Cricket</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Stats Grid: Batting & Bowling --}}
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">

                                    {{-- Batting Stats Card --}}
                                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 0.875rem; padding: 1.25rem;">
                                        <div style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #fbbf24; margin-bottom: 0.875rem; display: flex; align-items: center; gap: 0.375rem;">
                                            🏏 Batting Career Statistics
                                        </div>
                                        @if($batting && ($batting['matches'] > 0 || $batting['runs'] > 0))
                                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; text-align: center;">
                                                <div style="background: rgba(255,255,255,0.04); padding: 0.625rem; border-radius: 0.5rem;">
                                                    <div style="font-size: 0.65rem; color: rgba(148,163,184,0.6); text-transform: uppercase; font-weight: 700;">Matches</div>
                                                    <div style="font-weight: 900; font-size: 1rem; color: #fff;">{{ $batting['matches'] }}</div>
                                                </div>
                                                <div style="background: rgba(255,255,255,0.04); padding: 0.625rem; border-radius: 0.5rem;">
                                                    <div style="font-size: 0.65rem; color: rgba(148,163,184,0.6); text-transform: uppercase; font-weight: 700;">Runs</div>
                                                    <div style="font-weight: 900; font-size: 1rem; color: #f59e0b;">{{ $batting['runs'] }}</div>
                                                </div>
                                                <div style="background: rgba(255,255,255,0.04); padding: 0.625rem; border-radius: 0.5rem;">
                                                    <div style="font-size: 0.65rem; color: rgba(148,163,184,0.6); text-transform: uppercase; font-weight: 700;">Average</div>
                                                    <div style="font-weight: 900; font-size: 1rem; color: #fff;">{{ $batting['average'] !== null ? number_format($batting['average'], 2) : '—' }}</div>
                                                </div>
                                                <div style="background: rgba(255,255,255,0.04); padding: 0.625rem; border-radius: 0.5rem;">
                                                    <div style="font-size: 0.65rem; color: rgba(148,163,184,0.6); text-transform: uppercase; font-weight: 700;">High Score</div>
                                                    <div style="font-weight: 900; font-size: 1rem; color: #fff;">{{ $batting['highest_score'] ?? '—' }}</div>
                                                </div>
                                            </div>
                                            <div style="display: flex; gap: 1rem; justify-content: space-around; margin-top: 0.875rem; font-size: 0.75rem; color: rgba(203,213,225,0.8); font-weight: 600;">
                                                <span>Strike Rate: <strong style="color: #fff;">{{ $batting['strike_rate'] ? number_format($batting['strike_rate'], 1) : '—' }}</strong></span>
                                                <span>100s: <strong style="color: #fbbf24;">{{ $batting['hundreds'] }}</strong></span>
                                                <span>50s: <strong style="color: #fbbf24;">{{ $batting['fifties'] }}</strong></span>
                                            </div>
                                        @else
                                            <p style="font-size: 0.8125rem; color: rgba(100,116,139,0.7); text-align: center; padding: 1rem 0;">No batting stats recorded yet.</p>
                                        @endif
                                    </div>

                                    {{-- Bowling Stats Card --}}
                                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 0.875rem; padding: 1.25rem;">
                                        <div style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #818cf8; margin-bottom: 0.875rem; display: flex; align-items: center; gap: 0.375rem;">
                                            ⚾ Bowling Career Statistics
                                        </div>
                                        @if($bowling && ($bowling['overs'] > 0 || $bowling['wickets'] > 0))
                                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; text-align: center;">
                                                <div style="background: rgba(255,255,255,0.04); padding: 0.625rem; border-radius: 0.5rem;">
                                                    <div style="font-size: 0.65rem; color: rgba(148,163,184,0.6); text-transform: uppercase; font-weight: 700;">Overs</div>
                                                    <div style="font-weight: 900; font-size: 1rem; color: #fff;">{{ $bowling['overs'] }}</div>
                                                </div>
                                                <div style="background: rgba(255,255,255,0.04); padding: 0.625rem; border-radius: 0.5rem;">
                                                    <div style="font-size: 0.65rem; color: rgba(148,163,184,0.6); text-transform: uppercase; font-weight: 700;">Wickets</div>
                                                    <div style="font-weight: 900; font-size: 1rem; color: #818cf8;">{{ $bowling['wickets'] }}</div>
                                                </div>
                                                <div style="background: rgba(255,255,255,0.04); padding: 0.625rem; border-radius: 0.5rem;">
                                                    <div style="font-size: 0.65rem; color: rgba(148,163,184,0.6); text-transform: uppercase; font-weight: 700;">Economy</div>
                                                    <div style="font-weight: 900; font-size: 1rem; color: #fff;">{{ $bowling['economy'] !== null ? number_format($bowling['economy'], 2) : '—' }}</div>
                                                </div>
                                                <div style="background: rgba(255,255,255,0.04); padding: 0.625rem; border-radius: 0.5rem;">
                                                    <div style="font-size: 0.65rem; color: rgba(148,163,184,0.6); text-transform: uppercase; font-weight: 700;">Best</div>
                                                    <div style="font-weight: 900; font-size: 1rem; color: #fff;">{{ $bowling['best_bowling'] ?? '—' }}</div>
                                                </div>
                                            </div>
                                            <div style="display: flex; gap: 1rem; justify-content: space-around; margin-top: 0.875rem; font-size: 0.75rem; color: rgba(203,213,225,0.8); font-weight: 600;">
                                                <span>Bowling Avg: <strong style="color: #fff;">{{ $bowling['average'] !== null ? number_format($bowling['average'], 2) : '—' }}</strong></span>
                                                <span>5w Hauls: <strong style="color: #818cf8;">{{ $bowling['five_wickets'] }}</strong></span>
                                            </div>
                                        @else
                                            <p style="font-size: 0.8125rem; color: rgba(100,116,139,0.7); text-align: center; padding: 1rem 0;">No bowling stats recorded yet.</p>
                                        @endif
                                    </div>

                                </div>

                                {{-- Recent Form --}}
                                @if(!empty($recentForm) && count($recentForm) > 0)
                                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 0.75rem; padding: 1rem;">
                                        <div style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(148,163,184,0.6); margin-bottom: 0.625rem;">
                                            📊 Recent Match Form
                                        </div>
                                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                            @foreach($recentForm as $matchForm)
                                                <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.75rem;">
                                                    <span style="font-weight: 700; color: #fff;">vs {{ $matchForm['opponent'] ?? 'Opponent' }}</span>
                                                    <span style="color: rgba(148,163,184,0.7); margin-left: 0.375rem;">
                                                        @if(isset($matchForm['runs_scored'])) {{ $matchForm['runs_scored'] }} runs @endif
                                                        @if(isset($matchForm['wickets_taken'])) ({{ $matchForm['wickets_taken'] }} wkts) @endif
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>


{{-- ═══ LIVE MATCHES TICKER ═════════════════════════════════════════════════ --}}
@if($liveMatches->isNotEmpty())
<section style="background: rgba(239,68,68,0.06); border-top: 1px solid rgba(239,68,68,0.15); border-bottom: 1px solid rgba(239,68,68,0.15); padding: 0.75rem 0; overflow: hidden;">
    <div style="display: flex; align-items: center;">
        <div style="flex-shrink: 0; background: #ef4444; color: #fff; font-weight: 800; font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.1em; padding: 0.375rem 0.875rem; margin-right: 1rem;">LIVE</div>
        <div style="overflow: hidden; flex: 1;">
            <div class="ticker-track">
                @foreach(array_merge($liveMatches->toArray(), $liveMatches->toArray()) as $m)
                    <span style="display: inline-flex; align-items: center; gap: 0.5rem; margin-right: 3rem; font-size: 0.8125rem; font-weight: 600; color: rgba(255,255,255,0.85); white-space: nowrap;">
                        <span style="color: rgba(148,163,184,0.6); font-size: 0.7rem; text-transform: uppercase; font-weight: 700;">{{ $m['sport']['name'] }}</span>
                        {{ $m['home_team']['name'] }} <span style="color: #ef4444; font-weight: 800;">vs</span> {{ $m['away_team']['name'] }}
                        @if($m['venue']) <span style="color: rgba(100,116,139,0.7);">• {{ $m['venue'] }}</span> @endif
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- ═══ LIVE MATCHES CARDS ══════════════════════════════════════════════════ --}}
@if($liveMatches->isNotEmpty())
<section style="max-width: 1280px; margin: 0 auto; padding: 3rem 1.5rem 0;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
        <h2 style="font-weight: 800; font-size: 1.125rem; color: #fff; display: flex; align-items: center; gap: 0.625rem;">
            <span style="width: 0.625rem; height: 0.625rem; background: #ef4444; border-radius: 50%; animation: livepulse 1.2s ease-in-out infinite;"></span>
            Live Matches
        </h2>
        <a href="{{ route('public.matches', ['status' => 'live']) }}" style="font-size: 0.8125rem; font-weight: 600; color: #f59e0b; text-decoration: none;">View All →</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
        @foreach($liveMatches as $match)
        <a href="{{ route('public.matches') }}" style="display: block; text-decoration: none;">
            <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(239,68,68,0.2); border-radius: 1rem; padding: 1.25rem; transition: all 0.2s; cursor: pointer;"
                 onmouseover="this.style.background='rgba(239,68,68,0.08)'; this.style.borderColor='rgba(239,68,68,0.4)';"
                 onmouseout="this.style.background='rgba(255,255,255,0.04)'; this.style.borderColor='rgba(239,68,68,0.2)';">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.875rem;">
                    <span style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(148,163,184,0.6);">{{ $match->sport->name }}</span>
                    <span style="display: inline-flex; align-items: center; gap: 0.35rem; background: rgba(239,68,68,0.15); color: #f87171; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; padding: 0.25rem 0.6rem; border-radius: 2rem;">
                        <span style="width: 0.4rem; height: 0.4rem; background: #ef4444; border-radius: 50%; animation: livepulse 1.2s ease-in-out infinite;"></span>
                        LIVE
                    </span>
                </div>
                <div style="text-align: center; padding: 0.5rem 0;">
                    <div style="font-weight: 800; font-size: 1rem; color: #fff; line-height: 1.3;">
                        {{ $match->homeTeam->name }}
                    </div>
                    <div style="font-size: 0.75rem; color: rgba(148,163,184,0.5); font-weight: 600; margin: 0.375rem 0;">vs</div>
                    <div style="font-weight: 800; font-size: 1rem; color: #fff; line-height: 1.3;">
                        {{ $match->awayTeam->name }}
                    </div>
                </div>
                @if($match->venue)
                <div style="text-align: center; margin-top: 0.75rem; font-size: 0.75rem; color: rgba(100,116,139,0.7);">{{ $match->venue }}</div>
                @endif
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- ═══ UPCOMING MATCHES ════════════════════════════════════════════════════ --}}
@if($upcomingMatches->isNotEmpty())
<section style="max-width: 1280px; margin: 0 auto; padding: 3rem 1.5rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
        <h2 style="font-weight: 800; font-size: 1.125rem; color: #fff;">Upcoming Fixtures</h2>
        <a href="{{ route('public.matches', ['status' => 'upcoming']) }}" style="font-size: 0.8125rem; font-weight: 600; color: #f59e0b; text-decoration: none;">View All →</a>
    </div>

    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1rem; overflow: hidden;">
        @foreach($upcomingMatches as $i => $match)
        <a href="{{ route('public.matches') }}" style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; text-decoration: none; transition: background 0.15s; {{ $i > 0 ? 'border-top: 1px solid rgba(255,255,255,0.06);' : '' }}"
           onmouseover="this.style.background='rgba(255,255,255,0.04)'"
           onmouseout="this.style.background='transparent'">
            <div style="flex-shrink: 0; width: 2.75rem; height: 2.75rem; background: rgba(245,158,11,0.1); border-radius: 0.625rem; display: flex; align-items: center; justify-content: center;">
                <svg width="16" height="16" fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-weight: 700; font-size: 0.9rem; color: #e2e8f0;">{{ $match->homeTeam->name }} <span style="color: rgba(100,116,139,0.6); font-weight: 400;">vs</span> {{ $match->awayTeam->name }}</div>
                <div style="font-size: 0.75rem; color: rgba(100,116,139,0.7); margin-top: 0.125rem;">
                    {{ $match->scheduled_at?->format('D, M j • g:ia') ?? 'TBA' }}
                    @if($match->venue) · {{ $match->venue }} @endif
                </div>
            </div>
            <span style="flex-shrink: 0; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; background: rgba(99,102,241,0.12); color: #818cf8; border-radius: 0.375rem; padding: 0.25rem 0.625rem;">{{ $match->sport->name }}</span>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- ═══ FEATURES STRIP ══════════════════════════════════════════════════════ --}}
<section style="background: rgba(255,255,255,0.02); border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06); padding: 3.5rem 1.5rem;">
    <div style="max-width: 1280px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <h2 style="font-weight: 800; font-size: 1.75rem; color: #fff; letter-spacing: -0.02em; margin-bottom: 0.625rem;">Everything you need in one place</h2>
            <p style="color: rgba(148,163,184,0.75); font-size: 0.9375rem; max-width: 480px; margin: 0 auto;">Built for fans, coaches, and athletes who live and breathe sport.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem;">
            @foreach([
                ['🔴', 'Live Scores', 'Ball-by-ball and point-by-point scoring across all sports in real time.', 'rgba(239,68,68,0.1)', '#f87171'],
                ['📅', 'Match Schedule', 'Browse upcoming fixtures by sport, format, or age group.', 'rgba(245,158,11,0.1)', '#fbbf24'],
                ['👤', 'Player Profiles', 'Comprehensive career stats, batting averages, and performance history.', 'rgba(99,102,241,0.1)', '#818cf8'],
                ['📊', 'Analytics', 'Deep analysis including batting, bowling, and sport-specific metrics.', 'rgba(16,185,129,0.1)', '#34d399'],
            ] as [$emoji, $title, $desc, $bg, $color])
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 1rem; padding: 1.5rem; transition: all 0.2s;"
                 onmouseover="this.style.background='rgba(255,255,255,0.05)'; this.style.transform='translateY(-2px)';"
                 onmouseout="this.style.background='rgba(255,255,255,0.03)'; this.style.transform='none';">
                <div style="width: 2.75rem; height: 2.75rem; background: {{ $bg }}; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 1rem;">{{ $emoji }}</div>
                <h3 style="font-weight: 700; font-size: 0.9375rem; color: #fff; margin-bottom: 0.5rem;">{{ $title }}</h3>
                <p style="font-size: 0.8125rem; color: rgba(148,163,184,0.7); line-height: 1.6;">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ SPORTS LIST ═════════════════════════════════════════════════════════ --}}
<section style="max-width: 1280px; margin: 0 auto; padding: 3.5rem 1.5rem;">
    <div style="text-align: center; margin-bottom: 2rem;">
        <h2 style="font-weight: 800; font-size: 1.5rem; color: #fff; margin-bottom: 0.5rem;">20+ Sports Supported</h2>
        <p style="color: rgba(148,163,184,0.65); font-size: 0.875rem;">From cricket to chess — we cover it all.</p>
    </div>
    <div style="display: flex; flex-wrap: wrap; gap: 0.625rem; justify-content: center;">
        @foreach(['Cricket', 'Football', 'Basketball', 'Hockey', 'Volleyball', 'Beach Volleyball', 'Tennis', 'Badminton', 'Table Tennis', 'Swimming', 'Athletics', 'Boxing', 'Judo', 'Karate', 'Kabaddi', 'Rugby', 'Netball', 'Baseball', 'Chess', 'Elle'] as $sport)
        <span style="display: inline-flex; align-items: center; padding: 0.375rem 0.875rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 2rem; font-size: 0.8125rem; font-weight: 600; color: rgba(203,213,225,0.8);">{{ $sport }}</span>
        @endforeach
    </div>
</section>

{{-- ═══ CTA BANNER ══════════════════════════════════════════════════════════ --}}
<section style="padding: 2rem 1.5rem 4rem;">
    <div style="max-width: 700px; margin: 0 auto; background: linear-gradient(135deg, rgba(245,158,11,0.12), rgba(99,102,241,0.12)); border: 1px solid rgba(245,158,11,0.2); border-radius: 1.5rem; padding: 3rem 2rem; text-align: center;">
        <h2 style="font-weight: 900; font-size: 1.75rem; color: #fff; margin-bottom: 0.75rem; letter-spacing: -0.02em;">Ready to follow every match?</h2>
        <p style="color: rgba(148,163,184,0.8); font-size: 0.9375rem; margin-bottom: 1.75rem; line-height: 1.65;">Create a free account and get live scores, player stats and match alerts right on your phone.</p>
        <div style="display: flex; flex-wrap: wrap; gap: 0.875rem; justify-content: center;">
            <a href="{{ route('register') }}" class="btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem;">Create Free Account</a>
            <a href="{{ route('public.matches') }}"
               style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 2rem; font-size: 1rem; font-weight: 700; color: rgba(255,255,255,0.75); border: 1px solid rgba(255,255,255,0.15); border-radius: 0.625rem; text-decoration: none; transition: all 0.2s;"
               onmouseover="this.style.color='#fff'; this.style.borderColor='rgba(255,255,255,0.3)';"
               onmouseout="this.style.color='rgba(255,255,255,0.75)'; this.style.borderColor='rgba(255,255,255,0.15)';">
                Browse Matches
            </a>
        </div>
    </div>
</section>

@endsection
