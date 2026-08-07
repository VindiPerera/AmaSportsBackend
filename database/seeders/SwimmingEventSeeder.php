<?php

namespace Database\Seeders;

use App\Models\SwimmingEvent;
use Illuminate\Database\Seeder;

class SwimmingEventSeeder extends Seeder
{
    /**
     * Per Phase 3 spec §C2.
     *
     * @var list<string>
     */
    private const EVENTS = [
        '50m Freestyle', '100m Freestyle', '200m Freestyle', '400m Freestyle', '800m Freestyle',
        '1500m Freestyle', '100m Backstroke', '200m Backstroke', '100m Butterfly', '200m Butterfly',
        '100m Individual Medley', '200m Individual Medley', '400m Individual Medley',
        '4x100m Freestyle Relay', '4x200m Freestyle Relay', '4x100m Medley Relay',
        '4x100m Mix Medley', '10km Open Water',
    ];

    public function run(): void
    {
        foreach (self::EVENTS as $index => $name) {
            SwimmingEvent::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }
    }
}
