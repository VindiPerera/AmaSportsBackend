<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Player\StoreHockeyProfileRequest;
use App\Http\Resources\HockeyProfileResource;
use App\Models\HockeyProfile;
use App\Models\Player;
use App\Models\PlayerSport;
use App\Models\PlayerTeam;
use App\Models\Sport;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HockeyProfileController extends Controller
{
    use ApiResponse;

    /**
     * GET /player/hockey-profile — full nested read (overview + both
     * repeatable tables). Empty-shaped if not submitted yet.
     */
    public function show(Request $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        $profile = $player->hockeyProfile()
            ->with(['careerStats', 'recentMatches'])
            ->first();

        if (! $profile) {
            $profile = new HockeyProfile(['player_id' => $player->id]);
            $profile->setRelation('careerStats', collect());
            $profile->setRelation('recentMatches', collect());
        }

        $profile->team_names = $this->teamNames($player);

        return $this->success(new HockeyProfileResource($profile), 'Hockey profile retrieved successfully.');
    }

    /**
     * PUT /player/hockey-profile — upserts the overview fields and
     * replaces all repeatable-table rows in one transactional request.
     */
    public function update(StoreHockeyProfileRequest $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $sport = Sport::where('slug', Sport::HOCKEY_SLUG)->firstOrFail();

        $profile = DB::transaction(function () use ($request, $player, $sport) {
            $profile = HockeyProfile::updateOrCreate(
                ['player_id' => $player->id],
                $request->safe()->only([
                    'born', 'age', 'height', 'dominant_hand',
                    'player_position', 'college_university',
                ])
            );

            $profile->careerStats()->delete();
            $profile->careerStats()->createMany($request->input('career_stats', []));

            $profile->recentMatches()->delete();
            $profile->recentMatches()->createMany($request->input('recent_matches', []));

            PlayerTeam::where('player_id', $player->id)->where('sport_id', $sport->id)->delete();
            foreach ($request->input('teams', []) as $teamName) {
                PlayerTeam::create([
                    'player_id' => $player->id,
                    'sport_id' => $sport->id,
                    'team_name' => $teamName,
                ]);
            }

            PlayerSport::updateOrCreate(
                ['player_id' => $player->id, 'sport_id' => $sport->id],
                ['status' => PlayerSport::STATUS_COMPLETED]
            );

            return $profile;
        });

        $profile->load(['careerStats', 'recentMatches']);
        $profile->team_names = $this->teamNames($player);

        return $this->success(new HockeyProfileResource($profile), 'Hockey profile saved successfully.');
    }

    /**
     * @return list<string>
     */
    private function teamNames(Player $player): array
    {
        $sport = Sport::where('slug', Sport::HOCKEY_SLUG)->first();

        if (! $sport) {
            return [];
        }

        return PlayerTeam::where('player_id', $player->id)
            ->where('sport_id', $sport->id)
            ->pluck('team_name')
            ->all();
    }
}
