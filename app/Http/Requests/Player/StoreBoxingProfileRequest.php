<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoxingProfileRequest extends FormRequest
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
            'weight_class_id' => ['nullable', 'integer', 'exists:boxing_weight_classes,id'],
            'current_ranking' => ['nullable', 'string', 'max:100'],
            'college_university' => ['nullable', 'string', 'max:255'],

            'teams' => ['sometimes', 'array'],
            'teams.*' => ['string', 'max:255'],

            'career_stats' => ['sometimes', 'array'],
            'career_stats.*.format_id' => ['required', 'integer', 'exists:formats,id'],
            'career_stats.*.age_category_id' => ['required', 'integer', 'exists:age_categories,id'],
            'career_stats.*.match_category_id' => ['required', 'integer', 'exists:match_categories,id'],
            'career_stats.*.weight_class_id' => ['nullable', 'integer', 'exists:boxing_weight_classes,id'],
            'career_stats.*.matches' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.win' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.lost' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.third_place' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.second_place' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.champion' => ['nullable', 'integer', 'min:0'],

            'recent_fights' => ['sometimes', 'array'],
            'recent_fights.*.fight_date' => ['nullable', 'date'],
            'recent_fights.*.opponent' => ['nullable', 'string', 'max:255'],
            'recent_fights.*.venue' => ['nullable', 'string', 'max:255'],
            'recent_fights.*.weight_class_id' => ['nullable', 'integer', 'exists:boxing_weight_classes,id'],
            'recent_fights.*.win' => ['nullable', 'boolean'],
            'recent_fights.*.lost' => ['nullable', 'boolean'],
            'recent_fights.*.place' => ['nullable', 'string', 'max:50'],
        ];
    }
}
