@extends('admin.layouts.app')

@section('title', 'Player Registration')

@section('content')
    <div class="mb-6">
        <p class="text-sm text-gray-500">{{ $match->sport->name }} · {{ $match->scheduled_at?->format('M j, Y g:ia') }}</p>
        <h1 class="text-2xl font-semibold">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }} — Roster</h1>
    </div>

    <div class="grid grid-cols-2 gap-6">
        @foreach (['home' => ['team' => $match->homeTeam, 'players' => $homePlayers], 'away' => ['team' => $match->awayTeam, 'players' => $awayPlayers]] as $side => $config)
            <div class="bg-white rounded-lg border border-gray-200 p-5">
                <h2 class="font-medium mb-4">{{ $config['team']->name }}</h2>

                <table class="w-full text-sm mb-4">
                    <thead class="text-left text-gray-500">
                        <tr>
                            <th class="py-1 font-medium">ID Number</th>
                            <th class="py-1 font-medium">Name</th>
                            <th class="py-1 font-medium">Photo</th>
                            <th class="py-1 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($config['players'] as $player)
                            <tr>
                                <td class="py-2">{{ $player->id_number }}</td>
                                <td class="py-2">{{ $player->full_name }}</td>
                                <td class="py-2">
                                    @if ($player->photo_url)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($player->photo_url) }}" class="h-8 w-8 rounded-full object-cover">
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="py-2 text-right">
                                    <form method="POST" action="{{ route('admin.matches.players.destroy', [$match, $player]) }}"
                                          onsubmit="return confirm('Remove {{ $player->full_name }} from the roster?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-400">No players added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <form method="POST" action="{{ route('admin.matches.players.store', $match) }}" enctype="multipart/form-data" class="border-t border-gray-100 pt-4 space-y-2">
                    @csrf
                    <input type="hidden" name="side" value="{{ $side }}">
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="id_number" placeholder="ID Number" class="rounded border-gray-300 text-sm">
                        <input type="text" name="full_name" placeholder="Name" required class="rounded border-gray-300 text-sm">
                    </div>
                    <input type="file" name="photo" accept="image/*" class="w-full text-sm">
                    <button type="submit" class="w-full rounded bg-gray-100 text-gray-700 py-1.5 text-sm font-medium hover:bg-gray-200">
                        + Add player
                    </button>
                </form>
            </div>
        @endforeach
    </div>

    <div class="mt-6 flex justify-end">
        <a href="{{ route('admin.matches.index') }}" class="rounded bg-gray-900 text-white px-5 py-2.5 text-sm font-medium hover:bg-gray-800">
            Done
        </a>
    </div>
@endsection
