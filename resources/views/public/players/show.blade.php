@extends('public.layouts.app')

@section('title', $displayName . ' — ' . ($activeSport ? $activeSport->name : 'Player') . ' Profile & Career Stats')
@section('meta_description', 'Explore verified career statistics, averages, recent matches, and bio of ' . $displayName . ' on AmaX.')

@section('content')

{{-- ═══ 1. HERO COVER & ATHLETE IDENTITY (PlayerProfile.io Signature Style) ═══ --}}
<section class="relative bg-white border-b border-slate-200/80">
    {{-- Cover Photo Banner --}}
    <div class="relative h-56 sm:h-72 w-full overflow-hidden bg-slate-900">
        @if($coverPhotoUrl)
            <img src="{{ $coverPhotoUrl }}"
                 alt="{{ $displayName }} Cover"
                 class="w-full h-full object-cover cursor-pointer transition-opacity hover:opacity-95"
                 onclick="openLightbox('{{ $coverPhotoUrl }}')"
                 title="Click to zoom cover photo" />
        @else
            {{-- Default Cinematic Arena Cover --}}
            <img src="{{ asset('images/stadium-cta.jpg') }}"
                 alt="Stadium Arena"
                 class="w-full h-full object-cover object-center filter brightness-[0.75] saturate-110" />
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/60 via-slate-900/40 to-transparent"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(250,189,21,0.25),transparent_60%)]"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-white via-white/30 to-transparent"></div>
    </div>

    {{-- Athlete Identity Bar --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 -mt-20 sm:-mt-24 pb-8">

            {{-- Avatar & Bio Info --}}
            <div class="flex flex-col sm:flex-row sm:items-end gap-5 sm:gap-6">
                {{-- Circular Avatar with Light Ring --}}
                <div class="relative shrink-0">
                    <div class="w-32 h-32 sm:w-36 sm:h-36 rounded-full p-1 bg-white shadow-xl ring-4 ring-slate-100/80">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}"
                                 alt="{{ $displayName }}"
                                 class="w-full h-full rounded-full object-cover cursor-pointer hover:opacity-95 transition-opacity"
                                 onclick="openLightbox('{{ $photoUrl }}')"
                                 title="Click to view full photo" />
                        @else
                            <div class="w-full h-full rounded-full bg-slate-100 flex items-center justify-center text-4xl font-black text-slate-700">
                                {{ strtoupper(substr($displayName, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    @if($photoUrl)
                        <button onclick="openLightbox('{{ $photoUrl }}')"
                                title="Zoom photo"
                                class="absolute bottom-1 right-1 bg-white border border-slate-200 rounded-full w-8 h-8 flex items-center justify-center shadow-md text-slate-600 hover:text-brand-red transition-colors cursor-pointer"
                                aria-label="Zoom profile image">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                    @endif
                </div>

                {{-- Name & Primary Badges --}}
                <div class="space-y-2 mb-1">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h1 class="text-2xl sm:text-4xl font-black text-brand-charcoal tracking-tight">
                            {{ $displayName }}
                        </h1>
                        <x-badge variant="gold" size="md" dot>Verified Athlete</x-badge>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5 text-xs font-semibold text-slate-600">
                        @if($player->country)
                            <span class="inline-flex items-center gap-1 bg-slate-100 px-2.5 py-1 rounded-full text-slate-800">
                                📍 {{ $player->country }}
                            </span>
                        @endif

                        @if($detailedAge)
                            <span class="inline-flex items-center gap-1 bg-slate-100 px-2.5 py-1 rounded-full text-slate-800">
                                ⏳ Age: <strong class="font-extrabold text-slate-900">{{ $detailedAge }}</strong>
                            </span>
                        @endif

                        @if(!empty($sportData['profile']?->playing_role))
                            <span class="inline-flex items-center gap-1 bg-red-50 text-brand-red border border-red-100 px-2.5 py-1 rounded-full font-bold">
                                🏅 {{ $sportData['profile']->playing_role }}
                            </span>
                        @elseif(!empty($sportData['profile']?->player_position))
                            <span class="inline-flex items-center gap-1 bg-red-50 text-brand-red border border-red-100 px-2.5 py-1 rounded-full font-bold">
                                🏅 {{ $sportData['profile']->player_position }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right: Actions & Team Badges --}}
            <div class="flex flex-col sm:flex-row lg:flex-col items-start sm:items-center lg:items-end gap-3 mb-1">
                {{-- Team / College Chips --}}
                <div class="flex flex-wrap items-center gap-2 justify-start lg:justify-end">
                    @if(!empty($sportData['college_university']))
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs font-bold text-slate-700">
                            @if($collegeLogoUrl)
                                <img src="{{ $collegeLogoUrl }}" alt="College" class="w-4 h-4 object-contain rounded-xs" />
                            @else
                                <span>🎓</span>
                            @endif
                            <span>{{ $sportData['college_university'] }}</span>
                        </div>
                    @endif

                    @if(!empty($teamNames))
                        @foreach($teamNames as $teamName)
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-900 border border-amber-200 text-xs font-bold">
                                @if(isset($teamLogos[$teamName]))
                                    <img src="{{ $teamLogos[$teamName] }}" alt="{{ $teamName }}" class="w-4 h-4 object-contain rounded-xs" />
                                @else
                                    <span>🛡️</span>
                                @endif
                                <span>{{ $teamName }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Action CTAs --}}
                <div class="flex items-center gap-2">
                    <x-button type="button" variant="secondary" size="sm" onclick="copyProfileLink()" id="share-btn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        <span id="share-btn-text">Share Profile</span>
                    </x-button>

                    <x-button type="button" variant="dark" size="sm" onclick="window.print()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Print CV</span>
                    </x-button>
                </div>
            </div>
        </div>

        {{-- Multi-Sport Switcher Bar (if player plays multiple sports) --}}
        @if($playerSportsList->count() > 1)
            <div class="border-t border-slate-100 py-3 flex items-center gap-2 overflow-x-auto scrollbar-none">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mr-2 shrink-0">Sport:</span>
                @foreach($playerSportsList as $s)
                    @php $isActiveSport = ($s['slug'] === $activeSlug); @endphp
                    <a href="{{ route('public.players.show', ['player' => $player->id, 'sportSlug' => $s['slug']]) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold transition-all shrink-0 {{ $isActiveSport ? 'bg-brand-red text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        <span>{{ $s['icon'] }}</span>
                        <span>{{ $s['name'] }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- ═══ 2. QUICK BIOGRAPHICAL ATTRIBUTES BAR ═════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 text-left">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Full Name</span>
                <span class="text-sm font-bold text-slate-900 block mt-0.5 truncate">{{ $displayName }}</span>
            </div>
            @if($bornDateFormatted)
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Birth Date</span>
                    <span class="text-sm font-bold text-slate-900 block mt-0.5">{{ $bornDateFormatted }}</span>
                </div>
            @endif
            @if($detailedAge)
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Current Age</span>
                    <span class="text-sm font-bold text-brand-red block mt-0.5">{{ $detailedAge }}</span>
                </div>
            @endif
            @if(!empty($sportData['height']))
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Height</span>
                    <span class="text-sm font-bold text-slate-900 block mt-0.5">{{ $sportData['height'] }}</span>
                </div>
            @endif
            @if(!empty($sportData['profile']?->batting_style))
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Batting Style</span>
                    <span class="text-sm font-bold text-slate-900 block mt-0.5">{{ $sportData['profile']->batting_style }}</span>
                </div>
            @endif
            @if(!empty($sportData['profile']?->bowling_style))
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Bowling Style</span>
                    <span class="text-sm font-bold text-slate-900 block mt-0.5">{{ $sportData['profile']->bowling_style }}</span>
                </div>
            @endif
            @if(!empty($sportData['profile']?->dominant_hand))
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Dominant Hand</span>
                    <span class="text-sm font-bold text-slate-900 block mt-0.5">{{ ucfirst((string) $sportData['profile']->dominant_hand) }}</span>
                </div>
            @endif
            @if(!empty($sportData['profile']?->dominant_leg))
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Dominant Leg</span>
                    <span class="text-sm font-bold text-slate-900 block mt-0.5">{{ ucfirst((string) $sportData['profile']->dominant_leg) }}</span>
                </div>
            @endif
            @if(!empty($sportData['profile']?->player_position))
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Position</span>
                    <span class="text-sm font-bold text-slate-900 block mt-0.5">{{ $sportData['profile']->player_position }}</span>
                </div>
            @endif
            @if(!empty($sportData['profile']?->weight))
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Weight</span>
                    <span class="text-sm font-bold text-slate-900 block mt-0.5">{{ $sportData['profile']->weight }} kg</span>
                </div>
            @endif
            @if(!empty($sportData['profile']?->fide_id))
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">FIDE ID</span>
                    <span class="text-sm font-mono font-bold text-slate-900 block mt-0.5">{{ $sportData['profile']->fide_id }}</span>
                </div>
            @endif
            @if(!empty($sportData['profile']?->current_ranking))
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Ranking</span>
                    <span class="text-sm font-extrabold text-amber-600 block mt-0.5">#{{ $sportData['profile']->current_ranking }}</span>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ═══ 3. TABBED PROFILE CONTENT ═════════════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 flex-1">

    {{-- Tabs Navigation Bar --}}
    <div class="border-b border-slate-200 mb-8 overflow-x-auto flex space-x-8">
        <button id="tab-btn-overview"
                onclick="switchProfileTab('overview')"
                class="profile-tab-btn border-b-2 border-brand-red text-brand-red font-bold text-sm py-3 px-1 flex items-center gap-2 cursor-pointer transition-colors">
            <span>👤 Overview &amp; Bio</span>
        </button>

        <button id="tab-btn-stats"
                onclick="switchProfileTab('stats')"
                class="profile-tab-btn border-b-2 border-transparent text-slate-500 hover:text-slate-800 font-bold text-sm py-3 px-1 flex items-center gap-2 cursor-pointer transition-colors">
            <span>📊 Career Statistics</span>
        </button>

        <button id="tab-btn-matches"
                onclick="switchProfileTab('matches')"
                class="profile-tab-btn border-b-2 border-transparent text-slate-500 hover:text-slate-800 font-bold text-sm py-3 px-1 flex items-center gap-2 cursor-pointer transition-colors">
            <span>📅 Match Logs</span>
        </button>

        <button id="tab-btn-achievements"
                onclick="switchProfileTab('achievements')"
                class="profile-tab-btn border-b-2 border-transparent text-slate-500 hover:text-slate-800 font-bold text-sm py-3 px-1 flex items-center gap-2 cursor-pointer transition-colors">
            <span>🏆 Honors &amp; Achievements</span>
            <span class="bg-amber-100 text-amber-900 text-xs px-2 py-0.5 rounded-full">{{ count($achievements) }}</span>
        </button>
    </div>

    {{-- ── TAB 1: OVERVIEW ── --}}
    <div id="tab-panel-overview" class="profile-tab-panel space-y-8">
        {{-- KPI StatCards Grid (if present) --}}
        @if(!empty($sportData['kpi_cards']))
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($sportData['kpi_cards'] as $kpi)
                    <x-stat-card
                        :label="$kpi['label']"
                        :value="$kpi['value']"
                        :subtext="$kpi['sub'] ?? null"
                        accent="gold"
                    />
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Personal & Technical Attributes --}}
            <x-card>
                <x-slot:header>
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <span>📋 Technical &amp; Physical Specifications</span>
                    </h3>
                </x-slot:header>

                <div class="divide-y divide-slate-100 text-sm">
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-500">Nationality</span>
                        <span class="font-bold text-slate-900">{{ $player->country ?: '—' }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-500">Date of Birth</span>
                        <span class="font-bold text-slate-900">{{ $bornDateFormatted ?: '—' }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-500">Age</span>
                        <span class="font-bold text-brand-red">{{ $detailedAge ?: '—' }}</span>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-500">Height</span>
                        <span class="font-bold text-slate-900">{{ $sportData['height'] ?: '—' }}</span>
                    </div>
                    @foreach($sportData['overview_fields'] as $field)
                        <div class="py-2.5 flex justify-between">
                            <span class="text-slate-500">{{ $field['label'] }}</span>
                            <span class="font-bold text-slate-900">{{ $field['value'] ?: '—' }}</span>
                        </div>
                    @endforeach
                </div>
            </x-card>

            {{-- Sport-Specific Technical Breakdown or Personal Bests --}}
            @if($activeSlug === 'cricket')
                <x-card>
                    <x-slot:header>
                        <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                            <span>🎯 Bowling &amp; Delivery Breakdown</span>
                        </h3>
                    </x-slot:header>

                    @if(!empty($sportData['pitching_line_breakdown']))
                        <div class="mb-5">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Pitching Line</span>
                            <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 text-center">
                                @foreach($sportData['pitching_line_breakdown'] as $lineKey => $lineVal)
                                    <div class="bg-slate-50 p-2 rounded-xl border border-slate-100">
                                        <div class="text-[10px] font-bold text-slate-400 uppercase truncate">{{ ucwords(str_replace('_', ' ', $lineKey)) }}</div>
                                        <div class="text-sm font-extrabold text-slate-900 mt-0.5">{{ $lineVal }}%</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($sportData['ball_type_breakdown']))
                        <div>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Ball Type</span>
                            <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 text-center">
                                @foreach($sportData['ball_type_breakdown'] as $ballKey => $ballVal)
                                    <div class="bg-slate-50 p-2 rounded-xl border border-slate-100">
                                        <div class="text-[10px] font-bold text-slate-400 uppercase truncate">{{ ucwords(str_replace('_', ' ', $ballKey)) }}</div>
                                        <div class="text-sm font-extrabold text-slate-900 mt-0.5">{{ $ballVal }}%</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(empty($sportData['pitching_line_breakdown']) && empty($sportData['ball_type_breakdown']))
                        <div class="text-center py-8 text-slate-400 text-xs">
                            No delivery breakdown recorded yet for this athlete.
                        </div>
                    @endif
                </x-card>

            @elseif(!empty($sportData['personal_bests']))
                <x-card>
                    <x-slot:header>
                        <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                            <span>⏱️ Personal Bests &amp; Track Records</span>
                        </h3>
                    </x-slot:header>

                    <div class="divide-y divide-slate-100 text-sm">
                        @foreach($sportData['personal_bests'] as $pb)
                            <div class="py-2.5 flex justify-between items-center">
                                <span class="text-slate-600 font-medium">{{ $pb['event'] }}</span>
                                <span class="font-extrabold text-brand-red text-base">{{ $pb['record'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-card>
            @else
                <x-card>
                    <x-slot:header>
                        <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                            <span>🛡️ Athlete Passport &amp; Profile Summary</span>
                        </h3>
                    </x-slot:header>

                    <div class="space-y-4 text-xs">
                        <p class="text-slate-600 leading-relaxed">
                            Verified athlete profile registered under <strong class="text-slate-900 font-bold">{{ $activeSport ? $activeSport->name : 'AmaX' }}</strong>. Verified career metrics, match fixture results, and honors are recorded on this official page.
                        </p>

                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Registered Disciplines</span>
                                <span class="text-base font-black text-brand-charcoal mt-1 block">{{ $playerSportsList->count() }} {{ Str::plural('Sport', $playerSportsList->count()) }}</span>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Honors Earned</span>
                                <span class="text-base font-black text-amber-600 mt-1 block">{{ count($achievements) }} {{ Str::plural('Award', count($achievements)) }}</span>
                            </div>
                        </div>

                        @if(!empty($teamNames))
                            <div class="pt-2 border-t border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1.5">Affiliated Teams / Clubs</span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($teamNames as $tName)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-900 border border-amber-200 text-xs font-bold">
                                            🛡️ {{ $tName }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </x-card>
            @endif
        </div>

        {{-- Photo Gallery (if any) --}}
        @if(!empty($galleryPhotos))
            <x-card>
                <x-slot:header>
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <span>📸 Athlete Gallery ({{ count($galleryPhotos) }})</span>
                    </h3>
                </x-slot:header>

                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
                    @foreach($galleryPhotos as $photo)
                        <div class="aspect-square rounded-xl overflow-hidden bg-slate-100 border border-slate-200 cursor-pointer group"
                             onclick="openLightbox('{{ $photo['url'] }}')">
                            <img src="{{ $photo['url'] }}" alt="Gallery" class="w-full h-full object-cover transition-transform group-hover:scale-105" />
                        </div>
                    @endforeach
                </div>
            </x-card>
        @endif
    </div>

    {{-- ── TAB 2: CAREER STATISTICS ── --}}
    <div id="tab-panel-stats" class="profile-tab-panel hidden space-y-8">
        @if(!empty($sportData['batting_stats']) || !empty($sportData['bowling_stats']))
            {{-- 1. Batting Career Statistics Table --}}
            <x-card>
                <x-slot:header>
                    <div class="flex items-center justify-between w-full">
                        <h3 class="font-bold text-slate-900 text-sm">
                            {{ $activeSlug === 'soft-ball-cricket' ? '🏏 Softball Cricket Batting Career Statistics' : '🏏 Batting & Fielding Career Statistics' }}
                        </h3>
                        <x-badge variant="gold">Official Records</x-badge>
                    </div>
                </x-slot:header>

                @if(!empty($sportData['batting_stats']))
                    <div class="overflow-x-auto -mx-5 -mb-5 sm:-mx-6 sm:-mb-6">
                        <table class="w-full text-xs text-center border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4 text-left">Format</th>
                                    <th class="py-3 px-4 text-left">Category</th>
                                    @if($activeSlug === 'cricket')
                                        <th class="py-3 px-4 text-left">Type</th>
                                    @endif
                                    <th class="py-3 px-2">Mat</th>
                                    <th class="py-3 px-2">Inns</th>
                                    <th class="py-3 px-2">NO</th>
                                    <th class="py-3 px-3 font-extrabold text-brand-red">Runs</th>
                                    <th class="py-3 px-2">HS</th>
                                    <th class="py-3 px-3 font-extrabold text-slate-900">Avg</th>
                                    <th class="py-3 px-2">SR</th>
                                    <th class="py-3 px-2 text-amber-600">100s</th>
                                    <th class="py-3 px-2 text-amber-600">50s</th>
                                    <th class="py-3 px-2">4s</th>
                                    <th class="py-3 px-2">6s</th>
                                    <th class="py-3 px-2">Ct</th>
                                    @if($activeSlug === 'cricket')
                                        <th class="py-3 px-2">St</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @foreach($sportData['batting_stats'] as $row)
                                    <tr class="hover:bg-red-50/30 transition-colors">
                                        <td class="py-3 px-4 text-left font-bold text-slate-900">{{ $row['format'] }}</td>
                                        <td class="py-3 px-4 text-left text-slate-500">{{ $row['category'] }}</td>
                                        @if($activeSlug === 'cricket')
                                            <td class="py-3 px-4 text-left text-slate-400">{{ $row['match_type'] ?? '—' }}</td>
                                        @endif
                                        <td class="py-3 px-2">{{ $row['matches'] ?: '—' }}</td>
                                        <td class="py-3 px-2">{{ $row['innings'] ?: '—' }}</td>
                                        <td class="py-3 px-2">{{ $row['not_out'] ?: '—' }}</td>
                                        <td class="py-3 px-3 font-extrabold text-brand-red text-sm">{{ $row['runs'] ?: '0' }}</td>
                                        <td class="py-3 px-2 font-bold">{{ $row['hs'] ?: '—' }}</td>
                                        <td class="py-3 px-3 font-bold text-slate-900">{{ $row['average'] !== null && $row['average'] !== '' ? number_format((float)$row['average'], 2) : '—' }}</td>
                                        <td class="py-3 px-2">{{ $row['sr'] !== null && $row['sr'] !== '' ? number_format((float)$row['sr'], 1) : '—' }}</td>
                                        <td class="py-3 px-2 font-bold text-amber-600">{{ $row['hundreds'] ?: '0' }}</td>
                                        <td class="py-3 px-2 font-bold text-amber-600">{{ $row['fifties'] ?: '0' }}</td>
                                        <td class="py-3 px-2">{{ $row['fours'] ?: '0' }}</td>
                                        <td class="py-3 px-2">{{ $row['sixes'] ?: '0' }}</td>
                                        <td class="py-3 px-2">{{ $row['catches'] ?: '0' }}</td>
                                        @if($activeSlug === 'cricket')
                                            <td class="py-3 px-2">{{ $row['stumpings'] ?: '0' }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center py-8 text-xs text-slate-400">No batting statistics recorded yet.</p>
                @endif
            </x-card>

            {{-- 2. Bowling Career Statistics Table --}}
            <x-card>
                <x-slot:header>
                    <div class="flex items-center justify-between w-full">
                        <h3 class="font-bold text-slate-900 text-sm">
                            {{ $activeSlug === 'soft-ball-cricket' ? '⚾ Softball Cricket Bowling Career Statistics' : '⚾ Bowling Career Statistics' }}
                        </h3>
                        <x-badge variant="gold">Official Records</x-badge>
                    </div>
                </x-slot:header>

                @if(!empty($sportData['bowling_stats']))
                    <div class="overflow-x-auto -mx-5 -mb-5 sm:-mx-6 sm:-mb-6">
                        <table class="w-full text-xs text-center border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4 text-left">Format</th>
                                    <th class="py-3 px-4 text-left">Category</th>
                                    @if($activeSlug === 'cricket')
                                        <th class="py-3 px-4 text-left">Type</th>
                                    @endif
                                    <th class="py-3 px-2">Mat</th>
                                    <th class="py-3 px-2">Inns</th>
                                    <th class="py-3 px-2">Balls</th>
                                    @if($activeSlug === 'cricket')
                                        <th class="py-3 px-2">Dots</th>
                                    @endif
                                    <th class="py-3 px-2">Runs</th>
                                    <th class="py-3 px-3 font-extrabold text-brand-red">Wkts</th>
                                    <th class="py-3 px-2 font-bold">BBI</th>
                                    @if($activeSlug === 'cricket')
                                        <th class="py-3 px-2">BBM</th>
                                    @endif
                                    <th class="py-3 px-3 font-extrabold text-slate-900">Avg</th>
                                    <th class="py-3 px-2">Econ</th>
                                    @if($activeSlug === 'cricket')
                                        <th class="py-3 px-2">SR</th>
                                        <th class="py-3 px-2 text-amber-600">4w</th>
                                    @endif
                                    <th class="py-3 px-2 text-amber-600">5w</th>
                                    @if($activeSlug === 'cricket')
                                        <th class="py-3 px-2 text-amber-600">10w</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @foreach($sportData['bowling_stats'] as $row)
                                    <tr class="hover:bg-red-50/30 transition-colors">
                                        <td class="py-3 px-4 text-left font-bold text-slate-900">{{ $row['format'] }}</td>
                                        <td class="py-3 px-4 text-left text-slate-500">{{ $row['category'] }}</td>
                                        @if($activeSlug === 'cricket')
                                            <td class="py-3 px-4 text-left text-slate-400">{{ $row['match_type'] ?? '—' }}</td>
                                        @endif
                                        <td class="py-3 px-2">{{ $row['matches'] ?: '—' }}</td>
                                        <td class="py-3 px-2">{{ $row['innings'] ?: '—' }}</td>
                                        <td class="py-3 px-2">{{ $row['balls'] ?: '—' }}</td>
                                        @if($activeSlug === 'cricket')
                                            <td class="py-3 px-2">{{ $row['dot_balls'] ?? '—' }}</td>
                                        @endif
                                        <td class="py-3 px-2">{{ $row['runs'] ?: '—' }}</td>
                                        <td class="py-3 px-3 font-extrabold text-brand-red text-sm">{{ $row['wickets'] ?: '0' }}</td>
                                        <td class="py-3 px-2 font-bold text-slate-900">{{ $row['bbi'] ?: '—' }}</td>
                                        @if($activeSlug === 'cricket')
                                            <td class="py-3 px-2">{{ $row['bbm'] ?? '—' }}</td>
                                        @endif
                                        <td class="py-3 px-3 font-bold text-slate-900">{{ $row['average'] !== null && $row['average'] !== '' ? number_format((float)$row['average'], 2) : '—' }}</td>
                                        <td class="py-3 px-2">{{ $row['economy'] !== null && $row['economy'] !== '' ? number_format((float)$row['economy'], 2) : '—' }}</td>
                                        @if($activeSlug === 'cricket')
                                            <td class="py-3 px-2">{{ $row['sr'] !== null && $row['sr'] !== '' ? number_format((float)$row['sr'], 1) : '—' }}</td>
                                            <td class="py-3 px-2 font-bold text-amber-600">{{ $row['four_w'] ?? '0' }}</td>
                                        @endif
                                        <td class="py-3 px-2 font-bold text-amber-600">{{ $row['five_w'] ?? '0' }}</td>
                                        @if($activeSlug === 'cricket')
                                            <td class="py-3 px-2 font-bold text-amber-600">{{ $row['ten_w'] ?? '0' }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center py-8 text-xs text-slate-400">No bowling statistics recorded yet.</p>
                @endif
            </x-card>

        @elseif(!empty($sportData['stat_tables']))
            @foreach($sportData['stat_tables'] as $statTable)
                <x-card>
                    <x-slot:header>
                        <h3 class="font-bold text-slate-900 text-sm">📊 {{ $statTable['title'] }}</h3>
                    </x-slot:header>

                    @if(!empty($statTable['rows']))
                        <div class="overflow-x-auto -mx-5 -mb-5 sm:-mx-6 sm:-mb-6">
                            <table class="w-full text-xs text-center border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                                        @foreach($statTable['columns'] as $colHeader)
                                            <th class="py-3 px-4 {{ $loop->first ? 'text-left' : '' }}">{{ $colHeader }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                    @foreach($statTable['rows'] as $statRow)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            @foreach($statTable['keys'] as $k)
                                                <td class="py-3 px-4 {{ $loop->first ? 'text-left font-bold text-slate-900' : '' }}">
                                                    {{ $statRow[$k] !== null && $statRow[$k] !== '' ? $statRow[$k] : '—' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center py-8 text-xs text-slate-400">No stats recorded yet for this sport.</p>
                    @endif
                </x-card>
            @endforeach
        @else
            <x-empty-state
                title="No career statistics recorded yet"
                message="Career stats will appear here once matches and performance metrics are scored."
            />
        @endif
    </div>

    {{-- ── TAB 3: MATCH LOGS ── --}}
    <div id="tab-panel-matches" class="profile-tab-panel hidden space-y-6">
        @if(!empty($sportData['recent_matches']))
            <x-card>
                <x-slot:header>
                    <h3 class="font-bold text-slate-900 text-sm">
                        📅 {{ $activeSlug === 'soft-ball-cricket' ? 'Recent Softball Cricket Match Logs' : 'Recent Cricket Match Logs' }}
                    </h3>
                </x-slot:header>

                <div class="overflow-x-auto -mx-5 -mb-5 sm:-mx-6 sm:-mb-6">
                    <table class="w-full text-xs text-center border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                                <th class="py-3 px-4 text-left">Date</th>
                                <th class="py-3 px-4 text-left">Opponent</th>
                                <th class="py-3 px-3">Batting</th>
                                <th class="py-3 px-3">Bowling</th>
                                <th class="py-3 px-2">Catches</th>
                                @if($activeSlug === 'cricket')
                                    <th class="py-3 px-2">Stumpings</th>
                                    <th class="py-3 px-3 text-right">Status</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($sportData['recent_matches'] as $m)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3 px-4 text-left font-bold text-slate-900">{{ $m['match_date'] }}</td>
                                    <td class="py-3 px-4 text-left font-bold text-brand-charcoal">vs {{ $m['opponent'] }}</td>
                                    <td class="py-3 px-3 font-extrabold text-slate-900">
                                        @if($m['runs'] !== null && $m['runs'] !== '')
                                            <span class="text-brand-red">{{ $m['runs'] }}</span>
                                            <span class="text-slate-400 font-normal text-[10px]">({{ $m['balls'] ?? 0 }}b)</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 font-extrabold text-slate-900">
                                        @if($m['wickets'] !== null && $m['wickets'] !== '')
                                            <span class="text-slate-900">{{ $m['wickets'] }} wkts</span>
                                            <span class="text-slate-400 font-normal text-[10px]">({{ $m['overs'] ?? 0 }}ov)</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="py-3 px-2">{{ $m['catches'] ?: '0' }}</td>
                                    @if($activeSlug === 'cricket')
                                        <td class="py-3 px-2">{{ $m['stumpings'] ?: '0' }}</td>
                                        <td class="py-3 px-3 text-right">
                                            @if(!empty($m['played_xi']))
                                                <x-badge variant="success" size="sm">PLAYED XI</x-badge>
                                            @else
                                                <x-badge variant="neutral" size="sm">SQUAD</x-badge>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>

        @elseif(!empty($sportData['recent_tables']))
            @foreach($sportData['recent_tables'] as $recentTable)
                <x-card>
                    <x-slot:header>
                        <h3 class="font-bold text-slate-900 text-sm">📅 {{ $recentTable['title'] }}</h3>
                    </x-slot:header>

                    @if(!empty($recentTable['rows']))
                        <div class="overflow-x-auto -mx-5 -mb-5 sm:-mx-6 sm:-mb-6">
                            <table class="w-full text-xs text-center border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                                        @foreach($recentTable['columns'] as $colHeader)
                                            <th class="py-3 px-4 {{ $loop->first ? 'text-left' : ($loop->last ? 'text-right' : '') }}">{{ $colHeader }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                    @foreach($recentTable['rows'] as $row)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            @foreach($recentTable['keys'] as $k)
                                                @if($k === 'result')
                                                    <td class="py-3 px-4">
                                                        @php
                                                            $res = strtoupper((string) ($row[$k] ?? ''));
                                                            $badgeVariant = 'neutral';
                                                            if (str_contains($res, 'WIN') || str_contains($res, 'GOLD') || str_contains($res, 'CHAMPION')) {
                                                                $badgeVariant = 'success';
                                                            } elseif (str_contains($res, 'SILVER') || str_contains($res, 'BRONZE')) {
                                                                $badgeVariant = 'gold';
                                                            } elseif (str_contains($res, 'LOSS')) {
                                                                $badgeVariant = 'danger';
                                                            }
                                                        @endphp
                                                        @if($row[$k] !== null && $row[$k] !== '—')
                                                            <x-badge :variant="$badgeVariant" size="sm">{{ $row[$k] }}</x-badge>
                                                        @else
                                                            <span class="text-slate-400">—</span>
                                                        @endif
                                                    </td>
                                                @elseif($loop->first)
                                                    <td class="py-3 px-4 text-left font-bold text-slate-900">{{ $row[$k] ?: '—' }}</td>
                                                @elseif($loop->last)
                                                    <td class="py-3 px-4 text-right font-medium text-slate-600">{{ $row[$k] ?: '—' }}</td>
                                                @else
                                                    <td class="py-3 px-4 font-semibold text-slate-800">{{ $row[$k] ?: '—' }}</td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center py-8 text-xs text-slate-400">No recent match records found for this sport.</p>
                    @endif
                </x-card>
            @endforeach
        @else
            <x-empty-state
                title="No match logs recorded yet"
                message="Match logs will automatically populate when matches and competitions are scored."
            />
        @endif
    </div>

    {{-- ── TAB 4: ACHIEVEMENTS (Classic Trophy & Medal Showcase in Light Theme) ── --}}
    <div id="tab-panel-achievements" class="profile-tab-panel hidden space-y-6">
        @if(!empty($achievements))
            @php
                $achEmoji = [
                    'trophy' => '🏆', 'medal' => '🥇', 'star' => '⭐',
                    'award' => '🎖️', 'badge' => '🏅', 'target' => '🎯',
                    'crown' => '👑', 'flame' => '🔥', 'ribbon' => '🎖️',
                    'flash' => '⚡', 'flag' => '🚩', 'shield-checkmark' => '🛡️',
                    'trending-up' => '📈', 'rocket' => '🚀', 'diamond' => '💎',
                    'sparkles' => '✨', 'baseball' => '⚾',
                ];

                $achTheme = function($ach) {
                    $slug = strtolower($ach['icon'] ?? '');
                    $title = strtolower($ach['title'] ?? '');
                    $color = strtolower($ach['color'] ?? '');

                    // Gold Tier
                    if (in_array($slug, ['trophy', 'crown']) || str_contains($title, 'century') || str_contains($title, 'gold') || str_contains($title, 'champion')) {
                        return [
                            'gradient' => ['#FEF08A', '#F59E0B', '#B45309'],
                            'glow' => 'rgba(245, 158, 11, 0.32)',
                            'pillBg' => '#FEF3C7',
                            'pillBorder' => '#FDE68A',
                            'pillText' => '#92400E',
                            'spark' => '#F59E0B',
                            'borderHover' => 'rgba(245, 158, 11, 0.45)',
                            'tier' => 'GOLD TIER',
                        ];
                    }

                    // Ruby / Red Elite Tier
                    if (in_array($slug, ['star', 'flame', 'flash']) || str_contains($title, 'fire') || str_contains($title, 'run machine') || str_starts_with($color, '#e') || str_starts_with($color, '#d')) {
                        return [
                            'gradient' => ['#FECDD3', '#EC1F24', '#9F1239'],
                            'glow' => 'rgba(236, 31, 36, 0.28)',
                            'pillBg' => '#FEF2F2',
                            'pillBorder' => '#FECACA',
                            'pillText' => '#991B1B',
                            'spark' => '#EC1F24',
                            'borderHover' => 'rgba(236, 31, 36, 0.4)',
                            'tier' => 'ELITE TIER',
                        ];
                    }

                    // Sapphire / Blue Tier (Fifty club, ribbon, rocket, blue)
                    if (in_array($slug, ['ribbon', 'rocket', 'diamond']) || str_contains($title, 'fifty') || str_starts_with($color, '#3') || str_starts_with($color, '#2') || str_starts_with($color, '#0')) {
                        return [
                            'gradient' => ['#BAE6FD', '#0284C7', '#0369A1'],
                            'glow' => 'rgba(2, 132, 199, 0.28)',
                            'pillBg' => '#F0F9FF',
                            'pillBorder' => '#BAE6FD',
                            'pillText' => '#075985',
                            'spark' => '#0284C7',
                            'borderHover' => 'rgba(2, 132, 199, 0.4)',
                            'tier' => 'SAPPHIRE TIER',
                        ];
                    }

                    // Emerald / Green Master Tier
                    if (in_array($slug, ['target', 'award', 'trending-up']) || str_starts_with($color, '#1') || str_starts_with($color, '#05')) {
                        return [
                            'gradient' => ['#A7F3D0', '#10B981', '#065F46'],
                            'glow' => 'rgba(16, 185, 129, 0.28)',
                            'pillBg' => '#ECFDF5',
                            'pillBorder' => '#A7F3D0',
                            'pillText' => '#065F46',
                            'spark' => '#10B981',
                            'borderHover' => 'rgba(16, 185, 129, 0.4)',
                            'tier' => 'MASTER TIER',
                        ];
                    }

                    // Silver Tier (Medal, Badge, Flag, Shield, Neutral)
                    return [
                        'gradient' => ['#E2E8F0', '#94A3B8', '#475569'],
                        'glow' => 'rgba(148, 163, 184, 0.28)',
                        'pillBg' => '#F1F5F9',
                        'pillBorder' => '#E2E8F0',
                        'pillText' => '#334155',
                        'spark' => '#64748B',
                        'borderHover' => 'rgba(148, 163, 184, 0.5)',
                        'tier' => 'SILVER TIER',
                    ];
                };
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($achievements as $ach)
                    @php $theme = $achTheme($ach); @endphp
                    <div class="group bg-white border border-slate-200/90 rounded-2xl p-6 flex flex-col items-center text-center shadow-xs hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 relative"
                         style="transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;"
                         onmouseover="this.style.borderColor='{{ $theme['borderHover'] }}';"
                         onmouseout="this.style.borderColor='rgba(226, 232, 240, 0.9)';">

                        {{-- Tier Tag at Top --}}
                        <div class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 mb-4 shadow-2xs"
                             style="background: {{ $theme['pillBg'] }}; border: 1px solid {{ $theme['pillBorder'] }};">
                            <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background: {{ $theme['spark'] }}; box-shadow: 0 0 6px {{ $theme['spark'] }};"></span>
                            <span class="text-[10px] font-black uppercase tracking-wider" style="color: {{ $theme['pillText'] }};">{{ $theme['tier'] }}</span>
                        </div>

                        {{-- Circular 3D Medal Badge with Glow --}}
                        <div class="w-18 h-18 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mb-4 transition-transform duration-300 group-hover:scale-110 shadow-lg"
                             style="background: linear-gradient(135deg, {{ $theme['gradient'][0] }} 0%, {{ $theme['gradient'][1] }} 55%, {{ $theme['gradient'][2] }} 100%); box-shadow: 0 0 0 4px #FFFFFF, 0 10px 25px {{ $theme['glow'] }};">
                            <span class="text-3xl sm:text-4xl filter drop-shadow-md select-none">
                                {{ $achEmoji[$ach['icon']] ?? ($ach['icon'] ?: '🏆') }}
                            </span>
                        </div>

                        {{-- Title --}}
                        <h4 class="font-extrabold text-slate-900 text-sm sm:text-base leading-snug mb-1">
                            {{ $ach['title'] }}
                        </h4>

                        {{-- Date Earned --}}
                        @if(!empty($ach['unlocked_at']))
                            <span class="text-[11px] font-semibold text-slate-400 mb-2.5 block">
                                Earned {{ $ach['unlocked_at'] }}
                            </span>
                        @endif

                        {{-- Description --}}
                        @if(!empty($ach['description']))
                            <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                {{ $ach['description'] }}
                            </p>
                        @endif

                        {{-- Value Pill --}}
                        @if(!empty($ach['value']))
                            <div class="mt-auto pt-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-slate-50 text-slate-700 border border-slate-200 shadow-2xs">
                                    <span style="color: #F59E0B;">⚡</span> {{ $ach['value'] }}
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center shadow-xs">
                <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-600 text-3xl flex items-center justify-center mx-auto mb-3 shadow-xs border border-amber-200/60">
                    🏆
                </div>
                <h3 class="font-extrabold text-slate-800 text-base">No achievements unlocked yet</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Trophies, awards, and milestone records earned by this athlete will be showcased here.</p>
            </div>
        @endif
    </div>
</section>

{{-- ═══ 4. LIGHTBOX MODAL ═══════════════════════════════════════════════════ --}}
<div id="image-lightbox"
     class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-xs flex items-center justify-center p-4"
     onclick="closeLightbox(event)">
    <div class="relative max-w-4xl max-h-[90vh]">
        <button type="button"
                onclick="closeLightbox(null)"
                class="absolute -top-10 right-0 text-white hover:text-brand-red text-2xl font-bold cursor-pointer"
                aria-label="Close image">
            ✕
        </button>
        <img id="lightbox-image"
             src=""
             alt="Zoomed image"
             class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain" />
    </div>
</div>

@push('scripts')
<script>
    function switchProfileTab(tabId) {
        document.querySelectorAll('.profile-tab-panel').forEach(panel => {
            panel.classList.add('hidden');
        });
        document.querySelectorAll('.profile-tab-btn').forEach(btn => {
            btn.classList.remove('border-brand-red', 'text-brand-red');
            btn.classList.add('border-transparent', 'text-slate-500');
        });

        const activePanel = document.getElementById('tab-panel-' + tabId);
        const activeBtn = document.getElementById('tab-btn-' + tabId);

        if (activePanel) activePanel.classList.remove('hidden');
        if (activeBtn) {
            activeBtn.classList.add('border-brand-red', 'text-brand-red');
            activeBtn.classList.remove('border-transparent', 'text-slate-500');
        }
    }

    function openLightbox(url) {
        if (!url) return;
        const lightbox = document.getElementById('image-lightbox');
        const img = document.getElementById('lightbox-image');
        img.src = url;
        lightbox.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeLightbox(e) {
        if (e && e.target && e.target.id === 'lightbox-image') return;
        const lightbox = document.getElementById('image-lightbox');
        lightbox.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function copyProfileLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const btnText = document.getElementById('share-btn-text');
            if (btnText) {
                const old = btnText.innerText;
                btnText.innerText = 'Link Copied!';
                setTimeout(() => { btnText.innerText = old; }, 2000);
            }
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeLightbox(null);
    });
</script>
@endpush

@endsection
