<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CricketRecentMatch extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'cricket_profile_id',
        'age_category_id',
        'format_id',
        'match_date',
        'opponent',
        'ground',
        'year',
        'played_xi',
        'batting_innings',
        'runs',
        'balls',
        'not_out',
        'hs',
        'fours',
        'sixes',
        'hundreds',
        'fifties',
        'overs',
        'maidens',
        'bowling_innings',
        'bowling_balls',
        'bowling_runs',
        'wickets',
        'bbi',
        'bbm',
        'three_w',
        'four_w',
        'five_w',
        'ten_w',
        'catches',
        'stumpings',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'match_date' => 'date',
            'played_xi' => 'boolean',
            'not_out' => 'boolean',
            'hundreds' => 'boolean',
            'fifties' => 'boolean',
            'three_w' => 'boolean',
            'four_w' => 'boolean',
            'five_w' => 'boolean',
            'ten_w' => 'boolean',
        ];
    }

    public function cricketProfile(): BelongsTo
    {
        return $this->belongsTo(CricketProfile::class);
    }
}
