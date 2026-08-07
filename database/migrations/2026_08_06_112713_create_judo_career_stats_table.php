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
        // weight_position_id / competition_level_id are independent
        // dropdowns — no mapping assumed between them (spec Phase 2 §B5,
        // pending client confirmation).
        Schema::create('judo_career_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('judo_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('format_id')->constrained();
            $table->foreignId('age_category_id')->constrained();
            $table->foreignId('match_category_id')->constrained();
            $table->foreignId('weight_position_id')->nullable()->constrained();
            $table->foreignId('competition_level_id')->nullable()->constrained();
            $table->unsignedInteger('matches')->nullable();
            $table->unsignedInteger('win')->nullable();
            $table->unsignedInteger('lost')->nullable();
            $table->unsignedInteger('third_place')->nullable();
            $table->unsignedInteger('second_place')->nullable();
            $table->unsignedInteger('champion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judo_career_stats');
    }
};
