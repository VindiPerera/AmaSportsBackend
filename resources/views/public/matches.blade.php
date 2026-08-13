@extends('public.layouts.app')

@section('title', 'Matches & Schedule — AmaX')
@section('meta_description', 'Browse live, upcoming and finished matches across all sports on AmaX. No login required.')

@section('content')

<section style="max-width: 1280px; margin: 0 auto; padding: 3rem 1.5rem;">

    {{-- Page Header --}}
    <div style="margin-bottom: 2rem;">
        <h1 style="font-weight: 900; font-size: clamp(1.75rem, 4vw, 2.5rem); color: #fff; letter-spacing: -0.03em; margin-bottom: 0.5rem;">Matches &amp; Schedule</h1>
        <p style="font-size: 0.9375rem; color: rgba(148,163,184,0.75);">Live scores, upcoming fixtures and completed match results — all in one place.</p>
    </div>

    {{-- Status Filter Tabs --}}
    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.75rem;">
        @foreach([
            ['', 'All Matches',  'all'],
            ['live',     '🔴 Live Now',   'live'],
            ['upcoming', '📅 Upcoming',   'upcoming'],
            ['finished', '✓ Finished',   'finished'],
        ] as [$val, $label, $key])
        <a href="{{ route('public.matches', $val ? ['status' => $val] : []) }}"
           style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.8125rem; font-weight: 700; text-decoration: none; transition: all 0.15s; white-space: nowrap;
                  {{ $status === $val
                       ? 'background: rgba(245,158,11,0.2); border: 1px solid rgba(245,158,11,0.4); color: #fbbf24;'
                       : 'background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); color: rgba(148,163,184,0.8);' }}"
           onmouseover="this.style.borderColor='rgba(255,255,255,0.2)'; this.style.color='#fff';"
           onmouseout="if('{{ $status }}' !== '{{ $val }}') { this.style.borderColor='rgba(255,255,255,0.1)'; this.style.color='rgba(148,163,184,0.8)'; }">
            {{ $label }}
            @if($key === 'live' && $liveCounts > 0)
                <span style="background: #ef4444; color: #fff; font-size: 0.625rem; font-weight: 800; padding: 0.1rem 0.35rem; border-radius: 2rem;">{{ $liveCounts }}</span>
            @endif
        </a>
        @endforeach
    </div>

    {{-- Match Count Summary --}}
    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
        <span style="font-size: 0.8125rem; font-weight: 600; color: rgba(100,116,139,0.7);">
            <span style="color: #f87171; font-weight: 800;">{{ $liveCounts }}</span> live
        </span>
        <span style="font-size: 0.8125rem; font-weight: 600; color: rgba(100,116,139,0.7);">
            <span style="color: #fbbf24; font-weight: 800;">{{ $upcomingCounts }}</span> upcoming
        </span>
        <span style="font-size: 0.8125rem; font-weight: 600; color: rgba(100,116,139,0.7);">
            <span style="color: rgba(148,163,184,0.6); font-weight: 800;">{{ $finishedCounts }}</span> finished
        </span>
    </div>

    {{-- Matches Table --}}
    @if($matches->isEmpty())
        <div style="background: rgba(255,255,255,0.03); border: 1px dashed rgba(255,255,255,0.1); border-radius: 1rem; padding: 4rem 2rem; text-align: center;">
            <div style="font-size: 2.5rem; margin-bottom: 1rem;">🏟️</div>
            <p style="font-weight: 700; color: rgba(148,163,184,0.6); font-size: 1rem;">No matches found</p>
            <p style="font-size: 0.875rem; color: rgba(100,116,139,0.5); margin-top: 0.375rem;">
                @if($status) Try viewing <a href="{{ route('public.matches') }}" style="color: #f59e0b; text-decoration: none;">all matches</a>. @else Check back soon for upcoming fixtures. @endif
            </p>
        </div>
    @else
        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.07); border-radius: 1rem; overflow: hidden;">
            @foreach($matches as $i => $match)
            <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; transition: background 0.15s; {{ $i > 0 ? 'border-top: 1px solid rgba(255,255,255,0.05);' : '' }}"
                 onmouseover="this.style.background='rgba(255,255,255,0.04)'"
                 onmouseout="this.style.background='transparent'">

                {{-- Sport Badge --}}
                <div style="flex-shrink: 0; width: 3rem; height: 3rem; background: rgba(99,102,241,0.1); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: #818cf8; text-align: center; line-height: 1.2;">{{ Str::limit($match->sport->name, 6, '') }}</span>
                </div>

                {{-- Match Info --}}
                <div style="flex: 1; min-width: 0;">
                    <div style="font-weight: 700; font-size: 0.9375rem; color: #e2e8f0; line-height: 1.35; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $match->homeTeam->name }}
                        <span style="color: rgba(100,116,139,0.5); font-weight: 400; font-size: 0.8125rem; margin: 0 0.375rem;">vs</span>
                        {{ $match->awayTeam->name }}
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.875rem; margin-top: 0.25rem;">
                        <span style="font-size: 0.75rem; color: rgba(100,116,139,0.65);">
                            🕐 {{ $match->scheduled_at?->format('D, M j • g:ia') ?? 'TBA' }}
                        </span>
                        @if($match->venue)
                        <span style="font-size: 0.75rem; color: rgba(100,116,139,0.65);">
                            📍 {{ $match->venue }}
                        </span>
                        @endif
                        @if($match->country)
                        <span style="font-size: 0.75rem; color: rgba(100,116,139,0.65);">
                            🌍 {{ $match->country }}
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Status Badge --}}
                <div style="flex-shrink: 0; text-align: right;">
                    @if($match->status === 'live')
                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25); color: #f87171; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; padding: 0.3rem 0.7rem; border-radius: 2rem; white-space: nowrap;">
                            <span style="width: 0.4rem; height: 0.4rem; background: #ef4444; border-radius: 50%; animation: livepulse 1.2s ease-in-out infinite;"></span>
                            LIVE
                        </span>
                    @elseif($match->status === 'upcoming')
                        <span style="display: inline-flex; align-items: center; gap: 0.35rem; background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2); color: #fbbf24; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; padding: 0.3rem 0.7rem; border-radius: 2rem; white-space: nowrap;">
                            UPCOMING
                        </span>
                    @else
                        <span style="display: inline-flex; align-items: center; background: rgba(100,116,139,0.1); border: 1px solid rgba(100,116,139,0.15); color: rgba(148,163,184,0.6); font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; padding: 0.3rem 0.7rem; border-radius: 2rem; white-space: nowrap;">
                            FINISHED
                        </span>
                    @endif
                </div>

                {{-- Login to view details CTA --}}
                <div style="flex-shrink: 0; display: none;" class="match-cta">
                    <a href="/login" style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.375rem 0.75rem; background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2); border-radius: 0.5rem; font-size: 0.75rem; font-weight: 700; color: #fbbf24; text-decoration: none; white-space: nowrap; transition: all 0.15s;">
                        View Details →
                    </a>
                </div>

            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($matches->hasPages())
        <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
            {{ $matches->links() }}
        </div>
        @endif
    @endif

    {{-- Login Banner --}}
    <div style="margin-top: 2.5rem; background: linear-gradient(135deg, rgba(245,158,11,0.08), rgba(99,102,241,0.08)); border: 1px solid rgba(245,158,11,0.15); border-radius: 1.25rem; padding: 2rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.25rem;">
        <div>
            <h3 style="font-weight: 800; font-size: 1rem; color: #fff; margin-bottom: 0.375rem;">Want live scores &amp; full match details?</h3>
            <p style="font-size: 0.875rem; color: rgba(148,163,184,0.75);">Log in or create a free account to access detailed stats and real-time scores.</p>
        </div>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <a href="{{ route('login') }}" style="display: inline-flex; align-items: center; padding: 0.625rem 1.25rem; border: 1px solid rgba(255,255,255,0.15); border-radius: 0.625rem; font-size: 0.875rem; font-weight: 700; color: rgba(255,255,255,0.8); text-decoration: none; transition: all 0.15s;"
               onmouseover="this.style.borderColor='rgba(255,255,255,0.3)'; this.style.color='#fff';"
               onmouseout="this.style.borderColor='rgba(255,255,255,0.15)'; this.style.color='rgba(255,255,255,0.8)';">
                Log In
            </a>
            <a href="{{ route('register') }}" class="btn-primary">Get Started Free</a>
        </div>
    </div>

</section>

@endsection
