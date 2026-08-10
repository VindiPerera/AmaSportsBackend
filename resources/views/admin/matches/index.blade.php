@extends('admin.layouts.app')

@section('title', 'Matches Management')

@section('content')
    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Matches Directory</h1>
            <p class="text-sm text-slate-500 mt-0.5">Manage sports fixtures, set up rosters, and open live score engines</p>
        </div>
        <a href="{{ route('admin.matches.create') }}" 
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 text-sm font-bold shadow-lg shadow-blue-600/25 transition-all hover:scale-[1.02]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            + Create Match
        </a>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
        @foreach (['' => 'All Matches', 'live' => '🔴 Live Now', 'upcoming' => '📅 Upcoming', 'finished' => '✓ Finished'] as $value => $label)
            <a href="{{ route('admin.matches.index', $value ? ['status' => $value] : []) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ request('status', '') === $value ? 'bg-slate-900 text-white shadow-md' : 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300 hover:text-slate-900' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Matches Data Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Sport</th>
                        <th class="px-5 py-3.5">Match Fixture</th>
                        <th class="px-5 py-3.5">Scheduled Date & Time</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Video Stream</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($matches as $match)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-4 font-bold text-slate-800">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-extrabold bg-blue-50 text-blue-700">
                                    {{ $match->sport->name }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">{{ $match->homeTeam->name }} <span class="text-slate-400 font-normal">vs</span> {{ $match->awayTeam->name }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">{{ $match->venue ?? 'No venue assigned' }}</div>
                            </td>
                            <td class="px-5 py-4 text-xs font-medium text-slate-600">
                                {{ $match->scheduled_at?->format('M j, Y • g:ia') ?? 'N/A' }}
                            </td>
                            <td class="px-5 py-4">
                                @if ($match->status === 'live')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-extrabold bg-red-50 text-red-600 border border-red-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500 animate-ping"></span>
                                        LIVE
                                    </span>
                                @elseif ($match->status === 'upcoming')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                        UPCOMING
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                                        FINISHED
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if ($match->youtube_stream_url)
                                    <a href="{{ $match->youtube_stream_url }}" target="_blank" 
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 4-8 4z"/></svg>
                                        Live Stream
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 font-medium">None</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.matches.players.index', $match) }}" 
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                                    Roster
                                </a>
                                <a href="{{ route('admin.live-score.show', $match) }}"
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                    Live Score
                                </a>
                                @if ($match->status !== 'finished')
                                    <a href="{{ route('admin.matches.stream.show', $match) }}"
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-red-50 text-red-700 hover:bg-red-100 transition-colors">
                                        Streaming{{ $match->hasActiveStreamAccess() ? ' ✓' : '' }}
                                    </a>
                                @endif
                                @if ($match->status === 'upcoming')
                                    <a href="{{ route('admin.matches.edit', $match) }}" 
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                                        Edit
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 font-medium">
                                No matches found matching this criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $matches->links() }}
    </div>
@endsection
