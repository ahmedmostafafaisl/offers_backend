<?php

namespace App\Http\Requests\Dashboard\Plans;

use App\Models\Plan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Shared rules for creating / updating a plan from the dashboard.
 *
 * The admin ticks one or more billing types (quarterly / semi_annual / annual)
 * and enters a price for each ticked type. Prices of un-ticked types are stored as NULL
 * which means "this type is not offered by the plan".
 */
abstract class PlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $types = $this->input('types', []);
        $types = is_array($types) ? $types : [];

        $priceRule = fn(string $type) => [
            Rule::requiredIf(in_array($type, $types, true)),
            'nullable',
            'numeric',
            'min:0',
        ];

        $rules = [
            'name' => 'required|string|max:255',

            'types' => 'required|array|min:1',
            'types.*' => ['string', Rule::in(array_keys(Plan::TYPES))],

            'features' => 'nullable|array',
            'features.*.id' => 'nullable|integer|exists:plan_features,id',
            'features.*.name' => 'nullable|string|max:255',
            'features.*.description' => 'nullable|string',
        ];

        foreach (Plan::TYPES as $type => $column) {
            $rules[$column] = $priceRule($type);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'types.required' => __('dashboard.plan_types_required'),
            'types.min' => __('dashboard.plan_types_required'),
        ];
    }

    /**
     * Return only the plan columns, with un-selected types forced to NULL.
     */
    public function validated($key = null, $default = null)
    {
        $data = parent::validated();
        $selected = $data['types'] ?? [];
        unset($data['types']);

        foreach (Plan::TYPES as $type => $column) {
            $data[$column] = in_array($type, $selected, true) ? $data[$column] : null;
        }

        return $key ? data_get($data, $key, $default) : $data;
    }
}
