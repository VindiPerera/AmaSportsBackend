<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Admin-defined achievement templates ("Score 100 runs in a match" -> a
 * postable badge). Cricket-only for now (see CricketMetricEvaluator), but
 * `sport_id` + the free-form `metric_key` string (rather than a fixed enum
 * or a hard cricket foreign key) keep it sport-agnostic on paper — adding a
 * new sport later is just registering another MetricEvaluator that
 * recognizes its own metric key prefix (e.g. "football_goals"), with no
 * schema change here.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_id')->constrained();
            $table->string('title');
            $table->text('description')->nullable();
            // Which MetricEvaluator (and which stat within it) this checks —
            // e.g. "cricket_runs", "cricket_wickets". Not a foreign key: the
            // set of valid values is defined in code (AchievementService's
            // registered evaluators), not a database table, since it's a
            // capability check, not a lookup list.
            $table->string('metric_key');
            $table->unsignedInteger('threshold');
            // Ionicons name (e.g. "trophy", "flash") + a hex color — a
            // lightweight "badge" look without needing image upload/storage.
            $table->string('icon')->default('trophy');
            $table->string('color', 7)->default('#F59E0B');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
