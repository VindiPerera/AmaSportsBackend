@extends('admin.layouts.app')

@section('title', 'Matches Management')

@section('content')

    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-brand-charcoal tracking-tight">Matches Directory</h1>
            <p class="text-xs text-slate-500 mt-1">Manage fixture schedules, rosters, stream unlocks, and real-time score desks</p>
        </div>
        <x-button href="{{ route('admin.matches.create') }}" variant="primary" size="md">
            + Create Match
        </x-button>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
        @foreach (['' => 'All Matches', 'live' => '🔴 Live Now', 'upcoming' => '📅 Upcoming', 'finished' => '✓ Finished'] as $value => $label)
            @php $isActive = (request('status', '') === $value); @endphp
            <a href="{{ route('admin.matches.index', $value ? ['status' => $value] : []) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ $isActive ? 'bg-brand-red text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Matches Data Table Card --}}
    @if($matches->isEmpty())
        <x-empty-state
            title="No matches found"
            message="There are no fixtures matching the current filter."
        >
            <x-button href="{{ route('admin.matches.create') }}" variant="primary" size="sm">
                + Create New Match
            </x-button>
        </x-empty-state>
    @else
        <x-card class="p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-slate-50/80 border-b border-slate-100 text-slate-400 text-[10px] font-extrabold uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">Sport</th>
                            <th class="px-5 py-3.5">Match Fixture</th>
                            <th class="px-5 py-3.5">Scheduled Date</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Live Stream</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 text-xs">
                        @foreach ($matches as $match)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-4 font-bold text-slate-900">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $match->sport->name }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-slate-900 text-sm">
                                        {{ $match->homeTeam->name }} <span class="text-slate-400 font-normal text-xs mx-1">vs</span> {{ $match->awayTeam->name }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">📍 {{ $match->venue ?? 'No venue assigned' }}</div>
                                </td>
                                <td class="px-5 py-4 font-medium text-slate-600">
                                    {{ $match->scheduled_at?->format('M j, Y • g:i A') ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-4">
                                    @if ($match->status === 'live')
                                        <x-badge variant="live" size="sm">LIVE</x-badge>
                                    @elseif ($match->status === 'upcoming')
                                        <x-badge variant="info" size="sm">UPCOMING</x-badge>
                                    @else
                                        <x-badge variant="neutral" size="sm">FINISHED</x-badge>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if ($match->youtube_stream_url)
                                        <a href="{{ $match->youtube_stream_url }}" target="_blank" 
                                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-brand-red hover:bg-red-100 transition-colors border border-red-200">
                                            <span>▶ Live Stream</span>
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">None</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right space-x-1 whitespace-nowrap">
                                    <x-button href="{{ route('admin.matches.players.index', $match) }}" variant="secondary" size="sm">
                                        Roster
                                    </x-button>
                                    <x-button href="{{ route('admin.live-score.show', $match) }}" variant="quiet" size="sm">
                                        Score
                                    </x-button>
                                    @if ($match->status !== 'finished')
                                        <x-button href="{{ route('admin.matches.stream.show', $match) }}" variant="secondary" size="sm">
                                            Stream{{ $match->hasActiveStreamAccess() ? ' ✓' : '' }}
                                        </x-button>
                                    @endif
                                    @if ($match->status === 'upcoming')
                                        <x-button href="{{ route('admin.matches.edit', $match) }}" variant="ghost" size="sm">
                                            Edit
                                        </x-button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($matches->hasPages())
                <div class="p-4 border-t border-slate-100">
                    <x-pagination :paginator="$matches" />
                </div>
            @endif
        </x-card>
    @endif

@endsection
