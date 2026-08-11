<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Phase 7 (Fielding Analyses) detail on top of the existing
        // catches/stumpings columns — `stumpings` already means "successful"
        // stumpings, so only the missed counterpart is new here. All
        // nullable, additive; existing rows and the public API are
        // unaffected until the player fills these in.
        Schema::table('cricket_batting_stats', function (Blueprint $table) {
            $table->unsignedInteger('run_outs')->nullable()->after('stumpings');
            $table->unsignedInteger('direct_hits')->nullable()->after('run_outs');
            $table->unsignedInteger('runs_saved')->nullable()->after('direct_hits');
            $table->unsignedInteger('runs_giving')->nullable()->after('runs_saved');
            $table->unsignedInteger('stumps_missing')->nullable()->after('runs_giving');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cricket_batting_stats', function (Blueprint $table) {
            $table->dropColumn(['run_outs', 'direct_hits', 'runs_saved', 'runs_giving', 'stumps_missing']);
        });
    }
};
