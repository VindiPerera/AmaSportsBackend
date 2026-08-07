<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class StoreFootballProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Goalkeeper columns (clean sheets / goals conceded) are always
     * accepted but nullable — client-confirmed to leave them optional
     * rather than hide them for non-goalkeepers.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'born' => ['nullable', 'date'],
            'age' => ['nullable', 'integer', 'min:1', 'max:120'],
            'height' => ['nullable', 'string', 'max:50'],
            'dominant_leg' => ['nullable', 'string', 'in:right,left'],
            'player_position' => ['nullable', 'string', 'max:100'],
            'college_university' => ['nullable', 'string', 'max:255'],

            'teams' => ['sometimes', 'array'],
            'teams.*' => ['string', 'max:255'],

            'career_stats' => ['sometimes', 'array'],
            'career_stats.*.format_id' => ['required', 'integer', 'exists:formats,id'],
            'career_stats.*.age_category_id' => ['required', 'integer', 'exists:age_categories,id'],
            'career_stats.*.match_category_id' => ['required', 'integer', 'exists:match_categories,id'],
            'career_stats.*.matches' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.win' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.lost' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.goals' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.assists' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.defensive_actions' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.goalkeeper_clean_sheets' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.goalkeeper_goals_conceded' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.yellow_card' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.red_card' => ['nullable', 'integer', 'min:0'],

            'recent_matches' => ['sometimes', 'array'],
            'recent_matches.*.match_date' => ['nullable', 'date'],
            'recent_matches.*.opponent' => ['nullable', 'string', 'max:255'],
            'recent_matches.*.venue' => ['nullable', 'string', 'max:255'],
            'recent_matches.*.win' => ['nullable', 'boolean'],
            'recent_matches.*.lost' => ['nullable', 'boolean'],
            'recent_matches.*.goals' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.assists' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.defensive_actions' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.goalkeeper_clean_sheets' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.goalkeeper_goals_conceded' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.yellow_card' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.red_card' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
