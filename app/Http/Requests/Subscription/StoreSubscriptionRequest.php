<?php

namespace App\Http\Requests\Subscription;

use App\Http\Requests\Concerns\ChecksPlanOffersType;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    use ChecksPlanOffersType;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_type' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'type' => 'required|in:quarterly,semi_annual,annual',
        ];
    }
}
