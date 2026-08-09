<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'sport_id',
        'country',
        'school_academy',
        'club',
        'logo_url',
        'photo_url',
    ];

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }
}
