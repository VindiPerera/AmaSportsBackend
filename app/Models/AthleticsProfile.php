<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AthleticsProfile extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['player_id', 'born', 'age', 'height', 'weight', 'college_university'];

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

    public function personalBests(): HasMany
    {
        return $this->hasMany(AthleticsPersonalBest::class);
    }

    public function careerStats(): HasMany
    {
        return $this->hasMany(AthleticsCareerStat::class);
    }

    public function recentEvents(): HasMany
    {
        return $this->hasMany(AthleticsRecentEvent::class);
    }
}
