<?php

namespace Database\Seeders;

use App\Models\KarateStyle;
use Illuminate\Database\Seeder;

class KarateStyleSeeder extends Seeder
{
    /**
     * Per Phase 3 spec §C7.
     *
     * @var list<string>
     */
    private const STYLES = [
        'Shotokan', 'Goju-ryu', 'Uechi-ryu', 'Wado-ryu', 'Shito-ryu', 'Ashihara',
        'Chito-ryu', 'Enshin', 'Shorin-ryu', 'Kishimoto-di', 'Kyokushin',
    ];

    public function run(): void
    {
        foreach (self::STYLES as $index => $name) {
            KarateStyle::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }
    }
}
