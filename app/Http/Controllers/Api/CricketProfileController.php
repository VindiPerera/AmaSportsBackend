<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Player\StoreCricketProfileRequest;
use App\Http\Resources\CricketProfileResource;
use App\Models\CricketProfile;
use App\Models\Player;
use App\Models\PlayerSport;
use App\Models\PlayerTeam;
use App\Models\PlayerTeamLogo;
use App\Models\Sport;
use App\Services\Achievements\AchievementService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CricketProfileController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly AchievementService $achievements)
    {
    }

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

        $profile->team_names = $player->fillEmptyOverview($profile, $this->teamNames($player));
        $profile->team_logos = $this->teamLogos($player);

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
        $profile->team_logos = $this->teamLogos($player);

        // Cricket-only for now (see CricketMetricEvaluator) — the player's
        // new career totals may have just crossed an Achievement threshold.
        $this->achievements->evaluateForPlayer($player);

        return $this->success(new CricketProfileResource($profile), 'Cricket profile saved successfully.');
    }

    /**
     * POST /player/cricket-profile/college-logo — uploads (or replaces) the
     * College/University logo. Multipart, immediate — same pattern as the
     * player's own avatar/cover photo (see PlayerProfileController) and the
     * team logos (see PlayerTeamLogoController); unlike team logos, this
     * lives directly on `cricket_profiles` since that row is stable
     * (updateOrCreate) rather than deleted-and-recreated on every save.
     */
    public function uploadCollegeLogo(Request $request): JsonResponse
    {
        $request->validate(['logo' => ['required', 'image', 'max:5120']]);

        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $profile = CricketProfile::firstOrNew(['player_id' => $player->id]);

        if ($profile->college_logo_path) {
            Storage::disk('public')->delete($profile->college_logo_path);
        }

        $profile->college_logo_path = $request->file('logo')->store("players/{$player->id}/college", 'public');
        $profile->save();

        return $this->success([
            'college_logo_url' => Storage::disk('public')->url($profile->college_logo_path),
        ], 'College logo uploaded successfully.');
    }

    /**
     * DELETE /player/cricket-profile/college-logo — removes the
     * College/University logo (the college_university name itself is
     * untouched).
     */
    public function removeCollegeLogo(Request $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $profile = CricketProfile::where('player_id', $player->id)->first();

        if ($profile?->college_logo_path) {
            Storage::disk('public')->delete($profile->college_logo_path);
            $profile->update(['college_logo_path' => null]);
        }

        return $this->success(null, 'College logo removed successfully.');
    }

    /**
     * POST /player/cricket-profile/score-sheet — uploads a photo of one
     * match's physical/official scoresheet. Multipart, immediate — same
     * "immediate, own endpoint" pattern as the college logo above and the
     * team logos (see PlayerTeamLogoController), because a Recent Match row
     * doesn't exist yet (and so has nothing to attach a file to) until the
     * whole Cricket profile form is submitted. The returned URL travels back
     * with that match's `score_sheet_url` on the next PUT and is stored
     * as-is; it isn't tied to a player/profile record here.
     */
    public function uploadScoreSheet(Request $request): JsonResponse
    {
        $request->validate(['image' => ['required', 'image', 'max:5120']]);

        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        $path = $request->file('image')->store("players/{$player->id}/cricket/score-sheets", 'public');

        return $this->success([
            'score_sheet_url' => Storage::disk('public')->url($path),
        ], 'Score sheet uploaded successfully.');
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

    /**
     * Logos are managed separately from `teams` above (see PlayerTeamLogo /
     * PlayerTeamLogoController) — keyed by team name so the mobile app can
     * match a logo to whichever of `teams` it belongs to.
     *
     * @return list<array{team_name: string, logo_url: string}>
     */
    private function teamLogos(Player $player): array
    {
        $sport = Sport::where('slug', Sport::CRICKET_SLUG)->first();

        if (! $sport) {
            return [];
        }

        return PlayerTeamLogo::where('player_id', $player->id)
            ->where('sport_id', $sport->id)
            ->get()
            ->map(fn (PlayerTeamLogo $logo) => [
                'team_name' => $logo->team_name,
                'logo_url' => Storage::disk('public')->url($logo->logo_path),
            ])
            ->all();
    }
}
