<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * `average`/`sr`/`economy` are derived (runs÷balls×100 etc, see
 * statMerge.ts), not typed in directly — decimal(8,2) only allows up to
 * 999999.99, which a large runs/small balls entry can overflow (e.g.
 * 5,555,555 runs off 9 balls -> sr ~61,728,388.89), crashing the save with
 * a 22003 out-of-range error. Widened well past anything a real (or
 * deliberately large test) entry could produce.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cricket_batting_stats', function (Blueprint $table) {
            $table->decimal('average', 14, 2)->nullable()->change();
            $table->decimal('sr', 14, 2)->nullable()->change();
        });

        Schema::table('cricket_bowling_stats', function (Blueprint $table) {
            $table->decimal('average', 14, 2)->nullable()->change();
            $table->decimal('economy', 14, 2)->nullable()->change();
            $table->decimal('sr', 14, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('cricket_batting_stats', function (Blueprint $table) {
            $table->decimal('average', 8, 2)->nullable()->change();
            $table->decimal('sr', 8, 2)->nullable()->change();
        });

        Schema::table('cricket_bowling_stats', function (Blueprint $table) {
            $table->decimal('average', 8, 2)->nullable()->change();
            $table->decimal('economy', 8, 2)->nullable()->change();
            $table->decimal('sr', 8, 2)->nullable()->change();
        });
    }
};
