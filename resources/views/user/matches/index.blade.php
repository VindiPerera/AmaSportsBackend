@extends('user.layouts.app')

@section('title', 'Matches & Schedule')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-black text-brand-charcoal tracking-tight">Matches &amp; Schedule</h1>
        <p class="text-sm text-slate-500 mt-1">Manage match fixtures, review live scores, and schedule upcoming games.</p>
    </div>
    <x-button href="{{ route('user.matches.create') }}" variant="primary" size="md">
        <span>+ Create New Match</span>
    </x-button>
</div>

{{-- Status Filter Tabs --}}
<div class="flex flex-wrap gap-2 mb-6">
    @foreach([
        ['', 'All Matches'],
        ['live', '🔴 Live Now'],
        ['upcoming', '📅 Upcoming'],
        ['finished', '✓ Finished'],
    ] as [$val, $label])
        @php $isActive = ($status === $val); @endphp
        <a href="{{ route('user.matches.index', $val ? ['status' => $val] : []) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $isActive ? 'bg-brand-red text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- Matches Table Container --}}
@if($matches->isEmpty())
    <x-empty-state
        title="No matches scheduled"
        message="You haven't scheduled any fixtures yet. Click below to create your first match."
    >
        <x-button href="{{ route('user.matches.create') }}" variant="primary" size="sm">
            + Create New Match
        </x-button>
    </x-empty-state>
@else
    <x-card class="p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 font-extrabold uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-5">Sport</th>
                        <th class="py-3.5 px-5">Fixture Teams</th>
                        <th class="py-3.5 px-5">Scheduled Date</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-xs">
                    @foreach($matches as $match)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-5 font-bold text-brand-charcoal">
                                <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md text-[11px] font-extrabold">{{ $match->sport->name }}</span>
                            </td>
                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-900 text-sm">
                                    {{ $match->homeTeam->name }} <span class="text-slate-400 font-normal text-xs mx-1">vs</span> {{ $match->awayTeam->name }}
                                </div>
                                @if($match->venue)
                                    <div class="text-slate-400 text-[11px] mt-0.5">📍 {{ $match->venue }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-5 font-medium text-slate-600">
                                {{ $match->scheduled_at?->format('M j, Y • g:i A') ?? 'TBA' }}
                            </td>
                            <td class="py-4 px-5">
                                @if($match->status === 'live')
                                    <x-badge variant="live" size="sm">LIVE</x-badge>
                                @elseif($match->status === 'upcoming')
                                    <x-badge variant="info" size="sm">UPCOMING</x-badge>
                                @else
                                    <x-badge variant="neutral" size="sm">FINISHED</x-badge>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-right space-x-2">
                                <x-button href="{{ route('user.matches.edit', $match) }}" variant="secondary" size="sm">
                                    Edit
                                </x-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($matches->hasPages())
            <div class="p-4 border-t border-slate-100">
                <x-pagination :paginator="$matches" />
            </div>
        @endif
    </x-card>
@endif

@endsection
