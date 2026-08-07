<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AthleticsEvent extends Model
{
    public const TYPE_RUNNING = 'running';

    public const TYPE_JUMPING = 'jumping';

    public const TYPE_THROWING = 'throwing';

    public const TYPE_WALKING = 'walking';

    /**
     * @var list<string>
     */
    protected $fillable = ['name', 'type', 'sort_order'];
}
