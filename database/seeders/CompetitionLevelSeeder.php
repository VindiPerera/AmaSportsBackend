<?php

namespace Database\Seeders;

use App\Models\CompetitionLevel;
use Illuminate\Database\Seeder;

class CompetitionLevelSeeder extends Seeder
{
    /**
     * Per Phase 2 spec §B5.
     *
     * @var list<string>
     */
    private const LEVELS = ['Cadets competition', 'Senior competition', 'Junior competitions'];

    public function run(): void
    {
        foreach (self::LEVELS as $index => $name) {
            CompetitionLevel::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }
    }
}
