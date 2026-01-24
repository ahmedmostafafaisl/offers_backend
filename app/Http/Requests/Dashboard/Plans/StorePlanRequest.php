<?php

namespace App\Http\Requests\Dashboard\Plans;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'monthly_price' => 'required|numeric|min:0',
            'annually_price' => 'required|numeric|min:0',

            'features' => 'nullable|array',
            'features.*.id' => 'nullable|integer|exists:plan_features,id',
            'features.*.name' => 'required_with:features|string|max:255',
            'features.*.description' => 'nullable|string',
        ];
    }
}
