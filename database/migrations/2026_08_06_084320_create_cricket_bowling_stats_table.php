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
        // Repeatable "Bowling" rows (spec 6.2).
        Schema::create('cricket_bowling_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cricket_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('format_id')->constrained();
            $table->foreignId('age_category_id')->constrained();
            $table->foreignId('match_category_id')->constrained();
            $table->foreignId('cricket_match_type_id')->nullable()->constrained();
            $table->unsignedInteger('matches')->nullable();
            $table->unsignedInteger('innings')->nullable();
            $table->unsignedInteger('balls')->nullable();
            $table->unsignedInteger('runs')->nullable();
            $table->unsignedInteger('wickets')->nullable();
            // e.g. "4/25"
            $table->string('bbi')->nullable();
            $table->string('bbm')->nullable();
            $table->decimal('average', 8, 2)->nullable();
            $table->decimal('economy', 8, 2)->nullable();
            $table->decimal('sr', 8, 2)->nullable();
            $table->unsignedInteger('four_w')->nullable();
            $table->unsignedInteger('five_w')->nullable();
            $table->unsignedInteger('ten_w')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cricket_bowling_stats');
    }
};
