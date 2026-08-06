<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Player\AddPlayerSportRequest;
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

    /**
     * POST /player/sports — add a sport to this player. Idempotent: adding
     * an already-added sport just returns the existing row.
     */
    public function store(AddPlayerSportRequest $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        $playerSport = PlayerSport::firstOrCreate(
            ['player_id' => $player->id, 'sport_id' => $request->integer('sport_id')],
            ['status' => PlayerSport::STATUS_PLACEHOLDER]
        );

        $playerSport->load('sport');

        return $this->success([
            'id' => $playerSport->id,
            'status' => $playerSport->status,
            'sport' => [
                'id' => $playerSport->sport->id,
                'name' => $playerSport->sport->name,
                'slug' => $playerSport->sport->slug,
                'has_full_form' => $playerSport->sport->has_full_form,
            ],
        ], 'Sport added.', 201);
    }
}
