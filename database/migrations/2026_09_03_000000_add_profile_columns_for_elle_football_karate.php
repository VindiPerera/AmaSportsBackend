<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Elle
        if (Schema::hasTable('elle_career_stats') && ! Schema::hasColumn('elle_career_stats', 'total_balls')) {
            Schema::table('elle_career_stats', function (Blueprint $table) {
                $table->unsignedInteger('total_balls')->nullable()->after('lost');
            });
        }
        if (Schema::hasTable('elle_recent_matches') && ! Schema::hasColumn('elle_recent_matches', 'total_balls')) {
            Schema::table('elle_recent_matches', function (Blueprint $table) {
                $table->unsignedInteger('total_balls')->nullable()->after('lost');
            });
        }

        // Football
        if (Schema::hasTable('football_career_stats') && ! Schema::hasColumn('football_career_stats', 'play_position')) {
            Schema::table('football_career_stats', function (Blueprint $table) {
                $table->string('play_position', 100)->nullable()->after('match_category_id');
            });
        }
        if (Schema::hasTable('football_recent_matches') && ! Schema::hasColumn('football_recent_matches', 'play_position')) {
            Schema::table('football_recent_matches', function (Blueprint $table) {
                $table->string('play_position', 100)->nullable()->after('venue');
            });
        }

        // Karate
        if (Schema::hasTable('karate_career_stats') && ! Schema::hasColumn('karate_career_stats', 'style')) {
            Schema::table('karate_career_stats', function (Blueprint $table) {
                $table->string('style', 100)->nullable()->after('match_category_id');
            });
        }
        if (Schema::hasTable('karate_recent_matches') && ! Schema::hasColumn('karate_recent_matches', 'style')) {
            Schema::table('karate_recent_matches', function (Blueprint $table) {
                $table->string('style', 100)->nullable()->after('venue');
            });
        }

        // Rugby
        if (Schema::hasTable('rugby_career_stats') && ! Schema::hasColumn('rugby_career_stats', 'play_position')) {
            Schema::table('rugby_career_stats', function (Blueprint $table) {
                $table->string('play_position', 100)->nullable()->after('match_category_id');
            });
        }
        if (Schema::hasTable('rugby_recent_matches') && ! Schema::hasColumn('rugby_recent_matches', 'venue')) {
            Schema::table('rugby_recent_matches', function (Blueprint $table) {
                $table->string('venue', 255)->nullable()->after('opponent');
            });
        }
        if (Schema::hasTable('rugby_recent_matches') && ! Schema::hasColumn('rugby_recent_matches', 'play_position')) {
            Schema::table('rugby_recent_matches', function (Blueprint $table) {
                $table->string('play_position', 100)->nullable()->after('venue');
            });
        }

        // Swimming
        if (Schema::hasTable('swimming_recent_events') && ! Schema::hasColumn('swimming_recent_events', 'format_id')) {
            Schema::table('swimming_recent_events', function (Blueprint $table) {
                $table->foreignId('format_id')->nullable()->after('event_date')->constrained();
            });
        }

        // Kabadi
        if (Schema::hasTable('kabadi_career_stats') && ! Schema::hasColumn('kabadi_career_stats', 'tpe')) {
            Schema::table('kabadi_career_stats', function (Blueprint $table) {
                $table->unsignedInteger('tpe')->nullable()->after('lost');
            });
        }
        if (Schema::hasTable('kabadi_recent_matches') && ! Schema::hasColumn('kabadi_recent_matches', 'tpe')) {
            Schema::table('kabadi_recent_matches', function (Blueprint $table) {
                $table->unsignedInteger('tpe')->nullable()->after('lost');
            });
        }

        // Basketball
        if (Schema::hasTable('basketball_career_stats') && ! Schema::hasColumn('basketball_career_stats', 'play_position')) {
            Schema::table('basketball_career_stats', function (Blueprint $table) {
                $table->string('play_position', 100)->nullable()->after('match_category_id');
            });
        }
        if (Schema::hasTable('basketball_recent_matches') && ! Schema::hasColumn('basketball_recent_matches', 'play_position')) {
            Schema::table('basketball_recent_matches', function (Blueprint $table) {
                $table->string('play_position', 100)->nullable()->after('venue');
            });
        }

        // Judo Overview
        if (Schema::hasTable('judo_profiles')) {
            if (! Schema::hasColumn('judo_profiles', 'weight_position_id')) {
                Schema::table('judo_profiles', function (Blueprint $table) {
                    $table->foreignId('weight_position_id')->nullable()->after('weight')->constrained();
                });
            }
            if (! Schema::hasColumn('judo_profiles', 'competition_level_id')) {
                Schema::table('judo_profiles', function (Blueprint $table) {
                    $table->foreignId('competition_level_id')->nullable()->after('weight_position_id')->constrained();
                });
            }
        }

        // Athletics
        if (Schema::hasTable('athletics_career_stats') && ! Schema::hasColumn('athletics_career_stats', 'personal_best')) {
            Schema::table('athletics_career_stats', function (Blueprint $table) {
                $table->string('personal_best', 50)->nullable()->after('athletics_event_id');
            });
        }
        if (Schema::hasTable('athletics_recent_events')) {
            if (! Schema::hasColumn('athletics_recent_events', 'format_id')) {
                Schema::table('athletics_recent_events', function (Blueprint $table) {
                    $table->foreignId('format_id')->nullable()->after('event_date')->constrained();
                });
            }
            if (! Schema::hasColumn('athletics_recent_events', 'personal_best')) {
                Schema::table('athletics_recent_events', function (Blueprint $table) {
                    $table->string('personal_best', 50)->nullable()->after('athletics_event_id');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('elle_career_stats') && Schema::hasColumn('elle_career_stats', 'total_balls')) {
            Schema::table('elle_career_stats', fn (Blueprint $table) => $table->dropColumn('total_balls'));
        }
        if (Schema::hasTable('elle_recent_matches') && Schema::hasColumn('elle_recent_matches', 'total_balls')) {
            Schema::table('elle_recent_matches', fn (Blueprint $table) => $table->dropColumn('total_balls'));
        }

        if (Schema::hasTable('football_career_stats') && Schema::hasColumn('football_career_stats', 'play_position')) {
            Schema::table('football_career_stats', fn (Blueprint $table) => $table->dropColumn('play_position'));
        }
        if (Schema::hasTable('football_recent_matches') && Schema::hasColumn('football_recent_matches', 'play_position')) {
            Schema::table('football_recent_matches', fn (Blueprint $table) => $table->dropColumn('play_position'));
        }

        if (Schema::hasTable('karate_career_stats') && Schema::hasColumn('karate_career_stats', 'style')) {
            Schema::table('karate_career_stats', fn (Blueprint $table) => $table->dropColumn('style'));
        }
        if (Schema::hasTable('karate_recent_matches') && Schema::hasColumn('karate_recent_matches', 'style')) {
            Schema::table('karate_recent_matches', fn (Blueprint $table) => $table->dropColumn('style'));
        }

        if (Schema::hasTable('rugby_career_stats') && Schema::hasColumn('rugby_career_stats', 'play_position')) {
            Schema::table('rugby_career_stats', fn (Blueprint $table) => $table->dropColumn('play_position'));
        }
        if (Schema::hasTable('rugby_recent_matches') && Schema::hasColumn('rugby_recent_matches', 'play_position')) {
            Schema::table('rugby_recent_matches', fn (Blueprint $table) => $table->dropColumn('play_position'));
        }
        if (Schema::hasTable('rugby_recent_matches') && Schema::hasColumn('rugby_recent_matches', 'venue')) {
            Schema::table('rugby_recent_matches', fn (Blueprint $table) => $table->dropColumn('venue'));
        }

        if (Schema::hasTable('swimming_recent_events') && Schema::hasColumn('swimming_recent_events', 'format_id')) {
            Schema::table('swimming_recent_events', fn (Blueprint $table) => $table->dropConstrainedForeignId('format_id'));
        }

        if (Schema::hasTable('kabadi_career_stats') && Schema::hasColumn('kabadi_career_stats', 'tpe')) {
            Schema::table('kabadi_career_stats', fn (Blueprint $table) => $table->dropColumn('tpe'));
        }
        if (Schema::hasTable('kabadi_recent_matches') && Schema::hasColumn('kabadi_recent_matches', 'tpe')) {
            Schema::table('kabadi_recent_matches', fn (Blueprint $table) => $table->dropColumn('tpe'));
        }

        if (Schema::hasTable('basketball_career_stats') && Schema::hasColumn('basketball_career_stats', 'play_position')) {
            Schema::table('basketball_career_stats', fn (Blueprint $table) => $table->dropColumn('play_position'));
        }
        if (Schema::hasTable('basketball_recent_matches') && Schema::hasColumn('basketball_recent_matches', 'play_position')) {
            Schema::table('basketball_recent_matches', fn (Blueprint $table) => $table->dropColumn('play_position'));
        }

        if (Schema::hasTable('judo_profiles')) {
            if (Schema::hasColumn('judo_profiles', 'competition_level_id')) {
                Schema::table('judo_profiles', fn (Blueprint $table) => $table->dropConstrainedForeignId('competition_level_id'));
            }
            if (Schema::hasColumn('judo_profiles', 'weight_position_id')) {
                Schema::table('judo_profiles', fn (Blueprint $table) => $table->dropConstrainedForeignId('weight_position_id'));
            }
        }

        if (Schema::hasTable('athletics_career_stats') && Schema::hasColumn('athletics_career_stats', 'personal_best')) {
            Schema::table('athletics_career_stats', fn (Blueprint $table) => $table->dropColumn('personal_best'));
        }
        if (Schema::hasTable('athletics_recent_events')) {
            if (Schema::hasColumn('athletics_recent_events', 'personal_best')) {
                Schema::table('athletics_recent_events', fn (Blueprint $table) => $table->dropColumn('personal_best'));
            }
            if (Schema::hasColumn('athletics_recent_events', 'format_id')) {
                Schema::table('athletics_recent_events', fn (Blueprint $table) => $table->dropConstrainedForeignId('format_id'));
            }
        }
    }
};
