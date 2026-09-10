<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A single $10/1-year app subscription purchase or renewal. Gates "Add
 * Sport" and the Analysis tab (Phase 6 revision 2) — and, per product
 * decision, editing sport profiles the player already registered too, since
 * an expired subscription locks the whole player-data-writing surface, not
 * just new registrations.
 */
class Subscription extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_EXPIRED = 'expired';

    public const STATUS_CANCELLED = 'cancelled';

    /** Fixed price for now — one plan, no tiers. */
    public const AMOUNT = 10.00;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'player_id',
        'paypal_order_id',
        'amount',
        'currency',
        'status',
        'is_trial',
        'starts_at',
        'expires_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'is_trial' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    /** Currently paid for and within its 1-year window. */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && $this->expires_at !== null
            && $this->expires_at->isFuture();
    }
}
