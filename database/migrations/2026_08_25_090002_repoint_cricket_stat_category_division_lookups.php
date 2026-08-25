<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
     * Any pre-existing rows are cleared first (see the accompanying data
     * cleanup) — their old IDs pointed at the shared tables and would
     * otherwise silently resolve to the wrong cricket_categories/
     * cricket_divisions row once the FK target changes.
     */
    public function up(): void
    {
        foreach (['cricket_batting_stats', 'cricket_bowling_stats'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                // Column names, not constraint names — Schema::table()
                // resolves these to the conventional `{table}_{column}_
                // foreign` name itself.
                $table->dropForeign(['age_category_id']);
                $table->dropForeign(['format_id']);
            });

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
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['age_category_id']);
                $table->dropForeign(['format_id']);
            });

            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('format_id')->nullable(false)->change();
            });

            Schema::table($tableName, function (Blueprint $table) {
                $table->foreign('age_category_id')->references('id')->on('age_categories');
                $table->foreign('format_id')->references('id')->on('formats');
            });
        }
    }
};
