<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class StoreNetBallProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'born' => ['nullable', 'date'],
            'age' => ['nullable', 'integer', 'min:1', 'max:120'],
            'height' => ['nullable', 'string', 'max:50'],
            'dominant_hand' => ['nullable', 'string', 'in:right,left'],
            'player_position' => ['nullable', 'string', 'max:100'],
            'college_university' => ['nullable', 'string', 'max:255'],

            'teams' => ['sometimes', 'array'],
            'teams.*' => ['string', 'max:255'],

            'career_stats' => ['sometimes', 'array'],
            'career_stats.*.format_id' => ['required', 'integer', 'exists:formats,id'],
            'career_stats.*.age_category_id' => ['required', 'integer', 'exists:age_categories,id'],
            'career_stats.*.match_category_id' => ['required', 'integer', 'exists:match_categories,id'],
            'career_stats.*.matches' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.matches_won' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.matches_lost' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.goals' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.attempts' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.goal_accuracy' => ['nullable', 'numeric'],
            'career_stats.*.result_won' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.result_lost' => ['nullable', 'integer', 'min:0'],

            'recent_matches' => ['sometimes', 'array'],
            'recent_matches.*.match_date' => ['nullable', 'date'],
            'recent_matches.*.opponent' => ['nullable', 'string', 'max:255'],
            'recent_matches.*.venue' => ['nullable', 'string', 'max:255'],
            'recent_matches.*.goals' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.attempts' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.goal_accuracy' => ['nullable', 'numeric'],
            'recent_matches.*.win' => ['nullable', 'boolean'],
            'recent_matches.*.lost' => ['nullable', 'boolean'],
        ];
    }
}
