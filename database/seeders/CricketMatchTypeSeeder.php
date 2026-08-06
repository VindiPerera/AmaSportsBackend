<?php

namespace Database\Seeders;

use App\Models\CricketMatchType;
use Illuminate\Database\Seeder;

class CricketMatchTypeSeeder extends Seeder
{
    /**
     * Per spec section 6.4.
     *
     * @var list<string>
     */
    private const TYPES = [
        'Six-a-side', 'T10', 'T20', '40 overs', '50 overs', 'One Day',
        'Two Day', 'Three Day', 'Four Day', 'Test', 'Champions', 'Others',
    ];

    public function run(): void
    {
        foreach (self::TYPES as $index => $name) {
            CricketMatchType::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }
    }
}
