<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class AddPlayerSportRequest extends FormRequest
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
            'sport_id' => ['required', 'integer', 'exists:sports,id'],
        ];
    }
}
