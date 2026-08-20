<?php

namespace Database\Seeders;

use App\Models\Sport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SportSeeder extends Seeder
{
    /**
     * Order and list per spec section 6.1.
     *
     * @var list<string>
     */
    private const SPORTS = [
        'Athletics', 'Badminton', 'Basketball', 'Boxing', 'Chess', 'Cricket',
        'Hockey', 'Judo', 'Karate', 'Rugby', 'Shooting', 'Football',
        'Swimming', 'Table Tennis', 'Tennis', 'Volleyball', 'Beach Volleyball',
        'Elle', 'Netball', 'Soft Ball Cricket', 'Base Ball', 'Kabadi',
    ];

    /**
     * Phase 1 shipped Cricket + Hockey; Phase 2 added Base Ball, Netball,
     * Tennis/Badminton/Table Tennis (shared form), Kabadi, and Judo; Phase 3
     * added Basketball, Football, Rugby, Boxing, Karate, Chess, Athletics,
     * and Swimming; Phase 4 added Volleyball, Beach Volleyball, and Elle;
     * Phase 5 adds Soft Ball Cricket. Only Shooting still falls through to
     * "coming soon" — no reference form has been provided for it yet.
     */
    private const FULL_FORM_SLUGS = [
        Sport::CRICKET_SLUG, Sport::HOCKEY_SLUG, Sport::BASE_BALL_SLUG, Sport::NETBALL_SLUG,
        Sport::TENNIS_SLUG, Sport::BADMINTON_SLUG, Sport::TABLE_TENNIS_SLUG,
        Sport::KABADI_SLUG, Sport::JUDO_SLUG,
        Sport::BASKETBALL_SLUG, Sport::FOOTBALL_SLUG, Sport::RUGBY_SLUG, Sport::BOXING_SLUG,
        Sport::KARATE_SLUG, Sport::CHESS_SLUG, Sport::ATHLETICS_SLUG, Sport::SWIMMING_SLUG,
        Sport::VOLLEYBALL_SLUG, Sport::BEACH_VOLLEYBALL_SLUG, Sport::ELLE_SLUG,
        Sport::SOFT_BALL_CRICKET_SLUG,
    ];

    public function run(): void
    {
        foreach (self::SPORTS as $index => $name) {
            $slug = Str::slug($name);

            Sport::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'has_full_form' => in_array($slug, self::FULL_FORM_SLUGS, true),
                    'sort_order' => $index,
                ]
            );
        }
    }
}
