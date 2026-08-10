@extends('admin.layouts.app')

@section('title', 'Player Roster Registration')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.matches.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-500 mb-2 inline-block">← Back to Matches</a>
        <div class="flex items-center gap-2">
            <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-blue-50 text-blue-700 uppercase tracking-wide">
                {{ $match->sport->name }}
            </span>
            <span class="text-xs text-slate-500 font-medium">• {{ $match->scheduled_at?->format('M j, Y • g:ia') }}</span>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-1">
            {{ $match->homeTeam->name }} <span class="text-slate-400 font-normal">vs</span> {{ $match->awayTeam->name }} — Team Rosters
        </h1>
        <p class="text-sm text-slate-500 mt-0.5">Register player lineups and official squad numbers for live match scoring</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach (['home' => ['label' => 'Home Roster', 'team' => $match->homeTeam, 'players' => $homePlayers], 'away' => ['label' => 'Away Roster', 'team' => $match->awayTeam, 'players' => $awayPlayers]] as $side => $config)
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-soft">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <h2 class="text-base font-extrabold text-slate-900">{{ $config['team']->name }}</h2>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                        {{ $config['players']->count() }} Players
                    </span>
                </div>

                <div class="overflow-x-auto mb-6">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs uppercase font-bold text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="py-2 px-1">ID Number</th>
                                <th class="py-2 px-1">Player Name</th>
                                <th class="py-2 px-1">Photo</th>
                                <th class="py-2 px-1 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($config['players'] as $player)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-2.5 px-1 font-mono text-xs font-bold text-slate-600">{{ $player->id_number }}</td>
                                    <td class="py-2.5 px-1 font-bold text-slate-900">{{ $player->full_name }}</td>
                                    <td class="py-2.5 px-1">
                                        @if ($player->photo_url)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($player->photo_url) }}" class="h-8 w-8 rounded-full object-cover border border-slate-200">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-xs font-bold text-slate-500">
                                                {{ strtoupper(substr($player->full_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-1 text-right">
                                        <form method="POST" action="{{ route('admin.matches.players.destroy', [$match, $player]) }}"
                                              onsubmit="return confirm('Remove {{ $player->full_name }} from the roster?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-500 hover:underline">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-xs text-slate-400 font-medium">No players registered to roster yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Add Player Form --}}
                <form method="POST" action="{{ route('admin.matches.players.store', $match) }}" enctype="multipart/form-data" class="border-t border-slate-100 pt-4 space-y-3">
                    @csrf
                    <input type="hidden" name="side" value="{{ $side }}">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-700">+ Add Player to Squad</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <input type="text" name="id_number" placeholder="ID / Jersey No" class="rounded-xl bg-slate-50 border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 outline-none">
                        <input type="text" name="full_name" placeholder="Full Name *" required class="rounded-xl bg-slate-50 border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700">
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-slate-900 hover:bg-slate-800 text-white py-2 text-xs font-bold shadow transition-all">
                        + Add Squad Member
                    </button>
                </form>
            </div>
        @endforeach
    </div>

    <div class="mt-8 flex justify-end">
        <a href="{{ route('admin.matches.index') }}" class="rounded-xl bg-blue-600 hover:bg-blue-500 text-white px-8 py-3 text-sm font-extrabold shadow-lg shadow-blue-600/30 transition-all">
            Finish & Return to Matches Directory →
        </a>
    </div>
@endsection
