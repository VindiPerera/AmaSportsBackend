<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RugbyCareerStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'rugby_profile_id', 'format_id', 'age_category_id', 'match_category_id',
        'matches', 'win', 'lost', 'tries', 'conversion', 'penalty_kick', 'drop_goal',
        'yellow_card', 'red_card',
    ];

    public function rugbyProfile(): BelongsTo
    {
        return $this->belongsTo(RugbyProfile::class);
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
