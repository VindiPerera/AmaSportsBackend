<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Reproduces the production failure: roll back to just before the
 * cricket_categories/cricket_divisions repoint migration, insert a stale
 * pre-feature batting row using the OLD shared age_categories/formats ids
 * (exactly what a real deployed environment had), then re-migrate and
 * confirm it completes without the FK-add error that hit production, and
 * without leaving the now-orphaned legacy row behind.
 */
class CricketCategoryMigrationRepairTest extends TestCase
{
    use RefreshDatabase;

    public function test_repoint_migration_succeeds_against_a_database_with_stale_legacy_rows(): void
    {
        // RefreshDatabase already ran every migration. Roll back to just
        // before the repoint migration (and the one after it that depends
        // on its nullable change) to reproduce the pre-fix schema state.
        $this->artisan('migrate:rollback', ['--step' => 2])->run();

        $this->assertTrue(Schema::hasTable('age_categories'));
        $this->assertTrue(Schema::hasTable('cricket_batting_stats'));

        $oldAgeCategoryId = DB::table('age_categories')->insertGetId(['name' => 'U13', 'sort_order' => 2]);
        $oldFormatId = DB::table('formats')->insertGetId(['name' => 'Div IV', 'sort_order' => 4]);
        $matchCategoryId = DB::table('match_categories')->insertGetId(['name' => 'Club', 'sort_order' => 1]);
        $player = Player::create(['user_id' => User::factory()->create()->id]);
        $profileId = DB::table('cricket_profiles')->insertGetId(['player_id' => $player->id]);

        DB::table('cricket_batting_stats')->insert([
            'cricket_profile_id' => $profileId,
            'age_category_id' => $oldAgeCategoryId,
            'format_id' => $oldFormatId,
            'match_category_id' => $matchCategoryId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->artisan('migrate')->assertSuccessful();

        // The stale legacy row (unrecoverable — its ids meant something in
        // a table this migration no longer points at) is gone rather than
        // left dangling or silently misattributed to an unrelated row.
        $this->assertSame(0, DB::table('cricket_batting_stats')->count());

        // The new FK genuinely lands on cricket_categories, and it's seeded.
        $this->assertGreaterThan(0, DB::table('cricket_categories')->count());
        $foreignKeys = collect(Schema::getForeignKeys('cricket_batting_stats'));
        $ageCategoryFk = $foreignKeys->first(fn ($fk) => $fk['columns'] === ['age_category_id']);
        $this->assertSame('cricket_categories', $ageCategoryFk['foreign_table']);
    }

    public function test_repoint_migration_is_idempotent_on_a_second_run(): void
    {
        // Everything already migrated cleanly (this test's own setUp). A
        // second `up()` must be a total no-op — no error, no data loss.
        $player = Player::create(['user_id' => User::factory()->create()->id]);
        $profileId = DB::table('cricket_profiles')->insertGetId(['player_id' => $player->id]);
        $categoryId = DB::table('cricket_categories')->value('id');

        DB::table('cricket_batting_stats')->insert([
            'cricket_profile_id' => $profileId,
            'age_category_id' => $categoryId,
            'format_id' => null,
            'match_category_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $migration = require database_path(
            'migrations/2026_08_25_090002_repoint_cricket_stat_category_division_lookups.php'
        );
        $migration->up();

        $this->assertSame(1, DB::table('cricket_batting_stats')->count());
    }
}
