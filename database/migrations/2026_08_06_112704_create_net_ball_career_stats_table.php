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
        // Spec Phase 2 §B2 lists "Win/Lost" and "Won/Lost" as two separate
        // column pairs — kept literally as two pairs per client confirmation
        // (matches the Hockey precedent from Phase 1).
        Schema::create('net_ball_career_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('net_ball_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('format_id')->constrained();
            $table->foreignId('age_category_id')->constrained();
            $table->foreignId('match_category_id')->constrained();
            $table->unsignedInteger('matches')->nullable();
            $table->unsignedInteger('matches_won')->nullable();
            $table->unsignedInteger('matches_lost')->nullable();
            $table->unsignedInteger('goals')->nullable();
            $table->unsignedInteger('attempts')->nullable();
            $table->decimal('goal_accuracy', 5, 2)->nullable();
            $table->unsignedInteger('result_won')->nullable();
            $table->unsignedInteger('result_lost')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('net_ball_career_stats');
    }
};
