<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Cricket-only "Category" lookup for the Batting/Bowling Career Stats "Add
 * New Stat" flow — see the cricket_categories migration for why this isn't
 * the shared `age_categories` table.
 */
class CricketCategory extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['name', 'sort_order'];
}
