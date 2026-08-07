<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NetBallCareerStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'net_ball_profile_id', 'format_id', 'age_category_id', 'match_category_id',
        'matches', 'matches_won', 'matches_lost', 'goals', 'attempts', 'goal_accuracy',
        'result_won', 'result_lost',
    ];

    public function netBallProfile(): BelongsTo
    {
        return $this->belongsTo(NetBallProfile::class);
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
