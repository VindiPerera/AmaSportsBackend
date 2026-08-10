<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MatchSetupRequest;
use App\Models\AgeCategory;
use App\Models\Format;
use App\Models\GameMatch;
use App\Models\MatchCategory;
use App\Models\Sport;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MatchController extends Controller
{
    public function index(Request $request): View
    {
        $matches = GameMatch::with(['sport', 'homeTeam', 'awayTeam'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->orderByRaw("FIELD(status, 'live', 'upcoming', 'finished')")
            ->orderBy('scheduled_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.matches.index', ['matches' => $matches]);
    }

    public function create(): View
    {
        return view('admin.matches.create', $this->formOptions() + ['match' => null]);
    }

    public function store(MatchSetupRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $homeTeam = $this->resolveTeam($data, 'home', (int) $data['sport_id']);
        $awayTeam = $this->resolveTeam($data, 'away', (int) $data['sport_id']);

        $match = GameMatch::create([
            'sport_id' => $data['sport_id'],
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'status' => GameMatch::STATUS_UPCOMING,
            'scheduled_at' => $data['scheduled_at'],
            'venue' => $data['venue'] ?? null,
            'youtube_stream_url' => $data['youtube_stream_url'] ?? null,
            'format_id' => $data['format_id'] ?? null,
            'age_category_id' => $data['age_category_id'] ?? null,
            'match_category_id' => $data['match_category_id'] ?? null,
            'country' => $data['country'] ?? null,
            'contact_mobile' => $data['contact_mobile'] ?? null,
            'contact_whatsapp' => $data['contact_whatsapp'] ?? null,
            'contact_email' => $data['contact_email'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.matches.players.index', $match)
            ->with('success', 'Match created — now add the roster for each side.');
    }

    public function edit(GameMatch $match): View|RedirectResponse
    {
        if ($match->status !== GameMatch::STATUS_UPCOMING) {
            return redirect()
                ->route('admin.matches.index')
                ->with('error', 'Only upcoming matches can be edited.');
        }

        $match->load(['homeTeam', 'awayTeam']);

        return view('admin.matches.create', $this->formOptions() + ['match' => $match]);
    }

    public function update(MatchSetupRequest $request, GameMatch $match): RedirectResponse
    {
        if ($match->status !== GameMatch::STATUS_UPCOMING) {
            return redirect()
                ->route('admin.matches.index')
                ->with('error', 'Only upcoming matches can be edited.');
        }

        $data = $request->validated();

        $homeTeam = $this->resolveTeam($data, 'home', (int) $data['sport_id'], $match->home_team_id);
        $awayTeam = $this->resolveTeam($data, 'away', (int) $data['sport_id'], $match->away_team_id);

        $match->update([
            'sport_id' => $data['sport_id'],
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'scheduled_at' => $data['scheduled_at'],
            'venue' => $data['venue'] ?? null,
            'youtube_stream_url' => $data['youtube_stream_url'] ?? null,
            'format_id' => $data['format_id'] ?? null,
            'age_category_id' => $data['age_category_id'] ?? null,
            'match_category_id' => $data['match_category_id'] ?? null,
            'country' => $data['country'] ?? null,
            'contact_mobile' => $data['contact_mobile'] ?? null,
            'contact_whatsapp' => $data['contact_whatsapp'] ?? null,
            'contact_email' => $data['contact_email'] ?? null,
        ]);

        return redirect()
            ->route('admin.matches.index')
            ->with('success', 'Match updated.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'sports' => Sport::whereIn('slug', Sport::ADMIN_LIVE_SCORE_SLUGS)->orderBy('sort_order')->get(),
            'formats' => Format::orderBy('sort_order')->get(),
            'ageCategories' => AgeCategory::orderBy('sort_order')->get(),
            'matchCategories' => MatchCategory::orderBy('sort_order')->get(),
        ];
    }

    /**
     * Resolve the home/away Team for this match: reuse an existing team by
     * id (picked via the typeahead) or create one from the typed name, then
     * apply any profile/photo updates from the form. Kept inside the same
     * request as match creation — no separate async "create team" endpoint.
     *
     * @param  array<string, mixed>  $data
     */
    private function resolveTeam(array $data, string $side, int $sportId, ?int $fallbackTeamId = null): Team
    {
        $teamId = $data["{$side}_team_id"] ?? $fallbackTeamId;

        $team = filled($teamId)
            ? Team::findOrFail($teamId)
            : Team::firstOrCreate(['name' => trim($data["{$side}_team_name"]), 'sport_id' => $sportId]);

        $team->fill(array_filter([
            'country' => $data["{$side}_team_country"] ?? null,
            'school_academy' => $data["{$side}_team_school_academy"] ?? null,
            'club' => $data["{$side}_team_club"] ?? null,
        ], fn ($value) => $value !== null));

        foreach (['logo' => 'logo_url', 'photo' => 'photo_url'] as $field => $column) {
            if (request()->hasFile("{$side}_{$field}")) {
                if ($team->{$column}) {
                    Storage::disk('public')->delete($team->{$column});
                }
                $team->{$column} = request()->file("{$side}_{$field}")->store("teams/{$team->id}", 'public');
            }
        }

        $team->save();

        return $team;
    }
}
