<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FootballCareerStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'football_profile_id', 'format_id', 'age_category_id', 'match_category_id',
        'matches', 'win', 'lost', 'goals', 'assists', 'defensive_actions',
        'goalkeeper_clean_sheets', 'goalkeeper_goals_conceded', 'yellow_card', 'red_card',
    ];

    public function footballProfile(): BelongsTo
    {
        return $this->belongsTo(FootballProfile::class);
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
