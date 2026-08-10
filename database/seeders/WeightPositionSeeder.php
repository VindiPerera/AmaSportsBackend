<?php

namespace Database\Seeders;

use App\Models\WeightPosition;
use Illuminate\Database\Seeder;

class WeightPositionSeeder extends Seeder
{
    /**
     * Per Phase 2 spec §B5 — kept in submission order (not sorted
     * numerically) since the client hasn't confirmed a canonical order yet.
     *
     * @var list<string>
     */
    private const WEIGHTS = [
        '35', '36', '40', '44', '45', '48', '50', '53', '55', '57', '60', '63', '+63', '66',
        '+66', '70', '+70', '73', '78', '+78', '81', '+81', '90', '100', '+100',
    ];

    public function run(): void
    {
        foreach (self::WEIGHTS as $index => $label) {
            WeightPosition::updateOrCreate(['label' => $label], ['sort_order' => $index]);
        }
    }
}
