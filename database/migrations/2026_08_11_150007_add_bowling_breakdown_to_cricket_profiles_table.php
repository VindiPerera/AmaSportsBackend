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
        // Phase 7 (Bowling Analyses Report) — a player-entered, career-to-
        // date breakdown of deliveries by Pitching Line and Ball Type.
        // Deliberately a single free-form JSON map per player (id => count)
        // rather than per-bowling-stat-row or per-delivery event tracking:
        // this feature is self-reported by the player (not a live
        // ball-by-ball admin scoring tool — see Phase 7 clarification), so
        // there's no delivery event stream to aggregate from. Same JSON-blob
        // pattern already used for matches.live_score.
        Schema::table('cricket_profiles', function (Blueprint $table) {
            $table->json('pitching_line_breakdown')->nullable()->after('college_university');
            $table->json('ball_type_breakdown')->nullable()->after('pitching_line_breakdown');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cricket_profiles', function (Blueprint $table) {
            $table->dropColumn(['pitching_line_breakdown', 'ball_type_breakdown']);
        });
    }
};
