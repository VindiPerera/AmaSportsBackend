<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\CricketMatchType;
use App\Models\Format;
use App\Models\MatchCategory;
use App\Models\Sport;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class LookupController extends Controller
{
    use ApiResponse;

    /**
     * GET /lookups — every dropdown-backing lookup table in one payload, so
     * the mobile app's profile/form screens load with a single call.
     */
    public function index(): JsonResponse
    {
        return $this->success([
            'sports' => Sport::orderBy('sort_order')->get(['id', 'name', 'slug', 'has_full_form']),
            'formats' => Format::orderBy('sort_order')->get(['id', 'name']),
            'age_categories' => AgeCategory::orderBy('sort_order')->get(['id', 'name']),
            'match_categories' => MatchCategory::orderBy('sort_order')->get(['id', 'name']),
            'cricket_match_types' => CricketMatchType::orderBy('sort_order')->get(['id', 'name']),
        ], 'Lookups retrieved successfully.');
    }
}
