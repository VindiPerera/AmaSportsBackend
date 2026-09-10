<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Same overflow risk as cricket's average/sr/economy (see
 * 2026_09_07_171403_widen_cricket_average_sr_economy_columns.php) —
 * `goal_accuracy` is a free-typed numeric field (StoreNetBallProfileRequest
 * only validates it as 'nullable','numeric', no max) but was capped at
 * decimal(5,2) (999.99), so any large entry crashes the save with a 22003
 * out-of-range error instead of just saving.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('net_ball_career_stats', function (Blueprint $table) {
            $table->decimal('goal_accuracy', 14, 2)->nullable()->change();
        });

        Schema::table('net_ball_recent_matches', function (Blueprint $table) {
            $table->decimal('goal_accuracy', 14, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('net_ball_career_stats', function (Blueprint $table) {
            $table->decimal('goal_accuracy', 5, 2)->nullable()->change();
        });

        Schema::table('net_ball_recent_matches', function (Blueprint $table) {
            $table->decimal('goal_accuracy', 5, 2)->nullable()->change();
        });
    }
};
