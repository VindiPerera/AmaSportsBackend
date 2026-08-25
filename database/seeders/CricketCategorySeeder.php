<?php

namespace Database\Seeders;

use App\Models\CricketCategory;
use Illuminate\Database\Seeder;

class CricketCategorySeeder extends Seeder
{
    /**
     * Cricket's own "Category" list for the Batting/Bowling Career Stats
     * "Add New Stat" flow — client-provided, not the shared age_categories
     * seed. Order matters (drives sort_order / dropdown order).
     *
     * @var list<string>
     */
    private const CATEGORIES = [
        'U12', 'U13', 'U14', 'U15', 'U16', 'U17', 'U18', 'U19', 'U20', 'U21',
        'U22', 'U23', 'U24', 'Div i', 'Div ii', 'Div iii', 'Div iv', 'Div v',
        'District', 'Province', 'National', 'International', 'Mercantile',
        'Practice', 'Academy', 'Friendly', 'Premier', 'Zara', 'Other',
    ];

    public function run(): void
    {
        foreach (self::CATEGORIES as $index => $name) {
            CricketCategory::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }

        // Keep the table in sync with the list above — drop anything no
        // longer in it (safe: the Add Stat flow has no self-service
        // "create new" any more, so nothing outside this seeder adds rows).
        CricketCategory::whereNotIn('name', self::CATEGORIES)->delete();
    }
}
