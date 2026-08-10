<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Format;
use App\Models\Player;
use App\Services\CricketAnalysisService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CricketAnalysisController extends Controller
{
    use ApiResponse;

    /**
     * GET /player/cricket-analysis — server-computed career/format/recent-form
     * aggregates for the Analysis tab's Cricket screen (spec Phase 5 §2).
     * All averages/rates are computed here, never on the mobile client.
     *
     * `?format=` accepts either a numeric Format id or its name (case
     * insensitive) — see the "Format" note in CricketAnalysisService: this
     * app's `formats` lookup is competition level (Div I, Premier, National,
     * ...), the same field the Cricket profile form itself calls "Format".
     * Omit it (or pass "all") for the unfiltered career totals.
     */
    public function __invoke(Request $request, CricketAnalysisService $service): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $formatId = $this->resolveFormatId($request->query('format'));

        $data = $service->build($player, $formatId);

        return $this->success($data, 'Cricket analysis retrieved successfully.');
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
