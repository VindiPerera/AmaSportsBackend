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
        // Backs both Live Score (spec 5) and, later, an admin match editor.
        // `live_score` is a free-form JSON blob so per-sport score shapes
        // (starting with Cricket) don't require a schema change to add.
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_id')->constrained();
            $table->foreignId('home_team_id')->constrained('teams');
            $table->foreignId('away_team_id')->constrained('teams');
            $table->enum('status', ['upcoming', 'live', 'finished'])->default('upcoming');
            $table->dateTime('scheduled_at')->nullable();
            $table->string('venue')->nullable();
            $table->json('live_score')->nullable();
            // Admin pastes this in later; player-side just embeds it.
            $table->string('youtube_stream_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
