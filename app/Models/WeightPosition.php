<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeightPosition extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['label', 'sort_order'];
}
