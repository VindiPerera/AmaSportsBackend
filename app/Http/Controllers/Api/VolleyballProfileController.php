<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Player\StoreVolleyballProfileRequest;
use App\Http\Resources\VolleyballProfileResource;
use App\Models\Player;
use App\Models\PlayerSport;
use App\Models\PlayerTeam;
use App\Models\Sport;
use App\Models\VolleyballProfile;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VolleyballProfileController extends Controller
{
    use ApiResponse;

    public function show(Request $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        $profile = $player->volleyballProfile()->with(['careerStats', 'recentMatches'])->first();

        if (! $profile) {
            $profile = new VolleyballProfile(['player_id' => $player->id]);
            $profile->setRelation('careerStats', collect());
            $profile->setRelation('recentMatches', collect());
        }

        $profile->team_names = $this->teamNames($player);

        return $this->success(new VolleyballProfileResource($profile), 'Volleyball profile retrieved successfully.');
    }

    /**
     * PUT /player/volleyball-profile — the only place a `player_sports` row
     * for Volleyball gets created (see Phase 2 fix A3).
     */
    public function update(StoreVolleyballProfileRequest $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $sport = Sport::where('slug', Sport::VOLLEYBALL_SLUG)->firstOrFail();

        $profile = DB::transaction(function () use ($request, $player, $sport) {
            $profile = VolleyballProfile::updateOrCreate(
                ['player_id' => $player->id],
                $request->safe()->only(['born', 'age', 'height', 'dominant_hand', 'player_position', 'college_university'])
            );

            $profile->careerStats()->delete();
            $profile->careerStats()->createMany($request->input('career_stats', []));

            $profile->recentMatches()->delete();
            $profile->recentMatches()->createMany($request->input('recent_matches', []));

            PlayerTeam::where('player_id', $player->id)->where('sport_id', $sport->id)->delete();
            foreach ($request->input('teams', []) as $teamName) {
                PlayerTeam::create(['player_id' => $player->id, 'sport_id' => $sport->id, 'team_name' => $teamName]);
            }

            PlayerSport::updateOrCreate(
                ['player_id' => $player->id, 'sport_id' => $sport->id],
                ['status' => PlayerSport::STATUS_COMPLETED]
            );

            return $profile;
        });

        $profile->load(['careerStats', 'recentMatches']);
        $profile->team_names = $this->teamNames($player);

        return $this->success(new VolleyballProfileResource($profile), 'Volleyball profile saved successfully.');
    }

    /**
     * @return list<string>
     */
    private function teamNames(Player $player): array
    {
        $sport = Sport::where('slug', Sport::VOLLEYBALL_SLUG)->first();

        if (! $sport) {
            return [];
        }

        return PlayerTeam::where('player_id', $player->id)->where('sport_id', $sport->id)->pluck('team_name')->all();
    }
}
