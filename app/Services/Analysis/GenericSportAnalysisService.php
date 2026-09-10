<?php

namespace App\Services\Analysis;

use App\Models\Format;
use App\Models\Player;
use Illuminate\Support\Collection;

/**
 * Generic counterpart to CricketAnalysisService for the 16 sports whose
 * career stats are a single table of plain integer counts (see
 * SportAnalysisConfig's docblock for which sports qualify and why the rest
 * don't). One aggregation routine — sum the configured columns, then derive
 * rate fields from those sums — covers all of them instead of duplicating
 * per-sport services.
 */
class GenericSportAnalysisService
{
    private const RECENT_FORM_LIMIT = 10;

    public function build(Player $player, string $sport, ?int $formatId): array
    {
        $config = SportAnalysisConfig::get($sport);
        if (! $config) {
            return $this->emptyResult();
        }

        $profile = $player->{$config['profile_relation']};
        if (! $profile) {
            return $this->emptyResult();
        }

        /** @var Collection $allRows */
        $allRows = $profile->{$config['career_relation']};
        $scopedRows = $formatId ? $allRows->where('format_id', $formatId)->values() : $allRows;

        $career = $this->aggregate($scopedRows, $config);
        $byFormat = $this->groupByFormat($allRows, $config);
        $availableFormats = $this->availableFormats($allRows);
        $selectedFormat = $formatId ? $availableFormats->firstWhere('id', $formatId) : null;

        $recentRelation = $config['recent_relation'] ?? null;
        $recentRows = collect();
        $hasRecent = false;
        if ($recentRelation) {
            $dateColumn = $config['recent_date_column'] ?? 'created_at';
            $recentRows = $profile->{$recentRelation}()
                ->orderByDesc($dateColumn)
                ->orderByDesc('id')
                ->limit(self::RECENT_FORM_LIMIT)
                ->get()
                ->reverse()
                ->values();
            $hasRecent = $profile->{$recentRelation}()->exists();
        }

        $personalBests = [];
        if (isset($config['personal_bests_relation'])) {
            $personalBests = $profile->{$config['personal_bests_relation']}()
                ->with('event')
                ->get()
                ->map(fn ($pb) => [
                    'event' => $pb->event->name ?? null,
                    'value' => $pb->personal_best,
                ])
                ->all();
        }

        $hasAnyStats = $allRows->isNotEmpty() || $hasRecent || count($personalBests) > 0;

        return [
            'has_profile' => true,
            'has_any_stats' => $hasAnyStats,
            'filter' => [
                'format_id' => $formatId,
                'format_name' => $selectedFormat['name'] ?? null,
            ],
            'available_formats' => $availableFormats->values()->all(),
            'overview' => $this->overview($career, $config),
            'career' => $career,
            'by_format' => $byFormat,
            'personal_bests' => $personalBests,
            'recent_form' => $recentRows->map(fn ($row) => $this->recentRowToArray($row))->all(),
        ];
    }

    private function emptyResult(): array
    {
        return [
            'has_profile' => false,
            'has_any_stats' => false,
            'filter' => ['format_id' => null, 'format_name' => null],
            'available_formats' => [],
            'overview' => [],
            'career' => [],
            'by_format' => [],
            'personal_bests' => [],
            'recent_form' => [],
        ];
    }

    /**
     * @param  Collection<int, mixed>  $rows
     */
    private function aggregate(Collection $rows, array $config): array
    {
        $totals = [];
        foreach ($config['sum_columns'] as $column) {
            $totals[$column] = (int) $rows->sum($column);
        }
        foreach ($config['derived'] ?? [] as $key => $fn) {
            $totals[$key] = $fn($totals);
        }

        return $totals;
    }

    /**
     * Only the configured `overview` keys, in order — falls back to the
     * first four summed columns when a sport doesn't specify one.
     */
    private function overview(array $career, array $config): array
    {
        $keys = $config['overview'] ?? array_slice($config['sum_columns'], 0, 4);
        $overview = [];
        foreach ($keys as $key) {
            $overview[$key] = $career[$key] ?? null;
        }

        return $overview;
    }

    /**
     * @param  Collection<int, mixed>  $rows
     * @return list<array<string, mixed>>
     */
    private function groupByFormat(Collection $rows, array $config): array
    {
        if ($rows->isEmpty()) {
            return [];
        }

        $formats = Format::whereIn('id', $rows->pluck('format_id')->filter()->unique())
            ->orderBy('sort_order')
            ->get(['id', 'name']);

        return $formats
            ->map(function (Format $format) use ($rows, $config) {
                $formatRows = $rows->where('format_id', $format->id)->values();
                if ($formatRows->isEmpty()) {
                    return null;
                }

                return array_merge(
                    ['format_id' => $format->id, 'format_name' => $format->name],
                    $this->aggregate($formatRows, $config)
                );
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, mixed>  $rows
     * @return Collection<int, array{id:int, name:string}>
     */
    private function availableFormats(Collection $rows): Collection
    {
        $ids = $rows->pluck('format_id')->filter()->unique();
        if ($ids->isEmpty()) {
            return collect();
        }

        return Format::whereIn('id', $ids)
            ->orderBy('sort_order')
            ->get(['id', 'name'])
            ->map(fn (Format $f) => ['id' => $f->id, 'name' => $f->name]);
    }

    /**
     * Every fillable column on the row except the FK back to its profile —
     * generic stand-in for a per-sport "which columns does Recent Form
     * show" config, since these tables have no rate fields to recompute.
     */
    private function recentRowToArray($row): array
    {
        $data = [];
        foreach ($row->getFillable() as $key) {
            if (str_ends_with($key, '_profile_id')) {
                continue;
            }
            $value = $row->{$key};
            if ($value instanceof \Illuminate\Support\Carbon) {
                $value = $value->toDateString();
            }
            $data[$key] = $value;
        }

        return $data;
    }
}
