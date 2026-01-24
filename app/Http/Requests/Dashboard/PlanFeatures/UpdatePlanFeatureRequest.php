<?php

namespace App\Http\Requests\Dashboard\PlanFeatures;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanFeatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plan_id' => 'required|exists:plans,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
