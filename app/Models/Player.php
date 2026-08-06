<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'full_name',
        'country',
        'cover_photo_url',
        'photo_url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function playerSports(): HasMany
    {
        return $this->hasMany(PlayerSport::class);
    }

    public function playerTeams(): HasMany
    {
        return $this->hasMany(PlayerTeam::class);
    }

    public function cricketProfile(): HasOne
    {
        return $this->hasOne(CricketProfile::class);
    }

    public function hockeyProfile(): HasOne
    {
        return $this->hasOne(HockeyProfile::class);
    }
}
