<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class StoreKabadiProfileRequest extends FormRequest
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
            'weight' => ['nullable', 'string', 'max:50'],
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
            'career_stats.*.cbp' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.raids' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.successful_raids' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.unsuccessful_raids' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.raid_touch_point' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.raid_bonus_point' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.tackles' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.successful_tackles' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.unsuccessful_tackles' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.empty_raids' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.yellow_cards' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.green_cards' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.red_cards' => ['nullable', 'integer', 'min:0'],

            'recent_matches' => ['sometimes', 'array'],
            'recent_matches.*.match_date' => ['nullable', 'date'],
            'recent_matches.*.opponent' => ['nullable', 'string', 'max:255'],
            'recent_matches.*.venue' => ['nullable', 'string', 'max:255'],
            'recent_matches.*.win' => ['nullable', 'boolean'],
            'recent_matches.*.lost' => ['nullable', 'boolean'],
            'recent_matches.*.cbp' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.raids' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.successful_raids' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.unsuccessful_raids' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.raid_touch_point' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.raid_bonus_point' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.tackles' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.successful_tackles' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.unsuccessful_tackles' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.empty_raids' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.yellow_cards' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.green_cards' => ['nullable', 'integer', 'min:0'],
            'recent_matches.*.red_cards' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
