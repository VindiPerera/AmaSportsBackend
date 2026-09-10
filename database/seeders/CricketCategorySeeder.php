<?php

namespace Database\Seeders;

use App\Models\CricketCategory;
use Illuminate\Database\Seeder;

class CricketCategorySeeder extends Seeder
{
    /**
     * Cricket's own "Format" list for the Batting/Bowling Career Stats
     * "Add New Stat" flow — client-provided, not the shared age_categories
     * seed. Every Format can be paired with any Category (see
     * CricketDivisionSeeder) — there's no age-based restriction on which
     * Formats offer a Category. Order matters (drives sort_order / dropdown
     * order).
     *
     * @var list<string>
     */
    private const CATEGORIES = [
        'Under 13 Div I', 'Under 13 Div II', 'Under 13 Div III', 'Under 13 Zonal',
        'Under 15 Div I', 'Under 15 Div II', 'Under 15 Div III', 'Under 15 Zonal',
        'Under 17 Div I', 'Under 17 Div II', 'Under 17 Div III', 'Under 17 Zonal',
        'Under 19 Div I', 'Under 19 Div II', 'Under 19 Div III', 'Under 19 Zonal',
        'Under 15 District', 'Under 15 Provincial', 'Under 15 National',
        'Under 17 District', 'Under 17 Provincial', 'Under 17 National',
        'Under 19 District', 'Under 19 Provincial', 'Under 19 National',
        'Premier', 'Tier B',
        'Division I', 'Division II', 'Division III', 'Division IV',
        'Practice', 'Friendly',
        'State Service Div I', 'State Service Div II', 'State Service Div III',
        'Mercantile Div I', 'Mercantile Div II', 'Mercantile Div III',
        'Academy',
        'Under 20', 'Under 21', 'Under 22', 'Under 23',
        'National', 'Zonal', 'Super League', 'List A', 'Other',
    ];

    public function run(): void
    {
        foreach (self::CATEGORIES as $index => $name) {
            CricketCategory::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }

        // Older rows outside this list are intentionally left in place —
        // existing stat rows still reference some of them via foreign key,
        // so they can't be deleted. The dropdown hides them client-side
        // instead (see CRICKET_FORMATS in cricketLookups.ts).
    }
}
