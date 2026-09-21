@extends('public.layouts.app')

@section('title', $displayName . ' — ' . ($activeSport ? $activeSport->name : 'Player') . ' Profile & Career Stats')
@section('meta_description', 'Explore career statistics, batting/bowling averages, recent matches, and bio of ' . $displayName . ' on AmaX.')

@section('content')

{{-- ═══ HERO COVER & IDENTITY HEADER ═════════════════════════════════════════ --}}
<section style="position: relative; background: #070b14; border-bottom: 1px solid rgba(255,255,255,0.08);">
    {{-- Cover Photo Banner --}}
    <div style="position: relative; height: 260px; width: 100%; overflow: hidden; background: linear-gradient(135deg, #0b1f3a 0%, #1e1b4b 50%, #0a0f1e 100%);">
        @if($coverPhotoUrl)
            <img src="{{ $coverPhotoUrl }}"
                 alt="{{ $displayName }} Cover"
                 style="width: 100%; height: 100%; object-fit: cover; opacity: 0.65; cursor: pointer;"
                 onclick="openLightbox('{{ $coverPhotoUrl }}')"
                 title="Click to view cover photo" />
        @else
            {{-- Modern sports pattern fallback --}}
            <div style="position: absolute; inset: 0; background: radial-gradient(circle at 80% 20%, rgba(245,158,11,0.2) 0%, transparent 60%), radial-gradient(circle at 20% 80%, rgba(99,102,241,0.15) 0%, transparent 60%);"></div>
            <div style="position: absolute; inset: 0; opacity: 0.05; background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>
        @endif
        <div style="position: absolute; inset: 0; background: linear-gradient(to top, #070b14 0%, rgba(7,11,20,0.4) 60%, transparent 100%);"></div>
    </div>

    {{-- Player Header Strip --}}
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 1.5rem; position: relative;">
        <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 1.5rem; margin-top: -5rem; padding-bottom: 2rem;">

            {{-- Avatar & Identity --}}
            <div style="display: flex; align-items: flex-end; gap: 1.5rem; flex-wrap: wrap;">
                {{-- Circular Avatar --}}
                <div style="position: relative; flex-shrink: 0;">
                    <div style="width: 8.5rem; height: 8.5rem; border-radius: 50%; padding: 4px; background: linear-gradient(135deg, #f59e0b, #eab308, #6366f1); box-shadow: 0 12px 30px rgba(0,0,0,0.6);">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}"
                                 alt="{{ $displayName }}"
                                 style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; background: #1e293b; cursor: pointer; display: block;"
                                 onclick="openLightbox('{{ $photoUrl }}')"
                                 title="Click to zoom profile photo" />
                        @else
                            <div style="width: 100%; height: 100%; border-radius: 50%; background: #1e293b; display: flex; align-items: center; justify-content: center; font-size: 2.75rem; font-weight: 900; color: #f59e0b; text-transform: uppercase;">
                                {{ strtoupper(substr($displayName, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    @if($photoUrl)
                        <button onclick="openLightbox('{{ $photoUrl }}')"
                                title="Zoom photo"
                                style="position: absolute; bottom: 6px; right: 6px; background: rgba(15,23,42,0.9); border: 1px solid rgba(255,255,255,0.2); border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #fff; font-size: 13px;">
                            🔍
                        </button>
                    @endif
                </div>

                {{-- Name, Country & Roles --}}
                <div style="margin-bottom: 0.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.625rem; flex-wrap: wrap; margin-bottom: 0.35rem;">
                        <h1 style="font-size: clamp(1.75rem, 4vw, 2.5rem); font-weight: 900; color: #fff; line-height: 1.1; letter-spacing: -0.02em;">
                            {{ $displayName }}
                        </h1>
                        <span style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: #34d399; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; padding: 0.2rem 0.6rem; border-radius: 2rem;">
                            ✓ Verified Player
                        </span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; color: rgba(203,213,225,0.8); font-size: 0.875rem;">
                        @if($player->country)
                            <span style="display: inline-flex; align-items: center; gap: 0.35rem; background: rgba(255,255,255,0.06); padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-weight: 600; color: #f1f5f9;">
                                📍 {{ $player->country }}
                            </span>
                        @endif

                        @if($detailedAge)
                            <span style="background: rgba(255,255,255,0.06); padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-weight: 600; color: #f1f5f9;">
                                ⏳ Age: <strong style="color: #fff;">{{ $detailedAge }}</strong>
                            </span>
                        @endif

                        @if(!empty($sportData['profile']?->playing_role))
                            <span style="color: #fbbf24; font-weight: 700;">
                                • {{ $sportData['profile']->playing_role }}
                            </span>
                        @elseif(!empty($sportData['profile']?->player_position))
                            <span style="color: #fbbf24; font-weight: 700;">
                                • {{ $sportData['profile']->player_position }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Team & College Affiliations --}}
            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.75rem; margin-bottom: 0.5rem;">
                {{-- College / University Badge --}}
                @if(!empty($sportData['college_university']))
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 0.75rem; padding: 0.375rem 0.875rem;">
                        @if($collegeLogoUrl)
                            <img src="{{ $collegeLogoUrl }}" alt="College" style="width: 1.5rem; height: 1.5rem; object-fit: contain; border-radius: 0.25rem;" />
                        @else
                            <span style="font-size: 1rem;">🎓</span>
                        @endif
                        <span style="font-size: 0.8125rem; font-weight: 700; color: #f1f5f9;">{{ $sportData['college_university'] }}</span>
                    </div>
                @endif

                {{-- Teams Pills --}}
                @if(!empty($teamNames))
                    <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; justify-content: flex-end;">
                        @foreach($teamNames as $teamName)
                            <div style="display: inline-flex; align-items: center; gap: 0.375rem; background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25); border-radius: 0.625rem; padding: 0.3rem 0.75rem;">
                                @if(isset($teamLogos[$teamName]))
                                    <img src="{{ $teamLogos[$teamName] }}" alt="{{ $teamName }}" style="width: 1.25rem; height: 1.25rem; object-fit: contain; border-radius: 0.25rem;" />
                                @else
                                    <span style="font-size: 0.875rem;">🛡️</span>
                                @endif
                                <span style="font-size: 0.75rem; font-weight: 800; color: #f59e0b;">{{ $teamName }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- ── MULTI-SPORT SWITCHER (If Player has multiple sports) ── --}}
        @if($playerSportsList->count() > 1)
            <div style="display: flex; align-items: center; gap: 0.5rem; overflow-x: auto; padding-bottom: 0.75rem; margin-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 0.875rem;">
                <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(148,163,184,0.7); margin-right: 0.5rem; white-space: nowrap;">Sports:</span>
                @foreach($playerSportsList as $s)
                    @php $isActiveSport = ($s['slug'] === $activeSlug); @endphp
                    <a href="{{ route('public.players.show', ['player' => $player->id, 'sportSlug' => $s['slug']]) }}"
                       style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.4rem 0.875rem; border-radius: 2rem; font-size: 0.8125rem; font-weight: 700; text-decoration: none; transition: all 0.15s; white-space: nowrap; {{ $isActiveSport ? 'background: #f59e0b; color: #111827; box-shadow: 0 4px 12px rgba(245,158,11,0.3);' : 'background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.8); border: 1px solid rgba(255,255,255,0.1);' }}">
                        <span>{{ $s['icon'] }}</span>
                        <span>{{ $s['name'] }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- ═══ CRICINFO-STYLE QUICK PERSONAL BIO STRIP ═══════════════════════════════ --}}
<section style="background: rgba(255,255,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.06); padding: 1rem 1.5rem;">
    <div style="max-width: 1280px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 1rem; text-align: left;">
            <div>
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(148,163,184,0.6);">Full Name</div>
                <div style="font-size: 0.875rem; font-weight: 800; color: #fff; margin-top: 0.15rem;">{{ $displayName }}</div>
            </div>

            @if($bornDateFormatted)
            <div>
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(148,163,184,0.6);">Born</div>
                <div style="font-size: 0.875rem; font-weight: 800; color: #fff; margin-top: 0.15rem;">{{ $bornDateFormatted }}</div>
            </div>
            @endif

            @if($detailedAge)
            <div>
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(148,163,184,0.6);">Age</div>
                <div style="font-size: 0.875rem; font-weight: 800; color: #f59e0b; margin-top: 0.15rem;">{{ $detailedAge }}</div>
            </div>
            @endif

            @if(!empty($sportData['height']))
            <div>
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(148,163,184,0.6);">Height</div>
                <div style="font-size: 0.875rem; font-weight: 800; color: #fff; margin-top: 0.15rem;">{{ $sportData['height'] }}</div>
            </div>
            @endif

            @if(!empty($sportData['profile']?->playing_role))
            <div>
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(148,163,184,0.6);">Playing Role</div>
                <div style="font-size: 0.875rem; font-weight: 800; color: #fff; margin-top: 0.15rem;">{{ $sportData['profile']->playing_role }}</div>
            </div>
            @endif

            @if(!empty($sportData['profile']?->batting_style))
            <div>
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(148,163,184,0.6);">Batting Style</div>
                <div style="font-size: 0.875rem; font-weight: 800; color: #fff; margin-top: 0.15rem;">{{ $sportData['profile']->batting_style }}</div>
            </div>
            @endif

            @if(!empty($sportData['profile']?->bowling_style))
            <div>
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(148,163,184,0.6);">Bowling Style</div>
                <div style="font-size: 0.875rem; font-weight: 800; color: #fff; margin-top: 0.15rem;">{{ $sportData['profile']->bowling_style }}</div>
            </div>
            @endif

            @if(!empty($sportData['profile']?->player_position))
            <div>
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(148,163,184,0.6);">Position</div>
                <div style="font-size: 0.875rem; font-weight: 800; color: #fff; margin-top: 0.15rem;">{{ $sportData['profile']->player_position }}</div>
            </div>
            @endif

            @if(!empty($sportData['profile']?->dominant_leg))
            <div>
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(148,163,184,0.6);">Dominant Leg</div>
                <div style="font-size: 0.875rem; font-weight: 800; color: #fff; margin-top: 0.15rem;">{{ ucfirst((string) $sportData['profile']->dominant_leg) }}</div>
            </div>
            @endif

            @if(!empty($sportData['profile']?->dominant_hand))
            <div>
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(148,163,184,0.6);">Dominant Hand</div>
                <div style="font-size: 0.875rem; font-weight: 800; color: #fff; margin-top: 0.15rem;">{{ ucfirst((string) $sportData['profile']->dominant_hand) }}</div>
            </div>
            @endif

            @if(!empty($sportData['profile']?->current_ranking))
            <div>
                <div style="font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(148,163,184,0.6);">Current Rank</div>
                <div style="font-size: 0.875rem; font-weight: 800; color: #38bdf8; margin-top: 0.15rem;">#{{ $sportData['profile']->current_ranking }}</div>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- ═══ CRICINFO-STYLE TABBED MAIN CONTENT ════════════════════════════════════ --}}
<section style="max-width: 1280px; margin: 0 auto; padding: 2rem 1.5rem 4rem; flex: 1;">

    {{-- Tabs Navigation Bar --}}
    <div style="display: flex; align-items: center; gap: 0.5rem; border-bottom: 2px solid rgba(255,255,255,0.08); margin-bottom: 2rem; overflow-x: auto;">
        <button id="tab-btn-overview"
                onclick="switchTab('overview')"
                class="profile-tab-btn active"
                style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.75rem 1.25rem; font-size: 0.9375rem; font-weight: 700; cursor: pointer; background: none; border: none; border-bottom: 3px solid transparent; color: #f59e0b; border-color: #f59e0b; margin-bottom: -2px; transition: all 0.15s; white-space: nowrap;">
            👤 Overview
        </button>

        <button id="tab-btn-stats"
                onclick="switchTab('stats')"
                class="profile-tab-btn"
                style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.75rem 1.25rem; font-size: 0.9375rem; font-weight: 700; cursor: pointer; background: none; border: none; border-bottom: 3px solid transparent; color: rgba(148,163,184,0.8); margin-bottom: -2px; transition: all 0.15s; white-space: nowrap;">
            📊 Career Stats
        </button>

        <button id="tab-btn-matches"
                onclick="switchTab('matches')"
                class="profile-tab-btn"
                style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.75rem 1.25rem; font-size: 0.9375rem; font-weight: 700; cursor: pointer; background: none; border: none; border-bottom: 3px solid transparent; color: rgba(148,163,184,0.8); margin-bottom: -2px; transition: all 0.15s; white-space: nowrap;">
            📅 Matches &amp; Events
        </button>

        <button id="tab-btn-achievements"
                onclick="switchTab('achievements')"
                class="profile-tab-btn"
                style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.75rem 1.25rem; font-size: 0.9375rem; font-weight: 700; cursor: pointer; background: none; border: none; border-bottom: 3px solid transparent; color: rgba(148,163,184,0.8); margin-bottom: -2px; transition: all 0.15s; white-space: nowrap;">
            🏆 Achievements ({{ count($achievements) }})
        </button>
    </div>

    {{-- ── TAB 1: OVERVIEW ── --}}
    <div id="tab-panel-overview" class="tab-panel" style="display: block;">
        {{-- KPI Cards Grid (if present) --}}
        @if(!empty($sportData['kpi_cards']))
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                @foreach($sportData['kpi_cards'] as $kpi)
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1rem; padding: 1.25rem; text-align: center; position: relative; overflow: hidden;">
                        <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(148,163,184,0.7); margin-bottom: 0.25rem;">
                            {{ $kpi['label'] }}
                        </div>
                        <div style="font-size: 1.75rem; font-weight: 900; color: {{ $kpi['color'] ?? '#fff' }};">
                            {{ $kpi['value'] }}
                        </div>
                        @if(!empty($kpi['sub']))
                            <div style="font-size: 0.75rem; font-weight: 600; color: rgba(203,213,225,0.7); margin-top: 0.25rem;">
                                {{ $kpi['sub'] }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Overview Cards Row --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">

            {{-- Personal & Technical Attributes --}}
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; padding: 1.5rem;">
                <h3 style="font-size: 1rem; font-weight: 800; color: #fff; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                    📋 Biographical &amp; Physical Information
                </h3>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.625rem; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 0.875rem;">
                        <span style="color: rgba(148,163,184,0.7);">Nationality</span>
                        <strong style="color: #fff;">{{ $player->country ?: '—' }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.625rem; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 0.875rem;">
                        <span style="color: rgba(148,163,184,0.7);">Birth Date</span>
                        <strong style="color: #fff;">{{ $bornDateFormatted ?: '—' }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.625rem; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 0.875rem;">
                        <span style="color: rgba(148,163,184,0.7);">Current Age</span>
                        <strong style="color: #f59e0b;">{{ $detailedAge ?: '—' }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.625rem; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 0.875rem;">
                        <span style="color: rgba(148,163,184,0.7);">Height</span>
                        <strong style="color: #fff;">{{ $sportData['height'] ?: '—' }}</strong>
                    </div>
                    @foreach($sportData['overview_fields'] as $field)
                        <div style="display: flex; justify-content: space-between; padding-bottom: 0.625rem; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 0.875rem;">
                            <span style="color: rgba(148,163,184,0.7);">{{ $field['label'] }}</span>
                            <strong style="color: #fff;">{{ $field['value'] ?: '—' }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Sport-Specific Technical Breakdown (e.g. Cricket Pitch/Ball or Athletics Personal Bests) --}}
            @if($activeSlug === 'cricket')
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; padding: 1.5rem;">
                    <h3 style="font-size: 1rem; font-weight: 800; color: #fff; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                        🎯 Delivery &amp; Bowling Distribution
                    </h3>

                    @if(!empty($sportData['pitching_line_breakdown']))
                        <div style="margin-bottom: 1.25rem;">
                            <div style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #fbbf24; margin-bottom: 0.625rem;">Pitching Line Breakdown</div>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap: 0.5rem; text-align: center;">
                                @foreach($sportData['pitching_line_breakdown'] as $lineKey => $lineVal)
                                    <div style="background: rgba(255,255,255,0.04); padding: 0.5rem; border-radius: 0.5rem;">
                                        <div style="font-size: 0.65rem; color: rgba(148,163,184,0.7); text-transform: uppercase; font-weight: 700;">{{ ucwords(str_replace('_', ' ', $lineKey)) }}</div>
                                        <div style="font-weight: 900; font-size: 0.9375rem; color: #fff;">{{ $lineVal }}%</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($sportData['ball_type_breakdown']))
                        <div>
                            <div style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #38bdf8; margin-bottom: 0.625rem;">Ball Type Breakdown</div>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap: 0.5rem; text-align: center;">
                                @foreach($sportData['ball_type_breakdown'] as $ballKey => $ballVal)
                                    <div style="background: rgba(255,255,255,0.04); padding: 0.5rem; border-radius: 0.5rem;">
                                        <div style="font-size: 0.65rem; color: rgba(148,163,184,0.7); text-transform: uppercase; font-weight: 700;">{{ ucwords(str_replace('_', ' ', $ballKey)) }}</div>
                                        <div style="font-weight: 900; font-size: 0.9375rem; color: #fff;">{{ $ballVal }}%</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(empty($sportData['pitching_line_breakdown']) && empty($sportData['ball_type_breakdown']))
                        <p style="font-size: 0.8125rem; color: rgba(148,163,184,0.6); text-align: center; padding: 2rem 0;">No delivery breakdown recorded yet.</p>
                    @endif
                </div>

            @elseif(!empty($sportData['personal_bests']))
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; padding: 1.5rem;">
                    <h3 style="font-size: 1rem; font-weight: 800; color: #fff; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                        ⏱️ Personal Bests &amp; Records
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($sportData['personal_bests'] as $pb)
                            <div style="display: flex; justify-content: space-between; padding-bottom: 0.625rem; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 0.875rem;">
                                <span style="color: rgba(148,163,184,0.8); font-weight: 600;">{{ $pb['event'] }}</span>
                                <strong style="color: #f59e0b; font-size: 0.9375rem;">{{ $pb['record'] }}</strong>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Photo Gallery Section (Up to 10 photos) --}}
        @if(!empty($galleryPhotos))
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; padding: 1.5rem; margin-bottom: 2rem;">
                <h3 style="font-size: 1rem; font-weight: 800; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    📸 Photo Gallery ({{ count($galleryPhotos) }})
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 0.875rem;">
                    @foreach($galleryPhotos as $photo)
                        <div style="position: relative; aspect-ratio: 1; border-radius: 0.75rem; overflow: hidden; background: #1e293b; border: 1px solid rgba(255,255,255,0.1); cursor: pointer;"
                             onclick="openLightbox('{{ $photo['url'] }}')">
                            <img src="{{ $photo['url'] }}" alt="Gallery" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.2s;"
                                 onmouseover="this.style.transform='scale(1.05)'"
                                 onmouseout="this.style.transform='scale(1)'" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    {{-- ── TAB 2: CRICINFO CAREER STATS ── --}}
    <div id="tab-panel-stats" class="tab-panel" style="display: none;">

        {{-- CRICKET SPECIFIC STATS TABLES --}}
        @if($activeSlug === 'cricket')

            {{-- 1. Batting Career Statistics Table --}}
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; padding: 1.5rem; margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #fff; display: flex; align-items: center; gap: 0.5rem;">
                        🏏 Batting &amp; Fielding Career Statistics
                    </h3>
                    <span style="font-size: 0.75rem; color: rgba(148,163,184,0.6);">Official Career Log</span>
                </div>

                @if(!empty($sportData['batting_stats']))
                    <div class="cricinfo-table-container" style="overflow-x: auto;">
                        <table class="cricinfo-table">
                            <thead>
                                <tr>
                                    <th style="text-align: left;">Division</th>
                                    <th style="text-align: left;">Category</th>
                                    <th style="text-align: left;">Match Type</th>
                                    <th>Mat</th>
                                    <th>Inns</th>
                                    <th>NO</th>
                                    <th style="color: #f59e0b;">Runs</th>
                                    <th>HS</th>
                                    <th>Avg</th>
                                    <th>SR</th>
                                    <th>100s</th>
                                    <th>50s</th>
                                    <th>4s</th>
                                    <th>6s</th>
                                    <th>Ct</th>
                                    <th>St</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sportData['batting_stats'] as $row)
                                    <tr>
                                        <td style="text-align: left; font-weight: 700; color: #fff;">{{ $row['format'] }}</td>
                                        <td style="text-align: left; color: rgba(203,213,225,0.8);">{{ $row['category'] }}</td>
                                        <td style="text-align: left; color: rgba(148,163,184,0.7);">{{ $row['match_type'] }}</td>
                                        <td>{{ $row['matches'] ?: '—' }}</td>
                                        <td>{{ $row['innings'] ?: '—' }}</td>
                                        <td>{{ $row['not_out'] ?: '—' }}</td>
                                        <td style="font-weight: 800; color: #f59e0b;">{{ $row['runs'] ?: '0' }}</td>
                                        <td>{{ $row['hs'] ?: '—' }}</td>
                                        <td style="font-weight: 700; color: #fff;">{{ $row['average'] !== null && $row['average'] !== '' ? number_format((float)$row['average'], 2) : '—' }}</td>
                                        <td>{{ $row['sr'] !== null && $row['sr'] !== '' ? number_format((float)$row['sr'], 1) : '—' }}</td>
                                        <td style="color: #fbbf24;">{{ $row['hundreds'] ?: '0' }}</td>
                                        <td style="color: #fbbf24;">{{ $row['fifties'] ?: '0' }}</td>
                                        <td>{{ $row['fours'] ?: '0' }}</td>
                                        <td>{{ $row['sixes'] ?: '0' }}</td>
                                        <td>{{ $row['catches'] ?: '0' }}</td>
                                        <td>{{ $row['stumpings'] ?: '0' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="font-size: 0.875rem; color: rgba(148,163,184,0.6); text-align: center; padding: 2rem 0;">No batting statistics recorded yet.</p>
                @endif
            </div>

            {{-- 2. Bowling Career Statistics Table --}}
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; padding: 1.5rem; margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #fff; display: flex; align-items: center; gap: 0.5rem;">
                        ⚾ Bowling Career Statistics
                    </h3>
                    <span style="font-size: 0.75rem; color: rgba(148,163,184,0.6);">Official Career Log</span>
                </div>

                @if(!empty($sportData['bowling_stats']))
                    <div class="cricinfo-table-container" style="overflow-x: auto;">
                        <table class="cricinfo-table">
                            <thead>
                                <tr>
                                    <th style="text-align: left;">Division</th>
                                    <th style="text-align: left;">Category</th>
                                    <th style="text-align: left;">Match Type</th>
                                    <th>Mat</th>
                                    <th>Inns</th>
                                    <th>Balls</th>
                                    <th>Dots</th>
                                    <th>Runs</th>
                                    <th style="color: #38bdf8;">Wkts</th>
                                    <th>BBI</th>
                                    <th>BBM</th>
                                    <th>Avg</th>
                                    <th>Econ</th>
                                    <th>SR</th>
                                    <th>4w</th>
                                    <th>5w</th>
                                    <th>10w</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sportData['bowling_stats'] as $row)
                                    <tr>
                                        <td style="text-align: left; font-weight: 700; color: #fff;">{{ $row['format'] }}</td>
                                        <td style="text-align: left; color: rgba(203,213,225,0.8);">{{ $row['category'] }}</td>
                                        <td style="text-align: left; color: rgba(148,163,184,0.7);">{{ $row['match_type'] }}</td>
                                        <td>{{ $row['matches'] ?: '—' }}</td>
                                        <td>{{ $row['innings'] ?: '—' }}</td>
                                        <td>{{ $row['balls'] ?: '—' }}</td>
                                        <td>{{ $row['dot_balls'] ?: '—' }}</td>
                                        <td>{{ $row['runs'] ?: '—' }}</td>
                                        <td style="font-weight: 800; color: #38bdf8;">{{ $row['wickets'] ?: '0' }}</td>
                                        <td style="font-weight: 700; color: #fff;">{{ $row['bbi'] ?: '—' }}</td>
                                        <td>{{ $row['bbm'] ?: '—' }}</td>
                                        <td>{{ $row['average'] !== null && $row['average'] !== '' ? number_format((float)$row['average'], 2) : '—' }}</td>
                                        <td style="font-weight: 700; color: #fff;">{{ $row['economy'] !== null && $row['economy'] !== '' ? number_format((float)$row['economy'], 2) : '—' }}</td>
                                        <td>{{ $row['sr'] !== null && $row['sr'] !== '' ? number_format((float)$row['sr'], 1) : '—' }}</td>
                                        <td style="color: #818cf8;">{{ $row['four_w'] ?: '0' }}</td>
                                        <td style="color: #818cf8;">{{ $row['five_w'] ?: '0' }}</td>
                                        <td style="color: #818cf8;">{{ $row['ten_w'] ?: '0' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="font-size: 0.875rem; color: rgba(148,163,184,0.6); text-align: center; padding: 2rem 0;">No bowling statistics recorded yet.</p>
                @endif
            </div>

            {{-- 3. Drop Catches Table (if any) --}}
            @if(!empty($sportData['drop_catches']))
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; padding: 1.5rem; margin-bottom: 2rem;">
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        🧤 Fielding &amp; Catch Opportunities Log
                    </h3>
                    <div class="cricinfo-table-container" style="overflow-x: auto;">
                        <table class="cricinfo-table">
                            <thead>
                                <tr>
                                    <th style="text-align: left;">Division</th>
                                    <th style="text-align: left;">Category</th>
                                    <th style="text-align: left;">Field Position</th>
                                    <th style="text-align: left;">Reason / Context</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sportData['drop_catches'] as $dc)
                                    <tr>
                                        <td style="text-align: left; font-weight: 700; color: #fff;">{{ $dc['format'] }}</td>
                                        <td style="text-align: left; color: rgba(203,213,225,0.8);">{{ $dc['category'] }}</td>
                                        <td style="text-align: left; color: #fbbf24; font-weight: 700;">{{ $dc['field_position'] }}</td>
                                        <td style="text-align: left; color: rgba(148,163,184,0.8);">{{ $dc['drop_reason'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        {{-- OTHER SPORTS STATS TABLES --}}
        @elseif(!empty($sportData['stat_tables']))
            @foreach($sportData['stat_tables'] as $statTable)
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; padding: 1.5rem; margin-bottom: 2rem;">
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        📊 {{ $statTable['title'] }}
                    </h3>

                    @if(!empty($statTable['rows']))
                        <div class="cricinfo-table-container" style="overflow-x: auto;">
                            <table class="cricinfo-table">
                                <thead>
                                    <tr>
                                        @foreach($statTable['columns'] as $colHeader)
                                            <th style="{{ $loop->first ? 'text-align: left;' : '' }}">{{ $colHeader }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($statTable['rows'] as $statRow)
                                        <tr>
                                            @foreach($statTable['keys'] as $k)
                                                <td style="{{ $loop->first ? 'text-align: left; font-weight: 700; color: #fff;' : '' }}">
                                                    {{ $statRow[$k] !== null && $statRow[$k] !== '' ? $statRow[$k] : '—' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p style="font-size: 0.875rem; color: rgba(148,163,184,0.6); text-align: center; padding: 2rem 0;">No stats recorded yet for this sport.</p>
                    @endif
                </div>
            @endforeach

        @else
            <div style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1); border-radius: 1rem; padding: 3rem; text-align: center; color: rgba(148,163,184,0.7);">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">📊</div>
                <p style="font-weight: 700; font-size: 1rem; color: #fff;">No career statistics recorded yet</p>
                <p style="font-size: 0.8125rem; margin-top: 0.25rem; color: rgba(100,116,139,0.8);">Career stats will appear here once matches and performance metrics are updated.</p>
            </div>
        @endif

    </div>

    {{-- ── TAB 3: MATCHES & EVENT LOGS ── --}}
    <div id="tab-panel-matches" class="tab-panel" style="display: none;">

        {{-- CRICKET RECENT MATCHES TABLE --}}
        @if($activeSlug === 'cricket')
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; padding: 1.5rem;">
                <h3 style="font-size: 1.125rem; font-weight: 800; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    📅 Recent Cricket Match Logs
                </h3>

                @if(!empty($sportData['recent_matches']))
                    <div class="cricinfo-table-container" style="overflow-x: auto;">
                        <table class="cricinfo-table">
                            <thead>
                                <tr>
                                    <th style="text-align: left;">Date</th>
                                    <th style="text-align: left;">Match vs</th>
                                    <th>Batting</th>
                                    <th>Bowling</th>
                                    <th>Catches</th>
                                    <th>Stumpings</th>
                                    <th>XI Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sportData['recent_matches'] as $m)
                                    <tr>
                                        <td style="text-align: left; font-weight: 700; color: #fff;">{{ $m['match_date'] }}</td>
                                        <td style="text-align: left; font-weight: 600; color: rgba(245,158,11,0.95);">vs {{ $m['opponent'] }}</td>
                                        <td style="font-weight: 800; color: #fff;">
                                            @if($m['runs'] !== null && $m['runs'] !== '')
                                                {{ $m['runs'] }} <span style="font-weight: normal; font-size: 0.75rem; color: rgba(148,163,184,0.8);">({{ $m['balls'] ?? 0 }}b, {{ $m['fours'] ?? 0 }}x4, {{ $m['sixes'] ?? 0 }}x6)</span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td style="font-weight: 800; color: #38bdf8;">
                                            @if($m['wickets'] !== null && $m['wickets'] !== '')
                                                {{ $m['wickets'] }} wkts <span style="font-weight: normal; font-size: 0.75rem; color: rgba(148,163,184,0.8);">({{ $m['overs'] ?? 0 }} ov)</span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $m['catches'] ?: '0' }}</td>
                                        <td>{{ $m['stumpings'] ?: '0' }}</td>
                                        <td>
                                            @if($m['played_xi'])
                                                <span style="background: rgba(16,185,129,0.15); color: #34d399; padding: 0.2rem 0.5rem; border-radius: 0.375rem; font-size: 0.7rem; font-weight: 800;">PLAYED XI</span>
                                            @else
                                                <span style="background: rgba(148,163,184,0.15); color: rgba(148,163,184,0.8); padding: 0.2rem 0.5rem; border-radius: 0.375rem; font-size: 0.7rem; font-weight: 800;">SQUAD</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p style="font-size: 0.875rem; color: rgba(148,163,184,0.6); text-align: center; padding: 2rem 0;">No recent cricket matches recorded yet.</p>
                @endif
            </div>

        {{-- OTHER SPORTS RECENT MATCHES TABLES --}}
        @elseif(!empty($sportData['recent_tables']))
            @foreach($sportData['recent_tables'] as $recentTable)
                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1.25rem; padding: 1.5rem; margin-bottom: 2rem;">
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        📅 {{ $recentTable['title'] }}
                    </h3>

                    @if(!empty($recentTable['rows']))
                        <div class="cricinfo-table-container" style="overflow-x: auto;">
                            <table class="cricinfo-table">
                                <thead>
                                    <tr>
                                        @foreach($recentTable['columns'] as $colHeader)
                                            <th style="{{ $loop->first ? 'text-align: left;' : '' }}">{{ $colHeader }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTable['rows'] as $rRow)
                                        <tr>
                                            @foreach($recentTable['keys'] as $k)
                                                @php $val = $rRow[$k] ?? '—'; @endphp
                                                <td style="{{ $loop->first ? 'text-align: left; font-weight: 700; color: #fff;' : '' }}">
                                                    @if($k === 'result')
                                                        @if(in_array($val, ['WIN', 'GOLD', '1']))
                                                            <span style="background: rgba(16,185,129,0.2); color: #34d399; font-weight: 800; padding: 0.2rem 0.6rem; border-radius: 0.375rem; font-size: 0.75rem;">{{ $val }}</span>
                                                        @elseif(in_array($val, ['LOSS', 'LOST']))
                                                            <span style="background: rgba(239,68,68,0.2); color: #f87171; font-weight: 800; padding: 0.2rem 0.6rem; border-radius: 0.375rem; font-size: 0.75rem;">{{ $val }}</span>
                                                        @elseif(in_array($val, ['SILVER', 'BRONZE']))
                                                            <span style="background: rgba(245,158,11,0.2); color: #fbbf24; font-weight: 800; padding: 0.2rem 0.6rem; border-radius: 0.375rem; font-size: 0.75rem;">{{ $val }}</span>
                                                        @else
                                                            {{ $val }}
                                                        @endif
                                                    @else
                                                        {{ $val }}
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p style="font-size: 0.875rem; color: rgba(148,163,184,0.6); text-align: center; padding: 2rem 0;">No match logs recorded yet.</p>
                    @endif
                </div>
            @endforeach

        @else
            <div style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1); border-radius: 1rem; padding: 3rem; text-align: center; color: rgba(148,163,184,0.7);">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">📅</div>
                <p style="font-weight: 700; font-size: 1rem; color: #fff;">No recent match logs recorded yet</p>
                <p style="font-size: 0.8125rem; margin-top: 0.25rem; color: rgba(100,116,139,0.8);">Match logs will automatically populate when matches are played.</p>
            </div>
        @endif

    </div>

    {{-- ── TAB 4: ACHIEVEMENTS ── --}}
    <div id="tab-panel-achievements" class="tab-panel" style="display: none;">
        @if(!empty($achievements))
            @php
                // The `icon` column stores an Ionicons glyph name (see
                // Admin\AchievementController::ICON_OPTIONS) — the mobile
                // app renders it natively via @expo/vector-icons. The web
                // has no Ionicons font loaded, so map each of the 13
                // possible names to an equivalent emoji instead of printing
                // the raw name as text.
                $achEmoji = [
                    'trophy' => '🏆', 'ribbon' => '🎖️', 'medal' => '🏅', 'star' => '⭐',
                    'flame' => '🔥', 'flash' => '⚡', 'flag' => '🚩', 'shield-checkmark' => '🛡️',
                    'trending-up' => '📈', 'rocket' => '🚀', 'diamond' => '💎',
                    'sparkles' => '✨', 'baseball' => '⚾',
                ];

                // Mirrors the mobile app's resolveAchievementTheme() (see
                // sport-mobile/src/components/achievements/AchievementBadge.tsx)
                // keyword-for-keyword, so a given achievement gets the same
                // "tier" — gradient medal + tier tag — on both platforms.
                $achTheme = function (array $ach) {
                    $title = strtolower($ach['title'] ?? '');
                    $icon = strtolower($ach['icon'] ?? '');
                    $hex = strtolower($ach['color'] ?? '');
                    $has = fn (string $haystack, array $needles) => collect($needles)->contains(fn ($n) => str_contains($haystack, $n));

                    if ($has($title, ['century', 'gold', 'trophy', 'champion']) || $has($icon, ['trophy', 'medal'])) {
                        return ['gradient' => ['#FFF3B0', '#F59E0B', '#B45309'], 'glow' => 'rgba(245,158,11,0.45)', 'pillBg' => '#FEF3C7', 'pillText' => '#B45309', 'spark' => '#F59E0B', 'tier' => 'GOLD TIER'];
                    }
                    if ($has($title, ['debut', 'rookie', 'speed', 'energy']) || $has($icon, ['flash', 'flag'])) {
                        return ['gradient' => ['#F5FFDB', '#D7FF3F', '#84CC16'], 'glow' => 'rgba(215,255,63,0.5)', 'pillBg' => '#F5FFDB', 'pillText' => '#3F6212', 'spark' => '#D7FF3F', 'tier' => 'ELITE MARK'];
                    }
                    if ($has($title, ['run machine', 'fire', 'flame']) || $has($icon, ['flame'])) {
                        return ['gradient' => ['#FED7AA', '#F97316', '#C2410C'], 'glow' => 'rgba(249,115,22,0.45)', 'pillBg' => '#FFEDD5', 'pillText' => '#C2410C', 'spark' => '#F97316', 'tier' => 'FLAME TIER'];
                    }
                    if ($has($title, ['500', 'strike', 'red', 'beast']) || str_starts_with($hex, '#e') || str_starts_with($hex, '#d0') || str_starts_with($hex, '#c')) {
                        return ['gradient' => ['#FECDD3', '#E11D48', '#9F1239'], 'glow' => 'rgba(225,29,72,0.4)', 'pillBg' => '#FFE4E6', 'pillText' => '#9F1239', 'spark' => '#E11D48', 'tier' => 'RUBY ELITE'];
                    }
                    if ($has($title, ['fifty', 'club', 'star']) || $has($icon, ['ribbon', 'star'])) {
                        return ['gradient' => ['#BAE6FD', '#0284C7', '#075985'], 'glow' => 'rgba(14,165,233,0.4)', 'pillBg' => '#E0F2FE', 'pillText' => '#0369A1', 'spark' => '#0284C7', 'tier' => 'STAR CLUB'];
                    }
                    if ($has($title, ['scorer', 'master', 'green']) || str_starts_with($hex, '#1') || str_starts_with($hex, '#2') || str_starts_with($hex, '#0')) {
                        return ['gradient' => ['#A7F3D0', '#10B981', '#065F46'], 'glow' => 'rgba(16,185,129,0.4)', 'pillBg' => '#DCFCE7', 'pillText' => '#166534', 'spark' => '#10B981', 'tier' => 'MASTER TIER'];
                    }

                    return ['gradient' => ['#E9D5FF', '#A855F7', '#6B21A8'], 'glow' => 'rgba(168,85,247,0.4)', 'pillBg' => '#F3E8FF', 'pillText' => '#6B21A8', 'spark' => '#A855F7', 'tier' => 'PRO RECORD'];
                };
            @endphp
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.25rem;">
                @foreach($achievements as $ach)
                    @php $theme = $achTheme($ach); @endphp
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 1rem; padding: 1.5rem 1.25rem; display: flex; flex-direction: column; align-items: center; text-align: center; transition: border-color 0.2s;"
                         onmouseover="this.style.borderColor='rgba(245,158,11,0.4)';"
                         onmouseout="this.style.borderColor='rgba(255,255,255,0.08)';">

                        {{-- Tier Tag --}}
                        <div style="display: inline-flex; align-items: center; gap: 0.35rem; background: {{ $theme['pillBg'] }}; border-radius: 2rem; padding: 0.2rem 0.625rem; margin-bottom: 1rem;">
                            <span style="width: 5px; height: 5px; border-radius: 50%; background: {{ $theme['spark'] }};"></span>
                            <span style="font-size: 0.6rem; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase; color: {{ $theme['pillText'] }};">{{ $theme['tier'] }}</span>
                        </div>

                        {{-- Medal Badge --}}
                        <div style="width: 4rem; height: 4rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; background: linear-gradient(135deg, {{ $theme['gradient'][0] }} 0%, {{ $theme['gradient'][1] }} 55%, {{ $theme['gradient'][2] }} 100%); box-shadow: 0 0 0 3px rgba(255,255,255,0.15), 0 8px 24px {{ $theme['glow'] }};">
                            <span style="font-size: 1.75rem; filter: drop-shadow(0 2px 3px rgba(0,0,0,0.45));">{{ $achEmoji[$ach['icon']] ?? '🏆' }}</span>
                        </div>

                        <h4 style="font-weight: 800; font-size: 0.9375rem; color: #fff; margin-bottom: 0.25rem;">{{ $ach['title'] }}</h4>

                        @if(!empty($ach['unlocked_at']))
                            <span style="font-size: 0.7rem; color: rgba(148,163,184,0.6); margin-bottom: 0.5rem;">{{ $ach['unlocked_at'] }}</span>
                        @endif

                        @if(!empty($ach['description']))
                            <p style="font-size: 0.8125rem; color: rgba(203,213,225,0.8); line-height: 1.5; margin-bottom: 0.75rem;">
                                {{ $ach['description'] }}
                            </p>
                        @endif

                        @if(!empty($ach['value']))
                            <span style="display: inline-flex; align-items: center; gap: 0.3rem; background: #F8FAFC0D; border: 1px solid rgba(255,255,255,0.12); color: #fff; font-size: 0.7rem; font-weight: 800; padding: 0.25rem 0.625rem; border-radius: 2rem;">
                                ⚡ {{ $ach['value'] }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1); border-radius: 1rem; padding: 3rem; text-align: center; color: rgba(148,163,184,0.7);">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🏆</div>
                <p style="font-weight: 700; font-size: 1rem; color: #fff;">No achievements unlocked yet</p>
                <p style="font-size: 0.8125rem; margin-top: 0.25rem; color: rgba(100,116,139,0.8);">Trophies, awards, and milestones earned by this player will be showcased here.</p>
            </div>
        @endif
    </div>

</section>

{{-- ═══ LIGHTBOX MODAL FOR FULL-RESOLUTION IMAGES ═════════════════════════════ --}}
<div id="image-lightbox"
     style="display: none; position: fixed; inset: 0; z-index: 100; background: rgba(0,0,0,0.92); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 2rem;"
     onclick="closeLightbox(event)">
    <div style="position: relative; max-width: 90vw; max-height: 90vh;">
        <button onclick="closeLightbox(null)"
                title="Close"
                style="position: absolute; top: -2.5rem; right: -0.5rem; background: rgba(255,255,255,0.15); border: none; border-radius: 50%; width: 2.25rem; height: 2.25rem; color: #fff; font-size: 1.25rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">
            ✕
        </button>
        <img id="lightbox-image"
             src=""
             alt="Zoomed image"
             style="max-width: 90vw; max-height: 85vh; object-fit: contain; border-radius: 0.75rem; box-shadow: 0 20px 40px rgba(0,0,0,0.8);" />
    </div>
</div>

<style>
    /* ─── Cricinfo Table Styles ─── */
    .cricinfo-table-container {
        border-radius: 0.75rem;
        border: 1px solid rgba(255,255,255,0.06);
        background: rgba(10,15,30,0.6);
    }
    .cricinfo-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8125rem;
        white-space: nowrap;
    }
    .cricinfo-table th {
        background: rgba(255,255,255,0.04);
        color: rgba(148,163,184,0.85);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-size: 0.7rem;
        padding: 0.75rem 0.875rem;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        text-align: center;
    }
    .cricinfo-table td {
        padding: 0.75rem 0.875rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        color: #e2e8f0;
        text-align: center;
        transition: background 0.15s;
    }
    .cricinfo-table tbody tr:hover td {
        background: rgba(245,158,11,0.05);
    }
    .cricinfo-table tbody tr:last-child td {
        border-bottom: none;
    }
</style>

<script>
    // Tab switching logic
    function switchTab(tabId) {
        document.querySelectorAll('.tab-panel').forEach(panel => {
            panel.style.display = 'none';
        });
        document.querySelectorAll('.profile-tab-btn').forEach(btn => {
            btn.style.color = 'rgba(148,163,184,0.8)';
            btn.style.borderColor = 'transparent';
        });

        const activePanel = document.getElementById('tab-panel-' + tabId);
        const activeBtn = document.getElementById('tab-btn-' + tabId);

        if (activePanel) activePanel.style.display = 'block';
        if (activeBtn) {
            activeBtn.style.color = '#f59e0b';
            activeBtn.style.borderColor = '#f59e0b';
        }
    }

    // Lightbox modal logic
    function openLightbox(url) {
        if (!url) return;
        const lightbox = document.getElementById('image-lightbox');
        const img = document.getElementById('lightbox-image');
        img.src = url;
        lightbox.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox(event) {
        if (event && event.target && event.target.id === 'lightbox-image') {
            return;
        }
        const lightbox = document.getElementById('image-lightbox');
        lightbox.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox(null);
        }
    });
</script>

@endsection
