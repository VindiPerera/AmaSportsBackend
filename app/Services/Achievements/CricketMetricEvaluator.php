<?php

namespace App\Services\Achievements;

use App\Models\Player;

/**
 * Cricket's career-total metrics — summed the same safe way
 * CricketAnalysisService does (plain counts summed across every
 * Format/Age/Category/Year row; see that class's docblock for why rate
 * fields like average/SR can't be summed the same way — irrelevant here
 * since every metric below is a plain count).
 */
class CricketMetricEvaluator implements MetricEvaluator
{
    private const METRICS = [
        'cricket_runs' => 'Total Runs Scored',
        'cricket_wickets' => 'Total Wickets Taken',
        'cricket_centuries' => 'Centuries (100s)',
        'cricket_fifties' => 'Half-Centuries (50s)',
        'cricket_five_wicket_hauls' => 'Five-Wicket Hauls',
        'cricket_matches' => 'Matches Played',
    ];

    public function supports(string $metricKey): bool
    {
        return array_key_exists($metricKey, self::METRICS);
    }

    public function availableMetrics(): array
    {
        return self::METRICS;
    }

    public function currentValue(Player $player, string $metricKey): ?int
    {
        $profile = $player->cricketProfile;
        if (! $profile) {
            return null;
        }

        return match ($metricKey) {
            'cricket_runs' => (int) $profile->battingStats()->sum('runs'),
            'cricket_centuries' => (int) $profile->battingStats()->sum('hundreds'),
            'cricket_fifties' => (int) $profile->battingStats()->sum('fifties'),
            'cricket_wickets' => (int) $profile->bowlingStats()->sum('wickets'),
            'cricket_five_wicket_hauls' => (int) $profile->bowlingStats()->sum('five_w'),
            // The Recent Matches log (one row per match, see
            // CricketMatchEntryCard on the mobile app) is the authoritative
            // match count — safer than summing the career rows' own
            // "matches" fields, which double-counts if a player's data
            // predates that unified per-match entry flow.
            'cricket_matches' => (int) $profile->recentMatches()->count(),
            default => null,
        };
    }
}
