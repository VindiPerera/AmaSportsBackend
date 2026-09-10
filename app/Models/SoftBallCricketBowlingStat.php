<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoftBallCricketBowlingStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'soft_ball_cricket_profile_id', 'matches', 'balls', 'runs', 'wickets', 'average',
        'economy', 'three_w', 'four_w', 'five_w', 'career_best',
    ];

    public function softBallCricketProfile(): BelongsTo
    {
        return $this->belongsTo(SoftBallCricketProfile::class);
    }
}
