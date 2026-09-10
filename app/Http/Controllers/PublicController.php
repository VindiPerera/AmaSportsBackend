<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\GameMatch;
use App\Models\Player;
use App\Models\PlayerTeam;
use App\Models\Sport;
use App\Services\CricketAnalysisService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Serves the public-facing website pages:
 *   /home    – Landing page (default with global player search & stats)
 *   /about   – About the platform
 *   /contact – Contact form (GET + POST)
 *   /matches – Matches & Schedule (public, read-only)
 */
class PublicController extends Controller
{
    /** GET /home — main landing page with global player search. */
    public function home(Request $request, CricketAnalysisService $service): View
    {
        $liveMatches = GameMatch::with(['sport', 'homeTeam', 'awayTeam'])
            ->where('status', GameMatch::STATUS_LIVE)
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get();

        $upcomingMatches = GameMatch::with(['sport', 'homeTeam', 'awayTeam'])
            ->where('status', GameMatch::STATUS_UPCOMING)
            ->orderBy('scheduled_at')
            ->limit(6)
            ->get();

        $query = trim((string) $request->query('q', ''));
        $searchResults = null;

        if (mb_strlen($query) >= 2) {
            $players = Player::query()
                ->where(function ($q) use ($query) {
                    $q->where('full_name', 'like', '%'.$query.'%')
                      ->orWhereHas('user', function ($u) use ($query) {
                          $u->where('name', 'like', '%'.$query.'%')
                            ->orWhere('email', 'like', '%'.$query.'%');
                      });
                })
                ->limit(10)
                ->get();

            $cricketSport = Sport::where('slug', Sport::CRICKET_SLUG)->first();

            $searchResults = $players->map(function (Player $player) use ($service, $cricketSport) {
                $analysis = $service->build($player, null);
                $teamName = $cricketSport
                    ? PlayerTeam::where('player_id', $player->id)->where('sport_id', $cricketSport->id)->value('team_name')
                    : null;

                return [
                    'player' => $player,
                    'team' => $teamName ?? 'Independent Player',
                    'analysis' => $analysis,
                ];
            });
        }

        return view('public.home', compact('liveMatches', 'upcomingMatches', 'query', 'searchResults'));
    }

    /** GET /about */
    public function about(): View
    {
        return view('public.about');
    }

    /** GET /contact */
    public function contact(): View
    {
        return view('public.contact');
    }

    /** POST /contact — store a contact message and redirect back. */
    public function contactStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:150'],
            'email'   => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Your message has been sent! We\'ll get back to you soon.');
    }

    /** GET /matches — public matches & schedule page. */
    public function matches(Request $request): View
    {
        $status = $request->query('status', '');

        $query = GameMatch::with(['sport', 'homeTeam', 'awayTeam'])
            ->orderByRaw("FIELD(status, 'live', 'upcoming', 'finished')")
            ->orderBy('scheduled_at');

        if (in_array($status, ['live', 'upcoming', 'finished'], true)) {
            $query->where('status', $status);
        }

        $matches = $query->paginate(20)->withQueryString();

        $liveCounts     = GameMatch::where('status', 'live')->count();
        $upcomingCounts = GameMatch::where('status', 'upcoming')->count();
        $finishedCounts = GameMatch::where('status', 'finished')->count();

        return view('public.matches', compact('matches', 'status', 'liveCounts', 'upcomingCounts', 'finishedCounts'));
    }
}
