<?php

namespace Database\Seeders;

use App\Models\Sport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SportSeeder extends Seeder
{
    /**
     * Order and list per spec section 6.1. Only Cricket and Hockey ship a
     * full registration form in this phase.
     *
     * @var list<string>
     */
    private const SPORTS = [
        'Athletics', 'Badminton', 'Basketball', 'Boxing', 'Chess', 'Cricket',
        'Hockey', 'Judo', 'Karate', 'Rugby', 'Shooting', 'Football',
        'Swimming', 'Table Tennis', 'Tennis', 'Volleyball', 'Beach Volleyball',
        'Elle', 'Netball', 'Soft Ball Cricket', 'Base Ball', 'Kabadi',
    ];

    private const FULL_FORM_SLUGS = [Sport::CRICKET_SLUG, Sport::HOCKEY_SLUG];

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
