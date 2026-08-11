<?php

namespace Database\Seeders;

use App\Models\PitchingLine;
use Illuminate\Database\Seeder;

class PitchingLineSeeder extends Seeder
{
    /**
     * The longer, authoritative 10-item list — per the user's decision to
     * use one list everywhere rather than the wireframe's two inconsistent
     * ones (Phase 7 spec §5).
     *
     * @var list<string>
     */
    private const LINES = [
        'Low Full Toss', 'Yorker', 'Over Pitch', 'Good Length', 'Just Short',
        'Short Pitch', 'Half Pitch', 'With Line', 'Leg Side', 'Out Side Off Side',
    ];

    public function run(): void
    {
        foreach (self::LINES as $index => $name) {
            PitchingLine::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }
    }
}
