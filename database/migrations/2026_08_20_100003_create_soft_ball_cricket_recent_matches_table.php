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
        // Repeatable "Recent Matches" rows — mixes a batting line (runs,
        // balls, average) with a bowling line (balls, runs, wickets) plus
        // fielding, matching the single combined row on the reference sheet.
        Schema::create('soft_ball_cricket_recent_matches', function (Blueprint $table) {
            $table->id();
            // Explicit short index name — the default auto-generated name
            // exceeds MySQL's 64-char identifier limit for this table name.
            $table->foreignId('soft_ball_cricket_profile_id')
                ->constrained(indexName: 'sbc_recent_matches_profile_id_foreign')
                ->cascadeOnDelete();
            $table->date('match_date')->nullable();
            $table->string('opponent')->nullable();
            $table->boolean('won')->default(false);
            $table->boolean('lost')->default(false);
            // Batting line.
            $table->unsignedInteger('runs')->nullable();
            $table->unsignedInteger('balls')->nullable();
            $table->string('average')->nullable();
            // Bowling line.
            $table->unsignedInteger('bowling_balls')->nullable();
            $table->unsignedInteger('bowling_runs')->nullable();
            $table->unsignedInteger('wickets')->nullable();
            $table->unsignedInteger('catches')->nullable();
            $table->unsignedInteger('stumpings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soft_ball_cricket_recent_matches');
    }
};
