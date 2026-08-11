<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropReason extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['name', 'sort_order'];
}
