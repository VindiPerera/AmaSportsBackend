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
use Illuminate\Support\Str;

/**
 * One-off: seeds a full Cricket career (multi-format stats + recent matches)
 * plus an active subscription for a specific real user (vinuk@gmail.com).
 *
 * Deliberately does NOT touch the `users` row — the account already exists
 * with its own real password, so this only looks the user up by email and
 * fails loudly if they're not found, rather than creating one.
 *
 * Run with: php artisan db:seed --class=VinukCricketStatsSeeder
 */
class VinukCricketStatsSeeder extends Seeder
{
    private const EMAIL = 'vinuk@gmail.com';

    private const CAREER = [
        ['format' => 'National', 'category' => 'International', 'matches' => 48, 'won' => 32, 'lost' => 16, 'innings' => 46, 'not_out' => 6, 'runs' => 2140, 'hs' => '134*', 'average' => 53.50, 'sr' => 92.30, 'hundreds' => 6, 'fifties' => 12, 'fours' => 210, 'sixes' => 58, 'catches' => 24,
            'bowl_innings' => 44, 'balls' => 2160, 'bowl_runs' => 1512, 'wickets' => 78, 'bbi' => '6/24', 'bowl_average' => 19.38, 'economy' => 4.20, 'bowl_sr' => 27.7, 'four_w' => 6, 'five_w' => 3],
        ['format' => 'Premier', 'category' => 'Tournament', 'matches' => 65, 'won' => 40, 'lost' => 22, 'innings' => 61, 'not_out' => 8, 'runs' => 2610, 'hs' => '158', 'average' => 49.25, 'sr' => 88.10, 'hundreds' => 7, 'fifties' => 15, 'fours' => 268, 'sixes' => 71, 'catches' => 31,
            'bowl_innings' => 58, 'balls' => 2820, 'bowl_runs' => 2068, 'wickets' => 95, 'bbi' => '5/19', 'bowl_average' => 21.77, 'economy' => 4.40, 'bowl_sr' => 29.7, 'four_w' => 8, 'five_w' => 4],
        ['format' => 'Div I', 'category' => 'Club', 'matches' => 30, 'won' => 21, 'lost' => 9, 'innings' => 28, 'not_out' => 4, 'runs' => 1180, 'hs' => '96', 'average' => 49.16, 'sr' => 101.40, 'hundreds' => 0, 'fifties' => 11, 'fours' => 130, 'sixes' => 44, 'catches' => 14,
            'bowl_innings' => 27, 'balls' => 1080, 'bowl_runs' => 792, 'wickets' => 42, 'bbi' => '4/15', 'bowl_average' => 18.86, 'economy' => 4.40, 'bowl_sr' => 25.7, 'four_w' => 3, 'five_w' => 1],
    ];

    private const RECENT = [
        ['days_ago' => 35, 'opponent' => 'Kandy Warriors', 'runs' => 88, 'balls' => 61, 'fours' => 9, 'sixes' => 3, 'overs' => 4.0, 'maidens' => 1, 'wickets' => 2, 'catches' => 1],
        ['days_ago' => 29, 'opponent' => 'Galle Gladiators', 'runs' => 45, 'balls' => 38, 'fours' => 4, 'sixes' => 1, 'overs' => 4.0, 'maidens' => 0, 'wickets' => 3, 'catches' => 0],
        ['days_ago' => 22, 'opponent' => 'Jaffna Stallions', 'runs' => 112, 'balls' => 79, 'fours' => 11, 'sixes' => 4, 'overs' => 3.0, 'maidens' => 0, 'wickets' => 1, 'catches' => 2],
        ['days_ago' => 15, 'opponent' => 'Dambulla Aura', 'runs' => 34, 'balls' => 29, 'fours' => 3, 'sixes' => 1, 'overs' => 4.0, 'maidens' => 1, 'wickets' => 4, 'catches' => 1],
        ['days_ago' => 8, 'opponent' => 'Negombo Titans', 'runs' => 67, 'balls' => 48, 'fours' => 6, 'sixes' => 2, 'overs' => 4.0, 'maidens' => 0, 'wickets' => 2, 'catches' => 0],
        ['days_ago' => 3, 'opponent' => 'Ruhuna Royals', 'runs' => 94, 'balls' => 66, 'fours' => 8, 'sixes' => 4, 'overs' => 4.0, 'maidens' => 1, 'wickets' => 3, 'catches' => 1],
    ];

    public function run(): void
    {
        $user = User::where('email', self::EMAIL)->first();
        if (! $user) {
            $this->command?->error('No user found with email '.self::EMAIL.' — create the account first, then re-run this seeder.');

            return;
        }

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

        DB::transaction(function () use ($sport, $formatIds, $fallbackFormatId, $ageCategoryId, $matchCategoryIds, $fallbackMatchCategoryId, $user) {
            $player = Player::updateOrCreate(
                ['user_id' => $user->id],
                ['full_name' => $user->name, 'country' => 'Sri Lanka']
            );

            $profile = CricketProfile::updateOrCreate(
                ['player_id' => $player->id],
                [
                    'born' => now()->subYears(24)->subMonths(3),
                    'age' => 24,
                    'batting_style' => 'Right-hand bat',
                    'bowling_style' => 'Right-arm fast medium',
                    'playing_role' => 'All-rounder',
                    'height' => '182 cm',
                    'college_university' => null,
                ]
            );

            $profile->battingStats()->delete();
            $profile->bowlingStats()->delete();
            $profile->recentMatches()->delete();

            foreach (self::CAREER as $row) {
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

            foreach (self::RECENT as $m) {
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
                'team_name' => 'Colombo Kings CC',
            ]);

            PlayerSport::updateOrCreate(
                ['player_id' => $player->id, 'sport_id' => $sport->id],
                ['status' => PlayerSport::STATUS_COMPLETED]
            );

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

            $this->command?->info("[premium] {$user->name} <{$user->email}> — Cricket career stats seeded.");
        });
    }
}
