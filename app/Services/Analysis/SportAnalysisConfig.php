<?php

namespace App\Services\Analysis;

use App\Models\Sport;
use App\Support\StatMath;

/**
 * One entry per sport slug the generic analysis service knows how to
 * aggregate — every sport here has a single career-stats table of plain
 * integer counts (no player-entered rate columns to reconcile, unlike
 * Cricket's batting/bowling split), so one generic aggregation routine
 * covers all of them. `sum_columns` lists the columns to total; `derived`
 * computes rate fields (win %, accuracy, ...) from those totals rather than
 * ever averaging/summing a rate column directly. `overview` picks which
 * totals headline the Analysis screen's top grid.
 *
 * Racket sports (Tennis/Badminton/Table Tennis — one shared table split by a
 * `category` enum) and Soft-Ball-Cricket (a Cricket-shaped batting/bowling
 * split with no Format/Category dimension at all) don't fit this single-
 * table shape and are deliberately left out for now; the Analysis tab falls
 * back to "Coming Soon" for those.
 */
class SportAnalysisConfig
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        $winPct = fn (string $win, string $lost) => fn (array $t) => StatMath::safeDivide(
            (($t[$win] ?? 0) * 100),
            ($t[$win] ?? 0) + ($t[$lost] ?? 0),
            1
        );

        return [
            Sport::HOCKEY_SLUG => [
                'profile_relation' => 'hockeyProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentMatches',
                'recent_date_column' => 'match_date',
                'sum_columns' => ['matches', 'matches_won', 'matches_lost', 'goals', 'assist_goals', 'defeat_goal', 'result_won', 'result_lost', 'result_drawn'],
                'derived' => ['win_percentage' => $winPct('matches_won', 'matches_lost')],
                'overview' => ['matches', 'goals', 'assist_goals', 'win_percentage'],
            ],
            Sport::BASE_BALL_SLUG => [
                'profile_relation' => 'baseBallProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentMatches',
                'recent_date_column' => 'match_date',
                'sum_columns' => ['matches', 'nt', 'at_bats', 'runs', 'hits', 'rbi', 'won', 'lost'],
                'derived' => ['win_percentage' => $winPct('won', 'lost')],
                'overview' => ['matches', 'runs', 'hits', 'win_percentage'],
            ],
            Sport::NETBALL_SLUG => [
                'profile_relation' => 'netBallProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentMatches',
                'recent_date_column' => 'match_date',
                'sum_columns' => ['matches', 'matches_won', 'matches_lost', 'goals', 'attempts', 'result_won', 'result_lost'],
                'derived' => [
                    'win_percentage' => $winPct('matches_won', 'matches_lost'),
                    'goal_accuracy' => fn (array $t) => StatMath::safeDivide(($t['goals'] ?? 0) * 100, $t['attempts'] ?? 0, 1),
                ],
                'overview' => ['matches', 'goals', 'goal_accuracy', 'win_percentage'],
            ],
            Sport::KABADI_SLUG => [
                'profile_relation' => 'kabadiProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentMatches',
                'recent_date_column' => 'match_date',
                'sum_columns' => [
                    'matches', 'win', 'lost', 'cbp', 'raids', 'successful_raids', 'unsuccessful_raids',
                    'raid_touch_point', 'raid_bonus_point', 'tackles', 'successful_tackles',
                    'unsuccessful_tackles', 'empty_raids', 'yellow_cards', 'green_cards', 'red_cards',
                ],
                'derived' => ['win_percentage' => $winPct('win', 'lost')],
                'overview' => ['matches', 'raids', 'tackles', 'win_percentage'],
            ],
            Sport::JUDO_SLUG => [
                'profile_relation' => 'judoProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentFights',
                'recent_date_column' => 'fight_date',
                'sum_columns' => ['matches', 'win', 'lost', 'third_place', 'second_place', 'champion'],
                'derived' => ['win_percentage' => $winPct('win', 'lost')],
                'overview' => ['matches', 'champion', 'win_percentage'],
            ],
            Sport::BASKETBALL_SLUG => [
                'profile_relation' => 'basketballProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentMatches',
                'recent_date_column' => 'match_date',
                'sum_columns' => ['matches', 'win', 'lost', 'points', 'rebounds', 'assists', 'blocks', 'steals', 'minutes'],
                'derived' => [
                    'win_percentage' => $winPct('win', 'lost'),
                    'points_per_match' => fn (array $t) => StatMath::safeDivide($t['points'] ?? 0, $t['matches'] ?? 0),
                ],
                'overview' => ['matches', 'points', 'points_per_match', 'win_percentage'],
            ],
            Sport::FOOTBALL_SLUG => [
                'profile_relation' => 'footballProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentMatches',
                'recent_date_column' => 'match_date',
                'sum_columns' => ['matches', 'win', 'lost', 'goals', 'assists', 'defensive_actions', 'goalkeeper_clean_sheets', 'goalkeeper_goals_conceded', 'yellow_card', 'red_card'],
                'derived' => ['win_percentage' => $winPct('win', 'lost')],
                'overview' => ['matches', 'goals', 'assists', 'win_percentage'],
            ],
            Sport::RUGBY_SLUG => [
                'profile_relation' => 'rugbyProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentMatches',
                'recent_date_column' => 'match_date',
                'sum_columns' => ['matches', 'win', 'lost', 'tries', 'conversion', 'penalty_kick', 'drop_goal', 'yellow_card', 'red_card'],
                'derived' => ['win_percentage' => $winPct('win', 'lost')],
                'overview' => ['matches', 'tries', 'conversion', 'win_percentage'],
            ],
            Sport::BOXING_SLUG => [
                'profile_relation' => 'boxingProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentFights',
                'recent_date_column' => 'fight_date',
                'sum_columns' => ['matches', 'win', 'lost', 'third_place', 'second_place', 'champion'],
                'derived' => ['win_percentage' => $winPct('win', 'lost')],
                'overview' => ['matches', 'champion', 'win_percentage'],
            ],
            Sport::KARATE_SLUG => [
                'profile_relation' => 'karateProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentMatches',
                'recent_date_column' => 'match_date',
                'sum_columns' => ['matches', 'fights', 'win', 'lost', 'third_place', 'second_place', 'champion'],
                'derived' => ['win_percentage' => $winPct('win', 'lost')],
                'overview' => ['matches', 'fights', 'champion', 'win_percentage'],
            ],
            Sport::CHESS_SLUG => [
                'profile_relation' => 'chessProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentMatches',
                'recent_date_column' => 'match_date',
                'sum_columns' => ['games', 'win', 'lost', 'third_place', 'second_place', 'champion'],
                'derived' => ['win_percentage' => $winPct('win', 'lost')],
                'overview' => ['games', 'champion', 'win_percentage'],
            ],
            Sport::ATHLETICS_SLUG => [
                'profile_relation' => 'athleticsProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentEvents',
                'recent_date_column' => 'event_date',
                'personal_bests_relation' => 'personalBests',
                'sum_columns' => ['matches', 'third_place', 'second_place', 'champion'],
                'derived' => [],
                'overview' => ['matches', 'champion', 'second_place', 'third_place'],
            ],
            Sport::SWIMMING_SLUG => [
                'profile_relation' => 'swimmingProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentEvents',
                'recent_date_column' => 'event_date',
                'personal_bests_relation' => 'personalBests',
                'sum_columns' => ['matches', 'third_place', 'second_place', 'champion'],
                'derived' => [],
                'overview' => ['matches', 'champion', 'second_place', 'third_place'],
            ],
            Sport::VOLLEYBALL_SLUG => [
                'profile_relation' => 'volleyballProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentMatches',
                'recent_date_column' => 'match_date',
                'sum_columns' => ['matches', 'win', 'lost', 'passes', 'setting', 'serve', 'attacking', 'blocking', 'digging', 'third_place', 'second_place', 'champion'],
                'derived' => ['win_percentage' => $winPct('win', 'lost')],
                'overview' => ['matches', 'attacking', 'blocking', 'win_percentage'],
            ],
            Sport::BEACH_VOLLEYBALL_SLUG => [
                'profile_relation' => 'beachVolleyballProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentMatches',
                'recent_date_column' => 'match_date',
                'sum_columns' => ['matches', 'win', 'lost', 'passes', 'setting', 'serve', 'attacking', 'blocking', 'digging', 'third_place', 'second_place', 'champion'],
                'derived' => ['win_percentage' => $winPct('win', 'lost')],
                'overview' => ['matches', 'attacking', 'blocking', 'win_percentage'],
            ],
            Sport::ELLE_SLUG => [
                'profile_relation' => 'elleProfile',
                'career_relation' => 'careerStats',
                'recent_relation' => 'recentMatches',
                'recent_date_column' => 'match_date',
                'sum_columns' => ['matches', 'win', 'lost', 'runs', 'catches', 'third_place', 'second_place', 'champion'],
                'derived' => ['win_percentage' => $winPct('win', 'lost')],
                'overview' => ['matches', 'runs', 'catches', 'win_percentage'],
            ],
        ];
    }

    public static function get(string $slug): ?array
    {
        return self::all()[$slug] ?? null;
    }

    public static function supportedSlugs(): array
    {
        return array_keys(self::all());
    }
}
