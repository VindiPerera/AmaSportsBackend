
<?php

namespace Database\Seeders;

use App\Models\AgeCategory;
use App\Models\CricketBattingStat;
use App\Models\CricketBowlingStat;
use App\Models\CricketProfile;
use App\Models\CricketRecentMatch;
use App\Models\Format;
use App\Models\MatchCategory;
use App\Models\Player;
use App\Models\PlayerSport;
use App\Models\PlayerTeam;
use App\Models\Sport;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * A realistic list of Cricket players for local dev / manual testing —
 * everyone the Player Search screen and the Analysis/premium paywall need
 * something to show. Idempotent (keyed by email) so re-running `db:seed`
 * never duplicates rows.
 *
 * Two tiers, deliberately:
 *  - PREMIUM_PLAYERS: full multi-format career stats + an active paid
 *    Subscription, so premium screens (Analysis tab, "Add Sport") have a
 *    real account to log into that isn't paywalled.
 *  - FREE_PLAYERS: a name + a complete-enough Cricket profile (one format,
 *    a few recent matches) but NO subscription — the common case, and what
 *    exercises the paywall/upsell screens.
 *
 * All logins use the password "password".
 */
class CricketPlayersSeeder extends Seeder
{
    private const PASSWORD = 'password';

    private const PREMIUM_PLAYERS = [
        [
            'name' => 'Kavindu Rajapaksha',
            'email' => 'kavindu.rajapaksha@amasports.app',
            'country' => 'Sri Lanka',
            'team' => 'Colombo Kings CC',
            'batting_style' => 'Right-hand bat',
            'bowling_style' => 'Right-arm fast medium',
            'playing_role' => 'All-rounder',
            'height' => '182 cm',
            'college' => 'Royal College, Colombo',
            // One row per level: National, Premier, Club.
            'career' => [
                ['format' => 'National', 'category' => 'International', 'matches' => 48, 'won' => 32, 'lost' => 16, 'innings' => 46, 'not_out' => 6, 'runs' => 2140, 'hs' => '134*', 'average' => 53.50, 'sr' => 92.30, 'hundreds' => 6, 'fifties' => 12, 'fours' => 210, 'sixes' => 58, 'catches' => 24,
                    'bowl_innings' => 44, 'balls' => 2160, 'bowl_runs' => 1512, 'wickets' => 78, 'bbi' => '6/24', 'bowl_average' => 19.38, 'economy' => 4.20, 'bowl_sr' => 27.7, 'four_w' => 6, 'five_w' => 3],
                ['format' => 'Premier', 'category' => 'Tournament', 'matches' => 65, 'won' => 40, 'lost' => 22, 'innings' => 61, 'not_out' => 8, 'runs' => 2610, 'hs' => '158', 'average' => 49.25, 'sr' => 88.10, 'hundreds' => 7, 'fifties' => 15, 'fours' => 268, 'sixes' => 71, 'catches' => 31,
                    'bowl_innings' => 58, 'balls' => 2820, 'bowl_runs' => 2068, 'wickets' => 95, 'bbi' => '5/19', 'bowl_average' => 21.77, 'economy' => 4.40, 'bowl_sr' => 29.7, 'four_w' => 8, 'five_w' => 4],
                ['format' => 'Div I', 'category' => 'Club', 'matches' => 30, 'won' => 21, 'lost' => 9, 'innings' => 28, 'not_out' => 4, 'runs' => 1180, 'hs' => '96', 'average' => 49.16, 'sr' => 101.40, 'hundreds' => 0, 'fifties' => 11, 'fours' => 130, 'sixes' => 44, 'catches' => 14,
                    'bowl_innings' => 27, 'balls' => 1080, 'bowl_runs' => 792, 'wickets' => 42, 'bbi' => '4/15', 'bowl_average' => 18.86, 'economy' => 4.40, 'bowl_sr' => 25.7, 'four_w' => 3, 'five_w' => 1],
            ],
            'recent' => [
                ['days_ago' => 35, 'opponent' => 'Kandy Warriors', 'runs' => 88, 'balls' => 61, 'fours' => 9, 'sixes' => 3, 'overs' => 4.0, 'maidens' => 1, 'wickets' => 2, 'catches' => 1],
                ['days_ago' => 29, 'opponent' => 'Galle Gladiators', 'runs' => 45, 'balls' => 38, 'fours' => 4, 'sixes' => 1, 'overs' => 4.0, 'maidens' => 0, 'wickets' => 3, 'catches' => 0],
                ['days_ago' => 22, 'opponent' => 'Jaffna Stallions', 'runs' => 112, 'balls' => 79, 'fours' => 11, 'sixes' => 4, 'overs' => 3.0, 'maidens' => 0, 'wickets' => 1, 'catches' => 2],
                ['days_ago' => 15, 'opponent' => 'Dambulla Aura', 'runs' => 34, 'balls' => 29, 'fours' => 3, 'sixes' => 1, 'overs' => 4.0, 'maidens' => 1, 'wickets' => 4, 'catches' => 1],
                ['days_ago' => 8, 'opponent' => 'Negombo Titans', 'runs' => 67, 'balls' => 48, 'fours' => 6, 'sixes' => 2, 'overs' => 4.0, 'maidens' => 0, 'wickets' => 2, 'catches' => 0],
                ['days_ago' => 3, 'opponent' => 'Ruhuna Royals', 'runs' => 94, 'balls' => 66, 'fours' => 8, 'sixes' => 4, 'overs' => 4.0, 'maidens' => 1, 'wickets' => 3, 'catches' => 1],
            ],
        ],
        [
            'name' => 'Sanduni Perera',
            'email' => 'sanduni.perera@amasports.app',
            'country' => 'Sri Lanka',
            'team' => 'Southern Sirens CC',
            'batting_style' => 'Left-hand bat',
            'bowling_style' => 'Slow left-arm orthodox',
            'playing_role' => 'Batter',
            'height' => '168 cm',
            'college' => 'Visakha Vidyalaya, Colombo',
            'career' => [
                ['format' => 'National', 'category' => 'International', 'matches' => 40, 'won' => 27, 'lost' => 13, 'innings' => 39, 'not_out' => 5, 'runs' => 1890, 'hs' => '121', 'average' => 55.59, 'sr' => 84.70, 'hundreds' => 4, 'fifties' => 13, 'fours' => 198, 'sixes' => 22, 'catches' => 19,
                    'bowl_innings' => 20, 'balls' => 720, 'bowl_runs' => 468, 'wickets' => 22, 'bbi' => '3/18', 'bowl_average' => 21.27, 'economy' => 3.90, 'bowl_sr' => 32.7, 'four_w' => 1, 'five_w' => 0],
                ['format' => 'Premier', 'category' => 'Tournament', 'matches' => 52, 'won' => 33, 'lost' => 19, 'innings' => 49, 'not_out' => 7, 'runs' => 2280, 'hs' => '145*', 'average' => 54.29, 'sr' => 81.20, 'hundreds' => 5, 'fifties' => 16, 'fours' => 240, 'sixes' => 28, 'catches' => 22,
                    'bowl_innings' => 26, 'balls' => 936, 'bowl_runs' => 608, 'wickets' => 29, 'bbi' => '4/22', 'bowl_average' => 20.97, 'economy' => 3.90, 'bowl_sr' => 32.3, 'four_w' => 2, 'five_w' => 0],
            ],
            'recent' => [
                ['days_ago' => 32, 'opponent' => 'Kandy Warriors', 'runs' => 76, 'balls' => 64, 'fours' => 8, 'sixes' => 1, 'overs' => 2.0, 'maidens' => 0, 'wickets' => 1, 'catches' => 1],
                ['days_ago' => 25, 'opponent' => 'Eagles CC', 'runs' => 54, 'balls' => 49, 'fours' => 5, 'sixes' => 0, 'overs' => 2.0, 'maidens' => 1, 'wickets' => 0, 'catches' => 0],
                ['days_ago' => 18, 'opponent' => 'Titans XI', 'runs' => 121, 'balls' => 91, 'fours' => 13, 'sixes' => 2, 'overs' => 0.0, 'maidens' => 0, 'wickets' => 0, 'catches' => 2],
                ['days_ago' => 11, 'opponent' => 'Colts SC', 'runs' => 38, 'balls' => 33, 'fours' => 3, 'sixes' => 1, 'overs' => 2.0, 'maidens' => 0, 'wickets' => 2, 'catches' => 0],
                ['days_ago' => 4, 'opponent' => 'Rovers CC', 'runs' => 63, 'balls' => 51, 'fours' => 6, 'sixes' => 1, 'overs' => 1.0, 'maidens' => 0, 'wickets' => 1, 'catches' => 1],
            ],
        ],
    ];

    /** [name, email-local-part, batting_style, bowling_style, playing_role] — one format row each, lighter than the premium tier. */
    private const FREE_PLAYERS = [
        ['Dinesh Chandimal', 'dinesh.chandimal', 'Right-hand bat', 'Right-arm off break', 'Wicket-keeper batter'],
        ['Nuwan Pradeep', 'nuwan.pradeep', 'Right-hand bat', 'Right-arm fast', 'Bowler'],
        ['Sachin Fernando', 'sachin.fernando', 'Left-hand bat', 'Slow left-arm orthodox', 'All-rounder'],
        ['Ravindu Silva', 'ravindu.silva', 'Right-hand bat', 'Right-arm medium fast', 'Batter'],
        ['Chamara Wickramasinghe', 'chamara.wickramasinghe', 'Right-hand bat', 'Right-arm leg break', 'All-rounder'],
        ['Isuru Bandara', 'isuru.bandara', 'Left-hand bat', 'Left-arm fast medium', 'Bowler'],
    ];

    private const OPPONENTS = ['Eagles CC', 'Titans XI', 'Colts SC', 'Rovers CC', 'Hawks CC', 'Panthers CC'];

    public function run(): void
    {
        $sport = Sport::where('slug', Sport::CRICKET_SLUG)->first();
        if (! $sport) {
            $this->command?->warn('Cricket sport row not found — run SportSeeder first. Skipping.');

            return;
        }

        $formatIds = Format::pluck('id', 'name');
        $fallbackFormatId = Format::query()->value('id');
        $ageCategoryId = AgeCategory::where('name', 'Open')->value('id') ?? AgeCategory::query()->value('id');
        $matchCategoryIds = MatchCategory::pluck('id', 'name');
        $fallbackMatchCategoryId = MatchCategory::query()->value('id');

        foreach (self::PREMIUM_PLAYERS as $data) {
            $this->seedPlayer($sport, $formatIds, $fallbackFormatId, $ageCategoryId, $matchCategoryIds, $fallbackMatchCategoryId, $data, premium: true);
        }

        foreach (self::FREE_PLAYERS as $index => [$name, $emailLocal, $battingStyle, $bowlingStyle, $role]) {
            $runs = 300 + $index * 47;
            $innings = 12 + $index;
            $wickets = 8 + $index * 2;
            $balls = 400 + $index * 60;

            $data = [
                'name' => $name,
                'email' => $emailLocal.'@amasports.app',
                'country' => 'Sri Lanka',
                'team' => self::OPPONENTS[$index % count(self::OPPONENTS)],
                'batting_style' => $battingStyle,
                'bowling_style' => $bowlingStyle,
                'playing_role' => $role,
                'height' => (170 + $index).' cm',
                'college' => null,
                'career' => [
                    [
                        'format' => 'Premier', 'category' => 'Tournament',
                        'matches' => $innings, 'won' => (int) round($innings * 0.6), 'lost' => $innings - (int) round($innings * 0.6),
                        'innings' => $innings, 'not_out' => 2, 'runs' => $runs, 'hs' => (60 + $index * 5).($index % 2 === 0 ? '*' : ''),
                        'average' => round($runs / max($innings - 2, 1), 2), 'sr' => 95.0, 'hundreds' => 0, 'fifties' => 3,
                        'fours' => 20 + $index * 3, 'sixes' => 5 + $index, 'catches' => 3 + $index,
                        'bowl_innings' => $innings, 'balls' => $balls, 'bowl_runs' => (int) round($balls * 0.75), 'wickets' => $wickets,
                        'bbi' => '3/'.(20 + $index), 'bowl_average' => round(($balls * 0.75) / max($wickets, 1), 2),
                        'economy' => round(($balls * 0.75) / max($balls / 6, 1), 2), 'bowl_sr' => 24.0, 'four_w' => 1, 'five_w' => 0,
                    ],
                ],
                'recent' => array_map(fn ($m) => [
                    'days_ago' => ($m + 1) * 6,
                    'opponent' => self::OPPONENTS[($index + $m) % count(self::OPPONENTS)],
                    'runs' => max(0, 20 + ($m * 13 + $index * 7) % 70),
                    'balls' => 30 + $m * 5,
                    'fours' => $m % 4,
                    'sixes' => $m % 3,
                    'overs' => 3.0 + $m * 0.4,
                    'maidens' => $m % 2,
                    'wickets' => $m % 3,
                    'catches' => $m % 2,
                ], range(0, 4)),
            ];

            $this->seedPlayer($sport, $formatIds, $fallbackFormatId, $ageCategoryId, $matchCategoryIds, $fallbackMatchCategoryId, $data, premium: false);
        }
    }

    private function seedPlayer(
        Sport $sport,
        $formatIds,
        ?int $fallbackFormatId,
        ?int $ageCategoryId,
        $matchCategoryIds,
        ?int $fallbackMatchCategoryId,
        array $data,
        bool $premium,
    ): void {
        DB::transaction(function () use ($sport, $formatIds, $fallbackFormatId, $ageCategoryId, $matchCategoryIds, $fallbackMatchCategoryId, $data, $premium) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make(self::PASSWORD),
                    'role' => User::ROLE_STUDENT,
                ]
            );

            $player = Player::updateOrCreate(
                ['user_id' => $user->id],
                ['full_name' => $data['name'], 'country' => $data['country']]
            );

            $profile = CricketProfile::updateOrCreate(
                ['player_id' => $player->id],
                [
                    'born' => now()->subYears(24)->subMonths(3),
                    'age' => 24,
                    'batting_style' => $data['batting_style'],
                    'bowling_style' => $data['bowling_style'],
                    'playing_role' => $data['playing_role'],
                    'height' => $data['height'],
                    'college_university' => $data['college'],
                ]
            );

            $profile->battingStats()->delete();
            $profile->bowlingStats()->delete();
            $profile->recentMatches()->delete();

            foreach ($data['career'] as $row) {
                $formatId = $formatIds[$row['format']] ?? $fallbackFormatId;
                $matchCategoryId = $matchCategoryIds[$row['category']] ?? $fallbackMatchCategoryId;

                CricketBattingStat::create([
                    'cricket_profile_id' => $profile->id,
                    'format_id' => $formatId,
                    'age_category_id' => $ageCategoryId,
                    'match_category_id' => $matchCategoryId,
                    'matches' => $row['matches'],
                    'won' => $row['won'],
                    'lost' => $row['lost'],
                    'innings' => $row['innings'],
                    'not_out' => $row['not_out'],
                    'runs' => $row['runs'],
                    'hs' => $row['hs'],
                    'average' => $row['average'],
                    'best' => (int) filter_var($row['hs'], FILTER_SANITIZE_NUMBER_INT),
                    'sr' => $row['sr'],
                    'hundreds' => $row['hundreds'],
                    'fifties' => $row['fifties'],
                    'fours' => $row['fours'],
                    'sixes' => $row['sixes'],
                    'catches' => $row['catches'],
                    'stumpings' => 0,
                ]);

                CricketBowlingStat::create([
                    'cricket_profile_id' => $profile->id,
                    'format_id' => $formatId,
                    'age_category_id' => $ageCategoryId,
                    'match_category_id' => $matchCategoryId,
                    'matches' => $row['matches'],
                    'innings' => $row['bowl_innings'],
                    'balls' => $row['balls'],
                    'runs' => $row['bowl_runs'],
                    'wickets' => $row['wickets'],
                    'bbi' => $row['bbi'],
                    'bbm' => $row['bbi'],
                    'average' => $row['bowl_average'],
                    'economy' => $row['economy'],
                    'sr' => $row['bowl_sr'],
                    'four_w' => $row['four_w'],
                    'five_w' => $row['five_w'],
                    'ten_w' => 0,
                ]);
            }

            foreach ($data['recent'] as $m) {
                CricketRecentMatch::create([
                    'cricket_profile_id' => $profile->id,
                    'match_date' => now()->subDays($m['days_ago']),
                    'opponent' => $m['opponent'],
                    'played_xi' => true,
                    'runs' => $m['runs'],
                    'balls' => $m['balls'],
                    'fours' => $m['fours'],
                    'sixes' => $m['sixes'],
                    'overs' => $m['overs'],
                    'maidens' => $m['maidens'],
                    'wickets' => $m['wickets'],
                    'catches' => $m['catches'],
                    'stumpings' => 0,
                ]);
            }

            PlayerTeam::where('player_id', $player->id)->where('sport_id', $sport->id)->delete();
            PlayerTeam::create([
                'player_id' => $player->id,
                'sport_id' => $sport->id,
                'team_name' => $data['team'],
            ]);

            PlayerSport::updateOrCreate(
                ['player_id' => $player->id, 'sport_id' => $sport->id],
                ['status' => PlayerSport::STATUS_COMPLETED]
            );

            if ($premium) {
                Subscription::updateOrCreate(
                    ['player_id' => $player->id, 'status' => Subscription::STATUS_ACTIVE],
                    [
                        'paypal_order_id' => 'SEEDED-'.Str::upper(Str::random(10)),
                        'amount' => Subscription::AMOUNT,
                        'currency' => 'USD',
                        'starts_at' => now(),
                        'expires_at' => now()->addYear(),
                    ]
                );
            }

            $this->command?->info(($premium ? '[premium] ' : '[free]    ')."{$data['name']} <{$data['email']}>");
        });
    }
}
