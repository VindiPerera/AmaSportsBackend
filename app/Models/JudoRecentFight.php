<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JudoRecentFight extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'judo_profile_id', 'fight_date', 'opponent', 'venue', 'weight_position_id',
        'competition_level_id', 'win', 'lost', 'place',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['fight_date' => 'date', 'win' => 'boolean', 'lost' => 'boolean'];
    }

    public function judoProfile(): BelongsTo
    {
        return $this->belongsTo(JudoProfile::class);
    }

    public function weightPosition(): BelongsTo
    {
        return $this->belongsTo(WeightPosition::class);
    }

    public function competitionLevel(): BelongsTo
    {
        return $this->belongsTo(CompetitionLevel::class);
    }
}
