<?php

namespace Database\Seeders;

use App\Models\DropReason;
use Illuminate\Database\Seeder;

class DropReasonSeeder extends Seeder
{
    /**
     * "How to Drop" — per Phase 7 spec §2.
     *
     * @var list<string>
     */
    private const REASONS = [
        'Direct Hand', 'Below Knee', 'Over Chest', 'Right Side', 'Left Side',
        'Drive Right Side', 'Drive Left Side', 'Drive Right Side One Hand',
        'Drive Left Side One Hand', 'Running Forward', 'Running Right Side',
        'Running Left Side', 'Running Backward', 'Near Boundary Line',
    ];

    public function run(): void
    {
        foreach (self::REASONS as $index => $name) {
            DropReason::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }
    }
}
