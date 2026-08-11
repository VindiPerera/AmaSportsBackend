<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use App\Models\AthleticsEvent;
use App\Models\BallType;
use App\Models\BoxingWeightClass;
use App\Models\CompetitionLevel;
use App\Models\CricketMatchType;
use App\Models\DropReason;
use App\Models\FieldPosition;
use App\Models\Format;
use App\Models\KarateStyle;
use App\Models\MatchCategory;
use App\Models\PitchingLine;
use App\Models\Sport;
use App\Models\SwimmingEvent;
use App\Models\WeightPosition;
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
            // Judo only — no assumed mapping between the two (see Phase 2 spec §B5).
            'weight_positions' => WeightPosition::orderBy('sort_order')->get(['id', 'label']),
            'competition_levels' => CompetitionLevel::orderBy('sort_order')->get(['id', 'name']),
            // Phase 3 additions.
            'athletics_events' => AthleticsEvent::orderBy('sort_order')->get(['id', 'name', 'type']),
            'swimming_events' => SwimmingEvent::orderBy('sort_order')->get(['id', 'name']),
            'boxing_weight_classes' => BoxingWeightClass::orderBy('sort_order')->get(['id', 'name']),
            'karate_styles' => KarateStyle::orderBy('sort_order')->get(['id', 'name']),
            // Phase 7 additions — Cricket Fielding/Bowling Analyses.
            'field_positions' => FieldPosition::orderBy('sort_order')->get(['id', 'name']),
            'drop_reasons' => DropReason::orderBy('sort_order')->get(['id', 'name']),
            'pitching_lines' => PitchingLine::orderBy('sort_order')->get(['id', 'name']),
            'ball_types' => BallType::orderBy('sort_order')->get(['id', 'name']),
        ], 'Lookups retrieved successfully.');
    }
}
