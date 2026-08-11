<?php

namespace Database\Seeders;

use App\Models\FieldPosition;
use Illuminate\Database\Seeder;

class FieldPositionSeeder extends Seeder
{
    /**
     * Per Phase 7 spec §2.
     *
     * @var list<string>
     */
    private const POSITIONS = [
        'Wicket Keeper', '1st Slip', '2nd Slip', '3rd Slip', 'Gully', 'Silly Point',
        'Short Leg', 'Point', 'Cover Point', 'Cover', 'Backward Point', 'Mid Off',
        'Mid On', 'Square Leg', 'Fine Leg', 'Third Man', 'Long On', 'Deep Point',
        'Deep Mid Wicket', 'Mid Wicket', 'Sweep Cover',
    ];

    public function run(): void
    {
        foreach (self::POSITIONS as $index => $name) {
            FieldPosition::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }
    }
}
