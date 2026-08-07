<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KarateProfile extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'player_id', 'born', 'age', 'height', 'weight', 'player_style_id',
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

    public function playerStyle(): BelongsTo
    {
        return $this->belongsTo(KarateStyle::class, 'player_style_id');
    }

    public function careerStats(): HasMany
    {
        return $this->hasMany(KarateCareerStat::class);
    }

    public function recentMatches(): HasMany
    {
        return $this->hasMany(KarateRecentMatch::class);
    }
}
