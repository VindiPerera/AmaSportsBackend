<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameMatch;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $liveMatches = GameMatch::with(['sport', 'homeTeam', 'awayTeam'])
            ->where('status', GameMatch::STATUS_LIVE)
            ->orderBy('scheduled_at')
            ->get();

        $upcomingMatches = GameMatch::with(['sport', 'homeTeam', 'awayTeam'])
            ->where('status', GameMatch::STATUS_UPCOMING)
            ->orderBy('scheduled_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'liveMatches' => $liveMatches,
            'upcomingMatches' => $upcomingMatches,
        ]);
    }
}
