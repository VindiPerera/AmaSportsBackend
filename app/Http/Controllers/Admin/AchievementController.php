<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AchievementRequest;
use App\Models\Achievement;
use App\Models\Sport;
use App\Services\Achievements\AchievementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Admin CRUD for Achievement templates (see AchievementService,
 * PlayerAchievementController). Cricket-only today — the Metric dropdown is
 * built from `AchievementService::allAvailableMetrics()`, so it only ever
 * offers metrics a registered MetricEvaluator can actually compute; adding
 * a new sport's evaluator later makes its metrics appear here automatically,
 * no changes needed in this controller or its views.
 */
class AchievementController extends Controller
{
    /** Ionicons names offered for the badge — any valid Ionicons name works
     * on the mobile app; this list is just a curated, achievement-flavored
     * starting set for the dropdown. */
    private const ICON_OPTIONS = [
        'trophy', 'ribbon', 'medal', 'star', 'flame', 'flash', 'flag',
        'shield-checkmark', 'trending-up', 'rocket', 'diamond', 'sparkles', 'baseball',
    ];

    public function __construct(private readonly AchievementService $achievements)
    {
    }

    public function index(): View
    {
        $achievements = Achievement::query()
            ->with('sport')
            ->withCount(['playerAchievements as unlocked_count', 'playerAchievements as posted_count' => fn ($q) => $q->whereNotNull('posted_at')])
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('admin.achievements.index', ['achievements' => $achievements]);
    }

    public function create(): View
    {
        return view('admin.achievements.form', [
            'achievement' => null,
            'metrics' => $this->achievements->allAvailableMetrics(),
            'icons' => self::ICON_OPTIONS,
        ]);
    }

    public function store(AchievementRequest $request): RedirectResponse
    {
        Achievement::create($this->withSportId($request->validated()));

        return redirect()
            ->route('admin.achievements.index')
            ->with('success', 'Achievement created.');
    }

    public function edit(Achievement $achievement): View
    {
        return view('admin.achievements.form', [
            'achievement' => $achievement,
            'metrics' => $this->achievements->allAvailableMetrics(),
            'icons' => self::ICON_OPTIONS,
        ]);
    }

    public function update(AchievementRequest $request, Achievement $achievement): RedirectResponse
    {
        $achievement->update($this->withSportId($request->validated()));

        return redirect()
            ->route('admin.achievements.index')
            ->with('success', 'Achievement updated.');
    }

    public function destroy(Achievement $achievement): RedirectResponse
    {
        $achievement->delete();

        return redirect()
            ->route('admin.achievements.index')
            ->with('success', 'Achievement removed — players who already unlocked or posted it keep it.');
    }

    /** The sport is derived from the metric key's prefix (e.g. "cricket_runs"
     * -> the "cricket" sport slug) rather than picked separately, so it can
     * never disagree with the metric — every MetricEvaluator's keys must be
     * prefixed with their sport's exact slug for this to resolve. */
    private function withSportId(array $data): array
    {
        $sportSlug = explode('_', $data['metric_key'])[0];
        $data['sport_id'] = Sport::where('slug', $sportSlug)->firstOrFail()->id;
        $data['is_active'] = $data['is_active'] ?? false;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
