<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Matches\UpdateMatchScoreRequest;
use App\Http\Resources\GameMatchResource;
use App\Models\GameMatch;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    use ApiResponse;

    /**
     * GET /matches — Live Score list, across all sports. Public (no auth)
     * so the tab can be built/tested before login flows are wired to it;
     * every other player route stays behind Sanctum.
     */
    public function index(Request $request): JsonResponse
    {
        $matches = GameMatch::with(['sport', 'homeTeam', 'awayTeam'])
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderByRaw("FIELD(status, 'live', 'upcoming', 'finished')")
            ->orderBy('scheduled_at')
            ->get();

        return $this->success(GameMatchResource::collection($matches), 'Matches retrieved successfully.');
    }

    /**
     * GET /matches/{match} — Match Detail, including live score fields and
     * the admin-pasted YouTube stream URL.
     */
    public function show(GameMatch $match): JsonResponse
    {
        $match->load(['sport', 'homeTeam', 'awayTeam']);

        return $this->success(new GameMatchResource($match), 'Match retrieved successfully.');
    }

    /**
     * PATCH /matches/{match}/score — basic authenticated score-update
     * endpoint per spec §5. No admin UI yet, but the API is ready for one;
     * gated to admins now rather than left open.
     */
    public function updateScore(UpdateMatchScoreRequest $request, GameMatch $match): JsonResponse
    {
        if (! $request->user()->isAdmin()) {
            return $this->error('Only an admin can update match scores.', 403);
        }

        $match->update($request->validated());
        $match->load(['sport', 'homeTeam', 'awayTeam']);

        return $this->success(new GameMatchResource($match), 'Match updated successfully.');
    }
}
