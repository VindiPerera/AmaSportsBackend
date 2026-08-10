<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KabadiRecentMatch extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'kabadi_profile_id', 'match_date', 'opponent', 'venue', 'win', 'lost',
        'cbp', 'raids', 'successful_raids', 'unsuccessful_raids', 'raid_touch_point',
        'raid_bonus_point', 'tackles', 'successful_tackles', 'unsuccessful_tackles',
        'empty_raids', 'yellow_cards', 'green_cards', 'red_cards',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['match_date' => 'date', 'win' => 'boolean', 'lost' => 'boolean'];
    }

    public function kabadiProfile(): BelongsTo
    {
        return $this->belongsTo(KabadiProfile::class);
    }
}
