<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriptionCountryPriceRequest extends FormRequest
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
        // Editing an existing row keeps its own country out of the
        // uniqueness check (see SubscriptionPriceController::update) —
        // otherwise re-saving the same row with an unchanged country would
        // fail against itself.
        $priceId = $this->route('price')?->id;

        return [
            'country' => [
                'required',
                'string',
                Rule::in(config('countries')),
                Rule::unique('subscription_country_prices', 'country')->ignore($priceId),
            ],
            'amount' => ['required', 'numeric', 'min:0', 'max:9999.99'],
        ];
    }
}
