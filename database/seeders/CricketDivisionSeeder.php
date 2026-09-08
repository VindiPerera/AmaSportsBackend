<?php

namespace Database\Seeders;

use App\Models\CricketDivision;
use Illuminate\Database\Seeder;

class CricketDivisionSeeder extends Seeder
{
    /**
     * Cricket's own "Category" list — client-provided. Every Format (see
     * CricketCategorySeeder) can be paired with any of these; there's no
     * age-based restriction on which Formats offer a Category (see
     * CareerStatAddModal on the mobile app).
     *
     * @var list<string>
     */
    private const DIVISIONS = [
        'Six a side', 'T10', 'T20', '30 Over', '40 Over', '50 Over',
        'One Day', 'Two Day', 'Three Day', 'Four Day', 'Test',
    ];

    public function run(): void
    {
        foreach (self::DIVISIONS as $index => $name) {
            CricketDivision::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }

        CricketDivision::whereNotIn('name', self::DIVISIONS)->delete();
    }
}
