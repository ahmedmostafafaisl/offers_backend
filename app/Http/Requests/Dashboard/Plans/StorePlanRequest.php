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
            'quarterly_price' => 'nullable|numeric|min:0',
            'semi_annual_price' => 'nullable|numeric|min:0',
            'annual_price' => 'nullable|numeric|min:0',

            'features' => 'nullable|array',
            'features.*.id' => 'nullable|integer|exists:plan_features,id',
            'features.*.name' => 'nullable:features|string|max:255',
            'features.*.description' => 'nullable|string',
        ];
    }
}
