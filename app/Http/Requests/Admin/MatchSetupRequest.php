<?php

namespace App\Http\Requests\Admin;

use App\Models\Sport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MatchSetupRequest extends FormRequest
{
    public function authorize(): bool
    {
        // EnsureAdmin already gates every route this request is used on.
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'sport_id' => [
                'required',
                Rule::exists('sports', 'id')->where(
                    fn ($query) => $query->whereIn('slug', Sport::ADMIN_LIVE_SCORE_SLUGS)
                ),
            ],
            'format_id' => ['nullable', 'exists:formats,id'],
            'age_category_id' => ['nullable', 'exists:age_categories,id'],
            'match_category_id' => ['nullable', 'exists:match_categories,id'],
            'scheduled_at' => ['required', 'date'],
            'venue' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:100'],
            'contact_mobile' => ['nullable', 'string', 'max:30'],
            'contact_whatsapp' => ['nullable', 'string', 'max:30'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            // No youtube_stream_url here — set only via the paid $5 unlock
            // flow (Admin\StreamAccessController::updateUrl).

            'home_team_id' => ['nullable', 'exists:teams,id'],
            'home_team_name' => ['required_without:home_team_id', 'nullable', 'string', 'max:255'],
            'home_team_country' => ['nullable', 'string', 'max:100'],
            'home_team_school_academy' => ['nullable', 'string', 'max:255'],
            'home_team_club' => ['nullable', 'string', 'max:255'],
            'home_logo' => ['nullable', 'image', 'max:2048'],
            'home_photo' => ['nullable', 'image', 'max:2048'],

            'away_team_id' => ['nullable', 'exists:teams,id'],
            'away_team_name' => ['required_without:away_team_id', 'nullable', 'string', 'max:255'],
            'away_team_country' => ['nullable', 'string', 'max:100'],
            'away_team_school_academy' => ['nullable', 'string', 'max:255'],
            'away_team_club' => ['nullable', 'string', 'max:255'],
            'away_logo' => ['nullable', 'image', 'max:2048'],
            'away_photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
