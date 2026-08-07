<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeachVolleyballCareerStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'beach_volleyball_profile_id', 'format_id', 'age_category_id', 'match_category_id',
        'matches', 'win', 'lost', 'passes', 'setting', 'serve', 'attacking', 'blocking',
        'digging', 'third_place', 'second_place', 'champion',
    ];

    public function beachVolleyballProfile(): BelongsTo
    {
        return $this->belongsTo(BeachVolleyballProfile::class);
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
