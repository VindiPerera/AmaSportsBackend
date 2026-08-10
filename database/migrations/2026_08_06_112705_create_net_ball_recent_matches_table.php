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
        Schema::create('net_ball_recent_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('net_ball_profile_id')->constrained()->cascadeOnDelete();
            $table->date('match_date')->nullable();
            $table->string('opponent')->nullable();
            $table->string('venue')->nullable();
            $table->unsignedInteger('goals')->nullable();
            $table->unsignedInteger('attempts')->nullable();
            $table->decimal('goal_accuracy', 5, 2)->nullable();
            $table->boolean('win')->default(false);
            $table->boolean('lost')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('net_ball_recent_matches');
    }
};
