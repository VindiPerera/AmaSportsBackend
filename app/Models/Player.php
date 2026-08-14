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

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'trial_used_at' => 'datetime',
        ];
    }

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

    /**
     * Whether a payment was ever actually captured for this player — i.e.
     * "subscribe vs renew" copy should say "renew" only for players who
     * really were paid subscribers at some point. Deliberately NOT "does
     * the latest row exist": a `pending` row is created the instant
     * checkout starts (Api\SubscriptionController::createOrder), before
     * PayPal confirms anything, so a player who abandoned/failed checkout
     * without ever completing one would otherwise be told their
     * subscription "expired" despite never having paid. Checks every row,
     * not just latestSubscription(), because a lapsed subscriber's most
     * recent row can be a fresh abandoned renewal attempt (`pending`)
     * sitting on top of an older, genuinely `active` one.
     */
    public function hasEverBeenSubscribed(): bool
    {
        return $this->subscriptions()->where('status', Subscription::STATUS_ACTIVE)->exists();
    }

    /**
     * Whether this player can still start the one-time free trial month
     * (Phase 8). `trial_used_at` is the single source of truth for this —
     * deliberately not derived from `subscriptions` (e.g. "is there a row
     * with is_trial=true") so it stays true even if a trial row is ever
     * removed. Not fillable on this model on purpose: only
     * Api\SubscriptionController::startTrial() may set it, via forceFill(),
     * and nothing in the app UI exposes clearing it back to null.
     */
    public function isTrialEligible(): bool
    {
        return $this->trial_used_at === null;
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
