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
        // Auto-generated FK/index names on this table exceed MySQL's 64-char
        // identifier limit (long table name + long column name), so the
        // profile FK is named explicitly.
        Schema::create('beach_volleyball_career_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beach_volleyball_profile_id');
            $table->foreign('beach_volleyball_profile_id', 'bv_career_stats_profile_fk')
                ->references('id')->on('beach_volleyball_profiles')->cascadeOnDelete();
            $table->foreignId('format_id')->constrained();
            $table->foreignId('age_category_id')->constrained();
            $table->foreignId('match_category_id')->constrained();
            $table->unsignedInteger('matches')->nullable();
            $table->unsignedInteger('win')->nullable();
            $table->unsignedInteger('lost')->nullable();
            $table->unsignedInteger('passes')->nullable();
            $table->unsignedInteger('setting')->nullable();
            $table->unsignedInteger('serve')->nullable();
            $table->unsignedInteger('attacking')->nullable();
            $table->unsignedInteger('blocking')->nullable();
            $table->unsignedInteger('digging')->nullable();
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
        Schema::dropIfExists('beach_volleyball_career_stats');
    }
};
