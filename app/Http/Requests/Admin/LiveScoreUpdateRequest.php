<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Covers Start/Update/Finish on the Live Score Control page for all three
 * admin-supported sports. Field names/shapes mirror the `MultiSportLiveScore`
 * TS interface in sport-mobile/src/services/firebaseService.ts exactly —
 * the controller only reads the block relevant to the match's sport.
 */
class LiveScoreUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Two things HTML form submission doesn't give us for free, both fixed
     * here rather than downstream:
     *
     * 1. Blank number inputs (e.g. an unset badminton set, wickets before
     *    the first fall) submit as `""`, not `null` — and Laravel's
     *    `nullable` rule only exempts an attribute that is exactly `null`,
     *    so `""` would otherwise fail `integer`/`numeric`.
     * 2. The `integer`/`numeric` rules only *validate* that a string looks
     *    numeric — they don't cast it. Left uncast, `$request->validated()`
     *    hands back `"87"` (string) for `cricket_score.runs`, which
     *    FirebaseLiveScoreService::encodeValue() then writes to Firestore
     *    as a `stringValue` instead of `integerValue` — harmless for
     *    display (template literals stringify either way) but breaks
     *    arithmetic on the mobile side (`"15" + 1 === "151"`, not `16`).
     *
     * Casting here (before validation) fixes both: `""` becomes `null`
     * (passes `nullable`), and numeric strings become real int/float PHP
     * values that flow untouched through `validated()`.
     */
    protected function prepareForValidation(): void
    {
        if (is_array($this->input('racket_scores'))) {
            $this->merge(['racket_scores' => $this->normalizeBlock($this->input('racket_scores'), intFields: [
                'set1_home', 'set1_away', 'set2_home', 'set2_away', 'set3_home', 'set3_away',
                'current_set', 'points_home', 'points_away',
            ])]);
        }

        // cricket_score is intentionally NOT normalized here — the cricket
        // scorer (Admin\LiveScoreController's cricket partial) posts real
        // JSON with already-correct int/float types for its deeply nested
        // team_a/team_b structure, and rules() below only validates shape
        // (array-or-not) rather than every leaf, so there's nothing to cast.

        if (is_array($this->input('team_score'))) {
            $block = $this->normalizeBlock($this->input('team_score'), intFields: [
                'home_total', 'away_total', 'outs_home', 'outs_away',
            ]);

            foreach (['home_sets_or_breakdown', 'away_sets_or_breakdown'] as $listKey) {
                if (is_array($block[$listKey] ?? null)) {
                    $block[$listKey] = array_map(
                        fn ($value) => $value === '' || $value === null ? null : (int) $value,
                        $block[$listKey]
                    );
                }
            }

            $this->merge(['team_score' => $block]);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $intFields
     * @param  list<string>  $floatFields
     * @return array<string, mixed>
     */
    private function normalizeBlock(array $data, array $intFields = [], array $floatFields = []): array
    {
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            } elseif ($value === null) {
                continue;
            } elseif (in_array($key, $intFields, true)) {
                $data[$key] = (int) $value;
            } elseif (in_array($key, $floatFields, true)) {
                $data[$key] = (float) $value;
            }
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Badminton
            'racket_scores' => ['sometimes', 'array'],
            'racket_scores.set1_home' => ['nullable', 'integer', 'min:0'],
            'racket_scores.set1_away' => ['nullable', 'integer', 'min:0'],
            'racket_scores.set2_home' => ['nullable', 'integer', 'min:0'],
            'racket_scores.set2_away' => ['nullable', 'integer', 'min:0'],
            'racket_scores.set3_home' => ['nullable', 'integer', 'min:0'],
            'racket_scores.set3_away' => ['nullable', 'integer', 'min:0'],
            'racket_scores.current_set' => ['nullable', 'integer', 'min:1', 'max:3'],
            'racket_scores.points_home' => ['nullable', 'integer', 'min:0'],
            'racket_scores.points_away' => ['nullable', 'integer', 'min:0'],

            // Cricket — full two-innings match scorer (Admin\LiveScoreController's
            // cricket partial: toss, teamA/teamB each with their own batting
            // lineup + bowling figures, innings transition, result). The
            // structure is deep (per-batter/per-bowler stat rows, ball-by-ball
            // history) and entirely admin-JS-authored — rather than enumerate
            // every leaf, validate the shape (array-or-not) and let the
            // schemaless Firestore/MySQL JSON storage carry the rest, same as
            // the rest of this app's `live_score` column already does.
            'cricket_score' => ['sometimes', 'array'],
            'cricket_score.team_a_name' => ['nullable', 'string', 'max:150'],
            'cricket_score.team_b_name' => ['nullable', 'string', 'max:150'],
            'cricket_score.toss_winner' => ['nullable', 'string', 'in:home,away'],
            'cricket_score.toss_choice' => ['nullable', 'string', 'in:bat,bowl'],
            'cricket_score.innings' => ['nullable', 'integer', 'in:1,2'],
            'cricket_score.team_a' => ['nullable', 'array'],
            'cricket_score.team_b' => ['nullable', 'array'],
            'cricket_score.result' => ['nullable', 'array'],
            'cricket_score.summary' => ['nullable', 'string', 'max:255'],

            // Volleyball
            'team_score' => ['sometimes', 'array'],
            'team_score.home_total' => ['nullable', 'integer', 'min:0'],
            'team_score.away_total' => ['nullable', 'integer', 'min:0'],
            'team_score.home_sets_or_breakdown' => ['nullable', 'array'],
            'team_score.home_sets_or_breakdown.*' => ['nullable', 'integer', 'min:0'],
            'team_score.away_sets_or_breakdown' => ['nullable', 'array'],
            'team_score.away_sets_or_breakdown.*' => ['nullable', 'integer', 'min:0'],
            'team_score.outs_home' => ['nullable', 'integer', 'min:0'],
            'team_score.outs_away' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
