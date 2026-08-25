<?php

use Database\Seeders\CricketCategorySeeder;
use Database\Seeders\CricketDivisionSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * `age_category_id`/`format_id` on the two Career Stat tables now point
     * at the new cricket-only `cricket_categories`/`cricket_divisions`
     * tables instead of the shared `age_categories`/`formats` lookups —
     * column names are kept as-is (nothing on the frontend or in the
     * request/resource layer needs to change) but their meaning for these
     * two tables only changes to Cricket's own Category+Division picker.
     * `format_id` also becomes nullable: Division is only offered for a few
     * Categories (see CareerStatAddModal), so most rows won't have one.
     *
     * Fully self-contained and safe to run standalone via `php artisan
     * migrate` (no separate `db:seed` step required first, unlike the local
     * dev setup this was first written against) and safe to retry after a
     * partial failure or re-run after already succeeding — every step
     * checks the current state (via Schema::getForeignKeys(), driver-
     * agnostic) before acting. See the deploy incident this fixed: a fresh
     * environment hit the age_category_id FK add before cricket_categories
     * had any rows, because seeding wasn't part of the migration itself.
     */
    public function up(): void
    {
        // Ensure the canonical lookup rows exist before anything points a
        // foreign key at them — idempotent (updateOrCreate-by-name).
        (new CricketCategorySeeder())->run();
        (new CricketDivisionSeeder())->run();

        foreach (['cricket_batting_stats', 'cricket_bowling_stats'] as $tableName) {
            // Already migrated this table on a previous run — nothing left
            // to do. Checked by referenced table, not just constraint
            // presence, so a partial failure (FK dropped but not yet
            // re-added) is correctly treated as "not done" and retried.
            if ($this->foreignReferences($tableName, 'age_category_id', 'cricket_categories')) {
                continue;
            }

            // Getting here means age_category_id still means whatever the
            // OLD shared age_categories table said (or the FK is currently
            // missing mid-retry) — either way every row present right now
            // predates this migration and carries no valid mapping into
            // cricket_categories/cricket_divisions' id space, so it's
            // cleared rather than left to violate the incoming FK (or
            // worse, silently land on an unrelated row that happens to
            // share the same id).
            DB::table($tableName)->delete();

            $this->dropForeignIfExists($tableName, 'age_category_id');
            $this->dropForeignIfExists($tableName, 'format_id');

            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('format_id')->nullable()->change();
            });

            Schema::table($tableName, function (Blueprint $table) {
                $table->foreign('age_category_id')->references('id')->on('cricket_categories');
                $table->foreign('format_id')->references('id')->on('cricket_divisions');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['cricket_batting_stats', 'cricket_bowling_stats'] as $tableName) {
            $this->dropForeignIfExists($tableName, 'age_category_id');
            $this->dropForeignIfExists($tableName, 'format_id');

            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('format_id')->nullable(false)->change();
            });

            Schema::table($tableName, function (Blueprint $table) {
                $table->foreign('age_category_id')->references('id')->on('age_categories');
                $table->foreign('format_id')->references('id')->on('formats');
            });
        }
    }

    /**
     * @return array{name: string, columns: list<string>, foreign_table: string}|null
     */
    private function foreignKey(string $table, string $column): ?array
    {
        foreach (Schema::getForeignKeys($table) as $foreignKey) {
            if ($foreignKey['columns'] === [$column]) {
                return $foreignKey;
            }
        }

        return null;
    }

    private function foreignReferences(string $table, string $column, string $referencedTable): bool
    {
        $foreignKey = $this->foreignKey($table, $column);

        return $foreignKey !== null && $foreignKey['foreign_table'] === $referencedTable;
    }

    private function dropForeignIfExists(string $table, string $column): void
    {
        if ($this->foreignKey($table, $column) !== null) {
            // Column-name array form (not the constraint name) — SQLite's
            // grammar (used in tests) only supports dropping by column,
            // rebuilding the table under the hood; MySQL resolves the same
            // array to its conventional `{table}_{column}_foreign` name.
            Schema::table($table, fn (Blueprint $t) => $t->dropForeign([$column]));
        }
    }
};
