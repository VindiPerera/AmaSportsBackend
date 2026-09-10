<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * A logo image for one of the free-text team affiliations in
     * `player_teams` (e.g. "NCC", "School XI"). Kept as its own table,
     * keyed by team name, rather than a column on `player_teams` — that
     * table is wholesale deleted-and-recreated on every profile save (see
     * CricketProfileController::update()), which would force re-uploading
     * every logo on every save; this table is managed independently via its
     * own immediate-upload endpoint (same pattern as the player's own
     * avatar/cover photo), so a logo survives profile saves untouched.
     */
    public function up(): void
    {
        Schema::create('player_team_logos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sport_id')->constrained()->cascadeOnDelete();
            $table->string('team_name');
            $table->string('logo_path');
            $table->timestamps();

            $table->unique(['player_id', 'sport_id', 'team_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_team_logos');
    }
};
