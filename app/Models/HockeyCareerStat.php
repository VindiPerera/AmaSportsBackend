<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HockeyCareerStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'hockey_profile_id',
        'format_id',
        'age_category_id',
        'match_category_id',
        'kit_number',
        'matches',
        'matches_won',
        'matches_lost',
        'goals',
        'assist_goals',
        'defeat_goal',
        'result_won',
        'result_lost',
        'result_drawn',
    ];

    public function hockeyProfile(): BelongsTo
    {
        return $this->belongsTo(HockeyProfile::class);
    }

    public function format(): BelongsTo
    {
        return $this->belongsTo(Format::class);
    }

    public function ageCategory(): BelongsTo
    {
        return $this->belongsTo(AgeCategory::class);
    }

    public function matchCategory(): BelongsTo
    {
        return $this->belongsTo(MatchCategory::class);
    }
}
