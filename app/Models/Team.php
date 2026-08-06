<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['name', 'sport_id'];

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }
}
