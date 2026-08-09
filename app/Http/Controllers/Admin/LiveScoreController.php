<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LiveScoreUpdateRequest;
use App\Models\GameMatch;
use App\Models\Sport;
use App\Services\FirebaseLiveScoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LiveScoreController extends Controller
{
    /**
     * Maps a sport slug to the `MultiSportLiveScore` field it owns —
     * mirrors sport-mobile/src/services/firebaseService.ts exactly.
     */
    private const SCORE_KEYS = [
        Sport::BADMINTON_SLUG => 'racket_scores',
        Sport::CRICKET_SLUG => 'cricket_score',
        Sport::VOLLEYBALL_SLUG => 'team_score',
    ];

    public function show(GameMatch $match): View
    {
        $match->load(['sport', 'homeTeam', 'awayTeam', 'matchPlayers']);

        abort_unless(in_array($match->sport->slug, Sport::ADMIN_LIVE_SCORE_SLUGS, true), 404);

        $firebase = app(FirebaseLiveScoreService::class);
        $scoreKey = self::SCORE_KEYS[$match->sport->slug];

        // While live, Update only ever writes to Firestore (matches.live_score
        // stays null until Finish — see LiveScoreController::update), so
        // Firestore is the only place the current score actually lives.
        // Without reading it back here, navigating away from this page and
        // returning mid-match would show a blank form even though scoring
        // is still in progress.
        $liveSnapshot = $match->status === GameMatch::STATUS_LIVE ? $firebase->getSnapshot($match) : null;
        $currentScore = $liveSnapshot[$scoreKey] ?? $match->live_score[$scoreKey] ?? [];

        return view('admin.live-score.show', [
            'match' => $match,
            'scoreKey' => $scoreKey,
            'currentScore' => $currentScore,
            'firebaseConfigured' => $firebase->isConfigured(),
            // Cricket's toss/scorer screen seeds each side's initial batting
            // order from the roster already entered in Player Registration,
            // rather than generic "Player N" placeholders.
            'homeRosterNames' => $match->homePlayers->pluck('full_name')->values(),
            'awayRosterNames' => $match->awayPlayers->pluck('full_name')->values(),
        ]);
    }

    public function start(LiveScoreUpdateRequest $request, GameMatch $match, FirebaseLiveScoreService $firebase): RedirectResponse
    {
        $scoreKey = $this->scoreKeyFor($match);

        $payload = array_merge($match->toFirestoreSnapshot(), [
            'status' => 'live',
            $scoreKey => $request->validated()[$scoreKey] ?? [],
        ]);

        $pushed = $firebase->pushSnapshot($match, $payload);

        $match->update(['status' => GameMatch::STATUS_LIVE]);

        $redirect = redirect()->route('admin.live-score.show', $match)->with('success', 'Match is live.');

        if (! $pushed) {
            $redirect->with('firebase_warning', 'Firebase is not configured — live updates are not being broadcast to the mobile app yet.');
        }

        return $redirect;
    }

    public function update(LiveScoreUpdateRequest $request, GameMatch $match, FirebaseLiveScoreService $firebase): JsonResponse
    {
        if ($match->status !== GameMatch::STATUS_LIVE) {
            return response()->json(['success' => false, 'message' => 'Match is not live.'], 422);
        }

        $scoreKey = $this->scoreKeyFor($match);

        $pushed = $firebase->mergeUpdate($match, [
            $scoreKey => $request->validated()[$scoreKey] ?? [],
            'status' => 'live',
        ]);

        return response()->json(['success' => true, 'firebase_ok' => $pushed]);
    }

    public function finish(LiveScoreUpdateRequest $request, GameMatch $match, FirebaseLiveScoreService $firebase): RedirectResponse
    {
        $scoreKey = $this->scoreKeyFor($match);
        $scoreBlock = $request->validated()[$scoreKey] ?? [];

        $match->update([
            'status' => GameMatch::STATUS_FINISHED,
            'live_score' => [
                $scoreKey => $scoreBlock,
                'last_updated' => now()->toISOString(),
            ],
        ]);

        $pushed = $firebase->markFinished($match, [$scoreKey => $scoreBlock]);

        $redirect = redirect()->route('admin.matches.index')->with('success', 'Match finished.');

        if (! $pushed) {
            $redirect->with('firebase_warning', 'Firebase is not configured — the mobile app was not notified this match finished.');
        }

        return $redirect;
    }

    private function scoreKeyFor(GameMatch $match): string
    {
        $match->loadMissing('sport');

        abort_unless(isset(self::SCORE_KEYS[$match->sport->slug]), 404);

        return self::SCORE_KEYS[$match->sport->slug];
    }
}
