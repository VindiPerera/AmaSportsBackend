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
        Schema::create('swimming_recent_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('swimming_profile_id')->constrained()->cascadeOnDelete();
            $table->date('event_date')->nullable();
            $table->foreignId('age_category_id')->constrained();
            $table->foreignId('match_category_id')->constrained();
            $table->unsignedInteger('matches')->nullable();
            $table->foreignId('swimming_event_id')->constrained();
            // "Performance (Time)" — free-text time string.
            $table->string('performance_time')->nullable();
            $table->string('place')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swimming_recent_events');
    }
};
