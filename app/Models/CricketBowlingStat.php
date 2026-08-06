<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CricketBowlingStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'cricket_profile_id',
        'format_id',
        'age_category_id',
        'match_category_id',
        'cricket_match_type_id',
        'matches',
        'innings',
        'balls',
        'runs',
        'wickets',
        'bbi',
        'bbm',
        'average',
        'economy',
        'sr',
        'four_w',
        'five_w',
        'ten_w',
    ];

    public function cricketProfile(): BelongsTo
    {
        return $this->belongsTo(CricketProfile::class);
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

    public function cricketMatchType(): BelongsTo
    {
        return $this->belongsTo(CricketMatchType::class);
    }
}
