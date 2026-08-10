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

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * The subscription row that currently governs this player's access —
     * always the most recent one, never a merged/extended view of history
     * (Phase 6 spec: "always check the most recent one for current access").
     */
    public function latestSubscription(): ?Subscription
    {
        return $this->subscriptions()->latest('id')->first();
    }

    public function hasActiveSubscription(): bool
    {
        return (bool) $this->latestSubscription()?->isActive();
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

    public function baseBallProfile(): HasOne
    {
        return $this->hasOne(BaseBallProfile::class);
    }

    public function netBallProfile(): HasOne
    {
        return $this->hasOne(NetBallProfile::class);
    }

    /** One row per racket sport (Tennis/Badminton/Table Tennis) the player has submitted. */
    public function racketSportProfiles(): HasMany
    {
        return $this->hasMany(RacketSportProfile::class);
    }

    public function kabadiProfile(): HasOne
    {
        return $this->hasOne(KabadiProfile::class);
    }

    public function judoProfile(): HasOne
    {
        return $this->hasOne(JudoProfile::class);
    }

    public function basketballProfile(): HasOne
    {
        return $this->hasOne(BasketballProfile::class);
    }

    public function footballProfile(): HasOne
    {
        return $this->hasOne(FootballProfile::class);
    }

    public function rugbyProfile(): HasOne
    {
        return $this->hasOne(RugbyProfile::class);
    }

    public function boxingProfile(): HasOne
    {
        return $this->hasOne(BoxingProfile::class);
    }

    public function karateProfile(): HasOne
    {
        return $this->hasOne(KarateProfile::class);
    }

    public function chessProfile(): HasOne
    {
        return $this->hasOne(ChessProfile::class);
    }

    public function athleticsProfile(): HasOne
    {
        return $this->hasOne(AthleticsProfile::class);
    }

    public function swimmingProfile(): HasOne
    {
        return $this->hasOne(SwimmingProfile::class);
    }

    public function volleyballProfile(): HasOne
    {
        return $this->hasOne(VolleyballProfile::class);
    }

    public function beachVolleyballProfile(): HasOne
    {
        return $this->hasOne(BeachVolleyballProfile::class);
    }

    public function elleProfile(): HasOne
    {
        return $this->hasOne(ElleProfile::class);
    }
}
