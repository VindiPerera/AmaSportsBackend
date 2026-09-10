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
        // Repeatable "Bowling Career Stats" rows — flat, see note on the
        // batting stats migration above.
        Schema::create('soft_ball_cricket_bowling_stats', function (Blueprint $table) {
            $table->id();
            // Explicit short index name — the default auto-generated name
            // exceeds MySQL's 64-char identifier limit for this table name.
            $table->foreignId('soft_ball_cricket_profile_id')
                ->constrained(indexName: 'sbc_bowling_stats_profile_id_foreign')
                ->cascadeOnDelete();
            $table->unsignedInteger('matches')->nullable();
            $table->unsignedInteger('balls')->nullable();
            $table->unsignedInteger('runs')->nullable();
            $table->unsignedInteger('wickets')->nullable();
            $table->decimal('average', 8, 2)->nullable();
            $table->decimal('economy', 8, 2)->nullable();
            $table->unsignedInteger('three_w')->nullable();
            $table->unsignedInteger('four_w')->nullable();
            $table->unsignedInteger('five_w')->nullable();
            // e.g. "5/20"
            $table->string('career_best')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soft_ball_cricket_bowling_stats');
    }
};
