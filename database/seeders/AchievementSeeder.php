<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Sport;
use Illuminate\Database\Seeder;

/**
 * Starter cricket badges — several tiers per metric (bronze/silver/gold-style
 * progression) rather than one badge each, so unlocking feels like a real
 * ladder. Admins can edit/add more via /admin/achievements.
 */
class AchievementSeeder extends Seeder
{
    private const ACHIEVEMENTS = [
        // Matches played
        ['title' => 'Rookie Debut', 'description' => 'Log your first match.', 'metric_key' => 'cricket_matches', 'threshold' => 1, 'icon' => 'flag', 'color' => '#64748B'],
        ['title' => 'Seasoned Player', 'description' => 'Log 10 matches.', 'metric_key' => 'cricket_matches', 'threshold' => 10, 'icon' => 'star', 'color' => '#F97316'],
        ['title' => 'Veteran', 'description' => 'Log 50 matches.', 'metric_key' => 'cricket_matches', 'threshold' => 50, 'icon' => 'shield-checkmark', 'color' => '#0EA5E9'],

        // Half-centuries
        ['title' => 'Fifty Club', 'description' => 'Score your first half-century (50+ runs in an innings).', 'metric_key' => 'cricket_fifties', 'threshold' => 1, 'icon' => 'ribbon', 'color' => '#3B82F6'],
        ['title' => 'Fifty Specialist', 'description' => 'Score 10 half-centuries.', 'metric_key' => 'cricket_fifties', 'threshold' => 10, 'icon' => 'ribbon', 'color' => '#1D4ED8'],

        // Centuries
        ['title' => 'Century Club', 'description' => 'Score your first century (100+ runs in an innings).', 'metric_key' => 'cricket_centuries', 'threshold' => 1, 'icon' => 'trophy', 'color' => '#F59E0B'],
        ['title' => 'Century Machine', 'description' => 'Score 5 centuries.', 'metric_key' => 'cricket_centuries', 'threshold' => 5, 'icon' => 'trophy', 'color' => '#B45309'],

        // Career runs
        ['title' => 'Century Run-Scorer', 'description' => 'Reach 100 career runs.', 'metric_key' => 'cricket_runs', 'threshold' => 100, 'icon' => 'medal', 'color' => '#10B981'],
        ['title' => '500 Club', 'description' => 'Reach 500 career runs.', 'metric_key' => 'cricket_runs', 'threshold' => 500, 'icon' => 'medal', 'color' => '#059669'],
        ['title' => 'Run Machine', 'description' => 'Reach 1,000 career runs.', 'metric_key' => 'cricket_runs', 'threshold' => 1000, 'icon' => 'flame', 'color' => '#DC2626'],

        // Career wickets
        ['title' => 'Wicket Taker', 'description' => 'Reach 25 career wickets.', 'metric_key' => 'cricket_wickets', 'threshold' => 25, 'icon' => 'flash', 'color' => '#8B5CF6'],
        ['title' => 'Century of Wickets', 'description' => 'Reach 100 career wickets.', 'metric_key' => 'cricket_wickets', 'threshold' => 100, 'icon' => 'shield-checkmark', 'color' => '#EF4444'],

        // Five-wicket hauls
        ['title' => 'Five-Wicket Haul', 'description' => 'Take 5 or more wickets in a single innings.', 'metric_key' => 'cricket_five_wicket_hauls', 'threshold' => 1, 'icon' => 'flash', 'color' => '#7C3AED'],
        ['title' => 'Bowling Menace', 'description' => 'Take 5 or more wickets in an innings, 5 times.', 'metric_key' => 'cricket_five_wicket_hauls', 'threshold' => 5, 'icon' => 'flame', 'color' => '#B91C1C'],
    ];

    public function run(): void
    {
        $sportId = Sport::where('slug', Sport::CRICKET_SLUG)->value('id');
        if (! $sportId) {
            return;
        }

        foreach (self::ACHIEVEMENTS as $index => $data) {
            Achievement::updateOrCreate(
                ['metric_key' => $data['metric_key'], 'threshold' => $data['threshold']],
                [...$data, 'sport_id' => $sportId, 'is_active' => true, 'sort_order' => $index]
            );
        }
    }
}
