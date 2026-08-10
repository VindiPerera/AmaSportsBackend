<?php

namespace App\Services;

use App\Models\Format;
use App\Models\Player;
use Illuminate\Support\Collection;

/**
 * Builds the aggregated payload for GET /player/cricket-analysis.
 *
 * IMPORTANT — this schema stores *pre-aggregated* rows, not raw
 * ball-by-ball data: `cricket_batting_stats` / `cricket_bowling_stats` are
 * one repeatable row per Format/Age/Category/MatchType combination the
 * player themselves typed in (see spec 6.2), each already carrying its own
 * "average"/"sr"/"economy" etc. That means combining *multiple* rows into
 * one bucket (e.g. the "All formats" career total, or a Format that has
 * more than one row) is only safe for fields that are plain sums (matches,
 * runs, wickets, balls, hundreds, ...). Rate fields (average, economy,
 * strike rate) are recomputed from those summed raw counts instead of
 * summing the rows' own rate values, which would be mathematically wrong.
 *
 * One rate is a genuine schema gap: `cricket_batting_stats` never stored
 * "balls faced", only the player-entered "sr" per row. With no balls-faced
 * total to divide runs by, a combined Strike Rate can only be trusted when
 * exactly one row feeds the bucket (then the row's own sr is used as-is);
 * with more than one contributing row it comes back `null` rather than
 * fabricate a number. See the smallest-schema-addition note below.
 */
class CricketAnalysisService
{
    private const RECENT_FORM_LIMIT = 10;

    public function build(Player $player, ?int $formatId): array
    {
        $profile = $player->cricketProfile()
            ->with(['battingStats', 'bowlingStats'])
            ->first();

        if (! $profile) {
            return $this->emptyResult();
        }

        $battingAll = $profile->battingStats;
        $bowlingAll = $profile->bowlingStats;

        $battingScoped = $formatId ? $battingAll->where('format_id', $formatId)->values() : $battingAll;
        $bowlingScoped = $formatId ? $bowlingAll->where('format_id', $formatId)->values() : $bowlingAll;

        $careerBatting = $this->aggregateBatting($battingScoped);
        $careerBowling = $this->aggregateBowling($bowlingScoped);

        $recentMatches = $profile->recentMatches()
            ->orderByDesc('match_date')
            ->orderByDesc('id')
            ->limit(self::RECENT_FORM_LIMIT)
            ->get()
            ->reverse()
            ->values();

        $availableFormats = $this->availableFormats($battingAll, $bowlingAll);
        $selectedFormat = $formatId ? $availableFormats->firstWhere('id', $formatId) : null;

        $hasAnyStats = $battingAll->isNotEmpty() || $bowlingAll->isNotEmpty() || $profile->recentMatches()->exists();

        return [
            'has_profile' => true,
            'has_any_stats' => $hasAnyStats,
            'filter' => [
                'format_id' => $formatId,
                'format_name' => $selectedFormat['name'] ?? null,
            ],
            'available_formats' => $availableFormats->values()->all(),
            'overview' => [
                'matches' => max($careerBatting['matches'], $careerBowling['matches']),
                'runs' => $careerBatting['runs'],
                'wickets' => $careerBowling['wickets'],
                'batting_average' => $careerBatting['average'],
                'bowling_average' => $careerBowling['average'],
                'win_percentage' => $careerBatting['win_percentage'],
            ],
            'batting' => [
                'career' => $careerBatting,
                'by_format' => $this->groupByFormat($battingAll, fn (Collection $rows) => $this->aggregateBatting($rows)),
            ],
            'bowling' => [
                'career' => $careerBowling,
                'by_format' => $this->groupByFormat($bowlingAll, fn (Collection $rows) => $this->aggregateBowling($rows)),
            ],
            'boundaries' => [
                'fours' => $careerBatting['fours'],
                'sixes' => $careerBatting['sixes'],
            ],
            'recent_form' => $recentMatches->map(fn ($m) => [
                'match_date' => $m->match_date?->toDateString(),
                'opponent' => $m->opponent,
                'runs' => $m->runs,
                'balls' => $m->balls,
                'fours' => $m->fours,
                'sixes' => $m->sixes,
                'overs' => $m->overs !== null ? (float) $m->overs : null,
                'maidens' => $m->maidens,
                'wickets' => $m->wickets,
                'catches' => $m->catches,
                'stumpings' => $m->stumpings,
                // Computed here (not on the client) so the "Strike Rate trend"
                // chart never has to divide runs/balls itself.
                'strike_rate' => $this->safeDivide((int) $m->runs * 100, (int) $m->balls, 1),
                // Always null — `cricket_recent_matches` has no "runs conceded"
                // column, so an economy rate (runs/over) can't be computed per
                // match. See the class docblock / smallest-schema-addition note.
                'economy' => null,
            ])->all(),
        ];
    }

    private function emptyResult(): array
    {
        $emptyBatting = $this->aggregateBatting(collect());
        $emptyBowling = $this->aggregateBowling(collect());

        return [
            'has_profile' => false,
            'has_any_stats' => false,
            'filter' => ['format_id' => null, 'format_name' => null],
            'available_formats' => [],
            'overview' => [
                'matches' => 0,
                'runs' => 0,
                'wickets' => 0,
                'batting_average' => null,
                'bowling_average' => null,
                'win_percentage' => null,
            ],
            'batting' => ['career' => $emptyBatting, 'by_format' => []],
            'bowling' => ['career' => $emptyBowling, 'by_format' => []],
            'boundaries' => ['fours' => 0, 'sixes' => 0],
            'recent_form' => [],
        ];
    }

    /**
     * @param  Collection<int, \App\Models\CricketBattingStat>  $rows
     */
    private function aggregateBatting(Collection $rows): array
    {
        $matches = (int) $rows->sum('matches');
        $innings = (int) $rows->sum('innings');
        $notOuts = (int) $rows->sum('not_out');
        $runs = (int) $rows->sum('runs');
        $won = (int) $rows->sum('won');
        $lost = (int) $rows->sum('lost');

        return [
            'matches' => $matches,
            'innings' => $innings,
            'not_outs' => $notOuts,
            'runs' => $runs,
            'highest_score' => $this->bestHighScore($rows, 'hs'),
            'average' => $this->safeDivide($runs, $innings - $notOuts),
            // Only trustworthy when a single row feeds this bucket — see
            // class docblock. `cricket_batting_stats` has no "balls faced"
            // column to recompute a combined rate from.
            'strike_rate' => $rows->count() === 1 ? $this->toFloatOrNull($rows->first()->sr) : null,
            'hundreds' => (int) $rows->sum('hundreds'),
            'fifties' => (int) $rows->sum('fifties'),
            'fours' => (int) $rows->sum('fours'),
            'sixes' => (int) $rows->sum('sixes'),
            'catches' => (int) $rows->sum('catches'),
            'stumpings' => (int) $rows->sum('stumpings'),
            'won' => $won,
            'lost' => $lost,
            'win_percentage' => $this->safeDivide($won * 100, $won + $lost, 1),
        ];
    }

    /**
     * @param  Collection<int, \App\Models\CricketBowlingStat>  $rows
     */
    private function aggregateBowling(Collection $rows): array
    {
        $balls = (int) $rows->sum('balls');
        $runs = (int) $rows->sum('runs');
        $wickets = (int) $rows->sum('wickets');

        return [
            'matches' => (int) $rows->sum('matches'),
            'innings' => (int) $rows->sum('innings'),
            'balls' => $balls,
            'runs_conceded' => $runs,
            'wickets' => $wickets,
            'best_bowling_innings' => $this->bestBowlingFigure($rows, 'bbi'),
            'best_bowling_match' => $this->bestBowlingFigure($rows, 'bbm'),
            'average' => $this->safeDivide($runs, $wickets),
            'economy' => $this->safeDivide($runs, $balls / 6),
            'strike_rate' => $this->safeDivide($balls, $wickets),
            'four_w' => (int) $rows->sum('four_w'),
            'five_w' => (int) $rows->sum('five_w'),
            'ten_w' => (int) $rows->sum('ten_w'),
        ];
    }

    /**
     * @param  Collection<int, mixed>  $rows
     * @return list<array<string, mixed>>
     */
    private function groupByFormat(Collection $rows, callable $aggregate): array
    {
        if ($rows->isEmpty()) {
            return [];
        }

        $formats = Format::whereIn('id', $rows->pluck('format_id')->unique())
            ->orderBy('sort_order')
            ->get(['id', 'name']);

        return $formats
            ->map(function (Format $format) use ($rows, $aggregate) {
                $formatRows = $rows->where('format_id', $format->id)->values();
                if ($formatRows->isEmpty()) {
                    return null;
                }

                return array_merge(
                    ['format_id' => $format->id, 'format_name' => $format->name],
                    $aggregate($formatRows)
                );
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, \App\Models\CricketBattingStat>  $battingAll
     * @param  Collection<int, \App\Models\CricketBowlingStat>  $bowlingAll
     * @return Collection<int, array{id:int, name:string}>
     */
    private function availableFormats(Collection $battingAll, Collection $bowlingAll): Collection
    {
        $ids = $battingAll->pluck('format_id')->merge($bowlingAll->pluck('format_id'))->unique();

        if ($ids->isEmpty()) {
            return collect();
        }

        return Format::whereIn('id', $ids)
            ->orderBy('sort_order')
            ->get(['id', 'name'])
            ->map(fn (Format $f) => ['id' => $f->id, 'name' => $f->name]);
    }

    /**
     * Divide-by-zero-safe rate helper — returns `null` (never `Infinity`/NAN)
     * whenever the denominator is missing or zero, per spec §2.
     */
    private function safeDivide(int|float $numerator, int|float $denominator, int $decimals = 2): ?float
    {
        if ($denominator <= 0) {
            return null;
        }

        return round($numerator / $denominator, $decimals);
    }

    private function toFloatOrNull(mixed $value): ?float
    {
        return $value === null ? null : (float) $value;
    }

    /**
     * Picks the best "HS"-style value (e.g. "114*", "87", "-") across rows,
     * highest numeric value first, preferring a not-out ("*") knock on ties.
     */
    private function bestHighScore(Collection $rows, string $field): ?string
    {
        $best = null;

        foreach ($rows as $row) {
            $raw = $row->{$field};
            if (! $raw || ! preg_match('/^(\d+)(\*?)/', trim($raw), $m)) {
                continue;
            }

            $value = (int) $m[1];
            $notOut = $m[2] === '*';

            if ($best === null
                || $value > $best['value']
                || ($value === $best['value'] && $notOut && ! $best['notOut'])
            ) {
                $best = ['value' => $value, 'notOut' => $notOut];
            }
        }

        return $best === null ? null : $best['value'].($best['notOut'] ? '*' : '');
    }

    /**
     * Picks the best "W/R"-style figure (e.g. "4/25") across rows — most
     * wickets first, fewest runs conceded as the tiebreaker.
     */
    private function bestBowlingFigure(Collection $rows, string $field): ?string
    {
        $best = null;

        foreach ($rows as $row) {
            $raw = $row->{$field};
            if (! $raw || ! preg_match('/^(\d+)\D+(\d+)/', trim($raw), $m)) {
                continue;
            }

            $wickets = (int) $m[1];
            $runs = (int) $m[2];

            if ($best === null
                || $wickets > $best['wickets']
                || ($wickets === $best['wickets'] && $runs < $best['runs'])
            ) {
                $best = ['wickets' => $wickets, 'runs' => $runs];
            }
        }

        return $best === null ? null : "{$best['wickets']}/{$best['runs']}";
    }
}
