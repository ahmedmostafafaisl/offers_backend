<?php

namespace App\Http\Requests\Dashboard\Subscriptions;

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
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
            'expiration_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:quarterly,semi_annual,annual',
            'is_active' => 'nullable|boolean',
        ];
    }
}
