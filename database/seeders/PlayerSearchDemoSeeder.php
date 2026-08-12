<?php

namespace Database\Seeders;

use App\Models\CricketBattingStat;
use App\Models\CricketBowlingStat;
use App\Models\CricketProfile;
use App\Models\CricketRecentMatch;
use App\Models\Player;
use Illuminate\Database\Seeder;

/**
 * Local dev / manual testing only — NOT wired into DatabaseSeeder::run().
 *
 * The Player Search screen (Api\PlayerSearchController) only surfaces
 * players who have BOTH a `full_name` and a Cricket profile. Neither is set
 * automatically — `players` rows are created bare (see
 * Player::firstOrCreate in various profile controllers) and only get a name
 * once someone submits "Edit Profile", same for the Cricket profile form.
 * A freshly seeded/local DB (via DatabaseSeeder's `User::factory(10)`) has
 * neither, so search always comes back empty except for whichever one
 * player you happened to fill in by hand.
 *
 * This seeder backfills a handful of existing nameless players with a
 * name + a minimal Cricket profile (plus one batting/bowling row and a
 * few recent matches, so the search result cards show real numbers
 * instead of all-zero) purely so there's something to find while testing
 * the search screen locally.
 *
 * Run with: php artisan db:seed --class=PlayerSearchDemoSeeder
 */
class PlayerSearchDemoSeeder extends Seeder
{
    /** [full_name, batting_style, bowling_style, playing_role] */
    private const DEMO_PLAYERS = [
        ['Dinesh Chandimal', 'Right-hand bat', 'Right-arm off break', 'Wicket-keeper batter'],
        ['Nuwan Pradeep', 'Right-hand bat', 'Right-arm fast', 'Bowler'],
        ['Sachin Fernando', 'Left-hand bat', 'Slow left-arm orthodox', 'All-rounder'],
        ['Ravindu Silva', 'Right-hand bat', 'Right-arm medium fast', 'Batter'],
        ['Chamara Wickramasinghe', 'Right-hand bat', 'Right-arm leg break', 'All-rounder'],
        ['Isuru Bandara', 'Left-hand bat', 'Left-arm fast medium', 'Bowler'],
        ['Tharindu Perera', 'Right-hand bat', 'Right-arm off break', 'Batter'],
        ['Anjana Rathnayake', 'Right-hand bat', 'Right-arm fast medium', 'All-rounder'],
    ];

    private const FORMAT_ID = 10; // Premier
    private const AGE_CATEGORY_ID = 13; // Open
    private const MATCH_CATEGORY_ID = 1; // Tournament

    private const OPPONENTS = ['Eagles CC', 'Titans XI', 'Colts SC', 'Rovers CC', 'Hawks CC', 'Panthers CC'];

    public function run(): void
    {
        $candidates = Player::query()
            ->whereNull('full_name')
            ->orderBy('id')
            ->limit(count(self::DEMO_PLAYERS))
            ->get();

        if ($candidates->isEmpty()) {
            $this->command?->warn('No nameless players left to backfill — nothing to do.');

            return;
        }

        foreach ($candidates as $index => $player) {
            [$fullName, $battingStyle, $bowlingStyle, $role] = self::DEMO_PLAYERS[$index];

            $player->update(['full_name' => $fullName]);

            $profile = CricketProfile::updateOrCreate(
                ['player_id' => $player->id],
                [
                    'born' => now()->subYears(19 + $index)->subMonths($index * 3),
                    'age' => 19 + $index,
                    'batting_style' => $battingStyle,
                    'bowling_style' => $bowlingStyle,
                    'playing_role' => $role,
                    'height' => (170 + $index) . ' cm',
                    'college_university' => null,
                ]
            );

            $runs = 300 + $index * 47;
            $innings = 12 + $index;
            $wickets = 8 + $index * 2;
            $balls = 400 + $index * 60;

            CricketBattingStat::updateOrCreate(
                [
                    'cricket_profile_id' => $profile->id,
                    'format_id' => self::FORMAT_ID,
                    'age_category_id' => self::AGE_CATEGORY_ID,
                    'match_category_id' => self::MATCH_CATEGORY_ID,
                ],
                [
                    'matches' => $innings,
                    'won' => (int) round($innings * 0.6),
                    'lost' => $innings - (int) round($innings * 0.6),
                    'innings' => $innings,
                    'not_out' => 2,
                    'runs' => $runs,
                    'hs' => (60 + $index * 5) . ($index % 2 === 0 ? '*' : ''),
                    'average' => round($runs / max($innings - 2, 1), 2),
                    'fours' => 20 + $index * 3,
                    'sixes' => 5 + $index,
                    'catches' => 3 + $index,
                    'stumpings' => 0,
                ]
            );

            CricketBowlingStat::updateOrCreate(
                [
                    'cricket_profile_id' => $profile->id,
                    'format_id' => self::FORMAT_ID,
                    'age_category_id' => self::AGE_CATEGORY_ID,
                    'match_category_id' => self::MATCH_CATEGORY_ID,
                ],
                [
                    'matches' => $innings,
                    'innings' => $innings,
                    'balls' => $balls,
                    'runs' => (int) round($balls * 0.75),
                    'wickets' => $wickets,
                    'average' => round(($balls * 0.75) / max($wickets, 1), 2),
                    'economy' => round(($balls * 0.75) / max($balls / 6, 1), 2),
                ]
            );

            CricketRecentMatch::where('cricket_profile_id', $profile->id)->delete();
            for ($m = 0; $m < 5; $m++) {
                CricketRecentMatch::create([
                    'cricket_profile_id' => $profile->id,
                    'match_date' => now()->subDays(($m + 1) * 6),
                    'opponent' => self::OPPONENTS[($index + $m) % count(self::OPPONENTS)],
                    'played_xi' => true,
                    'runs' => max(0, 20 + ($m * 13 + $index * 7) % 70),
                    'balls' => 30 + $m * 5,
                    'fours' => $m % 4,
                    'sixes' => $m % 3,
                    'overs' => 3.0 + $m * 0.4,
                    'maidens' => $m % 2,
                    'wickets' => $m % 3,
                    'catches' => $m % 2,
                    'stumpings' => 0,
                ]);
            }

            $this->command?->info("Seeded search-demo profile for player #{$player->id}: {$fullName}");
        }
    }
}
