<?php

namespace App\Http\Requests\Admin;

use App\Services\Achievements\AchievementService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AchievementRequest extends FormRequest
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
        $metricKeys = array_keys(app(AchievementService::class)->allAvailableMetrics());

        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'metric_key' => ['required', Rule::in($metricKeys)],
            'threshold' => ['required', 'integer', 'min:1'],
            'icon' => ['required', 'string', 'max:50'],
            'color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
