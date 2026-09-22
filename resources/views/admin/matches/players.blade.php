@extends('admin.layouts.app')

@section('title', 'Player Roster Registration')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.matches.index') }}" class="text-xs font-bold text-slate-500 hover:text-brand-red mb-2 inline-block transition-colors">← Back to Matches</a>
        <div class="flex items-center gap-2">
            <x-badge variant="gold" size="sm">{{ $match->sport->name }}</x-badge>
            <span class="text-xs text-slate-400 font-medium">• {{ $match->scheduled_at?->format('M j, Y • g:i A') }}</span>
        </div>
        <h1 class="text-2xl font-black text-brand-charcoal tracking-tight mt-1">
            {{ $match->homeTeam->name }} <span class="text-slate-400 font-normal">vs</span> {{ $match->awayTeam->name }} — Team Lineups
        </h1>
        <p class="text-xs text-slate-500 mt-1">Register verified player rosters and jersey numbers for live matchday scoring</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach (['home' => ['label' => 'Home Roster', 'team' => $match->homeTeam, 'players' => $homePlayers, 'badge' => 'red'], 'away' => ['label' => 'Away Roster', 'team' => $match->awayTeam, 'players' => $awayPlayers, 'badge' => 'gold']] as $side => $config)
            <x-card class="p-6">
                <x-slot:header>
                    <div class="flex items-center justify-between w-full">
                        <h2 class="text-base font-extrabold text-slate-900">{{ $config['team']->name }}</h2>
                        <x-badge :variant="$config['badge']" size="sm">
                            {{ $config['players']->count() }} Players
                        </x-badge>
                    </div>
                </x-slot:header>

                <div class="overflow-x-auto mb-6">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead>
                            <tr class="text-[10px] uppercase font-extrabold text-slate-400 border-b border-slate-100">
                                <th class="py-2.5 px-2">ID / Jersey</th>
                                <th class="py-2.5 px-2">Player Name</th>
                                <th class="py-2.5 px-2">Photo</th>
                                <th class="py-2.5 px-2 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($config['players'] as $player)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-2.5 px-2 font-mono font-bold text-slate-600">{{ $player->id_number ?: '—' }}</td>
                                    <td class="py-2.5 px-2 font-bold text-slate-900">{{ $player->full_name }}</td>
                                    <td class="py-2.5 px-2">
                                        @if ($player->photo_url)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($player->photo_url) }}" class="h-7 w-7 rounded-full object-cover border border-slate-200">
                                        @else
                                            <div class="h-7 w-7 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                                {{ strtoupper(substr($player->full_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-2 text-right">
                                        <form method="POST" action="{{ route('admin.matches.players.destroy', [$match, $player]) }}"
                                              onsubmit="return confirm('Remove {{ $player->full_name }} from the roster?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-700 cursor-pointer">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-xs text-slate-400">No players registered to roster yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Add Player Form --}}
                <form method="POST" action="{{ route('admin.matches.players.store', $match) }}" enctype="multipart/form-data" class="border-t border-slate-100 pt-4 space-y-3">
                    @csrf
                    <input type="hidden" name="side" value="{{ $side }}">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-700">+ Add Squad Member</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <input type="text" name="id_number" placeholder="ID / Jersey No" class="rounded-xl bg-slate-50 border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-brand-red outline-none">
                        <input type="text" name="full_name" placeholder="Full Name *" required class="rounded-xl bg-slate-50 border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-brand-red outline-none">
                    </div>
                    <div>
                        <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700">
                    </div>
                    <x-button type="submit" variant="dark" size="sm" class="w-full justify-center">
                        + Add to Squad
                    </x-button>
                </form>
            </x-card>
        @endforeach
    </div>

    <div class="mt-8 flex justify-end">
        <x-button href="{{ route('admin.matches.index') }}" variant="primary" size="md">
            Finish &amp; Return to Matches Directory →
        </x-button>
    </div>
@endsection
