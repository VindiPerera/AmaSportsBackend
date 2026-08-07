<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Player\StoreAthleticsProfileRequest;
use App\Http\Resources\AthleticsProfileResource;
use App\Models\AthleticsProfile;
use App\Models\Player;
use App\Models\PlayerSport;
use App\Models\PlayerTeam;
use App\Models\Sport;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AthleticsProfileController extends Controller
{
    use ApiResponse;

    public function show(Request $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        $profile = $player->athleticsProfile()
            ->with(['personalBests', 'careerStats', 'recentEvents'])
            ->first();

        if (! $profile) {
            $profile = new AthleticsProfile(['player_id' => $player->id]);
            $profile->setRelation('personalBests', collect());
            $profile->setRelation('careerStats', collect());
            $profile->setRelation('recentEvents', collect());
        }

        $profile->team_names = $this->teamNames($player);

        return $this->success(new AthleticsProfileResource($profile), 'Athletics profile retrieved successfully.');
    }

    /**
     * PUT /player/athletics-profile — the only place a `player_sports` row
     * for Athletics gets created (see Phase 2 fix A3).
     */
    public function update(StoreAthleticsProfileRequest $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $sport = Sport::where('slug', Sport::ATHLETICS_SLUG)->firstOrFail();

        $profile = DB::transaction(function () use ($request, $player, $sport) {
            $profile = AthleticsProfile::updateOrCreate(
                ['player_id' => $player->id],
                $request->safe()->only(['born', 'age', 'height', 'weight', 'college_university'])
            );

            $profile->personalBests()->delete();
            $profile->personalBests()->createMany($request->input('personal_bests', []));

            $profile->careerStats()->delete();
            $profile->careerStats()->createMany($request->input('career_stats', []));

            $profile->recentEvents()->delete();
            $profile->recentEvents()->createMany($request->input('recent_events', []));

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

        $profile->load(['personalBests', 'careerStats', 'recentEvents']);
        $profile->team_names = $this->teamNames($player);

        return $this->success(new AthleticsProfileResource($profile), 'Athletics profile saved successfully.');
    }

    /**
     * @return list<string>
     */
    private function teamNames(Player $player): array
    {
        $sport = Sport::where('slug', Sport::ATHLETICS_SLUG)->first();

        if (! $sport) {
            return [];
        }

        return PlayerTeam::where('player_id', $player->id)->where('sport_id', $sport->id)->pluck('team_name')->all();
    }
}
