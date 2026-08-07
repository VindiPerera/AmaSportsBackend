<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoxingCareerStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'boxing_profile_id', 'format_id', 'age_category_id', 'match_category_id',
        'weight_class_id', 'matches', 'win', 'lost', 'third_place', 'second_place', 'champion',
    ];

    public function boxingProfile(): BelongsTo
    {
        return $this->belongsTo(BoxingProfile::class);
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

    public function weightClass(): BelongsTo
    {
        return $this->belongsTo(BoxingWeightClass::class, 'weight_class_id');
    }
}
