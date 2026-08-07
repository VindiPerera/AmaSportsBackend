<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChessCareerStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'chess_profile_id', 'format_id', 'age_category_id', 'match_category_id',
        'games', 'win', 'lost', 'third_place', 'second_place', 'champion',
    ];

    public function chessProfile(): BelongsTo
    {
        return $this->belongsTo(ChessProfile::class);
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
