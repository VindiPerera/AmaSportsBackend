<?php

namespace Database\Seeders;

use App\Models\AthleticsEvent;
use Illuminate\Database\Seeder;

class AthleticsEventSeeder extends Seeder
{
    /**
     * Per Phase 3 spec §C1, grouped by type.
     *
     * @var array<string, list<string>>
     */
    private const EVENTS = [
        AthleticsEvent::TYPE_RUNNING => ['100m', '200m', '400m', '800m', '10km', 'Half Marathon', 'Full Marathon'],
        AthleticsEvent::TYPE_JUMPING => ['High Jump', 'Pole Vault', 'Long Jump', 'Triple Jump'],
        AthleticsEvent::TYPE_THROWING => ['Shot Put', 'Discus Throw', 'Hammer Throw', 'Javelin Throw'],
        AthleticsEvent::TYPE_WALKING => ['Race Walking'],
    ];

    public function run(): void
    {
        $sortOrder = 0;
        foreach (self::EVENTS as $type => $names) {
            foreach ($names as $name) {
                AthleticsEvent::updateOrCreate(['name' => $name], ['type' => $type, 'sort_order' => $sortOrder++]);
            }
        }
    }
}
