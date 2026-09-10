<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The Cricket profile's three separate "Add" flows (Batting Career Stat,
 * Bowling Career Stat, Recent Match) are being replaced by one combined
 * per-match form — every match now carries its own Format/Category/Ground/
 * Year plus the batting AND bowling figures for that match, in one entry.
 * That entry still both (a) appends here as its own row — Recent Matches
 * stays a flat, unaggregated list — and (b) merges into the cumulative
 * Batting/Bowling Career Stats rows (unchanged merge logic, see
 * statMerge.ts), so these columns exist purely to keep each individual
 * match's full detail on file, not to replace the aggregate tables.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cricket_recent_matches', function (Blueprint $table) {
            // "Format" (age_category_id) and "Category" (format_id) — same
            // cricket_categories/cricket_divisions lookups the Career Stats
            // tables use, so this match's contribution merges into the right
            // aggregate row.
            $table->foreignId('age_category_id')->nullable()->after('cricket_profile_id')->constrained('cricket_categories');
            $table->foreignId('format_id')->nullable()->after('age_category_id')->constrained('cricket_divisions');
            $table->string('ground')->nullable()->after('opponent');
            $table->unsignedSmallInteger('year')->nullable()->after('ground');

            // Batting & Fielding detail for this match.
            $table->unsignedInteger('batting_innings')->nullable()->after('year');
            $table->boolean('not_out')->default(false)->after('runs');
            // Carries the "*" not-out marker (e.g. "76*") — feeds the
            // Career Stats HS "best of" comparison the same way a career
            // row's own hs does (see statMerge.best()).
            $table->string('hs', 20)->nullable()->after('not_out');
            $table->boolean('hundreds')->default(false)->after('sixes');
            $table->boolean('fifties')->default(false)->after('hundreds');

            // Bowling detail for this match — distinct from the batting
            // runs/balls above, and from the legacy `overs`/`maidens`
            // columns this new form doesn't collect.
            $table->unsignedInteger('bowling_innings')->nullable()->after('fifties');
            $table->unsignedInteger('bowling_balls')->nullable()->after('bowling_innings');
            $table->unsignedInteger('bowling_runs')->nullable()->after('bowling_balls');
            $table->string('bbi', 20)->nullable()->after('wickets');
            $table->string('bbm', 20)->nullable()->after('bbi');
            $table->boolean('three_w')->default(false)->after('bbm');
            $table->boolean('four_w')->default(false)->after('three_w');
            $table->boolean('five_w')->default(false)->after('four_w');
            $table->boolean('ten_w')->default(false)->after('five_w');
        });
    }

    public function down(): void
    {
        Schema::table('cricket_recent_matches', function (Blueprint $table) {
            $table->dropConstrainedForeignId('age_category_id');
            $table->dropConstrainedForeignId('format_id');
            $table->dropColumn([
                'ground', 'year', 'batting_innings', 'not_out', 'hs', 'hundreds', 'fifties',
                'bowling_innings', 'bowling_balls', 'bowling_runs', 'bbi', 'bbm',
                'three_w', 'four_w', 'five_w', 'ten_w',
            ]);
        });
    }
};
