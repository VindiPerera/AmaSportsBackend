<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MatchPlayerRequest;
use App\Models\GameMatch;
use App\Models\MatchPlayer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MatchPlayerController extends Controller
{
    public function index(GameMatch $match): View
    {
        $match->load(['sport', 'homeTeam', 'awayTeam']);

        return view('admin.matches.players', [
            'match' => $match,
            'homePlayers' => $match->homePlayers()->get(),
            'awayPlayers' => $match->awayPlayers()->get(),
        ]);
    }

    public function store(MatchPlayerRequest $request, GameMatch $match): RedirectResponse
    {
        $data = $request->validated();

        $nextSortOrder = (int) $match->matchPlayers()->where('side', $data['side'])->max('sort_order') + 1;

        $player = MatchPlayer::create([
            'match_id' => $match->id,
            'side' => $data['side'],
            'id_number' => $data['id_number'] ?? null,
            'full_name' => $data['full_name'],
            'sort_order' => $nextSortOrder,
        ]);

        if ($request->hasFile('photo')) {
            $player->update([
                'photo_url' => $request->file('photo')->store("match-players/{$player->id}", 'public'),
            ]);
        }

        return back()->with('success', "Added {$player->full_name} to the roster.");
    }

    public function update(MatchPlayerRequest $request, GameMatch $match, MatchPlayer $matchPlayer): RedirectResponse
    {
        abort_unless($matchPlayer->match_id === $match->id, 404);

        $data = $request->validated();

        $matchPlayer->id_number = $data['id_number'] ?? null;
        $matchPlayer->full_name = $data['full_name'];

        if ($request->hasFile('photo')) {
            if ($matchPlayer->photo_url) {
                Storage::disk('public')->delete($matchPlayer->photo_url);
            }
            $matchPlayer->photo_url = $request->file('photo')->store("match-players/{$matchPlayer->id}", 'public');
        }

        $matchPlayer->save();

        return back()->with('success', "Updated {$matchPlayer->full_name}.");
    }

    public function destroy(GameMatch $match, MatchPlayer $matchPlayer): RedirectResponse
    {
        abort_unless($matchPlayer->match_id === $match->id, 404);

        if ($matchPlayer->photo_url) {
            Storage::disk('public')->delete($matchPlayer->photo_url);
        }

        $matchPlayer->delete();

        return back()->with('success', 'Player removed from the roster.');
    }
}
