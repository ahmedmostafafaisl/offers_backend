<?php

namespace App\Http\Requests\Dashboard\Plans;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'monthly_price' => 'nullable|numeric|min:0',
            'annually_price' => 'nullable|numeric|min:0',
        ];
    }
}
