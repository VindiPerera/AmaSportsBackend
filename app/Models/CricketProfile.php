<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CricketProfile extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'player_id',
        'born',
        'age',
        'batting_style',
        'bowling_style',
        'playing_role',
        'height',
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

    public function battingStats(): HasMany
    {
        return $this->hasMany(CricketBattingStat::class);
    }

    public function bowlingStats(): HasMany
    {
        return $this->hasMany(CricketBowlingStat::class);
    }

    public function recentMatches(): HasMany
    {
        return $this->hasMany(CricketRecentMatch::class);
    }
}
