<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KarateCareerStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'karate_profile_id', 'format_id', 'age_category_id', 'match_category_id',
        'matches', 'fights', 'win', 'lost', 'stats', 'weight_category', 'age_category',
        'third_place', 'second_place', 'champion',
    ];

    public function karateProfile(): BelongsTo
    {
        return $this->belongsTo(KarateProfile::class);
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
