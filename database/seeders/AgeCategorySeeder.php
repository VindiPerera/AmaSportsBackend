<?php

namespace Database\Seeders;

use App\Models\AgeCategory;
use Illuminate\Database\Seeder;

class AgeCategorySeeder extends Seeder
{
    /**
     * Per spec section 6.4.
     *
     * @var list<string>
     */
    private const AGES = [
        'U12', 'U13', 'U14', 'U15', 'U16', 'U17', 'U18', 'U19', 'U20', 'U21',
        'U22', 'U23', 'Open',
    ];

    public function run(): void
    {
        foreach (self::AGES as $index => $name) {
            AgeCategory::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }
    }
}
