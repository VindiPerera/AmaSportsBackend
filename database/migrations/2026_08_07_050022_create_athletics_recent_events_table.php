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
        // No `format_id` here — the spec's Recent Events table for
        // Athletics only lists Date/Age/Category/Matches/Event/Place.
        Schema::create('athletics_recent_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athletics_profile_id')->constrained()->cascadeOnDelete();
            $table->date('event_date')->nullable();
            $table->foreignId('age_category_id')->constrained();
            $table->foreignId('match_category_id')->constrained();
            $table->unsignedInteger('matches')->nullable();
            $table->foreignId('athletics_event_id')->constrained();
            $table->string('place')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athletics_recent_events');
    }
};
