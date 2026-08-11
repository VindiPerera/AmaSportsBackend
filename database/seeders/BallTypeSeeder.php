<?php

namespace Database\Seeders;

use App\Models\BallType;
use Illuminate\Database\Seeder;

class BallTypeSeeder extends Seeder
{
    /**
     * Includes "Other" as a real selectable option — per the user's
     * decision (Phase 7 spec §5).
     *
     * @var list<string>
     */
    private const TYPES = [
        'Fast Ball', 'Slow Ball', 'Leg Cutter', 'Off Cutter', 'Leg Spin',
        'Googly', 'Off Spin', 'Top Spin', 'Carrom Ball', 'Other',
    ];

    public function run(): void
    {
        foreach (self::TYPES as $index => $name) {
            BallType::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }
    }
}
