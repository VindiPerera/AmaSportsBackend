<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Small typeahead endpoint powering the Match Setup team picker —
     * lets an admin reuse an existing team (e.g. "Sri Lanka", "Ananda
     * College") instead of retyping it for every match.
     */
    public function search(Request $request): JsonResponse
    {
        $teams = Team::query()
            ->when($request->filled('sport_id'), fn ($query) => $query->where('sport_id', $request->integer('sport_id')))
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->string('q').'%'))
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'country', 'school_academy', 'club']);

        return response()->json(['data' => $teams]);
    }
}
