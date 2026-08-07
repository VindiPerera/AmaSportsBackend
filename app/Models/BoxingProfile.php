<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoxingProfile extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'player_id', 'born', 'age', 'height', 'weight', 'weight_class_id',
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

    public function weightClass(): BelongsTo
    {
        return $this->belongsTo(BoxingWeightClass::class, 'weight_class_id');
    }

    public function careerStats(): HasMany
    {
        return $this->hasMany(BoxingCareerStat::class);
    }

    public function recentFights(): HasMany
    {
        return $this->hasMany(BoxingRecentFight::class);
    }
}
