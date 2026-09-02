<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Format;
use App\Models\Player;
use App\Services\Analysis\GenericSportAnalysisService;
use App\Services\Analysis\SportAnalysisConfig;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SportAnalysisController extends Controller
{
    use ApiResponse;

    /**
     * GET /player/{sport}/analysis — the generic counterpart to
     * /player/cricket-analysis for every sport SportAnalysisConfig knows how
     * to aggregate (single career-stats table of plain integer counts).
     * `{sport}` is the Sport slug (e.g. "hockey", "base-ball"), matching the
     * slug used everywhere else in the API. Unknown/unsupported slugs 404 —
     * Tennis/Badminton/Table Tennis and Soft-Ball-Cricket aren't in the
     * config (see its docblock) and stay on the mobile "Coming Soon" screen.
     */
    public function __invoke(Request $request, string $sport, GenericSportAnalysisService $service): JsonResponse
    {
        if (! SportAnalysisConfig::get($sport)) {
            return $this->error('Analysis is not available for this sport yet.', 404);
        }

        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $formatId = $this->resolveFormatId($request->query('format'));

        $data = $service->build($player, $sport, $formatId);

        return $this->success($data, 'Sport analysis retrieved successfully.');
    }

    private function resolveFormatId(?string $raw): ?int
    {
        if ($raw === null || $raw === '' || strtolower($raw) === 'all') {
            return null;
        }

        if (ctype_digit($raw)) {
            return (int) $raw;
        }

        return Format::whereRaw('LOWER(name) = ?', [strtolower($raw)])->value('id');
    }
}
