<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MatchPlayerRequest extends FormRequest
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
            'side' => ['required', Rule::in(['home', 'away'])],
            'id_number' => ['nullable', 'string', 'max:50'],
            'full_name' => ['required', 'string', 'max:150'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
