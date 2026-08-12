<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CricketProfileResource;
use App\Models\CricketProfile;
use App\Models\Player;
use App\Models\PlayerTeam;
use App\Models\Sport;
use App\Services\CricketAnalysisService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Player Search (new — no prior search capability existed). Cricket-only for
 * now: it's the only sport with a real Analysis screen / aggregated stats
 * today, matching the "coming soon" treatment every other sport already gets
 * elsewhere in this app. Both endpoints are read-only discovery, not a write
 * or the Analysis tab itself, so — unlike those — they're deliberately NOT
 * gated behind `subscription.active`.
 */
class PlayerSearchController extends Controller
{
    use ApiResponse;

    /**
     * GET /players/search?q= — name search across players with a Cricket
     * profile. Reuses CricketAnalysisService (already builds overview +
     * recent_form) instead of duplicating that aggregation math per result.
     */
    public function index(Request $request, CricketAnalysisService $service): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 2) {
            return $this->success([], 'Enter at least 2 characters to search.');
        }

        $me = Player::firstOrCreate(['user_id' => $request->user()->id]);

        $players = Player::query()
            ->where('id', '!=', $me->id)
            ->where('full_name', 'like', '%'.$query.'%')
            ->whereHas('cricketProfile')
            ->limit(20)
            ->get();

        $sport = Sport::where('slug', Sport::CRICKET_SLUG)->first();

        $results = $players->map(function (Player $player) use ($service, $sport) {
            $analysis = $service->build($player, null);
            $team = $sport
                ? PlayerTeam::where('player_id', $player->id)->where('sport_id', $sport->id)->value('team_name')
                : null;

            return [
                'player_id' => $player->id,
                'full_name' => $player->full_name,
                'team' => $team,
                'sport' => 'Cricket',
                'overview' => $analysis['overview'],
                // Last 5, most-recent-first — recent_form itself is oldest→newest.
                'recent_form' => array_slice(array_reverse($analysis['recent_form']), 0, 5),
            ];
        });

        return $this->success($results->values()->all(), 'Search results retrieved successfully.');
    }

    /**
     * GET /players/{player}/cricket-profile — read-only, for any player (not
     * just the authenticated one). Mirrors CricketProfileController::show()
     * exactly so the mobile app can feed the response into the same
     * CricketPlayerDetailView it already uses for the player's own profile.
     */
    public function cricketProfile(Player $player): JsonResponse
    {
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

        $sport = Sport::where('slug', Sport::CRICKET_SLUG)->first();
        $profile->team_names = $sport
            ? PlayerTeam::where('player_id', $player->id)->where('sport_id', $sport->id)->pluck('team_name')->all()
            : [];

        return $this->success([
            'player' => [
                'id' => $player->id,
                'full_name' => $player->full_name,
                'country' => $player->country,
                'photo_url' => $player->photo_url ? Storage::disk('public')->url($player->photo_url) : null,
                'cover_photo_url' => $player->cover_photo_url ? Storage::disk('public')->url($player->cover_photo_url) : null,
            ],
            'cricket_profile' => new CricketProfileResource($profile),
        ], 'Player profile retrieved successfully.');
    }
}
