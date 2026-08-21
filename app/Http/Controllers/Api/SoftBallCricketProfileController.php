<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Player\StoreSoftBallCricketProfileRequest;
use App\Http\Resources\SoftBallCricketProfileResource;
use App\Models\Player;
use App\Models\PlayerSport;
use App\Models\PlayerTeam;
use App\Models\SoftBallCricketProfile;
use App\Models\Sport;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SoftBallCricketProfileController extends Controller
{
    use ApiResponse;

    public function show(Request $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        $profile = $player->softBallCricketProfile()->with(['battingStats', 'bowlingStats', 'recentMatches'])->first();

        if (! $profile) {
            $profile = new SoftBallCricketProfile(['player_id' => $player->id]);
            $profile->setRelation('battingStats', collect());
            $profile->setRelation('bowlingStats', collect());
            $profile->setRelation('recentMatches', collect());
        }

        $profile->team_names = $this->teamNames($player);

        return $this->success(new SoftBallCricketProfileResource($profile), 'Soft Ball Cricket profile retrieved successfully.');
    }

    /**
     * PUT /player/soft-ball-cricket-profile — the only place a
     * `player_sports` row for Soft Ball Cricket gets created (see Phase 2
     * fix A3): nothing is attached to the player's profile until this
     * succeeds.
     */
    public function update(StoreSoftBallCricketProfileRequest $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $sport = Sport::where('slug', Sport::SOFT_BALL_CRICKET_SLUG)->firstOrFail();

        $profile = DB::transaction(function () use ($request, $player, $sport) {
            $profile = SoftBallCricketProfile::updateOrCreate(
                ['player_id' => $player->id],
                $request->safe()->only([
                    'born', 'age', 'batting_style', 'bowling_style', 'playing_role', 'height', 'college_university',
                ])
            );

            $profile->battingStats()->delete();
            $profile->battingStats()->createMany($request->input('batting', []));

            $profile->bowlingStats()->delete();
            $profile->bowlingStats()->createMany($request->input('bowling', []));

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

        $profile->load(['battingStats', 'bowlingStats', 'recentMatches']);
        $profile->team_names = $this->teamNames($player);

        return $this->success(new SoftBallCricketProfileResource($profile), 'Soft Ball Cricket profile saved successfully.');
    }

    /**
     * @return list<string>
     */
    private function teamNames(Player $player): array
    {
        $sport = Sport::where('slug', Sport::SOFT_BALL_CRICKET_SLUG)->first();

        if (! $sport) {
            return [];
        }

        return PlayerTeam::where('player_id', $player->id)->where('sport_id', $sport->id)->pluck('team_name')->all();
    }
}
