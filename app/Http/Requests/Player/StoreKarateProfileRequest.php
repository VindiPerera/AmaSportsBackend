<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class StoreKarateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * `weight_category` / `age_category` in the stat rows are free-text —
     * no reference list was given for them (see Phase 3 spec §C7 notes).
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'born' => ['nullable', 'date'],
            'age' => ['nullable', 'integer', 'min:1', 'max:120'],
            'height' => ['nullable', 'string', 'max:50'],
            'weight' => ['nullable', 'string', 'max:50'],
            'player_style_id' => ['nullable', 'integer', 'exists:karate_styles,id'],
            'current_ranking' => ['nullable', 'string', 'max:100'],
            'college_university' => ['nullable', 'string', 'max:255'],

            'teams' => ['sometimes', 'array'],
            'teams.*' => ['string', 'max:255'],

            'career_stats' => ['sometimes', 'array'],
            'career_stats.*.format_id' => ['required', 'integer', 'exists:formats,id'],
            'career_stats.*.age_category_id' => ['required', 'integer', 'exists:age_categories,id'],
            'career_stats.*.match_category_id' => ['required', 'integer', 'exists:match_categories,id'],
            'career_stats.*.matches' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.fights' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.win' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.lost' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.stats' => ['nullable', 'string', 'max:255'],
            'career_stats.*.weight_category' => ['nullable', 'string', 'max:100'],
            'career_stats.*.age_category' => ['nullable', 'string', 'max:100'],
            'career_stats.*.third_place' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.second_place' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.champion' => ['nullable', 'integer', 'min:0'],

            'recent_matches' => ['sometimes', 'array'],
            'recent_matches.*.match_date' => ['nullable', 'date'],
            'recent_matches.*.opponent' => ['nullable', 'string', 'max:255'],
            'recent_matches.*.venue' => ['nullable', 'string', 'max:255'],
            'recent_matches.*.win' => ['nullable', 'boolean'],
            'recent_matches.*.lost' => ['nullable', 'boolean'],
            'recent_matches.*.stats' => ['nullable', 'string', 'max:255'],
            'recent_matches.*.weight_category' => ['nullable', 'string', 'max:100'],
            'recent_matches.*.age_category' => ['nullable', 'string', 'max:100'],
            'recent_matches.*.place' => ['nullable', 'string', 'max:50'],
        ];
    }
}
