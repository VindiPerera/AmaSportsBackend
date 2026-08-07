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
        // Repeatable "career status" rows (spec Phase 2 §B1).
        Schema::create('base_ball_career_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_ball_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('format_id')->constrained();
            $table->foreignId('age_category_id')->constrained();
            $table->foreignId('match_category_id')->constrained();
            $table->unsignedInteger('matches')->nullable();
            // NT = Not-out / No-decision.
            $table->unsignedInteger('nt')->nullable();
            $table->unsignedInteger('at_bats')->nullable();
            $table->unsignedInteger('runs')->nullable();
            $table->unsignedInteger('hits')->nullable();
            $table->unsignedInteger('rbi')->nullable();
            $table->unsignedInteger('won')->nullable();
            $table->unsignedInteger('lost')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_ball_career_stats');
    }
};
