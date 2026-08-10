@extends('admin.layouts.app')

@section('title', 'Matches')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Matches</h1>
        <a href="{{ route('admin.matches.create') }}" class="rounded bg-gray-900 text-white px-4 py-2 text-sm font-medium hover:bg-gray-800">
            + New Match
        </a>
    </div>

    <div class="flex gap-2 mb-4">
        @foreach (['' => 'All', 'live' => 'Live', 'upcoming' => 'Upcoming', 'finished' => 'Finished'] as $value => $label)
            <a href="{{ route('admin.matches.index', $value ? ['status' => $value] : []) }}"
               class="px-3 py-1.5 rounded text-sm {{ request('status', '') === $value ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-4 py-2 font-medium">Sport</th>
                    <th class="px-4 py-2 font-medium">Match</th>
                    <th class="px-4 py-2 font-medium">Date</th>
                    <th class="px-4 py-2 font-medium">Status</th>
                    <th class="px-4 py-2 font-medium">Stream</th>
                    <th class="px-4 py-2 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($matches as $match)
                    <tr>
                        <td class="px-4 py-3">{{ $match->sport->name }}</td>
                        <td class="px-4 py-3 font-medium">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $match->scheduled_at?->format('M j, Y g:ia') }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'inline-block px-2 py-0.5 rounded text-xs font-medium',
                                'bg-red-100 text-red-700' => $match->status === 'live',
                                'bg-blue-100 text-blue-700' => $match->status === 'upcoming',
                                'bg-gray-100 text-gray-600' => $match->status === 'finished',
                            ])>{{ ucfirst($match->status) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($match->youtube_stream_url)
                                <a href="{{ $match->youtube_stream_url }}" target="_blank" class="text-blue-600 hover:underline">Watch</a>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('admin.matches.players.index', $match) }}" class="text-gray-600 hover:underline">Roster</a>
                            <a href="{{ route('admin.live-score.show', $match) }}" class="text-gray-600 hover:underline">Live Score</a>
                            @if ($match->status === 'upcoming')
                                <a href="{{ route('admin.matches.edit', $match) }}" class="text-gray-600 hover:underline">Edit</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">No matches found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $matches->links() }}
    </div>
@endsection
