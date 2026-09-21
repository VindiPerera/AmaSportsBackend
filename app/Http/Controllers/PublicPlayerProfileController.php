<?php

namespace App\Http\Controllers;

use App\Models\AgeCategory;
use App\Models\AthleticsEvent;
use App\Models\BoxingWeightClass;
use App\Models\CompetitionLevel;
use App\Models\CricketCategory;
use App\Models\CricketDivision;
use App\Models\CricketMatchType;
use App\Models\DropReason;
use App\Models\FieldPosition;
use App\Models\Format;
use App\Models\KarateStyle;
use App\Models\MatchCategory;
use App\Models\PitchingLine;
use App\Models\Player;
use App\Models\PlayerCollegeLogo;
use App\Models\PlayerSport;
use App\Models\PlayerTeam;
use App\Models\PlayerTeamLogo;
use App\Models\RacketSportProfile;
use App\Models\SoftBallCricketProfile;
use App\Models\Sport;
use App\Models\SwimmingEvent;
use App\Models\WeightPosition;
use App\Services\Analysis\GenericSportAnalysisService;
use App\Services\Analysis\SportAnalysisConfig;
use App\Services\CricketAnalysisService;
use App\Support\StatMath;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PublicPlayerProfileController extends Controller
{
    /** Sport slug to emoji icon mapping. */
    public const SPORT_ICONS = [
        'cricket' => '🏏',
        'football' => '⚽',
        'basketball' => '🏀',
        'athletics' => '🏃',
        'swimming' => '🏊',
        'badminton' => '🏸',
        'tennis' => '🎾',
        'table-tennis' => '🏓',
        'hockey' => '🏑',
        'rugby' => '🏉',
        'volleyball' => '🏐',
        'beach-volleyball' => '🏖️',
        'netball' => '🥅',
        'base-ball' => '⚾',
        'boxing' => '🥊',
        'karate' => '🥋',
        'judo' => '🥋',
        'chess' => '♟️',
        'elle' => '🏏',
        'kabadi' => '🤼',
        'soft-ball-cricket' => '🏏',
        'shooting' => '🎯',
    ];

    /**
     * AJAX/JSON Live search endpoint for the global search bar.
     * GET /search/players?q=...
     */
    public function search(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 1) {
            return response()->json(['results' => []]);
        }

        $players = Player::query()
            ->with(['user', 'playerSports.sport', 'playerTeams'])
            ->where(function ($q) use ($query) {
                $q->where('full_name', 'like', '%'.$query.'%')
                  ->orWhereHas('user', function ($u) use ($query) {
                      $u->where('name', 'like', '%'.$query.'%')
                        ->orWhere('email', 'like', '%'.$query.'%');
                  });
            })
            ->limit(10)
            ->get();

        $results = $players->map(function (Player $player) {
            $displayName = $player->full_name ?: ($player->user->name ?? 'Unnamed Player');
            $photoUrl = $player->photo_url ? Storage::disk('public')->url($player->photo_url) : null;

            $sports = $player->playerSports
                ->where('status', PlayerSport::STATUS_COMPLETED)
                ->map(fn ($ps) => [
                    'name' => $ps->sport?->name ?? 'Sport',
                    'slug' => $ps->sport?->slug ?? '',
                    'icon' => self::SPORT_ICONS[$ps->sport?->slug ?? ''] ?? '🏅',
                ])
                ->filter(fn ($s) => ! empty($s['slug']))
                ->values();

            // Fallback: If no completed player_sports row, check if Cricket or another profile exists
            if ($sports->isEmpty() && $player->cricketProfile()->exists()) {
                $sports->push([
                    'name' => 'Cricket',
                    'slug' => 'cricket',
                    'icon' => '🏏',
                ]);
            }

            $primaryTeam = $player->playerTeams->first()?->team_name;

            return [
                'id' => $player->id,
                'name' => $displayName,
                'country' => $player->country,
                'photo_url' => $photoUrl,
                'sports' => $sports,
                'primary_team' => $primaryTeam,
                'url' => route('public.players.show', ['player' => $player->id]),
            ];
        });

        return response()->json(['results' => $results]);
    }

    /**
     * Dedicated Cricinfo-Style Public Player Profile Page.
     * GET /players/{player}/{sportSlug?}
     */
    public function show(
        Request $request,
        CricketAnalysisService $cricketService,
        GenericSportAnalysisService $genericService,
        Player $player,
        ?string $sportSlug = null
    ): View {
        $player->load(['user', 'playerSports.sport', 'photos', 'playerTeams', 'playerAchievements.achievement']);

        // Collect all sports the player has registered or completed
        $playerSportsList = $player->playerSports
            ->filter(fn ($ps) => $ps->sport !== null)
            ->map(fn ($ps) => [
                'id' => $ps->sport->id,
                'name' => $ps->sport->name,
                'slug' => $ps->sport->slug,
                'icon' => self::SPORT_ICONS[$ps->sport->slug] ?? '🏅',
            ])
            ->keyBy('slug');

        // Check additional profile relations in case player_sports status wasn't marked
        if ($player->cricketProfile()->exists() && ! $playerSportsList->has('cricket')) {
            $sport = Sport::where('slug', 'cricket')->first();
            if ($sport) {
                $playerSportsList->put('cricket', [
                    'id' => $sport->id,
                    'name' => $sport->name,
                    'slug' => $sport->slug,
                    'icon' => '🏏',
                ]);
            }
        }

        // Determine active sport
        $activeSlug = $sportSlug;
        if (! $activeSlug || ! $playerSportsList->has($activeSlug)) {
            $activeSlug = $playerSportsList->keys()->first() ?? 'cricket';
        }

        $activeSport = Sport::where('slug', $activeSlug)->first();

        // Basic Profile Info
        $displayName = $player->full_name ?: ($player->user->name ?? 'Unnamed Player');
        $photoUrl = $player->photo_url ? Storage::disk('public')->url($player->photo_url) : null;
        $coverPhotoUrl = $player->cover_photo_url ? Storage::disk('public')->url($player->cover_photo_url) : null;

        // Common lookups cache
        $lookups = [
            'formats' => Format::pluck('name', 'id')->all(),
            'age_categories' => AgeCategory::pluck('name', 'id')->all(),
            'match_categories' => MatchCategory::pluck('name', 'id')->all(),
            'cricket_categories' => CricketCategory::pluck('name', 'id')->all(),
            'cricket_divisions' => CricketDivision::pluck('name', 'id')->all(),
            'cricket_match_types' => CricketMatchType::pluck('name', 'id')->all(),
            'field_positions' => FieldPosition::pluck('name', 'id')->all(),
            'drop_reasons' => DropReason::pluck('name', 'id')->all(),
            'athletics_events' => AthleticsEvent::pluck('name', 'id')->all(),
            'swimming_events' => SwimmingEvent::pluck('name', 'id')->all(),
            'boxing_weight_classes' => BoxingWeightClass::pluck('name', 'id')->all(),
            'karate_styles' => KarateStyle::pluck('name', 'id')->all(),
            'weight_positions' => WeightPosition::pluck('label', 'id')->all(),
            'competition_levels' => CompetitionLevel::pluck('name', 'id')->all(),
        ];

        // Teams & Logos for Active Sport
        $teamNames = [];
        $teamLogos = [];
        $collegeLogoUrl = null;

        if ($activeSport) {
            $teamNames = PlayerTeam::where('player_id', $player->id)
                ->where('sport_id', $activeSport->id)
                ->pluck('team_name')
                ->all();

            $teamLogos = PlayerTeamLogo::where('player_id', $player->id)
                ->where('sport_id', $activeSport->id)
                ->get()
                ->mapWithKeys(fn ($l) => [$l->team_name => Storage::disk('public')->url($l->logo_path)])
                ->all();

            $collegeLogo = PlayerCollegeLogo::where('player_id', $player->id)
                ->where('sport_id', $activeSport->id)
                ->first();
            if ($collegeLogo && $collegeLogo->logo_path) {
                $collegeLogoUrl = Storage::disk('public')->url($collegeLogo->logo_path);
            }
        }

        // Gallery Photos
        $galleryPhotos = $player->photos->map(fn ($p) => [
            'id' => $p->id,
            'url' => Storage::disk('public')->url($p->path),
        ])->all();

        // Achievements for Active Sport (or all if active sport has none)
        $achievements = $player->playerAchievements
            ->filter(fn ($pa) => ! $activeSport || ! $pa->achievement?->sport_id || $pa->achievement->sport_id === $activeSport->id)
            ->map(fn ($pa) => [
                'title' => $pa->achievement?->title ?? 'Achievement',
                'description' => $pa->achievement?->description,
                'icon' => $pa->achievement?->icon ?? '🏆',
                'color' => $pa->achievement?->color ?? '#f59e0b',
                'unlocked_at' => $pa->unlocked_at?->format('d M Y'),
                'value' => $pa->achieved_value,
            ])
            ->values()
            ->all();

        // Sport Specific Details
        $sportData = $this->buildSportData($player, $activeSlug, $activeSport, $lookups, $cricketService, $genericService);

        // Merge college logo fallback from profile if not in PlayerCollegeLogo table
        if (! $collegeLogoUrl && ! empty($sportData['profile']?->college_logo_path)) {
            $collegeLogoUrl = Storage::disk('public')->url($sportData['profile']->college_logo_path);
        }

        // Bio details formatting
        $bornDateFormatted = $sportData['born'] ? Carbon::parse($sportData['born'])->format('d M Y') : null;
        $detailedAge = $this->calculateDetailedAge($sportData['born'], $sportData['age']);

        return view('public.players.show', compact(
            'player',
            'displayName',
            'photoUrl',
            'coverPhotoUrl',
            'playerSportsList',
            'activeSlug',
            'activeSport',
            'teamNames',
            'teamLogos',
            'collegeLogoUrl',
            'galleryPhotos',
            'achievements',
            'sportData',
            'bornDateFormatted',
            'detailedAge'
        ));
    }

    /**
     * Builds comprehensive sport profile data (career stats, recent matches, technical attributes).
     */
    private function buildSportData(
        Player $player,
        string $slug,
        ?Sport $sport,
        array $lookups,
        CricketAnalysisService $cricketService,
        GenericSportAnalysisService $genericService
    ): array {
        $result = [
            'type' => $slug,
            'born' => null,
            'age' => null,
            'height' => null,
            'college_university' => null,
            'overview_fields' => [],
            'kpi_cards' => [],
            'stat_tables' => [],
            'recent_tables' => [],
            'personal_bests' => [],
            'profile' => null,
        ];

        // 1. CRICKET
        if ($slug === 'cricket') {
            $profile = $player->cricketProfile()
                ->with(['battingStats', 'bowlingStats', 'recentMatches', 'dropCatches'])
                ->first();

            $result['profile'] = $profile;
            $result['born'] = $profile?->born;
            $result['age'] = $profile?->age;
            $result['height'] = $profile?->height;
            $result['college_university'] = $profile?->college_university;

            $result['overview_fields'] = [
                ['label' => 'Playing Role', 'value' => $profile?->playing_role],
                ['label' => 'Batting Style', 'value' => $profile?->batting_style],
                ['label' => 'Bowling Style', 'value' => $profile?->bowling_style],
                ['label' => 'Height', 'value' => $profile?->height],
                ['label' => 'Education / College', 'value' => $profile?->college_university],
            ];

            // Delivery breakdown
            $result['pitching_line_breakdown'] = $profile?->pitching_line_breakdown ?? [];
            $result['ball_type_breakdown'] = $profile?->ball_type_breakdown ?? [];

            // Analysis overview
            $analysis = $cricketService->build($player, null);
            $battingOverview = $analysis['overview']['career_batting'] ?? null;
            $bowlingOverview = $analysis['overview']['career_bowling'] ?? null;

            if ($battingOverview && ($battingOverview['matches'] > 0 || $battingOverview['runs'] > 0)) {
                $result['kpi_cards'][] = ['label' => 'Total Runs', 'value' => $battingOverview['runs'] ?? 0, 'sub' => 'Avg: '.($battingOverview['average'] !== null ? number_format($battingOverview['average'], 2) : '—'), 'color' => '#f59e0b'];
                $result['kpi_cards'][] = ['label' => 'Batting SR', 'value' => $battingOverview['strike_rate'] ? number_format($battingOverview['strike_rate'], 1) : '—', 'sub' => 'HS: '.($battingOverview['highest_score'] ?? '—'), 'color' => '#fbbf24'];
            }
            if ($bowlingOverview && ($bowlingOverview['overs'] > 0 || $bowlingOverview['wickets'] > 0)) {
                $result['kpi_cards'][] = ['label' => 'Wickets', 'value' => $bowlingOverview['wickets'] ?? 0, 'sub' => 'Best: '.($bowlingOverview['best_bowling'] ?? '—'), 'color' => '#38bdf8'];
                $result['kpi_cards'][] = ['label' => 'Economy', 'value' => $bowlingOverview['economy'] !== null ? number_format($bowlingOverview['economy'], 2) : '—', 'sub' => '5w: '.($bowlingOverview['five_wickets'] ?? 0), 'color' => '#818cf8'];
            }

            // Batting Table
            $result['batting_stats'] = ($profile?->battingStats ?? collect())->sortByDesc('year')->map(fn ($row) => [
                'format' => $lookups['cricket_divisions'][$row->format_id] ?? '—',
                'category' => $lookups['cricket_categories'][$row->age_category_id] ?? '—',
                'match_type' => $lookups['cricket_match_types'][$row->cricket_match_type_id] ?? '—',
                'matches' => $row->matches,
                'won' => $row->won,
                'lost' => $row->lost,
                'innings' => $row->innings,
                'not_out' => $row->not_out,
                'runs' => $row->runs,
                'hs' => $row->hs,
                'average' => $row->average,
                'sr' => $row->sr,
                'hundreds' => $row->hundreds,
                'fifties' => $row->fifties,
                'fours' => $row->fours,
                'sixes' => $row->sixes,
                'catches' => $row->catches,
                'stumpings' => $row->stumpings,
                'run_outs' => $row->run_outs,
            ])->all();

            // Bowling Table
            $result['bowling_stats'] = ($profile?->bowlingStats ?? collect())->sortByDesc('year')->map(fn ($row) => [
                'format' => $lookups['cricket_divisions'][$row->format_id] ?? '—',
                'category' => $lookups['cricket_categories'][$row->age_category_id] ?? '—',
                'match_type' => $lookups['cricket_match_types'][$row->cricket_match_type_id] ?? '—',
                'matches' => $row->matches,
                'innings' => $row->innings,
                'balls' => $row->balls,
                'dot_balls' => $row->dot_balls,
                'runs' => $row->runs,
                'wickets' => $row->wickets,
                'bbi' => $row->bbi,
                'bbm' => $row->bbm,
                'average' => $row->average,
                'economy' => $row->economy,
                'sr' => $row->sr,
                'four_w' => $row->four_w,
                'five_w' => $row->five_w,
                'ten_w' => $row->ten_w,
            ])->all();

            // Recent Matches
            $result['recent_matches'] = ($profile?->recentMatches ?? collect())->sortByDesc('match_date')->map(fn ($row) => [
                'match_date' => $row->match_date ? Carbon::parse($row->match_date)->format('d M Y') : '—',
                'opponent' => $row->opponent ?: 'Opponent',
                'played_xi' => (bool) $row->played_xi,
                'runs' => $row->runs,
                'balls' => $row->balls,
                'fours' => $row->fours,
                'sixes' => $row->sixes,
                'overs' => $row->overs,
                'maidens' => $row->maidens,
                'wickets' => $row->wickets,
                'catches' => $row->catches,
                'stumpings' => $row->stumpings,
            ])->all();

            // Drop Catches
            $result['drop_catches'] = ($profile?->dropCatches ?? collect())->map(fn ($row) => [
                'format' => $lookups['cricket_divisions'][$row->format_id] ?? '—',
                'category' => $lookups['cricket_categories'][$row->age_category_id] ?? '—',
                'field_position' => $lookups['field_positions'][$row->field_position_id] ?? '—',
                'drop_reason' => $lookups['drop_reasons'][$row->drop_reason_id] ?? '—',
            ])->all();

            return $result;
        }

        // 2. RACKET SPORTS (Tennis, Badminton, Table Tennis)
        if (in_array($slug, ['tennis', 'badminton', 'table-tennis'], true)) {
            $profile = RacketSportProfile::where('player_id', $player->id)
                ->where('sport_id', $sport?->id)
                ->with(['careerStats', 'recentMatches'])
                ->first();

            $result['profile'] = $profile;
            $result['born'] = $profile?->born;
            $result['age'] = $profile?->age;
            $result['height'] = $profile?->height;
            $result['college_university'] = $profile?->college_university;

            $result['overview_fields'] = [
                ['label' => 'Dominant Hand', 'value' => ucfirst((string) $profile?->dominant_hand)],
                ['label' => 'Current Ranking', 'value' => $profile?->current_ranking ? '#'.$profile->current_ranking : null],
                ['label' => 'Weight', 'value' => $profile?->weight ? $profile->weight.' kg' : null],
                ['label' => 'Height', 'value' => $profile?->height],
                ['label' => 'Education / College', 'value' => $profile?->college_university],
            ];

            // Career Stats table
            $careerRows = ($profile?->careerStats ?? collect())->sortByDesc('year')->map(fn ($row) => [
                'category' => ucfirst((string) $row->category),
                'format' => $lookups['formats'][$row->format_id] ?? '—',
                'age_category' => $lookups['age_categories'][$row->age_category_id] ?? '—',
                'matches' => $row->matches,
                'win' => $row->win,
                'lost' => $row->lost,
                'set_win' => $row->set_win,
                'set_lost' => $row->set_lost,
                'champion' => $row->champion,
                'second_place' => $row->second_place,
                'semi_final' => $row->semi_final,
                'win_pct' => StatMath::safeDivide((int) $row->win * 100, (int) $row->win + (int) $row->lost, 1).'%',
            ])->all();

            $result['stat_tables'][] = [
                'title' => ucfirst($slug).' Career Statistics',
                'columns' => ['Category', 'Format', 'Age', 'Matches', 'Won', 'Lost', 'Sets Won', 'Sets Lost', 'Champion', 'Runner-up', 'Semi-final', 'Win %'],
                'keys' => ['category', 'format', 'age_category', 'matches', 'win', 'lost', 'set_win', 'set_lost', 'champion', 'second_place', 'semi_final', 'win_pct'],
                'rows' => $careerRows,
            ];

            // Recent Matches
            $recentRows = ($profile?->recentMatches ?? collect())->sortByDesc('match_date')->map(fn ($row) => [
                'match_date' => $row->match_date ? Carbon::parse($row->match_date)->format('d M Y') : '—',
                'opponent' => $row->opponent ?: 'Opponent',
                'venue' => $row->venue ?: '—',
                'result' => $row->win ? 'WIN' : ($row->lost ? 'LOSS' : '—'),
                'score' => ($row->my_score !== null || $row->opponent_score !== null) ? ($row->my_score ?? 0).' - '.($row->opponent_score ?? 0) : '—',
            ])->all();

            $result['recent_tables'][] = [
                'title' => 'Recent Matches',
                'columns' => ['Date', 'Match vs', 'Venue', 'Result', 'Score'],
                'keys' => ['match_date', 'opponent', 'venue', 'result', 'score'],
                'rows' => $recentRows,
            ];

            return $result;
        }

        // 3. SOFTBALL CRICKET
        if ($slug === 'soft-ball-cricket') {
            $profile = SoftBallCricketProfile::where('player_id', $player->id)
                ->with(['battingStats', 'bowlingStats', 'recentMatches'])
                ->first();

            $result['profile'] = $profile;
            $result['born'] = $profile?->born;
            $result['age'] = $profile?->age;
            $result['height'] = $profile?->height;
            $result['college_university'] = $profile?->college_university;

            $result['overview_fields'] = [
                ['label' => 'Playing Role', 'value' => $profile?->playing_role],
                ['label' => 'Batting Style', 'value' => $profile?->batting_style],
                ['label' => 'Bowling Style', 'value' => $profile?->bowling_style],
                ['label' => 'Height', 'value' => $profile?->height],
                ['label' => 'Education / College', 'value' => $profile?->college_university],
            ];

            $result['batting_stats'] = ($profile?->battingStats ?? collect())->sortByDesc('year')->map(fn ($row) => [
                'format' => $lookups['formats'][$row->format_id] ?? '—',
                'category' => $lookups['age_categories'][$row->age_category_id] ?? '—',
                'matches' => $row->matches,
                'innings' => $row->innings,
                'not_out' => $row->not_out,
                'runs' => $row->runs,
                'hs' => $row->hs,
                'average' => $row->average,
                'sr' => $row->sr,
                'hundreds' => $row->hundreds,
                'fifties' => $row->fifties,
                'fours' => $row->fours,
                'sixes' => $row->sixes,
                'catches' => $row->catches,
            ])->all();

            $result['bowling_stats'] = ($profile?->bowlingStats ?? collect())->sortByDesc('year')->map(fn ($row) => [
                'format' => $lookups['formats'][$row->format_id] ?? '—',
                'category' => $lookups['age_categories'][$row->age_category_id] ?? '—',
                'matches' => $row->matches,
                'innings' => $row->innings,
                'balls' => $row->balls,
                'runs' => $row->runs,
                'wickets' => $row->wickets,
                'bbi' => $row->bbi,
                'average' => $row->average,
                'economy' => $row->economy,
                'five_w' => $row->five_w,
            ])->all();

            $result['recent_matches'] = ($profile?->recentMatches ?? collect())->sortByDesc('match_date')->map(fn ($row) => [
                'match_date' => $row->match_date ? Carbon::parse($row->match_date)->format('d M Y') : '—',
                'opponent' => $row->opponent ?: 'Opponent',
                'runs' => $row->runs,
                'balls' => $row->balls,
                'wickets' => $row->wickets,
                'overs' => $row->overs,
                'catches' => $row->catches,
            ])->all();

            return $result;
        }

        // 4. GENERIC SPORTS (Football, Basketball, Athletics, Swimming, Rugby, Hockey, Volleyball, Netball, Baseball, Boxing, Karate, Judo, Chess, Kabadi, Elle)
        $config = SportAnalysisConfig::get($slug);
        if ($config) {
            $profileRelation = $config['profile_relation'];
            $profile = $player->{$profileRelation}()->first();

            $result['profile'] = $profile;
            $result['born'] = $profile?->born;
            $result['age'] = $profile?->age;
            $result['height'] = $profile?->height;
            $result['college_university'] = $profile?->college_university;

            // Overview attributes tailored per sport
            $fields = [];
            if (isset($profile->player_position)) {
                $fields[] = ['label' => 'Position', 'value' => $profile->player_position];
            }
            if (isset($profile->dominant_leg)) {
                $fields[] = ['label' => 'Dominant Leg', 'value' => ucfirst((string) $profile->dominant_leg)];
            }
            if (isset($profile->dominant_hand)) {
                $fields[] = ['label' => 'Dominant Hand', 'value' => ucfirst((string) $profile->dominant_hand)];
            }
            if (isset($profile->weight)) {
                $fields[] = ['label' => 'Weight', 'value' => $profile->weight.' kg'];
            }
            if (isset($profile->fide_id)) {
                $fields[] = ['label' => 'FIDE ID', 'value' => $profile->fide_id];
            }
            if (isset($profile->current_ranking)) {
                $fields[] = ['label' => 'Ranking', 'value' => '#'.$profile->current_ranking];
            }
            $fields[] = ['label' => 'Height', 'value' => $profile?->height];
            $fields[] = ['label' => 'Education / College', 'value' => $profile?->college_university];
            $result['overview_fields'] = array_filter($fields, fn ($f) => ! empty($f['value']));

            // Personal Bests (Athletics / Swimming)
            if ($slug === 'athletics' && method_exists($profile, 'personalBests')) {
                $result['personal_bests'] = $profile->personalBests->map(fn ($pb) => [
                    'event' => $lookups['athletics_events'][$pb->athletics_event_id] ?? 'Event',
                    'record' => $pb->personal_best ?: '—',
                ])->all();
            } elseif ($slug === 'swimming' && method_exists($profile, 'personalBests')) {
                $result['personal_bests'] = $profile->personalBests->map(fn ($pb) => [
                    'event' => $lookups['swimming_events'][$pb->swimming_event_id] ?? 'Event',
                    'record' => $pb->personal_best ?: '—',
                ])->all();
            }

            // Generic Career Stats
            $careerRelation = $config['career_relation'];
            $rawCareerStats = ($profile?->{$careerRelation} ?? collect())->sortByDesc('year');

            if ($rawCareerStats->isNotEmpty()) {
                $firstRow = $rawCareerStats->first()->toArray();
                $displayCols = [];
                $keys = [];

                // Standard format & category columns
                if (array_key_exists('format_id', $firstRow)) {
                    $displayCols[] = 'Format';
                    $keys[] = 'format';
                }
                if (array_key_exists('age_category_id', $firstRow)) {
                    $displayCols[] = 'Age';
                    $keys[] = 'age_category';
                }
                if (array_key_exists('match_category_id', $firstRow)) {
                    $displayCols[] = 'Category';
                    $keys[] = 'match_category';
                }
                if (array_key_exists('year', $firstRow)) {
                    $displayCols[] = 'Year';
                    $keys[] = 'year';
                }

                // Append each numerical stat column
                foreach ($config['sum_columns'] as $col) {
                    if (array_key_exists($col, $firstRow)) {
                        $displayCols[] = ucwords(str_replace('_', ' ', $col));
                        $keys[] = $col;
                    }
                }

                $formattedRows = $rawCareerStats->map(function ($row) use ($lookups, $config) {
                    $data = $row->toArray();
                    $data['format'] = $lookups['formats'][$row->format_id ?? 0] ?? '—';
                    $data['age_category'] = $lookups['age_categories'][$row->age_category_id ?? 0] ?? '—';
                    $data['match_category'] = $lookups['match_categories'][$row->match_category_id ?? 0] ?? '—';
                    $data['year'] = $row->year ?? '—';
                    return $data;
                })->all();

                $result['stat_tables'][] = [
                    'title' => ucwords(str_replace('-', ' ', $slug)).' Career Statistics',
                    'columns' => $displayCols,
                    'keys' => $keys,
                    'rows' => $formattedRows,
                ];
            }

            // Generic Recent Matches / Events
            $recentRelation = $config['recent_relation'];
            $rawRecent = ($profile?->{$recentRelation} ?? collect())->sortByDesc($config['recent_date_column'] ?? 'created_at');

            if ($rawRecent->isNotEmpty()) {
                $formattedRecent = $rawRecent->map(function ($row) {
                    $opponent = $row->opponent ?? ($row->venue ?? 'Match / Event');
                    $date = $row->match_date ?? ($row->event_date ?? null);
                    $resultText = '—';
                    if (isset($row->win) && isset($row->lost)) {
                        $resultText = $row->win ? 'WIN' : ($row->lost ? 'LOSS' : (isset($row->drawn) && $row->drawn ? 'DRAW' : '—'));
                    } elseif (isset($row->place)) {
                        $resultText = $row->place == 1 ? 'GOLD' : ($row->place == 2 ? 'SILVER' : ($row->place == 3 ? 'BRONZE' : 'Rank '.$row->place));
                    }

                    return [
                        'date' => $date ? Carbon::parse($date)->format('d M Y') : '—',
                        'opponent' => $opponent,
                        'result' => $resultText,
                        'details' => $this->summarizeRecentRow($row),
                    ];
                })->all();

                $result['recent_tables'][] = [
                    'title' => 'Recent Matches & Events',
                    'columns' => ['Date', 'Match / Event', 'Result', 'Performance / Score'],
                    'keys' => ['date', 'opponent', 'result', 'details'],
                    'rows' => $formattedRecent,
                ];
            }

            // Generic analysis KPI highlights
            $analysis = $genericService->build($player, $slug, null);
            if (! empty($analysis['overview'])) {
                foreach ($analysis['overview'] as $k => $val) {
                    if ($val !== null && $val !== '' && $val !== '0' && $val !== 0) {
                        $label = ucwords(str_replace('_', ' ', $k));
                        $formattedVal = is_numeric($val) && str_contains($k, 'percentage') ? number_format((float) $val, 1).'%' : $val;
                        $result['kpi_cards'][] = [
                            'label' => $label,
                            'value' => $formattedVal,
                            'sub' => 'Career',
                            'color' => '#f59e0b',
                        ];
                    }
                }
            }

            return $result;
        }

        return $result;
    }

    /**
     * Creates a concise human performance summary from a generic match row.
     */
    private function summarizeRecentRow(object $row): string
    {
        $parts = [];
        if (isset($row->goals) && $row->goals > 0) $parts[] = $row->goals.' '.\Illuminate\Support\Str::plural('Goal', $row->goals);
        if (isset($row->assists) && $row->assists > 0) $parts[] = $row->assists.' '.\Illuminate\Support\Str::plural('Assist', $row->assists);
        if (isset($row->points) && $row->points > 0) $parts[] = $row->points.' Pts';
        if (isset($row->rebounds) && $row->rebounds > 0) $parts[] = $row->rebounds.' Reb';
        if (isset($row->hits) && $row->hits > 0) $parts[] = $row->hits.' Hits';
        if (isset($row->runs) && $row->runs > 0) $parts[] = $row->runs.' Runs';
        if (isset($row->timing) && ! empty($row->timing)) $parts[] = 'Time: '.$row->timing;
        if (isset($row->distance) && ! empty($row->distance)) $parts[] = 'Dist: '.$row->distance;
        if (isset($row->round) && ! empty($row->round)) $parts[] = 'Round '.$row->round;
        if (isset($row->method) && ! empty($row->method)) $parts[] = 'via '.$row->method;

        return ! empty($parts) ? implode(' • ', $parts) : 'Completed';
    }

    /**
     * Formats detailed age (e.g., "26y 145d") from DOB or integer age.
     */
    private function calculateDetailedAge(?string $born, mixed $fallbackAge): ?string
    {
        if ($born) {
            try {
                $dob = Carbon::parse($born);
                $now = Carbon::now();
                $diff = $dob->diff($now);
                return "{$diff->y}y {$diff->d}d";
            } catch (\Throwable) {
                // fallback below
            }
        }

        return $fallbackAge ? "{$fallbackAge} years" : null;
    }
}
