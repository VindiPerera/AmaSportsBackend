@extends('user.layouts.app')

@section('title', 'Matches & Schedule')

@section('content')

{{-- Header --}}
<div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 900; font-size: 1.75rem; color: #fff; letter-spacing: -0.02em; margin-bottom: 0.25rem;">Matches &amp; Schedule</h1>
        <p style="font-size: 0.875rem; color: rgba(148,163,184,0.75);">View upcoming fixtures, track live scores, and create new matches.</p>
    </div>
    <a href="{{ route('user.matches.create') }}" class="btn-primary" style="padding: 0.625rem 1.25rem; font-size: 0.875rem;">
        ➕ Create New Match
    </a>
</div>

{{-- Status Filter Tabs --}}
<div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; overflow-x: auto;">
    @foreach([
        ['', 'All Matches'],
        ['live', '🔴 Live Now'],
        ['upcoming', '📅 Upcoming'],
        ['finished', '✓ Finished'],
    ] as [$val, $label])
    <a href="{{ route('user.matches.index', $val ? ['status' => $val] : []) }}"
       class="nav-link {{ $status === $val ? 'active' : '' }}"
       style="font-size: 0.8125rem;">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Matches Table --}}
<div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; overflow: hidden;">
    @if($matches->isEmpty())
        <div style="padding: 4rem 2rem; text-align: center; color: rgba(148,163,184,0.6);">
            <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">🏟️</div>
            <p style="font-weight: 700; font-size: 1rem;">No matches scheduled</p>
            <p style="font-size: 0.875rem; margin-top: 0.375rem; color: rgba(100,116,139,0.7);">Click "Create New Match" to schedule a fixture.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                    <tr style="background: rgba(255,255,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.08); text-transform: uppercase; font-size: 0.7rem; font-weight: 800; letter-spacing: 0.08em; color: rgba(148,163,184,0.6);">
                        <th style="padding: 1rem 1.25rem;">Sport</th>
                        <th style="padding: 1rem 1.25rem;">Fixture</th>
                        <th style="padding: 1rem 1.25rem;">Date &amp; Time</th>
                        <th style="padding: 1rem 1.25rem;">Status</th>
                        <th style="padding: 1rem 1.25rem; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matches as $match)
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: background 0.15s;" onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 1rem 1.25rem; font-weight: 800; color: #818cf8;">
                            {{ $match->sport->name }}
                        </td>
                        <td style="padding: 1rem 1.25rem;">
                            <div style="font-weight: 700; color: #fff;">
                                {{ $match->homeTeam->name }} <span style="color: rgba(100,116,139,0.6); font-weight: 400;">vs</span> {{ $match->awayTeam->name }}
                            </div>
                            @if($match->venue)
                                <div style="font-size: 0.75rem; color: rgba(100,116,139,0.7); margin-top: 0.2rem;">📍 {{ $match->venue }}</div>
                            @endif
                        </td>
                        <td style="padding: 1rem 1.25rem; color: rgba(203,213,225,0.8); font-size: 0.8125rem;">
                            {{ $match->scheduled_at?->format('M j, Y • g:ia') ?? 'TBA' }}
                        </td>
                        <td style="padding: 1rem 1.25rem;">
                            @if($match->status === 'live')
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); font-size: 0.65rem; font-weight: 800; padding: 0.25rem 0.625rem; border-radius: 2rem;">
                                    🔴 LIVE
                                </span>
                            @elseif($match->status === 'upcoming')
                                <span style="display: inline-flex; align-items: center; background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); font-size: 0.65rem; font-weight: 800; padding: 0.25rem 0.625rem; border-radius: 2rem;">
                                    UPCOMING
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; background: rgba(100,116,139,0.15); color: rgba(148,163,184,0.7); border: 1px solid rgba(100,116,139,0.3); font-size: 0.65rem; font-weight: 800; padding: 0.25rem 0.625rem; border-radius: 2rem;">
                                    FINISHED
                                </span>
                            @endif
                        </td>
                        <td style="padding: 1rem 1.25rem; text-align: right;">
                            <a href="{{ route('user.matches.edit', $match) }}" style="color: #f59e0b; font-weight: 700; font-size: 0.8125rem; text-decoration: none; margin-right: 0.75rem;">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($matches->hasPages())
        <div style="padding: 1rem 1.25rem; border-top: 1px solid rgba(255,255,255,0.06);">
            {{ $matches->links() }}
        </div>
        @endif
    @endif
</div>

@endsection
