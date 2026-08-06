<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sport extends Model
{
    public const CRICKET_SLUG = 'cricket';

    public const HOCKEY_SLUG = 'hockey';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'has_full_form',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'has_full_form' => 'boolean',
        ];
    }

    public function playerSports(): HasMany
    {
        return $this->hasMany(PlayerSport::class);
    }
}
