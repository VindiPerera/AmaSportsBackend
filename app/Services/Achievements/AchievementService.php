<?php

namespace App\Services\Achievements;

use App\Models\Achievement;
use App\Models\Player;
use App\Models\PlayerAchievement;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Checks a player's current stats against every active Achievement and
 * unlocks (creates a PlayerAchievement row for) any newly-crossed threshold.
 * Run after a sport profile save (see CricketProfileController::update)
 * and whenever the player's achievements list loads (so a threshold crossed
 * by data entered before this feature existed still gets picked up the
 * first time they check) — both call evaluateForPlayer, so neither path
 * needs its own duplicate-detection logic beyond the unique(player_id,
 * achievement_id) constraint this already relies on.
 */
class AchievementService
{
    /** @var MetricEvaluator[] */
    private array $evaluators;

    public function __construct(CricketMetricEvaluator $cricketEvaluator)
    {
        // Register a new sport's evaluator here once one exists — nothing
        // else in this class (or the admin CRUD) needs to change.
        $this->evaluators = [$cricketEvaluator];
    }

    /**
     * @return Collection<int, PlayerAchievement> newly-unlocked rows (empty
     *  if nothing new crossed its threshold this time).
     */
    public function evaluateForPlayer(Player $player): Collection
    {
        $alreadyUnlockedIds = $player->playerAchievements()->pluck('achievement_id')->all();

        $candidates = Achievement::query()
            ->where('is_active', true)
            ->whereNotIn('id', $alreadyUnlockedIds)
            ->get();

        $newlyUnlocked = collect();

        foreach ($candidates as $achievement) {
            $evaluator = $this->evaluatorFor($achievement->metric_key);
            if (! $evaluator) {
                continue;
            }

            $value = $evaluator->currentValue($player, $achievement->metric_key);
            if ($value === null || $value < $achievement->threshold) {
                continue;
            }

            $newlyUnlocked->push(PlayerAchievement::create([
                'player_id' => $player->id,
                'achievement_id' => $achievement->id,
                'unlocked_at' => Carbon::now(),
                'achieved_value' => $value,
            ]));
        }

        return $newlyUnlocked;
    }

    private function evaluatorFor(string $metricKey): ?MetricEvaluator
    {
        foreach ($this->evaluators as $evaluator) {
            if ($evaluator->supports($metricKey)) {
                return $evaluator;
            }
        }

        return null;
    }

    /** All evaluators' metric options, keyed by metric key — for the admin
     * CRUD's dropdown (see AchievementController). */
    public function allAvailableMetrics(): array
    {
        $metrics = [];
        foreach ($this->evaluators as $evaluator) {
            $metrics += $evaluator->availableMetrics();
        }

        return $metrics;
    }
}
