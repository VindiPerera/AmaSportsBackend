<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Shared Tennis / Badminton / Table Tennis profile — see spec Phase 2 §B3. */
class RacketSportProfile extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'player_id', 'sport_id', 'born', 'age', 'height', 'dominant_hand', 'weight',
        'current_ranking', 'college_university',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['born' => 'date'];
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    public function careerStats(): HasMany
    {
        return $this->hasMany(RacketSportCareerStat::class);
    }

    public function recentMatches(): HasMany
    {
        return $this->hasMany(RacketSportRecentMatch::class);
    }
}
