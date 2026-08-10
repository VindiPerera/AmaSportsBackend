<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JudoCareerStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'judo_profile_id', 'format_id', 'age_category_id', 'match_category_id',
        'weight_position_id', 'competition_level_id', 'matches', 'win', 'lost',
        'third_place', 'second_place', 'champion',
    ];

    public function judoProfile(): BelongsTo
    {
        return $this->belongsTo(JudoProfile::class);
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

    public function weightPosition(): BelongsTo
    {
        return $this->belongsTo(WeightPosition::class);
    }

    public function competitionLevel(): BelongsTo
    {
        return $this->belongsTo(CompetitionLevel::class);
    }
}
