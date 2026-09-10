<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Cricket-only "Division" lookup — only offered for a handful of Categories
 * (see CareerStatAddModal's DIVISION_ELIGIBLE_CATEGORIES on the mobile app).
 * See the cricket_divisions migration for why this isn't the shared
 * `formats` table.
 */
class CricketDivision extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['name', 'sort_order'];
}
