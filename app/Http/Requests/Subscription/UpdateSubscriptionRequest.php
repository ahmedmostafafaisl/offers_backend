<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|exists:users,id',
            'plan_id' => 'sometimes|exists:plans,id',
            'start_date' => 'sometimes|date',
            'expiration_date' => 'sometimes|date|after:start_date',
            'type' => 'sometimes|in:quarterly,semi_annual,annual',
        ];
    }
}
