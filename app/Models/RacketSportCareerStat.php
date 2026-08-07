<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RacketSportCareerStat extends Model
{
    public const CATEGORY_SINGLE = 'single';

    public const CATEGORY_DOUBLE = 'double';

    public const CATEGORY_MIX_DOUBLE = 'mix_double';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'racket_sport_profile_id', 'category', 'format_id', 'age_category_id', 'match_category_id',
        'matches', 'win', 'lost', 'set_win', 'set_lost', 'quarter_final', 'semi_final',
        'third_place', 'second_place', 'champion',
    ];

    public function racketSportProfile(): BelongsTo
    {
        return $this->belongsTo(RacketSportProfile::class);
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
