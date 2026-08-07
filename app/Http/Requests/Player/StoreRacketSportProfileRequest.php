<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class StoreRacketSportProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Shared Tennis / Badminton / Table Tennis form (spec Phase 2 §B3) —
     * `sport_id` says which of the three this submission is for.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'sport_id' => ['required', 'integer', 'exists:sports,id'],
            'born' => ['nullable', 'date'],
            'age' => ['nullable', 'integer', 'min:1', 'max:120'],
            'height' => ['nullable', 'string', 'max:50'],
            'dominant_hand' => ['nullable', 'string', 'in:right,left'],
            'weight' => ['nullable', 'string', 'max:50'],
            'current_ranking' => ['nullable', 'string', 'max:100'],
            'college_university' => ['nullable', 'string', 'max:255'],

            'teams' => ['sometimes', 'array'],
            'teams.*' => ['string', 'max:255'],

            'career_stats' => ['sometimes', 'array'],
            'career_stats.*.category' => ['required', 'string', 'in:single,double,mix_double'],
            'career_stats.*.format_id' => ['required', 'integer', 'exists:formats,id'],
            'career_stats.*.age_category_id' => ['required', 'integer', 'exists:age_categories,id'],
            'career_stats.*.match_category_id' => ['required', 'integer', 'exists:match_categories,id'],
            'career_stats.*.matches' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.win' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.lost' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.set_win' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.set_lost' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.quarter_final' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.semi_final' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.third_place' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.second_place' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.champion' => ['nullable', 'integer', 'min:0'],

            'recent_matches' => ['sometimes', 'array'],
            'recent_matches.*.match_date' => ['nullable', 'date'],
            'recent_matches.*.opponent' => ['nullable', 'string', 'max:255'],
            'recent_matches.*.win' => ['nullable', 'boolean'],
            'recent_matches.*.lost' => ['nullable', 'boolean'],
            'recent_matches.*.set_1' => ['nullable', 'string', 'max:10'],
            'recent_matches.*.set_2' => ['nullable', 'string', 'max:10'],
            'recent_matches.*.set_3' => ['nullable', 'string', 'max:10'],
            'recent_matches.*.set_4' => ['nullable', 'string', 'max:10'],
            'recent_matches.*.set_5' => ['nullable', 'string', 'max:10'],
        ];
    }
}
