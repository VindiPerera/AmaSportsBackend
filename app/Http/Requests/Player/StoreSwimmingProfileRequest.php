<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class StoreSwimmingProfileRequest extends FormRequest
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
            'college_university' => ['nullable', 'string', 'max:255'],

            'teams' => ['sometimes', 'array'],
            'teams.*' => ['string', 'max:255'],

            'personal_bests' => ['sometimes', 'array'],
            'personal_bests.*.swimming_event_id' => ['required', 'integer', 'exists:swimming_events,id'],
            'personal_bests.*.personal_best' => ['nullable', 'string', 'max:50'],

            'career_stats' => ['sometimes', 'array'],
            'career_stats.*.format_id' => ['required', 'integer', 'exists:formats,id'],
            'career_stats.*.age_category_id' => ['required', 'integer', 'exists:age_categories,id'],
            'career_stats.*.match_category_id' => ['required', 'integer', 'exists:match_categories,id'],
            'career_stats.*.swimming_event_id' => ['required', 'integer', 'exists:swimming_events,id'],
            'career_stats.*.matches' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.current_time' => ['nullable', 'string', 'max:20'],
            'career_stats.*.third_place' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.second_place' => ['nullable', 'integer', 'min:0'],
            'career_stats.*.champion' => ['nullable', 'integer', 'min:0'],

            'recent_events' => ['sometimes', 'array'],
            'recent_events.*.event_date' => ['nullable', 'date'],
            'recent_events.*.age_category_id' => ['required', 'integer', 'exists:age_categories,id'],
            'recent_events.*.match_category_id' => ['required', 'integer', 'exists:match_categories,id'],
            'recent_events.*.matches' => ['nullable', 'integer', 'min:0'],
            'recent_events.*.swimming_event_id' => ['required', 'integer', 'exists:swimming_events,id'],
            'recent_events.*.performance_time' => ['nullable', 'string', 'max:20'],
            'recent_events.*.place' => ['nullable', 'string', 'max:50'],
        ];
    }
}
