<?php

namespace Tests\Feature;

use App\Models\AgeCategory;
use App\Models\CricketProfile;
use App\Models\Format;
use App\Models\MatchCategory;
use App\Models\Player;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CricketAnalysisTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The Analysis tab requires an active subscription (Phase 6 revision 2)
     * — give every test player one so these tests keep exercising the
     * analysis aggregation logic itself, not the paywall gate.
     */
    private function actingPlayer(): Player
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $player = Player::create(['user_id' => $user->id]);

        Subscription::create([
            'player_id' => $player->id,
            'amount' => Subscription::AMOUNT,
            'currency' => 'USD',
            'status' => Subscription::STATUS_ACTIVE,
            'starts_at' => now(),
            'expires_at' => now()->addYear(),
        ]);

        return $player;
    }

    public function test_returns_empty_shape_when_player_has_no_cricket_profile(): void
    {
        $this->actingPlayer();

        $response = $this->getJson('/api/player/cricket-analysis');

        $response->assertOk()
            ->assertJsonPath('data.has_profile', false)
            ->assertJsonPath('data.has_any_stats', false)
            ->assertJsonPath('data.overview.batting_average', null)
            ->assertJsonPath('data.overview.matches', 0);
    }

    public function test_aggregates_career_totals_and_avoids_divide_by_zero(): void
    {
        $player = $this->actingPlayer();
        $profile = CricketProfile::create(['player_id' => $player->id]);

        $divI = Format::create(['name' => 'Div I', 'sort_order' => 1]);
        $premier = Format::create(['name' => 'Premier', 'sort_order' => 2]);
        $age = AgeCategory::create(['name' => 'Open', 'sort_order' => 1]);
        $category = MatchCategory::create(['name' => 'Club', 'sort_order' => 1]);

        // Two batting rows across two formats — average must be recomputed
        // from summed runs/innings/not_out, not summed from the rows' own
        // "average" values.
        $profile->battingStats()->createMany([
            [
                'format_id' => $divI->id, 'age_category_id' => $age->id, 'match_category_id' => $category->id,
                'matches' => 5, 'innings' => 5, 'not_out' => 1, 'runs' => 200, 'hs' => '87',
                'average' => 50, 'sr' => 120.5, 'hundreds' => 0, 'fifties' => 2, 'fours' => 20, 'sixes' => 5,
                'catches' => 2, 'stumpings' => 0, 'won' => 3, 'lost' => 2,
            ],
            [
                'format_id' => $premier->id, 'age_category_id' => $age->id, 'match_category_id' => $category->id,
                'matches' => 3, 'innings' => 0, 'not_out' => 0, 'runs' => 0, 'hs' => null,
                'average' => null, 'sr' => null, 'hundreds' => 0, 'fifties' => 0, 'fours' => 0, 'sixes' => 0,
                'catches' => 0, 'stumpings' => 0, 'won' => 0, 'lost' => 0,
            ],
        ]);

        // A never-played bowler row: innings/balls are 0, so average/economy/SR
        // must come back null instead of throwing a division-by-zero error.
        $profile->bowlingStats()->create([
            'format_id' => $divI->id, 'age_category_id' => $age->id, 'match_category_id' => $category->id,
            'matches' => 5, 'innings' => 0, 'balls' => 0, 'runs' => 0, 'wickets' => 0,
            'bbi' => null, 'bbm' => null, 'average' => null, 'economy' => null, 'sr' => null,
            'four_w' => 0, 'five_w' => 0, 'ten_w' => 0,
        ]);

        $response = $this->getJson('/api/player/cricket-analysis');

        $response->assertOk()
            ->assertJsonPath('data.has_any_stats', true)
            ->assertJsonPath('data.batting.career.matches', 8)
            ->assertJsonPath('data.batting.career.runs', 200)
            // (5 innings - 1 not out) => 200/4 = 50.0 (json_encode drops the
            // trailing zero for whole-number floats, hence plain 50 here).
            ->assertJsonPath('data.batting.career.average', 50)
            // Two contributing rows -> strike rate can't be safely combined
            // (no balls-faced column to recompute from) -> null, not summed/averaged.
            ->assertJsonPath('data.batting.career.strike_rate', null)
            ->assertJsonPath('data.batting.career.win_percentage', 60)
            ->assertJsonPath('data.bowling.career.average', null)
            ->assertJsonPath('data.bowling.career.economy', null)
            ->assertJsonCount(2, 'data.batting.by_format');
    }

    public function test_format_filter_scopes_career_totals_but_not_by_format_breakdown(): void
    {
        $player = $this->actingPlayer();
        $profile = CricketProfile::create(['player_id' => $player->id]);

        $divI = Format::create(['name' => 'Div I', 'sort_order' => 1]);
        $premier = Format::create(['name' => 'Premier', 'sort_order' => 2]);
        $age = AgeCategory::create(['name' => 'Open', 'sort_order' => 1]);
        $category = MatchCategory::create(['name' => 'Club', 'sort_order' => 1]);

        $profile->battingStats()->createMany([
            [
                'format_id' => $divI->id, 'age_category_id' => $age->id, 'match_category_id' => $category->id,
                'matches' => 5, 'innings' => 5, 'not_out' => 0, 'runs' => 100, 'hs' => '40',
            ],
            [
                'format_id' => $premier->id, 'age_category_id' => $age->id, 'match_category_id' => $category->id,
                'matches' => 2, 'innings' => 2, 'not_out' => 0, 'runs' => 400, 'hs' => '120*',
            ],
        ]);

        $response = $this->getJson('/api/player/cricket-analysis?format='.$divI->id);

        $response->assertOk()
            ->assertJsonPath('data.filter.format_id', $divI->id)
            ->assertJsonPath('data.filter.format_name', 'Div I')
            ->assertJsonPath('data.batting.career.runs', 100)
            ->assertJsonPath('data.batting.career.highest_score', '40')
            // The comparison bar chart always shows every format regardless
            // of the top-level filter.
            ->assertJsonCount(2, 'data.batting.by_format');
    }

    public function test_best_bowling_figure_prefers_most_wickets_then_fewest_runs(): void
    {
        $player = $this->actingPlayer();
        $profile = CricketProfile::create(['player_id' => $player->id]);

        $divI = Format::create(['name' => 'Div I', 'sort_order' => 1]);
        $age = AgeCategory::create(['name' => 'Open', 'sort_order' => 1]);
        $category = MatchCategory::create(['name' => 'Club', 'sort_order' => 1]);

        $profile->bowlingStats()->createMany([
            [
                'format_id' => $divI->id, 'age_category_id' => $age->id, 'match_category_id' => $category->id,
                'bbi' => '3/40', 'bbm' => '3/40',
            ],
            [
                'format_id' => $divI->id, 'age_category_id' => $age->id, 'match_category_id' => $category->id,
                'bbi' => '4/60', 'bbm' => '5/80',
            ],
            [
                'format_id' => $divI->id, 'age_category_id' => $age->id, 'match_category_id' => $category->id,
                'bbi' => '4/25', 'bbm' => '4/25',
            ],
        ]);

        $response = $this->getJson('/api/player/cricket-analysis');

        $response->assertOk()
            // 4 wickets beats 3; between the two 4-wicket hauls, fewer runs wins.
            ->assertJsonPath('data.bowling.career.best_bowling_innings', '4/25')
            ->assertJsonPath('data.bowling.career.best_bowling_match', '5/80');
    }
}
