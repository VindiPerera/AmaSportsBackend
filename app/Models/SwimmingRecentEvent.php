<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SwimmingRecentEvent extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'swimming_profile_id', 'event_date', 'age_category_id', 'match_category_id',
        'matches', 'swimming_event_id', 'performance_time', 'place',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['event_date' => 'date'];
    }

    public function swimmingProfile(): BelongsTo
    {
        return $this->belongsTo(SwimmingProfile::class);
    }

    public function ageCategory(): BelongsTo
    {
        return $this->belongsTo(AgeCategory::class);
    }

    public function matchCategory(): BelongsTo
    {
        return $this->belongsTo(MatchCategory::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(SwimmingEvent::class, 'swimming_event_id');
    }
}
