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

            'teams' => ['sometimes', 'array'],
            'teams.*' => ['string', 'max:255'],

            'batting' => ['sometimes', 'array'],
            'batting.*.format_id' => ['required', 'integer', 'exists:formats,id'],
            'batting.*.age_category_id' => ['required', 'integer', 'exists:age_categories,id'],
            'batting.*.match_category_id' => ['required', 'integer', 'exists:match_categories,id'],
            'batting.*.cricket_match_type_id' => ['nullable', 'integer', 'exists:cricket_match_types,id'],
            'batting.*.matches' => ['nullable', 'integer', 'min:0'],
            'batting.*.won' => ['nullable', 'integer', 'min:0'],
            'batting.*.lost' => ['nullable', 'integer', 'min:0'],
            'batting.*.innings' => ['nullable', 'integer', 'min:0'],
            'batting.*.not_out' => ['nullable', 'integer', 'min:0'],
            'batting.*.runs' => ['nullable', 'integer', 'min:0'],
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

            'bowling' => ['sometimes', 'array'],
            'bowling.*.format_id' => ['required', 'integer', 'exists:formats,id'],
            'bowling.*.age_category_id' => ['required', 'integer', 'exists:age_categories,id'],
            'bowling.*.match_category_id' => ['required', 'integer', 'exists:match_categories,id'],
            'bowling.*.cricket_match_type_id' => ['nullable', 'integer', 'exists:cricket_match_types,id'],
            'bowling.*.matches' => ['nullable', 'integer', 'min:0'],
            'bowling.*.innings' => ['nullable', 'integer', 'min:0'],
            'bowling.*.balls' => ['nullable', 'integer', 'min:0'],
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
            'recent_matches.*.match_date' => ['nullable', 'date'],
            'recent_matches.*.opponent' => ['nullable', 'string', 'max:255'],
            'recent_matches.*.played_xi' => ['nullable', 'boolean'],
            'recent_matches.*.runs' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.balls' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.fours' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.sixes' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.overs' => ['nullable', 'numeric'],
            'recent_matches.*.maidens' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.wickets' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.catches' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.stumpings' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
