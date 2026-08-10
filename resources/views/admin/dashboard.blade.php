@extends('admin.layouts.app')

@section('title', 'Live Scores & Matches')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        {{-- LEFT / CENTER MAIN COLUMN (8 COLS) --}}
        <div class="lg:col-span-8 space-y-6">
            
            {{-- ESPNcricinfo Style Sub-Nav Filter Tabs --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs space-y-4">
                <div class="flex items-center gap-6 border-b border-slate-100 pb-3 font-extrabold text-xs">
                    <a href="{{ route('admin.dashboard') }}" class="text-[#0366D6] border-b-2 border-[#0366D6] pb-3 -mb-3">
                        Live Scores & Matches
                    </a>
                    <a href="{{ route('admin.matches.index') }}" class="text-slate-500 hover:text-slate-900 pb-3 -mb-3">
                        Match Schedule
                    </a>
                    <a href="{{ route('admin.matches.index', ['status' => 'finished']) }}" class="text-slate-500 hover:text-slate-900 pb-3 -mb-3">
                        Match Results
                    </a>
                </div>

                {{-- Format Filter Chips --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-bold scrollbar-none">
                    <span class="text-slate-400 text-[10px] font-extrabold uppercase mr-1">FORMAT:</span>
                    <button class="px-3 py-1 rounded-full bg-slate-900 text-white shadow-xs">All Formats</button>
                    <button class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200">Int'l</button>
                    <button class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200">T20s</button>
                    <button class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200">ODIs</button>
                    <button class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200">Tests</button>
                    <button class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200">Domestic</button>
                    <button class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200">Youth</button>
                </div>
            </div>

            {{-- Real Live Matches Section --}}
            <div>
                <h2 class="text-sm font-black text-slate-900 mb-3 flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                    </span>
                    Live Matches
                </h2>

                @if ($liveMatches->isEmpty())
                    <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-8 text-center">
                        <p class="text-xs font-bold text-slate-500">No matches are live right now</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Start an upcoming match from the schedule below to launch real-time scoring.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($liveMatches as $match)
                            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:border-[#0366D6] transition-all">
                                <div class="flex items-center justify-between text-[11px] font-bold text-slate-500 border-b border-slate-100 pb-2.5 mb-3">
                                    <span class="text-red-600 uppercase tracking-wider flex items-center gap-1 font-black">
                                        <span class="h-2 w-2 rounded-full bg-red-500 animate-ping"></span>
                                        {{ $match->sport->name }} • LIVE NOW
                                    </span>
                                    <span>{{ $match->venue ?? 'Main Arena' }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-base font-extrabold text-slate-900 leading-snug">
                                            {{ $match->homeTeam->name }} <span class="text-slate-400 font-normal">vs</span> {{ $match->awayTeam->name }}
                                        </h3>
                                        <p class="text-xs text-slate-500 mt-1">Real-time ball-by-ball score tracking enabled</p>
                                    </div>

                                    <a href="{{ route('admin.live-score.show', $match) }}" 
                                       class="px-4 py-2 rounded-xl bg-[#0366D6] hover:bg-blue-700 text-white text-xs font-extrabold shadow-xs transition-all">
                                        Open Scorer →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Real Upcoming Matches Section --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-black text-slate-900">Upcoming Fixtures</h2>
                    <a href="{{ route('admin.matches.index') }}" class="text-xs font-bold text-[#0366D6] hover:underline">View All Fixtures →</a>
                </div>

                @if ($upcomingMatches->isEmpty())
                    <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-8 text-center">
                        <p class="text-xs text-slate-400 font-medium">No upcoming matches scheduled.</p>
                    </div>
                @else
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden divide-y divide-slate-100">
                        @foreach ($upcomingMatches as $match)
                            <div class="p-4 flex items-center justify-between hover:bg-slate-50/80 transition-colors">
                                <div class="flex items-center gap-3">
                                    <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-[#0366D6] text-[10px] font-black uppercase">
                                        {{ $match->sport->name }}
                                    </span>
                                    <div>
                                        <h4 class="text-xs font-extrabold text-slate-900">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</h4>
                                        <p class="text-[10px] text-slate-500 font-semibold">{{ $match->scheduled_at?->format('D, M j • g:ia') ?? 'TBA' }} • {{ $match->venue ?? 'Venue TBA' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.matches.players.index', $match) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                                        Roster
                                    </a>
                                    <a href="{{ route('admin.live-score.show', $match) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#0366D6] text-xs font-extrabold transition-all">
                                        Score
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- RIGHT COLUMN (4 COLS): ESPNcricinfo Style Quick Links Sidebar --}}
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4">
                <h3 class="text-xs font-extrabold text-slate-900 tracking-wider uppercase border-b border-slate-100 pb-2.5">
                    Quick Links & Tools
                </h3>

                <div class="space-y-3 text-xs font-bold">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 text-slate-700 hover:text-[#0366D6] transition-colors group">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#0366D6] flex items-center justify-center text-sm font-black group-hover:scale-105 transition-transform">
                            📊
                        </div>
                        <div>
                            <span class="block text-slate-900">Desktop Scoreboard</span>
                            <span class="text-[10px] text-slate-400 font-normal">Real-time ball-by-ball scoring desk</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.matches.index') }}" class="flex items-center gap-3 text-slate-700 hover:text-[#0366D6] transition-colors group">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-black group-hover:scale-105 transition-transform">
                            📅
                        </div>
                        <div>
                            <span class="block text-slate-900">Tournament Schedule</span>
                            <span class="text-[10px] text-slate-400 font-normal">View & manage all upcoming fixtures</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.matches.create') }}" class="flex items-center gap-3 text-slate-700 hover:text-[#0366D6] transition-colors group">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-black group-hover:scale-105 transition-transform">
                            ➕
                        </div>
                        <div>
                            <span class="block text-slate-900">Create New Match</span>
                            <span class="text-[10px] text-slate-400 font-normal">Add teams, venue, and stream links</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-indigo-50/50 rounded-2xl border border-blue-100 p-5 text-xs text-slate-700 space-y-2">
                <span class="text-[10px] font-black uppercase text-[#0366D6] tracking-wider block">REALTIME FIREBASE SYNC</span>
                <p class="font-semibold text-slate-800 leading-relaxed">
                    Scoreboard changes updated on this control panel automatically broadcast live to mobile applications in real-time.
                </p>
            </div>
        </div>
    </div>
@endsection
