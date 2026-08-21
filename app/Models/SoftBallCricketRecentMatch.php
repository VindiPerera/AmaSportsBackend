<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoftBallCricketRecentMatch extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'soft_ball_cricket_profile_id', 'match_date', 'opponent', 'won', 'lost', 'runs', 'balls',
        'average', 'bowling_balls', 'bowling_runs', 'wickets', 'catches', 'stumpings',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['match_date' => 'date', 'won' => 'boolean', 'lost' => 'boolean'];
    }

    public function softBallCricketProfile(): BelongsTo
    {
        return $this->belongsTo(SoftBallCricketProfile::class);
    }
}
