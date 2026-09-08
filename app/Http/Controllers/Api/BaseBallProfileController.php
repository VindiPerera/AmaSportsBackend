<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Player\StoreBaseBallProfileRequest;
use App\Http\Resources\BaseBallProfileResource;
use App\Models\BaseBallProfile;
use App\Models\Player;
use App\Models\PlayerSport;
use App\Models\PlayerTeam;
use App\Models\Sport;
use App\Traits\ApiResponse;
use App\Traits\HasSportLogos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaseBallProfileController extends Controller
{
    use ApiResponse;
    use HasSportLogos;

    public function show(Request $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $sport = Sport::where('slug', Sport::BASE_BALL_SLUG)->first();

        $profile = $player->baseBallProfile()->with(['careerStats', 'recentMatches'])->first();

        if (! $profile) {
            $profile = new BaseBallProfile(['player_id' => $player->id]);
            $profile->setRelation('careerStats', collect());
            $profile->setRelation('recentMatches', collect());
        }

        if ($sport) {
            $this->attachSportLogos($player, $sport, $profile);
        }
        $profile->team_names = $player->fillEmptyOverview($profile, $profile->team_names ?? $this->teamNames($player));

        return $this->success(new BaseBallProfileResource($profile), 'Base Ball profile retrieved successfully.');
    }

    /**
     * PUT /player/base-ball-profile — the only place a `player_sports` row
     * for Base Ball gets created (see Phase 2 fix A3): nothing is attached
     * to the player's profile until this succeeds.
     */
    public function update(StoreBaseBallProfileRequest $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $sport = Sport::where('slug', Sport::BASE_BALL_SLUG)->firstOrFail();

        $profile = DB::transaction(function () use ($request, $player, $sport) {
            $profile = BaseBallProfile::updateOrCreate(
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
        $this->attachSportLogos($player, $sport, $profile);

        return $this->success(new BaseBallProfileResource($profile), 'Base Ball profile saved successfully.');
    }

    /**
     * @return list<string>
     */
    private function teamNames(Player $player): array
    {
        $sport = Sport::where('slug', Sport::BASE_BALL_SLUG)->first();

        if (! $sport) {
            return [];
        }

        return PlayerTeam::where('player_id', $player->id)->where('sport_id', $sport->id)->pluck('team_name')->all();
    }
}
