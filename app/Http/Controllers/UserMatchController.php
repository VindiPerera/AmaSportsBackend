<?php

namespace App\Http\Controllers;

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
use Illuminate\View\View;

class UserMatchController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', '');

        $query = GameMatch::with(['sport', 'homeTeam', 'awayTeam'])
            ->orderByRaw("FIELD(status, 'live', 'upcoming', 'finished')")
            ->orderBy('scheduled_at');

        if (in_array($status, ['live', 'upcoming', 'finished'], true)) {
            $query->where('status', $status);
        }

        $matches = $query->paginate(20)->withQueryString();

        return view('user.matches.index', compact('matches', 'status'));
    }

    public function create(): View
    {
        return view('user.matches.create', $this->formOptions() + ['match' => null]);
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
            'format_id' => $data['format_id'] ?? null,
            'age_category_id' => $data['age_category_id'] ?? null,
            'match_category_id' => $data['match_category_id'] ?? null,
            'country' => $data['country'] ?? null,
            'contact_mobile' => $data['contact_mobile'] ?? null,
            'contact_whatsapp' => $data['contact_whatsapp'] ?? null,
            'contact_email' => $data['contact_email'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('user.matches.index')->with('success', 'Match created successfully!');
    }

    public function edit(GameMatch $match): View
    {
        return view('user.matches.create', $this->formOptions() + ['match' => $match]);
    }

    public function update(MatchSetupRequest $request, GameMatch $match): RedirectResponse
    {
        $data = $request->validated();

        $homeTeam = $this->resolveTeam($data, 'home', (int) $data['sport_id']);
        $awayTeam = $this->resolveTeam($data, 'away', (int) $data['sport_id']);

        $match->update([
            'sport_id' => $data['sport_id'],
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'scheduled_at' => $data['scheduled_at'],
            'venue' => $data['venue'] ?? null,
            'format_id' => $data['format_id'] ?? null,
            'age_category_id' => $data['age_category_id'] ?? null,
            'match_category_id' => $data['match_category_id'] ?? null,
            'country' => $data['country'] ?? null,
            'contact_mobile' => $data['contact_mobile'] ?? null,
            'contact_whatsapp' => $data['contact_whatsapp'] ?? null,
            'contact_email' => $data['contact_email'] ?? null,
        ]);

        return redirect()->route('user.matches.index')->with('success', 'Match updated successfully!');
    }

    private function resolveTeam(array $data, string $prefix, int $sportId): Team
    {
        $teamIdKey = "{$prefix}_team_id";
        $nameKey = "{$prefix}_team_name";
        $schoolKey = "{$prefix}_team_school_academy";
        $clubKey = "{$prefix}_team_club";
        $countryKey = "{$prefix}_team_country";

        if (! empty($data[$teamIdKey])) {
            $team = Team::findOrFail($data[$teamIdKey]);
            $team->update([
                'name' => $data[$nameKey],
                'school_academy' => $data[$schoolKey] ?? null,
                'club' => $data[$clubKey] ?? null,
                'country' => $data[$countryKey] ?? null,
            ]);

            return $team;
        }

        return Team::create([
            'sport_id' => $sportId,
            'name' => $data[$nameKey],
            'school_academy' => $data[$schoolKey] ?? null,
            'club' => $data[$clubKey] ?? null,
            'country' => $data[$countryKey] ?? null,
        ]);
    }

    private function formOptions(): array
    {
        return [
            'sports' => Sport::orderBy('name')->get(),
            'formats' => Format::orderBy('name')->get(),
            'ageCategories' => AgeCategory::orderBy('name')->get(),
            'matchCategories' => MatchCategory::orderBy('name')->get(),
        ];
    }
}
