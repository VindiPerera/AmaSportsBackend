<?php

namespace Database\Seeders;

use App\Models\BoxingWeightClass;
use Illuminate\Database\Seeder;

class BoxingWeightClassSeeder extends Seeder
{
    /**
     * Per Phase 3 spec §C4.
     *
     * @var list<string>
     */
    private const CLASSES = [
        'Heavy Weight', 'Cruiser Weight', 'Light Heavy Weight', 'Super Middle Weight',
        'Middle Weight', 'Super Light Weight', 'Light Weight', 'Super Welter Weight',
        'Feather Weight', 'Super Bantam Weight', 'Bantam Weight', 'Super Flyweight',
        'Minimum Weight', 'Fly Weight', 'Light Flyweight',
    ];

    public function run(): void
    {
        foreach (self::CLASSES as $index => $name) {
            BoxingWeightClass::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }
    }
}
