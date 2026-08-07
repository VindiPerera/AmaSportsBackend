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
        // `weight_class_id` here is the player's current/primary class;
        // Career Status / Recent Fight rows carry their own too, since a
        // boxer's class can change across their career.
        Schema::create('boxing_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->unique()->constrained()->cascadeOnDelete();
            $table->date('born')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->foreignId('weight_class_id')->nullable()->constrained('boxing_weight_classes');
            $table->string('current_ranking')->nullable();
            $table->string('college_university')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boxing_profiles');
    }
};
