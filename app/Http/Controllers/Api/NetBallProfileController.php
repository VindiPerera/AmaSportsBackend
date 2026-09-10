<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Player\StoreNetBallProfileRequest;
use App\Http\Resources\NetBallProfileResource;
use App\Models\NetBallProfile;
use App\Models\Player;
use App\Models\PlayerSport;
use App\Models\PlayerTeam;
use App\Models\Sport;
use App\Traits\ApiResponse;
use App\Traits\HasSportLogos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NetBallProfileController extends Controller
{
    use ApiResponse;
    use HasSportLogos;

    public function show(Request $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $sport = Sport::where('slug', Sport::NETBALL_SLUG)->first();

        $profile = $player->netBallProfile()->with(['careerStats', 'recentMatches'])->first();

        if (! $profile) {
            $profile = new NetBallProfile(['player_id' => $player->id]);
            $profile->setRelation('careerStats', collect());
            $profile->setRelation('recentMatches', collect());
        }

        if ($sport) {
            $this->attachSportLogos($player, $sport, $profile);
        }
        $profile->team_names = $player->fillEmptyOverview($profile, $profile->team_names ?? $this->teamNames($player));

        return $this->success(new NetBallProfileResource($profile), 'Net Ball profile retrieved successfully.');
    }

    /**
     * PUT /player/net-ball-profile — the only place a `player_sports` row
     * for Net Ball gets created (see Phase 2 fix A3).
     */
    public function update(StoreNetBallProfileRequest $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $sport = Sport::where('slug', Sport::NETBALL_SLUG)->firstOrFail();

        $profile = DB::transaction(function () use ($request, $player, $sport) {
            $profile = NetBallProfile::updateOrCreate(
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

        return $this->success(new NetBallProfileResource($profile), 'Net Ball profile saved successfully.');
    }

    /**
     * @return list<string>
     */
    private function teamNames(Player $player): array
    {
        $sport = Sport::where('slug', Sport::NETBALL_SLUG)->first();

        if (! $sport) {
            return [];
        }

        return PlayerTeam::where('player_id', $player->id)->where('sport_id', $sport->id)->pluck('team_name')->all();
    }
}
