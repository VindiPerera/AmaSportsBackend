<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PlayerTeam;
use App\Models\Sport;
use App\Services\CricketAnalysisService;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Public player analysis page — no login required (see routes/admin.php).
 * Cricket-only for now, matching Api\PlayerSearchController::cricketProfile
 * and the mobile Analysis tab, which are also cricket-only today.
 */
class PlayerAnalysisController extends Controller
{
    public function show(Player $player, CricketAnalysisService $analysisService): View
    {
        $analysis = $analysisService->build($player, null);

        $sport = Sport::where('slug', Sport::CRICKET_SLUG)->first();
        $team = $sport
            ? PlayerTeam::where('player_id', $player->id)->where('sport_id', $sport->id)->value('team_name')
            : null;

        return view('admin.players.show', [
            'player' => $player,
            'photoUrl' => $player->photo_url ? Storage::disk('public')->url($player->photo_url) : null,
            'team' => $team,
            'analysis' => $analysis,
        ]);
    }
}
