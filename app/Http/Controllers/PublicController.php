<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\GameMatch;
use App\Models\Player;
use App\Models\PlayerSport;
use App\Models\PlayerTeam;
use App\Models\RacketSportCareerStat;
use App\Models\Sport;
use App\Services\Analysis\GenericSportAnalysisService;
use App\Services\Analysis\SportAnalysisConfig;
use App\Services\CricketAnalysisService;
use App\Support\StatMath;
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
    /**
     * GET /home — main landing page with global player search.
     *
     * Stats are sport-aware: Cricket keeps its own batting/bowling service
     * (the only sport with a rate-column split), every sport in
     * SportAnalysisConfig gets its career totals from
     * GenericSportAnalysisService, Tennis/Badminton/Table-Tennis get a small
     * local aggregate over RacketSportCareerStat (see
     * buildRacketSportOverview() — they share one table split by a
     * `category` enum, which doesn't fit SportAnalysisConfig's one-profile-
     * per-sport shape), and only Soft-Ball-Cricket is flagged as not tracked
     * yet rather than silently showing nothing — mirrors the mobile Analysis
     * tab's "Coming Soon".
     */
    public function home(Request $request, CricketAnalysisService $cricketService, GenericSportAnalysisService $genericService): View
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
                ->with('user')
                ->where(function ($q) use ($query) {
                    $q->where('full_name', 'like', '%'.$query.'%')
                      ->orWhereHas('user', function ($u) use ($query) {
                          $u->where('name', 'like', '%'.$query.'%')
                            ->orWhere('email', 'like', '%'.$query.'%');
                      });
                })
                ->limit(10)
                ->get();

            $searchResults = $players->map(function (Player $player) use ($cricketService, $genericService) {
                // The match can come from the account name/email (see the
                // query above) even when the player's own `full_name` was
                // never filled in — fall back to the account name so the
                // card never renders blank.
                $displayName = $player->full_name ?: ($player->user->name ?? 'Unnamed Player');

                $sports = $player->playerSports()
                    ->with('sport')
                    ->where('status', PlayerSport::STATUS_COMPLETED)
                    ->get()
                    ->map(function (PlayerSport $playerSport) use ($player, $cricketService, $genericService) {
                        $sport = $playerSport->sport;
                        if (! $sport) {
                            return null;
                        }

                        $team = PlayerTeam::where('player_id', $player->id)->where('sport_id', $sport->id)->value('team_name');

                        if ($sport->slug === Sport::CRICKET_SLUG) {
                            return [
                                'sport' => $sport,
                                'team' => $team,
                                'type' => 'cricket',
                                'analysis' => $cricketService->build($player, null),
                            ];
                        }

                        if (SportAnalysisConfig::get($sport->slug)) {
                            return [
                                'sport' => $sport,
                                'team' => $team,
                                'type' => 'generic',
                                'analysis' => $genericService->build($player, $sport->slug, null),
                            ];
                        }

                        if (in_array($sport->slug, [Sport::TENNIS_SLUG, Sport::BADMINTON_SLUG, Sport::TABLE_TENNIS_SLUG], true)) {
                            return [
                                'sport' => $sport,
                                'team' => $team,
                                'type' => 'generic',
                                'analysis' => $this->buildRacketSportOverview($player, $sport),
                            ];
                        }

                        // Soft-Ball-Cricket — no aggregator yet.
                        return [
                            'sport' => $sport,
                            'team' => $team,
                            'type' => 'unsupported',
                            'analysis' => null,
                        ];
                    })
                    ->filter()
                    ->values();

                return [
                    'player' => $player,
                    'display_name' => $displayName,
                    'sports' => $sports,
                ];
            });
        }

        return view('public.home', compact('liveMatches', 'upcomingMatches', 'query', 'searchResults'));
    }

    /**
     * Career totals for Tennis/Badminton/Table Tennis — summed across every
     * RacketSportCareerStat row for this player+sport (all categories:
     * single/double/mix_double combined, matching how the search card shows
     * one number per stat rather than splitting by category). Shaped to
     * match GenericSportAnalysisService::build()'s `has_any_stats`/
     * `overview` keys so the view's generic stat-grid partial can render
     * either without knowing the difference.
     */
    private function buildRacketSportOverview(Player $player, Sport $sport): array
    {
        $stats = RacketSportCareerStat::whereHas(
            'racketSportProfile',
            fn ($q) => $q->where('player_id', $player->id)->where('sport_id', $sport->id)
        )->get();

        $win = (int) $stats->sum('win');
        $lost = (int) $stats->sum('lost');

        return [
            'has_any_stats' => $stats->isNotEmpty(),
            'overview' => [
                'matches' => (int) $stats->sum('matches'),
                'win_percentage' => StatMath::safeDivide($win * 100, $win + $lost, 1),
                'champion' => (int) $stats->sum('champion'),
                'semi_final' => (int) $stats->sum('semi_final'),
            ],
        ];
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
