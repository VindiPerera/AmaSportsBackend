<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HockeyProfile extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'player_id',
        'born',
        'age',
        'height',
        'dominant_hand',
        'player_position',
        'college_university',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'born' => 'date',
        ];
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function careerStats(): HasMany
    {
        return $this->hasMany(HockeyCareerStat::class);
    }

    public function recentMatches(): HasMany
    {
        return $this->hasMany(HockeyRecentMatch::class);
    }
}
