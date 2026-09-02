<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Unlike the team logos (see player_team_logos — that table exists
     * separately because player_teams is wholesale deleted-and-recreated on
     * every save), `cricket_profiles` is a stable single row per player
     * (updateOrCreate), so the college/university logo can live directly on
     * it as a plain column, uploaded via its own immediate endpoint just
     * like the player's avatar/cover photo.
     */
    public function up(): void
    {
        Schema::table('cricket_profiles', function (Blueprint $table) {
            $table->string('college_logo_path')->nullable()->after('college_university');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cricket_profiles', function (Blueprint $table) {
            $table->dropColumn('college_logo_path');
        });
    }
};
