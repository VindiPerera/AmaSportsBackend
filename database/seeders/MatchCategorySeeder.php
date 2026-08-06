<?php

namespace Database\Seeders;

use App\Models\MatchCategory;
use Illuminate\Database\Seeder;

class MatchCategorySeeder extends Seeder
{
    /**
     * Per spec section 6.4. The spec lists "Tournament" twice (start and
     * end) — deduped here to a single row (see plan notes).
     *
     * @var list<string>
     */
    private const CATEGORIES = [
        'Tournament', 'Friendly', 'Practice', 'Mercantile', 'State Service',
        'School', 'Club', 'National', 'International', 'Academy',
    ];

    public function run(): void
    {
        foreach (self::CATEGORIES as $index => $name) {
            MatchCategory::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }
    }
}
