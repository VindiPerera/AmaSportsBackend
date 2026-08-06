<?php

namespace Database\Seeders;

use App\Models\Format;
use Illuminate\Database\Seeder;

class FormatSeeder extends Seeder
{
    /**
     * Per spec section 6.4.
     *
     * @var list<string>
     */
    private const FORMATS = [
        'Div I', 'Div II', 'Div III', 'Div IV', 'Div V', 'Zonal', 'District',
        'Province', 'National', 'Premier', 'Other', 'Academy', 'Super League',
    ];

    public function run(): void
    {
        foreach (self::FORMATS as $index => $name) {
            Format::updateOrCreate(['name' => $name], ['sort_order' => $index]);
        }
    }
}
