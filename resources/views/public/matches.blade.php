@extends('public.layouts.app')

@section('title', 'Matches & Schedule — AmaX')
@section('meta_description', 'Browse live, upcoming and finished matches across all sports on AmaX. Real-time scores and matchday schedules.')

@section('content')

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Page Header --}}
    <div class="max-w-2xl mb-8">
        <span class="text-xs font-extrabold uppercase tracking-widest text-brand-red">MATCHDAY CENTER</span>
        <h1 class="text-3xl sm:text-4xl font-black text-brand-charcoal tracking-tight mt-1">Matches &amp; Schedule</h1>
        <p class="text-sm text-slate-500 mt-2">
            Real-time live scores, upcoming fixtures, and verified match results across all sports.
        </p>
    </div>

    {{-- Status Filter Tabs & Summary --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-2">
            @foreach([
                ['', 'All Matches', 'all'],
                ['live', 'Live Now', 'live'],
                ['upcoming', 'Upcoming', 'upcoming'],
                ['finished', 'Finished', 'finished'],
            ] as [$val, $label, $key])
                @php $isActive = ($status === $val); @endphp
                <a href="{{ route('public.matches', $val ? ['status' => $val] : []) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $isActive ? 'bg-brand-red text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
                    @if($key === 'live')
                        <span class="relative flex h-2 w-2">
                            @if($liveCounts > 0)
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                            @endif
                            <span class="relative inline-flex rounded-full h-2 w-2 {{ $isActive ? 'bg-white' : 'bg-brand-red' }}"></span>
                        </span>
                    @endif
                    <span>{{ $label }}</span>
                    @if($key === 'live' && $liveCounts > 0)
                        <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] {{ $isActive ? 'bg-white/20 text-white' : 'bg-red-50 text-brand-red' }}">{{ $liveCounts }}</span>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="flex items-center gap-4 text-xs font-semibold text-slate-500">
            <span><strong class="text-brand-red font-bold">{{ $liveCounts }}</strong> Live</span>
            <span>•</span>
            <span><strong class="text-amber-600 font-bold">{{ $upcomingCounts }}</strong> Upcoming</span>
            <span>•</span>
            <span><strong class="text-slate-700 font-bold">{{ $finishedCounts }}</strong> Finished</span>
        </div>
    </div>

    {{-- Matches List --}}
    @if($matches->isEmpty())
        <x-empty-state
            title="No matches found"
            message="No matches found matching the selected filter. Try selecting 'All Matches' or check back soon."
        >
            <x-button href="{{ route('public.matches') }}" variant="secondary" size="sm">
                View All Matches
            </x-button>
        </x-empty-state>
    @else
        <div class="bg-white rounded-3xl border border-slate-200/80 divide-y divide-slate-100 overflow-hidden shadow-xs">
            @foreach($matches as $match)
                <div class="p-5 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/70 transition-colors">
                    {{-- Sport Icon / Badge --}}
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-center shrink-0 text-slate-700 font-black text-xs uppercase tracking-wider">
                            {{ Str::limit($match->sport->name, 4, '') }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $match->sport->name }}</span>
                                @if($match->venue)
                                    <span class="text-xs text-slate-400 font-medium">• 📍 {{ $match->venue }}</span>
                                @endif
                            </div>

                            <div class="text-base sm:text-lg font-bold text-brand-charcoal truncate">
                                <span>{{ $match->homeTeam->name }}</span>
                                <span class="text-slate-400 font-normal text-xs uppercase tracking-wider mx-2">vs</span>
                                <span>{{ $match->awayTeam->name }}</span>
                            </div>

                            <div class="flex items-center gap-3 text-xs text-slate-500 mt-1">
                                <span>🕐 {{ $match->scheduled_at?->format('D, M j • g:i A') ?? 'Time TBA' }}</span>
                                @if($match->country)
                                    <span>• 🌍 {{ $match->country }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Match Status & Score --}}
                    <div class="flex items-center justify-between md:justify-end gap-4 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-slate-100">
                        @if($match->status === 'live')
                            <div class="text-right">
                                <x-badge variant="live" size="sm">LIVE MATCH</x-badge>
                                @if(!empty($match->live_score_display))
                                    <div class="text-base font-black text-brand-red mt-1">{{ $match->live_score_display }}</div>
                                @endif
                            </div>
                        @elseif($match->status === 'finished')
                            <div class="text-right">
                                <x-badge variant="success" size="sm">COMPLETED</x-badge>
                                @if($match->result_summary)
                                    <div class="text-xs font-semibold text-slate-600 mt-1">{{ $match->result_summary }}</div>
                                @endif
                            </div>
                        @else
                            <x-badge variant="info" size="sm">SCHEDULED</x-badge>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($matches->hasPages())
            <div class="mt-6">
                <x-pagination :paginator="$matches" />
            </div>
        @endif
    @endif

</section>

@endsection
