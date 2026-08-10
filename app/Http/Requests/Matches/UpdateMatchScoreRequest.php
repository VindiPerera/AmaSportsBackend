<?php

namespace App\Http\Requests\Matches;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMatchScoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Endpoint exists now so an admin panel can start writing to it
        // later; controller also double-checks the caller is an admin.
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'string', 'in:upcoming,live,finished'],
            'live_score' => ['sometimes', 'nullable', 'array'],
            // No youtube_stream_url here — it's only ever set via the paid
            // $5 unlock flow (Admin\StreamAccessController::updateUrl).
            // Accepting it on this generic endpoint would let an admin set
            // it for free.
        ];
    }
}
