<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BaseBallCareerStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'base_ball_profile_id', 'format_id', 'age_category_id', 'match_category_id',
        'matches', 'nt', 'at_bats', 'runs', 'hits', 'rbi', 'won', 'lost',
    ];

    public function baseBallProfile(): BelongsTo
    {
        return $this->belongsTo(BaseBallProfile::class);
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
