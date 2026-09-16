<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class StoreCricketProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Saves the whole Cricket profile form (overview + all repeatable
     * tables) in one request — see spec 6.2. Every stat-row numeric field
     * is nullable so a player can leave columns blank rather than being
     * forced to enter 0 everywhere.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'born' => ['nullable', 'date'],
            'age' => ['nullable', 'integer', 'min:1', 'max:120'],
            'batting_style' => ['nullable', 'string', 'max:100'],
            'bowling_style' => ['nullable', 'string', 'max:100'],
            'playing_role' => ['nullable', 'string', 'max:100'],
            'height' => ['nullable', 'string', 'max:50'],
            'college_university' => ['nullable', 'string', 'max:255'],

            // Career-to-date bowling breakdown — see Phase 7 migration note.
            // Keyed by pitching_lines.id / ball_types.id (as strings, since
            // JSON object keys are always strings); values are ball counts.
            'pitching_line_breakdown' => ['nullable', 'array'],
            'pitching_line_breakdown.*' => ['nullable', 'integer', 'min:0'],
            'ball_type_breakdown' => ['nullable', 'array'],
            'ball_type_breakdown.*' => ['nullable', 'integer', 'min:0'],

            'teams' => ['sometimes', 'array'],
            'teams.*' => ['string', 'max:255'],

            'batting' => ['sometimes', 'array'],
            // Cricket's own Category/Division lookups (see CricketCategory/
            // CricketDivision) — Division is nullable: only Categories
            // U12...U19 have one (see CareerStatAddModal), everything else
            // is Category-only.
            'batting.*.format_id' => ['nullable', 'integer', 'exists:cricket_divisions,id'],
            'batting.*.age_category_id' => ['required', 'integer', 'exists:cricket_categories,id'],
            // "Format" (cricket_match_type_id) and "Match Category" are no
            // longer collected in the Match Details step — both nullable so
            // new entries save with neither filled in.
            'batting.*.match_category_id' => ['nullable', 'integer', 'exists:match_categories,id'],
            'batting.*.cricket_match_type_id' => ['nullable', 'integer', 'exists:cricket_match_types,id'],
            'batting.*.year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'batting.*.matches' => ['nullable', 'integer', 'min:0'],
            'batting.*.won' => ['nullable', 'integer', 'min:0'],
            'batting.*.lost' => ['nullable', 'integer', 'min:0'],
            'batting.*.innings' => ['nullable', 'integer', 'min:0'],
            'batting.*.not_out' => ['nullable', 'integer', 'min:0'],
            'batting.*.runs' => ['nullable', 'integer', 'min:0'],
            'batting.*.balls' => ['nullable', 'integer', 'min:0'],
            'batting.*.hs' => ['nullable', 'string', 'max:20'],
            'batting.*.average' => ['nullable', 'numeric'],
            'batting.*.best' => ['nullable', 'integer', 'min:0'],
            'batting.*.sr' => ['nullable', 'numeric'],
            'batting.*.hundreds' => ['nullable', 'integer', 'min:0'],
            'batting.*.fifties' => ['nullable', 'integer', 'min:0'],
            'batting.*.fours' => ['nullable', 'integer', 'min:0'],
            'batting.*.sixes' => ['nullable', 'integer', 'min:0'],
            'batting.*.catches' => ['nullable', 'integer', 'min:0'],
            'batting.*.stumpings' => ['nullable', 'integer', 'min:0'],
            'batting.*.run_outs' => ['nullable', 'integer', 'min:0'],
            'batting.*.direct_hits' => ['nullable', 'integer', 'min:0'],
            'batting.*.runs_saved' => ['nullable', 'integer', 'min:0'],
            'batting.*.runs_giving' => ['nullable', 'integer', 'min:0'],
            'batting.*.stumps_missing' => ['nullable', 'integer', 'min:0'],

            'bowling' => ['sometimes', 'array'],
            'bowling.*.format_id' => ['nullable', 'integer', 'exists:cricket_divisions,id'],
            'bowling.*.age_category_id' => ['required', 'integer', 'exists:cricket_categories,id'],
            'bowling.*.match_category_id' => ['nullable', 'integer', 'exists:match_categories,id'],
            'bowling.*.cricket_match_type_id' => ['nullable', 'integer', 'exists:cricket_match_types,id'],
            'bowling.*.year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'bowling.*.matches' => ['nullable', 'integer', 'min:0'],
            'bowling.*.innings' => ['nullable', 'integer', 'min:0'],
            'bowling.*.balls' => ['nullable', 'integer', 'min:0'],
            'bowling.*.dot_balls' => ['nullable', 'integer', 'min:0'],
            'bowling.*.wide_balls' => ['nullable', 'integer', 'min:0'],
            'bowling.*.no_balls' => ['nullable', 'integer', 'min:0'],
            'bowling.*.runs' => ['nullable', 'integer', 'min:0'],
            'bowling.*.wickets' => ['nullable', 'integer', 'min:0'],
            'bowling.*.bbi' => ['nullable', 'string', 'max:20'],
            'bowling.*.bbm' => ['nullable', 'string', 'max:20'],
            'bowling.*.average' => ['nullable', 'numeric'],
            'bowling.*.economy' => ['nullable', 'numeric'],
            'bowling.*.sr' => ['nullable', 'numeric'],
            'bowling.*.four_w' => ['nullable', 'integer', 'min:0'],
            'bowling.*.five_w' => ['nullable', 'integer', 'min:0'],
            'bowling.*.ten_w' => ['nullable', 'integer', 'min:0'],

            'recent_matches' => ['sometimes', 'array'],
            // "Format" and "Category" — same lookups the Career Stats tables
            // use (see batting.*.age_category_id above), so this match's
            // figures merge into the right aggregate row.
            'recent_matches.*.age_category_id' => ['nullable', 'integer', 'exists:cricket_categories,id'],
            'recent_matches.*.format_id' => ['nullable', 'integer', 'exists:cricket_divisions,id'],
            'recent_matches.*.match_date' => ['nullable', 'date'],
            'recent_matches.*.opponent' => ['nullable', 'string', 'max:255'],
            'recent_matches.*.ground' => ['nullable', 'string', 'max:255'],
            'recent_matches.*.year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'recent_matches.*.played_xi' => ['nullable', 'boolean'],
            'recent_matches.*.batting_innings' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.runs' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.balls' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.not_out' => ['nullable', 'boolean'],
            'recent_matches.*.hs' => ['nullable', 'string', 'max:20'],
            'recent_matches.*.fours' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.sixes' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.hundreds' => ['nullable', 'boolean'],
            'recent_matches.*.fifties' => ['nullable', 'boolean'],
            'recent_matches.*.overs' => ['nullable', 'numeric'],
            'recent_matches.*.maidens' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.bowling_innings' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.bowling_balls' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.bowling_runs' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.wickets' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.bbi' => ['nullable', 'string', 'max:20'],
            'recent_matches.*.bbm' => ['nullable', 'string', 'max:20'],
            'recent_matches.*.three_w' => ['nullable', 'boolean'],
            'recent_matches.*.four_w' => ['nullable', 'boolean'],
            'recent_matches.*.five_w' => ['nullable', 'boolean'],
            'recent_matches.*.ten_w' => ['nullable', 'boolean'],
            'recent_matches.*.catches' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.stumpings' => ['nullable', 'integer', 'min:0'],
            // Set via the separate /player/cricket-profile/score-sheet
            // upload endpoint, which returns this URL for the client to
            // carry along on the next bulk save — not a file upload itself.
            // Deliberately nullable even though the mobile form now requires
            // it for any match added through AddCricketMatchModal: this
            // whole array is re-validated (and re-saved) on every profile
            // save, including matches added before this field existed —
            // making it required here would permanently block those players
            // from ever saving their profile again, since there's no way to
            // attach a photo to a match after the fact.
            'recent_matches.*.score_sheet_url' => ['nullable', 'string', 'max:500'],

            // Repeatable "Drop Catches" rows — Phase 7 spec §2. Format/Age/
            // Category are optional context (unlike the batting/bowling
            // tables, where they're required), since a player logging a
            // single drop-catch event may not want to fill out the full
            // career-bucket context every time.
            'drop_catches' => ['sometimes', 'array'],
            'drop_catches.*.format_id' => ['nullable', 'integer', 'exists:formats,id'],
            'drop_catches.*.age_category_id' => ['nullable', 'integer', 'exists:age_categories,id'],
            'drop_catches.*.match_category_id' => ['nullable', 'integer', 'exists:match_categories,id'],
            'drop_catches.*.field_position_id' => ['nullable', 'integer', 'exists:field_positions,id'],
            'drop_catches.*.drop_reason_id' => ['nullable', 'integer', 'exists:drop_reasons,id'],
        ];
    }
}
