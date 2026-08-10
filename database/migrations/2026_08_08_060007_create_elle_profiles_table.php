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
        Schema::create('elle_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->unique()->constrained()->cascadeOnDelete();
            $table->date('born')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('height')->nullable();
            $table->enum('dominant_hand', ['right', 'left'])->nullable();
            $table->string('player_position')->nullable();
            $table->string('college_university')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elle_profiles');
    }
};
