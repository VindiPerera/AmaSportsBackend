<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AthleticsRecentEvent extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'athletics_profile_id', 'event_date', 'age_category_id', 'match_category_id',
        'matches', 'athletics_event_id', 'place',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['event_date' => 'date'];
    }

    public function athleticsProfile(): BelongsTo
    {
        return $this->belongsTo(AthleticsProfile::class);
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
        return $this->belongsTo(AthleticsEvent::class, 'athletics_event_id');
    }
}
