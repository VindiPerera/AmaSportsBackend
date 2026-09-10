<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerAchievementResource;
use App\Models\Player;
use App\Models\PlayerAchievement;
use App\Services\Achievements\AchievementService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlayerAchievementController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly AchievementService $achievements)
    {
    }

    /**
     * GET /player/achievements — re-checks thresholds first (so a milestone
     * from stats entered before this feature existed, or since the last
     * check, still surfaces the moment this loads — see AchievementService),
     * then returns everything split into "pending" (unlocked, not yet
     * posted — shown as a notification the player can act on) and "posted"
     * (already shared — shown on Player Profile and Home).
     */
    public function index(Request $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $this->achievements->evaluateForPlayer($player);

        $all = $player->playerAchievements()
            ->with('achievement')
            ->whereHas('achievement', fn ($q) => $q->where('is_active', true))
            ->orderByDesc('unlocked_at')
            ->get();

        $pending = $all->whereNull('posted_at')->values();
        $posted = $all->whereNotNull('posted_at')->sortByDesc('posted_at')->values();

        return $this->success([
            'pending' => PlayerAchievementResource::collection($pending),
            'posted' => PlayerAchievementResource::collection($posted),
        ], 'Achievements retrieved successfully.');
    }

    /**
     * POST /player/achievements/{playerAchievement}/post — the player's
     * deliberate "share this" action (spec: nothing posts automatically).
     * Idempotent: posting an already-posted achievement just keeps its
     * original posted_at rather than erroring or bumping it.
     */
    public function post(Request $request, PlayerAchievement $playerAchievement): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        if ($playerAchievement->player_id !== $player->id) {
            return $this->error('Achievement not found.', 404);
        }

        if (! $playerAchievement->posted_at) {
            $playerAchievement->update(['posted_at' => now()]);
        }

        return $this->success(
            new PlayerAchievementResource($playerAchievement->fresh('achievement')),
            'Achievement posted successfully.'
        );
    }
}
