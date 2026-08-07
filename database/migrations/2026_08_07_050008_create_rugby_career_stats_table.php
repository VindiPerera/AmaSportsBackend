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
        // `tries` (not `try` — avoids ambiguity with the PHP/SQL keyword).
        Schema::create('rugby_career_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rugby_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('format_id')->constrained();
            $table->foreignId('age_category_id')->constrained();
            $table->foreignId('match_category_id')->constrained();
            $table->unsignedInteger('matches')->nullable();
            $table->unsignedInteger('win')->nullable();
            $table->unsignedInteger('lost')->nullable();
            $table->unsignedInteger('tries')->nullable();
            $table->unsignedInteger('conversion')->nullable();
            $table->unsignedInteger('penalty_kick')->nullable();
            $table->unsignedInteger('drop_goal')->nullable();
            $table->unsignedInteger('yellow_card')->nullable();
            $table->unsignedInteger('red_card')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rugby_career_stats');
    }
};
