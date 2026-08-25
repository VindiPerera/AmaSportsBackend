<?php

namespace Database\Seeders;

use App\Models\CricketDivision;
use Illuminate\Database\Seeder;

class CricketDivisionSeeder extends Seeder
{
    /**
     * Cricket's own "Division" list — client-provided. Only offered for
     * Categories U12...U19 (see CareerStatAddModal's
     * DIVISION_ELIGIBLE_CATEGORIES on the mobile app); every other Category
     * has no Division at all.
     *
     * @var list<string>
     */
    private const DIVISIONS = ['Div i', 'Div ii', 'Div iii', 'Others'];

    public function run(): void
    {
        foreach (self::DIVISIONS as $index => $name) {
            CricketDivision::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }

        CricketDivision::whereNotIn('name', self::DIVISIONS)->delete();
    }
}
