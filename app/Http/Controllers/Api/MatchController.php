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
    /**
     * Relations every GameMatchResource needs — format/age/category feed the
     * resource's `format`/`age_group`/`category` fields, matching what the
     * admin panel (Admin\MatchController) now sets on creation.
     */
    private const BASE_RELATIONS = ['sport', 'homeTeam', 'awayTeam', 'format', 'ageCategory', 'matchCategory', 'liveStreamAccess'];

    public function index(Request $request): JsonResponse
    {
        $matches = GameMatch::with(self::BASE_RELATIONS)
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderByRaw("FIELD(status, 'live', 'upcoming', 'finished')")
            ->orderBy('scheduled_at')
            ->get();

        return $this->success(GameMatchResource::collection($matches), 'Matches retrieved successfully.');
    }

    /**
     * GET /matches/{match} — Match Detail, including live score fields, the
     * admin-pasted YouTube stream URL, and each side's roster.
     */
    public function show(GameMatch $match): JsonResponse
    {
        $match->load([...self::BASE_RELATIONS, 'matchPlayers']);

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
        $match->load(self::BASE_RELATIONS);

        return $this->success(new GameMatchResource($match), 'Match updated successfully.');
    }
}
