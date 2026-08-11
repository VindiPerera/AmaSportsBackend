<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Player\StoreCricketProfileRequest;
use App\Http\Resources\CricketProfileResource;
use App\Models\CricketProfile;
use App\Models\Player;
use App\Models\PlayerSport;
use App\Models\PlayerTeam;
use App\Models\Sport;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CricketProfileController extends Controller
{
    use ApiResponse;

    /**
     * GET /player/cricket-profile — full nested read (overview + all three
     * repeatable tables). Returns an empty-shaped profile if the player
     * hasn't submitted the Cricket form yet, so the mobile form can render
     * either way without a special "not found" branch.
     */
    public function show(Request $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        $profile = $player->cricketProfile()
            ->with(['battingStats', 'bowlingStats', 'recentMatches', 'dropCatches'])
            ->first();

        if (! $profile) {
            $profile = new CricketProfile(['player_id' => $player->id]);
            $profile->setRelation('battingStats', collect());
            $profile->setRelation('bowlingStats', collect());
            $profile->setRelation('recentMatches', collect());
            $profile->setRelation('dropCatches', collect());
        }

        $profile->team_names = $this->teamNames($player);

        return $this->success(new CricketProfileResource($profile), 'Cricket profile retrieved successfully.');
    }

    /**
     * PUT /player/cricket-profile — upserts the overview fields and
     * replaces all repeatable-table rows in one transactional request
     * (spec 6.2: "Submit button ... saves everything in one request").
     */
    public function update(StoreCricketProfileRequest $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $sport = Sport::where('slug', Sport::CRICKET_SLUG)->firstOrFail();

        $profile = DB::transaction(function () use ($request, $player, $sport) {
            $profile = CricketProfile::updateOrCreate(
                ['player_id' => $player->id],
                $request->safe()->only([
                    'born', 'age', 'batting_style', 'bowling_style',
                    'playing_role', 'height', 'college_university',
                    'pitching_line_breakdown', 'ball_type_breakdown',
                ])
            );

            $profile->battingStats()->delete();
            $profile->battingStats()->createMany($request->input('batting', []));

            $profile->bowlingStats()->delete();
            $profile->bowlingStats()->createMany($request->input('bowling', []));

            $profile->recentMatches()->delete();
            $profile->recentMatches()->createMany($request->input('recent_matches', []));

            $profile->dropCatches()->delete();
            $profile->dropCatches()->createMany($request->input('drop_catches', []));

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

        $profile->load(['battingStats', 'bowlingStats', 'recentMatches', 'dropCatches']);
        $profile->team_names = $this->teamNames($player);

        return $this->success(new CricketProfileResource($profile), 'Cricket profile saved successfully.');
    }

    /**
     * @return list<string>
     */
    private function teamNames(Player $player): array
    {
        $sport = Sport::where('slug', Sport::CRICKET_SLUG)->first();

        if (! $sport) {
            return [];
        }

        return PlayerTeam::where('player_id', $player->id)
            ->where('sport_id', $sport->id)
            ->pluck('team_name')
            ->all();
    }
}
