<?php

namespace App\Services\Achievements;

use App\Models\Player;

/**
 * One sport's worth of achievement metrics. AchievementService holds a list
 * of these and asks each "do you handle this metric key?" — adding a new
 * sport's achievements later is just writing one more evaluator and
 * registering it in AchievementService's constructor, with no changes to
 * the achievements schema, the admin CRUD, or the evaluation loop itself.
 */
interface MetricEvaluator
{
    /** True if this evaluator knows how to compute `$metricKey` (e.g. a
     * CricketMetricEvaluator recognizes "cricket_runs", "cricket_wickets"). */
    public function supports(string $metricKey): bool;

    /** The player's current career value for this metric (e.g. total runs
     * scored across every Format/Category/Year row) — null if the player
     * has no profile for this evaluator's sport yet. */
    public function currentValue(Player $player, string $metricKey): ?int;

    /** Metric keys this evaluator supports, for populating the admin CRUD's
     * metric dropdown (see AchievementController) — keeps the admin from
     * picking a key nothing can ever evaluate. */
    public function availableMetrics(): array;
}
