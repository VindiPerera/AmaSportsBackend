<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FootballRecentMatch extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'football_profile_id', 'match_date', 'opponent', 'venue', 'win', 'lost',
        'goals', 'assists', 'defensive_actions', 'goalkeeper_clean_sheets',
        'goalkeeper_goals_conceded', 'yellow_card', 'red_card',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['match_date' => 'date', 'win' => 'boolean', 'lost' => 'boolean'];
    }

    public function footballProfile(): BelongsTo
    {
        return $this->belongsTo(FootballProfile::class);
    }
}
