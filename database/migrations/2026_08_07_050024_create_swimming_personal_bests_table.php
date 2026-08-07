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
        Schema::create('swimming_personal_bests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('swimming_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('swimming_event_id')->constrained();
            // Time string, e.g. "1:02.34".
            $table->string('personal_best')->nullable();
            $table->timestamps();

            $table->unique(['swimming_profile_id', 'swimming_event_id'], 'swimming_personal_bests_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swimming_personal_bests');
    }
};
