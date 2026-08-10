<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RugbyRecentMatch extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'rugby_profile_id', 'match_date', 'opponent', 'win', 'lost',
        'tries', 'conversion', 'penalty_kick', 'drop_goal', 'yellow_card', 'red_card',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['match_date' => 'date', 'win' => 'boolean', 'lost' => 'boolean'];
    }

    public function rugbyProfile(): BelongsTo
    {
        return $this->belongsTo(RugbyProfile::class);
    }
}
