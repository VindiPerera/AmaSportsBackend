<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class StoreSoftBallCricketProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Saves the whole Soft Ball Cricket profile form (overview + all
     * repeatable tables) in one request. Every stat-row numeric field is
     * nullable so a player can leave columns blank rather than being forced
     * to enter 0 everywhere.
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
            'batting.*.matches' => ['nullable', 'integer', 'min:0'],
            'batting.*.runs' => ['nullable', 'integer', 'min:0'],
            'batting.*.innings' => ['nullable', 'integer', 'min:0'],
            'batting.*.highest' => ['nullable', 'string', 'max:20'],
            'batting.*.not_out' => ['nullable', 'integer', 'min:0'],
            'batting.*.hundreds' => ['nullable', 'integer', 'min:0'],
            'batting.*.fifties' => ['nullable', 'integer', 'min:0'],
            'batting.*.sixes' => ['nullable', 'integer', 'min:0'],
            'batting.*.fours' => ['nullable', 'integer', 'min:0'],
            'batting.*.catches' => ['nullable', 'integer', 'min:0'],
            'batting.*.stumpings' => ['nullable', 'integer', 'min:0'],
            'batting.*.won' => ['nullable', 'integer', 'min:0'],
            'batting.*.lost' => ['nullable', 'integer', 'min:0'],
            'batting.*.tied' => ['nullable', 'integer', 'min:0'],

            'bowling' => ['sometimes', 'array'],
            'bowling.*.matches' => ['nullable', 'integer', 'min:0'],
            'bowling.*.balls' => ['nullable', 'integer', 'min:0'],
            'bowling.*.runs' => ['nullable', 'integer', 'min:0'],
            'bowling.*.wickets' => ['nullable', 'integer', 'min:0'],
            'bowling.*.average' => ['nullable', 'numeric', 'min:0'],
            'bowling.*.economy' => ['nullable', 'numeric', 'min:0'],
            'bowling.*.three_w' => ['nullable', 'integer', 'min:0'],
            'bowling.*.four_w' => ['nullable', 'integer', 'min:0'],
            'bowling.*.five_w' => ['nullable', 'integer', 'min:0'],
            'bowling.*.career_best' => ['nullable', 'string', 'max:20'],

            'recent_matches' => ['sometimes', 'array'],
            'recent_matches.*.match_date' => ['nullable', 'date'],
            'recent_matches.*.opponent' => ['nullable', 'string', 'max:255'],
            'recent_matches.*.won' => ['nullable', 'boolean'],
            'recent_matches.*.lost' => ['nullable', 'boolean'],
            'recent_matches.*.runs' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.balls' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.average' => ['nullable', 'string', 'max:20'],
            'recent_matches.*.bowling_balls' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.bowling_runs' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.wickets' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.catches' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.stumpings' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
