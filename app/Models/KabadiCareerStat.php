<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KabadiCareerStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'kabadi_profile_id', 'format_id', 'age_category_id', 'match_category_id',
        'matches', 'win', 'lost', 'cbp', 'raids', 'successful_raids', 'unsuccessful_raids',
        'raid_touch_point', 'raid_bonus_point', 'tackles', 'successful_tackles',
        'unsuccessful_tackles', 'empty_raids', 'yellow_cards', 'green_cards', 'red_cards',
    ];

    public function kabadiProfile(): BelongsTo
    {
        return $this->belongsTo(KabadiProfile::class);
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
