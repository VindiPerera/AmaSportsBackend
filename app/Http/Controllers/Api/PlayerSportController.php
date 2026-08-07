<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PlayerSport;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlayerSportController extends Controller
{
    use ApiResponse;

    /**
     * GET /player/sports — every sport this player has added, for the
     * Player Profile "My Sports" hub. A player can add more than one sport.
     */
    public function index(Request $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        $sports = $player->playerSports()
            ->with('sport')
            ->get()
            ->map(fn (PlayerSport $playerSport) => [
                'id' => $playerSport->id,
                'status' => $playerSport->status,
                'sport' => [
                    'id' => $playerSport->sport->id,
                    'name' => $playerSport->sport->name,
                    'slug' => $playerSport->sport->slug,
                    'has_full_form' => $playerSport->sport->has_full_form,
                ],
            ]);

        return $this->success($sports, 'Player sports retrieved successfully.');
    }

    // Deliberately no store()/add endpoint here — a `player_sports` row is
    // only ever created by a sport profile's own submit endpoint (e.g.
    // CricketProfileController::update), never just by picking a sport in
    // the UI. This is what stops an unfinished form from polluting the
    // player's sport list (see Phase 2 fix A3).
}
