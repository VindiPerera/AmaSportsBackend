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
        Schema::create('judo_recent_fights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('judo_profile_id')->constrained()->cascadeOnDelete();
            $table->date('fight_date')->nullable();
            $table->string('opponent')->nullable();
            $table->string('venue')->nullable();
            $table->foreignId('weight_position_id')->nullable()->constrained();
            $table->foreignId('competition_level_id')->nullable()->constrained();
            $table->boolean('win')->default(false);
            $table->boolean('lost')->default(false);
            $table->string('place')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judo_recent_fights');
    }
};
