<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PitchingLine extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['name', 'sort_order'];
}
