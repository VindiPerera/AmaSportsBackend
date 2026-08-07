<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElleCareerStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'elle_profile_id', 'format_id', 'age_category_id', 'match_category_id',
        'matches', 'win', 'lost', 'runs', 'catches', 'third_place', 'second_place', 'champion',
    ];

    public function elleProfile(): BelongsTo
    {
        return $this->belongsTo(ElleProfile::class);
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
