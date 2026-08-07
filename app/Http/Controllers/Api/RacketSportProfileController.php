<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Player\StoreRacketSportProfileRequest;
use App\Http\Resources\RacketSportProfileResource;
use App\Models\Player;
use App\Models\PlayerSport;
use App\Models\PlayerTeam;
use App\Models\RacketSportProfile;
use App\Models\Sport;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Shared Tennis / Badminton / Table Tennis endpoints (spec Phase 2 §B3) —
 * every request carries a `sport_id` saying which of the three it's for.
 */
class RacketSportProfileController extends Controller
{
    use ApiResponse;

    private const ALLOWED_SLUGS = [Sport::TENNIS_SLUG, Sport::BADMINTON_SLUG, Sport::TABLE_TENNIS_SLUG];

    /**
     * GET /player/racket-sport-profile?sport_id=<id>
     */
    public function show(Request $request): JsonResponse
    {
        $request->validate(['sport_id' => ['required', 'integer', 'exists:sports,id']]);

        $sport = Sport::findOrFail($request->integer('sport_id'));
        if (! in_array($sport->slug, self::ALLOWED_SLUGS, true)) {
            return $this->error('That sport does not use the racket-sport form.', 422);
        }

        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        $profile = RacketSportProfile::where('player_id', $player->id)
            ->where('sport_id', $sport->id)
            ->with(['careerStats', 'recentMatches'])
            ->first();

        if (! $profile) {
            $profile = new RacketSportProfile(['player_id' => $player->id, 'sport_id' => $sport->id]);
            $profile->setRelation('careerStats', collect());
            $profile->setRelation('recentMatches', collect());
        }

        $profile->team_names = $this->teamNames($player, $sport);

        return $this->success(new RacketSportProfileResource($profile), 'Racket sport profile retrieved successfully.');
    }

    /**
     * PUT /player/racket-sport-profile — the only place a `player_sports`
     * row for this sport gets created (see Phase 2 fix A3).
     */
    public function update(StoreRacketSportProfileRequest $request): JsonResponse
    {
        $sport = Sport::findOrFail($request->integer('sport_id'));
        if (! in_array($sport->slug, self::ALLOWED_SLUGS, true)) {
            return $this->error('That sport does not use the racket-sport form.', 422);
        }

        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        $profile = DB::transaction(function () use ($request, $player, $sport) {
            $profile = RacketSportProfile::updateOrCreate(
                ['player_id' => $player->id, 'sport_id' => $sport->id],
                $request->safe()->only(['born', 'age', 'height', 'dominant_hand', 'weight', 'current_ranking', 'college_university'])
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
        $profile->team_names = $this->teamNames($player, $sport);

        return $this->success(new RacketSportProfileResource($profile), 'Racket sport profile saved successfully.');
    }

    /**
     * @return list<string>
     */
    private function teamNames(Player $player, Sport $sport): array
    {
        return PlayerTeam::where('player_id', $player->id)->where('sport_id', $sport->id)->pluck('team_name')->all();
    }
}
