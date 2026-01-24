<?php

namespace App\Http\Requests\Plan;

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
            'name' => 'nullable|string|max:255',
            'monthly_price' => 'nullable|numeric|min:0',
            'annually_price' => 'nullable|numeric|min:0',
            'features' => 'nullable|array',
            'features.*.name' => 'required_with:features|string|max:255',
            'features.*.description' => 'nullable|string',
        ];
    }
}
