<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JudoProfile extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'player_id', 'born', 'age', 'height', 'weight', 'college_university', 'current_ranking',
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

    public function careerStats(): HasMany
    {
        return $this->hasMany(JudoCareerStat::class);
    }

    public function recentFights(): HasMany
    {
        return $this->hasMany(JudoRecentFight::class);
    }
}
