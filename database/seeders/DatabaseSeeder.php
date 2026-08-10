<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with a small set of demo accounts
     * for local development and manual API testing.
     */
    public function run(): void
    {
        $this->call([
            SportSeeder::class,
            FormatSeeder::class,
            AgeCategorySeeder::class,
            MatchCategorySeeder::class,
            CricketMatchTypeSeeder::class,
            WeightPositionSeeder::class,
            CompetitionLevelSeeder::class,
            AthleticsEventSeeder::class,
            SwimmingEventSeeder::class,
            BoxingWeightClassSeeder::class,
            KarateStyleSeeder::class,
        ]);

        User::factory()->coach()->create([
            'name' => 'Demo Coach',
            'email' => 'coach@amasports.app',
        ]);

        User::factory()->student()->create([
            'name' => 'Demo Student',
            'email' => 'student@amasports.app',
        ]);

        User::factory()->admin()->create([
            'name' => 'Demo Admin',
            'email' => 'admin@amasports.app',
        ]);

        User::factory(10)->student()->create();
    }
}
