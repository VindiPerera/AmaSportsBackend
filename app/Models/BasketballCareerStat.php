<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BasketballCareerStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'basketball_profile_id', 'format_id', 'age_category_id', 'match_category_id',
        'matches', 'win', 'lost', 'points', 'rebounds', 'assists', 'blocks', 'steals', 'minutes',
    ];

    public function basketballProfile(): BelongsTo
    {
        return $this->belongsTo(BasketballProfile::class);
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
