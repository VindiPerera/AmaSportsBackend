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
        'match_date',
        'opponent',
        'played_xi',
        'runs',
        'balls',
        'fours',
        'sixes',
        'overs',
        'maidens',
        'wickets',
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
        ];
    }

    public function cricketProfile(): BelongsTo
    {
        return $this->belongsTo(CricketProfile::class);
    }
}
